#!/bin/sh

echo "🚀 Iniciando aplicación Laravel..."

# Esperar a que la base de datos esté disponible
echo "⏳ Esperando base de datos..."
sleep 5

# Ejecutar migraciones
echo "📦 Ejecutando migraciones..."
php artisan migrate --force

# Optimizaciones de Laravel para producción
echo "⚡ Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico para storage
php artisan storage:link

echo "✅ Aplicación lista!"

# Iniciar PHP-FPM y Nginx
php-fpm -D
nginx -g 'daemon off;'
