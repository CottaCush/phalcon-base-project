<?php

namespace App\Bootstrap;

use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use PhalconUtils\Bootstrap\BootstrapInterface;
use PhalconUtils\Middleware\OAuthMiddleware;
use PhalconUtils\Middleware\RequestLoggerMiddleware;

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
            $api->attach(function () use ($api) {
                return (new OAuthMiddleware())->call($api);
            });
        }

        if ($config->environment != 'production' && $config->debug) {
            $api->attach(new RequestLoggerMiddleware());
        }
    }
}
