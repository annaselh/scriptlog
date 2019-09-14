<?php
<<<<<<< HEAD
/**
 * Function use_memcached
 * 
 * @category Function to use cache with memcached
 * @package  SCRIPTLOG/LIB/UTILITY
 * @param string $host
 * 
 */
function use_memcached($host)
{
   
    $var_cached = new Memcached();
   
    return $var_cached -> addServer($host);
   
} 
=======

function use_memcached()
{
   
}
>>>>>>> 1d4d8df28f2f9d5c4f79df9b1e7826277c18ba3d
