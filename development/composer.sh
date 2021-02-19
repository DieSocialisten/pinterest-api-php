#!/usr/bin/env bash

./runtime-env.sh docker-compose run --rm pinterest-api-php-dev /var/www/pinterest-api-php/composer.phar --working-dir=/var/www/pinterest-api-php/ "$@"
