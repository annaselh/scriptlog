<?php if (!defined('SCRIPTLOG')) die("Direct Access Not Allowed!");
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
  
  public function findThemes($position, $limit, $fetchMode = null, $orderBy = "ID")
  {
    $sql = "SELECT ID, theme_title, theme_desc, theme_designer, theme_directory, 
                theme_status FROM themes ORDER BY '$orderBy'
           LIMIT :position, :limit";
    
    $this->setSQL($sql);
    
    if (is_null($fetchMode)) {
        
        $themes = $this->findAll([':position' => $position, ':limit' => $limit]);
        
    } else {
        
        $themes = $this->findAll([':position' => $position, ':limit' => $limit], $fetchMode);
        
    }
    
    if (empty($themes)) return false;
    
    return $themes;
    
  }
  
  public function findTheme($themeId, $sanitize, $fetchMode = null)
  {
    $cleanId = $this->filteringId($sanitize, $themeId, 'sql');
    
    $sql = "SELECT ID, theme_title, theme_desc, theme_designer, 
                theme_directory, theme_status 
            FROM themes WHERE ID = ?";
    
    $this->setSQL($sql);
    
    if (is_null($fetchMode)) {
        
       $themeDetails = $this->findRow([$cleanId]);
       
    } else {
        
       $themeDetails = $this->findRow([$cleanId], $fetchMode);
       
    }
    
    if (empty($themeDetails)) return false;
    
    return $themeDetails;
    
  }
  
  public function addTheme($accessLevel, $bind)
  {
    if ($accessLevel == 'Administrator') {
    
        $stmt = $this->create("themes", [
            'theme_title' => $bind['theme_title'],
            'theme_desc' => $bind['theme_desc'],
            'theme_designer' => $bind['theme_designer'],
            'theme_directory' => $bind['theme_directory'],
            'theme_status' => $bind['theme_status']
        ]);
        
    }
    
    return false;
    
  }
  
  public function updateTheme($accessLevel, $sanitize, $bind, $themeId)
  {
      $cleanId = $this->filteringId($sanitize, $themeId, 'sql');
      if ($accessLevel == 'Administrator') {
          $bind = [
              'theme_title' => $bind['theme_title'],
              'theme_desc' => $bind['theme_desc'],
              'theme_designer' => $bind['theme_designer'],
              'theme_director' => $bind['theme_directory'],
              'theme_status' => $bind['theme_status']
          ];
          
          $stmt = $this->modify("themes", $params, "`ID` = {$cleanId}");
          
      }
      
      return false;
      
  }
  
  public function deleteTheme($accessLevel, $sanitize, $themeId)
  {
    $cleanId = $this->filteringId($sanitize, $themeId, 'sql');
   
    if ($accessLevel == 'Administrator') {
        
      $stmt = $this->delete("themes", "`ID` = {$cleanId}");
        
    }
    
    return false;
    
  }
  
  public function activateTheme($accessLevel, $sanitize, $themeId)
  {
    $cleanId = $this->filteringId($sanitize, $themeId, 'sql');
    
    if ($accessLevel == 'Administrator') {
    
      // activate theme
      $stmt = $this->modify("themes", [
          'theme_status' => 'Y'
      ], "`ID` = {$cleanId}");
        
      // non-activate the other table
      $stmt2 = $this->modify("themes", [
          'theme_status' => 'N'
      ], "`ID` != {$cleanId}");
      
    }
    
    return false;
    
  }
  
  public function isThemeExists($theme_title)
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
  
  public function loadTheme($theme_status)
  {
    $sql = "SELECT ID, theme_title, theme_desc, theme_designer, theme_directory, 
            theme_status FROM themes WHERE theme_status = ?";
    
    $this->setSQL($sql);
    $stmt = $this->findRow([$theme_status], PDO::FETCH_ASSOC);
  }
  
}