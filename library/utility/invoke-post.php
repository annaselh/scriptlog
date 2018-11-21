<?php
/**
 * Invoke Post Function
 * 
 * @param mixed $args
 */
function invoke_post($args = null)
{
  global $frontPaginator, $sanitizer;

  $postDao = new Post();
  $articleContent = new PostFront();
  $frontContent = new FrontContent($articleContent);

  if (!empty($args)) {

    return $frontContent->getContent->grabItem($postDao, $args);
    
  } else {

    return $frontContent->getContent->grabItems($postDao, $frontPaginator, $sanitizer);

  }

}