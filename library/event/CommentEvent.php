<?php 
/**
 * CommentEvent Class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class CommentEvent
{
  
  private $comment_id;
  
  private $post_id;
  
  private $author_name;
  
  private $author_ip;
  
  private $content;
  
  private $status;
  
  
  public function __construct(Comment $commentDao, FormValidator $validator, Sanitize $sanitizer)
  {
   $this->commentDao = $commentDao;
   $this->validator = $validator;
   $this->sanitizer = $sanitizer;
  }
  
  public function setCommentId($comment_id)
  {
    $this->comment_id = $comment_id;
  }

  public function setPostId($post_id)
  {
    $this->post_id = $post_id;
  }
  
  public function setAuthorName($author_name)
  {
    $this->author_name = $author_name;
  }
  
  public function setAuthorIP($author_ip)
  {
    $this->author_ip = $author_ip;
  }
  
  public function setCommentContent($content)
  {
   $this->content = prevent_injection($content);
  }
  
  public function setCommentStatus($status)
  {
   $this->status = $status;
  }
  
  public function grabComments($position, $limit, $orderBy = 'ID')
  {
    return $this->commentDao->findComments($position, $limit, $orderBy);
  }
  
  public function grabComment($id)
  {
    return $this->commentDao->findComment($id, $this->sanitizer);
  }
  
  public function addComment()
  {
   $this->validator->sanitize($this->post_id, 'int');
   $this->validator->sanitize($this->author_name, 'string');
   
   return $this->commentDao->addComment([
       'comment_post_id' => $this->post_id,
       'comment_author_name' => $this->author_name,
       'comment_author_ip' => $this->author_ip,
       'comment_content' => $this->content,
       'date_publish' => date("Ymd")
   ]);
   
  }
  
  public function modifyComment()
  {
    $this->validator->sanitize($this->comment_id, 'int');
    $this->validator->sanitize($this->author_name, 'string');
    
    return $this->commentDao->updateComment($this->comment_id, [
        'comment_author_name' => $this->author_name,
        'comment_content' => $this->content,
        'comment_status' => $this->status
    ]);
    
  }
  
  public function removeComment()
  {
     
    $this->validator->sanitize($this->comment_id, 'int');
    
    $data_comment = $this->commentDao->findComment($this->comment_id, $this->sanitizer);
    if (false === $data_comment) {
        direct_page('index.php?load=comments&error=commentNotFound', 404);
    }
    
    return $this->commentDao->deleteComment($this->comment_id, $this->sanitizer);
    
  }
  
}