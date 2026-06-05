FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    nodejs \
    npm \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Adjust permissions for Laravel directories
RUN mkdir -p storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
