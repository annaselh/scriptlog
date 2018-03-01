<?php

// is integer
function check_integer($input)
{
    if (!ctype_digit(strval($input))) {
        
        return false;
        
    } else {
        
        return true;
    }
    
}
