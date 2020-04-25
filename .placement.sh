#!/bin/sh

cd `dirname $0`
currentPath=`pwd`
exclusionFiles=`find $currentPath -maxdepth 1 -type f`

# 前回スクリプトで実行した際に展開していたシンボリックリンクを一旦削除
# ※ ファイルパスの変更があった場合に `composer dump-autoload` が失敗するのを防ぐため
find . -type l | grep -v '/vendor/' | xargs rm -f

# Laravel の初期セットを展開
find .laravel -mindepth 1 -maxdepth 1 | xargs cp -at .

# laravel_template の初期セットを展開
find .customize -mindepth 1 -maxdepth 1 | xargs cp -at .

# src 配下のファイルを ./ にシンボリックリンクとして展開
mkdir -p src
for path in `find src -type f`
do
    dst=${path#src/}
    src=`echo $dst | sed -Ee 's;[^/]+/;../;g' | sed -Ee "s;${path##*/};$path;"`
    mkdir -p `dirname $dst`
    ln -nfs $src $dst
done
