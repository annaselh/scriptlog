<?php

if (!defined('APP_PATH')) define('APP_PATH', dirname(dirname(__FILE__)) . '/');

$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http' : 'https';
$server_host = $_SERVER['HTTP_HOST'];

$installURL = $protocol . '://' . $server_host . dirname($_SERVER['PHP_SELF']) . '/';

// Package detail
define('APP_TITLE', 'scriptlog');
define('APP_CODENAME', 'Maleo Senkawor');
define('APP_VERSION', '1.0');
define('APP_INC', 'include');

if (file_exists(APP_PATH . APP_INC . '/vendor/autoload.php')) {
    
    require(__DIR__ . '/vendor/autoload.php');
    
}

if (!isset($_SESSION)) {
    
  @session_start();
    
}

$errors = array();