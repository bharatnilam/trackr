#!/bin/sh
set -e

# Read the PORT environment variable provided by Hugging Face, default to 8080 if not set
PORT="${PORT:-8080}"

echo "==> Setting Nginx to listen on port ${PORT}"

# Use sed to replace the placeholder in the template and create the final config in /tmp
sed "s/__PORT__/$PORT/g" /var/www/html/.docker/nginx.conf.template > /tmp/nginx.conf

echo "==> Running Laravel startup tasks..."
php artisan migrate --force
php artisan config:cache
php artisan route:cache

echo "==> Starting services..."

# Start PHP-FPM in the background
php-fpm &

# Start Nginx in the foreground using our dynamically generated config file
# The -c flag tells Nginx to use a specific config file
nginx -g "daemon off;" -c /tmp/nginx.conf