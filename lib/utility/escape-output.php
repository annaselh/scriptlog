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
function escape_output($value, $type, $url_encoded = null)
{
 
 $html = array();

 switch ($type) {

     case 'url':

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
     
 } 

 return $html;

}