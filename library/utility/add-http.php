<?php
/**
 * Add Http Function
 * add http prefix to URL when missing
 *  
 * @param string $url
 * @return string
 */
function add_http($url)
{
    if ($retrieveURL = parse_url($url)) {
        
        if (!isset($retrieveURL["scheme"])) {
            $url = "http://{}";
        }
    }
    
    return $url;
}