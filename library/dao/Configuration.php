<?php 
/**
 * Configuration Class
 * 
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Configuration
{
  
 protected $dbc;
  
 public function __construct($dbc)
 {
		$this->dbc = $dbc;
 }
 
 public function updateConfig($id, $siteName, $metaDescription, $metaKeywords, 
              $instagram, $twitter, $facebook, $logo = null)
 {
 
  if (empty($logo)) {
  	
  $sql = "UPDATE settings SET site_name = ?, meta_description = ?,
  		    meta_keywords = ?, facebook_url = ?, twitter_url, instagram_url = ? 
  		WHERE ID = ?";
  	 
  $data = array($siteName, $metaDescription, $metaKeywords, $facebook, 
              $twitter, $instagram, $id);
  	 
  } else {
  	
   $sql = "UPDATE settings SET site_name = ?, meta_description = ?,
  		    meta_keywords = ?, logo = ?, facebook_url = ?, twitter_url, instagram_url = ?
  		WHERE ID = ?";
      
   $data = array($siteName, $metaDescription, $metaKeywords, $logo, $facebook, $twitter, $instagram, $id);

  }
  
  $stmt = $this->statementHandle($sql, $data);
  
 }
  
 public function checkConfigId($id, $sanitizing) 
 {
 
 $sql = "SELECT ID FROM settings WHERE ID = ? LIMIT 1";
 
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
  	
  	$this->dbc = null;
  	
  	throw new PDOException($e);
  	
  }
  
 }
 
 public function findConfigs()
 {
 	$sql = "SELECT ID, app_key, site_name, meta_description, 
               meta_keywords, logo, favicon, facebook_url,
               twitter_url, instagram_url
  		 FROM settings LIMIT 1";
 	
 	$setup = array();
 	
 	$stmt = $this->dbc->query($sql);
 	
 	while ($row = $stmt -> fetch()) {
 		
 		$setup[] = $row;
 	}
 	
 	$this->dbc = null;
 	
 	return(array('results' => $setup));
 	
 }
 
 public function findConfig($id, $sanitizing)
 {
 
  $sql = "SELECT ID, app_key, site_name, meta_description, 
               meta_keywords, logo, favicon, facebook_url,
               twitter_url, instagram_url
  		 FROM settings  WHERE ID = ? LIMIT 1";
 	
 $id_sanitized = $this->filteringId($sanitizing, $id, 'sql');
 	
 $data = array($id_sanitized);
 	
 $stmt = $this->statementHandle($sql, $data);
 	
 return $stmt -> fetch();
 	
 }
 
 public function checkToSetup()
 {
   $sql = "SELECT ID FROM settings LIMIT 1";
   
   try {
   	
   	$stmt = $this->dbc->query($sql);
   	
   	$founded = $stmt -> rowCount();
   	
   	if ($founded < 1) {
   		
   		return true;
   		
   	} else {
   		
   		return false;
   	}
   	
   } catch (PDOException $e) {
   
   	 $this->dbc = null;

   	 throw new PDOException($e);
   	 
   }
   
 }
 
 protected function statementHandle($sql, $data = NULL)
 {
 	
 	$statement = $this->dbc->prepare($sql);
 	
 	try {
 		
 		$statement->execute($data);
 		
 	} catch (PDOException $e) {
 		
 		$this->dbc = null;
 		
 		$this->error = LogError::newMessage($e);
 		$this->error = LogError::customErrorMessage();
 		
 	}
 	
 	return $statement;
 	
 }
 
 protected function filteringId(Sanitize $sanitize, $str, $type)
 {
 	$this->sanitizing = $sanitize;
 	
 	$sanitized_var = filter_var($str, FILTER_SANITIZE_NUMBER_INT);
 	
 	if (filter_var($sanitized_var, FILTER_VALIDATE_INT)) {
 		
 		return $this->sanitizing->sanitasi($sanitized_var, $type);
 		
 	} else {
 		
 		$exception = "This Id is considered invalid";
 		
 		LogError::newMessage($exception);
 		LogError::customErrorMessage();
 		
 	}
 	
 }
 
}