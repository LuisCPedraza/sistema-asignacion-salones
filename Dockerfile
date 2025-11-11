FROM php:8.3-fpm

# Instalar dependencias del sistema y Nginx
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copiar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Nginx
COPY nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar la aplicación
COPY . .

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Script de inicio que ejecuta Nginx y PHP-FPM
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Exponer puerto
EXPOSE 80

CMD ["/start.sh"]
