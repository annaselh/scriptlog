<?php
// generate session key
function generate_session_key($value)
{
    // create value
    $salt = 'cTtd*7xMCY-MGHfDagnuC6[+yez/DauJUmHTS).t,b,T6_m@TO^WpkFBbm,L<%C';
    $sessionKey = sha1(mt_rand(1000, 9999) . time(). $salt .$value);
    return $sessionKey;
    
}