<?php
/**
 * Function build query
 */
function build_query($base, $query_data)
{
  
  $url = $base . "?". http_build_query($query_data);
  $safe_url = htmlspecialchars($url, ENT_QUOTES);
  return $safe_url;
  
}