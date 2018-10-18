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
   * errors
   * @var array
   */
  private $errors;
  

  /**
   * User
   * @var string
   */
  private $userDao;
   
  public $loggedIn;

  const INTRUDER_NAME = "INTRUDER";

  const INTRUDER_LEVEL = 0;
 
  const COOKIE_EXPIRE =  8640000;  //60*60*24*100 seconds = 100 days by default

  const COOKIE_PATH = "/";  //Available in whole domain
 
  public function __construct(User $userDao, FormValidator $validator)
  {
    $this->userDao = $userDao;

    $this->loggedIn = $this->isLoggedIn();
    
    if (!$this->loggedIn) {
        
        $this->user_email = $_SESSION['user_email'] = self::INTRUDER_NAME;
        $this->user_level = self::INTRUDER_LEVEL;

    }
    
  }
  
  
  /**
   * ValidateCSRFToken
   * @return boolean
   */
  public function validateCSRFToken($key)
  {
    $checkCSRFToken = csrf_check_token('csrfToken', $_POST, 60*10);

    if ($checkCSRFToken === false) {
        $this->setError("Sorry there was unsuspected attempt");
        return false;
    }
    
    return true;

  }

  /**
   * Is Email Exists
   * 
   * @param string  $email
   * @return boolean
   */
  public function checkEmailExists($email)
  {
      if ($this->userDao->checkUserEmail($email)) {
         
          return true;
          
      }
      
      return false;
      
  }

  /**
  * 
  * @param string $user_login
  * @return boolean
  */
 public function checkUserLogin($user_login)
 {
   return $this->userDao->isUserLoginExists($user_login);
 }
 

 /**
  * Checking access level
  * @return boolean
  */
 public function accessLevel()
 {
    if (isset($_SESSION['user_level'])) {
         
      return ($_SESSION['user_level']);
        
    }
      
    return false;

 }
 
 /**
  * Logout
  */
 public function logout()
 {
     
   if (isset($_COOKIE['cookie_email']) && isset($_COOKIE['cookie_id'])) {
   
       setcookie("cookie_email", "", time() - self::COOKIE_EXPIRE, self::COOKIE_PATH);
       setcookie("cookie_id", "", time() - self::COOKIE_EXPIRE, self::COOKIE_PATH);
     
   }
   
   $_SESSION = array();
   
   session_destroy();
   
   setcookie('PHPSESSID', '', time()-3600, '/', '', 0, 0);
   
   $this->loggedIn = false;
   
   $loginPage = APP_PROTOCOL."://".APP_HOSTNAME.dirname($_SERVER['PHP_SELF']).'/';
   
   header("Location: ".$loginPage);
   exit();
   
 }
 
 /**
  * get User data and validate it
  * 
  * @param string $user_email
  * @return boolean|boolean|array|object
  */
 public function getUserData($user_email)
 {
     $this->validator->sanitize($user_email, "email");
     
    
     if (!$this->checkEmailExists($user_email)) {
         return false;
     }
     
     $account_info = $this->userDao->getUserByEmail($user_email);
     
     if ($account_info) {
         return $account_info;
     }
     
     return false;
     
 }
 
  /**
  * Login
  * @param array $values
  * @return boolean
  */
 public function login($values)
 {
     $user_email = $values['user_email'];
     $user_pass = $values['user_pass'];
     $remember_me = isset($values['rememberme']);
    
     $this->validator->sanitize($user_email, 'email');
     $this->validator->validate($user_email, 'email');
     $this->validator->validate($user_pass, 'password'); 
     
     try {

        if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
              
            header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
            throw new Exception("Sorry, unpleasant attempt detected!");
            
         }
     
         if (!$this->validateUserAccount($user_email, $user_pass)) {
             return false;
         }
         
         $account_info = $this->userDao->getUserByEmail($user_email);
         if (!$account_info) {
             return false;
         }
         
        $user_id = $_SESSION['user_id'] = $account_info['ID'];
        $user_email = $_SESSION['user_email'] = $account_info['user_email'];
        $user_level = $_SESSION['user_level'] = $account_info['user_level'];
        $user_login = $_SESSION['user_login'] = $account_info['user_login'];
        
        $last_session = session_id();
        session_regenerate_id(true);
        $recent_session = session_id();

        $sessionUserUpdated = $this->userDao->updateUserSession($this->sanitize, $recent_session, $user_id);
         
        if ($remember_me == true) {
             
            setcookie("cookie_email", $this->user_email, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);
            setcookie("cookie_id", $this->user_session, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);
             
        }
         
     } catch (Exception $e) {

       LogError::setStatusCode(http_response_code());
       LogError::newMessage($e);
       LogError::customErrorMessage();
       
     }

     return true;

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
        
        return false;
        
    }
    
    return true;
    
  }
  
  /**
  * Is logged in
  * @return boolean
  */
 private function isLoggedIn()
 {
     if (isset($_SESSION['user_email']) && isset($_SESSION['user_session'])
         && isset($_SESSION['user_id']) && $_SESSION['user_email'] != self::INTRUDER_NAME) {
             
         // check userDao session
         if ($this->userDao->checkUserSession($_SESSION['user_session']) === false) {
                 
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_level']);
            unset($_SESSION['user_login']);
            return false;
                 
          }
             
         $account_info = $this->userDao->getUserByEmail($_SESSION['user_email']);
         if (!$account_info) {

            return false;

         }
             
           $user_id = $account_info['ID'];
           $user_email = $account_info['user_email'];
           $user_login = $account_info['user_login'];
           $user_session = $account_info['user_session'];
           $user_level = $account_info['user_level'];
         
           return true;
             
         }
         
         if (isset($_COOKIE['cookie_email']) && isset($_COOKIE['cookie_id'])) {
             
             $user_email = $_SESSION['user_email'] = $_COOKIE['cookie_email'];
             $user_session = $_SESSION['user_session'] = $_COOKIE['cookie_id'];
             return true;
             
         }
         
         return false;
         
 }
 
}
