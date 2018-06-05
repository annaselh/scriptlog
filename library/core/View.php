<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * View Class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class View
{
  
  protected $previlege;
  
  protected $dir;
  
  protected $module;
  
  protected $action;
  
  protected $data = array();
  
  public function __construct($access, $dir, $module, $action = null)
  {
    
    $this->module = $module;
   
    $this->setPrevilege($access);
    
    if ($this->getPrevilege() == 'admin') $this->dir = APP_ROOT . APP_ADMIN . DS . $dir . DS .$this->module . DS;
   
    if (!is_null($action)) $this->action = $action;
    
  }
  
  public function setPrevilege($previlege)
  {
    $this->previlege = $previlege;
  }
  
  public function getPrevilege()
  {
    return $this->previlege;
  }
  
  public function set($key, $value)
  {
     $this->data[$key] = $value;
  }
  
  public function get($key)
  {
     return $this->data[$key];
  }
  
  public function render()
  {
      if (!is_dir($this->dir) && !file_exists($this->dir. $this->action . '.php')) {
          throw new Exception("View ".$this->action.'.php'. ' does not exists');
      }
      
      extract($this->data);
      ob_start();
      require($this->dir.$this->action.'.php');
      $render = ob_get_contents();
      ob_end_clean();
      echo $render;
    
  }
  
}