<?php
/**
 * 
 */
function check_cache_engine()
{

  if(defined(APP_CACHE) && APP_CACHE == true) {

     if(extension_loaded('memcached')) {

         return true;

     }
     
  }

}