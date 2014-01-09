<?php

namespace Core\Bootstrap;

use Core\Exception\DomainException;

class LoadModulesListener
{
    public function loadModules($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        $modules = $application->getModules();
        foreach ($modules as $moduleOptions) {
            $class = $moduleOptions['className'];
            $module = new $class();
            if (method_exists($module, 'registerAutoloaders')) {
                $module->registerAutoloaders();
            }
            if (method_exists($module, 'onBootstrap')) {
                $module->onBootstrap($application);
            }
        }
    }
}
