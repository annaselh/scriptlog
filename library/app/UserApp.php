<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * UserApp class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class UserApp extends BaseApp
{

  protected $view;
  
  public function __construct(User $userDao, ValidatorService $validator)
  {
      $this->userDao = $userDao;
      $this->validator = $validator;
      
  }
  
  public function listItems()
  {
   
    $this->setPageTitle('Users');
    $this->view = new View('admin','ui', 'users');
    $this->view->render($this->getPageTitle(), 'all-users');
   
  }
  
  public function login()
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
  
  public function logout()
  {
      
  }
  
  
}