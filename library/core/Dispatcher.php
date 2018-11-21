<?php
/**
 * Dispatcher Class
 * 
 * @package  SCRIPTLOG
 * @author   Maoelana Noermoehammad
 * @license  MIT
 * @version  1.0
 * @since    Since Release 1.0
 * 
 */
class Dispatcher
{
  /**
   * routes 
   * 
   * @var string
   * 
   */
  private $route;

  /**
   * errors
   * 
   * @var string
   * 
   */
  private $errors;

  /**
   * theme
   * 
   * @var string
   * 
   */
  private $theme;

  /**
   * Constructor
   * Registry route and Initialize an instantiate of theme
   */
  public function __construct()
  {
     if (Registry::isKeySet('route')) {

        $this->route = Registry::get('route');

     }

     $this->theme = new Theme();

  }

  /**
   * Dispacth route requested by rules
   * and identify where should respond in active theme
   * 
   */
  public function dispatch()
  {

    if (!$themeActived = $this->invokeTheme()) {
        
      include(APP_ROOT.APP_PUBLIC.DS.'themes'.DS.'maintenance.php');
      
    } else {

      $theme_dir = APP_ROOT.APP_PUBLIC.DS.$themeActived['theme_directory'].DS;

      foreach ( $this->route as $action => $routes ) {
    
        if ( preg_match( '~^'.$routes.'$~i', $this->requestURI(), $params ) ) {
           
            if (is_dir($theme_dir)) {

               include($theme_dir.'header.php');
               include($theme_dir.$action . '.php' );
               include($theme_dir.'footer.php');
               
            }

           // avoid the 404 message 
           exit();
   
        } 
   
      }

      // nothing is found so handle the error page 404
      include($theme_dir.'404.php');

    }
    
  }

  /**
   * Find route defined by rules
   */
  public function findRules()
  {
    $keys = array();
    $values = array();
  
    foreach ($this->route as $key => $value) {
      
      $keys[] = $key; 
      $values[] = $value;    
   }
  
   return(array("keys" => $keys, "values" => $values));
  
  }

  /**
   * Find request path
   * 
   * @param array $args
   * @return mixed
   */
  public function findRequestPath($args)
  {
    $path = $this->requestPath();
    $path = explode('/', $path);

    if (isset($path[$args])) {

      return $path[$args];

    } else {
       
       return false;

    }
    
  }

  /**
   * Find Request Parameters
   * 
   * @return mixed
   * 
   */
  public function findRequestParam()
  {
    $parameters = [];

    foreach ($this->route as $key => $value) {
      
       if (preg_match('~^'.$value.'$~i', $this->requestURI(), $matches)) {

         return $parameters[] = $matches;

       }

    }

  }

  /**
   * Parse query from URL requested
   */
  public function parseQuery()
  {
    $var  = parse_url($var, PHP_URL_QUERY);
    $var  = html_entity_decode($var);
    $var  = explode('&', $var);
    $queries  = array();
    
    foreach($var as $val)
    {
        $x = explode('=', $val);
        $queries[$x[0]] = $x[1];
    }
    
    unset($val, $x, $var);
    
    return $queries;
    
  }

  /**
   * Request path
   * 
   * @return mixed;
   */
  protected function requestPath()
  {
    $request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $script_name = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
    $parts = array_diff_assoc($request_uri, $script_name);
     
    if (empty($parts)) {
      return '/';
    }
     
    $path = implode('/', $parts);
   
    if (($position = strpos($path, '?')) !== FALSE) {
     $path = substr($path, 0, $position);
    }
     
    return $path;
   
  }

  /**
   * Request URI
   * 
   * @return mixed
   */
  protected function requestURI()
  {
    $uri = rtrim(dirname($_SERVER["SCRIPT_NAME"]), '/' );
    $uri = '/' . trim( str_replace( $uri, '', $_SERVER['REQUEST_URI'] ), '/' );
    $uri = urldecode( $uri );
    return $uri;
  }

  /**
   * Grab active theme
   */
  protected function invokeTheme()
  {
    return $this->theme->loadTheme('Y');
  }
  
}