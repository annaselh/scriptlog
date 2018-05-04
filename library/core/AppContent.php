<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

class AppContent
{
 
 protected $slides;
 
 protected $singlePost;
 
 protected $singlePage;
 
 protected $allPosts;
  
 protected $catpost;
 
 protected $searchResults;
  
 protected function setSlider(Post $slides, $position, $limit)
 {
     $this->slides = $slides;
     
     $results = $this->slides->findPosts($position, $limit);
     
     return $results;
 }
  
 protected function getPostById(Post $singlePost, $id, $sanitize)
 {
   $this->singlePost = $singlePost;
   
   $results = $this->singlePost->showPostById($id, $sanitize);
   
   return $results;
   
 }
 
 protected function getPageBySlug(Page $singlePage, $slug, $sanitize)
 {
  $this->singlePage = $singlePage;
  
  $results = $this->singlePage->findPageBySlug($slug, $sanitize);
  
  return $results;
  
 }
 
 protected function getCategoryPost(PostTopic $postCat, $topicId, $sanitize)
 {
   $this->catpost = $postCat;
   
   $results = $this->catpost->showPostByTopic($topicId, $sanitize);
   
   return $results;
   
 }
 
 protected function getAllPosts(Post $allPosts, $perPage, $sanitize)
 {
   $this->allPosts = $allPosts;
   
   $results = $this->allPosts->showAllPostPublished($perPage, $sanitize);
   
   return $results;
   
 }
 
 protected function seekingPost(SearchSeeker $searching, $data)
 {
   
   $this->searchResults = $searching;
   
   $results = $this->searchResults->searchPost($data);
   
   return $results;
   
 }
 
}