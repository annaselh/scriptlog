<?php
/**
 * Access level function
 * 
 * @return string
 * 
 */
function access_level()
{
  $user_level = false;

  $userDao = new User();
  $userToken = new UserToken();
  $validator = new FormValidator();
  $authenticator = new Authentication($userDao, $userToken, $validator);

  $accessLevel = $authenticator -> accessLevel();

  if(($accessLevel != 'administrator') && ($accessLevel != 'manager')) {
     
     $user_level = true;

  } else {

     $user_level = false;

  }

  return $user_level;

}