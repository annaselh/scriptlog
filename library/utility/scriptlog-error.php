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
   
   trigger_error($message.' in <strong>'.$caller['function'].'</strong> called from <strong>'.$caller['file'].'</strong> on line <strong>'.$caller['line'].'</strong>'."\n<br />error handler", $level);
   
}