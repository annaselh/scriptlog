<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$settingId = isset($_GET['settingId']) ? abs((int)$_GET['settingId']) : 0;
$configDao = new Configuration();
$validator = new FormValidator();
$configEvent = new ConfigurationEvent($configDao, $validator, $sanitizer);
$configApp = new ConfigurationApp($configEvent);

switch ($action) {

    case 'editConfig':
        
        #edit configuration
        if ($configDao -> checkConfigId($configId, $sanitizer)) {

            $configApp -> update($configId);
            
        } else {

            direct_page('index.php?load=settings&error=configNotFound', 404);
            
        }

        break;

    case 'setConfig':
      
        // set configuration
        if ($configDao->checkToSetup()) {

            $configApp -> insert();

        } 

        break;
    
    default:

       #display setting
       $configApp -> listItems();

       break;

}