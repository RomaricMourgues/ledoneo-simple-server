FROM php:7.4-cli
COPY . /usr/src/server
WORKDIR /usr/src/server/web

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

CMD [ "php", "-S", "0.0.0.0:8000" ]