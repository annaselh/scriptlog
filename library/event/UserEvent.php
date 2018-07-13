<?php 
/**
 * UserEvent Class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class UserEvent
{
    
 /**   
  * User's ID
  * @var integer
  */
 private $user_id;
 
 /**
  * User Login
  * @var string
  */
 private $user_login;
 
 /**
  * User E-mail
  * @var string
  */
 private $user_email;
 
 /**
  * User password
  * @var string
  */
 private $user_pass;

 /**
  * User level
  * @var string
  */
 private $user_level;

 /**
  * User fullname
  * @var string
  */
 private $user_fullname;
 
 /**
  * User url
  * @var string
  */
 private $user_url;
 
 /**
  * user activation key
  * @var string
  */
 private $user_activation_key;
 
 /**
  * user status
  * @var string
  */
 private $user_status;
 
 /**
  * user session
  * @var string
  */
 private $user_session;
 
 const COOKIE_EXPIRE =  8640000;  //60*60*24*100 seconds = 100 days by default

 const COOKIE_PATH = "/";  //Available in whole domain
 
 public function __construct(User $userDao, Authentication $authenticator, Sanitize $sanitize)
 {
    $this->userDao = $userDao;
    $this->authenticator = $authenticator;
    $this->sanitize = $sanitize;
    
    /*$this->loggedIn = $this->isLoggedIn();
    
    if (!$this->loggedIn) {
        
        header("Location: ".APP_PROTOCOL."://".APP_HOSTNAME.dirname(dirname($_SERVER['PHP_SELF']))."/".APP_ADMIN.'/login.php');
        exit();
        
    }*/
    
 }
  
 public function setUserId($userId)
 {
   $this->user_id = $userId;   
 }
 
 public function setUserLogin($user_login)
 {
   $this->user_login = $user_login;
 }
 
 public function setUserEmail($user_email)
 {
   $this->user_email = $user_email;
 }
 
 public function setUserPass($user_pass)
 {
   $this->user_pass = $user_pass;
 }
 
 public function setUserLevel($user_level)
 {
   $this->user_level = $user_level;
 }
 
 public function setUserFullname($user_fullname)
 {
   $this->user_fullname = $user_fullname;
 }
 
 public function setUserUrl($user_url)
 {
   $this->user_url = $user_url;
 }
 
 public function setActivationKey($activation_key)
 {
   $this->user_activation_key = $activation_key;
 }
 
 public function setUserStatus($status)
 {
   $this->user_status = $status;
 }
 
 public function setUserSession($user_session)
 {
   $this->user_session = $user_session;
 }
 
 public function grabUsers($position, $limit, $orderBy = 'ID')
 {
   return $this->userDao->getUsers($position, $limit, $orderBy);    
 }
 
 public function grabUser($userId)
 {
   return $this->userDao->getUserById($userId, $this->sanitize);
 }
 
 public function addUser()
 {
   
   $this->authenticator->validate("user_login", $this->user_login);  
   $this->authenticator->validate("user_email", $this->user_email);
   
   if ($this->authenticator->numErrors > 0) {
       return false;
   }
   
   if ($this->authenticator->isEmailExists($this->user_email)) {
       return false;
   }
   
   if ($this->authenticator->isUserLoginExists($this->user_login)) {
       
       return false;
       
   }
   
   return $this->userDao->createUser([
       'user_login' => $this->user_login,
       'user_email' => $this->user_email,
       'user_pass'  => $this->user_pass,
       'user_level' => $this->user_level,
       'user_fullname' => $this->user_fullname,
       'user_url' => $this->user_url,
       'user_registered' => date("Ymd"),
       'user_session' => $this->user_pass
   ]); 
        
 }
 
 public function modifyUser()
 {
  
   $this->user_level = isset($_SESSION['user_level']) ? $_SESSION['user_level'] : "";
   $this->user_id = isset($_SESSION['ID']) ? (int)$_SESSION['ID'] : "";
   
   if ($this->user_level !== 'Administrator') {
   
       if (!empty($this->user_pass)) {
           
           $bind = [
               'user_email' => $this->user_email,
               'user_pass' => $this->user_pass,
               'user_fullname' => $this->user_fullname,
               'user_url' => $this->user_fullname,
              ];
           
       } else {
           
           $bind = [
               'user_email' => $this->user_email,
               'user_fullname' => $this->user_fullname,
               'user_url' => $this->user_fullname,
           ];
           
       }
   
   } else {
       
       if (!empty($this->user_pass)) {
           
           $bind = [
               'user_email' => $this->user_email,
               'user_pass' => $this->user_pass,
               'user_level' => $this->user_level,
               'user_fullname' => $this->user_fullname,
               'user_url' => $this->user_fullname,
               'user_status' => $this->user_status
           ];
           
       } else {
           
           $bind = [
               'user_login' => $this->user_login,
               'user_email' => $this->user_email,
               'user_level' => $this->user_level,
               'user_fullname' => $this->user_fullname,
               'user_url' => $this->user_fullname,
               'user_status' => $this->user_status
           ];
           
       }
       
   }
      
   return $this->userDao->updateUser($this->user_level, $this->sanitize, $bind, $this->user_id);
   
 }
 
 public function removeUser()
 {
   $data_user = $this->userDao->getUserById($this->user_id, $this->sanitize);
   if (false === $data_user) {
       direct_page('index.php?load=users&error=userNotFound', 404);
   }
   
   return $this->userDao->deleteUser($this->user_id, $this->sanitize);
   
 }
 
 /**
  * Login
  * @param array $values
  * @return boolean
  */
 public function login($values)
 {
     $this->user_email = $values['user_email'];
     $this->user_pass = $values['user_pass'];
     $remember_me = isset($values['remember_me']) ? $values['remember_me'] : '';
     
     $this->authenticator->validate("user_email", $this->user_email);
     $this->authenticator->validate("user_pass", $this->user_pass);
     
     if ($this->authenticator->numErrors > 0) {
         return false;
     }
     
     if (!$this->authenticator->validateUserAccount($this->user_email, $this->user_pass)) {
         return false;
     }
     
     $account_info = $this->userDao->getUserByEmail($this->user_email);
     if (!$account_info) {
         return false;
     }
     
     $this->user_id = $_SESSION['user_id'] = $account_info['ID'];
     $this->user_email = $_SESSION['user_email'] = $account_info['user_email'];
     $this->user_session = $_SESSION['user_session'] = $account_info['user_session'];
     $this->user_level = $_SESSION['user_level'] = $account_info['user_level'];
     $this->user_login = $_SESSION['user_login'] = $account_info['user_login'];
     
     $this->userDao->updateUserSession($this->user_level, $this->sanitize, 
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
 public function getUserData($user_email)
 {
     $this->authenticator->validate("user_email", $user_email);
     
     if ($this->authenticator->numErrors > 0) {
         return false;
     }
     
     if (!$this->authenticator->isEmailExists($user_email)) {
         return false;
     }
     
     $account_info = $this->userDao->getUserByEmail($user_email);
     
     if ($account_info) {
         return $account_info;
     }
     
     return false;
     
 }
 
 /**
  * User Level DropDown
  * 
  * @param string $selected
  * @return string
  */
 public function userLevelDropDown($selected = "") 
 {
    return $this->userDao->setUserLevel($selected);
 }
 
 /**
  * Is logged in
  * @return boolean
  */
 private function isLoggedIn()
 {
     if (isset($_SESSION['user_email']) && isset($_SESSION['user_session'])
         && isset($_SESSION['user_id'])) {
             
         // check userDao session
         if ($this->userDao->checkUserSession($_SESSION['user_session']) === false) {
                 
                 unset($_SESSION['user_id']);
                 unset($_SESSION['user_email']);
                 unset($_SESSION['user_level']);
                 unset($_SESSION['user_login']);
                 
                 return false;
                 
          }
             
         $account_info = $this->userDao->getUserByEmail($_SESSION['user_email'], PDO::FETCH_ASSOC);
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
 
}