<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

class PostApp extends BaseApp
{
  
  protected $view;
  
  public function __construct(Post $postDao, ValidatorService $validator)
  {
    $this->postDao = $postDao;
    $this->validator = $validator;
    
  }
   
  public function listItems()
  {
    $this->setPageTitle('Posts');
    $posts = $this->postDao->findPosts('0', '10');
    $this->view = new View('admin', 'ui', 'posts', 'all-posts');
    $this->view->setData('posts', $posts);
    $this->view->setData('pageTitle', $this->getPageTitle());
    return $this->view->render();
  }
  
  public function insert()
  {
      if (!isset($_POST['postFormSubmit'])) {
          
        header("Location: ".APP_PROTOCOL."://".APP_HOSTNAME.dirname(dirname($_SERVER['PHP_SELF'])).'admin/index.php?load=posts&action='.$this->getFormAction().'&postId=0');
          
      }
      
      $file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
      $file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
      $file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
      $file_size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
      $file_error = isset($_FILES['image']['error']) ? $_FILES['image']['error'] : '';
      
      $tgl_sekarang = date("Ymd");
      $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
      $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $slug = make_slug($title);
      $meta_description = filter_input(INPUT_POST, 'meta_desc', FILTER_SANITIZE_SPECIAL_CHARS);
      $meta_keywords = filter_input(INPUT_POST, 'meta_key', FILTER_SANITIZE_SPECIAL_CHARS);
      $post_status = isset($_POST['post_status']) ? $_POST['post_status'] : "";
      $comment_status = isset($_POST['comment_status']) ? $_POST['comment_status'] : "";
      
      $errors = array();
      $check = true;
      
      $this->setPageTitle('Add New Post');
      $this->setFormAction('newPost');
     
      
    try {
          
    } catch (ViewException $e) {
        
    }
    
    
  }
  
  public function update()
  {
      
  }
  
  public function delete()
  {
      
  }
    
}