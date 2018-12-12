<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$userId = isset($_GET['userId']) ? abs((int)$_GET['userId']) : "";
$sessionId = isset($_GET['sessionId']) ? $_GET['sessionId'] : "";
$userEvent = new UserEvent($userDao, $validator, $sanitizer);
$userApp = new UserApp($userEvent);

switch ($action) {
    
    case 'newUser':
    
      if ($authenticator -> userAccessControl('users') === true) {

          direct_page('index.php?load=users&error=userNotFound', 404);

      } else {

        if ($userId == 0) {
           
            $userApp -> insert();
            
        }

      }
        
      break;
        
    case 'editUser':
        
        if ($userDao -> checkUserId($userId, $sanitizer)) {
            
            if($authenticator -> userAccessControl('users') == false) {
                
                $userApp -> updateProfile($userId);

            } else {

                $userApp -> update($id);
            }
        
        } elseif ($userDao -> checkUserSession($sessionId) == false) {

            direct_page('index.php?load=users&error=userNotFound', 404);
            
        }  else {
        
            direct_page('index.php?load=users&error=userNotFound', 404);
            
        }
        
        break;
        
    case 'deleteUser':
        
        if($authenticator -> userAccessControl('users') === true) {

            direct_page('index.php?load=users&error=userNotFound', 404);

        } else {

            $userApp -> remove($userId);

        }
        
        break;
                
    default:
        
        if($authenticator -> userAccessControl('users') === true) {

            $userApp -> showProfile($user_id, $sanitizer);

        } else {

            $userApp -> listItems();
            
        }
        
        break;
        
}


