#!/bin/sh

cd `dirname $0`
bash .placement.sh
composer install -n --prefer-dist --no-scripts
cp .env.example .env
php artisan key:generate
