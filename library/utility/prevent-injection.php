<?php

// prevent from injection
function prevent_injection($data)
{
    
  $data = @trim(stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES))));
  return $data;
    
}