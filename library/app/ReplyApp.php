<?php
/**
 * 
 */
class ReplyApp extends BaseApp
{
  private $view;

  private $replyEvent;

  public function __construct(ReplyEvent $replyEvent)
  {
    $this->replyEvent = $replyEvent;
  }


}