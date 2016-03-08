<?php

/**
 * Entry script for PHP built-in web-server
 */

if (preg_match('~\.(css|js|ico|png|jpg|otf|woff|woff2)$~', $_SERVER['REQUEST_URI'])) {
    return false;
};

include_once __DIR__ . '/web.php';
