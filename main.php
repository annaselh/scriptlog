<?php

ini_set('memory_limit', '16M');

$backstage = 'cabin';

$frontstage = 'public';

$library = 'library';

/**
 * The default application extension of resource files.
 * @var string
 */
define('APP_EXT', '.php');

// set the full path to the approot
define('APP_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);

if ((!is_dir($backstage)) && (is_dir($backstage))) 
    $backstage = APP_ROOT . $backstage;

if ((!is_dir($frontstage)) && (is_dir($frontstage)))
    $frontstage = APP_ROOT . $frontstage;

if ((!is_dir($library)) && (is_dir($library)))
    $library = APP_ROOT . $library;

define('APP_BACKSTAGE', $backstage.DIRECTORY_SEPARATOR);
define('APP_FRONTSTAGE', $frontstage.DIRECTORY_SEPARATOR);
define('APP_LIBRARY', $library.DIRECTORY_SEPARATOR);

unset($backstage, $frontstage, $library);

$key = '45432244f8be7222761bfeaaf673e5ea1f1c516c7f26460e69427be0f63c199a921b451e0ccd09c76f6352242822bec155e1f2b7fa28d29f16928a6233155f5f';
$checkIncKey = sha1(mt_rand(1, 1000).$key);
define('SCRIPTLOG', $checkIncKey);

if (!defined('PHP_EOL')) {
    define('PHP_EOL', strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? "\r\n" : "\n");
}

if (!defined('SCRIPTLOG_START_TIME')) {
    
    define('SCRIPTLOG_START_TIME', microtime(true));
    
}

if (!defined('SCRIPTLOG_START_MEMORY')) {
    
    define('SCRIPTLOG_START_MEMORY', memory_get_usage());
    
}

if (file_exists(APP_LIBRARY . 'functions'.APP_EXT)) {
    
    include APP_LIBRARY . 'functions'.APP_EXT;
}

if (file_exists(APP_LIBRARY . 'rules'.APP_EXT)) {
    
    include APP_LIBRARY.'rules'.APP_EXT;
}

if (file_exists(APP_LIBRARY.'init'.APP_EXT)) {
    
    include APP_LIBRARY.'init'.APP_EXT;
    
}

if (file_exists(APP_ROOT .'config' . APP_EXT)) {
    
    require APP_ROOT . 'config' . APP_EXT;
    
} else {
    
    if (is_dir(APP_ROOT . 'install'))
        header("Location: install");
        exit();
        
}