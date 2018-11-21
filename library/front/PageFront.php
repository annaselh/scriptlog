<?php
/**
 * 
 */
class PageFront implements Content
{
  public function grabItems($items, $paginator, $sanitizer)
  {

  }

  public function grabItem($item, $vars)
  {
    return $item -> findPageBySlug($vars);
  }
}