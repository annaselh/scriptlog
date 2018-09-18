<?php 
/**
 * File index.php
 * 
 * @category File
 * @package  ScriptLog
 * @author   Maoelana Noermoehammad <alanmoehammad@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://scriptlog.kartatopia.com
 * PHP version 7
 */
if (file_exists(__DIR__ . '/../config.php')) {
    
    include __DIR__ . '/../library/main.php';

} else {

    include __DIR__ . '/../library/main-reserve.php';    
    
}

require 'admin-layout.php';

$breadCrumbs = isset($_GET['load']) ? htmlentities(strip_tags($_GET['load'])) : http_response_code();

$stylePath = $config['app']['url'] . APP_ADMIN;

// current URL
$currentURL = APP_PROTOCOL . '://'. APP_HOSTNAME . dirname($_SERVER['PHP_SELF']) . DS;

$allowedQuery = array(
    'dashboard', 'posts', 'pages', 'topics', 'comments', 'templates',
    'menu', 'menu-child', 'users', 'settings', 'plugins'
);    

admin_header($stylePath, $breadCrumbs, $allowedQuery);
require 'navigation.php';
require 'request.php';
admin_footer($currentURL);