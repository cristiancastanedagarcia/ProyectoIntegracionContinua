FROM php:7.2-apache
COPY . /var/www/html/
RUN docker-php-ext-install mysqli