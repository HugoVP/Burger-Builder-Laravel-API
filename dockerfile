FROM composer AS installation

COPY ./ ./

RUN composer install --optimize-autoloader --no-dev

FROM php:7.1.3-fpm-alpine

WORKDIR /var/www

COPY --from=installation /app ./

RUN apk add --update postgresql-dev \
  && docker-php-ext-install pdo_pgsql