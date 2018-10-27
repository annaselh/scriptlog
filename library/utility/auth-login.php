<?php
/**
 * Authenticate Login Function
 * 
 * @package SCRIPTLOG
 * 
 */
function auth_login()
{
  $userDao = new User();
  $validator = new FormValidator();
  $authenticator = new Authentication($userDao, $validator);
  $authenticator->isUserLoggedIn();
}



