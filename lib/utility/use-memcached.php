<?php
/**
 * Function use_memcached
 * 
 * @category Function to use memcached class
 * 
 */
function use_memcached($host)
{
   
    $var_cached = new Memcached();
   
    return $var_cached -> addServer($host);
   
} 
