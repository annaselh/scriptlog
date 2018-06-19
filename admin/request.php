<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$load = null;
$pathToError = __DIR__ . DIRECTORY_SEPARATOR . "404.php";
$pathToLoad = null;
$allowedToLoad = [
    'dashboard', 
    'posts', 'pages', 
    'topics', 'comments',
    'themes', 'menu',
    'menu-child', 'users',
    'settings', 'plugins'
];

if (isset($_GET['load']) && $_GET['load'] !== '') {
    $load = htmlentities(strip_tags(strtolower($_GET['load'])));
    $load = filter_var($load, FILTER_SANITIZE_URL);
    $pathToLoad = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . APP_ADMIN . DIRECTORY_SEPARATOR ."{$load}.php";
}

if (!in_array($load, $allowedToLoad) || !is_readable($pathToLoad)) {
    
    include($pathToError);
    
} else {
    
    include($pathToLoad);
    
}