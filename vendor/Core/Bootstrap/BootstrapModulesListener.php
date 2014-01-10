<?php

namespace Core\Bootstrap;

class BootstrapModulesListener
{
    public function bootstrapModules($event, $application)
    {
        $modules = $application->getModules();
        foreach ($modules as $moduleOptions) {
            if (! isset($moduleOptions['object'])
                || ! is_object($moduleOptions['object'])
            ) {
                continue;
            }
            $module = $moduleOptions['object'];
            if (method_exists($module, 'onBootstrap')) {
                $module->onBootstrap($application);
            }
        }
    }
}
