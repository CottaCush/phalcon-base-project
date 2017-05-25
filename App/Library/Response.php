<?php

namespace App\Library;

use App\Interfaces\JSend;
use App\Constants\HttpStatusCodes;
use App\Constants\ResponseMessages;
use Phalcon\Http\Response as PhalconResponse;

/**
 * Class Response
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class Response extends PhalconResponse implements JSend
{
    const RESPONSE_TYPE_ERROR = 'error';
    const RESPONSE_TYPE_SUCCESS = 'success';
    const RESPONSE_TYPE_FAIL = 'fail';

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $code
     * @param int|string $http_status_code
     * @param null $message
     * @return mixed
     */
    public function sendError($code, $http_status_code = 500, $message = null)
    {
        $response = array(
            'status' => self::RESPONSE_TYPE_ERROR,
            'message' => (is_null($message)) ? ResponseMessages::getMessageFromCode($code) : $message,
            'code' => $code
        );

        $this->setStatusCode($http_status_code, HttpStatusCodes::getMessage($http_status_code))->sendHeaders();
        $this->setJsonContent($response);
        $this->sendResponse();
    }


    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @return mixed|void
     */
    public function sendSuccess($data)
    {
        $this->setStatusCode(200, HttpStatusCodes::getMessage(200))->sendHeaders();
        $this->setJsonContent(array(
            'status' => self::RESPONSE_TYPE_SUCCESS,
            'data' => $data
        ));
        $this->sendResponse();
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @param int|string $http_status_code
     * @return mixed
     */
    public function sendFail($data, $http_status_code = 500)
    {
        $this->setStatusCode($http_status_code, HttpStatusCodes::getMessage($http_status_code))->sendHeaders();
        $this->setJsonContent(array(
            'status' => self::RESPONSE_TYPE_FAIL,
            'data' => $data
        ));
        $this->sendResponse();
    }

    /**
     * Send response
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function sendResponse()
    {
        $this->setContentType("application/json");
        if (!$this->isSent()) {
            $this->send();
        }
    }
}