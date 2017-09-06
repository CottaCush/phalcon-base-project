<?php

use Phalcon\Config;

$config = array_merge(require 'config_common.php', [

]);

return new Config($config);
