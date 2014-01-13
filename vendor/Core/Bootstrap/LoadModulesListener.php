<?php

namespace Core\Bootstrap;

use ReflectionClass;

class LoadModulesListener
{
    public function init($event, $application)
    {
        $modules = $application->getModules();
        foreach ($modules as &$moduleOptions) {
            $class = $moduleOptions['className'];

            $reflection = new ReflectionClass($class);
            $moduleOptions['path'] = $reflection->getFileName();

            $module = new $class();
            $moduleOptions['object'] = $module;
            if (method_exists($module, 'registerAutoloaders')) {
                $module->registerAutoloaders();
            }
        }
        $application->registerModules($modules, true);
    }
}
