# ---- Stage 1: Build PHP Dependencies ----
# Use the official Composer image to get PHP dependencies
FROM composer:2.7 AS vendor

WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
# Install dependencies, --no-dev for production and --no-scripts to prevent errors
RUN composer install --no-dev --no-interaction --no-scripts --optimize-autoloader


# ---- Stage 2: Final Production Image ----
# Use the lean php-fpm image for the final application
FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies needed for Laravel
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsqlite3-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# Copy the composer dependencies from the 'vendor' stage
COPY --from=vendor /app/vendor /var/www/html/vendor

# Copy the rest of the application code
COPY . .

# Copy Nginx and startup script configurations
COPY .docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY .docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# ** THE KEY FIX IS HERE **
# Set ownership and permissions on all three writable directories.
# This ensures that both the startup user (1000) and the web server user (www-data)
# can write to storage, cache, AND the database directory.
RUN touch /var/www/html/database/database.sqlite \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Expose port 80 for the Nginx web server
EXPOSE 80

# The CMD will run our startup script which handles migrations and starts the services
CMD ["/usr/local/bin/start.sh"]