<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$postId = $userId = isset($_GET['userId']) ? abs((int)$_GET['userId']) : 0;
$postDao = new Post();
$validator = new ValidatorService();
$postModule = new PostApp($postDao, $validator);

switch ($action) {
    
    case 'newPost':
        
        $postModule -> insert();
        
        break;
        
    case 'editPost':
        
        $postModule -> update();
        
        break;
        
    case 'deletePost':
        
        $postModule -> delete();
        
        break;
        
    default:
        
        $postModule -> listItems();
        
        break;
        
}