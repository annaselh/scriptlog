<?php
/**
 * Read Datetime Function
 * Read datetime field from MySQL Database
 * 
 * @param string $datetime
 * @return string
 */
function read_datetime($datetime)
{
  return date_create($datetime);
}