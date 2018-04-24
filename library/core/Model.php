<?php 
/**
 * class Model
 * @author maoelana
 *
 */

class Model
{
 public $dbc;
 
 protected $error;
 
 protected $sanitizing;
  
 public function __construct() 
 {
   if (Registry::isKeySet('dbc')) $this->dbc = Registry::get('dbc');
 }
	
 public function dbSelect($sql, $bind = array(), $mode = null)
 {
    $statement = $this->dbc->prepare($sql);
    
    foreach ($bind as $parameters => $parameter) {
        $statement->bindParam("$parameters", $parameter);
    }
    
    try {
        
        $statement->execute();
        
        return $statement->fetchAll($mode);
        
    } catch (DbException $e) {
        
        $this->closeDbConnection();
        $this->error = LogError::newMessage($e);
        $this->error = LogError::customErrorMessage();
        
    }
    
 }
 
 public function dbInsert($table, $bind)
 {
   ksort($bind);
   $columns = implode('`,`',array_keys($bind));
   $values = ':' . implode(', :',array_keys($bind));
    
   $statement = $this->dbc->prepare("INSERT INTO $table(`$columns`) VALUES($values)");
    
   foreach ($bind as $parameters => $parameter) {
     $statement->bindValue(":$parameters", $parameter);
   }
    
   try {
        
     return $statement->execute();
      
   } catch (DbException $e) {
      
     $this->closeDbConnection();
     $this->error = LogError::newMessage($e);
     $this->error = LogError::customErrorMessage();
        
   }
     
 }
 
 public function dbUpdate($table, $bind, $whereClause)
 {
   ksort($bind);
   $columns = null;
   
   foreach ($bind as $parameters) {
       
       $columns .= "`$parameters` = :$parameters,";
   }
   
   $columns = rtrim($columns,',');
   
   $statement = $this->dbc->prepare("UPDATE $table SET $columns WHERE $whereClause");
   
   foreach ($bind as $parameters => $parameter) {
       $statement->bindValue(":$parameters", $parameter);
   }
   
   try {
       
      return $statement -> execute();
       
   } catch (DbException $e) {
       
      $this->closeDbConnection();
      $this->error = LogError::newMessage($e);
      $this->error = LogError::customErrorMessage();
      
   }
   
 }
 
 public function dbDelete($table, $whereClause, $limit = null)
 {
     if (!is_null($limit)) {
         
         $sql = "DELETE FROM $table WHERE $whereClause LIMIT $limit";
         
     } else {
         
         $sql = "DELETE FROM $table WHERE $whereClause";
     }
     
     try {
         
         $statement = $this->dbc->prepare($sql);
         return $statement -> execute();
         
     } catch (DbException $e) {
        
         $this->closeDbConnection();
         $this->error = LogError::newMessage($e);
         $this->error = LogError::customErrorMessage();
         
     }
     
 }
 	
 protected function statementHandle($sql, $data = null)
 {
     
     $statement = $this->dbc->prepare($sql);
     
     try {
         
         $statement->execute($data);
         
     } catch (PDOException $e) {
         
         $this->closeDbConnection();
         
         $this->error = LogError::newMessage($e);
         $this->error = LogError::customErrorMessage();
         
     }
     
     return $statement;
     
 }
 
 protected function lastId()
 {
   return $this->dbc->lastInsertId();
 }
	
 protected function closeDbConnection()
 {
   $this->dbc = null;
 }
		
 protected function filteringId(Sanitize $sanitize, $str, $type)
 {

 try {

   $this->sanitizing = $sanitize;
	 	
   switch ($type) {
      
      case 'sql':
        
          if (filter_var($str, FILTER_SANITIZE_NUMBER_INT)) {
              
              return $this->sanitizing->sanitasi($str, 'sql');
              
          } else {
              
              throw new Exception("ERROR: this - $str - Id is considered invalid.");
              
          }
          
          break;
      
      case 'xss':
            
          if (preventInject($str)) {
              
            return $this->sanitizing->sanitasi($str, 'xss');
              
          } else {
              
              throw new Exception("ERROR: this - $str - is considered invalid.");
          }
          
          break;
      
       }
	 	
      } catch (Exception $e) {
	 	
        $this->error = LogError::newMessage($e);
	    $this->error = LogError::customErrorMessage();}
			
	}
	
}