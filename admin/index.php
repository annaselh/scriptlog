<?php 
/**
 * File index.php
 * 
 * @package  SCRIPTLOG
 * @category admin\index.php
 * @author   M.Noermoehammad 
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://scriptlog.web.id
 * 
 */
if (file_exists(__DIR__ . '/../config.php')) {
    
    include __DIR__ . '/../lib/main.php';
    require __DIR__ . '/authorizer.php';

} else {

    include __DIR__ . '/../lib/main-dev.php';
    require __DIR__ . '/authorizer.php';
    //header("Location: ../install");
    //exit();
       
}

if (!$isUserLoggedIn) {
   
   header("Location: login.php");
   exit();
   
} 

$user_id = isset($_COOKIE['cookie_user_id']) ? $_COOKIE['cookie_user_id'] : $_SESSION['user_id'];
$user_email = isset($_COOKIE['cookie_user_email']) ? $_COOKIE['cookie_user_email'] : $_SESSION['user_email'];
$user_level = isset($_COOKIE['cookie_user_level']) ? $_COOKIE['cookie_user_level'] : $_SESSION['user_level'];
$user_login = isset($_COOKIE['cookie_user_login']) ? $_COOKIE['cookie_user_login'] : $_SESSION['user_login'];
$user_session = isset($_COOKIE['cookie_user_session']) ? $_COOKIE['cookie_user_session'] : $_SESSION['user_session'];
    
// BreadCrumbs
$breadCrumbs = isset($_GET['load']) ? htmlentities(strip_tags($_GET['load'])) : http_response_code();
// StylePath
$stylePath = $config['app']['url'] . APP_ADMIN;
// Current URL
$currentURL = APP_PROTOCOL . '://'. APP_HOSTNAME . dirname($_SERVER['PHP_SELF']) . DS;
// Allowed query
$allowedQuery = array('dashboard', 'posts', 'pages', 'topics', 'comments', 'templates', 
                       'menu', 'menu-child', 'users', 'settings', 'plugins', 'logout');    
// retrieve plugin actived -- for administrator
$plugin_navigation = setplugin($user_level, 'private');

require 'admin-layout.php';
admin_header($stylePath, $breadCrumbs, $allowedQuery);
require 'navigation.php';
require 'request.php';
admin_footer($currentURL);
ob_end_flush();
    
