<?php

namespace Core\Mvc;

class DefaultViewStrategy
{
    protected $config;

    protected $view;

    public function __construct($config, $view)
    {
        $this->config = $config;
        $this->view = $view;
    }

    public function beforeRequest($event, $dispatcher)
    {
        $this->registerView($event, $dispatcher);
    }

    public function beforeDispatch($event, $dispatcher)
    {
        $this->registerView($event, $dispatcher);
    }

    protected function registerView($event, $dispatcher)
    {
        $module = strtolower($dispatcher->getModuleName());
        $namespace = strtolower($dispatcher->getNamespaceName());

        if (false === strpos($namespace, $module)) {
            $pos = strpos($namespace, '\\');
            if (false === $pos) {
                $module = $namespace;
            } else {
                $module = substr($namespace, 0, $pos);
            }
        }

        if (! isset($this->config['view_strategy'][$module])) {
            return;
        }

        $options = $this->config['view_strategy'][$module];

        if (isset($options['view_dir'])) {
            $this->view->setViewsDir($options['view_dir']);
        }

        if (isset($options['layouts_dir'])) {
            $this->view->setLayoutsDir($options['layouts_dir']);
        }

        if (isset($options['default_layout'])) {
            $this->view->setLayout($options['default_layout']);
        }
    }
}
