<?php

return [
  'application' => [
    'root' => __DIR__ . '/..',
    'static_path' => '/webapp/test-app/',
    'base_path' => '/webapp/test-app/',
    'autoload' => [
      'App\\Controller' => __DIR__ . '/../controllers',
    ],
    'model' => [
      'root_directory' => '/var/www/site/app/models'
    ],
    'controller' => [
//            'root_directory' => '/var/www/site/app/controllers',
      'namespace' => 'App\\Controller\\',
    ],
    'view' => [
      'root_directory' => __DIR__ . '/../views',
    ],
  ],
  'annotations' => [
    'enabled' => true,
    'controllers' => [
      'directory' => '{application.root}/controllers',
    ]
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
    'server' => [
  'timezone' => 'Europe/Kiev',
  'displayErrors' => 'On',
  'errorLevel' => E_ALL,
],
];