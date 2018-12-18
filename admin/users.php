<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$userId = isset($_GET['userId']) ? abs((int)$_GET['userId']) : "";
$sessionId = isset($_GET['sessionId']) ? $_GET['sessionId'] : "";
$userEvent = new UserEvent($userDao, $validator, $sanitizer);
$userApp = new UserApp($userEvent);

switch ($action) {
    
    case 'newUser':
    
      if ($authenticator -> userAccessControl('users') === false) {

          direct_page('index.php?load=users&error=userNotFound', 404);

      } else {

        if (gettype($userId) !== "integer") {

           header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
           throw new AppException("invalid ID date type!");

        } else {

            if ($userId == 0) {

                $userApp -> insert();

            }

        }

      }
        
      break;
        
    case 'editUser':
        
        if (gettype($userId) !== "integer") {

            header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
            throw new AppException("Invalid ID data type!");

        }

        if ($userDao -> checkUserId($userId, $sanitizer)) {
            
            if($authenticator -> userAccessControl('users') == false) {
    
                $userApp -> updateProfile($user_id);
    
            } else {
    
                $userApp -> update($userId);
    
            }
            
        } elseif ($userDao -> checkUserSession($sessionId) == false) {
    
            direct_page('index.php?load=users&error=userNotFound', 404);
                
        } else {
            
            direct_page('index.php?load=users&error=userNotFound', 404);
                
        }

        break;
        
    case 'deleteUser':
        
        if($authenticator -> userAccessControl('users') === false) {

            direct_page('index.php?load=users&error=userNotFound', 404);

        } else {

            $userApp -> remove($userId);

        }
        
        break;
                
    default:
        
        if($authenticator -> userAccessControl('users') === false) {

            $userApp -> showProfile($user_id);

        } else {

            $userApp -> listItems();
            
        }
        
        break;
        
}


