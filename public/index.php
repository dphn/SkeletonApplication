<?php

chdir(dirname(__DIR__));

require 'init_autoloader.php';

if (! extension_loaded('phalcon')) {
    exit('Phalcon extension is not installed. See http://phalconphp.com/en/download');
}

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

$application = Core\Mvc\Application::init(require './config/application.config.php');
echo $application->handle()->getContent();
