<?php

namespace App\Middleware;

use App\Constants\Services;
use App\Library\Response;
use App\Constants\ResponseCodes;
use OAuth2\Request;
use OAuth2\Server;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use PhalconRest\Mvc\Plugin;
use Phalcon\Http\Request as HttpRequest;

/**
 * Class OAuthMiddleware
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Middleware
 */
class OAuthMiddleware extends Plugin implements MiddlewareInterface
{
    /**
     * check if path is excluded from authentication
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    private function isExcludedPath()
    {
        $request = new HttpRequest();
        $basePath = $request->getQuery('_url');
        $excludedPaths = $this->getDI()->get(Services::CONFIG)->oauth->excluded_paths;

        if (is_null($excludedPaths)) {
            $excludedPaths = [];
        } else {
            $excludedPaths = $excludedPaths->toArray();
        }

        foreach ($excludedPaths as $key => $value) {
            if (substr($basePath, 0, strlen($value)) == $value && $value != '/') {
                return true;
            }
        }

        return false;
    }

    public function call(Micro $application)
    {
        if ($this->isExcludedPath()) {
            return true;
        }

        /** @var Server $oauthServer */
        $oauthServer = $params = $this->getDI()->get(Services::OAUTH_SERVER);

        /** @var Response $response */
        $response = $this->getDI()->get(Services::RESPONSE);
        if (!$oauthServer->verifyResourceRequest(new Request($this->getDI()->get(Services::REQUEST)->getQuery()))) {
            $oauthServerResponse = $oauthServer->getResponse();
            if (!$oauthServerResponse->getParameters()) {
                $response->sendError(ResponseCodes::AUTH_ACCESS_TOKEN_REQUIRED, $oauthServerResponse->getStatusCode());
            } else {
                $response->sendError(
                    ResponseCodes::AUTH_ERROR,
                    $oauthServerResponse->getStatusCode(),
                    $oauthServerResponse->getParameter('error_description')
                );
            }
            return false;
        }

        return true;
    }
}
