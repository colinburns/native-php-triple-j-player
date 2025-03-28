#!/bin/bash

# Clear Laravel caches
echo "Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Ensure storage directories exist
echo "Creating storage directories..."
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p bootstrap/cache

# Set proper permissions
echo "Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Build frontend assets
echo "Building assets..."
npm run build

# Start NativePHP
echo "Starting NativePHP..."
php artisan native:serve
