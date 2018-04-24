<?php

// check detail request
function request($action, $param)
{
    global $dispatching;
    
    return $dispatching->URLDispatcher($action, $param);
    
}