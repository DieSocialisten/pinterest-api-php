#!/usr/bin/env bash

./runtime-env.sh docker-compose run --rm pinterest-api-php-dev /var/www/pinterest-api-php/vendor/bin/phpunit -c /var/www/pinterest-api-php/phpunit.xml
