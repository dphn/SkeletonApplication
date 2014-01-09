<?php

namespace Core\Mvc;

use Phalcon\Mvc\Application as MvcApplication,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\View,
    //
    Phalcon\DI\FactoryDefault as DiFactory,
    Phalcon\Http\Response,
    Phalcon\Config,
    //
    Exception;

class Application extends MvcApplication
{
    protected static $debugMode = true;

    /**
     * @var array
     */
    protected $defaultBootstrapListeners = [
        '\Core\Bootstrap\MergeConfigListener',
        '\Core\Bootstrap\RegisterModulesPathsListener',
        '\Core\Bootstrap\RegisterModulesListener',
        '\Core\Bootstrap\RegisterRoutesListener',
    ];

    /**
     * @param boolean $flag
     * @return void
     */
    public static function setDebugMode($flag = true)
    {
        static::$debugMode = (bool) $flag;
    }

    /**
     * @return boolean
     */
    public static function isDebugMode()
    {
        return static::$debugMode;
    }

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

        return $application->bootstrap();
    }

    /**
     * @return Core\Mvc\Application
     */
    public function bootstrap()
    {
        $eventsManager = $this->getEventsManager();

        foreach ($this->defaultBootstrapListeners as $listener) {
            $listener = new $listener();
            $eventsManager->attach('bootstrap', $listener);
        }

        return $this;
    }

    /**
     * @param string $url optional
     * @return Phalcon\Http\ResponseInterface
     */
    public function handle($url = '')
    {
        try {
            $eventsManager = $this->getEventsManager();
            $eventsManager->fire('bootstrap:beforeHandle', $this);
            return parent::handle($url);
        } catch (Exception $e) {
            $response = new Response();
            if (Application::isDebugMode()) {
                $response->setContent($e);
            }
            return $response;
        }
    }
}
