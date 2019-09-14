<?php
/**
 * Function add_http
 * add http prefix to URL when it missing
 *  
 * @category function
 * @package SCRIPTLOG/LIB/UTILITY
 * @param string $url
 * @return string
 * 
 */
function add_http($url)
{
    if ($retrieveURL = parse_url($url)) {
        
        if ((!preg_match( "@^[hf]tt?ps?://@", $url)) || (!isset($retrieveURL["scheme"]))) {
            $url = "http://" . $url;
        }
        
    }
    
    return $url;
    
}