<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$postId = isset($_GET['postId']) ? abs((int)$_GET['postId']) : 0;
$postDao = new Post();
$validator = new FormValidator();
$sanitizer = new Sanitize();
$postEvent = new PostEvent($postDao, $validator, $sanitizer);
$postApp = new PostApp($postEvent, $validator);

switch ($action) {
    
    case 'newPost':
        
        if ($postId == 0) {
            
            $postApp -> insert();
            
        }
        
        break;
        
    case 'editPost':
        
        if ($postDao -> checkPostId($postId, $sanitizer)) {
        
            $postApp -> update();
            
        } else {
            
            header("Location: index.php?load=posts&error=postNotFound");
            
        }
        
        
        break;
        
    case 'deletePost':
        
        $postApp -> delete();
        
        break;
        
    default:
        
        $postApp -> listItems();
        
        break;
        
}