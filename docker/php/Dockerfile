FROM php:8.2-fpm

RUN apt-get -y update && apt-get upgrade -y

RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

WORKDIR /var/www
