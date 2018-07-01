<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

class PageEvent
{
  private $pageId;
    
  private $author;
    
  private $title;
    
  private $slug;
    
  private $content;
    
  private $image;
    
  private $meta_desc;
    
  private $meta_key;
    
  private $page_status;
  
  private $post_type;
    
  private $comment_status;
  
  public function __construct(Page $pageDao, FormValidator $validator, Sanitize $sanitizer)
  {
    $this->pageDao = $pageDao;
    $this->validator = $validator;
    $this->sanitizer = $sanitizer;
  }
  
  public function setPageId($pageId)
  {
   $this->pageId = $pageId;
  }
  
  public function setPageTitle($title)
  {
    $this->title = $title;
  }
  
  public function setPageSlug($slug)
  {
    $this->slug = make_slug($slug);
  }
  
  public function setPageContent($content)
  {
    $this->content = prevent_injection($content);
  }
  
  public function setMetaDesc($meta_desc)
  {
    $this->meta_desc = $meta_desc;
  }
  
  public function setMetaKeys($meta_keys)
  {
    $this->meta_key = $meta_keys;
  }
  
  public function setPublish($page_status)
  {
    $this->page_status = $page_status;
  }
  
  public function setPostType($post_type)
  {
    $this->post_type = $post_type;    
  }
  
  public function setComment($comment_status)
  {
   $this->comment_status = $comment_status;
  }
 
  public function grabPages($position, $limit, $type)
  {
    return $this->pageDao->findPages($position, $limit, $type);
  }
  
  public function grabPage($id, $type)
  {
    return $this->pageDao->findPageById($id, $type, $this->sanitizer);
  }
  
  public function addPage()
  {
    $upload_path = __DIR__ . '/../../public/files/pictures/';
    $image_uploader =  new ImageUploader('image', $upload_path);
    
    $this->author = isset($_SESSION['ID']) ? (int)$_SESSION['ID'] : 0;
    
    $this->validator->sanitize($this->author, 'int');
    $this->validator->sanitize($this->title, 'string');
    $this->validator->sanitize($this->meta_desc, 'string');
    $this->validator->sanitize($this->meta_key, 'string');
    
    if ($image_uploader -> isImageUploaded()) {
       
        return $this->pageDao->createPage([
            'post_author' => $this->author,
            'date_created' => date("Ymd"),
            'post_title' => $this->title,
            'post_slug' => $this->slug,
            'post_content' => $this->content,
            'post_summary' => $this->meta_desc,
            'post_keyword' => $this->meta_key,
            'post_status' => $this->page_status,
            'post_type' => $this->post_type,
            'comment_status' => $this->comment_status
        ]);
        
    } else {
     
       $newFileName = $image_uploader -> renameImage();
       $uploadImagePost = $image_uploader -> uploadImage('post', $newFileName, 770, 400, 'crop');
       
       return $this->pageDao->createPage([
           'post_image' => $newFileName,
           'post_author' => $this->author,
           'date_created' => date("Ymd"),
           'post_title' => $this->title,
           'post_slug' => $this->slug,
           'post_content' => $this->content,
           'post_summary' => $this->meta_desc,
           'post_keyword' => $this->meta_key,
           'post_status' => $this->page_status,
           'post_type' => $this->post_type,
           'comment_status' => $this->comment_status
       ]);
        
    }
    
  }
  
  public function modifyPage()
  {
    $upload_path = __DIR__ . '/../../public/files/pictures/';
    $image_uploader =  new ImageUploader('image', $upload_path);
    
    $this->author = isset($_SESSION['ID']) ? (int)$_SESSION['ID'] : 0;
    
    $this->validator->sanitize($this->pageId, 'int');
    $this->validator->sanitize($this->author, 'int');
    $this->validator->sanitize($this->title, 'string');
    $this->validator->sanitize($this->meta_desc, 'string');
    $this->validator->sanitize($this->meta_key, 'string');
    
    if ($image_uploader -> isImageUploaded()) {
        
      return $this->pageDao->updatePage([
          'post_author' => $this->author,
          'date_modified' => date("Ymd"),
          'post_title' => $this->title,
          'post_slug' => $this->slug,
          'post_content' => $this->content,
          'post_summary' => $this->meta_desc,
          'post_keyword' => $this->meta_key,
          'post_status' => $this->page_status,
          'post_type' => $this->post_type,
          'comment_status' => $this->comment_status
      ], $this->pageId);
      
    } else {
        
       $newFileName = $image_uploader -> renameImage();
       $uploadImagePost = $image_uploader -> uploadImage('post', $newFileName, 770, 400, 'crop');
       
       return $this->pageDao->updatePage([
           'post_image' => $newFileName,
           'date_modified' => date("Ymd"),
           'post_title' => $this->title,
           'post_slug' => $this->slug,
           'post_content' => $this->content,
           'post_summary' => $this->meta_desc,
           'post_keyword' => $this->meta_key,
           'post_status' => $this->page_status,
           'post_type' => $this->post_type,
           'comment_status' => $this->comment_status
       ], $this->pageId);
       
    }
      
  }
  
  public function removePage()
  {
    $this->validator->sanitize($this->pageId, 'int');
    
    $data_page = $this->pageDao->findPageById($this->pageId, $this->post_type, $this->sanitizer);
    if (false === $data_page) {
        direct_page('index.php?load=pages&error=pageNotFound', 404);
    }
    
    $this->image = $data_page['post_image'];
    if ($this->image !== '') {
        
        if (is_readable(__DIR__ . '/../public/files/pictures/'.$this->post_image)) {
            unlink(__DIR__ . '/../public/files/pictures'.$this->image);
            unlink(__DIR__ . '/../public/files/pictures/thumbs/thumbs_'.$this->image);
        }
        
        return $this->pageDao->deletePage($this->pageId, $this->sanitizer, $this->post_type);
        
    } else {
        
        return $this->pageDao->deletePage($this->pageId, $this->sanitizer, $this->post_type);
        
    }
    
  }
  
  public function postStatusDropDown($selected = "") 
  {
    return $this->pageDao->dropDownPostStatus($selected);
  }
  
  public function commentStatusDropDown($selected = "")
  {
    return $this->pageDao->dropDownCommentStatus($selected);
  }
  
}