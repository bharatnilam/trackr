FROM serversideup/php:8.2-fpm-nginx
WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --optimize-autoloader