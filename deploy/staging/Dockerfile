FROM composer:2.10.3@sha256:d8f6343d3fae98107426bc49163ccad46ef85aabd4a27d80a74401fab4aba332 AS composer

FROM php:8.5-fpm-bookworm@sha256:81b9c405b013ebda0c9b8cd7a1a61424cf3627ca96348d93752a9b0539ce9a25 AS app

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        git \
        libcurl4-openssl-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        curl \
        exif \
        gd \
        mbstring \
        mysqli \
        pdo_mysql \
        pdo_sqlite \
        simplexml \
        zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY deploy/staging/php.ini /usr/local/etc/php/conf.d/99-contentify.ini
COPY deploy/staging/php-fpm.conf /usr/local/etc/php-fpm.d/zz-contentify.conf

WORKDIR /var/www/html
COPY . /var/www/html

RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --optimize-autoloader \
    && mkdir -p \
        bootstrap/cache \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        public/uploads \
    && chown -R www-data:www-data storage bootstrap/cache public

FROM nginx:1.26-alpine@sha256:1eadbb07820339e8bbfed18c771691970baee292ec4ab2558f1453d26153e22d AS web
COPY deploy/staging/nginx.conf /etc/nginx/conf.d/default.conf
COPY public /var/www/html/public
