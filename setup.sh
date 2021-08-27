#!/bin/sh
composer install
npm install
./vendor/bin/sail up -d

MYSQL_CONTAINER_HOSTNAME=$(vendor/bin/sail ps | grep mysql | sed -E 's/^(\S+).*/\1/g')

export DB_HOST=127.0.0.1 && sed -E -i.bak "s/^DB_HOST=.*/DB_HOST=$DB_HOST/g" .env

php artisan migrate:fresh

export DB_HOST=$MYSQL_CONTAINER_HOSTNAME && sed -E -i.bak "s/^DB_HOST=.*/DB_HOST=$DB_HOST/g" .env

php artisan optimize:clear
