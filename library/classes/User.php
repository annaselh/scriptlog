<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

class User extends Model
{
			
	public function __construct()
	{
		parent::__construct();
	}
	
	
	public function createUser($bind) 
	{
		
	   $stmt = $this->dbInsert("users", $bind);
	
	}
	
	public function updateUser($email, $user_fullname, $phone, 
			$level, $ID,  $accessLevel, $password = null)
	{
		try {
			
		if ($accessLevel != 'Administrator') {
			
			if (empty($password)) {
				
				$sql = "UPDATE users SET user_email = ?,
						user_fullname = ? WHERE ID = ?";
				
				
				$data = array($email, $user_fullname, $ID);
				
			} else {
				
				$hash_password = shield_pass($password, $ID);
				
				$sql = "UPDATE users SET user_email = ?, user_pass = ?,
						user_fullname = ? WHERE ID = ?";
				
				$data = array($email, $user_fullname, $hash_password, $ID);
				
			} 
			
			$stmt = $this->statementHandle($sql, $data);
			
		} else {
		
			if (empty($password)) {
				
				$sql = "UPDATE users SET user_email = ?,
						user_fullname = ?, user_level = ? WHERE ID = ?";
				
				
				$data = array($email, $user_fullname, $level, $ID);
				
			} else {
				
				$hash_password = shield_pass($password, $ID);
				
				$sql = "UPDATE users SET user_email = ?, user_pass = ?,
						user_fullname = ?, user_level = ? WHERE ID = ?";
				
				$data = array($email, $user_fullname, $hash_password, $level, $ID);
				
			}
			
			$stmt = $this->statementHandle($sql, $data);
			$this->closeDbConnection();
			
		}
			
		} catch (PDOException $e) {
			
		  $this -> closeDbConnection();
		
		  $this->error = LogError::newMessage($e);
		  $this->error = LogError::customErrorMessage();
				 
		}
		
	}
	
	public function deleteUser($ID, $sanitizing)
	{
		
		$cleanId = $this->filteringId($sanitizing, $ID, 'sql');
		
		$sql = "DELETE FROM users WHERE ID = ?";
		
		$data = array($cleanId);
		
		$stmt = $this->statementHandle($sql, $data);
		
	}
	
	public function setUserLevels($selected = '')
	{
		$option_selected = "";
	
		if (!$selected) {
				
			$option_selected = 'selected="selected"';
		}
	
		$levels = array('Administrator', 'Editor', 'Author',  'Contributor');
	
		$html = array();
	
		$html[] = '<label>Role*</label>';
		$html[] = '<select class="form-control" name="level">';
		
		foreach ( $levels as $g => $level) {
			
			if ($selected == $level) {
				
				$option_selected = 'selected="selected"';
			}
			
			// set up the option line
			$html[]  =  '<option value="' . $level. '"' . $option_selected . '>' . $level . '</option>';
			
			// clear out the selected option flag
			$option_selected = '';
			
		}
		
		if ( empty($selected) || empty($level))
		{
			$html[] = '<option value="0" selected> -- Select Role -- </option>';
		}
	
		$html[] = '</select>';
		
		return implode("\n", $html);
	
	}
	
	public function findAllUsers($position, $limit, $orderBy="user_fullname")
	{
		
		$sql = "SELECT ID, user_login, 
				user_email, user_fullname, user_pass, 
				user_level, user_reset_key, 
				user_reset_complete, user_session,
				user_registered
				FROM users ORDER BY $orderBy
				LIMIT :position, :limit";
		
		$stmt = $this->dbc->prepare($sql);
		
		$stmt -> bindParam(":position", $position, PDO::PARAM_INT);
		$stmt -> bindParam(":limit", $limit, PDO::PARAM_INT);
		
		try {
			
			$stmt -> execute();
			
			$users = array();
			
			foreach ($stmt -> fetchAll() as $row) {
				
				$users[] = $row;
			}
			
			$numbers = "SELECT ID FROM users";
			$stmt = $this->dbc->query($numbers);
			$totalUsers = $stmt -> rowCount();
			
			return (array("results" => $users, "totalUsers" => $totalUsers));
			
		} catch (PDOException $e) {
			
			$this->closeDbConnection();
			$this->error = LogError::newMessage($e);
			$this->error = LogError::customErrorMessage();
			
		}
		
	}
	
	public function findUser($userId, $sanitizing)
	{
	
		$sql = "SELECT ID, user_login, user_email, user_fullname, user_level, 
				user_session FROM users 
				WHERE ID = :ID";
		
		$id_sanitized = $this->filteringId($sanitizing, $userId, 'sql');
		
		$data = array(":ID"=>$id_sanitized);
		
		$stmt = $this -> statementHandle($sql, $data);

		return $stmt -> fetch();
		
	}
	
	public function isUserLoginExists($user_login)
	{
		$sql = "SELECT COUNT(ID) FROM users WHERE user_login = ?";
		
		$stmt = $this->dbc->prepare($sql);
		$stmt -> bindValue(1, $user_login);
		
		try {
			$stmt -> execute();
			$rows = $stmt -> fetchColumn();
			
			if ($rows == 1) {
			
				return true;
			
			} else {
			
				return false;
			
			}
			
		} catch (PDOException $e) {
			
			$this->closeDbConnection();
			
			$this->error = LogError::newMessage($e);
			$this->error = LogError::customErrorMessage();
			
		}
	}
	
	public function checkUserId($id, $sanitizing)
	{
	 
	 $sql = "SELECT ID FROM users WHERE ID = ?";

	 $cleanUpId = $this->filteringId($sanitizing, $id, 'sql'); 
	 
	 $stmt = $this->dbc->prepare($sql);
	 
	 $stmt -> bindValue(1, $cleanUpId);
	 
	 try {
	 	
	 	$stmt -> execute();
	 	$rows = $stmt -> rowCount();
	 	
	 	if ($rows > 0) {
	 		
	 		return true;
	 		
	 	} else {
	 		
	 		return false;
	 		
	 	}
	 	
	 } catch (PDOException $e) {
	 	
	 	$this->closeDbConnection();
	 	
	 	$this->error = LogError::newMessage($e);
	 	$this->error = LogError::customErrorMessage();
	 	
	 }
	 
	}
	
	public function checkUserSession($sesi)
	{
      $sql = "SELECT COUNT(ID) FROM users WHERE user_session = ?";
      
      $stmt = $this->dbc->prepare($sql);
      
      $stmt -> bindValue(1, $sesi);
      
      try {
      	$stmt -> execute();
      	$rows = $stmt -> fetchColumn();
      	
      	if ($rows == 1) {
      		
      		return true;
      		
      	} else {
      		
      		return false;
      		
      	}
      	
      } catch (PDOException $e) {
      	
      	$this->closeDbConnection();
      	
      	$this->error = LogError::newMessage($e);
      	$this->error = LogError::customErrorMessage();
      	
      }
      
	}
	
	private function checkUserEmail($email)
	{
		$sql = "SELECT user_email FROM users WHERE user_email = ?";
	
		$data = array($email);
	
		$sth = $this->statementHandle($sql, $data);
	
		if ( $sth -> rowCount() == 1) {
	
			$e = new Exception("Error: '$email' has been used. Please use other e-mail address !");
	
			throw $e;
			
		}
	
	}
	
	protected static function createSessionKey($key)
	{
	 // create session
	 $salt = 'cTtd*7xMCY-MGHfDagnuC6[+yez/DauJUmHTS).t,b,T6_m@TO^WpkFBbm,L<%C';
	 $key = sha1(mt_rand(10000, 99999) . time(). $salt);
	 return $key;
	}
	
}