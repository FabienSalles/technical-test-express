#!/usr/bin/env sh

bin/console doctrine:database:drop --if-exists --force --no-interaction
bin/console doctrine:database:create --if-not-exists --no-interaction
bin/console doctrine:schema:update --force
