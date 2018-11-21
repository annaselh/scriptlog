<?php
/**
 * Theme Meta Function 
 * Display meta tag, title tag based on client request
 *  
 */
function theme_meta()
{
  $findParam = find_request();

  $match = (is_array($findParam) && array_key_exists(0, $findParam)) ? $findParam[0] : '';
  $param1 = (is_array($findParam) && array_key_exists(1, $findParam)) ? $findParam[1] : '';
  $param2 = (is_array($findParam) && array_key_exists(2, $findParam)) ? $findParam[0] : '';

  switch ($match) {

      case 'single':
      
           echo title_tag($match, $param1);

          break;

      case 'blog':

          
          break;
      
      case 'category':
          
          
          break;

  }
  
}

function title_tag($match, $param)
{

  $errors = false;
  $html = '';

  if (($match == 'single') && (!empty($param))) {
   
    $detail_post = invoke_post($param);
    $html = '<title>'.$detail_post['post_title'].'</title>';

  } elseif (($match == 'category') && (!empty($param))) {

     
  }

  return $html;

}

function meta_tag()
{

}