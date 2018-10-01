<?php
/**
 * ReplyEvent Class
 * 
 * @package SCRIPTLOG
 * @author  Maoelana Noermoehammad 
 * @license MIT
 * @version 1.0.0
 * @since   Since Release 1.0.0
 * 
 */
class ReplyEvent
{
  private $reply_id;

  private $comment_id;

  private $user_id;

  private $reply_content;

  private $reply_status;

  private $replyDao;

  private $validator;

  private $sanitize;

  public function __construct(Reply $replyDao, FormValidator $validator, Sanitize $sanitize)  {

    $this->replyDao = $replyDao;
    $this->validator = $validator;
    $this->sanitize = $sanitize;

  }

  public function setReplyId($replyId)
  {
    $this->reply_id = $replyId;
  }

  public function setCommentId($commentId)
  {
    $this->comment_id = $comment_id;
  }

  public function setUserId($userId)
  {
    $this->user_d = $user_id;
  }

  public function setReplyContent($replyContent)
  {
    $this->reply_content = $replyContent;
  }

  public function setReplyStatus($replyStatus)
  {
    $this->reply_status = $replyStatus;
  }

  
}