<?php

namespace ExampleDi\Service;

class ExampleService
{
    protected $data = "These data were obtained using di, of the 'ExampleDi' module.";

    public function setData($string)
    {
        $this->data = $string;
    }

    public function getData()
    {
        return $this->data;
    }
}
