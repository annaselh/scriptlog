<?php

class MenuApp extends BaseApp
{
  protected $view;
  
  public function __construct(MenuEvent $menuEvent)
  {
      $this->menuEvent = $menuEvent;
  }
  
  public function listItems()
  {
      
  }
  
  public function insert()
  {
      
  }
  
  public function update($id)
  {
      
  }
  
  public function delete($id)
  {
      
  }
  
  protected function setView($viewName)
  {
    
  }
  
}