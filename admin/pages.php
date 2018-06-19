<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$pageId = isset($_GET['pageId']) ? abs((int)$_GET['pageId']) : 0;
$pageDao = new Page();
$validator = new FormValidator();
$sanitizer = new Sanitize();
$pageEvent = new PageEvent($pageDao, $validator, $sanitizer);


switch ($action) {
    
    case 'newPage':
    
        if ($pageId == 0) {
            
        }
        
        break;
    
    case 'editPage':
        
        break;
        
    case 'deletePage':
        
        break;
    
    default:
        
    break;
    
}
