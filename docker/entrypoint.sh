#!/bin/bash

set -e

echo "Aguardando serviços ficarem prontos..."

# Aguardar MySQL estar pronto
until php -r "try { new PDO('mysql:host=${DB_HOST:-db};port=${DB_PORT:-3306};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'MySQL pronto'; } catch (Exception \$e) { exit(1); }" 2>/dev/null; do
    echo "Aguardando MySQL..."
    sleep 2
done

echo "MySQL está pronto!"

# Limpar cache
echo "Limpando cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Otimizar aplicação
echo "Otimizando aplicação..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Aplicação pronta!"

# Executar comando passado como argumento
exec "$@"
