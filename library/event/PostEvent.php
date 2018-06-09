<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * PostService Class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class PostEvent
{
  private $postId;
  
  private $author;
  
  private $title;
  
  private $slug;
  
  private $content;
  
  private $image;
  
  private $meta_desc;
  
  private $meta_key;
  
  private $post_status;
  
  private $comment_status;
  
  private $topics; 
  
  public function __construct(Post $postDao, FormValidator $validator, Sanitize $sanitizer)
  {
     $this->postDao = $postDao;
     $this->validator = $validator;
     $this->sanitizer = $sanitizer;
  }
  
  public function setPostId($postId)
  {
    $this->postId = $postId;    
  }
  
  public function setPostTitle($title)
  {
    $this->title = $title;
  }
  
  public function setPostSlug($slug)
  {
    $this->slug = make_slug($slug);    
  }
  
  public function setPostContent($content)
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
  
  public function setPublish($post_status)
  {
    $this->post_status = $post_status;
  }
  
  public function setComment($comment_status)
  {
    $this->comment_status = $comment_status;    
  }
  
  public function setTopics($topics)
  {
    $this->topics = $topics;    
  }
  
  public function grabPosts($position, $limit, $orderBy = 'ID', $author = null)
  {
    return $this->postDao->findPosts($position, $limit, $orderBy, $author);
  }
  
  public function addPost()
  {
     $upload_path = __DIR__ . '/../../public/files/pictures/';
     $image_uploader =  new ImageUploader('image', $upload_path);
     $category = new Topic();
     
     $this->author = isset($_SESSION['ID']) ? (int)$_SESSION['ID'] : 0;
     
     $this->validator->sanitize($this->author, 'int');
     $this->validator->sanitize($this->title, 'string');
     $this->validator->sanitize($this->meta_desc, 'string');
     $this->validator->sanitize($this->meta_key, 'string');
    
     if ($image_uploader->isImageUploaded()) {
         
         if ($this->topics == 0) {
             
             $categoryId = $category -> createTopic(['topic_title' => 'Uncategorized', 'topic_slug' => 'uncategorized']);
             $getCategory = $category -> findTopicById($categoryId, $this->sanitizer, PDO::FETCH_ASSOC);
             
             return $this->postDao->createPost([
                 'post_author' => $this->author,
                 'date_created' => date("Ymd"),
                 'post_title' => prevent_injection($this->title),
                 'post_slug'  => $this->slug,
                 'post_content' => prevent_injection($this->content),
                 'post_summary' => $this->meta_desc,
                 'post_keyword' => $this->meta_key,
                 'post_status' => $this->post_status,
                 'comment_status' => $this->comment_status
             ], $getCategory['ID']);
             
         } else {
             
             return $this->postDao->createPost([
                 'post_author' => $this->author,
                 'date_created' => date("Ymd"),
                 'post_title' => prevent_injection($this->title),
                 'post_slug'  => $this->slug,
                 'post_content' => $this->content,
                 'post_summary' => $this->meta_desc,
                 'post_keyword' => $this->meta_key,
                 'post_status' => $this->post_status,
                 'comment_status' => $this->comment_status
             ], $this->topics);
             
         }
         
     } else {
       
         $newFileName = $image_uploader -> renameImage();
         $uploadImagePost = $image_uploader -> uploadImage('post', $newFileName, 770, 400, 'crop');
                
         if ($this->topics == 0) {
             
             $categoryId = $category -> createTopic(['topic_title' => 'Uncategorized', 'topic_slug' => 'uncategorized']);
             $getCategory = $category -> findTopicById($categoryId, $this->sanitizer, PDO::FETCH_ASSOC);
             
             return $this->postDao->createPost([
                 'post_image' => $fileName,
                 'post_author' => $this->author,
                 'date_created' => date("Ymd"),
                 'post_title' => prevent_injection($this->title),
                 'post_slug'  => $this->slug,
                 'post_content' => $this->content,
                 'post_summary' => $this->meta_desc,
                 'post_keyword' => $this->meta_key,
                 'post_status' => $this->post_status,
                 'comment_status' => $this->comment_status
             ], $getCategory['ID']);
             
         } else {
             
             return $this->postDao->createPost([
                 'post_image' => $fileName,
                 'post_author' => $this->author,
                 'date_created' => date("Ymd"),
                 'post_title' => prevent_injection($this->title),
                 'post_slug'  => $this->slug,
                 'post_content' => $this->content,
                 'post_summary' => $this->meta_desc,
                 'post_keyword' => $this->meta_key,
                 'post_status' => $this->post_status,
                 'comment_status' => $this->comment_status
             ], $this->topics);
             
         }
         
     }
     
  }
  
  public function modifyPost()
  {
     
    $upload_path = __DIR__ . '/../../public/files/pictures/';
    $image_uploader =  new ImageUploader('image', $upload_path);
    $category = new Topic();
      
    $this->author = isset($_SESSION['ID']) ? (int)$_SESSION['ID'] : 0;
    
    $this->validator->sanitize($this->postId, 'int');
    $this->validator->sanitize($this->author, 'int');
    $this->validator->sanitize($this->title, 'string');
    $this->validator->sanitize($this->meta_desc, 'string');
    $this->validator->sanitize($this->meta_key, 'string');
      
    if ($image_uploader -> isImageUploaded()) {
          
        return $this->postDao->updatePost([
            'post_author' => $this->author,
            'date_modified' => date("Ymd"),
            'post_title' => $this->title,
            'post_slug' => $this->slug,
            'post_content' => $this->content,
            'post_summary' => $this->meta_desc,
            'post_keyword' => $this->meta_key,
            'post_status' => $this->post_status,
            'comment_status' => $this->comment_status
        ], $this->postId, $this->topics);
         
     }
  }
  
  public function removePost()
  {
    $this->validator->sanitize($this->postId, 'int');
    
    if (false === $this->postDao->findPost($this->postId, $this->sanitizer)) {
        direct_page('/admin/index.php?load=posts');
    }
    
    
  }
  
  public function postStatusDropDown($selected = "")
  {
     return $this->postDao->dropDownPostStatus($selected);
  }
  
  public function commentStatusDropDown($selected = "")
  {
     return $this->postDao->dropDownCommentStatus($selected);
  }
}