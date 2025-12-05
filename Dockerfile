FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install dependencies (ignore platform requirements for compatibility)
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-req=php-64bit --ignore-platform-req=ext-zip

# Install Node dependencies and build assets
RUN npm ci && npm run build

# Ensure public/build directory exists and has correct permissions
RUN mkdir -p /app/public/build && \
    chmod -R 755 /app/public && \
    chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/public

# Expose port
EXPOSE 8080

# Run the application
CMD php artisan serve --host=0.0.0.0 --port=8080
