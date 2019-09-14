<?php
/**
 * MemCache Class extends Cache Class
 * 
 * @package  SCRIPTLOG/LIB/CORE/MemCache
 * @category Core Class
 * @author   Contributors
 * @license  MIT
 * @version  1.0
 * @since    Since Release 1.0
 * 
 */
class MemCache extends Cache 
{
  /**
   * @var object
   */
  public $meminstance;

  public function __construct() 
  {
    if (class_exists('Memcached')) {

        $this->meminstance = new Memcached();

    } else {

        $this->meminstance = new Memcache();

    }

  }

  public function readCache($key)
  {
    return $this->meminstance->get($key);
  }

  public function writeCache($key, $data, $expirationTimes)
  {
    return $this->meminstance->set($key, $data, 0, $expirationTimes);
  }

  public function removeCache($key)
  {
    return $this->meminstance->delete($key);
  }

  public function addServer($host, $port, $weight)
  {
    
    $servers = $this->meminstance->getServerList();

    if (is_array($servers)) {
        
        foreach ($servers as $server) {
            
            if ($server['host'] === $host and $server['port'] === $port) {
                return true;
            }

        }

    }

    return $this->meminstance->addServer($host, $port, $weight);

  }

}