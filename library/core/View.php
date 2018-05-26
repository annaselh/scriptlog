<?php

class View
{
  protected $file;
  
  protected $data = [];
  
  public function __construct($file)
  {
    $this->file = $file;
  }
  
  public function set($key, $value)
  {
    $this->data[$key] = $value;
  }
  
  public function get($key)
  {
    return $this->data[$key];
  }
  
  public function render()
  {
      
  }
}