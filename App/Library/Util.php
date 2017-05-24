<?php

namespace App\Library;

use App\Constants\Services;
use Handlebars\Handlebars;
use Phalcon\Di;
use Phalcon\Mailer\Manager as MailManager;
use stdClass;

/**
 * Class Util
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Library
 */
class Util
{
    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool|string
     */
    public static function getCurrentDateTime()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool|string
     */
    public static function getCurrentDate()
    {
        return date('Y-m-d');
    }

    /**
     * Gets value from array or object
     * Copied from Yii2 framework
     * @link http://www.yiiframework.com/
     * @copyright Copyright (c) 2008 Yii Software LLC
     * @license http://www.yiiframework.com/license/
     * @param      $array
     * @param      $key
     * @param null $default
     * @return null
     * @author Qiang Xue <qiang.xue@gmail.com>
     * @author Adegoke Obasa <goke@cottacush.com>
     * @author Rotimi Akintewe <rotimi.akintewe@gmail.com>
     */
    public static function getValue($array, $key, $default = null)
    {
        if (!isset($array)) {
            return $default;
        }

        if ($key instanceof \Closure) {
            return $key($array, $default);
        }
        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }
        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }
        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (is_object($array) && property_exists($array, $key)) {
            return $array->{$key};
        } elseif (is_array($array)) {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        } else {
            return $default;
        }
    }

    /**
     * Reads a CSV file
     * @credit http://www.codedevelopr.com/articles/reading-csv-files-into-php-array/
     * @param $csvFile
     * @return array
     */
    public static function readCSV($csvFile)
    {
        ini_set('auto_detect_line_endings', true);
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 1024);
        }
        fclose($file_handle);
        return $line_of_text;
    }

    /**
     * Get unique array or column elements
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param array $array
     * @param $elementKey
     * @return array
     */
    public static function getUniqueColumnElements(array $array, $elementKey)
    {
        $uniqueElements = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && isset($value[$elementKey])) {
                $value = $value[$elementKey];
            } elseif (is_object($value) && property_exists($value, $elementKey)) {
                $value = $value->{$elementKey};
            } else {
                continue;
            }
            if (!in_array($value, $uniqueElements, true)) {
                $uniqueElements[] = $value;
            }
        }
        return $uniqueElements;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param array $array
     * @param string $prefix
     * @param bool $mixed
     * @return array
     */
    public static function prependToArrayKeys(array $array, $prefix, $mixed = true)
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (!is_string($key)) {
                $result[] = $prefix . $value;
                continue;
            }

            $result[$prefix . $key] = $value;
        }

        return $result;
    }

    /**
     * Send message
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $template
     * @param $subject
     * @param $to
     * @param array $params
     * @param array $extras
     * @return bool
     */
    public static function send($template, $subject, $to, $params = [], $extras = [])
    {
        /** @var MailManager $mailer */
        $mailer = Di::getDefault()->get(Services::MAILER);

        $mailMessage = $mailer->createMessage()
            ->to((array)$to)
            ->subject(Util::getActualMessage($subject, $params))
            ->content(Util::getActualMessage($template, $params))
            ->cc((array)Util::getValue($extras, 'cc', new stdClass()))
            ->bcc((array)Util::getValue($extras, 'bcc', new stdClass()))
            ->contentType('text/html');

        if (Util::getValue($extras, 'attachment')) {
            $mailMessage->attachmentData(
                base64_decode(Util::getValue($extras, 'attachment.content', '')),
                Util::getValue($extras, 'attachment.name', ''),
                ['mime' => Util::getValue($extras, 'attachment.mime', '')]
            );
        }

        if (Util::getValue($extras, 'from')) {
            $mailMessage->from((array)Util::getValue($extras, 'from'));
        }

        try {
            return $mailMessage->send() > 0;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $message
     * @param array $params
     * @return string
     */
    public static function getActualMessage($message, array $params)
    {
        $engine = new Handlebars();
        return $engine->render($message, $params);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $imageData
     * @return int|null|string
     * @credits http://stackoverflow.com/a/35996452/1215010
     */
    public static function getBase64ImageMimeType($imageData)
    {
        $imageData = base64_decode($imageData);
        $f = finfo_open();
        $mimeType = finfo_buffer($f, $imageData, FILEINFO_MIME_TYPE);
        return ($mimeType ?: null);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $mixed
     * @return array|string
     * @credits http://stackoverflow.com/questions/10199017/how-to-solve-json-error-utf8-error-in-php-json-decode
     */
    public static function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = self::utf8ize($value);
            }
        } elseif (is_object($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed->$key = self::utf8ize($value);
            }
        } elseif (is_string($mixed)) {
            return utf8_encode($mixed);
        }
        return $mixed;
    }
}
