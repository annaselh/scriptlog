<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * Db Class
 * Implements dbInterface
 * 
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @copyright 2018 kartatopia.com
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Db implements DbInterface
{
 
 /**
  * database connection
  * @var string
  */
 private $dbc;
 
 /**
  * Error
  * @var string
  */
 private $error;
 
 public function __construct($values, $options)
 {
     $this->setDbConnection($values, $options);
 }
 
 /**
  * set database connection
  * {@inheritDoc}
  * @see DbInterface::setDbConnection()
  */
 public function setDbConnection($values = [], $options = [])
 {
   try {
       
      $dsn = $values[0];
      $dbUser = $values[1];
      $dbPass = $values[2];
      
      $default_options = [
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
          PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      ];
      
      $options = array_replace($default_options, $options);
      
      $dbc = new PDO($dsn, $dbUser, $dbPass, $options);
      
      $this->dbc = $dbc;
       
   } catch (DbException $e) {
       
      $this->closeDbConnection();
      $this->error = LogError::newMessage($e);
      $this->error = LogError::customErrorMessage();
      
   }
   
 }
 
 /**
  * close database connection
  */
 public function closeDbConnection()
 {
   $this->dbc = null;
 }
 
 /**
  * SQL - Query
  * 
  * {@inheritDoc}
  * @see DbInterface::dbQuery()
  */
 public function dbQuery($sql, $args = null)
 {
     if (!$args) {
         
         return $this->dbc->query($sql);
         
     }
     
     $stmt = $this->dbc->prepare($sql);
     $stmt -> execute($args);
     return $stmt;
 }
 
 /**
  * Insert record
  * 
  * {@inheritDoc}
  * @see DbInterface::dbInsert()
  */
 public function dbInsert($tablename, $params)
 {
    try {
        
        ksort($params);
        $columnsNames = implode('`,`',array_keys($params));
        $columnsValues = ':' . implode(', :',array_keys($params));
        
        $stmt = $this->dbc->prepare("INSERT INTO $tablename(`$columnsNames`) VALUES($columnsValues)");
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        return $stmt->execute();
        
    } catch (DbException $e) {
        
       $this->closeDbConnection();
       $this->error = LogError::newMessage($e);
       $this->error = LogError::customErrorMessage();
       
    }
    
 }
 
 /**
  * last insert id
  * 
  * {@inheritDoc}
  * @see DbInterface::dbLastInsertId()
  */
 public function dbLastInsertId()
 {
    return $this->dbc->lastInsertId();
 }
 
 /**
  * Update record
  * {@inheritDoc}
  * @see DbInterface::dbUpdate()
  */
 public function dbUpdate($tablename, $params, $where)
 {
   try {
       
       ksort($params);
       $columns = null;
       
       foreach ($params as $key => $value) {
           
           $columns .= "`$key` = :$key,";
       }
       
       $columns = rtrim($columns,',');
       
       $stmt = $this->dbc->prepare("UPDATE $tablename SET $columns WHERE $where");
       
       foreach ($params as $key => $value) {
           $stmt->bindValue(":$key", $value);
       }
       
       $stmt -> execute();
       
       return $stmt -> rowCount();
       
   } catch (DbException $e) {
       
       $this->closeDbConnection();
       $this->error = LogError::newMessage($e);
       $this->error = LogError::customErrorMessage();
       
   }   
   
 }
 
 /**
  * delete record
  * 
  * {@inheritDoc}
  * @see DbInterface::dbDelete()
  */
 public function dbDelete($tablename, $where, $limit = null)
 {
    try {
        
        if (!is_null($limit)) {
            
            $sql = "DELETE FROM $tablename WHERE $where LIMIT $limit";
        
        } else {
            
            $sql = "DELETE FROM $tablename WHERE $where";
        }
        
        $stmt = $this->dbc->prepare($sql);
        return $stmt -> execute();
        
    } catch (DbException $e) {
        
        $this->closeDbConnection();
        $this->error = LogError::newMessage($e);
        $this->error = LogError::customErrorMessage();
        
    }
    
 }
 
}