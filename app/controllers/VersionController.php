<?php

namespace App\Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Config;

/**
 * Class VersionController
 * @property \Phalcon\Config config
 * @package App\Controller
 */
class VersionController extends Controller
{

    public function index()
    {

        echo "Welcome! " . $this->config->appParams->appName . " V" . $this->config->appParams->appVersion;
    }
}
