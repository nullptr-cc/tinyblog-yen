<?php

include_once __DIR__ . '/startup.php';

$sarray = array_merge(
    parse_ini_file(getenv('TB_SETTINGS_INI'), true),
    [
        'routing_rules' => __DIR__ . '/../res/etc/routing.rules',
        'templates' => [
            'path' => __DIR__ . '/../res/tpl',
            'ext' => '.phtml'
        ]
    ]
);

$settings = new Yen\Settings\SettingsArray($sarray);

$app = new TinyBlog\Web\Application($settings);
$app->run();
