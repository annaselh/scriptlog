<?php
/**
 * Scriptlog verify password
 * 
 * @param string $user_input The user supplied string
 * @param string $stored The string of known length to compare against
 * @return boolean
 */
function scriptlog_verify_password($user_input, $stored)
{
    $result = 0;
    
    if (!function_exists('hash_equals')) {
        
        if (timing_safe_equals($stored, $user_input)) {
            
            if (password_verify(base64_encode(hash('sha384', $user_input, true)), $stored)) {
              
                return $result === 0;
                
            } else {
                
                scriptlog_error("Password not recognized!");
            }
            
        } else {
            
            scriptlog_error("Unidentical string detected!");
            
        }
        
    } else {
        
        if (hash_equals($stored, $user_input)) {
            
            if (password_verify(base64_encode(hash('sha384', $user_input, true)), $stored)) {
                
                return $result === 0;
                
            } else {
                
                scriptlog_error("Password not recognized!");
            }
            
        } else {
            
            scriptlog_error("Unidentical string detected!");
            
        }
        
    }
    
}