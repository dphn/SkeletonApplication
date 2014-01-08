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

        $view = $di->getShared('view');

        // It always ends with a slash.
        $view->setViewsDir(__DIR__ . str_replace('/', DS, '/view/templates/'));

        // It should always be a path relative to the "view" directory.
        $view->setLayoutsDir(str_replace('/', DS, '../layouts/'));

        /* Behavior of the "view" can be defined as "safe" in production and
         * "unsafe" in the development. If any of the templates can not be
         * found, it just will not be displayed. It does not throw any
         * exceptions.
         */
        $view->setLayout('layout');

        $router = $di->getShared('router');
        $router->add('/:controller/:action', [
            'module'     => 'Application',
            'controller' => 'index',
            'action'     => 'index',
        ]);
    }
}
