<?php

defined ('DS') || define('DS', DIRECTORY_SEPARATOR);

if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
    $loader->add('Core', __DIR__ . str_replace('/', DS, '/vendor/Core'));
    return;
}

$loader = new \Phalcon\Loader();
$loader->registerDirs([__DIR__ . DS . 'vendor'])->register();
