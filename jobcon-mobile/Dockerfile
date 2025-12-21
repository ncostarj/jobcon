FROM nginx:latest

WORKDIR /var/www/html/jobcon-mobile

# Basic packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zlib1g-dev \
    software-properties-common \
    build-essential \
    gnupg \
    && rm -rf /var/lib/apt/lists/*

# Node.js 24.x (LTS) + package managers
RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest yarn pnpm ionic

# # Ajustar permissões de usuário
# RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# USER www-data
