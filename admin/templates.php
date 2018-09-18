<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$themeId = isset($_GET['themeId']) ? abs((int)$_GET['themeId']) : 0;
$themeDao = new Theme();
$validator = new FormValidator();
$themeEvent = new ThemeEvent($themeDao, $validator, $sanitizer);
$themeApp = new ThemeApp($themeEvent);

switch ($action) {

    case 'newTheme':
        
        if ($themeId == 0) {
            
            $themeApp -> insert();

        } 

        break;

    case 'installTheme' :
        
        if ($themeId == 0) {

            $themeApp -> setupTheme();

        } 

        break;

    case 'editTheme':

        if ($themeDao -> checkThemeId($themeId, $sanitizer)) {
            
            $themeApp -> update($themeId);

        } else {

            direct_page('index.php?load=templates&error=themeNotFound', 404);

        }
    
        break;

    case 'deleteTheme':

       $themeApp -> delete($themeId);

    default:
        # show list of all themes
        $themeApp -> listItems();
        break;

}
