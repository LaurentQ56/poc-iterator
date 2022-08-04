ARG PHP_VERSION
FROM php:${PHP_VERSION}-fpm

ENV DEBIAN_FRONTEND="noninteractive" \
		SYMFONY_ALLOW_APPDEV=1 \
		COMPOSER_ALLOW_SUPERUSER=1

# Set timezone
RUN rm /etc/localtime && ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libmemcached-dev \
    zlib1g-dev \
    libzip-dev \
    libfontconfig1 \
    libxrender1 \
    ghostscript \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    librabbitmq-dev \
    libssl-dev \
    g++

# Install xdebug
RUN pecl install xdebug amqp memcached \
		&& echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini

RUN docker-php-ext-install pdo pdo_mysql bcmath zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl \
    && docker-php-ext-install -j$(nproc) iconv gd \
    && docker-php-ext-enable xdebug opcache amqp memcached

RUN rm -rf /var/lib/apt/list*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN useradd -ms /bin/bash iterator
USER iterator
WORKDIR /iterator
