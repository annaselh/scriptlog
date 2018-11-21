<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$subMenuId = isset($_GET['subMenuId']) ? abs((int)$_GET['subMenuId']) : 0;
$menuChildDao = new MenuChild();
$validator = new FormValidator();
$menuChildEvent = new MenuChildEvent($menuChildDao, $validator, $sanitizer);
$menuChildApp = new MenuChildApp($menuChildEvent);
    
    switch ($action) {
    
        case 'newSubmenu':
            # Add New Menu
            if ($subMenuId == 0) {
    
                $menuChildApp -> insert();
    
            }
    
            break;
        
        case 'editSubmenu':
    
            if ($menuChildDao -> checkMenuChildId($subMenuId, $sanitizer)) {
    
                $menuChildApp -> update($subMenuId);
    
            } else {
    
                direct_page('index.php?load=menu&error=menuNotFound', 404);
    
            }
    
            break;
    
        case 'deleteSubmenu':
    
            $menuChildApp -> remove($menuId);
    
        default:
            
            $menuChildApp -> listItems();
    
            break;
            
    }