<?php

namespace Core\Bootstrap;

use Phalcon\Config;

class MergeModulesConfigListener
{
    public function mergeConfig($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        $modules = $application->getModules();
        foreach ($modules as &$moduleOptions) {
            if (! isset($moduleOptions['object'])) {
                continue;
            }
            $module = $moduleOptions['object'];
            if (method_exists($module, 'getConfig')) {
                $moduleConfig = $module->getConfig();
                if ($moduleConfig instanceof Config) {
                    $config->merge($moduleConfig);
                    continue;
                }
                if (is_array($moduleConfig)) {
                    $config->merge(new Config($moduleConfig));
                }
            }
        }
    }
}
