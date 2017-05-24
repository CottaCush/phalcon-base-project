<?php

namespace App\Service;

use App\Constants\Services;
use App\Library\Logger;
use Phalcon\Di;
use Phalcon\Http\Client\Response as HttpResponse;
use App\Library\HttpClient;

/**
 * Class BaseGateway
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Service
 */
abstract class BaseGateway
{
    /** @var  $logger Logger */
    public $logger;
    protected $httpClient;
    protected $baseUrl;
    protected $lastError;

    public function __construct($baseUrl, $timeout)
    {
        $this->httpClient = new HttpClient($timeout);
        $this->baseUrl = $baseUrl;
        $this->logger = Di::getDefault()->get(Services::LOGGER);
    }

    /**
     * Build URL
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $url
     * @return string
     */
    public function buildURL($url)
    {
        return $this->baseUrl . $url;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $response HttpResponse
     * @param bool $default
     * @param bool $convertToArray
     * @param int $successCode
     * @return mixed
     */
    public function decodeJsonResponse($response, $default = false, $convertToArray = false, $successCode = 200)
    {
        if (!($response instanceof HttpResponse)) {
            $this->logger->error(get_called_class() . ' Service Error: ' . $response);
            $this->lastError = $response;
            return $default;
        }

        if (!HttpClient::isSuccessful($response, $successCode)) {
            $this->logger->error(get_called_class() . ' Service Error: ' . $response->body);
            $this->lastError = $response->body;
            return $default;
        }

        $decodedResponse = json_decode($response->body, $convertToArray);
        if (!$decodedResponse) {
            return $default;
        }

        return $decodedResponse;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function getLastError()
    {
        return ($this->lastError) ?: 'Unknown Error';
    }
}
