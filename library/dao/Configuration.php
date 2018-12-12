<?php 
/**
 * Configuration Class
 * 
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class Configuration extends Dao
{

/**
 * 
 */
public function __construct()
{

  parent::__construct();

}

/**
 * 
 */
public function createConfig($bind)
{
  // insert into settings
  $stmt = $this->create('settings', [
	'setting_name' => $bind['setting_name'],
	'setting_value' => $bind['setting_value'],
	'setting_desc' => $bind['setting_desc']
  ]);

}

/**
 * 
 */
public function updateConfig($sanitize, $bind, $ID)
{
	
  $cleanId = $this->filteringId($sanitize, $ID, 'sql');

  $stmt = $this->modify("settings", [
	  'setting_name' => $bind['setting_name'],
	  'setting_value' => $bind['setting_value'],
	  'setting_desc' => $bind['setting_desc']
  ], "`ID` = {$cleanId}");

}

public function deleteConfig($ID, $sanitize)
{
  $cleanId = $this->filteringId($sanitize, $ID, 'sql');

  $stmt = $this->deleteRecord("settings", "ID = $cleanId");

}

/**
 * 
 */
public function findConfigs($orderBy = 'ID')
{
  $sql = "SELECT ID, setting_name, setting_value, setting_desc
	FROM settings ORDER BY :orderBy DESC";

	$this->setSQL($sql);

	$configs = $this->findAll([':orderBy' => $orderBy]);

	if (empty($configs)) return false;

	return $configs;
	
}

/**
 * 
 */
public function findConfig($id, $sanitize)
{
  
  $sql = "SELECT ID, setting_name, setting_value, setting_desc
		      FROM settings WHERE ID = :ID ";
   
  $id_sanitized = $this->filteringId($sanitize, $id, 'sql');

  $this->setSQL($sql);

  $detailSetting = $this->findRow([':ID' => $id_sanitized]);

  if (empty($detailSetting)) return false;

  return $detailSetting;
  
}

/**
 * 
 */
public function checkConfigId($id, $sanitize)
{
  $cleanId = $this->filteringId($sanitize, $id, 'sql');
  $sql = "SELECT ID FROM settings WHERE ID = ?";
  $this->setSQL($sql);
  $stmt = $this->checkCountValue([$cleanId]);
  return $stmt > 0;
}

/**
 * 
 */
public function checkToSetup()
{
	$sql = "SELECT ID FROM settings";
	$this->setSQL($sql);
	$stmt = $this->checkCountValue();
	return $stmt < 1;
}

/**
 * 
 */
public function totalConfigRecords($data = null)
{
  $sql = "SELECT ID FROM settings";
  $this->setSQL($sql);
  return $this->checkCountValue($data);
}

}