<?php

define('APP_PATH', dirname(dirname(__FILE__)) . '/');
define('APP_INC', 'include');

$execution_started = microtime(true);
$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http' : 'https';
$server_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$installURL = $protocol . '://' . $server_host . dirname($_SERVER['PHP_SELF']) . '/';

if (file_exists(APP_PATH . APP_INC . '/vendor/autoload.php')) {
    
    require(__DIR__ . '/vendor/autoload.php');
    
}

if (!isset($_SESSION)) {
    
  session_start();
    
}

$errors = array();