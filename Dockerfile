# Use the Serversideup image for PHP 8.1 with Nginx
FROM serversideup/php:8.1-fpm-nginx

# Set the working directory in the container
WORKDIR /var/www/html

# chgrp -R 0 changes the group ownership to the 'root' group (GID=0)
# chmod -R g+w grants write permissions to the group
RUN mkdir -p /etc/nginx/sites-enabled /etc/nginx/certs \
    && chgrp -R 0 /etc/nginx \
    && chmod -R g+w /etc/nginx

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

ENTRYPOINT ["/usr/local/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]