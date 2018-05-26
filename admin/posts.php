<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$postId = $userId = isset($_GET['userId']) ? abs((int)$_GET['userId']) : 0;
$postDao = new Post();
$postModule = new PostApp();

switch ($action) {
    
    case 'newPost':
        
        
        
        break;
        
    case 'editPost':
        
        break;
        
    case 'deletePost':
        
        break;
        
    default:
        
        break;
        
}