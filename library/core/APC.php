<?php
/**
 * APC Class extends Cache
 * 
 * @package  SCRIPTLOG
 * @author   Contributors
 * @license  MIT
 * 
 */
class APC extends Cache
{
   
  public function readCache($key)
  {
    return apc_fetch($key);
  }

  public function writeCache($key, $data, $ttl)
  {
    return apc_store($key, $data, $ttl);
  }

  public function removeCache($key)
  {
    return apc_delete($key);
  }
  
}