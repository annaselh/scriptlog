<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$pageId = isset($_GET['pageId']) ? abs((int)$_GET['pageId']) : 0;
$pageDao = new Page();
$validator = new FormValidator();
$pageEvent = new PageEvent($pageDao, $validator, $sanitizer);
$pageApp = new PageApp($pageEvent);

switch ($action) {
    
    case 'newPage':
    
        if ($pageId == 0) {
            
            $pageApp -> insert();
            
        }
        
        break;
    
    case 'editPage':
        
        if ($pageDao -> checkPageId($pageId, $sanitizer)) {
            
            $pageApp -> update($pageId);
            
        } else {
            
            
        }
        break;
        
    case 'deletePage':
        
        $pageApp -> remove($pageId);
        
        break;
    
    default:
        
        $pageApp -> listItems();
        
    break;
    
}