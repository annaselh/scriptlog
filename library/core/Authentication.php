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
   * 
   * @var object
   */
  private $userDao;

  /**
   * Form Validator
   * 
   * @var object
   */
  private $validator;
   
  const COOKIE_EXPIRE =  8640000;  //60*60*24*100 seconds = 100 days by default

  const COOKIE_PATH = "/";  //Available in whole domain
 
  public function __construct(User $userDao, FormValidator $validator)
  {
    $this->userDao = $userDao;
    $this->validator = $validator;
  }
  
  public function findUserByEmail($email)
  {
    return $this->userDao->getUserByEmail($email);
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

     $account_info = $this->userDao->getUserByEmail($user_email);
        
     $_SESSION['user_id'] = abs((int)$account_info['ID']);
     $_SESSION['user_email'] = $account_info['user_email'];
     $_SESSION['user_level'] = $account_info['user_level'];
     $_SESSION['user_login'] = $account_info['user_login'];
     $_SESSION['user_fullname'] = $account_info['user_fullname'];
     $_SESSION['user_session'] = $account_info['user_session'];

     $_SESSION['KCFINDER'] = array();
     $_SESSION['KCFINDER']['disabled'] = false;
     $_SESSION['KCFINDER']['uploadURL'] =  APP_DIR . 'files/picture/';
     $_SESSION['KCFINDER']['uploadDir'] =  "";
    
     $_SESSION['agent'] = sha1($_SERVER['HTTP_USER_AGENT']);
     
     $last_session = session_id();
     session_regenerate_id(true);
     $recent_session = session_id();

     if ($remember_me == true) {
          
         setcookie("cookie_email", $user_email, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);
         setcookie("cookie_id", $user_session, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);
          
     }

     $sessionUserUpdated = $this->userDao->updateUserSession($recent_session, $account_info['ID']);

     direct_page('index.php?load=dashboard', 200);
      
 }
 
 /**
  * Logout
  */
public function logout()
{
      
    if (!isset($_SESSION['user_id'])) {
 
        direct_page('index.php?load=dashboard');
 
    } else {
 
     if (isset($_COOKIE['cookie_email']) && isset($_COOKIE['cookie_id'])) {
    
       setcookie("cookie_email", "", time() - self::COOKIE_EXPIRE, self::COOKIE_PATH);
       setcookie("cookie_id", "", time() - self::COOKIE_EXPIRE, self::COOKIE_PATH);

     }
   
    $_SESSION = array();
    session_destroy();
    $loginPage = APP_PROTOCOL."://".APP_HOSTNAME. dirname($_SERVER['PHP_SELF']).'/';
    header("Location: ".$loginPage);
    
   }
    
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
  * Is User logged in
  * @return boolean
  */
 public function isUserLoggedIn()
 {
    $_SESSION['userLoggedIn'] = false;
    
    if (isset($_SESSION['userLoggedIn']) && $_SESSION['userLoggedIn'] === true) {
       return true;
    }

    return false;

 }
 
}
