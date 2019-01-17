<?php
/**
 * application key function
 * 
 * @return string
 * 
 */
function app_key()
{
  global $config;

  if($config['app']['key'] == app_info()['app_key']) {

    return app_info()['app_key'];

  } else {

    scriptlog_error("Sorry, your application key not match!");

  }
  
}