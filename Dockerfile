FROM php:8.1-fpm as php

RUN apt update && apt install -y git zip unzip librabbitmq-dev libssh-dev libonig-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev

RUN pecl install xdebug

RUN docker-php-ext-install bcmath sockets pdo_mysql exif pcntl gd

COPY --from=composer/composer:2-bin /composer /usr/bin/composer

WORKDIR /var/www/exercise


FROM nginx:latest as nginx

COPY nginx.conf.template /etc/nginx/templates/default.conf.template
