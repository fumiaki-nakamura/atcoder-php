#!/bin/sh

cd `dirname $0`
currentPath=`dirname $0`
exclusionFiles=`\find $currentPath -maxdepth 1 -type f`
laravelPath=$currentPath/.laravel
files=`\find $laravelPath -maxdepth 1 -type f`

# 前回スクリプトで実行した際に展開していたシンボリックリンクを一旦削除
# ※ ファイルパスの変更があった場合に `composer dump-autoload` が失敗するのを防ぐため
find . -type l | grep -v '/vendor/' | xargs rm -f

cp -R .laravel/* $currentPath
for file in `\find .customize -maxdepth 1 -type f`;
do
    ln -nfs $file
done

# ./src 配下のファイルを ./ にシンボリックリンクとして展開
for path in `find src/ -type f`
do
    relativePath=`echo ${path#src/*/} | sed -Ee 's;(/?)[^/]+;\1..;g'`/$path
    linkPath=${path#src/*}
    mkdir -p `dirname $linkPath`
    ln -nfs $relativePath $linkPath
done
