<?php

namespace App\Bootstrap;

use App\Constants\Services;
use App\Library\Response;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\Server;
use OAuth2\Storage\Pdo;
use Phalcon\Cli\Console;
use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File;
use Phalcon\Security;
use PhalconRest\Api;

/**
 * Class ApiServicesBootStrap
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Library\BootStrap
 */
class ApiServicesBootStrap extends BaseServicesBootStrap
{

    public function run(Injectable $app, DiInterface $di, Config $config)
    {
        parent::run($app, $di, $config);

        $di->set(Services::RESPONSE, function () {
            return new Response();
        });

        $di->setShared(Services::OAUTH_SERVER, function () use ($di, $config) {
            $storage = new Pdo($di['db']->getInternalHandler());

            $server = new Server($storage, [
                'always_issue_new_refresh_token' => $config->oauth->always_issue_new_refresh_token,
                'refresh_token_lifetime' => $config->oauth->refresh_token_life_time,
                'access_lifetime' => $config->oauth->access_token_life_time,
                'id_lifetime' => $config->oauth->access_token_life_time
            ]);

            $server->addGrantType(new ClientCredentials($storage));
            $server->addGrantType(
                new RefreshToken(
                    $storage,
                    ['always_issue_new_refresh_token' => $config->oauth->always_issue_new_refresh_token]
                )
            );
            return $server;
        });
    }
}
