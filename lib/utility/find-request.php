<?php
/**
 * Find Request Function
 * find request URI path
 * 
 * @category Function
 * @package  SCRIPTLOG/LIB/EVENT
 * @return array
 * 
 */
function find_request($args)
{

  $dispatcher = new Dispatcher();
  
  return $dispatcher -> findRequestPath($args);

}