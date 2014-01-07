<?php

namespace Application\Controller;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->setVar('message', 'Hello, World!');
    }
}
