<?php

namespace ExampleForward\Controller;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    protected $externalService;

    public function indexAction()
    {
        $service = $this->getExternalService();
        $data = $service->getData();
        $this->view->setVar('externalData', $data);

        $this->view->setVar('message', 'Hello, Test!');
    }

    public function getExternalService()
    {
        if (! $this->externalService) {
            $di = $this->getDi();
            $this->externalService = $di->get(
                'ExampleDi.Service.ExampleService'
            );
        }
        return $this->externalService;
    }
}
