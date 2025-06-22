#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

echo "Running production startup script..."

# Run database migrations and cache configurations.
php artisan migrate --force
php artisan config:cache
php artisan route:cache

echo "Startup tasks complete. Starting services."

# Start PHP-FPM in the background
php-fpm

# Start Nginx in the foreground.
nginx -g "daemon off;"