<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * PostTopic class extends Model
 * Interacting with database to insert, update,
 * delete and select records from table post_topic
 * 
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @copyright 2018 kartatopia.com
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */

class PostTopic extends Dao
{
  
  public function __construct()
  {
    parent::__construct();
        
  }
  
  /**
   * Find post topic
   * 
   * @param integer $postId
   * @param object $sanitize
   * @return boolean|array|object
   */
  public function findPostTopic($postId, $sanitize)
  {
    $sql = "SELECT topic_title, topic_slug 
            FROM topics, post_topic
            WHERE topics.ID = post_topic.topic_id
            AND post_topic.post_id = :post_id";
    
    $cleanId = $this->filteringId($sanitize, $postId, 'sql');
    
    $this->setSQL($sql);
    
    $post_topics = $this->findRow([':post_id' => $cleanId]);
    
    if (empty($post_topics)) return false;
    
    return $post_topics;
    
  }
  
  /**
   * Set Link Topics
   * 
   * @param integer $postId
   * @param object $sanitize
   * @param string $position
   * @return string
   */
  public function setLinkTopics($postId, $sanitize, $position = 'meta')
  {
    $url = APP_PROTOCOL.'://'.APP_HOSTNAME.dirname(dirname($_SERVER['PHP_SELF'])).'/';
    
    $html = array();
   
    $linkCategories = $this->findPostTopic($postId, $sanitize);
   
    foreach ($linkCategories as $l => $linkCategory) {
       
        if (!$position) {
        
            $html[] = '<a href="'.$url.'category/'.preventInject($linkCategory['category_slug']).'" class="tag-name">'.preventInject($linkCategory->topic_title).'</a>';
        
        } else {
            
            $html[] = prevent_injection($linkCategory -> topic_title);
            
        }
    }
   
    return implode(", ", $html);
  
  }
  
  /**
   * show post by topic
   * 
   * @param integer $topicId
   * @param object $sanitize
   * @return boolean|array|object
   */
  public function showPostByTopic($topicId, $sanitize)
  {
      $cleanId = $this->filteringId($sanitize, $topicId, 'sql');
      
      $sql = "SELECT
                posts.postID, posts.post_author, posts.date_created,
                posts.post_title, posts.post_slug, posts.post_content,
                posts.post_status, posts.post_type, users.user_login
           FROM
                posts, post_topic, users
           WHERE
                posts.postID = post_topic.postID
                AND post_topic.topic_id = :topic_id
                AND posts.post_author = users.ID
                AND posts.post_status = 'publish' AND posts.post_type = 'blog'
           ORDER BY
                posts.postID DESC ";
      
      $this -> setSQL($sql);
      
      $postByTopics = $this->findAll([':topic_id' => $cleanId]);
      
      if (empty($postByTopics)) return false;
      
      return $postByTopics;
      
  }
              
}