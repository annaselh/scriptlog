<?php
/**
 * AppController Class
 * 
 * @package SCRIPTLOG
 * 
 * @link https://we-love-php.blogspot.com/2012/07/how-to-write-really-small-and-fast.html
 * @license MIT
 * @version 1.0.0
 * 
 */
class AppController
{
 
  protected $server = [];
  
  public function __construct()
  {
    $this->server = &$_SERVER;
  }
   
  public function get($pattern, $callback)
  {
    $this->route('GET', $pattern, $callback);
  }

  public function getPersistent($pattern, $callback)
  {
    $this->routePersistent('GET', $pattern, $callback);
  }

  public function delete($pattern, $callback)
  {
    $this->route('DELETE', $pattern, $callback);
  }

  public function quote($str)
  {

  }

  protected function route($method, $pattern, $callback)
  {
    if($this->server['REQUEST_METHOD'] != $method) return;

    $regex = preg_replace('#:([\w]+)#', '(?<\\1>[^/]+)',
      str_replace(['*', ')'], ['[^/]+', ')?'], $pattern));
      
      if(substr($pattern, -1)==='/') $regex .= '?';

  }
}