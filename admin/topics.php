<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$topicId = isset($_GET['topicId']) ? abs((int)$_GET['topicId']) : 0;
$topicDao = new Topic();
$validator = new FormValidator();
$topicEvent = new TopicEvent($topicDao, $validator, $sanitizer);
$topicApp = new TopicApp($topicEvent);

switch ($action) {
    
    case 'newTopic':
        
        if ($topicId == 0) {
            $topicApp -> insert();
        }
        
        break;
        
    case 'updateTopic':
        
        if ($topicDao -> checkTopicId($topicId, $sanitizer)) {
            
            $topicApp -> update($topicId);
            
        } else {
            
            header("Location: index.php?load=topics&error=topicNotFound");
            
        }
        
        break;
        
    case 'deleteTopic':
        
        $topicApp -> delete($topicId);
        
        break;
        
    default:
        
        $topicApp -> listItems();
        
        break;
        
}