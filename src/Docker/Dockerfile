FROM php:8-cli

RUN docker-php-ext-install mysqli

COPY --from=composer /usr/bin/composer /usr/bin/composer