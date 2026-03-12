FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    zip \
    unzip \
    supervisor

# Install PHP extensions
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

# Install Redis extension
RUN pecl install redis xdebug && \
    docker-php-ext-enable redis xdebug

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Setup user and group
ARG UID=1000
ARG GID=1000

RUN addgroup -g ${GID} laravel && \
    adduser -u ${UID} -G laravel -s /bin/sh -D laravel

# Configure PHP-FPM to run as the new user
RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html

# Xdebug config
# COPY docker/dev/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

USER laravel

EXPOSE 9000
