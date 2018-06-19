<?php
/**
 * Generate Session Key Function
 * 
 * @param string $value
 * @return string
 */
function generate_session_key($value)
{
    // create value
    $salt = random_generator(13);
    $sessionKey = sha1(mt_rand(100, 999) . time(). $salt .$value);
    return $sessionKey;
    
}