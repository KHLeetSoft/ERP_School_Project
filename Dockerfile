# Use official PHP image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Copy project
COPY . /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
    && docker-php-ext-install pdo_mysql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate Laravel key
RUN php artisan key:generate

# Expose port
EXPOSE 8080

# Start Apache
CMD ["apache2-foreground"]
