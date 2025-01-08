FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN apt-get update \
    && apt-get install -y libzip-dev zip \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-install sockets
RUN pecl install xdebug \ && docker-php-ext-enable xdebug
RUN apt-get update && apt-get install cron -y
RUN apt-get update && apt-get install -y supervisor

# Get latest Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

#nano
RUN ["apt-get", "update"]
RUN ["apt-get", "install", "-y", "nano"]
#CMD ["supervisord", "-n"]