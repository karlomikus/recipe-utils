FROM php:8.2-cli

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app
