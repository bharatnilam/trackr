# Use the Serversideup image for PHP 8.1 with Nginx
FROM serversideup/php:8.1-fpm-nginx

# Set the working directory in the container
WORKDIR /var/www/html

# Copy existing application files
COPY . .

# Fix: Ensure necessary directories exist and are writable before installing dependencies.
# The 'root' user running this command needs to be able to write to these directories
# when the 'artisan' script is triggered by Composer.
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Install Composer dependencies. This can now run without permission errors.
RUN composer install --no-interaction --no-dev --optimize-autoloader

# After installing, set the ownership of all application files to the web-server user ('www-data').
# This ensures that Nginx and PHP-FPM can read the files, including the vendor directory created by Composer.
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 to allow traffic to Nginx
EXPOSE 80

# The CMD directive is inherited from the base image