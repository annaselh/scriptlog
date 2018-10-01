<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$pluginId = isset($_GET['pluginId']) ? abs((int)$_GET['pluginId']) : 0;
$pluginDao = new Plugin();
$validator = new FormValidator();
$pluginEvent = new PluginEvent($pluginDao, $validator, $sanitizer);
$pluginApp = new PluginApp($pluginEvent);

switch ($action) {

    case 'installPlugin':
        
        if ($pluginId == 0) {
            
            $pluginApp -> installPlugin();
            
        } else {

            direct_page('index.php?load=dashboard', 200);

        }

        break;
    
    case 'activatePlugin':
        
        $pluginApp -> enablePlugin($pluginId);

        break;

    case 'deactivatePlugin':
        
        $pluginApp -> disablePlugin($pluginId);

        break;

    case 'newPlugin':
       
       if ($pluginId == 0) {

          $pluginApp -> insert();

       } else {

          direct_page('index.php?load=dashboard', 200);
          
       }

       break;

    case 'editPlugin':
       
       if ($pluginDao -> checkPluginId($pluginId, $sanitizer)) {

          $pluginApp -> update($pluginId);

       } else {
         
           direct_page('index.php?load=plugins&error=pluginNotFound', 404);

       }

       break;

    case 'deletePlugin':
       
        $pluginApp -> remove($pluginId);

       break;

    default:
        
        $pluginApp -> listItems();
        
        break;

}