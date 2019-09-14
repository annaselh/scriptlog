<?php
/**
 * Function build query
 * 
 * @category Function
 * @package  SCRIPTLOG/LIB/UTILITY
 * @param string $base
 * @param array $query_data
 * @return string
 * 
 */
function build_query($base, $query_data)
{
  
  $url = $base . "?". http_build_query($query_data);
  $safe_url = htmlspecialchars($url, ENT_QUOTES);
  return $safe_url;
  
}