<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

class PostApp extends BaseApp
{
  
  public function __construct(Post $postDao, ValidatorService $validator)
  {
    $this->postDao = $postDao;
    $this->validator = $validator;
  }
  
  public function listItems()
  {
      
    
  }
  
  public function insert()
  {
      
  }
  
  public function update()
  {
      
  }
  
  public function delete()
  {
      
  }
}