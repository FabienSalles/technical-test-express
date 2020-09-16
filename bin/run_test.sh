#!/usr/bin/env sh

set -eu

export APP_ENV=test
export APP_DEBUG=1

composer install --prefer-dist --no-progress --no-suggest --no-interaction

exec php bin/phpunit -c . $@
