<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

class PageEvent
{
  private $pageId;
    
  private $author;
    
  private $title;
    
  private $slug;
    
  private $content;
    
  private $image;
    
  private $meta_desc;
    
  private $meta_key;
    
  private $page_status;
    
  private $comment_status;
  
  public function __construct(Page $pageDao, FormValidator $validator, Sanitize $sanitizer)
  {
    $this->pageDao = $pageDao;
    $this->validator = $validator;
    $this->sanitizer = $sanitizer;
  }
  
  public function setPageId($pageId)
  {
   $this->pageId = $pageId;
  }
  
  public function setPageTitle($title)
  {
    $this->title = $title;
  }
  
  public function setPageSlug($slug)
  {
    $this->slug = make_slug($slug);
  }
  
  public function setPageContent($content)
  {
    $this->content = prevent_injection($content);
  }
  
  public function setMetaDesc($meta_desc)
  {
    $this->meta_desc = $meta_desc;
  }
  
  public function setMetaKeys($meta_keys)
  {
    $this->meta_key = $meta_keys;
  }
  
  public function setPublish($page_status)
  {
    $this->page_status = $page_status;
  }
  
  public function setComment($comment_status)
  {
   $this->comment_status = $comment_status;
  }
 
  public function grabPages($position, $limit, $type)
  {
    return $this->pageDao->findPages($position, $limit, $type);
  }
  
  public function grabPage($id, $type)
  {
    return $this->pageDao->findPageById($id, $type, $this->sanitizer);
  }
  
  public function addPage()
  {
    
  }
}