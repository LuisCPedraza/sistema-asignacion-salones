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

# Sustituir PORT en la configuración de Nginx (Render provee $PORT)
PORT=${PORT:-80}
echo "🔧 Configurando Nginx para escuchar en puerto ${PORT}..."
# Reemplaza la directiva 'listen ${PORT};' por el valor real
sed -i "s/listen \${PORT};/listen ${PORT};/" /etc/nginx/nginx.conf

# Iniciar PHP-FPM y Nginx
php-fpm -D
nginx -g 'daemon off;'
