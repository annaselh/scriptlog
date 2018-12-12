<?php  
/**
 * Page class extends Dao
 * insert, update, delete
 * and select records from users table
 *
 * @package   SCRIPTLOG
 * @author    M.Noermoehammad
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
public function findPages($type, $orderBy = 'ID')
{
   $sql = "SELECT ID, post_author, post_date, post_modified,
  		  post_title, post_type
  		  FROM posts WHERE post_type = :type
  		  ORDER BY :orderBy DESC";
    
    $this->setSQL($sql);
    
    $pages = $this->findAll([':type' => $type, ':orderBy' => $orderBy]);
    
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
public function findPageById($pageId, $post_type, $sanitize)
{
    $sql = "SELECT ID, post_image, post_author,
  	  	      post_date, post_modified, post_title,
  	  	      post_slug, post_content, post_status,
  	  	      post_type, comment_status
  	  	   FROM posts
  	  	   WHERE ID = ? AND post_type = ? ";
    
    $id_sanitized = $this -> filteringId($sanitize, $pageId, 'sql');
    
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
 * 
 */
public function findPageBySlug($slug, $sanitize)
{
    $sql = "SELECT
              posts.ID, posts.post_image, posts.post_author,
  	  	      posts.post_date, posts.post_modified, posts.post_title,
  	  	      posts.post_slug, posts.post_content,  posts.post_status,
  	  	      posts.post_type, posts.comment_status, users.user_login
  	  	   FROM
               posts, users
  	  	   WHERE
              posts.post_slug = :slug
              AND posts.post_status = 'publish'
              AND posts.post_type = 'page' ";
    
	$this->setSQL($sql);
	
	$slug_sanitized = $this->filteringId($sanitize, $slug, 'xss');

    $pageBySlug = $this->findRow([':slug' => $slug_sanitized]);
    
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
 	    'post_date' => $bind['post_date'],
 	    'post_content' => $bind['post_content'],
 	    'post_status' => $bind['post_status'],
 	    'post_type' => $bind['post_type'],
 	    'comment_status' => $bind['comment_status']
 	]);
 	
 } else {
 	
 	$stmt = $this->create("posts", [
 	    'post_author' => $bind['post_author'],
 	    'post_date' => $bind['post_date'],
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
public function updatePage($sanitize, $bind, $ID)
{
 
 $cleanId = $this->filteringId($sanitize, $ID, 'sql');

 if (empty($bind['post_image'])) {
 
 	$stmt = $this->modify("posts", [
 	    'post_modified' => $bind['post_modified'],
 	    'post_title' => $bind['post_title'],
 	    'post_slug' => $bind['post_slug'],
 	    'post_status' => $bind['post_status'],
 	    'comment_status' => $bind['comment_status']
 	], "ID = {$cleanId}"." AND `post_type` = {$bind['post_type']}");
 	
 } else {
 	
 	$stmt = $this->modify("posts", [
 	    'post_image' => $bind['post_image'],
 	    'post_modified' => $bind['post_modified'],
 	    'post_title' => $bind['post_title'],
 	    'post_slug' => $bind['post_slug'],
 	    'post_status' => $bind['post_status'],
 	    'comment_status' => $bind['comment_status']
 	    ], "ID = {$cleanId}"." AND `post_type` = {$bind['post_type']}");
 	
 }
  	
}

/**
 * Delete page
 * 
 * @param integer $id
 * @param object $sanitizing
 * @param string $type
 */
public function deletePage($ID, $sanitize, $type)
{
 $id_sanitized = $this->filteringId($sanitize, $ID, 'sql');
 $stmt = $this->deleteRecord("posts", "`ID` = {$id_sanitized} AND post_type = {$type}");  
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
     
    $name = 'post_status';
    // list position in array
    $posts_status = array('publish' => 'Publish', 'draft' => 'Draft');
    
    if ($selected != '') {
        $selected = $selected;
    }
    
    return dropdown($name, $posts_status, $selected);
    
}

/**
 * Set comment status
 * 
 * @param string $selected
 * @return string
 */
public function dropDownCommentStatus($selected = '')
{
    
    $name = 'comment_status';
    // list position in array
    $comment_status = array('open' => 'Open', 'close' => 'Close');
    
    if ($selected != '') {
        $selected = $selected;
    }
    
    return dropdown($name, $comment_status, $selected);
    
}

/**
 * Total page records
 * 
 * @param array $data
 * @return boolean
 */
public function totalPageRecords($data = null)
{
   $sql = "SELECT ID FROM posts WHERE post_type = 'page'";
   $this->setSQL($sql);
   return $this->checkCountValue();
}

}