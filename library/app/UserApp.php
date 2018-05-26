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
class UserApp
{

  public function __construct(User $userDao, ValidatorService $validator)
  {
      $this->userDao = $userDao;
      $this->validator = $validator;
      
      if (isset($_POST['login'])) {
          $this->login();
      
      } elseif (isset($_POST['register'])) {
          
          $this->register();
          
      } elseif (isset($_POST['update'])) {
          
          $this->update();
          
      } elseif (isset($_POST['delete'])) {
              
          $this->delete();
          
      } elseif (isset($_GET['logout'])) {
          
          $this->logout();
          
      }
      
  }
  
  public function getAllUsers()
  {
     
    require(APP_ROOT.APP_ADMIN.DS.'ui'.DS.'users'.DS.'all-users.php');
    
  }
  
  public function login()
  {
      
  }
  
  public function register()
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