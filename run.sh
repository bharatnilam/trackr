#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

echo "Running optimizations and migrations..."

# Cache configuration, routes, and views for production performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations automatically on startup
# The --force flag is important in production to bypass user prompts
php artisan migrate --force

echo "Starting Laravel server..."

# Start the Laravel development server, listening on all available network interfaces
# Render will automatically map its internal port to the public port 80/443
exec php artisan serve --host=0.0.0.0 --port=8000