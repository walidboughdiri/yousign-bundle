FROM interinvestcontainersregistry.azurecr.io/interinvest/php:8.1-fpm-alpine

COPY composer.json .
COPY composer.lock .

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer install
