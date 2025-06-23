# ---- Stage 1: Build PHP Dependencies ----
FROM composer:2.7 AS vendor

WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --optimize-autoloader


# ---- Stage 2: Final Production Image ----
FROM php:8.1-fpm

WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y nginx git unzip zip curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# Copy application code and dependencies
COPY --from=vendor /app/vendor /var/www/html/vendor
COPY . .

# Copy and prepare the startup script
COPY .docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Set correct permissions on writable directories for Laravel
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data \
    && touch /var/www/html/database/database.sqlite \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Expose a default port (will be overridden by PORT env var)
EXPOSE 8080
CMD ["/usr/local/bin/start.sh"]