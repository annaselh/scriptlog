<?php

// hash password for volunteer
function shield_password($password, $id)
{
    
    $salt = '!hi#HUde9';
    
    if (check_magic_quote()) {
        
        $password = stripslashes(strip_tags( htmlspecialchars( $password, ENT_QUOTES ) ) );
        
        $shield = hash_hmac('sha512', trim($password).$salt.$id, APP_KEY);
        
        return $shield;
        
    } else {
        
        $shield = hash_hmac('sha512', trim($password).$salt.$id, APP_KEY);
        
        return $shield;
    }
    
}