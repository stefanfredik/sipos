# docker/prod/Dockerfile
FROM php:8.3-fpm-alpine AS base

RUN apk add --no-cache \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    zip \
    unzip \
    supervisor \
    linux-headers \
    $PHPIZE_DEPS

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    opcache \
    zip

RUN pecl install redis && docker-php-ext-enable redis

# Production OPcache settings
COPY docker/prod/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Build stage untuk assets
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Final production stage
FROM base AS production
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
COPY --from=node-builder /app/public/build ./public/build

RUN composer install --optimize-autoloader --no-dev --no-interaction

RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

RUN chown -R www-data:www-data /var/www/html/storage \
    /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
