<?php
/**
 * File check-engine.php
 * 
 * @category  installation file check-engine.php
 * @package   SCRIPTLOG INSTALLATION
 * @author    M.Noermoehammad
 * @license   MIT
 * @version   1.0
 * 
 */
use Sinergi\BrowserDetector\Os;
use Sinergi\BrowserDetector\Browser;

/**
 * Checking PHP Version Function
 */
function check_php_version()
{
   
    if (version_compare(PHP_VERSION, '5.6', '>=')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

/**
 * Checking MySQL Server Version Function
 */
function check_mysql_version($link, $min)
{
  if ($link instanceof mysqli) 
  $mysql_version = $link->server_info;
  preg_match("/^[0-9\.]+/", $mysql_version, $match);
  $mysql_version = $match[0];
  return (version_compare($mysql_version, $min) >= 0);
}

/**
 * Checking Operating System
 */
function check_os()
{
   $os = new Os();
   
   if (($os -> getName() === Os::LINUX) || 
       ($os -> getName() === Os::FREEBSD) || 
       ($os -> getName() === Os::NETBSD) ||
       ($os -> getName() === Os::OPENBSD) ||
       ($os -> getName() === Os::OPENSOLARIS) ||
       ($os -> getName() === Os::CHROME_OS) ||
       ($os -> getName() === Os::WINDOWS) || 
       ($os -> getName() === Os::OSX)) {
       
    return(array("Operating_system" => $os -> getName()));
        
   }
   
}

/**
 * Checking Browser
 */
function check_browser()
{
 $browser = new Browser();
 
 return $browser -> getName();
 
}

/**
 * Checking Browser Version
 */
function check_browser_version()
{
 $browser = new Browser();
 
 if (($browser-> getName() == 'Chrome') && ($browser -> getVersion() < 65)) {
     
    return true;
     
 } elseif (($browser-> getName() == 'Firefox') && ($browser -> getVersion() < 56.0)) {
     
    return true;
         
 } elseif (($browser->getName() == 'Opera') && ($browser -> getVersion() < 52.0)) {
        
    return true;

 } elseif (($browser->getName() == 'Vivaldi') && ($browser -> getVersion() < 1.14)) {
          
    return true;
     
 } elseif (($browser->getName() == 'Internet Explorer') && ($browser -> getVersion() < 11)) {
     
    return true;
     
 } else {

    return false;
    
 }

}

/**
 * get browser version
 * 
 */
function get_browser_Version()
{
  $browser =  new Browser();

  if($browser -> getName()) {
      return $browser->getVersion();
  }

}

/**
 * Checking Web Server
 */
function check_web_server()
{
  $get_web_server = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : $_SERVER['SERVER_NAME'];
  
  $slice = explode("/", $get_web_server);
  
  $webServer = isset($slice[0]) ? $slice[0] : '';
  
  $version = isset($slice[1]) ? $slice[1] : '';

  return (array('WebServer'=>$webServer, 'Version'=>$version));
  
}

/**
 * Checking Main Engine
 */
function check_main_dir()
{
    if (is_dir(APP_PATH) && is_file(APP_PATH . '../lib/main.php')) {
        
       return true;
       
    } else {
        
       return false;
       
    }
    
}

/**
 * Checking Load Engine
 */
function check_loader()
{
    if (is_dir(APP_PATH) && is_file(APP_PATH . '../lib/Scriptloader.php')) {
        
     return true;
     
    } else {
        
     return false;
     
    }
    
}

/**
 * Checking Log Directory. It is writable or not
 */
function check_log_dir()
{
    if (is_dir(APP_PATH ) && is_dir(APP_PATH . '../public/log') && is_writable(APP_PATH . '../public/log')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

/**
 * Checking Theme Directory It is writeable or not
 */
function check_theme_dir()
{
    if (is_dir(APP_PATH ) && is_dir(APP_PATH . '../public/themes') && is_writable(APP_PATH . '../public/themes')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

/**
 * Checking Cache Directory. It is writable or not
 */
function check_cache_dir()
{
    if (is_dir(APP_PATH) && is_dir(APP_PATH . '../public/cache') && is_writable(APP_PATH . '../public/cache')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

/**
 * Checking lib Plugin Directory. It is writeable or not
 */
function check_plugin_dir()
{
    if (is_dir(APP_PATH) && is_dir(APP_PATH . '../lib/plugins') && is_writable(APP_PATH . '../lib/plugins')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

/**
 * Checking PCRE UTF-8
 */
function check_pcre_utf8()
{
    if (!@preg_match('/^.$/u', 'ñ')) {
        
        return true;
        
    } elseif (!@preg_match('/^\pL$/u', 'ñ')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

/**
 * Checking SPL
 */
function check_spl_enabled($value)
{
    if (function_exists($value)) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

/**
 * Checking filter_list function
 * whether enabled or not
 */
function check_filter_enabled()
{
   if (function_exists('filter_list')) {
     
     return true;
        
   } else {
       
     return false;
     
   }
   
}

/**
 * Checking extension iconv
 */
function check_iconv_enabled()
{
    if (extension_loaded('iconv')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

/**
 * Checking ctype_digit function 
 * exists or not
 */
function check_character_type()
{
    if (!function_exists('ctype_digit')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

/**
 * Checking server request global
 */
function check_uri_determination()
{
    if (isset($_SERVER['REQUEST_URI']) || isset($_SERVER['PHP_SELF']) || isset($_SERVER['PHP_INFO'])) {
        
       return true;
       
    } else {
        
        return false;
    }
    
}

/**
 * Checking extension pdo_mysql and PDO class
 */
function check_pdo_mysql()
{
    
    if(extension_loaded('pdo_mysql') && class_exists('PDO')){
        
        return true;
        exit();
        
    } else {
        
        return false;
        
    }
    
}

/**
 * Checking mysqli function
 */
function check_mysqli_enabled()
{
    if (function_exists('mysqli_connect')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

/**
 * Checking GD function
 */
function check_gd_enabled()
{
    if (function_exists('gd_info')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

/**
 * Checking mod_rewrite functionality
 */
function check_modrewrite()
{
  $apache_modules = (function_exists('apache_get_modules')) ? apache_get_modules() : exit;
  
  if((check_web_server()['WebServer'] == 'Apache')) {

    if((check_web_server()['Version'] >= '2.2')) {

        if(in_array('mod_rewrite', $apache_modules)) {
            return true;
        }
       
    }

  } elseif ((check_web_server()['WebServer'] == 'LiteSpeed')) {
      
    if(in_array('mod_rewrite', $apache_modules)) {
        return true;
    }
    
  } elseif ((check_web_server()['WebServer'] == 'nginx')) {
      return false;
  }
  
}