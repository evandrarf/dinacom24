FROM php:8.2-fpm

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

# Install additional PHP extensions (customize based on your needs)
# RUN docker-php-ext-install mysqli pdo_pgsql pdo_sqlite mcrypt

# Install PECL extensions (customize based on your needs)
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files and change ownership
COPY --chown=www-data:www-data . /var/www/
RUN chown -R www-data:www-data /var/www

WORKDIR /var/www

# Install dependencies
RUN composer install --no-scripts --no-dev --no-autoloader && \
    composer dump-autoload --optimize

RUN php artisan key:generate --ansi

RUN php artisan storage:link

RUN curl -sL https://deb.nodesource.com/setup_20.x | bash -

RUN apt-get install nodejs -y

RUN npm install
RUN npm run build

USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
