<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
/**
 * class Category extends Model
 * Interacting with database to insert, update, 
 * delete and select records from table category 
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @copyright 2018 kartatopia.com
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */

class Category extends Model
{
  
  /**
   * overide parent constructor
   */
  public function __construct()
  {
	
	parent::__construct();
	
  }
  
  /**
   * Insert a new records
   * 
   * @method createCategory
   * @param string $title
   * @param string $slug
   */
  public function createCategory($title, $slug)
  {
	$sql = "INSERT INTO category(category_title, category_slug)VALUES(?, ?)";
		
	$data = array($title, $slug);
		
	$stmt = $this->statementHandle($sql,$data);
		
	return $this->lastId();
  }

  /**
   * Update an existing records
   * 
   * @param string $title
   * @param string $slug
   * @param string $status
   * @param integer $ID
   */
  public function updateCategory($title, $slug, $status, $ID)
  {
	$sql = "UPDATE category SET category_title = ?, category_slug = ?, status = ?
				WHERE ID = ?";
		
	$data = array($title, $slug, $status, $ID);
		
	$stmt = $this->statementHandle($sql, $data);
	
  }

  /**
   * Delete an existing records
   * 
   * @param integer $ID
   * @param string $sanitizing
   */
  public function deleteCategoryById($ID, $sanitizing)
  {  	
  	$cleanCategoryId = $this->filteringId($sanitizing, $ID, 'sql');
  	
	$sql = "DELETE FROM category WHERE ID = ?";
	  
	$data = array($cleanCategoryId);
	  
	$stmt = $this->statementHandle($sql, $data);
	  
 }
	
 public function findCategories($position = NULL, $limit = NULL)
 {
 
  try {
  	
   $categories = array();
      
  	if ((!is_null($position)) && (!is_null($limit))) {
  		
  		$sql = "SELECT ID, category_title, category_slug, status
				FROM category 
                ORDER BY category_title
				DESC LIMIT :position, :limit";
  		
  		$stmt = $this->dbc->prepare($sql);
  		
  		$stmt -> bindParam(":position", $position, PDO::PARAM_INT);
  		$stmt -> bindParam(":limit", $limit, PDO::PARAM_INT);
  		
  		$stmt -> execute();
  		
  		foreach ($stmt -> fetchAll() as $row) {
  			$categories[] = $row;
  		}
  		
  		$numbers = "SELECT ID FROM category";
  		$stmt = $this->dbc->query($numbers);
  		$totalCategories = $stmt -> rowCount();
  		
  		return(array("results" => $categories, "totalCategories" => $totalCategories));
  		
  	} else {
  		
  		$sql = "SELECT ID, category_title 
               FROM category ORDER BY category_title";
  		
  		$stmt = $this->dbc->query($sql);
  			
  		while ($row = $stmt -> fetch()) {
  		    
  		    $categories[] = $row;
  		    
  		}
  		
  		return $categories;
  		
  	}
  	
  } catch (PDOException $e) {
  	
  	$this->closeDbConnection();
  	
  	$this->error = LogError::newMessage($e);
  	$this->error = LogError::customErrorMessage();
  	
  }
	
 }
	
 public function findCategory($ID, $sanitizing)
 {

 $sql = "SELECT ID, category_title, category_slug, status
		FROM category WHERE ID = ?";

 $id_sanitized = $this->filteringId($sanitizing, $ID, 'sql');
 
 $data = array($id_sanitized);
		
 $stmt = $this->statementHandle($sql, $data);
		
 return $stmt -> fetch();
		
 }

 public function findCategoryBySlug($slug, $sanitize)
 {
  $sql = "SELECT ID, category_title
          FROM category WHERE category_slug = :category_slug AND status = 'Y'";
  
  $slug_sanitized = $this->filteringId($sanitize, $slug, 'xss');
  
  $data = array(':category_slug' => $slug_sanitized);
  
  $stmt = $this->statementHandle($sql, $data);
  
  return $stmt -> fetch();
  
 }
 
 public function getPostCategory($categoryId, $postId)
 {
     $sql = "SELECT ID FROM post_category 
             WHERE ID = :ID AND postID = :postID";
     $stmt = $this->dbc->prepare($sql);
     $stmt -> execute(array(':ID' => $categoryId, ':postID' => $postId));
     return $stmt -> fetch();
 }
 
 public function setCategoryChecked($postId = '', $checked = NULL)
 {
   	  	
 $checked = "";
     
 if (is_null($checked)) {
     $checked="checked='checked'";
 }
      
 $html = array();
 
 $html[] = '<div class="form-group">';
 $html[] = '<label>Category : </label>';

 $items = $this->findCategories();
 
 if (empty($postId)) {
       
    foreach ($items as $i => $item) {
    
      if (isset($_POST['catID'])) {
          
          if (in_array($item['catID'], $_POST['catID'])) {
              
              $checked="checked='checked'";
          
          } else {
              
              $checked = null;
              
          }
          
      }
    
      $html[] = '<label class="checkbox-inline">';
      $html[] = '<input type="checkbox" name="catID[]" value="'.$item['ID'].'"'.$checked.'>'.$item['category_title'];
      $html[] = '</label>';
      
    }
    
 } else {
     
     foreach ($items as $i => $item) {
         
      $post_category = $this->getPostCategory($item['ID'], $postId);
         
      if ($post_category['ID'] == $item['ID']) {
        
        $checked="checked='checked'";
      
      } else {
       
        $checked = null;
      }
         
         $html[] = '<label class="checkbox-inline">';
         $html[] = '<input type="checkbox" name="catID[]" value="'.$item['ID'].'"'.$checked.'>'.$item['category_title'];
         $html[] = '</label>';
         
     }
     
 }
 
 if (empty($item['ID'])) {
     
     $html[] = '<label class="checkbox-inline">';
     $html[] = '<input type="checkbox" name="catID" value="0" checked>Uncategorized';
     $html[] = '</label>';
     
 }
 
  $html[] = '</div>';
 
  return implode("\n", $html);
 
 }
 
 public function checkCategoryId($id, $sanitizing)
 {
 	
 	$sql = "SELECT ID FROM category WHERE ID = ?";
 	
 	$cleanUpId = $this->filteringId($sanitizing, $id, 'sql');
 	
 	$stmt = $this->dbc->prepare($sql);
 	
 	$stmt -> bindValue(1, $cleanUpId);
 	
 	try {
 		
 		$stmt -> execute();
 		$rows = $stmt -> rowCount();
 		
 		if ($rows > 0) {
 			
 			return true;
 			
 		} else {
 			
 			return false;
 			
 		}
 		
 	} catch (PDOException $e) {
 		
 		$this->closeDbConnection();
 		
 		$this->error = LogError::newMessage($e);
 		$this->error = LogError::customErrorMessage();
 		
 	}
 	
 }
	
}