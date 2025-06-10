FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    libpq-dev libzip-dev libfreetype6-dev libjpeg62-turbo-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www && chmod -R 775 /var/www/storage

# Composer install (in user context for permission safety)
USER www-data
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

USER root

EXPOSE 9000
CMD ["php-fpm"]
