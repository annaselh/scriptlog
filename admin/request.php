<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed");

$load = null;
$pathToError = __DIR__ . DIRECTORY_SEPARATOR . "404.php";
$pathToLoad = null;
$allowedToLoad = ['dashboard', 'posts', 'pages', 'topics', 'comments', 'themes', 'menu', 'menu-child', 'users', 'settings', 'plugins'];

try {

    if (isset($_GET['load'])) {
    
        // checking if the string contains parent directory
        if (strstr($_GET['load'], '../') !== false) {
            
            http_response_code(400);
            throw new Exception("Directory traversal attempt!");
            
        }
        
        // checking remote file inclusions
        if (strstr($_GET['load'], 'file://') !== false ) {
           
           http_response_code(400);
           throw new Exception("Remote file inclusion attempt!");    
           
        }
        
        if ($_GET['load'] !== '') {
            $load = htmlentities(strip_tags(strtolower($_GET['load'])));
            $load = filter_var($load, FILTER_SANITIZE_URL);
            $pathToLoad = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . APP_ADMIN . DIRECTORY_SEPARATOR ."{$load}.php";
        }
        
        if (!in_array($load, $allowedToLoad) || !is_readable($pathToLoad)) {
            
            http_response_code(404);
            include($pathToError);
            
        } else {
            
            include($pathToLoad);
            
        }
        
    }
    
} catch (Exception $e) {

    LogError::newMessage($e);
    LogError::customErrorMessage();
    
}