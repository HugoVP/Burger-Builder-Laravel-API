FROM php:7.2.10-fpm-alpine

WORKDIR /var/www

RUN docker-php-ext-install pdo_mysql \
  && docker-php-ext-enable pdo_mysql

COPY ./composer.lock ./composer.json composer-install.sh ./
COPY ./database ./database

RUN chmod +x composer-install.sh \
  && ./composer-install.sh \
  && php composer.phar install --no-scripts \
  && rm composer.phar

COPY ./ ./

RUN php artisan route:cache; \
  php artisan config:cache

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]