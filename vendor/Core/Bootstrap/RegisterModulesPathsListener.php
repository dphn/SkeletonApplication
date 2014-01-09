<?php

namespace Core\Bootstrap;

use Phalcon\Loader;

class RegisterModulesPathsListener
{
    public function beforeHandle($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        if (isset($config['module_paths'])) {
            $paths = [];
            foreach ($config['module_paths'] as $path) {
                $paths[] = realpath($path);
            }
            $loader = new Loader();
            $loader->registerDirs($paths)->register();
        }
    }
}
