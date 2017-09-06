<?php

namespace App\Bootstrap;

use App\Constants\Services;
use Phalcon\Config;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File;
use PhalconUtils\Bootstrap\BootstrapInterface;
use PhalconUtils\Bootstrap\DefaultServicesBootstrap;

/**
 * Class BaseServicesBootStrap
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Library\BootStrap
 */
abstract class BaseServicesBootStrap extends DefaultServicesBootstrap implements BootstrapInterface
{
    public function run(Injectable $app, DiInterface $di, Config $config)
    {
        parent::run($app, $di, $config);

        $di->setShared(Services::DB, function () use ($config) {
            $connection = new DbAdapter([
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
                'persistent' => (php_sapi_name() == 'cli') ? true : false
            ]);

            if ($config->debug) {
                $eventsManager = new EventsManager();
                $logger = new File($config->application->logsDir . "sql_debug.log");

                $eventsManager->attach('db', function ($event, $connection) use ($logger) {
                    if ($event->getType() == 'beforeQuery') {
                        /** @var DbAdapter $connection */
                        $logger->log($connection->getSQLStatement(), Logger::DEBUG);
                    }
                });
                $connection->setEventsManager($eventsManager);
            }

            return $connection;
        });

        $di->set(Services::LOGGER, function () use ($config) {
            $logger = new \PhalconUtils\Util\Logger($config->application->logsDir . "general.log");
            return $logger;
        });
    }
}
