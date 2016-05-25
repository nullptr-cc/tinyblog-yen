<?php

include_once __DIR__ . '/../vendor/autoload.php';

set_include_path(
    get_include_path() . PATH_SEPARATOR .
    __DIR__ . '/../src/app' . PATH_SEPARATOR .
    __DIR__ . '/../src/lib' . PATH_SEPARATOR .
    __DIR__
);

spl_autoload_register(
    function ($classname)
    {
        $fname = str_replace('\\', '/', $classname) . '.php';
        if ($realpath = stream_resolve_include_path($fname)) {
            include_once $realpath;
            return true;
        };

        return false;
    }
);

define('DBOPTS_PATH', __DIR__ . '/dbopts');
define('DBFIXT_PATH', DBOPTS_PATH . '/fixtures');
define('TCDATA_PATH', __DIR__ . '/case-data');
