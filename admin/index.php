<?php 
/**
 * File index.php
 * 
 * @category File
 * @package  ScriptLog
 * @author   Maoelana Noermoehammad <alanmoehammad@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://scriptlog.kartatopia.com
 * 
 */
if (file_exists(__DIR__ . '/../config.php')) {
    
    include __DIR__ . '/../library/main.php';

} else {

    include __DIR__ . '/../library/main-dev.php';    
    
}

if ((!isset($_SESSION['agent'])) || ($_SESSION['agent'] != sha1($_SERVER['HTTP_USER_AGENT']))) {
	
	header("Location: login.php");
	 
} elseif (!isset($_SESSION['userLoggedIn'])) {

    header("Location: login.php");

} elseif (auth_login() === false) {

    header("Location: login.php");

} else {

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : "";
$user_level = isset($_SESSION['user_level']) ? $_SESSION['user_level'] : "";
$user_login = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : "";
$user_fullname = isset($_SESSION['user_fullname']) ? $_SESSION['user_fullname'] : "";
$user_session = isset($_SESSION['user_session']) ? $_SESSION['user_session'] : "";

// BreadCrumbs
$breadCrumbs = isset($_GET['load']) ? htmlentities(strip_tags($_GET['load'])) : http_response_code();
// StylePath
$stylePath = $config['app']['url'] . APP_ADMIN;
// current URL
$currentURL = APP_PROTOCOL . '://'. APP_HOSTNAME . dirname($_SERVER['PHP_SELF']) . DS;
// Allowed query
$allowedQuery = array(
    'dashboard', 'posts', 'pages', 'topics', 'comments', 'templates',
    'menu', 'menu-child', 'users', 'settings', 'plugins', 'logout'
);    

require 'admin-layout.php';
admin_header($stylePath, $breadCrumbs, $allowedQuery);
require 'navigation.php';
require 'request.php';
admin_footer($currentURL);

}

