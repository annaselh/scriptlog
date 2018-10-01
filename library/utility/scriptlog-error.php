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

function scriptlog_shutdown_fatal()
{
 
  $date_error = date("Y-m-d H:i:s (T)");

  $errorType = [
    E_ERROR              => 'Error',
    E_WARNING            => 'Warning',
    E_PARSE              => 'Parsing Error',
    E_NOTICE             => 'Notice',
    E_CORE_ERROR         => 'Core Error',
    E_CORE_WARNING       => 'Core Warning',
    E_COMPILE_ERROR      => 'Compile Error',
    E_COMPILE_WARNING    => 'Compile Warning',
    E_USER_ERROR         => 'User Error',
    E_USER_WARNING       => 'User Warning',
    E_USER_NOTICE        => 'User Notice',
    E_STRICT             => 'Runtime Notice',
    E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
  ];

  $scriptlogError = [E_ERROR, E_USER_ERROR, E_COMPILE_ERROR, E_CORE_ERROR];

  $lastError =  error_get_last();

  $errorMsg = null;
  
  if ($lastError !== NULL) {
  
    $trace = print_r(debug_backtrace(false), true);

    $errorMsg = "<errorentry>\n";
    $errorMsg .= "\t<datetime>".$date_error."</datetime>\n";
    $errorMsg .= "\t<errornum>".$lastError['type']."</errornum>\n";
    $errorMsg .= "\t<errortype>".$errorType[$lastError['type']]."</errortype>\n";
    $errorMsg .= "\t<errormsg>".$lastError['message']."</errormsg>\n";
    $errorMsg .= "\t<scriptname>".$lastError['file']."</scriptname>\n";
    $errorMsg .= "\t<scriptlinenum>".$lastError['line']."</scriptlinenum>\n";

    if (in_array($lastError['type'], $scriptlogError, true)) {
        $errorMsg .= "<\t><vartrace>".wddx_serialize_value($trace, "Variables")."</vartrace>\n";
    }

    $errorMsg .= "</errorentry>\n\n";

    if (is_writable(APP_ROOT.APP_PUBLIC.'/log/error.log')) {
      error_log($errorMsg, 3, APP_ROOT.APP_PUBLIC.'/log/error.log');
    }
    
    if ($lastError['type'] === E_USER_ERROR) {
        mail("scriptlog@yandex.com", "Critical User Error", $errorMsg);
    }

  }

}

