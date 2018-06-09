<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$userId = isset($_GET['userId']) ? abs((int)$_GET['userId']) : 0;
$authenticator = new Authentication();
$sanitize = new Sanitize();
$userDao = new User();
$userEvent = new UserEvent($userDao, $authenticator, $sanitize);
$userApp = new UserApp($userEvent, $authenticator);

switch ($action) {
    
    case 'newUser':
        
        
        
        break;
        
    case 'editUser':
        
       
        
        break;
        
    case 'deleteUser':
        
       
        
        break;
                
    default:
        
        $userApp -> listItems();
        
        break;
        
}