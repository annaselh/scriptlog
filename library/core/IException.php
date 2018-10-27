<?php
/**
 * IException interface
 * 
 * @package  SCRIPTLOG
 * @link https://stackoverflow.com/questions/628408/custom-exception-messages-best-practices
 * @link https://secure.php.net/manual/en/language.exceptions.php#91159
 * 
 */
interface IException
{
  public function getMessage();
  
  public function getCode();

  public function getFile();

  public function getLine();

  public function getTrace();

  public function getTraceAsString();

  public function __toString();

  public function __construct($message = null, $code = 0);
  
}