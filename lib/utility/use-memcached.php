<?php
/**
 * Function use_memcached
 * 
 * @category Function to use memcached class
 * @package  SCRIPTLOG/LIB/UTILITY
 * 
 */
function use_memcached($host)
{
   
    $var_cached = new MemCached();
   
    return $var_cached -> addServer($host);
   
} 
