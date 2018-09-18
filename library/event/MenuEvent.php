<?php

class MenuEvent
{
  private $menu_id;
  
  private $label;
  
  private $link;
  
  private $sort;
  
  private $status;

  private $menuDao;

  private $validator;

  private $sanitize;
  
  public function __construct(Menu $menuDao, FormValidator $validator, Sanitize $sanitizer)
  {
    $this->menuDao = $menuDao;
    $this->validator = $validator;
    $this->sanitize = $sanitize;
  }

  public function setMenuId($menu_id)
  {
    $this->menu_id = $menu_id;
  }

  public function setMenuLabel($menu_label)
  {
    $this->label = $menu_label;
  }

  public function setMenuLink($menu_link)
  {
    $this->link = $menu_link;
  }

  public function setMenuSort($menu_sort)
  {
    $this->sort = $menu_sort;
  }

  public function grabMenus($orderBy = "ID")
  {
    return $this->menuDao->findMenus($orderBy);
  }

  public function grabMenu($id)
  {
    return $this->menuDao->findMenu($id);
  }

  public function addMenu()
  {
    $this->validator->sanitize($this->label, 'string');

    
  }

  public function modifyMenu()
  {

  }

  public function removeMenu()
  {

  }
  
  public function totalMenus($data = null)
  {
    return $this->menuDao->totalMenus($data);
  }

}