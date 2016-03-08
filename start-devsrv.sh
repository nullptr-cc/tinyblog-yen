#!/usr/bin/env bash

bdir=`dirname $0 | xargs realpath`
env TB_SETTINGS_INI=$bdir/settings.ini php -S 0.0.0.0:9988 -t $bdir/assets $bdir/src/entrance/dev.php
