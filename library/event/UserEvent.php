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
  * user session
  * @var string
  */
 private $user_session;
 
 private $userDao;

 private $validator;

 private $sanitize;

 
 public function __construct(User $userDao, FormValidator $validator, Sanitize $sanitize)
 {
    $this->userDao = $userDao;

    $this->validator = $validator;
    
    $this->sanitize = $sanitize;
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
 
 public function setUserActivationKey($activation_key)
 {
   $this->user_activation_key = $activation_key;
 }
 
 public function setUserSession($user_session)
 {
   $this->user_session = $user_session;
 }
 
 public function grabUsers($orderBy = 'ID', $fetchMode = null)
 {
   return $this->userDao->getUsers($orderBy, $fetchMode);    
 }
 
 public function grabUser($userId)
 {
   return $this->userDao->getUserById($userId, $this->sanitize);
 }
 
 public function addUser()
 {
  $this->validator->sanitize($this->user_login, 'string');
  $this->validator->sanitize($this->user_fullname, 'string');
  $this->validator->sanitize($this->user_email, 'email');

    if (empty($this->user_activation_key)) {
           
        return $this->userDao->createUser([
            'user_login' => $this->user_login,
            'user_email' => $this->user_email,
            'user_pass'  => $this->user_pass,
            'user_level' => $this->user_level,
            'user_fullname' => $this->user_fullname,
            'user_url' => $this->user_url,
            'user_registered' => date("Y-m-d H:i:s"),
            'user_session' => $this->user_session
        ]);
        
    } else {
        
        return $this->userDao->createUser([
            'user_login' => $this->user_login,
            'user_email' => $this->user_email,
            'user_pass'  => $this->user_pass,
            'user_level' => $this->user_level,
            'user_fullname' => $this->user_fullname,
            'user_url' => $this->user_url,
            'user_activation_key' => $this->user_activation_key,
            'user_session' => $this->user_session
        ]);
        
    }
    
 }
 
 public function modifyUser()
 {
  
  $this->validator->sanitize($this->user_url, 'url');
  $this->validator->sanitize($this->user_fullname, 'string');
  $this->validator->sanitize($this->user_email, 'email');
  
   if ($this->accessLevel() != 'administrator') {
   
       if (!empty($this->user_pass)) {
           
           $bind = [
               'user_email' => $this->user_email,
               'user_pass' => $this->user_pass,
               'user_fullname' => $this->user_fullname,
               'user_url' => $this->user_url
              ];
           
       } else {
           
           $bind = [
               'user_email' => $this->user_email,
               'user_fullname' => $this->user_fullname,
               'user_url' => $this->user_url
           ];
           
       }
   
   } else {
       
       if (!empty($this->user_pass)) {
           
           $bind = [
               'user_email' => $this->user_email,
               'user_pass' => $this->user_pass,
               'user_level' => $this->user_level,
               'user_fullname' => $this->user_fullname,
               'user_url' => $this->user_url
           ];
           
       } else {
           
           $bind = [
               'user_email' => $this->user_email,
               'user_level' => $this->user_level,
               'user_fullname' => $this->user_fullname,
               'user_url' => $this->user_url
           ];
           
       }
       
   }
      
   return $this->userDao->updateUser($this->accessLevel(), $this->sanitize, $bind, $this->user_id);
   
 }
 
 public function removeUser()
 {
   $this->validator->sanitize($this->user_id, 'int');
   if (!$data_user = $this->userDao->getUserById($this->user_id, $this->sanitize)) {
       
    direct_page('index.php?load=users&error=userNotFound', 404);
   
   }
   
   return $this->userDao->deleteUser($this->user_id, $this->sanitize);
   
 }
 
 /**
  * User Level DropDown
  * 
  * @param string $selected
  * @return string
  */
 public function userLevelDropDown($selected = "") 
 {
    return $this->userDao->dropDownUserLevel($selected);
 }
 
 /**
  * 
  * @param string $user_email
  * @return boolean
  */
 public function isEmailExists($user_email)
 {
   return $this->userDao->checkUserEmail($user_email);    
 }
 
 /**
  * Total Users records
  * @return boolean
  */
 public function totalUsers($data = null)
 {
   return $this->userDao->totalUserRecords($data);
 }
 
}
