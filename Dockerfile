FROM php:8.3-fpm

# # Install dependencies dan ekstensi PHP
# RUN apt-get update && apt-get install -y \
#     git unzip libicu-dev libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip \
#     libonig-dev locales curl mariadb-client \
#     && docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install gd pdo_mysql intl bcmath zip exif opcache \
#     && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip libonig-dev locales \
    libonig-dev curl \
    mariadb-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql intl bcmath zip exif opcache \
    && rm -rf /var/lib/apt/lists/*


# Set locale
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && locale-gen

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Git safe dir (hindari error ownership saat bind mount)
RUN git config --global --add safe.directory /var/www/html

WORKDIR /var/www/html
