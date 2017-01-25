<?php

namespace App\Controller;

use App\Constants\ResponseCodes;
use OAuth2\Request;
use OAuth2\Response;
use OAuth2\Server;

/**
 * Class AuthController
 * @property Server oauthServer
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Controller
 */
class AuthController extends BaseController
{
    /**
     * Get access token
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed|void
     */
    public function token()
    {
        $response = $this->oauthServer->handleTokenRequest(Request::createFromGlobals());
        if (!$response->isSuccessful()) {
            return $this->response->sendError(
                ResponseCodes::AUTH_ERROR,
                $response->getStatusCode(),
                $response->getParameter('error_description')
            );
        } else {
            return $this->response->sendSuccess($response->getParameters());
        }
    }

    /**
     * Get Authorization code
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed|void
     */
    public function authorize()
    {
        $response = new Response();

        /** @var Response $response */
        $response = $this->oauthServer->handleAuthorizeRequest(Request::createFromGlobals(), $response, true);
        if (!$response->getParameters()) {
            $code = substr(
                $response->getHttpHeader('Location'),
                strpos($response->getHttpHeader('Location'), 'code=') + 5,
                40
            );
            return $this->response->sendSuccess(['code' => $code]);
        } else {
            return $this->response->sendError(
                ResponseCodes::AUTH_ERROR,
                $response->getStatusCode(),
                $response->getParameter('error_description')
            );
        }
    }
}
