<?php
/**
 * check integer function
 * 
 * @param integer $input
 * @return boolean
 * 
 */
function check_integer($input)
{
    if (!ctype_digit(strval($input))) {
        
        return false;
        
    } else {
        
        return true;
    }
    
}
