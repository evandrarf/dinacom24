FROM php:8.2-fpm

ARG HOST_UID=1000
ARG HOST_GID=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    ca-certificates \
    gnupg \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    libreadline-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Install PECL extensions
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install nodejs -y

# Set user and group IDs
RUN usermod --uid $HOST_UID www-data \
    && groupmod --gid $HOST_GID www-data

# Copy application files
COPY --chown=www-data:www-data . /var/www/

# Set the working directory
WORKDIR /var/www

# Expose port 9000 and start php-fpm server
EXPOSE 9000

USER www-data

CMD ["php-fpm"]
