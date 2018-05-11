<?php

class MenuChild extends Dao
{

 public function __construct()
 {
    parent::__construct();
    
 }
 
 public function findMenuChilds($position, $limit, $orderBy = 'ID')
 {
   $sql = "SELECT mc.ID, mc.menu_child_label, mc.menu_child_link, 
           mc.menu_id, mc.menu_sub_child, mc.menu_child_sort, 
           mc.menu_child_status, mp.menu_label
           FROM menu_child AS mc
           INNER JOIN  menu AS mp ON mc.menu_id = mp.ID
           ORDER BY " . $orderBy . " LIMIT :position, :limit";
   
   $this->setSQL($sql);
   $menuChilds = $this -> findAll([':position' => $position, ':limit' => $limit], PDO::FETCH_ASSOC);
   
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
   $menuChildDetails = $this->findRow([$idsanitized], PDO::FETCH_ASSOC);
   
   if (empty($menuChildDetails)) return false;
   
   return $menuChildDetails;
   
 }
 
 public function addMenuChild($bind)
 {
  $menuChildSorted = self::findSortMenuChild();
  
  $stmt = $this->create("menu_child", [
      'menu_child_label' => $bind['menu_child_label'],
      'menu_child_link'  => $bind['menu_child_link'],
      'menu_id' => $bind['menu_id'],
      'menu_sub_child' => $bind['menu_sub_child'],
      'menu_child_sort' => $menuChildSorted,
      
  ]);
 }
 
 protected static function findSortMenuChild()
 {
   $sql = "SELECT menu_child_sort FROM menu_child ORDER BY menu_child_sort DESC";
   
   $this->setSQL($sql);
   
   $field = $this->findColumn();
   
   $menu_child_sorted = $field->menu_child_sort + 1;
   
   return $menu_child_sorted;
   
 }
}