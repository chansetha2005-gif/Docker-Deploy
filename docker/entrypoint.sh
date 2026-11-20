#!/bin/sh
set -e

cd /var/www/html

# Create .env from the example if none was provided
if [ ! -f .env ]; then
    echo "No .env found, copying .env.example"
    cp .env.example .env
fi

# Generate APP_KEY if it is empty
if ! grep -q '^APP_KEY=base64:' .env; then
    echo "Generating APP_KEY"
    php artisan key:generate --force
fi

# Wait for MySQL to accept connections (compose healthcheck also does this)
echo "Waiting for database..."
until php artisan db:show > /dev/null 2>&1; do
    sleep 2
done

php artisan migrate --force
php artisan optimize:clear > /dev/null
php artisan optimize

# Files created by artisan above are owned by root; give them back to Apache
chown -R www-data:www-data storage bootstrap/cache

exec "$@"
