<?php

include_once __DIR__ . '/startup.php';

$sarray = array_merge_recursive(
    parse_ini_file(getenv('TB_SETTINGS_INI'), true),
    [
        'web' => [
            'routing_rules' => realpath(__DIR__ . '/../res/etc/routing.rules'),
            'templates' => [
                'path' => realpath(__DIR__ . '/../res/tpl'),
                'ext' => '.phtml'
            ]
        ]
    ]
);

$settings = new Yen\Settings\SettingsArray($sarray);

$app = new TinyBlog\WebApplication($settings);
$app->run();
