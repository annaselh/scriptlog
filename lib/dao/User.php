<?php 
/**
 * User class extends Dao
 * insert, update, delete 
 * and select records from users table
 *
 * @package   SCRIPTLOG/LIB/DAO/User
 * @category  Dao Class
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
				   user_email, 
                   user_fullname,
				   user_level, 
                   user_session
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
 * getUserById
 * fetch single value of record by ID
 * 
 * @param integer $userId
 * @param object $sanitize
 * @param static $fetchMode = null
 * @return boolean|array|object
 * 
 */
 public function getUserById($userId, $sanitize, $fetchMode = null)
 {
   $cleanId = $this->filteringId($sanitize, $userId, 'sql');
   
   $sql = "SELECT ID, user_login, user_email, 
                  user_level, user_fullname, 
                  user_url, 
                  user_registered,
                  user_session 
           FROM users WHERE ID = :ID";
   
   $this->setSQL($sql);
   
   if (is_null($fetchMode)) {
       
    $userDetails = $this->findRow([':ID' => $cleanId]);
       
   } else {
       
    $userDetails = $this->findRow([':ID' => $cleanId], $fetchMode);
        
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
  *
  */
 public function getUserByEmail($user_email, $fetchMode = null)
 {
     
   $sql = "SELECT ID, user_login, user_email, 
                  user_level, 
                  user_fullname, 
                  user_url, 
                  user_registered, 
                  user_session 
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
  * get User by reset key
  * 
  * @param string $reset_key
  * @return mixed
  *  
  */
 public function getUserByResetKey($reset_key)
 {
   $sql = "SELECT ID, user_reset_key, user_reset_complete FROM users 
           WHERE user_reset_key = :reset_key LIMIT 1";
   
   $this->setSQL($sql);
   
   $resetKeyDetails = $this->findRow([':reset_key' => $reset_key]);

   if (empty($resetKeyDetails)) return false;

   return $resetKeyDetails;

 }

 /**
  * Create user
  * insert new record
  * 
  * @param array $bind
  *
  */
 public function createUser($bind) 
 {
	
  $hash_password = scriptlog_password($bind['user_pass']);
  $user_url = (empty($bind['user_url'])) ? '#' : $bind['user_url'];

  if (!empty($bind['user_activation_key'])) {
	  
	  $stmt = $this->create("users", [
	          
	          'user_login' => $bind['user_login'],
	          'user_email' => $bind['user_email'],
	          'user_pass'  => $hash_password,
	          'user_level' => $bind['user_level'],
	          'user_fullname' => $bind['user_fullname'],
	          'user_url'   => $user_url,
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
          'user_url'   => $user_url,
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
  
     if ($accessLevel != 'administrator') {
         
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
     
     $stmt = $this->modify("users", $bind, "ID = {$cleanId}");
     
 }

 /**
  * Update user session
  * 
  * @param string $accessLevel
  * @param object $sanitize
  * @param array $bind
  * @param integer $userId
  */
 public function updateUserSession($user_session, $user_id)
 {
    $newSession = generate_session_key($user_session, 13);
    $bind = ['user_session' => $newSession];
    $stmt = $this->modify("users", $bind, "ID = {$user_id}");
 }

 /**
  * Update reset key
  * update temporary reset key 
  * to reset password with key sent to user email account
  *
  * @param string $reset_key
  * @param string $user_email
  *
  */
 public function updateResetKey($reset_key, $user_email)
 {
   $bind = ['user_reset_key' => $reset_key, 'user_reset_complete' => 'No'];
   $stmt = $this->modify("users", $bind, "user_email = {$user_email}");
 }

 /**
  * Recover New password
  * 
  * @param array $bind
  * @param integer $user_id
  *
  */
 public function recoverNewPassword($bind, $user_id)
 {
   $recoverPassword = scriptlog_password($bind['user_pass']);
   $stmt = $this->modify("users", [
          'user_pass' => $recoverPassword, 
          'user_reset_complete' => $bind['user_reset_complete']
          ], "ID = {$user_id}");
          
 }

 /**
  * Activate user
  * 
  * @param string $key
  * @return int
  */
 public function activateUser($key)
 {
   $userAccount = false;

   $cek_user_key = $this->checkActivationKey($key);
   
   if ($cek_user_key === false) {
       
       $userAccount = false;
       
   } else {
       
       $bind = ['user_activation_key' => '1', 'user_registered' => date("Y-m-d H:i:s")];
       $stmt = $this->modify("users", $bind, "user_activation_key = {$key}");
       $userAccount = true;
       
   }
   
   return $userAccount;

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
   
  $stmt = $this->deleteRecord("users", "ID = {$clean_id}");
	 
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
  $levels = array('manager'=>'Manager', 
                  'editor' => 'Editor', 
                  'author'=>'Author', 
                  'contributor'=>'Contributor');
  
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
    $sql = "SELECT COUNT(ID) FROM users WHERE user_session = :user_session";
    $this->setSQL($sql);
    $stmt = $this->findColumn([':user_session' => $sesi]);
     
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
 * Checking user password
 * 
 * @method public checkUserPassword()
 * @param string $email
 * @param string $password
 * @return bool
 * 
 */
 public function checkUserPassword($email, $password)
 {
    $sql = "SELECT user_pass FROM users WHERE user_email = :user_email LIMIT 1";
    $this->setSQL($sql);
    $stmt = $this->checkCountValue([$email]);
    
    if ($stmt > 0) {
        
        $row = $this->findRow([':user_email' => $email]);
        
        $expected = crypt($password, $row['user_pass']);
        $correct = crypt($password, $row['user_pass']);

        if(!function_exists('hash_equals')) {

            if(timing_safe_equals($expected, $correct) == 0) {

                if(scriptlog_verify_password($password, $row['user_pass'])) {

                    return true;

                }

            }

        } else {

            if(hash_equals($expected, $correct)) {

                if(scriptlog_verify_password($password, $row['user_pass'])) {

                    return true;

                }

            }
            
        }
        
    }
    
    return false;
    
 }
 
 /**
  * Check User Id
  * 
  * @param integer $userId
  * @param object $sanitize
  * @return numeric
  */
 public function checkUserId($userId, $sanitize)
 {
     $sql = "SELECT ID FROM users WHERE ID = ?";
     $idsanitized = $this->filteringId($sanitize, $userId, 'sql');
     $this->setSQL($sql);
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


