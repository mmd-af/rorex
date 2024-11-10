FROM php:8.1-fpm as php

RUN apt update && apt install -y \
    git \
    zip \
    unzip \
    librabbitmq-dev \
    libssh-dev \
    libonig-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-install \
    bcmath \
    sockets \
    pdo_mysql \
    exif \
    pcntl \
    gd \
    zip

COPY --from=composer/composer:2-bin /composer /usr/bin/composer

RUN groupadd -g 1000 rorex && \
    useradd -u 1000 -g rorex -G www-data -m -d /var/www rorex

RUN chown -R rorex:rorex /var/www

USER rorex

WORKDIR /var/www/rorex

FROM nginx:latest as nginx

COPY nginx.conf.template /etc/nginx/templates/default.conf.template
