<?php

return [
    'module_paths' => [
        './module',
        './vendor',
    ],
    'modules' => [
        'Application',
        'ExampleForward',
        'ExampleDi',
    ],
    'config_glob_paths' => [
        'config/autoload/{,*.}{global,local}.php',
    ],
    'config_cache' => [
        'enabled' => false, // enable or disable configuration caching
        'lifetime' => 86400, // 24 hours
        'storage' => [
            'class' => 'Phalcon\Cache\Backend\File',
            'options' => [
                'cacheDir' => './data/cache',
            ],
        ],
    ],
    'view_strategy_class' => 'Core\Mvc\DefaultViewStrategy',
];
