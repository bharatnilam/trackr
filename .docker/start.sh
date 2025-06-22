#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

# Run database migrations. The --force flag is important for non-interactive environments.
php artisan migrate --force

# It's good practice to cache configuration in production for better performance
php artisan config:cache
php artisan route:cache

# Start PHP-FPM in the background
php-fpm

# Start Nginx in the foreground. This keeps the container running.
nginx -g "daemon off;"