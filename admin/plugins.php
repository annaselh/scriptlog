<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$pluginId = isset($_GET['pluginId']) ? abs((int)$_GET['pluginId']) : 0;
$pluginDao = new Plugin();
$validator = new FormValidator();
$pluginEvent = new PluginEvent($pluginDao, $validator, $sanitizer);
$pluginApp = new PluginApp($pluginEvent);

switch ($action) {

    case 'installPlugin':
        # code...
        break;
    
    case 'activatePlugin':
        # code...
        break;

    case 'deactivatePlugin':
        # code... 
        break;

    case 'addPlugin':
       
       if ($pluginId == 0) {

          $pluginApp -> insert();

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
       
        $pluginApp -> delete($pluginId);

       break;

    default:
        
        $pluginApp -> listItems();
        
        break;

}