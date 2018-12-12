<?php
/**
 * AppInterface Interface
 *
 * @package   SCRIPTLOG
 * @author    M.Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
interface AppInterface
{
    
  public function setPageTitle($pageTitle);
    
  public function getPageTitle();
    
  public function setFormAction($formAction);
    
  public function getFormAction();
    
}