<?php 
/**
 * DashboardApp class
 *
 * @package   SCRIPTLOG
 * @author    M.Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class DashboardApp
{
  public $pageTitle;
  
  public function welcomeAdmin($pageTitle)
  {
    $this->pageTitle = $pageTitle;
    
    require(APP_ROOT.APP_ADMIN.DS.'ui'.DS.'dashboard'.DS.'home-admin.php');
    
  }
  
  public function welcomeUser()
  {
      
  }
  
  
}