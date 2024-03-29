<?php 
/**
 * Class CommentApp extends BaseApp
 *
 * @package   SCRIPTLOG/LIB/APP/CommentApp
 * @category  App Class
 * @author    M.Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class CommentApp extends BaseApp
{

  private $view;
  
  private $commentEvent;
  
  public function __construct(CommentEvent $commentEvent)
  {
    $this->commentEvent = $commentEvent;
  }
  
  public function listItems()
  {
      
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
    $this->setPageTitle('Comments');
    $this->view->set('pageTitle', $this->getPageTitle());
    
    if (!$checkError) {
        $this->view->set('errors', $errors);
    }
    
    if ($checkStatus) {
        $this->view->set('status', $status);
    }
    
    $this->view->set('commentsTotal', count($this->commentEvent->grabComments()));
    $this->view->set('comments', $this->commentEvent->grabComments());
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
                
                header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
                throw new AppException("Sorry, unpleasant attempt detected!");
                
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
                
                $this->setView('submit-comment', 'public');
                $this->setPageTitle("Leave Comment");
                $this->setFormAction("leaveComment");
                
               
            } else {
                
                
            }
            
            
        } catch (AppException $e) {
            
            LogError::setStatusCode(http_response_code());
            LogError::newMessage($e);
            LogError::customErrorMessage();
            
        }
        
    }
    
  }
  
  public function update($id)
  {
    $errors = array();
    $checkError = true;
    
    if (!$getComment = $this->commentEvent->grabComment($id)) {
        
        direct_page('index.php?load=comments&error=commentNotFound', 404);
        
    }
    
    $data_comment = array(
        
        'ID' => $getComment['ID'],
        'comment_post_id' => $getComment['comment_post_id'],
        'comment_author_name' => $getComment['comment_author_name'],
        'comment_author_ip' => $getComment['comment_author_ip'],
        'comment_content' => $getComment['comment_content'],
        'comment_status' => $getComment['comment_status'],
        'comment_date' => $getComment['comment_date']
        
    );
    
    if (isset($_POST['commentFormSubmit'])) {
        
        $post_id = isset($_POST['post_id']) ? abs((int)$_POST['post_id']) : 0;
        $author_name = isset($_POST['author_name']) ? trim(htmlspecialchars($_POST['author_name'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8")) : "";
        $author_ip = get_ip_address();
        $comment_content = isset($_POST['comment_content']) ? $_POST['comment_content'] : "";
        $comment_id = isset($_POST['comment_id']) ? abs((int)$_POST['comment_id']) : 0;
        $comment_status = $_POST['comment_status'];
        
        try {
            
            if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
                
                header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
                throw new AppException("Sorry, unpleasant attempt detected!");
                
            }
            
            if (empty($author_name)) {
                
                $checkError = false;
                array_push($errors, "Please enter author name");
                
            }
            
            if (empty($comment_content)) {
                
                $checkError = false;
                array_push($errors, "Please enter comment content");
                
            }
            
            if (!$checkError) {
                
                $this->setView('edit-comment');
                $this->setPageTitle("Edit Comment");
                $this->setFormAction("editComment");
                $this->view->set('pageTitle', $this->getPageTitle());
                $this->view->set('formAction', $this->getFormAction());
                $this->view->set('errors', $errors);
                $this->view->set('commentData', $data_comment);
                $this->view->set('commentStatus', $this->commentEvent->commentStatementDropDown($getComment['comment_status']));
                $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
                
            } else {
              
                $this->commentEvent->setCommentId($comment_id);
                $this->commentEvent->setCommentContent($comment_content);
                $this->commentEvent->setCommentStatus($comment_status);
                $this->commentEvent->modifyComment();
                direct_page('index.php?load=comments&status=commentUpdated', 200);
                
            }
            
        } catch (AppException $e) {
            
            LogError::setStatusCode(http_response_code());
            LogError::newMessage($e);
            LogError::customErrorMessage();
            
        }
        
    } else {
        
        $this->setView('edit-comment');
        $this->setPageTitle("Edit Comment");
        $this->setFormAction("editComment");
        $this->view->set('pageTitle', $this->getPageTitle());
        $this->view->set('formAction', $this->getFormAction());
        $this->view->set('commentData', $data_comment);
        $this->view->set('commentStatus', $this->commentEvent->commentStatementDropDown($getComment['comment_status']));
        $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
        
    }
    
    return $this->view->render();
    
  }
  
  public function remove($id)
  {
     $this->commentEvent->setCommentId($id);
     $this->commentEvent->removeComment();
     direct_page('index.php?load=comments&status=commentDeleted', 200);
  }
  
  protected function setView($viewName, $uiPath = null)
  {
      if (!is_null($uiPath)) {
          
          $this->view = new View('public', $uiPath, $viewName);
      
      } else {
      
          $this->view = new View('admin', 'ui', 'comments', $viewName);
          
      }
      
  }
  
}