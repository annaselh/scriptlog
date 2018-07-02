<?php 
/**
 * BaseApp Class
 *
 * @package   SCRIPTLOG
 * @abstract  BaseApp Class
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
abstract class BaseApp implements AppInterface
{
  protected $pageTitle;
  
  protected $formAction;
  
  public function setPageTitle($pageTitle)
  {
    $this->pageTitle = $pageTitle; 
  }
  
  public function getPageTitle()
  {
    return $this->pageTitle;
  }
  
  public function setFormAction($formAction)
  {
    $this->formAction = $formAction;
  }
  
  public function getFormAction()
  {
    return $this->formAction;
  }
  
  abstract protected function listItems();
  
  abstract protected function insert();
  
  abstract protected function update($id);
  
  abstract protected function delete($id);
  
}