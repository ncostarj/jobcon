# Dockerfile
FROM nginx:latest

#
LABEL maintainer="Newton Gonzaga Costa<ncosta.rj@gmail.com>" \
      description="Imagem para desenvolvimento com rpa"

#
VOLUME [ "/var/www/html" ]

#
WORKDIR /var/www/html/jobcon-rpa

# Expose the default port
EXPOSE 80 3000 4200

# Basic packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    cron \
    vim \
    htop \
    git \
    locales \
    cron \
    zlib1g-dev \
    software-properties-common \
    build-essential \
    gnupg \
    && rm -rf /var/lib/apt/lists/*

# Node.js 24.x (LTS) + package managers
RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest yarn pnpm forever pm2 nodemon @angular/cli

# Copy conf file
COPY conf/nginx/nginx.conf /etc/nginx/conf.d

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

USER www-data
