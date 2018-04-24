<?php

use Sinergi\BrowserDetector\Os;
use Sinergi\BrowserDetector\Browser;

function check_php_version()
{
   
    if (version_compare(PHP_VERSION, '5.6', '>=')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

function check_os()
{
   $os = new Os();
   
   if (($os -> getName() === Os::LINUX) || ($os -> getName() === Os::FREEBSD) || 
       ($os -> getName() === Os::CHROME_OS) ||
       ($os -> getName() === Os::WINDOWS) || 
       ($os -> getName() === Os::OSX)) {
       
    return(array("Operating_system" => $os -> getName()));
        
   }
   
}
 
function check_browser()
{
 $browser = new Browser();
 
 return $browser -> getName();
 
}

function check_browser_version()
{
 $browser = new Browser();
 
 if ($browser-> getName() == 'Chrome') {
     
     if ($browser->getVersion() < 65)
         
         return true;
     
 } elseif ($browser-> getName() == 'Firefox') {
     
     if ($browser->getVersion() < 56.0)
         return true;
         
 } elseif ($browser->getName() == 'Opera') {
     
     if ($browser->getVersion() < 52.0) 
         
         return true;

 } elseif ($browser->getName() == 'Vivaldi') {
     
     if ($browser->getVersion() < 1.14)
         
         return true;
     
 } elseif ($browser->getName() == 'Internet Explorer') {
     
     if ($browser->getVersion() < 11) 
         
         return true;
     
 }

}

function check_web_server()
{
  $get_web_server = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : "";
  
  $slice = explode('/', $get_web_server);
  
  return (array('WebServer'=>$slice[0], 'Version'=>$slice[1]));
  
}

function check_main_dir()
{
    if (is_dir(APP_PATH) && is_file(APP_PATH . '../library/main.php')) {
        
       return true;
       
    } else {
        
       return false;
       
    }
    
}

function check_init_dir()
{
    if (is_dir(APP_PATH) && is_file(APP_PATH . '../library/init.php')) {
        
     return true;
     
    } else {
        
     return false;
     
    }
    
}

function check_log_dir()
{
    if (is_dir(APP_PATH ) && is_dir(APP_PATH . '../public/log') && is_writable(APP_PATH . '../public/log')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

function check_cache_dir()
{
    if (is_dir(APP_PATH) && is_dir(APP_PATH . '../public/cache') && is_writable(APP_PATH . '../public/cache')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

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

function check_spl_enabled($value)
{
    if (function_exists($value)) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

function check_filter_enabled()
{
   if (function_exists('filter_list')) {
     
     return true;
        
   } else {
       
     return false;
     
   }
   
}

function check_iconv_enabled()
{
    if (extension_loaded('iconv')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

function check_character_type()
{
    if (!function_exists('ctype_digit')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

function check_uri_determination()
{
    if (isset($_SERVER['REQUEST_URI']) || isset($_SERVER['PHP_SELF']) || isset($_SERVER['PHP_INFO'])) {
        
       return true;
       
    } else {
        
        return false;
    }
    
}

function check_pdo_mysql()
{
    
    if(extension_loaded('pdo_mysql') && class_exists('PDO')){
        
        return true;
        exit;
        
    } else {
        
        return false;
        
    }
    
}

function check_mysqli_enabled()
{
    if (function_exists('mysqli_connect')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

function check_gd_enabled()
{
    if (function_exists('gd_info')) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

function check_modrewrite()
{
  $apache_modules = (function_exists('apache_get_modules')) ? apache_get_modules() : exit;
  
  if(in_array('mod_rewrite', $apache_modules)) {
       return true;
   }
  
}