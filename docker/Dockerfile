FROM php:7.4.1-fpm

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    g++ git libicu-dev zip \
    libzip-dev \
    zip
RUN docker-php-ext-install intl opcache pdo_mysql
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip

RUN docker-php-ext-install mysqli

WORKDIR /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin -- filename=composer

COPY . /app