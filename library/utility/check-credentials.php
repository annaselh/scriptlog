<?php
/**
 * Check Credentials Functions
 * @param object $sanitizer
 */
function check_creadentials($sanitizer)
{
  $userDao = new User();
  $authenticator =  new Authentication();
  $userEvent = new UserEvent($userDao, $authenticator, $sanitizer);  

  if ((!isset($_SESSION['agent'])) || ($_SESSION['agent'] != sha1($_SERVER['HTTP_USER_AGENT']))) {

  } 
  return $userEvent->loggedIn;
}