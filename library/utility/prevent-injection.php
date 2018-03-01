<?php

// prevent from injection
function prevent_injecttion($data)
{
    
    $data = @trim(stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES))));
    return $data;
    
}