<?php

namespace Application\Controller;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->dispatcher->forward([
            "namespace"   => "ExampleForward\Controller",
            "controller" => "index",
            "action" => "index",
        ]);
        //$this->view->setVar('message', 'Hello, World!');
    }

    public function notFoundAction()
    {

    }
}
