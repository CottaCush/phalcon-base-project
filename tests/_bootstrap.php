<?php

include_once dirname(__FILE__) . '/../public/index.php';

exec('mysql -h ' . getenv('DB_HOST') . ' -u root -e "DROP DATABASE IF EXISTS ' . getenv('TEST_DB_NAME') . ';"');
exec('mysql -h ' . getenv('DB_HOST') . ' -u root -e "CREATE DATABASE ' . getenv('TEST_DB_NAME') . ';"');

exec(dirname(__FILE__) . '/../vendor/bin/phinx migrate -e testing');

exec(dirname(__FILE__) . '/../vendor/bin/phinx seed:run -s OauthSeeder -e testing');

include_once '_support/CommonTests.php';
