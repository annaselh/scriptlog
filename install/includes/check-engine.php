<?php

$failed = false;

function check_php_version()
{
  global $failed;
  
  if (version_compare(PHP_VERSION, '5.4', '>=')) {
        
     print PHP_VERSION;
        
  } else {
  
     $failed = true;
     
     print "Scriptlog requires PHP 5.4 or newer, this version is " . PHP_VERSION;
           
  }
    
}

