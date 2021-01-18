FROM node:lts-slim as node
COPY . /var/www/
WORKDIR /var/www/
RUN npm install
RUN npm run production

FROM composer as composer
COPY --from=node /var/www /var/www/
WORKDIR /var/www/
RUN composer install

FROM php:7.4-fpm-alpine
COPY --from=composer /var/www /var/www/
RUN touch /var/www/storage/database.sqlite
RUN chown -R www-data:www-data /var/www/storage
WORKDIR /var/www/
