<?php

include_once dirname(__FILE__) . '/public/index.php';

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => '%%PHINX_DBHOST%%',
            'name' => '%%PHINX_DBNAME%%',
            'user' => '%%PHINX_DBUSER%%',
            'pass' => '%%PHINX_DBPASS%%',
            'port' => '3306',
            'charset' => 'utf8'
        ],

        'staging' => [
            'adapter' => 'mysql',
            'host' => '%%PHINX_DBHOST%%',
            'name' => '%%PHINX_DBNAME%%',
            'user' => '%%PHINX_DBUSER%%',
            'pass' => '%%PHINX_DBPASS%%',
            'port' => '3306',
            'charset' => 'utf8'
        ],

        'development' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'vconnect_sss',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8'
        ],

        'testing' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'vconnect_sss_test',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8'
        ]
    ]
];
