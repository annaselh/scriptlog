<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * DbFactory Class
 *  
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @copyright 2018 kartatopia.com
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class DbFactory
{
  private $error;
  
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
         
         $this->error = LogError::newMessage($e);
         $this->error = LogError::customErrorMessage();
         
     }
     
  }
  
}