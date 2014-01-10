Phalcon Skeleton Application
============================

Introduction
------------
This is a simple, skeleton application using the Phalcon MVC layer and module
systems.

Features / Goals
----------------
* Using composer. [COMPLETE]
Application adapted for use of third-party modules using [composer](http://getcomposer.org).
Just specify the third-party module in the composer.json and install it by running the command:
```$ php composer.phar update```
Thus, you can extend the functionality of your application, simply by adding the required module with the composer.

* Manage modules using the configuration. [COMPLETE]:
To use a module, simply add it in the configuration.
If any functionality you do not need, remove the module from the configuration.
```
return [
    'modules' => [
        'Application',
        'MyModule',
        ...
    ],
    ...
];
```
* Automatically merge configuration files [COMPLETE].
The default config directory is defined as follows:
```
return [
    'config_glob_paths' => [
        'config/autoload/{,*.}{global,local}.php',
    ],
];
```
Due to this configuration, from the directory "config/autoload" files are automatically loaded in the following order:
first loaded all files matching "*. global.php"
then loaded all files matching "*. local.php"
Each configuration file will overwrite the settings of the previous file.
Thus, for example, in the file "mydb.local.php" you can specify the configuration of the connection to the database
and it will overwrite the settings of the third-party module.

* Setting DI through configuration. [COMPLETE]

* Setting routes through configuration. [COMPLETE]

* Caching a merged configuration. [INCOMPLETE]

* Module feature getConfig() [COMPLETE]
Module class may provide a method `getConfig()`.
This configuration will have a higher priority than the configuration files.

* Module feature onBootstrap() [COMPLETE]
Module class may provide a method `onBootstrap()`.
If the module provides this method, it will automatically be called before handle the application.
This occurs after loading the overall configuration.
The method take as argument  an instance of the application and thus can work with any services and events before the application is handled.


