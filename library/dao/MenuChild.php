<?php 
/**
 * Menu Child class extends Dao
 * insert, update, delete
 * and select records from menu_child table
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class MenuChild extends Dao
{

 public function __construct()
 {
    parent::__construct();
    
 }
 
 public function findMenuChilds($orderBy = 'ID')
 {
    
    $sql = "SELECT mc.ID, mc.menu_child_label, mc.menu_child_link, 
           mc.menu_id, mc.menu_sub_child, mc.menu_child_sort, 
           mc.menu_child_status, mp.menu_label
           FROM menu_child AS mc
           INNER JOIN  menu AS mp ON mc.menu_id = mp.ID
           ORDER BY :orderBy DESC";
             
    $this->setSQL($sql);
         
    $menuChilds = $this->findAll([':orderBy' => $orderBy]);
         
    if (empty($menuChilds)) return false;
   
    return $menuChilds;
   
 }
 
 public function findMenuChild($id, $sanitize)
 {
   $sql = "SELECT mc.ID, mc.menu_child_label, mc.menu_child_link, 
           mc.menu_id, mc.menu_sub_child, mc.menu_child_sort, 
           mc.menu_child_status, mp.menu_label
           FROM menu_child AS mc
           INNER JOIN  menu AS mp ON mc.menu_id = mp.ID
           WHERE mc.ID = ?";
   
   $idsanitized = $this->filteringId($sanitize, $id, 'sql');
   
   $this->setSQL($sql);
   
   $menuChildDetails = $this->findRow([$idsanitized]);
   
   if (empty($menuChildDetails)) return false;
   
   return $menuChildDetails;
   
 }
 
 public function findAscMenu($id, $sanitize)
 {
   $sql = "SELECT menu_id FROM menu_child WHERE ID = ?";
   $idsanitized = $this->filteringId($sanitize, $id, 'sql');
   $this->setSQL($sql);
   $ascendentMenu = $this->findColumn([$idsanitized]);
   if (empty($ascendentMenu)) return false;
   return $ascendentMenu;
 }

 public function insertMenuChild($bind)
 {
     
  $menuChildSorted = $this->findSortMenuChild();
  $stmt = $this->create("menu_child", [
      'menu_child_label' => $bind['menu_child_label'],
      'menu_child_link'  => $bind['menu_child_link'],
      'menu_id' => $bind['menu_id'],
      'menu_sub_child' => $bind['menu_sub_child'],
      'menu_child_sort' => $menuChildSorted
  ]);
  
 }
 
 public function updateMenuChild($sanitize, $bind, $ID)
 {
   
   $cleanId = $this->filteringId($sanitize, $ID, 'sql');
   $stmt = $this->modify("menu_child", [
       'menu_child_label' => $bind['menu_child_label'],
       'menu_child_link'  => $bind['menu_child_link'],
       'menu_id' => $bind['menu_id'],
       'menu_sub_child' => $bind['menu_sub_child'],
       'menu_child_sort' => $bind['menu_child_sort'],
       'menu_child_status' => $bind['menu_child_status']
   ], "`ID` = {$cleanId}");

 }
 
 public function activateMenuChild($id, $sanitize)
 {
   $idsanitized = $this->filteringId($sanitize, $id, 'sql');
   $stmt = $this->modify("menu_child", ['menu_child_status' => 'Y'], "`ID` => {$idsanitized}");
 }

 public function deactivateMenuChild($id, $sanitize)
 {
   $idsanitized = $this->filteringId($sanitize, $id, 'sql');
   $stmt = $this->modify("menu_child", ['menu_child_status' => 'N'], "`ID` => {$idsanitized}");
 }

 public function deleteMenuChild($id, $sanitize)
 {
   $id_sanitized = $this->filteringId($sanitize, $id, 'sql');
   $stmt = $this->deleteRecord("menu_child", "`ID` = {$id_sanitized}");    
 }
 
 public function menuChildExists($menu_child_label)
 {
   $sql = "SELECT COUNT(ID) FROM menu_child WHERE menu_child_label = ?";
   
   $this->setSQL($sql);
   
   $stmt = $this->findColumn([$menu_child_label]);
   
   if ($stmt == 1) {
       
    return true;

   } else {
       
    return false;

   }
   
 }
 
 public function checkMenuChildId($id, $sanitizing)
 {
     $sql = "SELECT ID FROM menu_child WHERE ID = ?";
     
     $idsanitized = $this->filteringId($sanitizing, $id, 'sql');
     
     $this->setSQL($sql);
     
     $stmt = $this->checkCountValue([$idsanitized]);
     
     return($stmt > 0);
 }
 
 public function dropDownMenuChild($selected = '') 
 {
   $name = 'child';

   $sub_menus = $this->findMenuChilds('menu_child_label');

   if ($selected != '') {
     $selected = $selected;
   }

   return dropdown($name, $sub_menus, $selected);

 }

 private function findSortMenuChild()
 {
   $sql = "SELECT menu_child_sort FROM menu_child ORDER BY menu_child_sort DESC";
   
   $this->setSQL($sql);
   
   $field = $this->findColumn();
   
   $menu_child_sorted = $field->menu_child_sort + 1;
   
   return $menu_child_sorted;
   
 }
 
 public function totalMenuChildRecords($data = null)
 {
   $sql = "SELECT ID FROM menu_child";
   $this->setSQL($sql);
   return $this->checkCountValue($data);  
 }
 
}