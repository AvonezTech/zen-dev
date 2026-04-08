#!/bin/bash
set -e

rm -rf vendor

composer install --no-interaction --prefer-dist --optimize-autoloader
php -d error_reporting="E_ALL & ~E_DEPRECATED & ~E_NOTICE" artisan migrate --force
# php -d error_reporting="E_ALL & ~E_DEPRECATED & ~E_NOTICE" artisan operations:process
