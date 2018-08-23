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
  
public function __construct()
{
  parent::__construct();
}

public function createConfig($bind)
{
  if(empty($bind['logo'])) {
		// insert into settings
		$stmt = $this->create('settings', [
			'app_url' => $bind['app_url'],
			'site_name' => $bind['site_name'],
			'meta_description' => $bind['meta_description'],
			'meta_keywords' => $bind['meta_keywords'],
			'email_address' => $bind['email_address'],
			'facebook' => $bind['facebook'],
			'twitter' => $bind['twitter'],
			'instagram' => $bind['instagram']
		]);

	} else {

		$stmt = $this->create('settings', [
			'app_url' => $bind['app_url'],
			'site_name' => $bind['site_name'],
			'meta_description' => $bind['meta_description'],
			'meta_keywords' => $bind['meta_keywords'],
			'logo' => $bind['logo'],
			'email_address' => $bind['email_address'],
			'facebook' => $bind['facebook'],
			'twitter' => $bind['twitter'],
			'instagram' => $bind['instagram']
		]);

	}
	
}

public function updateConfig($bind, $id)
{
  if(empty($bind['logo'])) {

	$stmt = $this->modify("settings", [
		'site_name' => $bind['site_name'],
		'meta_description' => $bind['meta_description'],
		'meta_keywords' => $bind['meta_keywords'], 
		'email_address' => $bind['email_address'],
		'facebook' => $bind['facebook'],
		'twitter' => $bind['twitter'],
		'instagram' => $bind['instagram']
	], "`ID` = {$id}");

  } else {
	
	$stmt = $this->modify("settings", [
		'site_name' => $bind['site_name'],
		'meta_description' => $bind['meta_description'],
		'meta_keywords' => $bind['meta_keywords'],
		'logo' => $bind['logo'], 
		'email_address' => $bind['email_address'],
		'facebook' => $bind['facebook'],
		'twitter' => $bind['twitter'],
		'instagram' => $bind['instagram']
	], "`ID` = {$id}");

  }

}

public function checkConfigId($id, $sanitize)
{
  $cleanId = $this->filteringId($sanitize, $id, 'sql');
  $sql = "SELECT ID FROM settings WHERE ID = ?";
  $this->setSQL($sql);
  $stmt = $this->checkCountValue([$cleanId]);
  return $stmt > 0;
}

public function checkToSetup()
{
	$sql = "SELECT ID FROM settings";
	$this->setSQL($sql);
	$stmt = $this->checkCountValue();
	return $stmt < 1;
}

public function findConfigs()
{
  $sql = "SELECT ID, app_key, app_url, site_name, 
	meta_description, meta_keywords, logo, 
	email_address, facebook, twitter, instagram
	FROM settings LIMIT 1";

	$this->setSQL($sql);

	$configs = $this->findAll();

	if (empty($configs)) return false;

	return $configs;
	
}

public function findConfig($id, $sanitize)
{
  
  $sql = "SELECT ID, app_key, app_url, site_name, meta_description, meta_keywords, 
		  logo, email_address, facebook, twitter, instagram
		  FROM settings WHERE ID = :ID ";
   
  $id_sanitized = $this->filteringId($sanitize, $id, 'sql');

  $this->setSQL($sql);

  $detailSetting = $this->findRow([':ID' => $id_sanitized]);

  if (empty($detailSetting)) return false;

  return $detailSetting;
  
}

}