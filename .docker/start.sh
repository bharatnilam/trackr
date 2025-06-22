#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

# Run database migrations and cache configurations.
# These commands run as the 'root' user by default.
php artisan migrate --force
php artisan config:cache
php artisan route:cache

# IMPORTANT: Fix permissions after running artisan.
# The artisan commands may create cache files owned by 'root'.
# We need to give the web server user 'www-data' ownership of these files.
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Start PHP-FPM in the background
php-fpm

# Start Nginx in the foreground. This keeps the container running.
nginx -g "daemon off;"