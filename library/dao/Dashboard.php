<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

class Dashboard extends Dao
{
  
 protected $posts;
 
 public function __construct()
 {
   parent::__construct();
        
 }

 public function getPosts(Post $post, $position, $limit, $orderBy = null, $author = null)
 {
   $this->posts = $post;
   
   $posts = $this->posts->findPosts($position, $limit, $orderBy, $author);
   
   return $posts;
   
 }
 
}