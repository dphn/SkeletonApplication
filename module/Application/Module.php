<?php

namespace Application;

use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces([
            'Application' => __DIR__ . DS . 'src',
        ]);
        $loader->register();
    }

    public function registerServices($di)
    {
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace('Application\Controller\\');

        $view = $di->get('view');
        $view->setViewsDir(__DIR__ . DS . 'views');

        $router = $di->getShared('router');
        $router->add('/:controller/:action', [
            'module'     => 'Application',
            'controller' => 'index',
            'action'     => 'index',
        ]);
    }
}
