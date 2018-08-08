<?php

require_once('simpletest/autorun.php');
require_once(dirname(dirname(__FILE__)).'/transaction.php');

class TestOfMysqlTransaction extends UnitTestCase
{
    
    function testCanReadSimpleSelect()
    {
      
      $transaction =  new MysqlTransaction();
      $result = $transaction->select('SELECT 1 as one');
      $row = $result->next();
      $this->assertEqual($row['one'], 1);
 
    }
    
}


