<?php

return [
    'router' => [
        'routes' => [
            'home' => [
                'route' => '/:controller/:action',
                'defaults' => [
                    'module' => 'Application',
                    'controller' => 'index',
                    'action' => 'index',
                ],
            ],
        ],
    ],
];