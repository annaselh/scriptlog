<?php
/**
 * Check Credentials Functions
 * @param object $sanitizer
 */
function check_creadentials(Sanitize $sanitizer)
{
  
 $user = new User();
 $authenticator = new Authentication();
 $authenticator->setUser($user);
 $userEvent = new UserEvent($user, $authenticator, $sanitizer);
 
  if ((!isset($_SESSION['agent'])) || ($_SESSION['agent'] != sha1($_SERVER['HTTP_USER_AGENT']))) {

    header("Location: login.php");

  } elseif ((!isset($_SESSION['user_email'])) || (!isset($_SESSION['user_session']))) {

    header("Location: login.php");
    
  } else {

    $userEvent->loggedIn;
    
  }
  
}