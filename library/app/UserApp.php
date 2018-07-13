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

  protected $view;
  
  public function __construct(UserEvent $userEvent)
  {
    
    $this->userEvent = $userEvent;
     
  }
  
  public function listItems()
  {
   
    $this->setPageTitle('Users');
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
    $this->view->set('pageTitle', $this->getPageTitle());
    
    if (!$checkError) {
       $this->view->set('errors', $errors);
    }
    
    if ($checkStatus) {
       $this->view->set('status', $status);
    }
    
    $this->view->render();
   
  }
  
  public function login()
  {
      
  }
  
  public function insert()
  {
    $errors = array();
    $checkError = true;
    
    if (isset($_POST['userFormSubmit'])) {
        
        $user_login = filter_input(INPUT_POST, 'user_login', FILTER_SANITIZE_STRING);
        $user_fullname = filter_input(INPUT_POST, 'user_fullname', FILTER_SANITIZE_STRING);
        $user_email = isset($_POST['user_email']) ? filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL) : "";
        $user_pass = isset($_POST['user_pass']) ? trim($_POST['user_pass']) : "";
        $user_url = filter_input(INPUT_POST, 'user_url', FILTER_SANITIZE_URL);
        $user_session = trim($_POST['session_id']);
        $user_role = isset($_POST['user_role']) ? trim($_POST['user_role']) : 0;
        $send_notification = isset($_POST['send_user_notification']) ? $_POST['send_user_notification'] : 0;
        
        try {
           
            if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
                
                $checkError = false;
                array_push($errors, "Sorry, unpleasant attempt detected!");
                
            }
            
            if (empty($user_login) || empty($user_email) || empty($user_pass)) {
            
                $checkError = false;
                array_push($errors, "All Column required must be filled");
                
            } else {
            
                if ($user_role == 0) {
                    
                    $checkError = false;
                    array_push($errors, "Please select user role");
                    
                }
                
                if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $user_login)) {
                    
                    $checkError = false;
                    array_push($errors, "Please enter username, use letters and numbers only at least 6-32 characters");
                    
                }
                
                if (email_validation($user_email) == 0) {
                    
                    $checkError = false;
                    array_push($errors, "Please enter a valid email address");
                    
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
                
                if ($send_notification == 1) {
                
                    $this->userEvent->setUserLogin($user_login);
                    $this->userEvent->setUserEmail($user_email);
                    $this->userEvent->setUserPass($user_pass);
                    $this->userEvent->setUserUrl($user_url);
                    $this->userEvent->setUserSession($user_session);
                    $this->userEvent->setUserLevel($user_level);
                    $this->userEvent->addUser();
                    
                    $site_info = app_info();
                    $app_url = $site_info['app_url'];
                    $site_name = $site_info['site_name'];
                    $activation_key = user_activation_key($user_email);
                    $sender = $site_info['email_address'];
                    
                    $subject = "Join for The Best Team in Town!";
                    $content = "If you never ask to be a user at {$site_name}. 
                                please feel free to ignore this email. 
                                But if you are asking for this information, 
                                here is your profile data:<br />
                                <b>Username:</b>{$user_login}<br />
                                <b>Password:</b>{$user_pass}<br />
                                Activate your account by clicking the link below:<br />
                                <a href={$app_url}".APP_ADMIN."/activate-user.php?key={$activation_key}>Activate My Account</a><br /><br />
                                Thank you, <br />
					            <b>{$site_name}</b>";
                    
                    $notify_newuser = mail_sender($sender, $user_email, $subject, $content);
                    
                    if ($notify_newuser) direct_page('index.php?load=users&status=userAdded', 200);
                    
                } else {
                    
                    $this->userEvent->setUserLogin($user_login);
                    $this->userEvent->setUserEmail($user_email);
                    $this->userEvent->setUserPass($user_pass);
                    $this->userEvent->setUserUrl($user_url);
                    $this->userEvent->setUserSession($user_session);
                    $this->userEvent->setUserLevel($user_level);
                    $this->userEvent->setUserStatus('1');
                    $this->userEvent->addUser();
                    direct_page('index.php?load=users&status=userAdded', 200);
                }
                
                
            }
            
        } catch (AppException $e) {
            
           $this->setView('all-users');
           $this->setPageTitle('Error 400');
           $this->view->set('pageTitle', $this->getPageTitle());
           $this->view->set('saveError', $e->getMessage());
           $this->view->set('formData', $_POST);
           
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
  
  public function update($id)
  {
    
    $getUser = $this->userEvent->grabUser($id);
    $errors = array();
    $checkError = true;
    
    if (false === $getUser) {
        
        direct_page('index.php?load=users&error=userNotFound', 404);
        
    }
    
    $data_user = array(
        
        'ID' => $getUser -> ID,
        
    );
  }
  
  public function delete($id)
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