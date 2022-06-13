FROM php:8.0

RUN apt-get update && \
    apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        graphviz \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-ext-install sockets \
    && docker-php-source delete

RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

RUN set -eux; \
	apt-get install -y gosu; \
	rm -rf /var/lib/apt/lists/*; \
    addgroup bar; \
    adduser -D -h /home -s /bin/sh -G bar foo

WORKDIR /usr/src/app

COPY entrypoint.sh /entrypoint
RUN chmod +x /entrypoint

ENTRYPOINT ["/entrypoint"]
