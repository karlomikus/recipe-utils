FROM php:8.2-cli

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
    apt-get install -y git unzip

WORKDIR /app
