<?php

use Phalcon\Loader;

$loader = new Loader();

$loader->registerDirs([
    $config->application->appDir,
    $config->application->tasksDir
]);


$loader->registerNamespaces([
    'App' => $config->application->appDir,
    'App\Task' => $config->application->tasksDir
])->register();
