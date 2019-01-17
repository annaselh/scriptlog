<?php
/**
 * Invoke Post Function
 * 
 * @param integer $args
 * 
 */
function invoke_post($args = null)
{
  global $frontPaginator, $sanitizer;

  $errors = array();

  $postDao = new Post();

  $content =  new ContentGateway($frontPaginator, $sanitizer);
  
  $frontContent = new FrontContent();

  if (is_null($args)) {


    
  } else {

    $detail_post = $frontContent -> readPost($postDao, $args);
    
    if ($detail_post === false) {
      
      $errors[] = 'Post requested not found';

    }

  }

}