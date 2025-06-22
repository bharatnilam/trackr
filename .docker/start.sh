#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

# Run artisan commands AS the web server user 'www-data'.
# This ensures that any files created (cache, logs) have the correct permissions.
echo "Running migrations and caching as www-data user..."
su -s /bin/sh -c "php artisan migrate --force" www-data
su -s /bin/sh -c "php artisan config:cache" www-data
su -s /bin/sh -c "php artisan route:cache" www-data
echo "Cache and migrations complete."

# Start PHP-FPM in the background. It will run workers as 'www-data'.
php-fpm

# Start Nginx in the foreground. This keeps the container running.
echo "Starting Nginx..."
nginx -g "daemon off;"