<?php
/**
 * Route Request Function
 * 
 * @return mixed
 * 
 */
function route_request()
{
  
  $dispatcher = new Dispatcher();
  
  return $dispatcher -> dispatch();

}