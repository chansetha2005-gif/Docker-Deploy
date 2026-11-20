# ---------------------------------------------------------------
# Laravel app image: PHP 8.4 + Apache + Composer
# (MySQL runs in its own container - see docker-compose.yml)
# ---------------------------------------------------------------
FROM php:8.4-apache

# 1. Install system packages + PHP extensions Laravel needs
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libzip-dev libpng-dev libicu-dev default-mysql-client \
    && docker-php-ext-install pdo_mysql zip bcmath gd intl opcache \
    && a2enmod rewrite \
    && echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername \
    && rm -rf /var/lib/apt/lists/*

# 2. Install Composer (copied from the official Composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3. Point Apache at Laravel's public/ folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# 4. Install PHP dependencies first (cached layer if composer files don't change)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

# 5. Copy the application code and finish the Composer install
COPY . .
RUN composer dump-autoload --optimize --no-dev \
    && chown -R www-data:www-data storage bootstrap/cache

# 6. Startup script (waits for MySQL, runs migrations, starts Apache)
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
