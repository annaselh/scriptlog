<?php
/**
 * FrontContent Class
 * 
 * @package  SCRIPTLOG
 * @author   Maoelana Noermoehammad
 */
class FrontContent
{
  protected $content;

  public function __construct(Content $content)
  {
    $this->content = $content;
  }

  public function getContent()
  {
    return $this->content;
  }

}