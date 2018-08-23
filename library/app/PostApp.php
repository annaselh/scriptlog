<?php 
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
  
  private $view;

  private $postEvent;
    
  public function __construct(PostEvent $postEvent)
  {
    
    $this->postEvent = $postEvent;
   
  }
  
  /**
   * Retrieve all posts
   *  
   * {@inheritDoc}
   * @see BaseApp::listItems()
   */
  public function listItems()
  {
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
    $this->setPageTitle('Posts');
    $this->view->set('pageTitle', $this->getPageTitle());
    
    if (!$checkError) {
        $this->view->set('errors', $errors);
    } 
    
    if ($checkStatus) {
        $this->view->set('status', $status);
    }
    
    $this->view->set('postsTotal', $this->postEvent->totalPosts());
    $this->view->set('posts', $this->postEvent->grabPosts());
    return $this->view->render();
    
  }
  
  /**
   * Insert new post
   * 
   * {@inheritDoc}
   * @see BaseApp::insert()
   */
  public function insert()
  {
    
    $topics = new Topic();
    $errors = array();
    $checkError = true;
    
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
        
         if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
         
             header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
             throw new AppException("Sorry, unpleasant attempt detected!");
             
         }
         
         if (empty($title)) {
           
            $checkError = false;
            array_push($errors, "Please enter title");
            
         }
         
         if (empty($content)) {
             
            $checkError = false;
            array_push($errors, "Please enter content");
             
         }
          
         if (!$checkError) {
            
            $this->setView('edit-post');
            $this->setPageTitle('Add New Post');
            $this->setFormAction('newPost');
            $this->view->set('pageTitle', $this->getPageTitle());
            $this->view->set('formAction', $this->getFormAction());
            $this->view->set('errors', $errors);
            $this->view->set('formData', $_POST);
            $this->view->set('topics', $topics->setCheckBoxTopic());
            $this->view->set('postStatus', $this->postEvent->postStatusDropDown());
            $this->view->set('commentStatus', $this->postEvent->commentStatusDropDown());
            $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
            
         } else {
             
             $this->postEvent->setPostTitle($title);
             $this->postEvent->setPostSlug($slug);
             $this->postEvent->setPostContent($content);
             $this->postEvent->setMetaDesc($meta_desc);
             $this->postEvent->setMetaKeys($meta_keys);
             $this->postEvent->setPublish($post_status);
             $this->postEvent->setComment($comment_status);
             $this->postEvent->addPost();
             direct_page('index.php?load=posts&status=postAdded', 200);
             
         }
            
      } catch (AppException $e) {
          
         LogError::setStatusCode(http_response_code());
         LogError::newMessage($e);
         LogError::customErrorMessage('admin');
         
      }
    
    } else {
        
        $this->setView('edit-post');
        $this->setPageTitle('Add New Post');
        $this->setFormAction('newPost');
        $this->view->set('pageTitle', $this->getPageTitle());
        $this->view->set('formAction', $this->getFormAction());
        $this->view->set('topics', $topics->setCheckBoxTopic());
        $this->view->set('postStatus', $this->postEvent->postStatusDropDown());
        $this->view->set('commentStatus', $this->postEvent->commentStatusDropDown());
        $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
        
    }
   
    return $this->view->render();
   
  }
  
  /**
   * Update post
   * 
   * {@inheritDoc}
   * @see BaseApp::update()
   */
  public function update($id)
  {
  
    $topics = new Topic();
    $errors = array();
    $checkError = true;
    
    if (!$getPost = $this->postEvent->grabPost($id)) {
        
        direct_page('index.php?load=posts&error=postNotFound', 404);
        
    }
    
    $data_post = array(
        'ID' => $getPost['ID'],
        'post_image' => $getPost['post_image'],
        'post_title' => $getPost['post_title'],
        'post_content' => $getPost['post_content'],
        'post_summary' => $getPost['post_summary'],
        'post_keyword' => $getPost['post_keyword']
    );
    
    if (isset($_POST['postFormSubmit'])) {
        
        $title = isset($_POST['post_title']) ? trim($_POST['post_title']) : "";
        $slug = make_slug($title);
        $content = isset($_POST['post_content']) ? trim($_POST['post_content']) : "";
        $category = isset($_POST['catID']) ? $_POST['catID'] : "";
        $meta_desc = isset($_POST['meta_description']) ? trim($_POST['meta_description']) : null;
        $meta_keys = isset($_POST['meta_keywords']) ? trim($_POST['meta_keywords']) : null;
        $post_status = $_POST['post_status'];
        $comment_status = $_POST['comment_status'];
        $post_id = isset($_POST['post_id']) ? abs((int)$_POST['post_id']) : 0;
        
        try {
            
            if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
                
                header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
                throw new AppException("Sorry, unpleasant attempt detected!");
                
            }
            
            if (empty($title)) {
                
               $checkError = false;
               array_push($errors, "Please enter title");
                
            }
            
            if (empty($content)) {
                
              $checkError = false;
              array_push($errors, "Please enter post content");
              
            }
            
            if (!$checkError) {
                
                $this->setView('edit-post');
                $this->setPageTitle('Edit Post');
                $this->setFormAction('editPost');
                $this->view->set('pageTitle', $this->getPageTitle());
                $this->view->set('formAction', $this->getFormAction());
                $this->view->set('errors', $errors);
                $this->view->set('postData', $data_post);
                $this->view->set('topics', $topics->setCheckBoxTopic($getPost['ID']));
                $this->view->set('postStatus', $this->postEvent->postStatusDropDown($getPost['post_status']));
                $this->view->set('commentStatus', $this->postEvent->commentStatusDropDown($getPost['comment_status']));
                $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
                
            } else {
              
                $this->postEvent->setPostId($post_id);
                $this->postEvent->setPostTitle($title);
                $this->postEvent->setPostSlug($slug);
                $this->postEvent->setPostContent($content);
                $this->postEvent->setMetaDesc($meta_desc);
                $this->postEvent->setMetaKeys($meta_keys);
                $this->postEvent->setPublish($post_status);
                $this->postEvent->setComment($comment_status);
                $this->postEvent->modifyPost();
                direct_page('index.php?load=posts&status=postUpdated', 200);
                
            }
            
        } catch (AppException $e) {
   
            LogError::setStatusCode(http_response_code());
            LogError::newMessage($e);
            LogError::customErrorMessage('admin');
            
        }
        
    } else {
   
        $this->setView('edit-post');
        $this->setPageTitle('Edit Post');
        $this->setFormAction('editPost');
        $this->view->set('pageTitle', $this->getPageTitle());
        $this->view->set('formAction', $this->getFormAction());
        $this->view->set('postData', $data_post);
        $this->view->set('topics', $topics->setCheckBoxTopic($getPost['comment_status']));
        $this->view->set('postStatus', $this->postEvent->postStatusDropDown($getPost['post_status']));
        $this->view->set('commentStatus', $this->postEvent->commentStatusDropDown($getPost['comment_status']));
        $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
        
    }
      
    return $this->view->render();
    
  }
  
  /**
   * Delete post
   * 
   * {@inheritDoc}
   * @see BaseApp::delete()
   */
  public function delete($id)
  {
    $this->postEvent->setPostId($id);
    $this->postEvent->removePost();  
    direct_page('index.php?load=posts&status=postDeleted', 200);
  }
    
  protected function setView($viewName)
  {
    $this->view = new View('admin', 'ui', 'posts', $viewName);
  }
  
}