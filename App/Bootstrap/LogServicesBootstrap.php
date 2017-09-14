<?php

namespace App\Bootstrap;

use App\Constants\Services;
use JeremyHarris\Papertrail\Logger;
use JeremyHarris\Papertrail\Logger as PaperTrailLogger;
use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use Phalcon\Logger\Adapter\File;
use PhalconUtils\Bootstrap\BootstrapInterface;

class LogServicesBootstrap implements BootstrapInterface
{
    public function run(Injectable $app, DiInterface $di, Config $config)
    {
        $di->set(Services::PAPERTRAIL_LOGGER, function () use ($config) {
            if (!$config->papertrail->enabled) {
                return null;
            }

            defined('PAPERTRAIL_HOST') or define('PAPERTRAIL_HOST', $config->papertrail->host);
            defined('PAPERTRAIL_PORT') or define('PAPERTRAIL_PORT', $config->papertrail->port);
            $paperTrailTarget = new PaperTrailLogger;
            return $paperTrailTarget;
        });

        $di->set(Services::FILE_LOGGER, function ($fileName) use ($config) {
            $fileTarget = new File($fileName);
            return $fileTarget;
        });

        $di->set(Services::LOGGER, function () use ($config, $di) {
            $fileTarget = new File($config->application->logsDir . 'general.log');
            $paperTrailTarget = $di->get(Services::PAPERTRAIL_LOGGER);

            $logger = new Logger([$fileTarget, $paperTrailTarget]);
            return $logger;
        });
    }
}
