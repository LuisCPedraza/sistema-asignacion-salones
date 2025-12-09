FROM php:8.3-fpm-alpine

# Instala dependencias de sistema, PHP y Nginx
RUN apk add --no-cache nginx git curl libpng-dev libjpeg-turbo-dev zip unzip postgresql-dev oniguruma-dev autoconf g++ make

# Instala extensiones PHP requeridas
RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd mbstring exif

# Instala Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader --no-dev

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Crear script de inicio
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Copia configuración de Nginx personalizada
COPY nginx.conf /etc/nginx/nginx.conf

# Expón el puerto 80 para Nginx
EXPOSE 80

# Usar script de inicio que ejecuta migraciones y optimizaciones
CMD ["docker-entrypoint.sh"]