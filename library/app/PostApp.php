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
    
  public function __construct(PostEvent $postEvent, FormValidator $validator)
  {
    $this->postEvent = $postEvent;
    $this->validator = $validator;
  }
  
  public function listItems()
  {
    $this->setPageTitle('Posts');
    $errors = array();
    $status = array();
    $checkError = true;
    $checkStatus = false;
    
    if (isset($_GET['error'])) {
        $checkError = false;
        if ($_GET['error'] == 'postNotFound') array_push($errors, "Error: Post Not Found!");
    }
    
    if (isset($_GET['status'])) {
        $checkStatus = true;
        if ($_GET['status'] == 'postAdded') array_push($status, "New post added");
        if ($_GET['status'] == 'postUpdated') array_push($status, "Post has been updated");
        if ($_GET['status'] == 'postDeleted') array_push($status, "Post deleted");
    }
    
    $this->setView('all-posts');
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
    
    $topics = new Topic();
    
    if (isset($_POST['postFormSubmit'])) {
        
       $title = isset($_POST['post_title']) ? trim($_POST['post_title']) : "";
       $slug = make_slug($title);
       $content = isset($_POST['post_content']) ? trim($_POST['post_content']) : "";
       $category = isset($_POST['catID']) ? $_POST['catID'] : "";
       $meta_desc = isset($_POST['meta_description']) ? trim($_POST['meta_description']) : null;
       $meta_keys = isset($_POST['meta_keywords']) ? trim($_POST['meta_keywords']) : null;
       $post_status = $_POST['post_status'];
       $comment_status = $_POST['comment_status'];
       
      try {
        
         if (empty($_POST['post_title'])) {
           
            throw new AppException("Please enter title");
            
         }
         
         if (empty($_POST['post_content'])) {
             
            throw new AppException("Please enter content");
             
         }
         
        $this->postEvent->setPostTitle($title);
        $this->postEvent->setPostSlug($slug);
        $this->postEvent->setPostContent($content);
        $this->postEvent->setMetaDesc($meta_desc);
        $this->postEvent->setMetaKeys($meta_keys);
        $this->postEvent->setPublish($post_status);
        $this->postEvent->setComment($comment_status);
        $this->postEvent->addPost();
        
        direct_page('index.php?load=posts&status=postAdded');
        
      } catch (AppException $e) {
          
         $this->setView('edit-post');
         $this->setPageTitle('Add New Post');
         $this->setFormAction('newPost');
         $this->view->set('pageTitle', $this->getPageTitle());
         $this->view->set('formAction', $this->getFormAction());
         $this->view->set('errors', $e->getMessage());
         $this->view->set('formData', $_POST);
         $this->view->set('topics', $topics->setTopic());
         $this->view->set('formAction', $this->getFormAction());
         $this->view->set('postStatus', $this->postEvent->postStatusDropDown());
         $this->view->set('commentStatus', $this->postEvent->commentStatusDropDown());
          
      }
    
    } else {
        
        $this->setView('edit-post');
        $this->setPageTitle('Add New Post');
        $this->setFormAction('newPost');
        $this->view->set('pageTitle', $this->getPageTitle());
        $this->view->set('formAction', $this->getFormAction());
        $this->view->set('topics', $topics->setTopic());
        $this->view->set('formAction', $this->getFormAction());
        $this->view->set('postStatus', $this->postEvent->postStatusDropDown());
        $this->view->set('commentStatus', $this->postEvent->commentStatusDropDown());
    }
   
    return $this->view->render();
   
  }
  
  public function update()
  {
  
    $topics = new Topic();
    
    if (isset($_POST['postFormSubmit'])) {
        
        $title = isset($_POST['post_title']) ? trim($_POST['post_title']) : "";
        $slug = make_slug($title);
        $content = isset($_POST['post_content']) ? trim($_POST['post_content']) : "";
        $category = isset($_POST['catID']) ? $_POST['catID'] : "";
        $meta_desc = isset($_POST['meta_description']) ? trim($_POST['meta_description']) : null;
        $meta_keys = isset($_POST['meta_keywords']) ? trim($_POST['meta_keywords']) : null;
        $post_status = $_POST['post_status'];
        $comment_status = $_POST['comment_status'];
        $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
        
        try {
            
        } catch (AppException $e) {
        }
        
    } else {
        
        $this->setView('edit-post');
        $this->setPageTitle('Edit Post');
        $this->setFormAction('editPost');
        $this->view->set('pageTitle', $this->getPageTitle());
        $this->view->set('formAction', $this->getFormAction());
        $this->view->set('topics', $topics->setTopic());
        $this->view->set('formAction', $this->getFormAction());
    }
      
    return $this->view->render();
    
  }
  
  public function delete()
  {
      
  }
    
  protected function setView($viewName)
  {
     $this->view = new View('admin', 'ui', 'posts', $viewName);
  }
  
}