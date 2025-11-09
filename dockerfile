# Imagen base con PHP, Composer y extensiones necesarias
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar archivos del proyecto
WORKDIR /var/www
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Establecer permisos
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Puerto de Laravel
EXPOSE 8000

# Comando de inicio
CMD php artisan serve --host=0.0.0.0 --port=8000
