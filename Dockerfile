FROM php:7.1.9-apache

COPY .docker/php/php.ini /usr/local/etc/php/
COPY . /var/www/html
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install opcache
