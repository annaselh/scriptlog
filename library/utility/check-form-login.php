<?php
/**
 * Check Form Login Function
 * 
 * Checking error and $_POST
 * 
 * @package SCRIPTLOG
 * @param Object $authenticator
 * @param String $type
 * @param String $field
 * 
 */
function check_form_login(Authentication $authenticator, $type, $field)
{

  if (is_object($authenticator)) {

    switch ($type) {
      
      case 'value':
          # get value
          $checkForm = $authenticator -> getValue($field);
          break;
        
      case 'error':
          # get error
          $checkForm = $authenticator -> getError($field);
          break;
  
    }

    return $checkForm;

  }

}