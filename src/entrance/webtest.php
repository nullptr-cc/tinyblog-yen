<?php

/**
 * Entry script for web tests
 */

if (preg_match('~\.(css|js|ico|png|jpg|otf|woff|woff2)$~', $_SERVER['REQUEST_URI'])) {
    return false;
};

if ($_SERVER['REQUEST_URI'] == '/__cc/start') {
    $ccid = uniqid('cc_');
    mkdir(getenv('COVERAGE_PATH') . '/' . $ccid, 0755, true);
    setcookie('__ccid',  $ccid, 0, '/');
    die('Ok');
};

if (strpos($_SERVER['REQUEST_URI'], '/__cc/get') === 0) {
    $path = getenv('COVERAGE_PATH') . '/' . $_GET['ccid'];
    $dir =
        new RegexIterator(
            new FilesystemIterator($path, FilesystemIterator::CURRENT_AS_PATHNAME),
            '~\.json$~'
        );
    $data = [];
    foreach ($dir as $file) {
        $data[] = json_decode(file_get_contents($file), true);
    };
    header('Content-Type: application/json');
    echo json_encode($data);
    die();
};

$enable_cc =
    function_exists('xdebug_get_code_coverage') &&
    isset($_COOKIE['__ccid']);

if ($enable_cc) {
    xdebug_start_code_coverage(XDEBUG_CC_UNUSED);
};

include_once __DIR__ . '/web.php';

if ($enable_cc) {
    xdebug_stop_code_coverage(false);
    $coverage = xdebug_get_code_coverage();
    $path = sprintf('%s/%s/%s.json', getenv('COVERAGE_PATH'), $_COOKIE['__ccid'], uniqid('json_'));
    file_put_contents($path, json_encode($coverage));
};
