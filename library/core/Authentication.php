<?php

class Authentication
{
  
 protected $dbc;
 
 protected $error;
 
 public function __construct($dbc)
 {
 	$this->dbc = $dbc;
 }
 
 public function findIdByEmail($email)
 {
 	$sql = "SELECT ID FROM users WHERE user_email = :email";
 	
 	$stmt = $this->dbc->prepare($sql);
 	
 	$stmt -> execute(array(":email" => $email));
 	
 	$row = $stmt -> fetch();
 	
 	return $row['ID'];
 	
 }
 
 public function isEmailExists($email)
 {
 	$sql = "SELECT `user_email` FROM `users` WHERE `user_email` = ? ";
 	
 	$stmt = $this->dbc->prepare($sql);
 	$stmt -> bindValue(1, $email);
 	
 	try {
 		
 	    $stmt -> execute();
 		$rows = $stmt -> rowCount();
 		
 		if ($rows > 0) {  // if rows are found for query
 			
 			return true;
 		}
 		else
 		{
 			return false;
 		}
 		
 	} catch (PDOException $e) {
 		
 		$this->dbc = null;
 		
 		$this->error = LogError::newMessage($e);
 		$this->error = LogError::customErrorMessage();
 			
 	}
 	
 }
 
 public function validateUser($email, $password)
 {
 	$volunteer_id = $this->findIdByEmail($email);
 	
    $hash_password = $this -> _verifyHashPassword($password, $volunteer_id);
 	
 	$sql = "SELECT ID, user_login, user_email, user_pass, 
            user_level, user_session
            FROM users WHERE user_email = :email 
            AND user_pass = :password ";
 	
 	$stmt = $this->dbc->prepare($sql);
 	$stmt -> bindParam(":email", $email, PDO::PARAM_STR);
 	$stmt -> bindParam(":password", $hash_password, PDO::PARAM_STR);
 	
 	try {
 		
 	 $stmt -> execute();
 	 
 	 return $stmt -> fetch();
 	 
 	} catch (PDOException $e) {
 		
 	  $this->dbc = null;
 	  
 	  $this->error = LogError::newMessage($e);
 	  $this->error = LogError::customErrorMessage();
 	  
 	}
 	
 }
 
 public function updateVolunteerSession($sessionKey, $email)
 {

   // update session
 	$sql = "UPDATE users SET user_session = :session 
           WHERE user_email = :email";
 	
 	$generateKey = generateSessionKey($sessionKey);
 	
 	$stmt = $this->dbc->prepare($sql);
 	$stmt -> bindparam(":session", $generateKey, PDO::PARAM_STR);
 	$stmt -> bindparam(":email", $email, PDO::PARAM_STR);
 	$stmt -> execute();
 	
 	// retrieve data users
 	$dataUser = $this->findPrivilege($email);
 	
 	if (isset($_SESSION['volunteerLoggedIn']) && $_SESSION['volunteerLoggedIn'] == true) {
  	 
 	 $_SESSION['ID'] = $dataUser['ID'];
 	 $_SESSION['Login'] = $dataUser['user_login'];
 	 $_SESSION['FirstName'] = $dataUser['volunteer_firstName'];
 	 $_SESSION['LastName'] = $dataUser['volunteer_lastName'];
 	 $_SESSION['Email'] = $dataUser['user_email'];
 	 $_SESSION['Level'] = $dataUser['user_level'];
 	 $_SESSION['Token'] = $dataUser['user_session'];
 	 $_SESSION['agent'] = sha1($_SERVER['HTTP_USER_AGENT']);
 	 	
 	 $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http' : 'https';
 	 $host     = $_SERVER['HTTP_HOST'];
 	 
 	 $logInPage = $protocol . '://' . $host . dirname($_SERVER['PHP_SELF']) . '/index.php?module=dashboard';
 	 
 	 header('Location:' . $logInPage);
 	 
 	}
 	
 }
 
 public function isVolunteerLoggedIn()
 {
  
  $_SESSION['volunteerLoggedIn'] = false;
 	
  if (isset($_SESSION['volunteerLoggedIn']) 
      && $_SESSION['volunteerLoggedIn'] == true) {
 	  
     return  true;
     
  } 
 	
 }
 
 public function accessLevel()
 {
 	if (isset($_SESSION['Level'])) {
 	
 	  return $_SESSION['Level'];
 	
 	} else {
 	
 		return false;
 	}
 	
 }
 
 public function signOutVolunteer()
 {
  if (!isset($_SESSION['ID'])) {
  	
  	directPage();
  	
  } else {
  	
  	$_SESSION = array();
  	
  	session_destroy();
  	
  	setcookie('PHPSESSID', '', time()-3600, '/', '', 0, 0);
  	
  	//Redirect to Login Page
  	$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http' : 'https';
  	$host     = $_SERVER['HTTP_HOST'];
  	
  	$logInPage = $protocol . '://' . $host . dirname($_SERVER['PHP_SELF']) . '/';
  	
  	header('Location:' . $logInPage);
  	
  }
  
 }
 
 public function recoverPassword($id, $password, $token)
 {
  
 $sql = "UPDATE users SET user_pass = :password, user_reset_complete = 'Yes' 
          WHERE user_reset_key = :token AND ID = :id";
 
 $hash_password = shieldPass($password, $id);
 
 try {
 	
 $stmt = $this->dbc->prepare($sql);
 
 $stmt -> execute(array(":token"=>$token, ":id"=>$id));

 if ($rows = $stmt -> rowCount() == 1) {
 		
 	// redirect to login page
 	$logInPage = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
 		
 	header('Location: ' . $logInPage . 'login.php?status=changed');
 		
 	}
 	
 } catch (PDOException $e) {
 	
   $this->dbc = null;
   
   $this->error = LogError::newMessage($e);
   $this->error = LogError::customErrorMessage();
   
 }
  
}
 
 private function _verifyHashPassword($password, $id)
 {
 	return shieldPass($password, $id);
 }
 
 protected function findPrivilege($email) 
 {
  $sql = "SELECT ID, user_login,
         user_email, user_fullname, user_level, user_reset_key,
         user_reset_complete, user_session, user_registered, 
         FROM users WHERE user_email = :email";
  
  $stmt = $this->dbc->prepare($sql);
  
  try {
  	
  	$stmt -> execute(array(":email" => $email));
  	
  	$this->dbc = null;
  	
  	return $stmt -> fetch();
  	
  } catch (PDOException $e) {
  	
  	$this->dbc = null;
  	
  	$this->error = LogError::newMessage($e);
  	$this->error = LogError::customErrorMessage();
  	
  }
  
 }
 
}