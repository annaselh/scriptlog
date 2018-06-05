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
class PostService
{
  private $postId;
  
  private $author;
  
  protected $title;
  
  protected $slug;
  
  protected $content;
  
  protected $image;
  
  protected $meta_desc;
  
  protected $meta_key;
  
  protected $post_status;
  
  protected $comment_status;
  
  protected $topics; 
  
  public function __construct(Post $postDao, FormValidator $validator)
  {
     $this->postDao = $postDao;
     $this->validator = $validator;
  }
  
  public function grabPosts()
  {
    
  }
  
  public function addPost($values)
  {
     $upload_path = __DIR__ . '/../../public/files/pictures/';
     $image_uploader =  new ImageUploader('image', $upload_path);
      
     $this->author = isset($_SESSION['ID']) ? (int)$_SESSION['ID'] : 0;
     $this->title = $values['post_title'];
     $this->slug = make_slug($this->title);
     $this->content = $values['post_content'];
     $this->meta_desc = $values['meta_description'];
     $this->meta_key = $values['meta_keywords'];
     $this->topics = $values['catID'];
     
     $this->validator->sanitize($this->author, 'int');
     $this->validator->sanitize($this->title, 'string');
     $this->validator->sanitize($this->meta_desc, 'string');
     $this->validator->sanitize($this->meta_key, 'string');
    
     if ($image_uploader->isImageUploaded()) {
         
         if ($this->topics == 0) {
             
             $category = new Topic();
             $categoryId = $category -> createTopic(['topic_title' => 'Uncategorized', 'topic_slug' => 'uncategorized']);
             $sanitize = new Sanitize();
             $getCategory = $category -> findTopicById($categoryId, $sanitize, PDO::FETCH_ASSOC);
             
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
         
         $image_uploader -> saveImagePost(770, 400, 'crop');
         $fileName = $image_uploader -> renameImage();
         if ($this->topics == 0) {
             
             $category = new Topic();
             $categoryId = $category -> createTopic(['topic_title' => 'Uncategorized', 'topic_slug' => 'uncategorized']);
             $sanitize = new Sanitize();
             $getCategory = $category -> findTopicById($categoryId, $sanitize, PDO::FETCH_ASSOC);
             
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
      
  }
  
  public function setPostStatus($selected = "")
  {
      return $this->postDao->setPostStatus($selected);
  }
  
  public function setCommentStatus($selected = "")
  {
      return $this->postDao->setCommentStatus($selected);
  }
}