<?php

use App\Bootstrap\ApiServicesBootStrap;
use App\Bootstrap\Bootstrap;
use App\Bootstrap\MiddlewareBootstrap;
use App\Bootstrap\RouteBootstrap;
use App\Constants\HttpStatusCodes;
use App\Constants\ResponseCodes;
use App\Constants\ResponseMessages;

date_default_timezone_set('UTC');

ini_set('display_errors', "On");
error_reporting(E_ALL);

//include Composer Auto Loader
include __DIR__ . "/../vendor/autoload.php";

$envFile = ((getenv('APPLICATION_ENV') == 'test') ? '.env.test' : '.env');
$dotenv = new Dotenv\Dotenv(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'env', $envFile);
$dotenv->load();

$env = getenv('APPLICATION_ENV');
$config = include __DIR__ . "/../App/Config/config.php";

ini_set('display_errors', (($config->debug) ? "On" : "Off"));

//include Phalcon Loader
include __DIR__ . "/../App/Config/loader.php";

// Instantiate application & DI
$di = new PhalconRest\Di\FactoryDefault();
$app = new PhalconRest\Api($di);


// Bootstrap components
$bootstrap = new Bootstrap(
    new ApiServicesBootStrap,
    new MiddlewareBootstrap,
    new RouteBootstrap
);

$bootstrap->run($app, $di, $config);


//handle invalid routes
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, HttpStatusCodes::getMessage(404))->sendHeaders();
    $app->response->setContentType('application/json');
    $app->response->setJsonContent(
        [
            'status' => 'error',
            'message' => ResponseMessages::getMessageFromCode(ResponseCodes::METHOD_NOT_IMPLEMENTED),
            'code' => ResponseCodes::METHOD_NOT_IMPLEMENTED
        ]
    );

    $app->response->send();
});


//handle errors and exceptions
$app->error(function ($exception) use ($app) {
    $app->response->setContentType('application/json');
    $app->response->setStatusCode(500, HttpStatusCodes::getMessage(500))->sendHeaders();
    $app->response->setJsonContent(
        [
            'status' => 'error',
            'message' => ResponseMessages::getMessageFromCode(ResponseCodes::UNEXPECTED_ERROR),
            'code' => ResponseCodes::UNEXPECTED_ERROR,
            'ex' => $exception->getMessage()
        ]
    );

    $app->response->send();
});


$app->handle();
