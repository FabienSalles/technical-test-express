# README

## Contexte:
Ce projet fictif est une API développée avec *Symfony* et *ApiPlatform* qui permet de créer un compte sur notre site.

La classe `App\Utils\TemplateManager` permet de personnaliser certaines parties des emails envoyés.
Elle (et `App\Utils\Template`) ont été récupérées d’un ancien projet, on ne sait plus très bien qui les a développées.
Les besoins évoluant très souvent, la classe `App\Utils\TemplateManager` a été modifiée à plusieurs reprises 
et il est devenu difficile de comprendre comment elle fonctionne.

Aujourd'hui, le métier veut ajouter de nouvelle fonctionnalité, 
la classe `App\Utils\TemplateManager` étant trop complexe 
avant d'ajouter de nouvelles fonctionnalités, il faut d'abord la nettoyer.

Votre mission, est de refactoriser `App\Utils\TemplateManager` pour la rendre compréhensible et évolutive.

Ce test ne devrait pas vous prendre plus d'1hr de travail, si vous voulez passer plus de temps libre à vous.
Le but de l'exercice est d'avoir un support pour la suite des entretiens.

## Règles/Conseils:
* Les classes qui ont l’annotation `@Test/ReadOnly` ne doivent pas être modifiées.
* La méthode `TemplateManager::getTemplateComputed` est utilisée à plusieurs endroit dans le projet il n'est donc pas authorisé de modifier sa signature
* _Faire des commits réguliers avec des messages clairs._
* Un code joli/clair est toujours préférable.
* Rajouter une explication (dans un fichier/message/email) des choix faits et du processus utilisé pour appréhender le problème.
* _Livrer votre travail dans un repo GIT (Gitlab ou Github)_

## Mission bonus:

Si il vous reste du temps, pour tester vos connaissances dans l'écosystème *Symfony* et *ApiPlatform*.

1°) Il faut ajouter la fonctionnalité d’envoyer un email au client une fois que le client s’est enregistré.
* Il ne faut pas implementer un mailer, il faut ré-utiliser la classe `Client\Mailer` et si besoin modifier le `Provider\MailerProvider`
* L’id du template à envoyer est `confirmation_001`
* Les données supplémentaires à envoyer sont 
    * firstName
    * lastName
    * gender
    
2°) Corriger toutes les erreurs qui restent


## Installation:

```bash
make install
```

#### Api docs
http://localhost:8080/api/docs

#### Lancer les tests unitaires
```bash
make tu
```
