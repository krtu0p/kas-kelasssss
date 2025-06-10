FROM php:8.2-apache

# Install deps
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    libzip-dev libpq-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo_pgsql mbstring zip gd && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /var/www/html

# Copy project files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Build Laravel app
RUN composer install --optimize-autoloader --no-dev

# Set environment to production and migrate+seed
RUN cp .env.example .env && \
    php artisan config:clear && \
    php artisan key:generate && \
    php artisan migrate --force && \
    php artisan db:seed --force

# Enable Apache rewrite
RUN a2enmod rewrite

EXPOSE 80
CMD ["apache2-foreground"]
