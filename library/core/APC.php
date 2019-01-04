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
 
  /**
   * read cache
   * 
   * @param string $key
   * 
   */
  public function readCache($key)
  {
    return apc_fetch($key);
  }

  /**
   * write cache
   * 
   * @param string $key
   * @param string $data
   * @param mixed $timelimit
   * 
   */
  public function writeCache($key, $data, $ttl)
  {
    return apc_store($key, $data, $ttl);
  }

  /**
   * remove cache
   * 
   * @param string $key
   * 
   */
  public function removeCache($key)
  {
    return apc_delete($key);
  }
  
}