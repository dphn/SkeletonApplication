<?php

namespace Core\Mvc;

use Core\Exception\DomainException,
    //
    Phalcon\Mvc\Application as MvcApplication,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\View,
    //
    Phalcon\DI\FactoryDefault as DiFactory,
    Phalcon\Loader,
    Phalcon\Config;

class Application extends MvcApplication
{
    /**
     * @param array $configuration
     * @return Core\Mvc\Application
     */
    public static function init($configuration = [])
    {
        $config = new Config($configuration);
        $di = new DiFactory();
        $di->setShared('config', $config);

        $application = new Application($di);

        $eventsManager = $di->getShared('eventsManager');
        $application->setEventsManager($eventsManager);

        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        $di->setShared('dispatcher', $dispatcher);

        $view = new View();
        $di->setShared('view', $view);

        return $application->bootstrap();
    }

    /**
     * @return Phalcon\Config
     */
    public function getConfig()
    {
        return $this->getDI()->get('config');
    }

    /**
     * @return Core\Mvc\Application
     */
    public function bootstrap()
    {
        $config = $this->getConfig();

        if (isset($config['module_paths'])) {
            $paths = [];
            foreach ($config['module_paths'] as $path) {
                $paths[] = realpath($path);
            }
            $loader = new Loader();
            $loader->registerDirs($paths)->register();
        }

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
        $this->registerModules($modulesConfig, true);

        if (! isset($config['default_module'])) {
            throw new DomainException(
                'Missing default module configuration.'
            );
        }
        $this->setDefaultModule($config['default_module']);

        return $this;
    }
}
