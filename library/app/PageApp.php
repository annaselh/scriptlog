<?php 
/**
 * PageApp Class extends BaseApp Class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class PageApp extends BaseApp
{
    
 protected $view;
 
 public function __construct(PageEvent $pageEvent)
 {
   $this->pageEvent = $pageEvent;
 }
 
 public function listItems()
 {
   $this->setPageTitle('Pages');
   $errors = array();
   $status = array();
   $checkError = true;
   $checkStatus = false;
   
   if (isset($_GET['error'])) {
       
       $checkError = false;
       if ($_GET['error'] == 'pageNotFound') array_push($errors, "Error: Page Not Found");
       
   }
   
   if (isset($_GET['status'])) {
       $checkStatus = true;
       if ($_GET['status'] == 'pageAdded') array_push($status, "New page added");
       if ($_GET['status'] == 'pageUpdated') array_push($status, "Page has been updated");
       if ($_GET['status'] == 'pageDeleted') array_push($status, "Page deleted");
   }
   
   $this->setView('all-pages');
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
  
  if (isset($_POST['pageFormSubmit'])) {
      
      $title = isset($_POST['post_title']) ? trim($_POST['post_title']) : "";
      $slug = make_slug($title);
      $content = isset($_POST['post_content']) ? trim($_POST['post_content']) : "";
      $meta_desc = isset($_POST['meta_description']) ? trim($_POST['meta_description']) : null;
      $meta_keys = isset($_POST['meta_keywords']) ? trim($_POST['meta_keywords']) : null;
      $post_status = $_POST['post_status'];
      $post_type = "page";
      $comment_status = $_POST['comment_status'];
      
      try {
          
          if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
              
              $checkError = false;
              array_push($errors, "Sorry, unpleasant attempt detected!");
              
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
              
              $this->setView('edit-page');
              $this->setPageTitle('Add New Page');
              $this->setFormAction('newPage');
              $this->view->set('pageTitle', $this->getPageTitle());
              $this->view->set('formAction', $this->getFormAction());
              $this->view->set('errors', $errors);
              $this->view->set('formData', $_POST);
              $this->view->set('postStatus', $this->pageEvent->postStatusDropDown());
              $this->view->set('commentStatus', $this->pageEvent->commentStatusDropDown());
              $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
              
          } else {
              
              $this->pageEvent->setPageTitle($title);
              $this->pageEvent->setPageSlug($slug);
              $this->pageEvent->setPageContent($content);
              $this->pageEvent->setMetaDesc($meta_desc);
              $this->pageEvent->setMetaKeys($meta_keys);
              $this->pageEvent->setPublish($post_status);
              $this->pageEvent->setComment($comment_status);
              $this->pageEvent->addPage();
              direct_page('index.php?load=pages&status=pageAdded', 200);
          }
          
      } catch (AppException $e) {
          
          $this->setView('all-posts');
          $this->setPageTitle('Error 400');
          $this->view->set('pageTitle', $this->getPageTitle());
          $this->view->set('saveError', $e->getMessage());
          $this->view->set('formData', $_POST);
          
      }
      
  } else {
      
      $this->setView('edit-page');
      $this->setPageTitle('Add New Page');
      $this->setFormAction('newPage');
      $this->view->set('pageTitle', $this->getPageTitle());
      $this->view->set('formAction', $this->getFormAction());
      $this->view->set('postStatus', $this->pageEvent->postStatusDropDown());
      $this->view->set('commentStatus', $this->pageEvent->commentStatusDropDown());
      $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
      
  }
  
  return $this->view->render();
  
 }
 
 public function update($id)
 {
   $getPage = $this->pageEvent->grabPage($id, 'page');
   $errors = array();
   $checkError = true;
   
   if (false == $getPage) {
       direct_page('index.php?load=pages&error=pageNotFound', 404);
   }
   
   $data_page = array(
       'ID' => $getPage->ID,
       'post_image' => $getPage -> post_image,
       'post_title' => $getPage->post_title,
       'post_content' => $getPost->post_content,
       'post_summary' => $getPost->post_summary,
       'post_keyword' => $getPost->post_keyword
   );
   
   if (isset($_POST['pageFormSubmit'])) {
       
       $title = isset($_POST['post_title']) ? trim($_POST['post_title']) : "";
       $slug = make_slug($title);
       $content = isset($_POST['post_content']) ? trim($_POST['post_content']) : "";
       $meta_desc = isset($_POST['meta_description']) ? trim($_POST['meta_description']) : null;
       $meta_keys = isset($_POST['meta_keywords']) ? trim($_POST['meta_keywords']) : null;
       $post_status = $_POST['post_status'];
       $post_type = "page";
       $comment_status = $_POST['comment_status'];
       $page_id = isset($_POST['page_id']) ? (int)$_POST['page_id'] : 0;
       
       try {
           
           if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
               
               $checkError = false;
               array_push($errors, "Sorry, unpleasant attempt detected");
               
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
               
               $this->setView('edit-page');
               $this->setPageTitle('Edit Page');
               $this->setFormAction('editPage');
               $this->view->set('pageTitle', $this->getPageTitle());
               $this->view->set('formAction', $this->getFormAction());
               $this->view->set('errors', $errors);
               $this->view->set('formData', $data_page);
               $this->view->set('postStatus', $this->pageEvent->postStatusDropDown($getPage->post_status));
               $this->view->set('commentStatus', $this->pageEvent->commentStatusDropDown($getPage->comment_status));
               $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
               
           } else {
               
               $this->pageEvent->setPageId($page_id);
               $this->pageEvent->setPageTitle($title);
               $this->pageEvent->setPageSlug($slug);
               $this->pageEvent->setPageContent($content);
               $this->pageEvent->setMetaDesc($meta_desc);
               $this->pageEvent->setMetaKeys($meta_keys);
               $this->pageEvent->setPublish($post_status);
               $this->pageEvent->setComment($comment_status);
               $this->pageEvent->modifyPage();
               direct_page('index.php?load=pages&status=pageUpdated', 200);
               
           }
           
       } catch (AppException $e) {
           
           http_response_code(400);
           $this->setView('edit-page');
           $this->setPageTitle('Error 400 Bad Request');
           $this->view->set('pageTitle', $this->getPageTitle());
           $this->view->set('saveError', $e->getMessage());
           $this->view->set('formData', $data_page);
           
       }
       
   } else {
    
      $this->setView('edit-page');
      $this->setPageTitle('Edit Page');
      $this->setFormAction('editPage');
      $this->view->set('pageTitle', $this->getPageTitle());
      $this->view->set('formAction', $this->getFormAction());
      $this->view->set('formData', $data_page);
      $this->view->set('postStatus', $this->pageEvent->postStatusDropDown($getPage->post_status));
      $this->view->set('commentStatus', $this->pageEvent->commentStatusDropDown($getPage->comment_status));
      $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
       
   }
   
   return $this->view->render();
   
 }
 
 public function delete($id)
 {
   $this->pageEvent->setPageId($id);
   $this->pageEvent->removePage();
   direct_page('index.php?load=pages&status=pageDeleted', 200);
 }
 
 protected function setView($viewName)
 {
   $this->view = new View('admin', 'ui', 'pages', $viewName);
 }
 
}