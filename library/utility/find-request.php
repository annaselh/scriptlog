<?php
/**
 * Find Request Function
 * find request URI path
 * 
 * @return array
 * 
 */
function find_request($args)
{

  $dispatcher = new Dispatcher();
  
  return $dispatcher -> findRequestPath($args);

}