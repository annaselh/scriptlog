<?php
// generate session key
function generate_session_key($value)
{
    // create value
    $salt = random_generator(13);
    $sessionKey = sha1(mt_rand(100, 999) . time(). $salt .$value);
    return $sessionKey;
    
}