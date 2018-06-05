<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * PostApp Class extends BaseApp Class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class PostApp extends BaseApp
{
  
  protected $view;
  
  public function __construct(PostService $postService, FormValidator $validator)
  {
    $this->postService = $postService;
    $this->validator = $validator;
  }
  
  public function listItems()
  {
    $this->setPageTitle('Posts');
    $this->setView('all-posts');
    $this->view->set('pageTitle', $this->getPageTitle());
    return $this->view->render();
  }
  
  public function insert()
  {
    $this->setPageTitle('Add New Post');
    $this->setFormAction('newPost');
    $errors = array();
    $check = true;
    $topics = new Topic();
    
    if (!isset($_POST['postFormSubmit'])) {
       
       $this->setView('edit-post');
       $this->view->set('pageTitle', $this->getPageTitle());
       $this->view->set('topics', $topics->setTopic());
       $this->view->set('postStatus', $this->postService->setPostStatus());
       $this->view->set('commentStatus', $this->postService->setCommentStatus());
    }
     
    if (empty($_POST['post_title'])) {
        
        $check = false;
        array_push($errors, "Title is required");
        
    }
      
    if (empty($_POST['content'])) {
          
        $check = false;
        array_push($errors, 'Content is required');
          
    }
      
    if (!$check) {
         
       $this->setView('edit-post');
       $this->view->set('pageTitle', $this->getPageTitle());
       $this->view->set('formAction', $this->getFormAction());
       $this->view->set('topics', $topics -> setTopic());
       $this->view->set('postStatus', $this->postService->setPostStatus());
       $this->view->set('commentStatus', $this->postService->setCommentStatus());
       $this->view->set('errors', $errors);
       $this->view->set('formData', $_POST);
       return $this->view->render();
          
    }
      
    try {
      
        $success = $this->postService->addPost($_POST);
        
        if ($success) {
            
            $this->setView('all-post');
            $this->view->set('pageTitle', $this->getPageTitle());
            $this->view->set('statusMessage', 'New post added');
            
        }
        
    } catch (AppException $e) {
        
        $this->setView('all-posts');
        $this->view->set('pageTitle', $this->getPageTitle());
        $this->view->set('formData', $_POST);
        $this->view->set('logError', $e->getMessage());
        
    }
    
    return $this->view->render();
    
  }
  
  public function update()
  {
      $this->setPageTitle('Edit Post');
      $this->setFormAction('editPost');
      $errors = array();
      $check = true;
      $topics = new Topic();
      
      
  }
  
  public function delete()
  {
      
  }
    
  protected function setView($viewName)
  {
     $this->view = new View('admin', 'ui', 'posts', $viewName);
  }
  
}