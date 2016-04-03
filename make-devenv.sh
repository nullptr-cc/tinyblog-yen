#!/usr/bin/env bash

bdir=`dirname $0 | xargs realpath`

pushd $bdir >/dev/null

echo "Configuring"
echo

read -p "Site host [127.0.0.1]: " site_host
read -p "Database host [localhost]: " db_host
read -p "Database name [tinyblog]: " db_name
read -p "Database username: " db_username
read -p "Database password: " db_password

if [ -z $site_host ]; then site_host="127.0.0.1"; fi
if [ -z $db_host ]; then db_host='localhost'; fi
if [ -z $db_name ]; then db_name='tinyblog'; fi


cat settings.ini-example |
    sed "s/<SITE_HOST>/${site_host}/g" |
    sed "s/<DB_HOST>/${db_host}/g" |
    sed "s/<DB_NAME>/${db_name}/g" |
    sed "s/<DB_USERNAME>/${db_username}/g" |
    sed "s/<DB_PASSWORD>/${db_password}/g" > settings.ini

echo
echo "Fetching dependencies"
echo

composer install
git clone https://github.com/nullptr-cc/yen2.git vendor/yen
git clone https://github.com/nullptr-cc/yada.git vendor/yada
mkdir -p $bdir/src/lib
ln -s $bdir/vendor/yen/src/Yen $bdir/src/lib/Yen
ln -s $bdir/vendor/yada/src/Yada $bdir/src/lib/Yada

echo
echo "Run ${bdir}/start-devsrv.sh"
echo

popd >/dev/null
