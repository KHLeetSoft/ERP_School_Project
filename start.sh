#!/bin/bash

# Create sqlite database if not exists
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi

# Run migrations
php artisan migrate --force

# Start Apache
apache2-foreground
