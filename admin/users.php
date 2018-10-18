<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$userId = isset($_GET['userId']) ? abs((int)$_GET['userId']) : 0;
$sessionId = isset($_GET['sessionId']) ? $_GET['sessionId'] : "";
$userDao = new User();
$validator = new FormValidator();
$userEvent = new UserEvent($userDao, $validator, $sanitizer);
$userApp = new UserApp($userEvent);

switch ($action) {
    
    case 'newUser':
        
        if ($userId == 0) {
           
            $userApp -> insert();
            
        }
        
        break;
        
    case 'editUser':
        
        if ($userDao -> checkUserId($userId, $sanitizer)) {
            
            $userApp -> update($userId);
            
        } else {
            
           direct_page('index.php?load=users&error=userNotFound', 404);
            
        }
        
        break;
        
    case 'deleteUser':
        
        $userApp -> remove($userId);
        
        break;
                
    default:
        
        $userApp -> listItems();
        
        break;
        
}