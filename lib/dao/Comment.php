<?php 
/**
 * Class Comment extends Dao
 * 
 * @package  SCRIPTLOG/LIB/DAO/Comment
 * @category Dao Class
 * @author   M.Noermoehammad
 * @license  MIT
 * @version  1.0
 * @since    Since Release 1.0
 * 
 */
class Comment extends Dao
{

/**
 * 
 */
 public function __construct()
 {
   parent::__construct();
 }
 
/**
 * Find Comments
 * 
 * @method public findComments()
 * @param integer|string $orderBy -- default order By Id
 * @return array
 * 
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
 
/**
 * Find Comment
 * 
 * @method public findComment()
 * @param integer|number $id
 * @param object $sanitize
 * @return array
 * 
 */
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
 
/**
 * Add Comment
 * 
 * @method public addComment()
 * @param array $bind
 * 
 */
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
 
/**
 * Update Comment
 * 
 * @method public updateComment()
 * @param object $sanitize
 * @param array $bind
 * @param integer $ID
 * 
 */
 public function updateComment($sanitize, $bind, $ID)
 {
   
   $cleanId = $this->filteringId($sanitize, $ID, 'sql');
   $stmt = $this->modify("comments", [
       'comment_author_name' => $bind['comment_author_name'],
       'comment_content' => $bind['comment_content'],
       'comment_status' => $bind['comment_status']
   ], "`ID` = {$cleanId}");
   
 }
 
/**
 * Delete comment
 * 
 * @method public deleteComment()
 * @param integer $ID
 * 
 */
 public function deleteComment($id, $sanitize)
 {
   $idsanitized = $this->filteringId($sanitize, $id, 'sql');
   $stmt = $this->deleteRecord("comments", "`ID` = {$idsanitized}");
 }
 
/**
 * Check comment Id
 * 
 * @method public checkCommentId()
 * @param integer $id
 * @param object $sanitize
 * @return integer|numeric
 * 
 */
 public function checkCommentId($id, $sanitize)
 {
   $sql = "SELECT ID FROM comments WHERE ID = ?";
   $id_sanitized = $this->filteringId($sanitize, $id, 'sql');
   $this->setSQL($sql);
   $stmt = $this->checkCountValue([$id_sanitized]);
   return $stmt > 0;
 }
 
/**
 * Drop down comment status
 * 
 * @method public dropDownCommentStatement($selected)
 * @param string $selected
 * @return mixed
 * 
 */
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