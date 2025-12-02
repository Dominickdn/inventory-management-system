#!/bin/bash
set -e

cd /var/www/symfony

# Wait for MariaDB to be ready
until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
  echo "Waiting for database..."
  sleep 2
done

php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration || true

exec php-fpm

