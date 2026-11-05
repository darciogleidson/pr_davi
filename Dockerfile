FROM php:8.4-fpm-alpine

RUN apk add --no-cache icu-libs libzip \
    && apk add --no-cache --virtual .build-dependencies $PHPIZE_DEPS icu-dev libzip-dev \
    && docker-php-ext-install -j"$(nproc)" bcmath intl zip \
    && apk del .build-dependencies

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]
