#!/usr/bin/env bash

bdir=`realpath $0 | xargs dirname | xargs dirname`
ccdir=$bdir/assets/coverage
mkdir -p $ccdir
env TB_SETTINGS_INI=$bdir/settings-webtest.ini \
    COVERAGE_PATH=$ccdir \
    php -S 0.0.0.0:9188 -t $bdir/assets $bdir/src/entrance/webtest.php && rm -rf $ccdir
