<?php

// scriptlog password is used when user forget reset their password
// this will be temporary password that need change after that
function scriptlog_pwd($password, $id)
{
    
    $salt = random_generator(32);
    
    if (check_magic_quote()) {
        
        $password = stripslashes(strip_tags( htmlspecialchars($password, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8")));
        
        $shield = hash_hmac('sha512', trim($password).$salt.$id, APP_KEY);
        
        return $shield;
        
    } else {
        
        $shield = hash_hmac('sha512', trim($password).$salt.$id, APP_KEY);
        
        return $shield;
    }
    
}