<?php 
/**
 * Dashboard class
 *
 * @package   SCRIPTLOG
 * @author    M.Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Dashboard
{
  
 private $posts;

 private $users;
 
 public function getPosts(Post $post, $orderBy = null, $author = null)
 {
   
   $this->posts = $post;
   
   $posts = $this->posts->findPosts($orderBy, $author);

   $totalPost = $this->posts->totalPostRecords();
   
   return array('posts' => $posts, 'totalPost' => $totalPost);

 }

 public function getUsers(User $users)
 {
     
 }
 
}