<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

class UserService
{
 /**
  * User Email
  * @var string
  */
 public $user_email;
 
 /**
  * Username 
  * @var string
  */
 public $user_login;
 
 /**
  * Log In 
  * @var string
  */
 public $loggedIn;
 
 /**
  * Instantiate of Sanitize class
  * @var object
  */
 public $sanitize;
 
 /**
  * User id
  * @var integer
  */
 private $user_id;
 
 /**
  * User session
  * @var string
  */
 private $user_session;
 
 /**
  * User session
  * @var string
  */
 private $user_level;
 
 /**
  * Instantiate of user class
  * @var object
  */
 protected $user;
 
 /**
  * Instantiate of validator class
  * @var object
  */
 protected $validator;
 
 const COOKIE_EXPIRE =  8640000;  //60*60*24*100 seconds = 100 days by default

 const COOKIE_PATH = "/";  //Available in whole domain
 
 public function __construct(User $user, ValidatorService $validator, Sanitize $sanitize)
 {
    $this->user = $user;
    $this->validator = $validator;
    $this->sanitize = $sanitize;
    
    $this->loggedIn = $this->isLoggedIn();
    
    if (!$this->loggedIn) {
        
        header("Location: ".APP_PROTOCOL."://".APP_HOSTNAME.dirname(dirname($_SERVER['PHP_SELF']))."/");
        exit();
        
    }
    
 }
 
 /**
  * Is logged in
  * @return boolean
  */
 private function isLoggedIn()
 {
     if (isset($_SESSION['user_email']) && isset($_SESSION['user_session'])
          && isset($_SESSION['user_id'])) {
         
         // check user session
         if ($this->user->checkUserSession($_SESSION['user_session']) === false) {
             
             unset($_SESSION['user_id']);
             unset($_SESSION['user_email']);
             unset($_SESSION['user_level']);
             unset($_SESSION['user_login']);
             
             return false;
             
         }
         
         $account_info = $this->user->getUserByEmail($_SESSION['user_email'], PDO::FETCH_ASSOC);
         if (!$account_info) {
             return false;
         }
         
         $this->user_id = $account_info['ID'];
         $this->user_email = $account_info['user_email'];
         $this->user_login = $account_info['user_login'];
         $this->user_session = $account_info['user_session'];
         $this->user_level = $account_info['user_level'];
         
         return true;
         
     }
     
     if (isset($_COOKIE['cookie_email']) && isset($_COOKIE['cookie_id'])) {
         
         $this->user_email = $_SESSION['user_email'] = $_COOKIE['cookie_email'];
         $this->user_session = $_SESSION['user_session'] = $_COOKIE['cookie_id']; 
         return true;
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
     $remember_me = isset($values['remember_me']) ? $values['remember_me'] : '';
     
     $this->validator->validate("user_email", $user_email);
     $this->validator->validate("user_pass", $user_pass);
     
     if ($this->validator->numErrors > 0) {
         return false;
     }
     
     if (!$this->validator->validateUserAccount($user_email, $user_pass)) {
         return false;
     }
     
     $account_info = $this->user->getUserByEmail($user_email);
     if (!$account_info) {
         return false;
     }
     
     $this->user_id = $_SESSION['user_id'] = $account_info['ID'];
     $this->user_email = $_SESSION['user_email'] = $account_info['user_email'];
     $this->user_session = $_SESSION['user_session'] = $account_info['user_session'];
     $this->user_level = $_SESSION['user_level'] = $account_info['user_level'];
     $this->user_login = $_SESSION['user_login'] = $account_info['user_login'];
     
     $this->user->updateUserSession($this->user_level, $this->sanitize, 
                 array('user_session'=>$this->user_session), $this->user_id);
     
     if ($remember_me == 'true') {
         
         setcookie("cookie_email", $this->user_email, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);
         setcookie("cookie_id", $this->user_session, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);
         
     }
     
     return true;
     
 }
 
 /**
  * Checking access level
  * @return boolean
  */
 public function accessLevel()
 {
    if (isset($_SESSION['user_level'])) {
         
      return ($this->user_level == $_SESSION['user_level']);
        
    }
      
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
 public function authUser($user_email)
 {
     $this->validator->validate("user_email", $user_email);
     
     if ($this->validator->numErrors > 0) {
         return false;
     }
     
     if (!$this->validator->isEmailExists($user_email)) {
         return false;
     }
     
     $account_info = $this->user->getUserByEmail($user_email);
     
     if ($account_info) {
         return $account_info;
     }
     
     return false;
     
 }
 
}