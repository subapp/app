<?php

return [
    'server' => [
        'timezone' => 'Europe/Kiev',
        'displayErrors' => 'On',
        'errorLevel' => E_ALL,
    ],
    'application' => [
        'root' => __DIR__ . '/..',
        'controller' => [
            'defaultNamespace' => 'App\\Controller\\',
        ],
        // components
        'uri' => [
            'base' => '/',
            'static' => '/',
        ],
        'template' => [
            'root' => '{application.root}/views',
        ],
    ],
    'annotations' => [
        'enabled' => true,
        'controllers' => [
            'directory' => '{application.root}/controllers',
        ],
    ],
    'db' => [
        'connection' => [
            'development' => [
                'dsn' => 'mysql:host=localhost;dbname=my-site',
                'user' => 'root',
                'password' => '0000',
            ],
            'production' => [
                'dsn' => 'mysql:host=localhost;dbname=my-site',
                'user' => 'root',
                'password' => '0000',
            ],
        ],
    ],
];