<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * Menu class extends Dao
 * insert, update, delete
 * and select records from menu table
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @copyright 2018 kartatopia.com
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Menu extends Dao
{
    
 public function __construct()
 {
	
  parent::__construct();
		
 }

 /**
  * Find list of menus
  * getting array of rows
  * 
  * @param integer $position
  * @param integer $limit
  * @param string $orderBy
  * @return boolean|array|object
  */
 public function findMenus($position = null, $limit = null, $orderBy = 'menu_label')
 {
     if (is_null($position) && is_null($limit)) {
         
         $sql = "SELECT ID, menu_label, menu_link, menu_sort, menu_status
                FROM menu ORDER BY ".$orderBy;
         
         $this->setSQL($sql);
         
         $menus = $this->findAll();
         
     } else {
         
         $sql = "SELECT ID, menu_label, menu_link, menu_sort, menu_status
                FROM menu ORDER BY ".$orderBy." LIMIT :position, :limit";
         
         $this->setSQL($sql);
         
         $menus = $this->findAll([':position' => $position, ':limit' => $limit]);
         
     }
     
     if (empty($menus)) return false;
     
     return $menus;
     
 }

 /**
  * Find Menu
  * 
  * @param integer $menuId
  * @param object $sanitizing
  * @param static $fetchMode
  * @return boolean|array|object
  */
 public function findMenu($menuId, $sanitizing, $fetchMode = null)
 {
     
     $sql = "SELECT ID, menu_label, menu_link, menu_sort, menu_status
             FROM menu WHERE ID = ?";
     
     $idsanitized = $this->filteringId($sanitizing, $menuId, 'sql');
     
     $this->setSQL($sql);
     
     if (is_null($fetchMode)) {
         
         $menuDetails = $this->findRow([$idsanitized]);
         
     } else {
         
         $menuDetails = $this->findRow([$idsanitized], $fetchMode);
         
     }
     
     if (empty($menuDetails)) return false;
     
     return $menuDetails;
     
 }
 
 /**
  * Insert new menu
  * 
  * @param array $bind
  */
 public function addMenu($bind)
 {
     
   $menuSorted = self::findSortMenu();
   
   $stmt = $this->create("menu", [
       'menu_label' => $bind['menu_label'],
       'menu_link' => $bind['menu_link'],
       'menu_sort' => $menuSorted,
       'menu_status' => $bind['menu_status']
   ]);
   
 }
 
 /**
  * Update menu
  * 
  * @param integer $id
  * @param array $bind
  */
 public function updateMenu($id, $bind)
 {
     
  $stmt = $this->modify("menu", [
      'menu_label' => $bind['menu_label'],
      'menu_link' => $bind['menu_link'],
      'menu_sort' => $bind['menu_sort'],
      'menu_status' => $bind['menu_status']
  ], "`ID` = {$id}");
  
 }
 
 /**
  * Delete menu
  * 
  * @param integer $id
  * @param object $sanitizing
  */
 public function deleteMenu($id, $sanitizing)
 {
  $cleanId = $this->filteringId($sanitizing, $id, 'sql');
  $stmt = $this->delete("menu", "`ID` = {$cleanId}");
 }

 /**
  * Check menu id
  * 
  * @param integer $id
  * @param object $sanitizing
  * @return boolean
  */
 public function checkMenuId($id, $sanitizing)
 {
  
  $sql = "SELECT ID FROM menu WHERE ID = ?";
  
  $idsanitized = $this->filteringId($sanitizing, $id, 'sql');
  
  $this->setSQL($sql);
  
  $stmt = $this->checkCountValue([$idsanitized]);
  
  return($stmt > 0);
  
 }
 
 /**
  * Find menu sorted
  * 
  * @return number
  */
 protected static function findSortMenu()
 {
 
  $sql = "SELECT menu_sort FROM menu ORDER BY menu_sort DESC";
 
  $this->setSQL($sql);
  
  $field = $this->findColumn();
  
  $menu_sorted = $field->menu_sort + 1;
  
  return $menu_sorted;
  
 }
 
}