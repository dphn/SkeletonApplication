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

    public function getConfig()
    {
        return [
            'router' => [
                'routes' => [
                    /* The route '/' is default route.
                     * If the default route is not specified, the framework
                     * can not determine the route, respectively, module,
                     * controller and action.
                     */
                    '/' => [
                        'route' => '/',
                        'defaults' => [
                            'module' => 'Application',
                            'controller' => 'index',
                            'action' => 'index',
                        ],
                    ],
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
    }

    public function onBootstrap($application)
    {
        $di = $application->getDI();
        $eventsManager = $application->getEventsManager();
        // your code here
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
    }
}
