<?php

namespace Test\Controller;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->setVar('message', 'Hello, Test!');
    }
}
