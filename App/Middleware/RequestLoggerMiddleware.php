<?php

namespace App\Middleware;

use App\Constants\Services;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use PhalconRest\Mvc\Plugin;

/**
 * Class LoggerMiddleware
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Middleware
 */
class RequestLoggerMiddleware extends Plugin implements MiddlewareInterface
{
    public function beforeExecuteRoute()
    {
        /** @var \Phalcon\Http\Request $request */
        $request = $this->getDI()->get(Services::REQUEST);
        
        $config = $this->getDI()->get(Services::CONFIG);
        
        $logger = new File($config->application->logsDir . "requests.log");
        
        $logger->log('Request URL:' . $request->getURI(), Logger::INFO);
        if ($request->isPost() || $request->isPut()) {
            $rawBody = $request->getRawBody();
            $logger->log('Request Body: ' . $rawBody, Logger::INFO);
        }
    }

    public function call(Micro $application)
    {
        return true;
    }
}
