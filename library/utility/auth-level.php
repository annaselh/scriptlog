<?php
/**
 * Authenticate User Level Function
 * 
 */
function auth_level()
{
  $userDao = new User();
  $validator = new FormValidator();
  $authenticator = new Authentication($userDao, $validator);
  return $authenticator->accessLevel();
}