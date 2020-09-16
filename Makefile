DOCKER_COMPOSE = docker-compose
EXEC_PHP = $(DOCKER_COMPOSE) exec php
COMPOSER = $(EXEC_PHP) composer
SYMFONY = $(EXEC_PHP) bin/console

build:
	@$(DOCKER_COMPOSE) pull --parallel --quiet --ignore-pull-failures 2> /dev/null
	$(DOCKER_COMPOSE) build --pull

install: build vendor start

vendor: ## Install vendors
	$(DOCKER_COMPOSE) run --rm php composer install

start: ## Start the project
	$(DOCKER_COMPOSE) up -d

stop: ## Stop the project
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

#### Tests

tu: ## Run unit tests
tu:
	$(EXEC_PHP) sh bin/run_test.sh --testsuite unit

.PHONY: tu
