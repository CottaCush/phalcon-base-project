<?php

namespace App\Bootstrap;

use App\Constants\Services;
use Phalcon\Config;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use Phalcon\Events\Manager as EventsManager;
use PhalconUtils\Bootstrap\BootstrapInterface;
use PhalconUtils\Bootstrap\DefaultServicesBootstrap;
use PhalconUtils\Util\Logger;

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

        $di->setShared(Services::DB, function () use ($config, $di) {
            $connection = new DbAdapter([
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
                'persistent' => (php_sapi_name() == 'cli') ? true : false
            ]);

            if ($config->debug) {
                $eventsManager = new EventsManager();
                $fileTarget = $di->get(Services::FILE_LOGGER, [$config->application->logsDir . 'sql_debug.log']);
                $paperTrailTarget = $di->get(Services::PAPERTRAIL_LOGGER);

                $logger = new Logger([$fileTarget, $paperTrailTarget]);

                $eventsManager->attach(Services::DB, function ($event, $connection) use ($logger) {
                    if ($event->getType() == 'beforeQuery') {
                        /** @var DbAdapter $connection */
                        $logger->debug($connection->getSQLStatement());
                    }
                });
                $connection->setEventsManager($eventsManager);
            }

            return $connection;
        });
    }
}
