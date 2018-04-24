<?php

ini_set('memory_limit', '5M');

$key = '5c12IpTl0g!@#';
$checkIncKey = sha1(mt_rand(1, 1000).$key);

define('DS', DIRECTORY_SEPARATOR);
define('APP_ADMIN', 'admin');
define('APP_PUBLIC', 'public');
define('APP_LIBRARY', 'library');
define('SCRIPTLOG', $checkIncKey);

if (!defined('APP_ROOT')) define('APP_ROOT', dirname(dirname(__FILE__)) . DS);

if (!defined('PHP_EOL')) define('PHP_EOL', strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? "\r\n" : "\n");

if (!defined('SCRIPTLOG_START_TIME')) {
    
    define('SCRIPTLOG_START_TIME', microtime(true));
    
}

if (!defined('SCRIPTLOG_START_MEMORY')) {
    
    define('SCRIPTLOG_START_MEMORY', memory_get_usage());
    
}

if (file_exists(__DIR__ . '/../config.php')) {
    
    include(__DIR__ . '/../config.php');
    
} else {
    
    if (is_dir(APP_ROOT . 'install'))
        header("Location: install");
        exit();
        
}

// call functions in folder utility
$function_directory = new RecursiveDirectoryIterator(__DIR__ . '/utility/', FilesystemIterator::FOLLOW_SYMLINKS);
$filter_iterator = new RecursiveCallbackFilterIterator($function_directory, function ($current, $key, $iterator){
    
    // skip hidden files and directories
    if ($current->getFilename()[0] === '.') {
        return false;
    }
    
    if ($current->isDir()) {
        
        // only recurse into intended subdirectories
        return $current->getFilename() === __DIR__ . '/utility/';
        
    } else {
        
        // only consume files of interest
        return strpos($current -> getFilename(), '.php');
        
    }
    
});
    
$files_dir_iterator = new RecursiveIteratorIterator($filter_iterator); 

foreach ($files_dir_iterator as $file) {
    
    include($file -> getPathname());
    
}

if (is_dir(APP_ROOT . APP_LIBRARY) && is_file(APP_ROOT . APP_LIBRARY . '/init.php') 
    && is_file(APP_ROOT . APP_LIBRARY . '/rules.php')) {

  require 'rules.php';
        
  require 'init.php';
     
}