FROM php:7-fpm-alpine
RUN apk upgrade --no-cache \
 && docker-php-ext-configure mysqli --with-mysqli \
 && docker-php-ext-install mysqli
