<?php

// function redirect page
function direct_page($page = '')
{
    
 $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false ? 'http' : 'https';
 $host     = $_SERVER['HTTP_HOST'];
    
 // defining url
 $url = $protocol . '://' . $host . dirname($_SERVER['PHP_SELF']);
    
 // remove any trailing slashes
 $url = rtrim($url, '/\\');
    
 // add the page
 $url .= '/' . $page;
    
 // redirect the user
 header("Location: $url");
    
}
