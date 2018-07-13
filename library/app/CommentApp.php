<?php 
class CommentApp extends BaseApp
{

  protected $view;
  
  public function __construct(CommentEvent $commentEvent)
  {
    $this->commentEvent = $commentEvent;
  }
  
  public function listItems()
  {
    $this->setPageTitle('Comments');
    $errors = array();
    $status = array();
    $checkError = true;
    $checkStatus = false;
    
    if (isset($_GET['error'])) {
        $checkError = false;
        if ($_GET['error'] == 'commentNotFound') array_push($errors, "Error: Comment Not Found!"); 
    }
    
    if (isset($_GET['status'])) {
        $checkStatus = true;
        if ($_GET['status'] == 'commentAdded') array_push($status, "New comment added");
        if ($_GET['status'] == 'commentUpdated') array_push($status, "Comment has been updated");
        if ($_GET['status'] == 'commentDeleted') array_push($status, "Comment deleted");
    }
    
    $this->setView('all-comments');
    $this->view->set('pageTitle', $this->getPageTitle());
    
    if (!$checkError) {
        $this->view->set('errors', $errors);
    }
    
    if ($checkStatus) {
        $this->view->set('status', $status);
    }
    
    return $this->view->render();
    
  }
  
  public function insert()
  {
    $errors = array();
    $checkError = true;
    
    if (isset($_POST['commentFormSubmit'])) {
        
        $post_id = isset($_POST['post_id']) ? abs((int)$_POST['post_id']) : 0;
        $author_name = isset($_POST['author_name']) ? trim(htmlspecialchars($_POST['author_name'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8")) : "";
        $author_ip = get_ip_address();
        $comment_content = isset($_POST['comment_content']) ? $_POST['comment_content'] : "";
        
        try {
            
            if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
                
                $checkError = false;
                array_push($errors, "Sorry, unpleasant attempt detected!");
                
            }
            
            if (empty($author_name)) {
                
                $checkError = false;
                array_push($errors, "Please enter your name");
                
            }
            
            if (empty($comment_content)) {
                
                $checkError = false;
                array_push($errors, "Please enter content");
                
            }
            
            if (!$checkError) {
                
               
            } else {
                
            }
            
            
        } catch (AppException $e) {
            
        }
        
    }
    
  }
  
  public function update($id)
  {
      
  }
  
  public function delete($id)
  {
      
  }
  
  protected function setView($viewName)
  {
    $this->view = new View('admin', 'ui', 'comments', $viewName);
  }
}