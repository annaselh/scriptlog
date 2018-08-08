<?php

class MenuEvent
{
  private $menu_id;
  
  private $label;
  
  private $link;
  
  private $sort;
  
  private $status;
  
  public function __construct(Menu $menuDao, FormValidator $validator, Sanitize $sanitizer)
  {
      
  }
}