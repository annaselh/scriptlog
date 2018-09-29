<?php
/**
 * AppJson Class extends AppController Class
 * 
 * @package  SCRIPTLOG
 * @link     https://we-love-php.blogspot.com/2012/07/how-to-write-really-small-and-fast.html
 * 
 */
class AppJson extends AppController
{
  protected function executeController($callback, $args)
  {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(call_user_func_array($callback, $args));
    throw new AppException(); // Exception instead of exit;
  }
}