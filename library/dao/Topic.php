<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * Topic class extends Dao
 * insert, update, delete and 
 * select records from topics table
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Topic extends Dao
{
  
  /**
   * overrides Dao constructor
   */
  public function __construct()
  {
	
	parent::__construct();
	
  }

  /**
   * Find All Topics
   * 
   * @param integer $position
   * @param integer $limit
   * @param string $orderBy
   * @return boolean|array|object
   */
  public function findTopics($position = null, $limit = null, $orderBy = 'ID')
  {
      if ((!is_null($position)) && (!is_null($limit))) {
          
          $sql = "SELECT ID, topic_title, topic_slug, topic_status
				  FROM topics
                  ORDER BY '$orderBy'
				  DESC LIMIT :position, :limit";
          
          $this->setSQL($sql);
          $topics = $this->findAll([':position'=>$position, ':limit'=>$limit]);
          
      } else {
          
          $sql = "SELECT ID, topic_title, topic_slug, topic_status
                  FROM topics ORDER BY '$orderBy'";
          
          $this->setSQL($sql);
          $topics = $this->findAll();
          
      }
      
      if (empty($topics)) return false;
      
      return $topics;
      
  }
  
  /**
   * Find Topic by ID
   * 
   * @param integer $topicId
   * @param object $sanitize
   * @param static $fetchMode
   * @return boolean|array|object
   */
  public function findTopicById($topicId, $sanitize, $fetchMode = null)
  {
    $cleanId = $this->filteringId($sanitize, $topicId, 'sql');
    
    $sql = "SELECT ID, topic_title, topic_slug, topic_status
		    FROM topics WHERE ID = ?";
    
    $this->setSQL($sql);
    
    if (is_null($fetchMode)) {
        
        $topicDetails = $this->findRow([$cleanId]);
        
    } else {
        
        $topicDetails = $this->findRow([$cleanId], $fetchMode);
        
    }
    
    if (empty($topicDetails)) return false;
    
    return $topicDetails;
    
  }
  
  public function findTopicBySlug($slug, $sanitize, $fetchMode = null)
  {
      $sql = "SELECT ID, topic_title
              FROM topics 
              WHERE topic_slug = ? AND topic_status = 'Y'";
      
      $slug_sanitized = $this->filteringId($sanitize, $slug, 'xss');
      
      $this->setSQL($sql);
      
      if (is_null($fetchMode)) {
          $topicDetails = $this->findRow([$slug_sanitized]);
      } else {
          $topicDetails = $this->findRow([$slug_sanitized], $fetchMode);
      }
      
      if (empty($topicDetails)) return false;
      
      return $topicDetails;
      
  }
  
  /**
   * Insert a new records
   * 
   * @method createCategory
   * @param string $title
   * @param string $slug
   */
  public function createTopic($bind)
  {
    $stmt = $this->create("topics", [
        'topic_title' => $bind['topic_title'], 
        'topic_slug' => $bind['topic_slug']
    ]);
    
    return $this->lastId();
    
  }

  /**
   * Update an existing records
   * 
   * @param string $title
   * @param string $slug
   * @param string $status
   * @param integer $ID
   */
  public function updateTopic($sanitize, $bind, $ID)
  {
   $cleanId = $this->filteringId($sanitize, $ID, 'sql');
   
   $stmt = $this->modify("topics", [
       'topic_title' => $bind['topic_title'],
       'topic_slug' => $bind['topic_slug'],
       'topic_status' => $bind['topic_status']
   ], "`ID` = {$cleanId}");
   
  }

  /**
   * Delete an existing records
   * 
   * @param integer $ID
   * @param string $sanitizing
   */
 public function deleteTopic($ID, $sanitize)
 {  	
  $cleanId = $this->filteringId($sanitize, $ID, 'sql');
  
  $stmt = $this->delete("topics", "`ID` = $cleanId");
 }

 /**
  * get post topic table
  * 
  * @param integer $topicId
  * @param integer $postId
  * @return boolean|array|object
  */
 public function getPostTopic($topicId, $postId)
 {
     $sql = "SELECT ID FROM post_topic
             WHERE ID = :ID AND postID = :postID";
     
     $this->setSQL($sql);
     
     $post_topic = $this->findRow(['ID' => $topicId, 'postID' => $postId]);
     
     if (empty($post_topic)) return false;
     
     return $post_topic;
     
 }
 
 /**
  * Set topic
  * post category
  * 
  * @param string $postId
  * @param array $checked
  * @return string
  */
 public function setTopic($postId = '', $checked = NULL)
 {
   	  	
 $checked = "";
     
 if (is_null($checked)) {
     $checked="checked='checked'";
 }
      
 $html = array();
 
 $html[] = '<div class="form-group">';
 $html[] = '<label>Category : </label>';

 $items = $this->findTopics();
 
 if (empty($postId)) {
       
     if ($items) {
         
         foreach ($items as $i => $item) {
             
             if (isset($_POST['catID'])) {
                 
                 if (in_array($item->ID, $_POST['catID'])) {
                     
                     $checked="checked='checked'";
                     
                 } else {
                     
                     $checked = null;
                     
                 }
                 
             }
             
             $html[] = '<label class="checkbox-inline">';
             $html[] = '<input type="checkbox" name="catID[]" value="'.$item->ID.'"'.$checked.'>'.$item->topic_title;
             $html[] = '</label>';
             
         }
         
     } else {
         
         $html[] = '<label class="checkbox-inline">';
         $html[] = '<input type="checkbox" name="catID" value="0" checked>Uncategorized';
         $html[] = '</label>';
         
     }
    
    
 } else {
     
     foreach ($items as $i => $item) {
         
      $post_topic = $this->getPostTopic($item->ID, $postId);
         
      if ($post_topic->ID == $item->ID) {
        
        $checked="checked='checked'";
      
      } else {
       
        $checked = null;
      }
         
         $html[] = '<label class="checkbox-inline">';
         $html[] = '<input type="checkbox" name="catID[]" value="'.$item->ID.'"'.$checked.'>'.$item->topic_title;
         $html[] = '</label>';
         
     }
     
 }
 
  $html[] = '</div>';
 
  return implode("\n", $html);
 
 }
 
 /**
  * Check topic id
  * 
  * @param integer $topicId
  * @param integer $sanitizing
  * @return boolean
  */
 public function checkTopicId($topicId, $sanitizing)
 {
   $sql = "SELECT ID FROM topics WHERE ID = ?";
   $cleanUpId = $this->filteringId($sanitizing, $id, 'sql');
   $this->setSQL($sql);
   $stmt = $this->checkCountValue([$cleanUpId]);
   return($stmt > 0);
 }
	
}