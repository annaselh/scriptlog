<?php
/**
 * interface Content
 * 
 * @package  SCRIPTLOG
 * @author   Maoelana Noermoehammad
 * 
 */
interface Content
{
  public function grabItems($items, $paginator, $sanitizer);

  public function grabItem($item, $vars, $sanitizer = null);

}