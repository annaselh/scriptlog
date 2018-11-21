<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$dashboardApp =  new DashboardApp();

switch ($action) {
    
    default:
        
        $dashboardApp -> welcomeAdmin('Dashboard');
        
        break;
       
}
