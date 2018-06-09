<?php  if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * Post class extends Dao
 * insert, update, delete
 * and select records from posts table
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Post extends Dao
{
 
protected $linkPosts;

public function __construct()
{
  parent::__construct();	
}

/**
 * Find posts
 * 
 * @param integer $position
 * @param integer $limit
 * @param string $orderBy
 * @param string $author
 * @return boolean|array|object
 */
public function findPosts($position, $limit, $orderBy = 'ID', $author = null)
{
    if (!is_null($author)) {
        
        $sql = "SELECT p.ID, p.post_image, p.post_author,
                p.date_created, p.date_modified, p.post_title, p.post_slug,
                p.post_content, p.post_status, p.post_type, u.user_login
  				FROM posts AS p
  				INNER JOIN users AS u ON p.post_author = u.ID
  				WHERE p.post_author = :author
  				AND p.post_type = 'blog'
  				ORDER BY p.{$orderBy} DESC
  		        LIMIT :position, :limit";
    
        $this->setSQL($sql);
        
        $posts = $this->findAll([':author' => $author, ':position' => $position, ':limit' => $limit], PDO::FETCH_ASSOC);
        
    } else {
        
        $sql = "SELECT p.ID, p.post_image, p.post_author,
                p.date_created, p.date_modified, p.post_title,
                p.post_slug, p.post_content, p.post_status, p.post_type, 
                u.user_login
  		    FROM
                 posts AS p
  		    INNER JOIN
                 users AS u ON p.post_author = u.ID
  		    WHERE
                 p.post_type = 'blog'
  			ORDER BY p.{$orderBy} DESC LIMIT :position, :limit";
          
        $this->setSQL($sql);
        
        $posts = $this->findAll([':position' => $position, ':limit' => $limit], PDO::FETCH_ASSOC);
    }
    
    if (empty($posts)) return false;
    
    return $posts;
    
}

/**
 * Find single value post
 * 
 * @param integer $postId
 * @param object $sanitize
 * @param string $author
 * @return boolean|array|object
 */
public function findPost($id, $sanitize, $author = null)
{
    
   $sanitized_id = $this->filteringId($sanitize, $id, 'sql');
    
   if (!empty($author)) {
        
        $sql = "SELECT ID, post_image, post_author,
  	  		  date_created, date_modified, post_title,
  	  		  post_slug, post_content, post_summary, 
              post_keyword, post_status,
  	  		  post_type, comment_status
  	  		  FROM posts
  	  		  WHERE ID = :ID AND post_author = :author
  			  AND post_type = 'blog'";
        
        $this->setSQL($sql);
        $postDetail = $this->findRow([':ID' => $sanitized_id, ':author' => $author]);
        
   } else {
        
       $sql = "SELECT ID, post_image, post_author,
  	  		  date_created, date_modified, post_title,
  	  		  post_slug, post_content, post_summary, post_keyword, 
              post_status,
  	  		  post_type, comment_status
  	  		  FROM posts
  	  		  WHERE ID = :ID AND post_type = 'blog'";
        
       $this->setSQL($sql);
       $postDetail = $this->findRow([':ID' => $sanitized_id]);
        
  }
    
  if (empty($postDetail)) return false;
  
  return $postDetail;
   
}

/**
 * show detail post by id
 * 
 * @param integer $id
 * @param object $sanitize
 * @return boolean|array|object
 */
public function showPostById($id, $sanitize)
{
    $sql = "SELECT p.ID, p.post_image, p.post_author,
                p.date_created, p.date_modified, p.post_title,
                p.post_slug, p.post_content, p.post_summary, p.post_keyword, 
                p.post_status, p.post_type, p.comment_status, u.user_login
  		   FROM posts AS p
  		   INNER JOIN users AS u ON p.post_author = u.ID
  		   WHERE p.ID = :ID AND p.post_type = 'blog'";
    
    $sanitized_id = $this->filteringId($sanitize, $id, 'sql');
    
    $this->setSQL($sql);
    
    $readPost = $this->findRow([':ID' => $sanitized_id], PDO::FETCH_ASSOC);
    
    if (empty($readPost)) return false;
    
    return $readPost;
    
}

/**
 * show posts published
 * 
 * @param Paginator $perPage
 * @param object $sanitize
 * @return boolean|array[]|object[]|string[]
 */
public function showPostsPublished(Paginator $perPage, $sanitize)
{
    
    $pagination = null;
    
    $this->linkPosts = $perPage;
    
    $getPostId = "SELECT ID FROM posts WHERE post_status = 'publish' AND post_type = 'blog'";
    
    $this->setSQL($getPostId);
    $this->linkPosts->set_total($this->checkCountValue());
    $sql = "SELECT p.ID, p.post_image, p.post_author,
                     p.date_created, p.date_modified, p.post_title,
                     p.post_slug, p.post_content, p.post_summary, p.post_keyword,
                     p.post_type, p.post_status, u.user_login
  			FROM posts AS p
  			INNER JOIN users AS u ON p.post_author = u.ID
  			WHERE p.post_type = 'blog' AND p.post_status = 'publish'
  			ORDER BY p.ID DESC " . $this->linkPosts->get_limit($sanitize);
    
    $this->setSQL($sql);
    $postsPublished = $this->findAll();
    $pagination = $this->linkPosts->page_links($sanitize);
    
    if (empty($postsPublished)) return false;
    return(['postsPublished' => $postsPublished, 'paginationLink' => $pagination]);
        
}

/**
 * insert new post
 * 
 * @param array $bind
 * @param integer $topicId
 */
public function createPost($bind, $topicId)
{
  
 if (!empty($bind['post_image'])) {
  		
  	// insert into posts
   $stmt = $this->create("posts", [
       'post_image' => $bind['post_image'],
       'post_author' => $bind['post_author'],
       'date_created' => $bind['date_created'],
       'post_title' => $bind['post_title'],
       'post_slug' => $bind['post_slug'],
       'post_content' => $bind['post_content'],
       'post_summary' => $bind['post_summary'],
       'post_keyword' => $bind['post_keyword'],
       'post_status' => $bind['post_status'],
       'comment_status' => $bind['comment_status']
   ]);
     	 
 } else {
  			
  $stmt = $this->create("posts", [
      'post_author' => $bind['post_author'],
      'date_created' => $bind['date_created'],
      'post_title' => $bind['post_title'],
      'post_slug' => $bind['post_slug'],
      'post_content' => $bind['post_content'],
      'post_summary' => $bind['post_summary'],
      'post_keyword' => $bind['post_keyword'],
      'post_status' => $bind['post_status'],
      'comment_status' => $bind['comment_status']
  ]);
  		  
 }
  	
 $postId = $this->lastId();
 
 if (is_array($topicId)) {
  			
  	foreach ($_POST['topic_id'] as $topicId) {
  	
  	$stmt2 = $this->create("post_topic", [
  	    'post_id' => $postId,
  	    'topic_id' => $topicId
  	]);
  			
   }
  			
 } else {
 
  $stmt2 = $this->create("post_topic", [
      'post_id' => $postId,
      'topic_id' => $topicId
  ]);
  
 }
 
}

/**
 * modify post
 * 
 * @param array $bind
 * @param integer $id
 * @param integer $topicId
 */
public function updatePost($bind, $id, $topicId) 
{
  	  
 if (!empty($bind['post_image'])) {
  	  	
  	$stmt = $this->modify("posts", [
  	    'post_image' => $bind['post_image'],
  	    'post_author' => $bind['post_author'],
  	    'date_modified' => $bind['date_modified'],
  	    'post_title' => $bind['post_title'],
  	    'post_slug' => $bind['post_slug'],
  	    'post_content' => $bind['post_content'],
  	    'post_summary' => $bind['post_summary'],
  	    'post_keyword' => $bind['post_keyword'],
  	    'post_status' => $bind['post_status'],
  	    'comment_status' => $bind['comment_status']
  	], "`ID` = {$id}");
  	 	
  } else {
  	 
      $stmt = $this->modify("posts", [
          'post_author' => $bind['post_author'],
          'date_modified' => $bind['date_modified'],
          'post_title' => $bind['post_title'],
          'post_slug' => $bind['post_slug'],
          'post_content' => $bind['post_content'],
          'post_summary' => $bind['post_summary'],
          'post_keyword' => $bind['post_keyword'],
          'post_status' => $bind['post_status'],
          'comment_status' => $bind['comment_status']
      ], "`ID` = {$id}");
      
  }
  
  // query Id
  $this->setSQL("SELECT ID FROM posts WHERE ID = ?");
  $post_id = $this->findColumn([$id]);
  
  // delete post_topic
  $stmt2 = $this->delete("post_topic", "`ID` = {$post_id->ID}");
  	  
  if (is_array($topicId)) {
  	     
  	 foreach ($_POST['catID'] as $topicId) {
  	     
  	    $stmt3 = $this->create("post_topic", [
  	        'post_id' => $id,
  	        'topic_id' => $topicId
  	    ]);
  	    
  	 }
  	     
  } else {
  	      
      $stmt3 = $this->create("post_topic", [
          'post_id' => $id,
          'topic_id' => $topicId
      ]);
      
  }
  	  
}

/**
 * Delete post record
 * 
 * @param integer $id
 * @param object $sanitizing
 */
public function deletePost($id, $sanitizing)
{ 
 $idsanitized = $this->filteringId($sanitizing, $id, 'sql');
 $stmt = $this->delete("posts", "`ID` = {$idsanitized}"); 	  
}

/**
 * check post id
 * 
 * @param integer $id
 * @param object $sanitizing
 * @return boolean
 */
public function checkPostId($id, $sanitizing)
{
  $cleanId = $this->filteringId($sanitizing, $id, 'sql');
  $sql = "SELECT ID FROM posts WHERE ID = ? AND post_type = 'blog'";
  $this->setSQL($sql);
  $stmt = $this->checkCountValue([$cleanId]);
  return($stmt > 0); 		
}

/**
 * set post status
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
    
 	$html[] = '<label>Post status :</label>';
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
 
public function dropDownCommentStatus($selected = "")
{
 	$option_selected = "";
 	
 	if (!$selected) {
 		
 		$option_selected = 'selected="selected"';
 	}
 	
 	// list position in array
 	$comment_status = array('open', 'close');
 	
 	$html = array();
 	
 	$html[] = '<label>Comments status :</label>';
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