<?php

use Phalcon\Loader;

$loader = new Loader();

$loader->registerDirs([
    $config->application->modelsDir,
    $config->application->controllersDir,
    $config->application->libsDir,
    $config->application->interfacesDir,
    $config->application->appDir,
    $config->application->constantsDir,
    $config->application->middlewaresDir,
    $config->application->tasksDir
]);


$loader->registerNamespaces([
    'App\Model' => $config->application->modelsDir,
    'App\Controller' => $config->application->controllersDir,
    'App\Library' => $config->application->libsDir,
    'App\CInterface' => $config->application->interfacesDir,
    'App' => $config->application->appDir,
    'App\Constants' => $config->application->constantsDir,
    'App\Middleware' => $config->application->middlewaresDir,
    'App\Task' => $config->application->tasksDir
])->register();
