#!/bin/sh
set -e

# Run Composer Install (if vendor/autoload.php is missing)
if [ ! -f vendor/autoload.php ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --optimize-autoloader
fi

# Copy .env if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi

# Ensure storage directories exist and are writable
echo "Setting up storage permissions..."
mkdir -p storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Ensure APP_KEY is set
if ! grep -q "APP_KEY=base64:" .env || [ -z "$(grep APP_KEY .env | cut -d '=' -f2)" ]; then
    echo "Generating Application Key..."
    php artisan key:generate --force
fi

# Setup SQLite Database
if [ ! -f database/database.sqlite ]; then
    echo "Creating SQLite database file..."
    mkdir -p database
    touch database/database.sqlite
fi
# Ensure permissions for SQLite
chown -R www-data:www-data database/
chmod -R 775 database/

# Run Migrations and Seeders
echo "Running database migrations..."
php artisan migrate --force

echo "Running database seeders..."
php artisan db:seed --force

# Start Apache in foreground
echo "Starting Apache..."
exec apache2-foreground
