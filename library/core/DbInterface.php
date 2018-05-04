<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * DbInterface - Interface
 * Describe the functionality
 * that any database adapter will need.
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @copyright 2018 kartatopia.com
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
interface DbInterface
{
    
 function setDbConnection($values = [], $options = []);
 
 function closeDbConnection();
 
 function dbQuery($sql, $parameters = null);
 
 function dbInsert($tablename, $params);
 
 function dbUpdate($tablename, $params, $where);
 
 function dbDelete($tablename, $where, $limit = null);
 
 function dbLastInsertId();
    
}