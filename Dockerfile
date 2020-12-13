FROM php:7-fpm-alpine
RUN apk upgrade --no-cache \
 && docker-php-ext-install pdo pdo_mysql
