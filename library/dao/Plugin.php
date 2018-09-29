<?php  
/**
 * Plugin class extends Dao
 * insert, update, delete
 * and select records from plugin table
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Plugin extends Dao
{
  protected $accessLevel;
  
  public function __construct()
  {
     parent::__construct();
  }
  
  /**
   * Get list of plugins
   * 
   * @param integer $position
   * @param integer $limit
   * @param string $orderBy
   * @return boolean|array|object
   */
  public function getPlugins($orderBy = 'ID')
  {
     
    $sql = "SELECT ID, plugin_name, plugin_link, plugin_desc, 
            plugin_status, plugin_level,
            plugin_sort FROM plugin ORDER BY :orderBy DESC";
   
    $this->setSQL($sql);
    
    $plugins = $this->findAll([':orderBy' => $orderBy]);
  
    if (empty($plugins)) return false;
    
    return $plugins;
    
  }
  
  /**
   * Get plugin
   * get single value of plugin
   * 
   * @param integer $id
   * @param integer $sanitize
   * @return boolean|array|object
   */
  public function getPlugin($id, $sanitize)
  {
     $idsanitized = $this->filteringId($sanitize, $id, 'sql');
     
     $sql = "SELECT ID, plugin_name, plugin_link, plugin_desc, 
             plugin_status, plugin_level, plugin_sort FROM plugin WHERE ID = ?"; 
     
     $this->setSQL($sql);
     
     $pluginDetails = $this->findRow([$idsanitized]);
     
     if (empty($pluginDetails)) return false;
     
     return $pluginDetails;
     
  }
  
  /**
   * Insert new plugin
   * 
   * @param array $bind
   */
  public function insertPlugin($bind)
  {
    $getSort = "SELECT plugin_sort FROM plugin ORDER BY plugin_sort DESC";
    $this->setSQL($getSort);
    $rows = $this->findColumn();
    $plugin_sorted = $rows['plugin_sort'] + 1;

     // input data plugin
     $stmt = $this->create("plugin", [
         'plugin_name' => $bind['plugin_name'],
         'plugin_link' => $bind['plugin_link'],
         'plugin_desc' => $bind['plugin_desc'],
         'plugin_level' => $bind['plugin_level'],
         'plugin_sort' => $plugin_sorted
     ]);
     
     $plugin_id = $this->lastId();
     
     $getLink = "SELECT ID, plugin_link FROM plugin WHERE ID = ?";
     
     $this->setSQL($getLink);
     
     $link = $this->findColumn([$plugin_id]);
     
     if ($link['plugin_link'] == '') {
         
        $stmt2 = $this->modify("plugin", ['plugin_link' => '#'], "`ID` = {$link['ID']}");
         
     }
     
  }
  
  /**
   * Update plugin
   * 
   * @param integer $id
   * @param array $bind
   */
  public function updatePlugin($sanitize, $bind, $ID)
  {

    $cleanId = $this->filteringId($sanitize, $ID, 'sql');
    $stmt = $this->modify("plugin", [
        'plugin_name' => $bind['plugin_name'],
        'plugin_link' => $bind['plugin_link'],
        'plugin_desc' => $bind['plugin_desc'],
        'plugin_status' => $bind['plugin_status'],
        'plugin_sort' => $bind['plugin_sort']
    ], "`ID` = {$cleanId}");
    
  }
  
  /**
   * Activate plugin
   * 
   * @param integer $id
   */
  public function activatePlugin($id, $sanitize)
  {
    $idsanitized = $this->filteringId($sanitize, $id, 'sql');
    $stmt = $this->modify("plugin", ['plugin_status' => 'Y'], "`ID` = {$idsanitized}");
  }
  
  /**
   * Deactivate plugin
   * 
   * @param integer $id
   */
  public function deactivatePlugin($id, $sanitize)
  {
    $idsanitized = $this->filteringId($sanitize, $id, 'sql');
    $stmt = $this->modify("plugin", ['plugin_status' => 'N'], "`ID` = {$idsanitized}");  
  }
  
  /**
   * Delete plugin
   * 
   * @param integer $id
   * @param object $sanitize
   */
  public function deletePlugin($id, $sanitize)
  {
    $idsanitized = $this->filteringId($sanitize, $id, 'sql');
    $stmt = $this->deleteRecord("plugin", "`ID` = {$idsanitized}");
  }
  
  /**
   * Check plugin Id
   * 
   * @param integer $id
   * @param object $sanitize
   * @return boolean
   */
  public function checkPluginId($id,$sanitize)
  {
    $idsanitized = $this->filteringId($sanitize, $id, 'sql');
    $sql = "SELECT ID FROM plugin WHERE ID = ?";
    $this->setSQL($sql);
    $stmt = $this->checkCountValue([$idsanitized]);
    return($stmt > 0);
  }
  
  /**
   * Is plugin active or not
   * 
   * @param string $plugin_name
   * @return boolean
   */
  public function isPluginActived($plugin_name)
  {
      if ($this->plugiExists($plugin_name) == true) {
          
         $sql = "SELECT plugin_status FROM plugin WHERE plugin_name = ?";
         $this->setSQL($sql);
         $plugin_status = $this->findColumn([$plugin_name]);
         
         if (empty($plugin_status)) return false;
         
         return $plugin_status;
         
      } else {
         
          return false;
          
      }
      
  }
   
  /**
   * Set menu plugin
   * 
   * @param UserEvent $userEvent
   * @return string
   */
  public function setMenuPlugin($accessLevel)
  {
    $this->accessLevel =$accessLevel;
    
    $plugins = $this->setPrivatePlugins();
    
    $html = array();
    
    foreach ($plugins as $p => $plugin) {
        
      $pluginPath = APP_ROOT . APP_LIBRARY . '/plugin/'.strtolower($plugin->plugin_name).'/'.strtolower($plugin->plugin_name).'.php';
      
      if ($this->accessLevel == 'administrator') {
            
          if (is_dir(APP_ROOT.APP_LIBRARY.'/plugin/'.strtolower($plugin->plugin_name)) && is_readable($pluginPath)) {
              
              $html[] = '<li><a href="'.$plugin->plugin_link.'">'.$plugin->plugin_name.'</a></li>';
              
          }
           
      }
      
    }
    
    return implode("\n", $html);
    
  }
  
  /**
   * Drop down plugin level
   * 
   * @param string $selected
   * @return string
   */
  public function dropDownPluginLevel($selected = '')
  {
     $name = 'plugin_level';

     $plugin_level = array('public' => 'Public', 'private' => 'Private');

     if ($selected != '') {
         $selected = $selected;
     }

     return dropdown($name, $plugin_level, $selected);
 
  }
  
  /**
   * Total plugins records
   * 
   * @param array $data
   * @return boolean
   * 
   */
  public function totalPluginRecords($data = null)
  {
    $sql = "SELECT ID FROM plugin";
    $this->setSQL($sql);
    return $this->checkCountValue($data);
  }
  
  /**
   * Set private plugin
   * 
   * @return boolean|array|object
   */
  public function setPrivatePlugins()
  {
    $sql = "SELECT ID, plugin_name, plugin_link, plugin_desc, 
            plugin_status, plugin_level, plugin_sort
            FROM plugin WHERE plugin_level = 'private' AND plugin_status = 'Y' 
            ORDER BY plugin_name";
    
    $this->setSQL($sql);
    
    $privatePlugins = $this->findRow();
    
    if (empty($privatePlugins)) return false;
    
    return $privatePlugins;
    
  }
 
  /**
   * is plugin exists or not
   * 
   * @param string $plugin_name
   * @return boolean
   */
  public function pluginExists($plugin_name)
  {
    $sql = "SELECT COUNT(ID) FROM plugin WHERE plugin_name = ?";
    $this->setSQL($sql);
    $stmt = $this->findColumn([$plugin_name]);
    
    if ($stmt == 1) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
  }

}