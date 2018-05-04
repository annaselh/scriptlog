<?php

// function redirect page
function direct_page($page = '')
{
 // defining url
 $url = APP_PROTOCOL . '://' . APP_HOSTNAME . dirname($_SERVER['PHP_SELF']);
    
 // remove any trailing slashes
 $url = rtrim($url, '/\\');
    
 // add the page
 $url .= '/' . $page;
    
 // redirect the user
 header("Location: $url");
 exit();
 
}
