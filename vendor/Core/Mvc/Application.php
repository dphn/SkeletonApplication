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
    Phalcon\Debug,
    //
    Exception;

class Application extends MvcApplication
{
    /**
     * @var boolean
     */
    protected static $debugMode = false;

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
        $reportingLevel = $flag ? E_ALL | E_STRICT : 0;
        error_reporting($reportingLevel);

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
        static $application;

        if ($application instanceof Application) {
            return $application;
        }

        Application::setDebugMode(Application::isDebugMode());

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
            if (Application::isDebugMode()) {
                (new Debug())->onUncaughtException($e);
            }
            return new Response();
        }
    }
}
