<?php 
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

  private $view;

  private $userEvent;
  
  public function __construct(UserEvent $userEvent)
  {
    $this->userEvent = $userEvent;       
  }
  
  public function listItems()
  {
   
    $errors = array();
    $status = array();
    $checkError = true;
    $checkStatus = false;
     
    if (isset($_GET['error'])) {
        $checkError = false;
        if ($_GET['error'] == 'userNotFound') array_push($errors, "Error: User Not Found!");
    }
    
    if (isset($_GET['status'])) {
        $checkStatus = true;
        if ($_GET['status'] == 'userAdded') array_push($status, "New user added");
        if ($_GET['status'] == 'userUpdated') array_push($status, "User has been updated");
        if ($_GET['status'] == 'userDeleted') array_push($status, "User deleted");
    }
    
    $this->setView('all-users');
    $this->setPageTitle('Users');
    $this->view->set('pageTitle', $this->getPageTitle());
    
    if (!$checkError) {
       
        $this->view->set('errors', $errors);
    
    }
    
    if ($checkStatus) {
       
        $this->view->set('status', $status);
        
    } 
    
    $this->view->set('usersTotal', $this->userEvent->totalUsers());
    $this->view->set('users', $this->userEvent->grabUsers());
    return $this->view->render();
   
  }
  
  /**
   * 
   * {@inheritDoc}
   * @see BaseApp::insert()
   */
  public function insert()
  {
   
    $errors = array();
    $checkError = true;
    
    if (isset($_POST['userFormSubmit'])) {
        
        $user_login = filter_input(INPUT_POST, 'user_login', FILTER_SANITIZE_STRING);
        $user_fullname = filter_input(INPUT_POST, 'user_fullname', FILTER_SANITIZE_STRING);
        $user_email = isset($_POST['user_email']) ? filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL) : "";
        $user_pass = prevent_injection($_POST['user_pass']);
        $user_url = isset($_POST['user_url']) ? filter_var($_POST['user_url'], FILTER_SANITIZE_URL) : "#";
        $user_level = isset($_POST['user_level']) ? trim($_POST['user_level']) : 0;
        $user_session = filter_input(INPUT_POST, 'session_id', FILTER_SANITIZE_STRING);
        $send_notification = isset($_POST['send_user_notification']) ? $_POST['send_user_notification'] : 0;
        
        try {
        
            if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
                
                header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
                throw new AppException("Sorry, unpleasant attempt detected!");
                
            }
            
            if (empty($user_login) || empty($user_email) || empty($user_pass)) {
                
               $checkError = false;
               array_push($errors, "All columns required must be filled");
               
            }
            
            if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $user_login)) {
                
                $checkError = false;
                array_push($errors, "Please enter username, use letters and numbers only at least 6-32 characters");
                
            }elseif ($this->userEvent->checkUserLogin($user_login)) {
                
                $checkError = false;
                array_push($errors, "Username already in use");
                
            }
            
            if (email_validation($user_email) == 0) {
                
                $checkError = false;
                array_push($errors, "Please enter a valid email address");
                
            } elseif ($this->userEvent->isEmailExists($user_email)) {
                
                $checkError = false;
                array_push($errors, "Email already in use");
                
            }
            
            if (strlen($user_pass) < 8) {

                $checkError = false;
                array_push($errors, "The password must consist of least 8 characters");

            } elseif (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,50}$/', $user_pass)) {

                $checkError = false;
                array_push($errors, "The password may contain letter and numbers, at least one number and one letter, any of these characters !@#$%");

            }

            if (!empty($user_url)) {
                
                if (!url_validation($user_url)) {
                    
                    $checkError = false;
                    array_push($errors, "Please enter a valid URL");
                    
                }
                
            }
            
            if (!empty($user_fullname)) {
                
                if (!preg_match('/^[A-Z \'.-]{2,90}$/i', $user_fullname)) {
                    
                    $checkError = false;
                    array_push($errors, "Please enter a valid fullname");
                    
                }
                
            }
            
            if (!$checkError) {
                
                $this->setView('edit-user');
                $this->setPageTitle('Add New User');
                $this->setFormAction('newUser');
                $this->view->set('pageTitle', $this->getPageTitle());
                $this->view->set('formAction', $this->getFormAction());
                $this->view->set('errors', $errors);
                $this->view->set('formData', $_POST);
                $this->view->set('userRole', $this->userEvent->userLevelDropDown());
                $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
               
            } else {
            
                $this->userEvent->setUserLogin($user_login);
                $this->userEvent->setUserEmail($user_email);
                $this->userEvent->setUserPass($user_pass);
                $this->userEvent->setUserLevel($user_level);
                $this->userEvent->setUserFullname($user_fullname);
                $this->userEvent->setUserUrl($user_url);
                $this->userEvent->setUserSession($user_session);
                
                if ($send_notification == 1) {
                    
                    $this->userEvent->setUserActivationKey(user_activation_key($user_login.get_ip_address()));
                      
                    $this->userEvent->addUser();
                    
                    notify_new_user($user_email, $user_email, $user_pass);
                    
                } else {
                
                    $this->userEvent->addUser();
                    
                }
                
                direct_page('index.php?load=users&status=userAdded', 200);
                
            }
            
        } catch (AppException $e) {
            
            LogError::setStatusCode(http_response_code());
            LogError::newMessage($e);
            LogError::customErrorMessage('admin');
            
        }
        
    } else {
        
        $this->setView('edit-user');
        $this->setPageTitle('Add New User');
        $this->setFormAction('newUser');
        $this->view->set('pageTitle', $this->getPageTitle());
        $this->view->set('formAction', $this->getFormAction());
        $this->view->set('userRole', $this->userEvent->userLevelDropDown());
        $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
        
    }
    
    return $this->view->render();
          
  }
  
  /**
   * 
   * {@inheritDoc}
   * @see BaseApp::update()
   */
  public function update($id)
  {
    
    $errors = array();
    $checkError = true;
    
    if (!$getUser = $this->userEvent->grabUser($id)) {
        
       direct_page('index.php?load=users&error=userNotFound', 404);
        
    }
    
    $data_user = array(
        
        'ID' => $getUser['ID'],
        'user_login' => $getUser['user_login'],
        'user_email' => $getUser['user_email'],
        'user_level' => $getUser['user_level'],
        'user_fullname' => $getUser['user_fullname'],
        'user_url' => $getUser['user_url'],
        'user_session' => $getUser['user_session']
        
    );
    
    if (isset($_POST['userFormSubmit'])) {
        
        $user_login = filter_input(INPUT_POST, 'user_login', FILTER_SANITIZE_STRING);
        $user_fullname = filter_input(INPUT_POST, 'user_fullname', FILTER_SANITIZE_STRING);
        $user_email = isset($_POST['user_email']) ? filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL) : "";
        $user_pass = isset($_POST['user_pass']) ? trim($_POST['user_pass']) : "";
        $user_url = filter_input(INPUT_POST, 'user_url', FILTER_SANITIZE_URL);
        $user_session = trim($_POST['session_id']);
        $user_level = isset($_POST['user_level']) ? trim($_POST['user_level']) : 0;
        $user_id = isset($_POST['user_id']) ? abs((int)$_POST['user_id']) : 0;
        
      try {
      
          if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
              
              header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
              throw new AppException("Sorry, unpleasant attempt detected!");
              
          }

          if (!empty($user_pass)) {

            if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,50}$/', $user_pass)) {
               
                $checkError = false;
                array_push($errors, "The Password may contain letter and numbers, at least one number and one letter, any of these characters !@#$%");

            }
            
          }

          if (!empty($user_url)) {
                
            if (!url_validation($user_url)) {
                
                $checkError = false;
                array_push($errors, "Please enter a valid URL");
                
            }
            
         }
        
         if (!empty($user_fullname)) {
            
            if (!preg_match('/^[A-Z \'.-]{2,90}$/i', $user_fullname)) {
                
                $checkError = false;
                array_push($errors, "Please enter a valid fullname");
                
            }
            
         }

         if ($user_id == 1) {

            if ($user_level !== 'administrator') {

                $checkError = false;
                array_push($errors, "Sorry, administrator is your default privilege");

            }
            
         }
 
         if (!$checkError) {
            
              $this->setView('edit-user');
              $this->setPageTitle('Edit User');
              $this->setFormAction('editUser');
              $this->view->set('pageTitle', $this->getPageTitle());
              $this->view->set('formAction', $this->getFormAction());
              $this->view->set('errors', $errors);
              $this->view->set('userData', $data_user);
              $this->view->set('userRole', $this->userEvent->userLevelDropDown($getUser['user_level']));
              $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
              
          } else {
              
              $this->userEvent->setUserEmail($user_email);
              $this->userEvent->setUserFullname($user_fullname);
              $this->userEvent->setUserUrl($user_url);
              $this->userEvent->setUserId($user_id);
              
              if ($this->userEvent->accessLevel() != 'administrator') {
              
                  if (!empty($user_pass)) {
                      
                      $this->userEvent->setUserPass($user_pass);

                  }
                  
                  $this->userEvent->modifyUser();
                  
              } else {
              
                  $this->userEvent->setUserLevel($user_level);
                  
                  if (!empty($user_pass)) {
                      
                    $this->userEvent->setUserPass($user_pass);
                      
                  }
                  
                  $this->userEvent->modifyUser();
                  
              }
              
              direct_page('index.php?load=users&status=userAdded', 200);
                      
          }
          
      } catch (AppException $e) {
          
          LogError::setStatusCode(http_response_code());
          LogError::newMessage($e);
          LogError::customErrorMessage('admin');
          
      }
      
    } else {
    
        $this->setView('edit-user');
        $this->setPageTitle('Edit User');
        $this->setFormAction('editUser');
        $this->view->set('pageTitle', $this->getPageTitle());
        $this->view->set('formAction', $this->getFormAction());
        $this->view->set('userData', $data_user);
        $this->view->set('userRole', $this->userEvent->userLevelDropDown($getUser['user_level']));
        $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
        
    }
    
   return $this->view->render();
    
  }
  
  /**
   * 
   * {@inheritDoc}
   * @see BaseApp::delete()
   */
  public function remove($id)
  {
    $this->userEvent->setUserId($id);
    $this->userEvent->removeUser();
    direct_page('index.php?load=users&status=userDeleted', 200);
  }
  
  protected function setView($viewName)
  {
     $this->view = new View('admin', 'ui', 'users', $viewName);
  }
  
}