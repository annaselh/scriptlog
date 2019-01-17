<?php
/**
 * 
 */
function db_connect($host, $user, $passwd, $dbname)
{

  $database_connection = mysqli_connect($host, $user, $passwd, $dbname);

  if(mysqli_connect_errno($database_connection)) {
    scriptlog_error('Failed to connect to MySQL '.mysqli_connect_error(), E_USER_ERROR);
    exit();
  }

  return $database_connection;

}

// close database connection
function db_close($connection)
{
 return mysqli_close($connection);
}

function table_exists($connection, $table, $counter = 0)
{
  if ($connection) {
    $counter++;

    $check = mysqli_query("SHOW TABLES LIKE '".$table."'");

    if($check !== false) {

       if(mysqli_num_rows($check) > 0) {

         return true;

       } else {

         return false;

       }

    }

  }

}

// check whether database table does exist 
function check_table($connection, $dbname)
{
  $install = false;

  if(!table_exists($link, $table)) {
      
    $install = true;

  } else {

    $install = false;

  }

  return $install;

}

