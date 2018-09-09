<?php 
/**
 * Registry Class
 * 
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Registry
{

/**
 * Data registered
 * 
 * @property array $_data
 * @static
 * @var array
 */
 private static $_data = array();
 
/**
 * get
 * 
 * @method get()
 * @static
 * @param string $key
 */
 public static function get($key)
 {
   return (isset(self::$_data[$key]) ? self::$_data[$key] : null);
 }
 
/**
 * set
 * 
 * @method set()
 * @static
 * @param string $key
 * @param string $value
 */
 public static function set($key, $value)
 {
   self::$_data[$key] = $value;
 }
 
 public static function setAll(array $key = array()) 
 {
   self::$_data = $key;
 }
 
 public static function isKeySet($key)
 {
   return (isset(self::$_data[$key]));  
 }
 
}