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

  private $user_id;

  private $user_session;
 
  private $userDao;

  private $userToken;

  private $validator;

  public $user_email;

  public $user_login;

  public $user_fullname;

  public $user_level;

  const COOKIE_EXPIRE =  2592000;  // default 1 month

  const COOKIE_PATH = "/";  //Available in whole domain
 
  /**
   * 
   */
  public function __construct(User $userDao, UserToken $userToken, FormValidator $validator)
  {
    $this->userDao = $userDao;
    $this->userToken = $userToken;
    $this->validator = $validator;
  }
  
  /**
   * Find User by Email
   * @param string $email
   * @return 
   * 
   */
  public function findUserByEmail($email)
  {
    return $this->userDao->getUserByEmail($email);
  }

  public function findTokenByUserEmail($email, $expired)
  {
    return $this->userToken->getTokenByUserEmail($email, $expired);
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
   
    if (isset($_COOKIE['cookie_user_level'])) {

       return $_COOKIE['cookie_user_level'];
    
    }

    if (isset($_SESSION['user_level'])) {

       return $_SESSION['user_level'];
       
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
    
     $email = $values['user_email'];
     $password = $values['user_pass'];
     $remember_me = isset($values['remember']);

     $this->validator->sanitize($email, 'email');
     $this->validator->validate($email, 'email');
     $this->validator->validate($password, 'password'); 

     $account_info = $this->findUserByEmail($email);

     $tokenizer = new Tokenizer();

      $this->user_id = $_SESSION['user_id'] = $account_info['ID'];
      $this->user_email = $_SESSION['user_email'] = $account_info['user_email'];
      $this->user_level = $_SESSION['user_level'] = $account_info['user_level'];
      $this->user_login = $_SESSION['user_login'] = $account_info['user_login'];
      $this->user_fullname = $_SESSION['user_fullname'] = $account_info['user_fullname'];
      $this->user_session = $_SESSION['user_session'] = generate_session_key($email, 13);

      $_SESSION['KCFINDER'] = array();
      $_SESSION['KCFINDER']['disabled'] = false;
      $_SESSION['KCFINDER']['uploadURL'] =  APP_DIR . 'files/picture/';
      $_SESSION['KCFINDER']['uploadDir'] =  "";
      $_SESSION['agent'] = sha1($_SERVER['HTTP_USER_AGENT']);
     
      if ($remember_me == true) {
           
           setcookie("cookie_user_email", $email, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);
           setcookie("cookie_user_login", $this->user_login, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);
           setcookie("cookie_user_level", $this->user_level, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);
           setcookie("cookie_user_fullname", $this->user_fullname, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);
           setcookie("cookie_user_session", $this->user_session, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);
           setcookie("cookie_user_id", $this->user_id, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);

           $random_password = $tokenizer -> createToken(16);
           setcookie("random_pwd", $random_password, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);

           $random_selector = $tokenizer -> createToken(32);
           setcookie("random_selector", $random_selector, time() + self::COOKIE_EXPIRE, self::COOKIE_PATH);

           $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);
           $hashed_selector = password_hash($random_selector, PASSWORD_DEFAULT);
           $expired_date = date("Y-m-d H:i:s", time() + self::COOKIE_EXPIRE);

           $token_info = $this->findTokenByUserEmail($email, 0);

           if (!empty($token_info['ID'])) {

             $updateExpired = $this->userToken->updateTokenExpired($token_info['ID']);

           }

           $bind = ['user_id' => $account_info['ID'], 'pwd_hash' => $hashed_password, 
                    'selector_hash' => $hashed_selector, 'expired_date' => $expired_date];

           $newUserToken = $this->userToken->createUserToken($bind);

      } else {

           $this->removeCookies();

      }

       $last_session = session_id();

       session_regenerate_id(true);
       
       $recent_session = session_id();

       $this->userDao->updateUserSession($recent_session, abs((int)$account_info['ID']));
       
       direct_page('index.php?load=dashboard', 200);
   
 }
 
 
 /**
  * Logout
  */
public function logout()
{
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_login']);
    unset($_SESSION['user_fullname']);
    unset($_SESSION['user_session']);
    unset($_SESSION['user_level']);
  
  	session_destroy();
    
    $this->removeCookies();
    
    $logout = APP_PROTOCOL . '://' . APP_HOSTNAME . dirname($_SERVER['PHP_SELF']) . '/';

    header("Location: $logout");
    exit();
    
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
  
public function resetUserPassword($user_email)
{
   
  $reset_key = md5(uniqid(rand(),true));

  if ($this->userDao->updateResetKey($reset_key, $user_email)) {
      
      # send notification to user email account
      reset_password($user_email, $reset_key);
    
  }

}

public function updateNewPassword($user_pass, $user_id)
{
  $this->validator->sanitize($user_id, 'int');
  $this->validator->validate($user_id, 'number');
  $this->validator->validate($user_pass, 'password');

  $bind = ['user_pass' => $user_pass, 'user_reset_complete' => 'Yes'];

  if ($this->userDao->recoverNewPassword($bind, $user_id)) {
      recover_password($user_pass);
  }

}

public function removeCookies()
{

  if (isset($_COOKIE['cookie_user_email']) && isset($_COOKIE['random_pwd']) && isset($_COOKIE['random_selector'])) {

      setcookie("cookie_user_email", "", time() - self::COOKIE_EXPIRE, self::COOKIE_PATH);
      setcookie("cookie_user_id", "", time() - self::COOKIE_EXPIRE, self::COOKIE_PATH);
      setcookie("cookie_user_level", "", time() - self::COOKIE_EXPIRE, self::COOKIE_PATH);
      setcookie("cookie_user_login", "", time() - self::COOKIE_EXPIRE, self::COOKIE_PATH);
      setcookie("cookie_user_session", "", time() - self::COOKIE_EXPIRE, self::COOKIE_PATH);
      setcookie("cookie_user_fullname", "", time() - self::COOKIE_EXPIRE, self::COOKIE_PATH);
      setcookie("random_pwd", "", time() - self::COOKIE_EXPIRE, self::COOKIE_PATH);  
      setcookie("random_selector", "", time() - self::COOKIE_EXPIRE, self::COOKIE_PATH);

  }

}
 
}
