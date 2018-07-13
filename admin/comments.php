<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";  
$commentId = isset($_GET['commentId']) ? abs((int)$_GET['commentId']) : 0;
$commentDao = new Comment();
$validator = new FormValidator();
$sanitizer = new Sanitize();
$commentEvent = new CommentEvent($commentDao, $validator, $sanitizer);
$commentApp = new CommentApp($commentEvent);


