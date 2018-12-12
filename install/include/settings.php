<?php
/**
 * File settings.php
 * 
 * @category  installation file settings.php
 * @package   SCRIPTLOG INSTALLATION
 * @author    M.Noermoehammad
 * @license   MIT
 * @version   1.0
 * 
 */
define('APP_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('APP_INC', 'include');

$execution_started = microtime(true);
$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http' : 'https';
$server_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$installURL = $protocol . '://' . $server_host . dirname($_SERVER['PHP_SELF']) . DIRECTORY_SEPARATOR;

if (file_exists(APP_PATH . APP_INC . '/vendor/autoload.php')) {
    
  require(__DIR__ . '/vendor/autoload.php');
    
}

if (!isset($_SESSION)) {
    
  session_start();
    
}

$errors = array();