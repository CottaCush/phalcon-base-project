<?php

namespace App\Constants;

/**
 * Class ResponseMessages
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Library
 */
class ResponseMessages
{
    private static $messages = [
        ResponseCodes::METHOD_NOT_IMPLEMENTED => 'method not implemented',
        ResponseCodes::INTERNAL_SERVER_ERROR => 'An internal server error occurred',
        ResponseCodes::UNEXPECTED_ERROR => 'An unexpected error occurred',
        ResponseCodes::AUTH_ERROR => 'An unexpected error occurred during authentication',
        ResponseCodes::AUTH_ACCESS_TOKEN_REQUIRED => 'access_token is required'
    ];

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $code
     * @return mixed
     */
    public static function getMessageFromCode($code)
    {
        if (array_key_exists($code, self::$messages)) {
            return self::$messages[$code];
        } else {
            return self::$messages[ResponseCodes::UNEXPECTED_ERROR];
        }
    }
}