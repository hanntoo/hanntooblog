FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction \ 
    && npm ci \ 
    && npm run build \ 
    && php artisan config:cache \ 
    && php artisan route:cache \ 
    && php artisan view:cache \ 
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
