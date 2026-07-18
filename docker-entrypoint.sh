#!/bin/sh
set -e

mkdir -p var/cache var/log

chown -R www-data:www-data var
chmod -R 775 var

php bin/console cache:clear --env=prod --no-debug || true
php bin/console cache:warmup --env=prod || true

php bin/console doctrine:migrations:migrate \
    --no-interaction \
    --allow-no-migration

exec apache2-foreground