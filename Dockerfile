# Use a PHP 8.1 FPM image as a base
FROM php:8.1-fpm

# Install system dependencies required by Laravel
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    # PDO Drivers
    libpq-dev \
    libsqlite3-dev \
    default-mysql-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql pdo_sqlite zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . .

# Copy composer.json and composer.lock
COPY composer.json composer.lock ./

# Install dependencies, excluding development ones, and optimize autoloader
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --optimize-autoloader

# Run composer scripts to finalize installation
RUN composer run-script post-root-package-install
RUN composer run-script post-autoload-dump

# Expose port 9000 and start php-fpm server
# NOTE: We use artisan serve in the run.sh script instead for simplicity on Render.
# If you were using Nginx, you would use php-fpm.
EXPOSE 8000

# Set ownership and permissions for storage and cache directories
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# The command to run the application will be in run.sh
CMD ["/var/www/run.sh"]