<?php

class MysqlTransaction
{
    function select()
    {
        return new MysqlResult();
    }
}

class MysqlResult
{
   function next()
   {
      return array('one' => '1');
   }
}