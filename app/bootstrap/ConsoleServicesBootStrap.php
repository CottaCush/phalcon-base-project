<?php

namespace App\Bootstrap;

use App\Constants\Services;
use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;

/**
 * Class ConsoleServicesBootStrap
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Library\BootStrap
 */
class ConsoleServicesBootStrap extends BaseServicesBootStrap
{

    public function run(Injectable $app, DiInterface $di, Config $config)
    {
        parent::run($app, $di, $config);

        $di->set(Services::CONSOLE, function () use ($app) {
            return $app;
        });
    }
}
