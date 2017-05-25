<?php

namespace App\Bootstrap;

use App\Interfaces\BootstrapInterface;
use App\Middleware\RequestLoggerMiddleware;
use App\Middleware\OAuthMiddleware;
use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use PhalconRest\Api;

/**
 * Class MiddlewareBootstrap
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Bootstrap\Bootstrap
 */
class MiddlewareBootstrap implements BootstrapInterface
{
    public function run(Injectable $api, DiInterface $di, Config $config)
    {
        if ($config->oauth->enabled) {
            $api->attach(new OAuthMiddleware());
        }

        if ($config->environment != 'production' && $config->debug) {
            $api->attach(new RequestLoggerMiddleware());
        }
    }
}
