FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    zip unzip curl libpq-dev libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_pgsql zip bcmath

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
