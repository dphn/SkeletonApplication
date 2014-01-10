<?php

namespace Core\Bootstrap;

use Core\Exception\DomainException;

class RegisterRoutesListener
{
    public function afterMergeConfig($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        $router = $di->getShared('router');
        if (isset($config['router']['routes'])) {
            $routes = $config['router']['routes'];
            foreach ($routes as $routeName => $routeOptions) {
                if (! isset($routeOptions['route'])) {
                    throw new DomainException(sprintf(
                        "Missing option 'route' for the route '%s'.",
                        $routeName
                    ));
                }
                if (! isset($routeOptions['defaults']['module'])) {
                    throw new DomainException(sprintf(
                        "Missing default option 'module' for the route '%s'",
                        $routeName
                    ));
                }
                if (! isset($routeOptions['defaults']['namespace'])) {
                    throw new DomainException(sprintf(
                        "Missing default option 'namespace' for the route '%s'",
                        $routeName
                    ));
                }
                $router->add($routeOptions['route'], (array) $routeOptions['defaults'])
                    ->setName($routeName);
            }
        }
    }
}
