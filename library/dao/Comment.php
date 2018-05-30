<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
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
 
 public function findComments($position, $limit, $orderBy = 'ID')
 {
   $sql = "SELECT ID, comment_post_id, comment_author_name, comment_author_url, 
           comment_author_ip, comment_content, comment_status, 
           comment_date, user_id FROM comments ORDER BY $orderBy
           DESC LIMIT :position, :limit";
   
   $this->setSQL($sql);
   $comments = $this->findAll([':position' => $position, ':limit' => $limit]);
   
   if (empty($comments)) return false;
   
   return $comments;
   
 }
 
 public function findComment($id, $sanitize)
 {
   $idsanitized = $this->filteringId($sanitize, $id, 'sql');
   $sql = "SELECT ID, comment_post_id, comment_author_name, comment_author_url, 
           comment_author_ip, comment_content, comment_status, 
           comment_date, user_id FROM comments WHERE ID = ?";
   $this->setSQL($sql);
   $commentDetails = $this->findRow([$idsanitized], PDO::FETCH_ASSOC);
   if (empty($commentDetails)) return false;
   
   return $commentDetails;
   
 }
 
 public function addComment($bind)
 {
    
   $stmt = $this->create("comments", [
        'comment_post_id' => $bind['comment_post_id'],
        'comment_author_name' => $bind['comment_author_name'],
        'comment_author_url' => $bind['comment_author_url'],
        'comment_author_ip' => $bind['comment_author_ip'],
        'comment_content' => $bind['comment_content'],
        'comment_date' => $bind['comment_date']
   ]); 
    
 }
 
 public function updateComment($id, $bind)
 {
   
   $stmt = $this->modify("comments", [
       'comment_author_name' => $bind['comment_author_name'],
       'comment_author_url' => $bind['comment_author_url'],
       'comment_content' => $bind['comment_content'],
       'comment_status' => $bind['comment_status']
   ], "`ID` = {$id}");
   
 }
 
 public function deleteComment($id, $sanitize)
 {
   $idsanitized = $this->filteringId($sanitize, $id, 'sql');
   $stmt = $this->delete("comments", "`ID` = {$idsanitized}");
 }
 
 public function checkCommentId($id, $sanitize)
 {
   $sql = "SELECT ID FROM comments WHERE ID = ?";
   $idsanitized = $this->filteringId($sanitize, $id, 'sql');
   $this->setSQL($sql);
   $stmt = $this->checkCountValue([$idsanitized]);
   return($stmt > 0);
 }
 
}