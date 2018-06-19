<?php  if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * Page class extends Dao
 * insert, update, delete
 * and select records from users table
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Page extends Dao
{

public function __construct()
{
  parent::__construct();
}

/**
 * Find pages
 * 
 * @param integer $position
 * @param integer $limit
 * @param string $type
 * @param string $orderBy
 * @return boolean|array|object
 */
public function findPages($position, $limit, $type, $orderBy = 'ID')
{
   $sql = "SELECT ID, post_author, date_created, date_modified,
  		  post_title, post_type
  		  FROM posts WHERE post_type = :type
  		  ORDER BY ".$orderBy."
  		  LIMIT :position, :limit";
    
    $this->setSQL($sql);
    
    $pages = $this->findAll([':type' => $type, ':position' => $position, ':limit' => $limit]);
    
    if (empty($pages)) return false;
    
    return $pages;
    
}

/**
 * Find page by id
 * 
 * @param integer $pageId
 * @param string $post_type
 * @param object $sanitizing
 * @return boolean|array|object
 */
public function findPageById($pageId, $post_type, $sanitizing)
{
    $sql = "SELECT ID, post_image, post_author,
  	  	   date_created, date_modified, post_title,
  	  	   post_slug, post_content, post_status,
  	  	   post_type, comment_status
  	  	   FROM posts
  	  	   WHERE ID = ? AND post_type = ? ";
    
    $id_sanitized = $this -> filteringId($sanitizing, $pageId, 'sql');
    
    $this->setSQL($sql);
    
    $pageById = $this->findRow([$id_sanitized, $post_type]);
    
    if (empty($pageById)) return false;
    
    return $pageById;
    
}

/**
 * Find page by slug title
 * 
 * @param string $slug
 * @return boolean|array|object
 */
public function findPageBySlug($slug)
{
    $sql = "SELECT
              posts.ID, posts.post_image, posts.post_author,
  	  	      posts.date_created, posts.date_modified, posts.post_title,
  	  	      posts.post_slug, posts.post_content, posts.post_status,
  	  	      posts.post_type, posts.comment_status, users.user_login
  	  	   FROM
               posts, users
  	  	   WHERE
              posts.post_slug = :slug
              AND posts.post_status = 'publish'
              AND posts.post_type = 'page' ";
    
    $this->setSQL($sql);
    $pageBySlug = $this->findRow([':slug' => $slug]);
    
    if (empty($pageBySlug)) return false;
    
    return $pageBySlug;
    
}

/**
 * Insert new page
 * 
 * @param array $bind
 */
public function createPage($bind)
{
 
 if (!empty($bind['post_image'])) {
 
 	$stmt = $this->create("posts", [
 	    'post_image' => $bind['post_image'],
 	    'post_author' => $bind['post_author'],
 	    'post_content' => $bind['post_content'],
 	    'post_status' => $bind['post_status'],
 	    'post_type' => $bind['post_type'],
 	    'comment_status' => $bind['comment_status']
 	]);
 	
 } else {
 	
 	$stmt = $this->create("posts", [
 	    'post_author' => $bind['post_author'],
 	    'post_content' => $bind['post_content'],
 	    'post_status' => $bind['post_status'],
 	    'post_type' => $bind['post_type'],
 	    'comment_status' => $bind['comment_status']
 	]);
 	
 }
 
}

/**
 * Update page
 * 
 * @param array $bind
 * @param integer $id
 */
public function updatePage($bind, $id)
{
 
 if (empty($bind['post_image'])) {
 
 	$stmt = $this->modify("posts", [
 	    'date_modified' => $bind['date_modified'],
 	    'post_title' => $bind['post_title'],
 	    'post_slug' => $bind['post_slug'],
 	    'post_status' => $bind['post_status'],
 	    'comment_status' => $bind['comment_status']
 	], "`ID` = {$id}"." AND `post_type` = {$bind['post_type']}");
 	
 } else {
 	
 	$stmt = $this->modify("posts", [
 	    'post_image' => $bind['post_image'],
 	    'date_modified' => $bind['date_modified'],
 	    'post_title' => $bind['post_title'],
 	    'post_slug' => $bind['post_slug'],
 	    'post_status' => $bind['post_status'],
 	    'comment_status' => $bind['comment_status']
 	    ], "`ID` = {$id}"." AND `post_type` = {$bind['post_type']}");
 	
 }
  	
}

/**
 * Delete page
 * 
 * @param integer $id
 * @param object $sanitizing
 * @param string $type
 */
public function deletePage($id, $sanitizing, $type)
{
   
 $sanitized_id = $this->filteringId($sanitizing, $id, 'sql');
 $stmt = $this->delete("posts", "`ID` = {$sanitized_id} AND post_type = {$type}");
   
}

/**
 * Check page id
 * 
 * @param integer $id
 * @param object $sanitizing
 * @return boolean
 */
public function checkPageId($id, $sanitizing)
{
   $cleanId = $this->filteringId($sanitizing, $id, 'sql');
   $sql = "SELECT ID FROM posts WHERE ID = ?";
   $this->setSQL($sql);
   $stmt = $this->checkCountValue([$cleanId]);
   return($stmt > 0);
}
 
/**
 * Set post status
 * 
 * @param string $selected
 * @return string
 */
public function dropDownPostStatus($selected = "")
{
     
     $option_selected = "";
     
     if (!$selected) {
         
         $option_selected = 'selected="selected"';
     }
     
     // list position in array
     $posts_status = array('publish', 'draft');
     
     $html = array();
     
     $html[] = '<label>Post setting</label>';
     $html[] = '<select class="form-control" name="post_status">';
     
     foreach ($posts_status as $s => $status) {
         
         if ($selected == $status) {
             $option_selected = 'selected="selected"';
         }
         
         // set up the option line
         $html[]  =  '<option value="' . $status. '"' . $option_selected . '>' . $status . '</option>';
         
         // clear out the selected option flag
         $option_selected = '';
         
     }
     
     $html[] = '</select>';
     
     return implode("\n", $html);
     
}

/**
 * Set comment status
 * 
 * @param string $selected
 * @return string
 */
public function dropDownCommentStatus($selected = '')
{
     $option_selected = "";
     
     if (!$selected) {
         
         $option_selected = 'selected="selected"';
     }
     
     // list position in array
     $comment_status = array('open', 'close');
     
     $html = array();
     
     $html[] = '<label>comments setting</label>';
     $html[] = '<select class="form-control" name="comment_status">';
     
     foreach ($comment_status as $c => $comment) {
         
         if ($selected == $comment) {
             $option_selected = 'selected="selected"';
         }
         
         // set up the option line
         $html[]  =  '<option value="' . $comment. '"' . $option_selected . '>' . $comment . '</option>';
         
         // clear out the selected option flag
         $option_selected = '';
         
     }
     
     $html[] = '</select>';
     
     return implode("\n", $html);
     
}
 
}