# Use the official PHP image with required extensions
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install Node.js and npm for building front-end assets
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install npm dependencies and build frontend assets
RUN npm install && npm run build

# Set permissions (optional, for storage/logs/cache)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port 8000 and start Laravel's server
EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
