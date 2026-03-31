#!/bin/sh
set -e

# Copy .env if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi

# Ensure APP_KEY is set
if ! grep -q "APP_KEY=base64:" .env || [ -z "$(grep APP_KEY .env | cut -d '=' -f2)" ]; then
    echo "Generating Application Key..."
    php artisan key:generate --force
fi

# Setup SQLite Database
if [ ! -f database/database.sqlite ]; then
    echo "Creating SQLite database file..."
    touch database/database.sqlite
    chown www-data:www-data database/database.sqlite
fi

# Run Composer Install (if vendor is missing or in dev)
if [ ! -d vendor ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --optimize-autoloader --no-dev
fi

# Run Migrations and Seeders
echo "Running database migrations..."
php artisan migrate --force

echo "Running database seeders..."
php artisan db:seed --force

# Start Apache in foreground
echo "Starting Apache..."
exec apache2-foreground
