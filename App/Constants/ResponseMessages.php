<?php

namespace App\Constants;

use PhalconUtils\Constants\ResponseMessages as PhalconUtilsResponseMessages;

/**
 * Class ResponseMessages
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Library
 */
class ResponseMessages extends PhalconUtilsResponseMessages
{
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