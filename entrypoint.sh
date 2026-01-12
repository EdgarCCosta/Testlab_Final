#!/bin/sh

# Esperar a que MySQL esté disponible
until php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
  echo "Esperando a que MySQL esté listo..."
  sleep 2
done

# Ejecutar migraciones y seeders
php artisan migrate --force

# Arrancar servidor de Laravel
php -S 0.0.0.0:${PORT:-8080} -t public