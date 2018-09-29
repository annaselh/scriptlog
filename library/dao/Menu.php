<?php 
/**
 * Menu class extends Dao
 * insert, update, delete
 * and select records from menu table
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Menu extends Dao
{
 
/**
 * Constructor
 * 
 */
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
 public function findMenus($orderBy = 'ID')
 {
    $sql = "SELECT ID, menu_label, menu_link, menu_sort, menu_status
    FROM menu ORDER BY  :orderBy DESC";

    $this->setSQL($sql);

    $menus = $this->findAll([':orderBy' => $orderBy]);

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
  *
  */
 public function findMenu($menuId, $sanitizing)
 {
     
     $sql = "SELECT ID, menu_label, menu_link, menu_sort, menu_status
             FROM menu WHERE ID = ?";
     
     $idsanitized = $this->filteringId($sanitizing, $menuId, 'sql');
     
     $this->setSQL($sql);
     
     $menuDetails = $this->findRow([$idsanitized]);
     
     if (empty($menuDetails)) return false;
     
     return $menuDetails;
     
 }
 
 /**
  * Insert new menu
  * 
  * @param array $bind
  */
 public function insertMenu($bind)
 {
     
   $menuSorted = $this->findSortMenu();
   $stmt = $this->create("menu", [
       'menu_label' => $bind['menu_label'],
       'menu_link' => $bind['menu_link'],
       'menu_sort' => $menuSorted
   ]);
   
   $menu_id = $this->lastId();

   $getLink = "SELECT ID, menu_link FROM menu WHERE ID = ?";

   $this->setSQL($getLink);

   $menu_link = $this->findColumn([$menu_id]);

   if (empty($menu_link['menu_link'])) {

      $stmt2 = $this->modify("menu", ['menu_link' => '#'], "`ID` = {$menu_link['ID']}");
   }

 }
 
 /**
  * Update menu
  * 
  * @param integer $id
  * @param array $bind
  */
 public function updateMenu($sanitize, $bind, $ID)
 {
  
  $cleanId = $this->filteringId($sanitize, $ID, 'sql');
  $stmt = $this->modify("menu", [
      'menu_label' => $bind['menu_label'],
      'menu_link' => $bind['menu_link'],
      'menu_sort' => $bind['menu_sort'],
      'menu_status' => $bind['menu_status']
  ], "`ID` = {$cleanId}");
  
 }
 
 /**
  * Activate menu
  * 
  * @param integer $id
  * @param object $sanitize
  *
  */
 public function activateMenu($id, $sanitize)
 {
   $idsanitized = $this->filteringId($sanitize, $id, 'sql');
   $stmt = $this->modify("menu", ['menu_status' => 'Y'], "`ID` => {$idsanitized}");
 }

 /**
  * Deactivate menu
  *
  * @param integer $id
  * @param object $sanitize
  *
  */
 public function deactivateMenu($id, $sanitize)
 {
  $idsanitized = $this->filteringId($sanitize, $id, 'sql');
  $stmt = $this->modify("menu", ['menu_status' => 'N'], "`ID` => {$idsanitized}");
 }

 /**
  * Delete menu
  * 
  * @param integer $id
  * @param object $sanitizing
  *
  */
 public function deleteMenu($id, $sanitize)
 {
  $cleanId = $this->filteringId($sanitize, $id, 'sql');
  $stmt = $this->deleteRecord("menu", "`ID` = {$cleanId}");
 }

 /**
  * Check menu id
  * 
  * @param integer $id
  * @param object $sanitizing
  * @return boolean
  *
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
  * @method menuExists()
  * @param string $menu_label
  *
  */
 public function menuExists($menu_label)
 {
   $sql = "SELECT COUNT(ID) FROM menu WHERE menu_label = ?";
   $this->setSQL($sql);
   $stmt = $this->findColumn([$menu_label]);

   if ($stmt == 1) {

      return true;

   } else {

      return true;

   }

 }

 /**
  * Drop down menu
  *
  * @param string $selected
  *
  */
 public function dropDownMenu($selected = '')
 {
   $name = 'parent';

   $menus = $this->findMenus('menu_label');

   if ($selected != '') {
      $selected = $selected;
   }

   return dropdown($name, $menus, $selected);

 }

 public function totalMenuRecords($data = null)
 {
   $sql = "SELECT ID FROM menu";
   $this->setSQL($sql);
   return $this->checkCountValue($data);
 }
 
 /**
  * Find menu sorted
  * 
  * @return number
  */
 private function findSortMenu()
 {
 
  $sql = "SELECT menu_sort FROM menu ORDER BY menu_sort DESC";
 
  $this->setSQL($sql);
  
  $field = $this->findColumn();
  
  $menu_sorted = $field->menu_sort + 1;
  
  return $menu_sorted;
  
 }

}