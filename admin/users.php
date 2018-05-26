<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$userId = isset($_GET['userId']) ? abs((int)$_GET['userId']) : 0;
$userDao = new User();
$validator = new Validator();
$userModule = new UserApp($userDao, $validator);

switch ($action) {
    
    case 'newUser':
        
        
        
        break;
        
    case 'editUser':
        
       
        
        break;
        
    case 'deleteUser':
        
       
        
        break;
                
    default:
        
        $userModule -> getAllUsers('Users');
        
        break;
        
}