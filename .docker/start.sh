#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

# Dynamically set the port in the Nginx configuration.
# The $PORT variable is provided by the Hugging Face platform.
# The default is 80 if the variable isn't set.
echo "Setting Nginx port to ${PORT:-80}..."
sed -i -e 's/__PORT__/'"$PORT"'/g' /etc/nginx/conf.d/default.conf

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