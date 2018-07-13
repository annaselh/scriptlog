<?php 

if (file_exists(__DIR__ . '/../config.php')) {
    
    include(__DIR__ . '/../library/main.php');

} else {

    include(__DIR__ . '/../library/main-reserve.php');    
    
}

include 'admin-layout.php';

$breadCrumbs = isset($_GET['load']) ? htmlentities(strip_tags($_GET['load'])) : http_response_code();
$stylePath = $config['app']['url'] . APP_ADMIN;
$currentURL = $app_protocol . '://'. $app_hostname . dirname($_SERVER['PHP_SELF']) . DS;
admin_header($stylePath, $breadCrumbs);
include 'navigation.php';
include 'request.php';
admin_footer($stylePath);
