<?php
/**
 * Find Request Function
 * find request URI path
 * 
 * @return array
 * 
 */
function find_request()
{

  $dispatcher = new Dispatcher();
  
  return $dispatcher -> findRequestParam();

}