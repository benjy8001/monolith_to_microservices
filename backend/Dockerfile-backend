FROM php:8.0-apache

RUN apt-get update && \
    apt-get install -y $PHPIZE_DEPS libjpeg-dev libpng-dev zlib1g-dev libonig-dev libzip-dev libfreetype6-dev && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    docker-php-ext-configure gd --with-jpeg --with-freetype && \
    docker-php-ext-install mysqli pdo pdo_mysql mbstring zip gd && \
    docker-php-ext-install sockets && \
    a2enmod rewrite && \
    apt-get remove -y $PHPIZE_DEPS && \
    docker-php-source delete

RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini && \
    sed -i 's@^upload_max_filesize = 2M@upload_max_filesize = 30M@g' /usr/local/etc/php/php.ini && \
    sed -i 's@^post_max_size = 8M@post_max_size = 31M@g' /usr/local/etc/php/php.ini

RUN set -eux; \
	apt-get install -y gosu; \
	rm -rf /var/lib/apt/lists/*; \
    addgroup bar; \
    adduser -D -h /home -s /bin/sh -G bar foo

WORKDIR /var/www/

COPY entrypoint.sh /entrypoint
RUN chmod +x /entrypoint

ENTRYPOINT ["/entrypoint"]
