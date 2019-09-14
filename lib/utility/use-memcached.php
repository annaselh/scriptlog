<?php
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