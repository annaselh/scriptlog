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
  
  public function __construct(UserEvent $userEvent, Authentication $validator)
  {
      $this->userEvent = $userEvent;
      $this->validator = $validator;
      
  }
  
  public function listItems()
  {
   
    $this->setPageTitle('Users');
    $this->setView('all-users');
    $this->view->set('pageTitle', $this->getPageTitle());
    $this->view->render();
   
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
  
  protected function setView($viewName)
  {
     $this->view = new View('admin', 'ui', 'users', $viewName);
  }
}