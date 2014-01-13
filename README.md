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
first loaded all files matching `*. global.php`
then loaded all files matching `*. local.php`
Each configuration file will overwrite the settings of the previous file.
Thus, for example, in the file "mydb.local.php" you can specify the configuration of the connection to the database
and it will overwrite the settings of the third-party module.

* Setting DI through configuration. [COMPLETE]

You can configure DI as follows:
```
return [
    'di' => [
        'example_1' => 'Application\Example',          // shared
        'example_2' => ['Application\Example'],        // shared
        'example_3' => ['Application\Example', true],  // shared
        'example_4' => ['Application\Example', false], // not shared
    ],
    ...
],
```

* Setting routes through configuration. [COMPLETE]

You can configure routing as follows:
```
return [
    'router' => [
        'routes' => [
            'home' => [ // route name
                'route' => '/:controller/:action',
                'defaults' => [
                    'module'     => 'Application',
                    'namespace'  => 'Application\Controller',
                    'controller' => 'index',
                    'action'     => 'index',
                ],
            ],
            'forum' => [ ... ],
            ...
        ],
    ],
];
```
Parameters "module" and "namespace" are required.

* Caching a merged configuration. [COMPLETE]

By default configuration is not cached, because it is necessary when developing an application.
To enable configuration caching, set the `enabled` option:
```
return [
    'config_cache' => [
        'enabled' => true, // enable or disable configuration caching
        'lifetime' => 86400, // 24 hours
        'storage' => [
            'class' => 'Phalcon\Cache\Backend\File',
            'options' => [
                'cacheDir' => './data/cache',
            ],
        ],
    ],
    ...
];
```

* Module feature getConfig() [COMPLETE]

Module class may provide a method `getConfig()`.
This configuration will have a higher priority than the configuration files.

* Default view strategy [COMPLETE]

Using the method `getConfig()` of `Module` class, you can configure the view strategy for your module as follows:
```
public function getConfig()
{
    return [
        'view_strategy' => [
            'application' => [ // module name in lowercase
                'view_dir'       => __DIR__ . str_replace('/', DS, '/view/templates/'),
                'layouts_dir'    => str_replace('/', DS, '../layouts/'),
                'default_layout' => 'layout',
            ],
        ],
        ...
    ];
}
```
You can also specify your own view strategy through the configuration as follows:
```
return [
    'view_strategy_class' => 'MyModule\MyViewStrategy',
];
```

* Module feature onBootstrap() [COMPLETE]

Module class may provide a method `onBootstrap()`.
If the module provides this method, it will automatically be called before handle the application.
This occurs after loading the overall configuration.
The method take as argument  an instance of the application and thus can work with any services and events before the application is handled.

What gives? Suppose you have a Forum module and a module for managing access rights.
Regardless which module is executed, it calls the method onBootstrap () of *each* module.
Thus, the control of access rights module will receive their configuration and attached a `Guard Listener` for events `afterRouting` or `beforeDispatch`.
This listener will block access to the administrative part of the Forum for unauthorized users.
If you want to add a Blog module - will simply need to specify the access configuration.
