#!/usr/bin/env bash

-e

SERVER=misc.heigl.org
mkdir -p ./tmp
export VERSION="`git describe --tags | cut -d "-" -f 1`"
TAG=`git describe --tags --abbrev=0`
TAG="master"
HASH=`git rev-parse $TAG`
echo "Deploying $HASH"
SED="sed -i "
if [ `uname` = 'Darwin' ] ; then
    SED="sed -i \"\""
fi
git archive --format=tar $TAG | tar x -C tmp
cd tmp
composer update --no-dev --prefer-dist

tar czf $HASH.tgz .

scp -r ./$HASH.tgz $SERVER:/var/www/com.getlatestassets.api/$HASH.tgz
ssh $SERVER mkdir -p "/var/www/com.getlatestassets.api/$HASH"
ssh $SERVER tar xz -f "/var/www/com.getlatestassets.api/$HASH.tgz" -C "/var/www/com.getlatestassets.api/$HASH" && echo "Extracted tgz"
ssh $SERVER rm -rf "/var/www/com.getlatestassets.api/$HASH.tgz" && echo "Removed tgz"
ssh $SERVER cd "/var/www/com.getlatestassets.api" && rm current && ln -sf "$HASH" "current" && echo "Set symbolic link"

cd ..

rm -rf ./tmp
