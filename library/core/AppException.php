<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * AppException Class extends Exception
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class AppException extends Exception
{
  private $error_message;
   
  public function __construct()
  {
    parent::__construct();    
  }
  
  public function getMessage()
  {
    $this->error_message = LogError::newMessage($this->error_message);
    $this->error_message = LogError::customErrorMessage();
  }
  
}