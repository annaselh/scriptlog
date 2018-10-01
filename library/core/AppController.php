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
    return htmlspecialchars($str, ENT_QUOTES);
  }

  public function render($template) 
  { 
    ob_start();
    include($template);
    return ob_get_clean();
  }

  public function display($template, $status=null) 
  {
    if ($status) header('HTTP/1.1 '.$status);
    include($template);
  }

  public function __get($name) 
  {
    if (isset($_REQUEST[$name])) return $_REQUEST[$name];
    return '';
  }

  public static function exception($e)
  {
    if ($e instanceof AppException) return;
    scriptlog_error($e -> getMessage()."\n".$e->getTraceAsString(), E_USER_WARNING);
    $appController = new AppController();
    $appController -> display('exception.php', 500);
  }

  protected function route($method, $pattern, $callback)
  {
    if($this->server['REQUEST_METHOD'] != $method) return;

    // convert URL parameter (e.g. ":id") to regular expression
    $regex = preg_replace('#:([\w]+)#', '(?<\\1>[^/]+)', str_replace(['*', ')'], ['[^/]+', ')?'], $pattern));
      
    if(substr($pattern, -1)==='/') $regex .= '?';

    // extract parameter values from URL if route matches the current request
    if (!preg_match('#^'.$regex.'$#', $this->server['PATH_INFO'], $values)) {
       return;
    }

    // extract parameter names from URL
    preg_match_all('#:([\w]+)#', $pattern, $params, PREG_PATTERN_ORDER);
    $args = [];
    foreach ($params[1] as $param) {
      if (isset($values[$param])) $args[] = urldecode($values[$param]);
    }

    $this->executeController($callback, $args);

  }

  protected function routePersistent($method, $pattern, $callback)
  {
    if ($this->server['REQUEST_METHOD'] != $method) return;

    //convert URL parameters (":p", "*") to regular expression
    $regex = str_replace(['*','(',')',':p'], ['[^/]+','(?:',')?','([^/]+)'], $pattern);
    if (substr($pattern,-1)==='/') $regex .= '?';

    // extract parameter values from URL if route matches the current request
    if (!preg_match('#^'.$regex.'$#', $this->server['PATH_INFO'], $values)) {
      return;
    }

    // decode URL parameters
    array_shift($values);
    foreach ($values as $key => $value) $value[$key] = urldecode($value);
    $this->executeController($callback, $value);

  }

  protected function executeController(&$callback, &$args) 
  {
    foreach ((array)$callback as $cb) call_user_func_array($cb, $args);
    throw new Halt(); // Exception instead of exit;
  }

}
