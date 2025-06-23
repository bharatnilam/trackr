# Use the Serversideup image for PHP 8.1 with Nginx
FROM serversideup/php:8.1-fpm-nginx

# Set the working directory in the container
WORKDIR /var/www/html

# Copy application files and set ownership to www-data in a single step.
# This is the critical change that avoids the "Operation not permitted" error.
COPY --chown=www-data:www-data . .

# Now, switch to the unprivileged 'www-data' user for the rest of the build.
# This is more secure and ensures that any files created by composer also have the correct ownership.
USER www-data

RUN touch database/database.sqlite

# Install Composer dependencies.
# Laravel's artisan scripts (like package:discover) can now run successfully because
# the 'www-data' user owns all the necessary files and directories.
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Expose port 80 to allow traffic to Nginx
EXPOSE 80