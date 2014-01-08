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

        $application = new Application();
        $application->setDI($di);

        $eventsManager = $di->getShared('eventsManager');
        $application->setEventsManager($eventsManager);

        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        $di->setShared('dispatcher', $dispatcher);

        $view = new View();
        $di->setShared('view', $view);

        return $application->bootstrap($di);
    }

    /**
     * @return Core\Mvc\Application
     */
    public function bootstrap($di)
    {
        $config = $di->get('config');

        if (isset($config['config_glob_paths'])) {
            $globPats = (array) $config['config_glob_paths'];
            foreach ($globPats as $path) {
                $files = glob($path, GLOB_BRACE);
                foreach ($files as $file) {
                    $item = require $file;
                    if ($item instanceof Config) {
                        $config->merge($item);
                        continue;
                    }
                    if (! is_array($item)) {
                        continue;
                    }
                    $item = new Config($item);
                    $config->merge($item);
                }
            }
        }

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
                $router->add($routeOptions['route'], (array) $routeOptions['defaults'])
                    ->setName($routeName);
            }
        }

        return $this;
    }
}
