<?php 
/**
 * Comment class extends Dao
 * insert, update, delete
 * and select records from comment table
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Comment extends Dao
{
    
 public function __construct()
 {
   parent::__construct();
   
 }
 
 /**
  * Retreive all comments records
  * from comments table
  * 
  * @param string $orderBy
  * @return boolean|array|object
  */
 public function findComments($orderBy = 'ID')
 {
   $sql = "SELECT c.ID, c.comment_post_id, c.comment_author_name,  
             c.comment_author_ip, c.comment_content, c.comment_status, 
             c.comment_date, p.post_title 
           FROM comments AS c INNER JOIN posts AS p 
           ON c.comment_post_id = p.ID ORDER BY :orderBy DESC ";
   
   $this->setSQL($sql);
   $comments = $this->findAll([':orderBy' => $orderBy]);
   
   if (empty($comments)) return false;
   
   return $comments;
   
 }
 
 public function findComment($id, $sanitize)
 {
   $id_sanitized = $this->filteringId($sanitize, $id, 'sql');
   
   $sql = "SELECT ID, comment_post_id, comment_author_name, 
           comment_author_ip, comment_content, comment_status, 
           comment_date FROM comments WHERE ID = ?";
   
   $this->setSQL($sql);
   
   $commentDetails = $this->findRow([$id_sanitized]);
   
   if (empty($commentDetails)) return false;
   
   return $commentDetails;
   
 }
 
 public function addComment($bind)
 {
    
   $stmt = $this->create("comments", [
        'comment_post_id' => $bind['comment_post_id'],
        'comment_author_name' => $bind['comment_author_name'],
        'comment_author_ip' => $bind['comment_author_ip'],
        'comment_content' => $bind['comment_content'],
        'comment_date' => $bind['comment_date']
   ]); 
    
 }
 
 public function updateComment($sanitize, $bind, $ID)
 {
   
   $cleanId = $this->filteringId($sanitize, $ID, 'sql');
   $stmt = $this->modify("comments", [
       'comment_author_name' => $bind['comment_author_name'],
       'comment_content' => $bind['comment_content'],
       'comment_status' => $bind['comment_status']
   ], "`ID` = {$cleanId}");
   
 }
 
 public function deleteComment($id, $sanitize)
 {
   $idsanitized = $this->filteringId($sanitize, $id, 'sql');
   $stmt = $this->deleteRecord("comments", "`ID` = {$idsanitized}");
 }
 
 public function checkCommentId($id, $sanitize)
 {
   $sql = "SELECT ID FROM comments WHERE ID = ?";
   $id_sanitized = $this->filteringId($sanitize, $id, 'sql');
   $this->setSQL($sql);
   $stmt = $this->checkCountValue([$id_sanitized]);
   return $stmt > 0;
 }
 
 public function dropDownCommentStatement($selected = '')
 {
     $name = 'comment_status';
     
     // list position in array
     $comment_status = array('approved' => 'Approved', 'pending' => 'Pending', 'spam' => 'Spam');
     
     if ($selected != '') {
         $selected = $selected;
     }
     
     return dropdown($name, $comment_status, $selected);
     
 }
 
}