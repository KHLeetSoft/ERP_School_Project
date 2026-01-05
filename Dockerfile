FROM php:8.2-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Install system dependencies + PHP extensions (MySQL + PostgreSQL)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Set Apache DocumentRoot to Laravel public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Copy project files
COPY . /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# FIX PERMISSIONS (THIS SOLVES YOUR ERROR)
RUN mkdir -p storage/logs \
    && touch storage/logs/laravel.log \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && chmod -R 777 storage/logs

# Copy & allow start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
