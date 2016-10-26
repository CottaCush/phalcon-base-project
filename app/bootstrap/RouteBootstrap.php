<?php

namespace App\Bootstrap;

use App\Bootstrap\Bootstrap;
use App\CInterface\BootstrapInterface;
use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use PhalconRest\Api;
use Phalcon\Mvc\Micro\Collection as RouteHandler;

/**
 * Class RouteBootstrap
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Bootstrap\Bootstrap
 */
class RouteBootstrap implements BootstrapInterface
{

    public function run(Injectable $api, DiInterface $di, Config $config)
    {
        //version routes
        $router = new RouteHandler();
        $router->setHandler('App\Controller\VersionController', true);
        $router->get('/', 'index');
        $api->mount($router);

        //auth routes
        $router = new RouteHandler();
        $router->setHandler('App\Controller\AuthController', true);
        $router->setPrefix('/oauth');
        $router->post('/token', 'token');
        $router->post('/authorize', 'authorize');
        $api->mount($router);
    }
}
