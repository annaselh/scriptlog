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

  private $sanitizer;

  public function __construct(Reply $replyDao, FormValidator $validator, Sanitize $sanitizer)
  {

    $this->replyDao = $replyDao;
    $this->validator = $validator;
    $this->sanitizer = $sanitizer;

  }
}