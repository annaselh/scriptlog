<?php 
/**
 * User class extends Dao
 * insert, update, delete 
 * and select records from users table
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class User extends Dao
{
 
 // overrides Dao constructor
 public function __construct()
 {
	parent::__construct();
 }
 
 /**
  * getUsers
  * list of users
  * 
  * @param integer $position
  * @param integer $limit
  * @param static $fetchMode
  * @param string $orderBy
  * @return boolean|array|object
  */
 public function getUsers($orderBy = 'ID', $fetchMode = null)
 {
    
    $sql = "SELECT ID, user_login,
				user_email, user_fullname,
				user_level, user_session
		   FROM users ORDER BY '$orderBy' DESC";
     
     $this->setSQL($sql);
     
     if (!is_null($fetchMode)) {
         
         $users = $this->findAll($fetchMode);
         
     } else {
         
         $users = $this->findAll();
         
     }
     
     if (empty($users)) return false;
     
     return $users;
     
 }

 /**
  * getUserByID
  * fetch single value by ID
  * 
  * @param integer $userId
  * @param object $sanitize
  * @param static $fetchMode
  * @return boolean|array|object
  */
 public function getUserById($userId, $sanitize, $fetchMode = null)
 {
   $cleanId = $this->filteringId($sanitize, $userId, 'sql');
   
   $sql = "SELECT ID, user_login, user_email, user_level, user_fullname, user_url, 
           user_session FROM users WHERE ID = :ID";
   
   $this->setSQL($sql);
   
   if (!is_null($fetchMode)) {
       
       $userDetails = $this->findRow([':ID' => $cleanId], $fetchMode);
       
   } else {
       
       $userDetails = $this->findRow([':ID' => $cleanId]);
       
   }
   
   if (empty($userDetails)) return false;
   
   return $userDetails;
   
 }

 /**
  * get user by email
  * 
  * @param string $user_email
  * @param static PDO::FETCH_MODE $fetchMode
  * @return boolean|array|object
  */
 public function getUserByEmail($user_email, $fetchMode = null)
 {
     
   $sql = "SELECT ID, user_login, user_email, user_level, 
           user_fullname, user_url, user_session 
          FROM users WHERE user_email = :user_email LIMIT 1";
   
   $this->setSQL($sql);
   
   if (is_null($fetchMode)) {
       
       $userDetails = $this->findRow([':user_email' => $user_email]);
       
   } else {
       
       $userDetails = $this->findRow([':user_email' => $user_email], $fetchMode);
       
   }
   
   if (empty($userDetails)) return false;
   
   return $userDetails;
   
 }
 
 /**
  * Create user
  * insert new record
  * 
  * @param array $bind
  */
 public function createUser($bind) 
 {
	
  $hash_password = scriptlog_password($bind['user_pass']);
  
  if (!empty($bind['user_activation_key'])) {
	  
	  $stmt = $this->create("users", [
	          
	          'user_login' => $bind['user_login'],
	          'user_email' => $bind['user_email'],
	          'user_pass'  => $hash_password,
	          'user_level' => $bind['user_level'],
	          'user_fullname' => $bind['user_fullname'],
	          'user_url'   => $bind['user_url'],
	          'user_activation_key' => $bind['user_activation_key'],
	          'user_session' => $bind['user_session']
	          
	      ]);
	      
   } else {
	      
      $stmt = $this->create("users", [
	          
          'user_login' => $bind['user_login'],
          'user_email' => $bind['user_email'],
          'user_pass'  => $hash_password,
          'user_level' => $bind['user_level'],
          'user_fullname' => $bind['user_fullname'],
          'user_url'   => $bind['user_url'],
          'user_registered' => $bind['user_registered'],
          'user_session' => $bind['user_session']
          
      ]);
	      
   }
	   
 }

 /**
  * Update user
  * Modify user record in user table
  * 
  * @param string $accessLevel
  * @param array $bind
  * @param integer $ID
  */
 public function updateUser($accessLevel, $sanitize, $bind, $userId)
 {
  $cleanId = $this->filteringId($sanitize, $userId, 'sql');
  $hash_password = scriptlog_password($bind['user_pass']);
  
     if ($accessLevel != 'Administrator') {
         
         if (empty($bind['user_pass'])) {
             
             $bind = array(
                'user_email' => $bind['user_email'],
                'user_fullname' => $bind['user_fullname'],
                'user_url' => $bind['user_url'] 
             );
             
         } else {
             
             $bind = array(
                 'user_email' => $bind['user_email'],
                 'user_pass' => $hash_password,
                 'user_fullname' => $bind['user_fullname'],
                 'user_url' => $bind['user_url']
             );
             
         }
         
     } else {
         
         if (empty($bind['user_pass'])) {
             
             $bind = array(
                 'user_email' => $bind['user_email'],
                 'user_level' => $bind['user_level'],
                 'user_fullname' => $bind['user_fullname'],
                 'user_url'=> $bind['user_url']
             );
             
         } else {
              
             $bind = array(
                 'user_email' => $bind['user_email'],
                 'user_pass' => $hash_password,
                 'user_level' => $bind['user_level'],
                 'user_fullname' => $bind['user_fullname'],
                 'user_url' => $bind['user_url']
             );
             
         }
          
     }
     
     $stmt = $this->modify("users", $bind, "`ID` = {$cleanId}");
     
 }

 /**
  * Update user session
  * 
  * @param string $accessLevel
  * @param object $sanitize
  * @param array $bind
  * @param integer $userId
  */
 public function updateUserSession($sanitize, $bind, $userId)
 {
   try {
       
       $cleanId = $this->filteringId($sanitize, $userId, 'sql');
       
       if (function_exists("random_bytes")) {
           $user_session = bin2hex(random_bytes(32).microtime()*10000000);
       } elseif (function_exists("openssl_random_pseudo_bytes")) {
           $user_session = bin2hex(openssl_random_pseudo_bytes(32).microtime()*10000000);
       } else {
          throw new DbException("No cryptographycal support for your php version!");    
       }
       
       $stmt = $this->modify("users", ['user_session' => $user_session], "`ID` = {$cleanId}");
       
   } catch (DbException $e) {
       
       $this->error = LogError::newMessage($e);
       $this->error = LogError::customErrorMessage();
       
   }
 }
 
 /**
  * Activate user
  * 
  * @param string $key
  * @return int
  */
 public function activateUser($key)
 {
   $cek_user_key = $this->checkActivationKey($key);
   
   if ($cek_user_key === false) {
       
       direct_page();
       
   } else {
       
       $bind = ['user_activation_key' => '1', 'user_registered' => date("Ymd")];
       $stmt = $this->modify("users", $bind, "`user_activation_key` = {$key}");
       return $stmt -> rowCount();
       
   }
   
 }
 
 /**
  * Delete user
  * delete an existing records in user table
  * 
  * @param integer $ID
  * @param object $sanitizing
  */
 public function deleteUser($ID, $sanitize)
 {
  
  $clean_id = $this->filteringId($sanitize, $ID, 'sql');
   
  $stmt = $this->deleteRecord("users", "`ID` = {$clean_id}");
	 
 }
 
 /**
  * set user level
  * 
  * @param string $selected
  * @return string
  */
 public function dropDownUserLevel($selected = '')
 {
  
  $name = 'user_level';
  $levels = array('manager'=>'Manager', 'editor' => 'Editor', 
           'author'=>'Author', 'contributor'=>'Contributor');
  
  if ($selected != '') {
      $selected = $selected;
  } 
  
  return dropdown($name, $levels, $selected);
  
 }

 /**
  * Checking username
  * exists or not
  * 
  * @param string $user_login
  * @return boolean
  */
 public function isUserLoginExists($user_login)
 {
   
   $sql = "SELECT COUNT(ID) FROM users WHERE user_login = ?";
   $this->setSQL($sql);
   $stmt = $this->findColumn([$user_login]);
     
   if ($stmt == 1) {
      
      return true;
   
   } else {
      
      return false;
       
   }
	
 }
	 
 /**
  * checking user session
  * 
  * @param string $sesi
  * @return boolean
  */
 public function checkUserSession($sesi)
 {
    $sql = "SELECT COUNT(ID) FROM users WHERE user_session = ?";
    $this->setSQL($sql);
    $stmt = $this->findColumn([$sesi]);
     
    if ($stmt == 1) {
         
        return true;
     
    } else {
        
        return false;
     
    }
     
 }

 /**
  * checking email
  * 
  * @param string $email
  * @return boolean
  */
 public function checkUserEmail($email)
 {
     $sql = "SELECT ID FROM users WHERE user_email = :email LIMIT 1";
     $this->setSQL($sql);
     $stmt = $this->checkCountValue([':email' => $email]);
     return($stmt > 0);
 }

 /**
  * Checking password
  * 
  * @param string $email
  * @param string $password
  * @return boolean
  */
 public function checkUserPassword($email, $password)
 {
    $sql = "SELECT user_pass FROM users WHERE user_email = ? LIMIT 1";
    $this->setSQL($sql);
    $stmt = $this->checkCountValue([$email]);
    
    if ($stmt > 0) {
        
        $row = $this->findRow([$email], PDO::FETCH_ASSOC);
        
        return (password_verify($password, $row['user_pass']));
        
    }
    
    return false;
    
 }
 
 /**
  * Check User Id
  * 
  * @param integer $userId
  * @param object $sanitize
  * @return boolean
  */
 public function checkUserId($userId, $sanitize)
 {
     $sql = "SELECT ID FROM users WHERE ID = ?";
     $this->setSQL($sql);
     $idsanitized = $this->filteringId($sanitize, $userId, 'sql');
     $stmt = $this->checkCountValue([$idsanitized]);
     return($stmt > 0);
 }
 
 /**
  * Total User Record
  * @param array $data
  * @return integer
  */
 public function totalUserRecords($data = null)
 {
     $sql = "SELECT ID FROM users";
     
     $this->setSQL($sql);
     
     return $this->checkCountValue($data);
     
 }
 
 /**
  * Check Activation Key
  * 
  * @param string $key
  * @return boolean
  */
 private function checkActivationKey($key)
 {
    $sql = "SELECT COUNT(ID) FROM users WHERE user_activation_key = :user_activation_key";
    $this->setSQL($sql);
    $row = $this->findColumn([':user_activation_key' => $key]);
     
    if ($row == 1) {
         
       return true;
         
    } else {
         
       return false;
         
    }
    
 }
 
}