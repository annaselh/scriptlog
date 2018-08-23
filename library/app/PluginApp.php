<?php
/**
 * PluginApp Class extends BaseApp Class
 * 
 * @package SCRIPTLOG
 * @author  Maoelana Noermoehammad
 * @license MIT
 * @version 1.0.0
 * @since   Since Release 1.0.0
 * 
 */
class PluginApp extends BaseApp
{
  private $view;

  private $pluginEvent;

  public function __construct(PluginEvent $pluginEvent)
  {
    $this->pluginEvent = $pluginEvent;
  }

  public function listItems()
  {
    
  }

  public function insert()
  {

  }

  public function update($id)
  {

  }

  public function delete($id)
  {

  }

  protected function setView($viewName)
  {
    
  }
  
}