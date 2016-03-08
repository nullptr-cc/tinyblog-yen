<?php

include_once __DIR__ . '/../../vendor/autoload.php';

set_error_handler(function ($no, $msg, $file, $line) {
    throw new \Yen\Core\Exception($msg, $no, null, $file, $line);
});

define('APP_DIR', realpath(__DIR__ . '/..'));
