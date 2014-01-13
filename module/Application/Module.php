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
                'not_found_route' => [
                    'module' => 'Application',
                    'namespace' => 'Application\Controller',
                    'controller' => 'index',
                    'action' => 'notFound',
                ],
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
                            'namespace' => 'Application\Controller',
                            'controller' => 'index',
                            'action' => 'index',
                        ],
                    ],
                    'home' => [
                        'route' => '/:controller/:action',
                        'defaults' => [
                            'module' => 'Application',
                            'namespace' => 'Application\Controller',
                            'controller' => 'index',
                            'action' => 'index',
                        ],
                    ],
                ],
            ],
            'view_strategy' => [
                'application' => [ // module name in lowercase
                    'view_dir'       => __DIR__ . str_replace('/', DS, '/view/templates/'),
                    'layouts_dir'    => str_replace('/', DS, '../layouts/'),
                    'default_layout' => 'layout',
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

    }
}
