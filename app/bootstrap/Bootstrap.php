<?php

namespace App\Bootstrap;

/**
 * Class Bootstrap
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App
 */
class Bootstrap
{
    protected $_executables;

    public function __construct(...$executables)
    {
        $this->_executables = $executables;
    }

    public function run(...$args)
    {
        foreach ($this->_executables as $executable) {
            call_user_func_array([$executable, 'run'], $args);
        }
    }
}
