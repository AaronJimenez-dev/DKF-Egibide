#!/bin/sh

# Espera a que MySQL esté disponible
until php -r "new PDO('mysql:host=db;port=3306;dbname=dkf_egibide', 'grupo04', 'grupo04');" 2>/dev/null; do
  echo "Esperando a que MySQL esté listo..."
  sleep 2
done

php artisan key:generate --force

php artisan migrate --force --seed

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
