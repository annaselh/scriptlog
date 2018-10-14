<?php 
/**
 * DbInterface interface
 * Describe the functionality
 * that any database adapter will need.
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
interface DbInterface
{
    
 public function setDbConnection($values = [], $options = []);
 
 public function closeDbConnection();
 
 public function dbQuery($sql, $parameters = null);
 
 public function dbInsert($tablename, array $params);
 
 public function dbUpdate($tablename, $params, $where);
 
 public function dbDelete($tablename, $where, $limit = null);
 
 public function dbLastInsertId();
    
}