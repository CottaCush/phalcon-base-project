<?php

use Phalcon\Config;

$config = array_merge(require 'config_common.php', [
    'oauth' => [
        'enabled' => (getenv('OAUTH_ENABLED') === 'false') ? false : true,
        'access_token_life_time' => 21600,
        'refresh_token_life_time' => 2419200,
        'always_issue_new_refresh_token' => true,
        'excluded_paths' => [
            '/oauth'
        ]
    ],

    'requestLogger' => [
        'excluded_paths' => [
            '/authentication'
        ]
    ]
]);

return new Config($config);
