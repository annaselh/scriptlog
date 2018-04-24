<?php
// create user_activation_key
function user_activation_key($value)
{
    $salt = 'c#haRl891';
    $token = md5( mt_rand( 10000, 99999 ) . time() . $value . $salt);
    return $token;
}