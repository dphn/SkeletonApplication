<?php

return [
    'module_paths' => [
        './module',
        './vendor',
    ],
    'modules' => [
        'Application',
    ],
    'config_glob_paths' => [
        'config/autoload/{,*.}{global,local}.php',
    ],
];
