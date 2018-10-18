<?php
/**
 * Processing Login Function
 * 
 * @package SCRIPTLOG
 * @param object $authenticator
 * @param object $sanitize
 * 
 */
function auth_login(Sanitize $sanitizer, Authentication $authenticator, User $userDao)
{

  $errorMsg = [];
  $userEvent = new UserEvent($userDao, $authenticator, $sanitizer);
  $success = $userEvent->login($_POST);
  
  if ($success) {
  
      $_SESSION['statusMessage'] = "Successful login!";
  
      $_SESSION['KCFINDER'] = array();
       
      $_SESSION['KCFINDER']['disabled'] = false;
      
      $_SESSION['KCFINDER']['uploadURL'] =  APP_DIR . 'files/picture/';
      
      $_SESSION['KCFINDER']['uploadDir'] =  "";
      
      $_SESSION['agent'] = sha1($_SERVER['HTTP_USER_AGENT']);

      $adminPage = APP_PROTOCOL . '://' . APP_HOSTNAME . dirname($_SERVER['PHP_SELF']) . '/index.php?load=dashboard';
      header('Location:' . $adminPage);
    
    } else {
  
      $errorMsg['values'] = $_POST;
      $errorMsg['errors'] = $authenticator->getListErrors();
  
    }
  
    return $errorMsg;

}