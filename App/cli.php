<?php

use App\Bootstrap\ConsoleServicesBootStrap;
use Phalcon\CLI\Console as ConsoleApp;
use Phalcon\DI;
use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Exception;
use Phalcon\Loader;

$di = new Cli();

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__)));

define('VERSION', '2.0.7');

ini_set('display_errors', "On");
error_reporting(E_ALL);

//include Composer Auto Loader
include __DIR__ . "/../vendor/autoload.php";

//Load Configuration
$dotenv = new Dotenv\Dotenv(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'env');
$dotenv->load();
$env = getenv('APPLICATION_ENV');
$config = include __DIR__ . "/../App/Config/config_cli.php";


//include Phalcon Loader
include __DIR__ . "/../App/Config/loader.php";

/**
 * Process the console arguments
 */
$arguments = array();
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

$console = new ConsoleApp($di);
$bootstrap = new \App\Bootstrap\Bootstrap(new ConsoleServicesBootStrap());
$bootstrap->run($console, $di, $config);


define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));


try {
    $console->handle($arguments);
} catch (Exception $e) {
    echo $e->getMessage();
    exit(255);
}
