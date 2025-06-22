#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

echo "========================================="
echo "====  STARTING DIAGNOSTIC STARTUP SCRIPT ===="
echo "========================================="
echo " "

echo "---- [DEBUG] Identifying current user ----"
echo "Running as user: $(whoami)"
echo " "

echo "---- [DEBUG] Checking key directory permissions BEFORE artisan runs ----"
echo "--- Permissions for /var/www/html/storage ---"
ls -la /var/www/html/storage
echo " "
echo "--- Permissions for /var/www/html/storage/logs ---"
ls -la /var/www/html/storage/logs
echo " "
echo "--- Permissions for /var/www/html/bootstrap/cache ---"
ls -la /var/www/html/bootstrap/cache
echo " "

echo "========================================="
echo "====   ATTEMPTING TO BOOT LARAVEL...   ===="
echo "========================================="
echo " "

# This is the point where the application is crashing.
# The logs above will show us the state right before this failure.
php artisan migrate --force

# The script will exit here if the above command fails.
# If it continues, it means we have passed the error point.

echo " "
echo ">>>> SUCCESS: Artisan command completed. Application is running. <<<<"
echo " "

# The rest of the original script...
php artisan config:cache
php artisan route:cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

php-fpm
nginx -g "daemon off;"