<?php

namespace ExampleForward;

use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces([
            'ExampleForward' => __DIR__ . DS . 'src',
        ]);
        $loader->register();
    }

    public function getConfig()
    {
        return [
            'view_strategy' => [
                'exampleforward' => [ // module name in lowercase
                    'view_dir'       => __DIR__ . str_replace('/', DS, '/view/templates/'),
                    'layouts_dir'    => str_replace('/', DS, '../layouts/'),
                    'default_layout' => 'layout',
                ],
            ],
        ];
    }

    public function registerServices($di)
    {

    }
}
