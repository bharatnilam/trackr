FROM richarvey/nginx-php-fpm:2.2.3-php82

COPY . /var/www/html

RUN composer install --no-interaction --no-dev --optimize-autoloader

RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache