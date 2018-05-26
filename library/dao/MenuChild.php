<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * Menu Child class extends Dao
 * insert, update, delete
 * and select records from menu_child table
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @copyright 2018 kartatopia.com
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
 
 public function findMenuChilds($position = null, $limit = null, $orderBy = 'ID')
 {
     if (is_null($position) && is_null($limit)) {
         
         $sql = "SELECT mc.ID, mc.menu_child_label, mc.menu_child_link, 
           mc.menu_id, mc.menu_sub_child, mc.menu_child_sort, 
           mc.menu_child_status, mp.menu_label
           FROM menu_child AS mc
           INNER JOIN  menu AS mp ON mc.menu_id = mp.ID
           ORDER BY $orderBy";
             
         $this->setSQL($sql);
         
         $menuChilds = $this->findAll();
         
     } else {
         
     
       $sql = "SELECT mc.ID, mc.menu_child_label, mc.menu_child_link, 
           mc.menu_id, mc.menu_sub_child, mc.menu_child_sort, 
           mc.menu_child_status, mp.menu_label
           FROM menu_child AS mc
           INNER JOIN  menu AS mp ON mc.menu_id = mp.ID
           ORDER BY " . $orderBy . " LIMIT :position, :limit";
   
       $this->setSQL($sql);
       $menuChilds = $this -> findAll([':position' => $position, ':limit' => $limit], PDO::FETCH_ASSOC);
   
    }
    
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
 
 public function updateMenuChild($id, $bind)
 {
   $stmt = $this->modify("menu_child", [
       'menu_child_label' => $bind['menu_child_label'],
       'menu_child_link'  => $bind['menu_child_link'],
       'menu_id' => $bind['menu_id'],
       'menu_sub_child' => $bind['menu_sub_child'],
       'menu_child_sort' => $bind['menu_child_sort']
   ], "`ID` = {$id}");
 }
 
 public function deleteMenuChild($id, $sanitize)
 {
   $id_sanitized = $this->filteringId($sanitize, $id, 'sql');
   $stmt = $this->delete("menu_child", "`ID` = {$id_sanitized}");    
 }
 
 public function isMenuChildExists($menu_child_label)
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
 
 public function setMenuChild($selected = null)
 {
     $option_selected = '';
     
     if (!is_null($selected)) {
         
       $option_selected = ' selected="selected"';
       
     }
     
     // get child menus
     $child_menus = $this->findMenuChilds();
     
     $html = array();
     $html[] = '<label for="sub_menu">Sub Menu</label>';
     $html[] = '<select class="form-control" name="menu_child">';
     $html[] = '<option value=0 selected>--Pilih Sub Menu--</option>';
     
     foreach ($child_menus as $child_menu) {

         if ((int)$selected == $child_menu -> ID) {
             $option_selected = ' selected="selected"';
         }
         
         $html[] = '<option value="'. $child_menu -> ID.'"'.$option_selected.'>' . $child_menu -> menu_child_label . '</option>';
         
         //clear out the selected option flag
         $option_selected = '';
     }
     
     $html[] = '</select>';
     
     return implode("\n", $html);
     
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