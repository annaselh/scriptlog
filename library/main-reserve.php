<?php
// date_default_timezone_set("Asia/Jakarta");
ini_set("memory_limit", "2M");
#ini_set("session.cookie_secure", "True");  //secure
#ini_set("session.cookie_httponly", "True"); // httpOnly
#header("Content-Security-Policy: default-src https:; font-src 'unsafe-inline' data: https:; form-action 'self' https://kartatopia.com;img-src data: https:; child-src https:; object-src 'self' www.google-analytics.com ajax.googleapis.com platform-api.sharethis.com kartatopia-studio.disqus.com; script-src 'unsafe-inline' https:; style-src 'unsafe-inline' https:;");

$key = '5c12IpTl0g!@#';
$checkIncKey = sha1(mt_rand(1, 1000).$key);
$app_hostname = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
$app_protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http' : 'https';

define('DS', DIRECTORY_SEPARATOR);
define('APP_TITLE', 'Scriptlog');
define('APP_CODENAME', 'Maleo Senkawor');
define('APP_VERSION', '1.0');
define('APP_ADMIN', 'admin');
define('APP_PUBLIC', 'public');
define('APP_LIBRARY', 'library');
define('SCRIPTLOG', $checkIncKey);

if (!defined('APP_ROOT')) define('APP_ROOT', dirname(dirname(__FILE__)) . DS);

if (!defined('PHP_EOL')) define('PHP_EOL', strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? "\r\n" : "\n");

if (!defined('APP_PROTOCOL')) define('APP_PROTOCOL', $app_protocol);

if (!defined('APP_HOSTNAME')) define('APP_HOSTNAME', $app_hostname);

if (!defined('SCRIPTLOG_START_TIME')) define('SCRIPTLOG_START_TIME', microtime(true));

if (!defined('SCRIPTLOG_START_MEMORY')) define('SCRIPTLOG_START_MEMORY', memory_get_usage());

if (file_exists(__DIR__ . '/../config.sample.php')) {

    $config = require APP_ROOT . 'config.sample.php';
    
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
    
if (is_dir(APP_ROOT . APP_LIBRARY) && is_file(APP_ROOT . APP_LIBRARY . '/Scriptloader.php')) {
    
    require 'Scriptloader.php';
    
}

$loader = new Scriptloader();
$loader -> setLibraryPaths(array(
    APP_ROOT . APP_LIBRARY . '/core/',
    APP_ROOT . APP_LIBRARY . '/dao/',
    APP_ROOT . APP_LIBRARY . '/event/',
    APP_ROOT . APP_LIBRARY . '/app/',
    APP_ROOT . APP_LIBRARY . '/plugins/',
));

$loader -> runLoader();

$rules = array(
    
    '/'        => "/",
    'category' => "/category/(?'category'[\w\-]+)",
    'page'     => "(?'page'[^/]+)",
    'post'     => "/post/(?'id'\d+)/(?'post'[\w\-]+)",
    'posts'    => "/posts/([^/]*)",
    'search'   => "(?'search'[\w\-]+)"
    
);

$dbc = DbFactory::connect(['mysql:host='.$config['db']['host'].';dbname='.$config['db']['name'],
    $config['db']['user'], $config['db']['pass']
]);

Registry::setAll(array('dbc' => $dbc, 'route' => $rules));

$configurations = new Configuration($dbc);
$searchPost = new SearchSeeker($dbc);
$frontPaginator = new Paginator(10, 'p');
$postFeeds = new RssFeed($dbc);
$sanitizer = new Sanitize();

# set_exception_handler('LogError::exceptionHandler');
# set_error_handler('LogError::errorHandler');
register_shutdown_function('scriptlog_shutdown_fatal');

if (!isset($_SESSION)) {
    
    session_start();
    
}

ob_start();