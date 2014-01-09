<?php

namespace Core\Bootstrap;

use Core\Exception\DomainException;

class RegisterModulesListener
{
    public function loadModules($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        $modulesConfig = [];
        if (isset($config['modules'])) {
            foreach ($config['modules'] as $moduleNamespace) {
                $modulesConfig[$moduleNamespace] = [
                    'className' => $moduleNamespace . '\Module',
                ];
            }
        }
        if (empty($modulesConfig)) {
            throw new DomainException(
                'Missing configuration of modules.'
            );
        }
        $application->registerModules($modulesConfig, true);

        if (! isset($config['default_module'])) {
            throw new DomainException(
                'Missing default module configuration.'
            );
        }
        $application->setDefaultModule($config['default_module']);
    }
}
