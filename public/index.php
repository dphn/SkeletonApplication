<?php

if (version_compare(PHP_VERSION, '5.4.0', '<=')) {
    exit(sprintf(
        'To run this application required PHP >= 5.4.0 Current version is %s.',
        PHP_VERSION
    ));
}
if (! extension_loaded('phalcon')) {
    exit('Phalcon extension is not installed. See http://phalconphp.com/en/download');
}

chdir(dirname(__DIR__));
require 'init_autoloader.php';

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

$application = Core\Mvc\Application::init(require './config/application.config.php');
echo $application->handle()->getContent();
