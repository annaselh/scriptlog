<?php
/**
 * Abstract Class Cache
 * Abstract it functionality to read cache, write cache, and remove cache
 * 
 * @package  SCRIPTLOG/LIB/CORE/Cache
 * @category Core Class
 * @abstract Class Cache
 * @author   Contributors
 * @license  MIT
 * @version  1.0
 * @since    Since Release 1.0
 * 
 */
abstract class Cache
{
  /**
   * Retrieve an item
   * 
   * @method @abstract readCache()
   * @param  string $key
   * 
   */
  abstract protected function readCache($key);

  /**
   * Store an item
   * 
   * @method @abstract writeCache()
   * @param string $key
   * @param mixed $value
   * @param int
   * 
   */
  abstract protected function writeCache($key, $value, $expiration);

  /**
   * Delete an item
   * 
   * @method @abstract removeCache()
   * @param string $key
   * 
   */
  abstract protected function removeCache($key);

}