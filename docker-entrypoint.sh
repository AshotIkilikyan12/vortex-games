#!/bin/sh
set -e

# Աշխատեցնում ենք միգրացիաները միայն միացման պահին
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

# Գործարկում ենք Apache-ն հիմնական ռեժիմով
exec apache2-foreground