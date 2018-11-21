<?php
/**
 * PostFront Class implements Content
 * 
 * @package  SCRIPTLOG
 * @author   Maoelana Noermoehammad
 * 
 */
class PostFront implements Content
{
  
  public function grabItems($items, $paginator, $sanitizer)
  {
    return $items -> showPostsPublished($paginator, $sanitizer);
  }
  
  public function grabItem($item, $vars, $sanitizer)
  {
     if (is_numeric($vars)) {

       return $item -> showPostById($vars, $sanitizer);
       
     }

  }
  
}