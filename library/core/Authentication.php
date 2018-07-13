<?php
/**
 * Authentication Class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Authentication
{
  /**
   * values
   * @var array
   */
  private $values = [];
  
  /**
   * errors
   * @var array
   */
  private $errors = [];
  
  /**
   * User
   * @var string
   */
  private $userDao;
  
  /**
   * status message
   * @var string;
   */
  public $statusMessage = null;
  
  /**
   * number of errors
   * @var integer
   */
  public $numErrors;
   
  const NAME_LENGTH_MIN = 6;
  
  const NAME_LENGTH_MAX = 120;
  
  const PASS_LENGTH_MIN = 8;
  
  public function __construct()
  {
      if (isset($_SESSION['values']) && isset($_SESSION['errors'])) {
          $this->values = $_SESSION['values'];
          $this->errors = $_SESSION['errors'];
          $this->numErrors = count($this->errors);
          
          unset($_SESSION['value_array']);
          unset($_SESSION['error_array']);
      } else {
          $this->numErrors = 0;
      }
      
      if (isset($_SESSION['statusMessage'])) {
          $this->statusMessage = $_SESSION['statusMessage'];
          unset($_SESSION['statusMessage']);
      }
      
  }
  
  /**
   * Dependency Injection
   * 
   * @method setUser
   * @param User $userDao
   */
  public function setUser(User $userDao)
  {
     $this->userDao = $userDao;
  }
  
  /**
   * Set Value
   * 
   * @param string $field
   * @param string $value
   */
  public function setValue($field, $value)
  {
     $this->values[$field] = $value;
  }
  
  /**
   * getValue
   * 
   * @param string $field
   * @return string
   */
  public function getValue($field)
  {
     if (array_key_exists($field, $this->values)) {
          
        return htmlspecialchars(stripslashes($this->values[$field]));
      
     } else {
       
        return "";
      
     }
      
  }
  
  /**
   * getError
   * 
   * @param string $field
   * @return string
   */
  public function getError($field)
  {
      if (array_key_exists($field, $this->errors)) {
          
         return $this->errors[$field];
         
      } else {
          
         return "";
          
      }
      
  }
  
  /**
   * getListErrors
   * 
   * @return array
   */
  public function getListErrors()
  {
    return $this->errors;    
  }
  
  /**
   * validate form field and its value
   * 
   * @param string $field
   * @param string $value
   * @return boolean
   */
  public function validate($field, $value)
  {
    $valid = false;
    
    if ($valid == $this->isEmpty($field, $value)) {
        
        $valid = true;
        
        if ($field == "user_login") $valid = $this->checkSize($field, $value, self::NAME_LENGTH_MIN, self::NAME_LENGTH_MAX);
        
        if ($field == "user_pass" || $field == "new_pass" ) $valid = $this->checkSize($field, $value, self::PASS_LENGTH_MIN);
        
        if ($valid) $valid = $this->checkFormat($field, $value);
        
    }
    
    return $valid;
    
  }
  
  /**
   * Is Email Exists
   * 
   * @param string  $email
   * @return boolean
   */
  public function isEmailExists($email)
  {
      if ($this->userDao->checkUserEmail($email)) {
         
          return true;
          
      }
      
      return false;
      
  }
  
  public function isUserLoginExists($user_login)
  {
      if ($this->userDao->isUserLoginExists($user_login)) {
          return true;
      }
      
      return false;
  }
  
  /**
   * Check password
   * 
   * @param string $email
   * @param string $password
   * @return boolean
   */
  public function checkPassword($email, $password)
  {
    $result = $this->userDao->checkUserPassword($email, $password);
    
    if ($result === false) {
        
        $this->setError("user_pass", "Current password incorrect");
        
        return false;
        
    }
    
    return true;
  }
  
  /**
   * Validate User Account
   * 
   * @param string $email
   * @param string $password
   * @return boolean
   */
  public function validateUserAccount($email, $password)
  {
    $result = $this->userDao->checkUserPassword($email, $password);
    if ($result === false) {
        
        $this->setError("user_pass", "Email address or password is incorrect");
        return false;
        
    }
    
    return true;
    
  }
  
  /**
   * Set Error
   * 
   * @param string $field
   * @param string $errorMessage
   */
  private function setError($field, $errorMessage)
  {
     $this->errors[$field] = $errorMessage;
     $this->numErrors = count($this->errors);
  }
  
  /**
   * Is Form Field Empty 
   * 
   * @param string $field
   * @param string $value
   * @return boolean
   */
  private function isEmpty($field, $value)
  {
     $value = trim($value);
     if (empty($value)) {
         $this->setError($field, "Field value not entered");
         return true;
     }
     
     return false;
     
  }
  
  /**
   * Check Format
   * 
   * @param string $field
   * @param string $value
   * @return boolean
   */
  private function checFormat($field, $value)
  {
      switch ($field) {
          
          case 'user_email':
              
              $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                      . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                      . "\.([a-z]{2,}){1}$/i";
              $msg = "Email address invalid";
              break;
              
          case 'user_pass':
          case 'new_pass':
              
              $regex = "/^(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$";
                $msg = "Password must contain at least (1) upper case letter";
                $msg .= "Password must contain at least (1) lower case letter";
                $msg .= "Password must contain at least (1) number or special character";
                $msg .= "Password must contain at least (8) characters in length";
              break;
              
          case 'user_login':
              
              $regex = "/^([a-z ])+$/i";
              $msg = "";
              break;
              
          default:;
              
      }
      
      if (!preg_match($regex, ($value = rtrim($value)))) {
          
           $this->setError($field, $msg);
           return false;
           
      }
      
  }
  
  /**
   * Check Size
   * 
   * @param string $field
   * @param string $value
   * @param integer $minLength
   * @param integer $maxLength
   * @return boolean
   */
  private function checkSize($field, $value, $minLength, $maxLength = null)
  {
     $value = trim($value);
     
     if (!is_null($maxLength)) {
         
         if (strlen($value) < $minLength || strlen($value) > $maxLength) {
             
             $this->setError($field, "Value length should be within ".$minLength." & ".$maxLength." characters");
             return false;
             
         }
         
     } else {
         
         if (strlen($value) < $minLength) {
             
             $this->setError($field, "Value length must contain at least ".$minLength." characters");
             return false;
         }
     }
     
     return true;
     
  } 
  
}