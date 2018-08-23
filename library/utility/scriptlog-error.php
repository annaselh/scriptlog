<?php
/**
 * Scriptlog Error Function
 * Generates user-level error/warning/notice message
 * 
 * @param string $message
 * @param string $level
 */
function scriptlog_error($message, $level = E_USER_NOTICE)
{
   $caller = next(debug_backtrace());
   
   trigger_error($message.' in '.$caller['function'].' function called from '.$caller['file'].' on line '.$caller['line'].' '."\n error handler", $level);
   
}