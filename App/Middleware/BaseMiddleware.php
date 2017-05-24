<?php

namespace App\Middleware;

use Phalcon\Config;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use PhalconRest\Mvc\Plugin;
use Phalcon\Http\Request as HttpRequest;

/**
 * Class BaseMiddleware
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Middleware
 */
abstract class BaseMiddleware extends Plugin implements MiddlewareInterface
{
    /**
     * check if path is excluded from middleware
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param array $excludedPaths
     * @return bool
     */
    public function isExcludedPath($excludedPaths)
    {
        $request = new HttpRequest();
        $basePath = $request->getQuery('_url');

        if (is_null($excludedPaths)) {
            $excludedPaths = [];
        } elseif ($excludedPaths instanceof Config) {
            $excludedPaths = $excludedPaths->toArray();
        } else {
            $excludedPaths = (array)$excludedPaths;
        }

        foreach ($excludedPaths as $key => $value) {
            if (substr($basePath, 0, strlen($value)) == $value && $value != '/') {
                return true;
            }
        }

        return false;
    }
}
