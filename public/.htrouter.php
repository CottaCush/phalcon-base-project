<?php

if (!file_exists(__DIR__ . '/' . $_SERVER['REQUEST_URI'])) {
    $url = parse_url($_SERVER['REQUEST_URI']);
    $_GET['_url'] = $url['path'];
}
return false;