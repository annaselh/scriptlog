<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * Dashboard class extend Dao
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
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