<?php

namespace ExampleDi;

use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces([
            'ExampleDi' => __DIR__ . DS . 'src',
        ]);
        $loader->register();
    }

    public function getConfig()
    {
        return [
            'di' => [
                'ExampleDi.Service.ExampleService' => 'ExampleDi\Service\ExampleService',
            ],
        ];
    }

    public function registerServices($di)
    {

    }
}