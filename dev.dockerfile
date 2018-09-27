FROM composer AS install-stage

WORKDIR /var/www

COPY ./composer.lock ./composer.json ./
COPY ./database ./database

RUN composer install --no-scripts

FROM php:7.2.10-fpm-alpine AS run-stage

WORKDIR /var/www

RUN docker-php-ext-install pdo_mysql \
  && docker-php-ext-enable pdo_mysql

COPY ./ ./
COPY --from=install-stage /var/www/vendor /var/www/vendor

RUN php artisan key:generate; \
  php artisan route:cache; \
  php artisan config:cache; \
  php artisan migrate:refresh --seed

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]