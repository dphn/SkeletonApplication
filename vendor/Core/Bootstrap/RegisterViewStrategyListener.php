<?php

namespace Core\Bootstrap;

class RegisterViewStrategyListener
{
    /**
     * @const string
     */
    const DefaultViewStrategyClass = 'Core\Mvc\DefaultViewStrategy';

    public function beforeMergeConfig($event, $application)
    {
        $di = $application->getDI();
        $dispatcher = $di->get('dispatcher');
        $eventsManager = $dispatcher->getEventsManager();
        $config = $di->get('config');
        $view = $di->get('view');

        $class = static::DefaultViewStrategyClass;
        if (isset($config['view_strategy_class'])) {
            $class = $config['view_strategy_class'];
        }
        $strategy = new $class($config, $view);
        $eventsManager->attach('dispatch', $strategy);
    }
}
