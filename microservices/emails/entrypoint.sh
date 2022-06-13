#!/usr/bin/env sh

curl -Ss https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
composer install

php artisan queue:work
