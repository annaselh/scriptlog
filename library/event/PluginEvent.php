<?php
/**
 * PluginEvent Class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class PluginEvent
{
  /**
   * Plugin's ID
   * 
   * @var integer
   */
  private $plugin_id;
  
  /**
   * Plugin's name
   * 
   * @var string
   */
  private $name;
  
  /**
   * Plugin's link
   * 
   * @var string
   */
  private $link;

  /**
   * Plugin's description
   * 
   * @var string
   */
  private $description;
  
  /**
   * Plugin's status
   * 
   * @var string
   */
  private $status;
  
  /**
   * Plugin's level
   * 
   * @var string
   */
  private $level;
  
  /**
   * Sort
   * @var string
   */
  private $sort;

  private $pluginDao;

  private $validator;

  private $sanitize;
  
  /**
   * Constructor
   * 
   * @param object $pluginDao
   * @param object $validator
   * @param object $sanitize
   */
  public function __construct(Plugin $pluginDao, FormValidator $validator, Sanitize $sanitize)
  {
    $this->pluginDao = $pluginDao;
    $this->validator = $validator;
    $this->sanitize = $sanitize;
  }

  public function setPluginId($pluginId)
  {
    $this->plugin_id = $pluginId;
  }

  public function setPluginName($name)
  {
    $this->name = $name;
  }

  public function setPluginLink($link)
  {
    $this->link = $link;
  }

  public function setPluginDescription($description)
  {
    $this->description = $description;
  }

  public function setPluginStatus($status)
  {
    $this->status = $status;
  }

  public function setPluginLevel($level)
  {
    $this->level = $level;
  }

  public function setPluginSort($sort)
  {
    $this->sort = $sort;
  }

  public function grabPlugins($orderBy = 'ID')
  {
    return $this->pluginDao->getPlugins($orderBy);
  }

  public function grabPlugin($id)
  {
    return $this->pluginDao->getPlugin($id);
  }

  public function insertPlugin()
  {
    $this->validator->sanitize($this->name, 'string');
    $this->validator->sanitize($this->link, 'string');
    $this->validator->sanitize($this->description, 'string');

    $getSort = "SELECT plugin_sort FROM plugin ORDER BY plugin_sort DESC";
     
    $this->setSQL($getSort);
     
    $rows = $this->findColumn();
     
    $plugin_sorted = $rows['plugin_sort'] + 1;

    if(empty($this->link)) $this->link = "#";

    return $this->pluginDao->addPlugin([
      'plugin_name' => $this->name,
      'plugin_link' => $this->link,
      'plugin_desc' => $this->description,
      'plugin_level' => $this->level,
      'plugin_sort' => $plugin_sorted
    ]);

  }

  public function modifyPlugin()
  {
    
    $this->validator->sanitize($this->plugin_id, 'int');
    $this->validator->sanitize($this->name, 'string');
    $this->validator->sanitize($this->link, 'string');
    $this->validator->sanitize($this->description, 'string');

    return $this->pluginDao->updatePlugin([
      'plugin_name' => $this->name,
      'plugin_link' => $this->link,
    ], $this->plugin_id);

  }

  
  public function removePlugin()
  {

  }
}