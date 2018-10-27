<?php

function auth_logout()
{
  $userDao = new User();
  $validator = new FormValidator();
  $authenticator = new Authentication($userDao, $validator);
  $authenticator -> logout();
}