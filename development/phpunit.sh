#!/usr/bin/env bash

./one-shot-command.sh /var/www/pinterest-api-php/vendor/bin/phpunit -c /var/www/pinterest-api-php/phpunit.xml "$@"
