<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * Dao Class
 * Data Access Object
 * 
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Dao
{
 /**
  * Database connection 
  * @var string
  */
 protected $dbc;
 
 /**
  * SQL
  * @var string
  */
 protected $sql;
 
 /**
  * Error
  * @var string
  */
 protected $error;
 
 /**
  * Sanitize
  * @var string
  */
 protected $sanitizing;
  
 public function __construct() 
 {
   if (Registry::isKeySet('dbc')) $this->dbc = Registry::get('dbc');
 }
	
 /**
  * Set SQL
  * @param string $sql
  */
 protected function setSQL($sql)
 {
    $this->sql = $sql;
 }
 
 /**
  * Set Error
  * @param LogError $error
  */
 protected function setError(LogError $error)
 {
   $this->error = $error;    
 }
 
 /**
  * Find All records
  * getting array of rows
  * 
  * @param array $data
  * @param PDO::FETCH_MODE static $fetchMode
  * @throws DbException
  * @return array|object
  */
 protected function findAll($data = null, $fetchMode = null)
 {
     
   try {
        
        if (!$this->sql) {
            throw new DbException("No SQL Query");
        }
        
        if (is_null($fetchMode)) {
           $stmt = $this->dbc->dbQuery($this->sql, $data);
           return $stmt -> fetchAll();
        } else {
            $stmt = $this->dbc->dbQuery($this->sql, $data);
            return $stmt->fetchAll($fetchMode);
        }
        
    } catch (DbException $e) {
    
        $this->closeConnection();
        $this->setError(LogError::newMessage($e));
        $this->setError(LogError::customErrorMessage());
        
    }
    
 }
 
 /**
  * Find Single row record
  * getting one row
  * 
  * @param array $data
  * @param PDO::FETCH_MODE static $fetchMode
  * @throws DbException
  * @return array|object
  */
 protected function findRow($data = null, $fetchMode = null)
 {
     
  try {
    
    if (!$this->sql) {
    
        throw new DbException("No SQL Query!");
    }
    
    if (is_null($fetchMode)) {
        $stmt = $this->dbc->dbQuery($this->sql, $data);
        return $stmt -> fetch();
    } else {
        $stmt = $this->dbc->dbQuery($this->sql, $data);
        return $stmt->fetch($fetchMode);
    }
    
  } catch (DbException $e) {
     
      $this->closeConnection();
      $this->setError(LogError::newMessage($e));
      $this->setError(LogError::customErrorMessage());
      
  }   
  
 }
 
 /**
  * Find Column
  * return a single column from the next row of results set
  * getting single field value
  * 
  * @param array $data
  * @param PDO::FETCH_MODE static $fetchMode
  * @throws DbException
  * @return boolean false if no more rows
  */
 protected function findColumn($data = null, $fetchMode = null)
 {
     
   try {
    
       if (!$this->sql) {
           
          throw new DbException("No SQL Query!");
           
       }
       
       if (is_null($fetchMode)) {
           
           $stmt = $this->dbc->dbQuery($this->sql, $data);
           return $stmt -> fetchColumn();
           
       } else {
           
           $stmt = $this->dbc->dbQuery($this->sql, $data);
           return $stmt -> fetchColumn($fetchMode);
           
       }
          
   } catch (DbException $e) {
       
       $this->closeConnection();
       $this->setError(LogError::newMessage($e));
       $this->setError(LogError::customErrorMessage());
       
   }
     
 }
 
 /**
  * check count values
  * row count
  * 
  * @param array $data
  * @throws DbException
  * @return boolean
  */
 protected function checkCountValue($data = null)
 {
     
   try {
         
        if (!$this->sql) {
             throw new DbException("No SQL Query!");
        }
         
        $stmt = $this->dbc->dbQuery($this->sql, $data);
        return $stmt->rowCount();
              
     } catch (DbException $e) {
         
         $this->closeConnection();
         $this->setError(LogError::newMessage($e));
         $this->setError(LogError::customErrorMessage());
         
     }
     
 }
 
 /**
  * Create records
  * 
  * @param string $table
  * @param array $params
  */
 protected function create($table, $params)
 {
   $stmt = $this->dbc->dbInsert($table, $params);
 }
 
 /**
  * Modify record
  * 
  * @param string $table
  * @param array $params
  * @param integer|string $where
  */
 protected function modify($table, $params, $where)
 {
   $stmt = $this->dbc->dbUpdate($table, $params, $where);
 }
 
 /**
  * Delete record
  * 
  * @param string $table
  * @param integer $where
  * @param integer $limit
  */
 protected function delete($table, $where, $limit = null)
 {
     if (!is_null($limit)) {
         $stmt = $this->dbc->dbDelete($table, $where, $limit);
     } else {
         $stmt = $this->dbc->dbDelete($table, $where);
     }
 }
 
 /**
  * Close database connection
  * 
  * @return bool
  */
 protected function closeConnection()
 {
   $this->dbc->closeDbConnection();
 }
 
 /**
  * Last insert Id
  * @return integer
  */
 protected function lastId()
 {
   return $this->dbc->dbLastInsertId();
 }
 
 /**
  * Filtering Id
  * Sanitizing server request $_GET
  * 
  * @param Sanitize $sanitize
  * @param string|integer $str
  * @param string|integer $type
  * @throws Exception
  * @return mixed 
  */
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
	 	
      } catch (DbException $e) {
	 	
          $this->setError(LogError::newMessage($e));
          $this->setError(LogError::customErrorMessage());
          
      }
			
	}
	
}