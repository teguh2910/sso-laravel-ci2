FROM php:8.1-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libzip-dev libsqlite3-dev libicu-dev \
    && docker-php-ext-install pdo pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY entrypoint-server.sh /usr/local/bin/entrypoint-server.sh
COPY entrypoint-client.sh /usr/local/bin/entrypoint-client.sh
RUN chmod +x /usr/local/bin/entrypoint-server.sh /usr/local/bin/entrypoint-client.sh

WORKDIR /var/www/html
