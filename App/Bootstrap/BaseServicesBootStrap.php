<?php

namespace App\Bootstrap;

use App\Interfaces\BootstrapInterface;
use App\Constants\Services;
use App\Library\Response;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\Server;
use OAuth2\Storage\Pdo;
use Phalcon\Cli\Console;
use Phalcon\Config;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Security;
use PhalconRest\Api;
use Pheanstalk\Pheanstalk;
use Simpleue\Queue\BeanStalkdQueue;

/**
 * Class BaseServicesBootStrap
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Library\BootStrap
 */
abstract class BaseServicesBootStrap implements BootstrapInterface
{

    public function run(Injectable $app, DiInterface $di, Config $config)
    {
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

        $di->setShared(Services::MODELS_MANAGER, function () {
            return new ModelsManager();
        });

        $di->setShared(Services::SECURITY, function () {
            $security = new Security();
            $security->setWorkFactor(12);
            return $security;
        });

        $di->set(Services::CONFIG, $config);
        

        $di->set(Services::LOGGER, function () use ($config) {
            $logger = new File($config->application->logsDir . "general.log");
            return $logger;
        });
    }
}
