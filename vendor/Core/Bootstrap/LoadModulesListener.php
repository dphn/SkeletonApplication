<?php

namespace Core\Bootstrap;

class LoadModulesListener
{
    public function init($event, $application)
    {
        $modules = $application->getModules();
        foreach ($modules as &$moduleOptions) {
            $class = $moduleOptions['className'];
            $module = new $class();
            $moduleOptions['object'] = $module;
            if (method_exists($module, 'registerAutoloaders')) {
                $module->registerAutoloaders();
            }
        }
        $application->registerModules($modules, true);
    }
}
