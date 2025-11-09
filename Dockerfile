# Imagen base con PHP, Composer y extensiones necesarias
FROM php:8.2-fpm

# Instalar dependencias del sistema (incluye SQLite)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    pkg-config \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-configure pdo_sqlite --with-pdo-sqlite=/usr && \
    docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Crear base de datos SQLite
RUN mkdir -p /var/www/database && touch /var/www/database/database.sqlite

# Asignar permisos a Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database

# Exponer el puerto de Laravel
EXPOSE 8000

RUN php artisan migrate --force

# Comando para iniciar Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
