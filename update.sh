#!/bin/sh

cd `dirname $0`
sh .placement.sh
composer dump-autoload
