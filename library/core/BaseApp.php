<?php 
/**
 * Abstract Class BaseApp
 *
 * @package   SCRIPTLOG
 * @abstract  BaseApp Class
 * @author    M.Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
abstract class BaseApp implements AppInterface
{
  /**
   * Page title
   * 
   * @var string
   */
  protected $pageTitle;

  /**
   * Form action
   * 
   * @var string
   */
  protected $formAction;
  
  /**
   * set page title
   * 
   * @param string $pageTitle
   */
  public function setPageTitle($pageTitle)
  {
    $this->pageTitle = $pageTitle; 
  }

  /**
   * get page title
   * 
   * @return string
   */
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
  
  abstract protected function remove($id);
  
} // End of abstract class BaseApp