FROM php:8.2-fpm

WORKDIR /var/www/html/jobcon-web

# Install base dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
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

# PHP Extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
        bcmath gd intl mbstring opcache pdo_mysql xml zip

# MongoDB PHP driver
RUN pecl install mongodb-1.21.1 && \
    echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongodb.ini

# Composer (latest stable)
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Node.js 24.x (LTS) + package managers
RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest yarn pnpm @vitejs/plugin-vue @vitejs/plugin-vue-jsx --save-dev 

RUN mkdir /var/www/.composer && \
    # echo '{ "process-timeout": 300, "use-include-path": false, "preferred-install": "dist", "notify-on-install": true, "gitlab-domains": ["gitlab.com"], "github-protocols": ["https"] }' > /var/www/.composer/config.json && \
    chown -R www-data:www-data /var/www/.composer && \
    chmod -R 775 /var/www/.composer

# Ajustar permissões de usuário
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

USER www-data

# Test versions
RUN composer --version && node --version && yarn --version

CMD ["php-fpm"]