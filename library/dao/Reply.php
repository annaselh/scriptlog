<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * Reply class extends Dao
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
class Reply extends Dao
{
    
  public function __construct()
  {
    parent::__construct();
  }
  
  public function findReplies($position, $limit, $orderBy = 'ID')
  {
    $sql = "SELECT ID, comment_id, user_id, reply_content, reply_status, date_publish 
            FROM comment_reply ORDER BY $orderBy DESC LIMIT :position, :limit";
    
    $this->setSQL($sql);
    $replies = $this->findAll([':position' => $position, ':limit' => $limit]);
    
    if (empty($replies)) return false;
    
    return $replies;
    
  }
  
  public function findReply($id, $sanitize)
  {
    $idsanitized = $this->filteringId($sanitize, $id, 'sql');
    $sql = "SELECT ID, comment_id, user_id, reply_content, reply_status
            date_publish FROM comment_reply WHERE ID = ?";
    $this->setSQL($sql);
    $replyDetails = $this->findRow([$idsanitized], PDO::FETCH_ASSOC);
    if (empty($replyDetails)) return false;
    
    return $replyDetails;
    
  }
  
  public function createReply($bind)
  {
    $stmt = $this->create("comment_reply", [
        'comment_id' => $bind['comment_id'],
        'user_id' => $bind['user_id'],
        'reply_content' => $bind['reply_content'],
        'date_publish' => $bind['date_publish']
    ]);
    
  }
  
  public function updateReply($id, $bind)
  {
     $stmt = $this->modify("comment", [
         'comment_id' => $bind['comment_id'],
         'user_id' => $bind['user_id'],
         'reply_content' => $bind['reply_content'],
         'reply_status' => $bind['reply_status']
     ], "`ID` = {$id}");
     
  }
  
  public function deleteReply($id, $sanitize)
  { 
    
    $idsanitized = $this->filteringId($sanitize, $id, 'sql');
    $stmt = $this->delete("comment_reply", "`ID` = {$idsanitized}");
    
  }
  
}