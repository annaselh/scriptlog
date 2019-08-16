<?php
/**
 * Escape output function
 * 
 * @category Function
 * @package  SCRIPTLOG/LIB/UTILITY
 * @param string $value
 * @param string $type
 * 
 */
function escape_output($base, $query_data, $type, $string_encoded = array())
{
 
 $html = array();

 switch ($type) {

   case 'href':

      if (!empty($string_encoded)) {

         $load = (is_array($string_encoded) && array_key_exists(0, $string_encoded)) ? rawurlencode($string_encoded[0]) : '';
         $action = (is_array($string_encoded) && array_key_exists(1, $string_encoded)) ? rawurlencode($string_encoded[1]) : '';
         $id = (is_array($string_encoded) && array_key_exists(2, $string_encoded)) ? rawurlencode($string_encoded[2]) : '';
         $user_session = (is_array($string_encoded) && array_key_exists(3, $string_encoded)) ? rawurlencode($string_encoded[3]) : '';
 
         if ($load === 'users') {

            $query_data = array(
              
              'load' => $load,
              'action'=> $action,
              'userId'=> $id,
              'sessionId' => $user_session

            );

         } else {

            $query_data = array(
              
              'load' => $load,
              'action'=> $action,
              'Id'=> $id,
         
           );

         }

         $html['link'] = build_query($base, $query_data);

      } else {

          
      } 

      break;

   default:

      break;

      /*
       if (is_null($url_encoded)) {

          $url = array(
            'value' => $value,
          );
    
          $link = "index.php?load={$url['value']}";

          $html['link'] = transform_html($link);
    
       } else {
    
          $url = array(
    
            'value' => urlencode($url_encoded)
            
          );
    
          $link = APP_PROTOCOL . "://" . APP_HOSTNAME . dirname(dirname($_SERVER['PHP_SELF'])) . DS . $value . "{$url['value']}";
    
          $html['link'] = htmlentities($link, ENT_QUOTES, 'UTF-8');
    
       }
    
       break;
       */
     
 }

 return $html;
   
}