<?php

use Phalcon\Cli\Task;

/**
 * Class BaseTask
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BaseTask extends Task
{

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $array
     * @param bool|false $return
     * @return string
     */
    public function printStringArray($array, $return = false)
    {
        $text = '';
        if (is_array($array)) {
            for ($i = 0; $i < count($array); $i++) {
                if (is_string($array[$i])) {
                    $text .= $array[$i];
                    $text .= (($i != (count($array) - 1)) ? ", " : '');
                }
            }
        }
        if ($return) {
            return $text;
        } else {
            $this->printToConsole($text);
            return '';
        }
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $message
     */
    public function printToConsole($message)
    {
        print $message . "\n";
    }

}