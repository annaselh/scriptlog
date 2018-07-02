<?php 
/**
 * SearchSeeker Class
 * Searching keyword from 
 * search functionality form
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class SearchSeeker
{
 
 private $dbc;
 
 private $errors;
 
 public $sql;
 
 public $results;
 
 public $bind;
 
 public function __construct($dbc)
 {
  $this->dbc = $dbc;
 }
 
 public function cleanUp($bind)
 {
  return $bind;
 }
 
 public function bindStatement($sql, $bind = "")
 {
  $this->sql = $sql;
  $this->bind = $this->cleanUp($bind);
  $this->errors = '';
  
  try {
  	
  	$sth = $this->dbc->prepare($this->sql);
  	
  	if ( $sth -> execute($this->bind) !== false )
  	{
  		
  		if (preg_match("/^(" . implode("|", array ("select", "describe", "pragma")) . ") /i", $this->sql))
  		{
  			
  			return $sth->fetchAll(PDO::FETCH_ASSOC);
  		}
  		elseif (preg_match("/^(" . implode("|", array ("delete", "insert", "update")) . ") /i", $this->sql))
  		{
  			
  			return $sth->rowCount();
  		}
  		
  	}
  	
  } catch (PDOException $e) {
  	
  	$this->errors = LogError::newMessage($e);
  	$this->errors = LogError::customErrorMessage();
  	
  }
  
  return false;
  
 }
 
 public function setQuery($sql, $bind = false)
 {
  $this->errors = '';
  
  try {
  	
  	if ($bind !== false) {
  	
  	 return $this->bindStatement($sql, $bind);
  	 
  	} else {
  		
  		$this->results = $this->query($sql);
  		return $this->results;
  	}
  	
  } catch (PDOException $e) {
  	
  	$this->error = LogError::newMessage($e);
  	$this->error = LogError::customErrorMessage();
  	
  }
  
  return false;
  
 }
 
 public function searchPost($data)
 {
    
    $bind = array(":keyword1" => "%$data%", ":keyword2" => "%$data%");
     
 	$this->sql = "SELECT 
                     ID,
                     post_author, date_created, 
                     post_title, post_slug, 
                     post_content, post_status, 
                     post_type
                 FROM 
                    posts
                 WHERE 
                    post_title LIKE :keyword1 OR post_content LIKE :keyword2
                    AND post_status = 'publish' AND post_type = 'blog' ";
 	             
 	
 	$results = $this->setQuery($this->sql, $bind); // hasil pencarian
 	
 	$sth = $this->dbc->prepare($this->sql);
 	$keyword = '%'.$data.'%';
 	$sth -> bindValue(':keyword1', $keyword, PDO::PARAM_STR);
 	$sth -> bindValue(':keyword2', $keyword, PDO::PARAM_STR);
 	$sth -> execute();
 	$totalRows = $sth -> rowCount();
 	
 	return (array("results" => $results, "totalRows" => $totalRows));
 	
 }
 
}