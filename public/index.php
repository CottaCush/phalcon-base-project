<?php

use App\Bootstrap\ApiServicesBootStrap;
use App\Bootstrap\MiddlewareBootstrap;
use App\Bootstrap\RouteBootstrap;
use App\Bootstrap\BaseServicesBootStrap;
use App\Constants\HttpStatusCodes;
use App\Constants\ResponseCodes;
use App\Constants\ResponseMessages;
use Phalcon\Logger;
use Phalcon\Mvc\Micro;

ini_set('display_errors', "On");
error_reporting(E_ALL);

//include Composer Auto Loader
include __DIR__ . "/../vendor/autoload.php";

$dotenv = new Dotenv\Dotenv(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'env');
$dotenv->load();

$env = getenv('APPLICATION_ENV');
$config = include __DIR__ . "/../app/config/config.php";


//include Phalcon Loader
include __DIR__ . "/../app/config/loader.php";

// Instantiate application & DI
$di = new PhalconRest\Di\FactoryDefault();
$app = new PhalconRest\Api($di);


// Bootstrap components
$bootstrap = new \App\Bootstrap\Bootstrap(
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
