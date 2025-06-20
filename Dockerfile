FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite