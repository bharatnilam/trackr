# Use the Serversideup image for PHP 8.1 with Nginx
FROM serversideup/php:8.1-fpm-nginx

# Set the working directory in the container
WORKDIR /var/www/html

# Copy existing application files from the repository to the container
# This assumes your .dockerignore file is properly set up to exclude
# unnecessary files like vendor/, node_modules/, and .env
COPY . .

# Install Composer dependencies. The Serversideup image comes with Composer.
# The --no-interaction flag prevents prompts, and --no-dev excludes development packages.
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Run database migrations.
# This step is commented out by default. It's often better to run migrations
# as a separate task after the container is deployed, rather than during the image build.
# However, if you want to include migrations in the build process, you can uncomment this line.
# Note: This requires your production database to be accessible during the build, which may not be secure or practical.
# RUN php artisan migrate --force

# Set the correct ownership for storage and bootstrap/cache directories
# to allow Laravel to write logs and cache files. 'www-data' is the user
# that Nginx/PHP-FPM runs as inside the container.
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 80 to allow traffic to Nginx
EXPOSE 80

# The CMD directive is inherited from the base image (serversideup/php:8.1-fpm-nginx),
# which starts the necessary services (Nginx, PHP-FPM). You do not need to add it here.