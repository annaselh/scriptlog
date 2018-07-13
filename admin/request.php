<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed");

$load = '';
$pathToLoad = null;
$allowedToLoad = ['dashboard', 'posts', 'pages', 'topics', 'comments', 'themes', 'menu', 'menu-child', 'users', 'settings', 'plugins'];

try {

    if (isset($_GET['load']) && $_GET['load'] != '') {
     
        $load = htmlentities(strip_tags(strtolower($_GET['load'])));
        $load = filter_var($load, FILTER_SANITIZE_URL);
           
        // checking if the string contains parent directory
        if (strstr($_GET['load'], '../') !== false) {
            
            header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
            throw new Exception("Directory traversal attempt!");
            
        }
        
        // checking remote file inclusions
        if (strstr($_GET['load'], 'file://') !== false ) {
            
            header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
            throw new Exception("Remote file inclusion attempt!");
            
        }
        
    }
    
    if (!is_readable(dirname(dirname(__FILE__)) .'/'. APP_ADMIN .'/'."{$load}.php") 
    || empty($load) || !in_array($load, $allowedToLoad)) {
        
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        throw new Exception("404 - Page requested not found");
        
    } else {
        
        include __DIR__ . '/'.$load.'.php';
        
    }
    
} catch (Exception $e) {

    LogError::setStatusCode(http_response_code());
    LogError::newMessage($e);
    LogError::customErrorMessage('admin');
    
}