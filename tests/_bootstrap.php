<?php

include_once dirname(__FILE__) . '/../public/index.php';

exec('DB_USERNAME=' . getenv('DB_USERNAME') . ' DB_PASSWORD= DB_NAME=' . getenv('DB_NAME') . ' DB_HOST=' . getenv('DB_HOST') . ' CLIENT_SECRET=' . getenv('CLIENT_SECRET') . ' CLIENT_ID=' . getenv('CLIENT_ID') . ' ant migrations');

include_once '_support/CommonTests.php';
