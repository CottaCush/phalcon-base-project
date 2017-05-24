<?php

namespace App\Library;

use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Http\Client\Request;
use Phalcon\Http\Client\Response as ClientResponse;

/**
 * Class HttpClient
 * @author Adeyemi Olaoye <yemexx1@gmail.com>
 * @package App\Library
 */
class HttpClient
{
    protected $provider;

    public function __construct($timeout = null)
    {
        $this->provider = Request::getProvider();
        if ($this->provider instanceof Curl) {
            if (!is_null($timeout)) {
                $this->provider->setTimeout(intval($timeout));
            }
            $this->provider->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $this->provider->setOption(CURLOPT_SSL_VERIFYHOST, false);
        }
    }

    public function setHeader($key, $value)
    {
        $this->provider->header->set($key, $value);
    }

    public function get($url, $params = [])
    {
        $url = $url . '?' . http_build_query($params);
        return $this->provider->get($url);
    }


    public function post($url, $data)
    {
        return $this->provider->post($url, $data);
    }

    public function put($url, $data)
    {
        return $this->provider->put($url, $data);
    }

    public function delete($url)
    {
        return $this->provider->delete($url);
    }

    /**
     * @return \Phalcon\Http\Client\Provider\Curl|\Phalcon\Http\Client\Provider\Stream
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $response ClientResponse
     * @param int $successCode
     * @return bool
     */
    public static function isSuccessful($response, $successCode = 200)
    {
        return $response->header->statusCode === $successCode;
    }
}
