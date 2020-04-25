#!/bin/sh

cd `dirname $0`
cd ../
sh .placement.sh
composer install -n --prefer-dist --no-scripts
