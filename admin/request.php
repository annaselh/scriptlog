<?php

$load = null;
$pathToError = "404.php";
$pathToView = null;

if (isset($_GET['load']) && $_GET['load'] != '') {
    $load = htmlentities(strip_tags(strtolower($_GET['load'])));
    $load = filter_var($load, FILTER_SANITIZE_STRING);
    $pathToView = dirname(dirname(__FILE__)) . DS . APP_ADMIN . DS ."{$load}.php";
}

// cek direktori admin - views
if (!is_readable($pathToView) || empty($load)) {
    
    include($pathToError);
    
} else {
    
    include($pathToView);
    
}