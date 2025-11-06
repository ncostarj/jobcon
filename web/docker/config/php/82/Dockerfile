FROM php:8.2-fpm

# Install base dependencies
RUN apt-get update && apt-get install -y \
    curl \
    openssl \
    unzip \
    zip \
    libicu-dev \
    libzip-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Installing php extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
        bcmath \
        gd \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        xml \
        zip

# Installing mongodb lib
RUN pecl install mongodb-1.21.1
RUN echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongodb.ini

# Installing composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# --- Install Node.js 24 (LTS) globally ---
RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest && \
    npm install -g yarn pnpm

# Change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

WORKDIR /var/www/html

USER www-data

RUN composer --version
RUN node --version
RUN yarn --version