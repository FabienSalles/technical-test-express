# Test technique

Liste des éléments réalisés, des remarques et du processus utilisé pour résoudre le test technique.

## Processus

1. Lecture du README
2. Installation du projet
3. Parcours du code source. Dans l'ordre, lecture :
    * du makefile
    * du fichier commposer.json
    * du fichier docker-compose.yml
    * du fichier run_test.sh
4. Lancement des TUs
5. Tout est vert => lecture des fichiers de tests

### Class RegisterCustomerHandlerTest

* On est sur un TU qui ne fait que tester les dépendances de la classe RegisterCustomerHandler.
* Les tests vérifient que les méthodes des dépendances sont bien appelées mais ne testent pas que le résultat final est bien celui souhaité.
* Il y à la fin un mock sur une dépendance externe "EventDispatcherInterface" (qui est un adapter), cela devrait être éviter "don't mock what you don't own" => Il faudrait en faire une abstraction comme le reste ou un stub
* Il y a de la duplication de chaine de caractères, beaucoup de prérequis pour instancier le SUT qui pourraient être déportés ailleurs si nécessaire et du code inutilisé pour faire la vérification de l'envoie de mail.

### Class RegisterPersisterTest

* Toujours un TU, couplé à l'implémentation car couplé à la classe associée (RegisterPersister)

### Avis personnel

* Beaucoup d'assertions via les mocks qui compliquent un peu la lisibilité des tests. 
* J'aurai préféré : 
    * mettre plus en avant les parties Arrange, Act and Assert en utilisant à minima des méthodes factories pour les prérequis du SUT et rassembler les mocks
    * Séparer les tests dans différentes méthodes de tests
    * Utilisé PHPSpec comme les tests sont couplés au codes :)
    

**Estimation du temps à l'instant T : 20min**

## La Classe TemplateManager

* Utilisée uniquement par la classe MailerProvider sans passer par l'injection de dépendance (ne respect pas les principes SOLID)
* Elle parait gérer différents mails (à cause des différents if) uniquement à partir de str_replace sans passer par un moteur de template
* La classe est couplée :
    * au contenu de l'objet Template via les mots clés pour remplacer les données
    * à l'environnement d'exécution via l'utilisation de la fonction getenv
    * au routing via la fin de construction d'une route
* La classe MailerProvider est fortement couplée à TemplateManager et ne pourra être testée séparement comme elle est instanciée en direct


## Créations des tests manquants

### Création d'un test pour vérifier que la classe TemplateManager fonctionne correctement
* Réutilisation du code mort de la classe RegisterCustomerHandlerTest, merci.
* Réutilisation de la méthode confirmTemplate de la classe MailProvider

###  Création d'un test pour vérifier que la classe MailerProvider fonctionne correctement
* Réutilisation du code

**Estimation du temps à l'instant T : 40min**

### Refactorisation de la classe TemplateManager
* Suppression de la variable d'env
* Suppression du if inutile
* Suppression du tableau inutile dans la fonction computeText
* Déplacement de la condition de l'urlLink
* Ajout de phpcs-fixer et fix des conventions de codage

**Estimation du temps à l'instant T : 60min**

## Dépassement de l'heure

### Refactorisation de la classe TemplateManager

* Création d'une condition return early
* Suppression de variables inutiles
* Remplacement de la condition sur le genre du customer (qui n'est pas utilisé dans le template)
* Changement de l'indentation des if et suppression du else inutile

## Bonus

* Envoi du mail sur le listener et création du TU associé

## Evolutions potentielles :
* rajouter le gender au mail
* refactorer la classe MailerProvider en injectant la classe TemplateManager
* passer la variable d'env via l'injection de dépendance
* faire un test end-to-end sur l'envoie de mail en utilisant l'api
* enlever les dupplications et notamment l'id du template à convertir en constante
* passer phpstan sur le projet
* regarder le reste des classes, rajouter des tests liés aux cas d'échecs et voir si d'autres choses doivent être corrigées
