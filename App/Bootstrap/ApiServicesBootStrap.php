<?php

namespace App\Bootstrap;

use App\Constants\Services;
use League\Fractal\Manager as FractalManager;
use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use PhalconUtils\Http\Response;
use PhalconUtils\Transformer\ResponseDataSerializer;

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

        /**
         * Fractal
         */
        $di->setShared(Services::FRACTAL_MANAGER, function () {
            $fractal = new FractalManager();
            $fractal->setSerializer(new ResponseDataSerializer());
            return $fractal;
        });
    }
}
