<?php 
/**
 * DbFactory Class
 *  
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class DbFactory
{
  private static $error;
  
  public static function connect($connection, $options = [])
  {
     try {
         
         $database = "Db";
         
         if (class_exists($database)) {
             
            return new $database($connection, $options);  
            
         } else {
             
             throw new DbException("Database Object is not exists");
             
         }
         
     } catch (DbException $e) {
         
         self::$error = LogError::newMessage($e);
         $self::$error = LogError::customErrorMessage();
         
     }
     
  }
  
}