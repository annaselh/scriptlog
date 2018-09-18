<?php 
/**
 * Theme class extends Dao
 * insert, update, delete
 * and select records from users table
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Theme extends Dao
{
  public function __construct()
  {
    
    parent::__construct();
    
  }
  
  /**
   * findThemes function
   * Retrieve all themes records 
   * ordered by ID (default)
   * 
   * @method mixed findThemes()
   * @param integer $orderBy
   * @return mixed
   * 
   */
  public function findThemes($orderBy = "ID")
  {
    $sql = "SELECT ID, theme_title, theme_desc, theme_designer, theme_directory, 
            theme_status FROM themes ORDER BY :orderBy DESC";
    
    $this->setSQL($sql);
    
    $themes = $this->findAll([':orderBy' => $orderBy]);

    if (empty($themes)) return false;
    
    return $themes;
    
  }
  
  /**
   * findTheme function
   * Retrieve single theme record
   * based on ID
   * 
   * @method mixed findTheme()
   * @param integer $id
   * @param object $sanitize
   * @return mixed
   * 
   */
  public function findTheme($id, $sanitize)
  {
    
    $sql = "SELECT ID, theme_title, theme_desc, theme_designer, 
                theme_directory, theme_status 
            FROM themes WHERE ID = ?";
    
    $idsanitized = $this->filteringId($sanitize, $id, 'sql');

    $this->setSQL($sql);
    
    $themeDetails = $this->findRow([$idsanitized]);
    
    if (empty($themeDetails)) return false;

    return $themeDetails;

  }

  /**
   * addTheme function
   * Insert new theme record into theme table
   * 
   * @method mixed addTheme()
   * @param array $bind
   * 
   */
  public function insertTheme($bind)
  {
    $stmt = $this->create("themes", [
      'theme_title' => $bind['theme_title'],
      'theme_desc' => $bind['theme_desc'],
      'theme_designer' => $bind['theme_designer'],
      'theme_directory' => $bind['theme_directory']
   ]);
  
  }
  
  /**
   * updateTheme function
   * update an existing theme record
   * 
   * @method mixed updateTheme()
   * @param integer $id
   * @param array $bind
   * 
   */
  public function updateTheme($bind, $id)
  {
     $stmt = $this->modify("plugin", [
       'theme_title' => $bind['theme_title'],
       'theme_desc' => $bind['theme_desc'],
       'theme_designer' => $bind['theme_designer'],
       'theme_directory' => $bind['theme_directory'],
       'theme_status' => $bind['theme_status']
     ], "`ID` = {$id}");

  }
  
  /**
   * deleteTheme function
   * Remove theme record from theme table
   * 
   * @method mixed deleteTheme()
   * @param integer $id
   * @param object $sanitize
   * 
   */
  public function deleteTheme($id, $sanitize)
  {
    $idsanitized = $this->filteringId($sanitize, $id, 'sql');
    $stmt = $this->delete("themes", "`ID` = {$idsanitized}");
  }
  
  /**
   * Check theme function
   * checking ID theme
   * 
   * @method mixed checkThemeId()
   * @param integer $id
   * @param object $sanitize
   * @return boolean
   * 
   */
  public function checkThemeId($id, $sanitize)
  {
    $idsanitized = $this->filteringId($sanitize, $id, 'sql');
    $sql = "SELECT ID FROM themes WHERE ID = ?";
    $this->setSQL($sql);
    $stmt = $this->checkCountValue([$idsanitized]);
    return($stmt > 0);
  }

  /**
   * Activate theme function
   * 
   * @method mixed activateTheme()
   * @param integer $id
   * @param object $
   * 
   */
  public function activateTheme($id, $sanitize)
  {
    $idsanitized = $this->filteringId($sanitize, $id, 'sql');
    
    // activate theme
    $stmt = $this->modify("themes", [
      'theme_status' => 'Y'
    ], "`ID` = {$idsanitized}");
    
  // non-activate the other table
    $stmt2 = $this->modify("themes", [
      'theme_status' => 'N'
    ], "`ID` != {$idsanitized}");
  
  }
  
  /**
   * Total theme record function
   * 
   * @method totalThemeRecords()
   * @param array $data
   * @return boolean
   * 
   */
  public function totalThemeRecords($data = null)
  {
    $sql = "SELECT ID FROM themes";
    $this->setSQL($sql);
    return $this->checkCountValue($data);
  }

  /**
   * Theme exists function
   * is theme exists or not
   * 
   * @method themeExists()
   * @param string $theme_title
   * @return boolean
   * 
   */
  public function themeExists($theme_title)
  {
    $sql = "SELECT COUNT(ID) FROM themes WHERE theme_title = ?";
    $this->setSQL($sql);
    $stmt = $this->findColumn([$theme_title]);
    
    if ($stmt == 1) {
       
       return true;
    
    } else {
       
       return false;
        
    }
    
  }

  /**
   * Load theme function
   * 
   * @method loadTheme()
   * @param string $theme_status
   * @return array
   * 
   */
  public function loadTheme($theme_status)
  {
    $sql = "SELECT ID, theme_title, theme_desc, theme_designer, theme_directory, 
            theme_status FROM themes WHERE theme_status = ?";
    $this->setSQL($sql);
    $loadTheme = $this->findRow([$theme_status]);

    if (empty($loadTheme)) return false;

    return $loadTheme;
    
  }
  
}