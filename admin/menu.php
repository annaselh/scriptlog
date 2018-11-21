<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$menuId = isset($_GET['menuId']) ? abs((int)$_GET['menuId']) : 0;
$menuDao = new Menu();
$validator = new FormValidator();
$menuEvent = new MenuEvent($menuDao, $validator, $sanitizer);
$menuApp = new MenuApp($menuEvent);
    
    switch ($action) {
    
        case 'newMenu':
            # Add New Menu
            if ($menuId == 0) {
    
                $menuApp -> insert();
    
            }
    
            break;
        
        case 'editMenu':
    
            if ($menuDao -> checkMenuId($menuId, $sanitizer)) {
    
                $menuApp -> update($menuId);
    
            } else {
    
                direct_page('index.php?load=menu&error=menuNotFound', 404);
    
            }
    
            break;
    
        case 'deleteMenu':
    
            $menuApp -> remove($menuId);
    
        default:
            
            $menuApp -> listItems();
    
            break;
            
    }