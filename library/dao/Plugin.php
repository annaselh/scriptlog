<?php
/**
 * Plugin class extends Dao
 * insert, update, delete
 * and select records from plugin table
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @copyright 2018 kartatopia.com
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
  
  public function getPlugins($position, $limit, $orderBy = 'ID')
  {
    $sql = "SELECT ID, plugin_name, plugin_desc, plugin_status, plugin_level,
            plugin_sort FROM plugin ORDER BY ".$orderBy." LIMIT :position, :limit";
   
    $this->setSQL($sql);
    
    $plugins = $this->findAll([':position' => $position, ':limit' => $limit], PDO::FETCH_ASSOC);
  
    if (empty($plugins)) return false;
    
    return $plugins;
    
  }
  
  public function getPlugin($id, $sanitize)
  {
     $idsanitized = $this->filteringId($sanitize, $id, 'sql');
     
     $sql = "SELECT ID, plugin_name, plugin_desc, plugin_status, plugin_level,
            plugin_sort FROM plugin WHERE ID = ?"; 
     
     $this->setSQL($sql);
     
     $pluginDetails = $this->findRow([$idsanitized]);
     
     if (empty($pluginDetails)) return false;
     
     return $pluginDetails;
     
  }
  
  public function addPlugin($bind)
  {
     $getSort = "SELECT plugin_sort FROM plugin ";
     
     $this->setSQL($getSort);
     
     $rows = $this->findColumn();
     
     $plugin_sorted = $rows -> plugin_sort + 1;
     
     // input data plugin
     $stmt = $this->create("plugin", [
         'plugin_name' => $bind['plugin_name'],
         'plugin_link' => $bind['plugin_link'],
         'plugin_desc' => $bind['plugin_desc'],
         'plugin_status' => $bind['plugin_status'],
         'plugin_sort' => $plugin_sorted
     ]);
     
     $plugin_id = $this->lastId();
     
     $getLink = "SELECT ID, plugin_link FROM plugin WHERE ID = ?";
     
     $this->setSQL($getLink);
     
     $link = $this->findColumn([$plugin_id]);
     
     if ($link->plugin_link == '') {
         
         $stmt2 = $this->modify("plugin", ['plugin_link' => '#'], "`ID` = {$link->ID}");
         
     }
     
  }
  
  public function updatePlugin($id, $bind)
  {
    $stmt = $this->modify("plugin", [
        'plugin_name' => $bind['plugin_name'],
        'plugin_link' => $bind['plugin_link'],
        'plugin_desc' => $bind['plugin_desc'],
        'plugin_status' => $bind['plugin_status'],
        'plugin_sort' => $bind['plugin_sort']
    ], "`ID` = {$id}");
    
  }
  
  public function activatePlugin($id)
  {
    $stmt = $this->modify("plugin", ['plugin_status' => 'Y'], "`ID` = {$id}");
  }
  
  public function deactivatePlugin($id)
  {
    $stmt = $this->modify("plugin", ['plugin_status' => 'N'], "`ID` = {$id}");  
  }
  
  public function deletePlugin($id, $sanitize)
  {
    $idsanitized = $this->filteringId($sanitize, $id, 'sql');
    $stmt = $this->delete("plugin", "`ID` = {$idsanitized}");
  }
  
  public function checkPluginId($id,$sanitize)
  {
    $idsanitized = $this->filteringId($sanitize, $id, 'sql');
    
  }
}
