FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    git \
    curl \
    libpq-dev \
    icu-dev \
    libzip-dev \
    zip \
    unzip \
    openssl \
    bash

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY docker/php/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
