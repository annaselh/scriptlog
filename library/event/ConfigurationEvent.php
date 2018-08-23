<?php
/**
 * ConfigurationEvent Class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class ConfigurationEvent
{
  /**
   * config's ID
   * 
   * @var integer
   */
  private $config_id;
  
  /**
   * App Key
   * 
   * @var string
   */
  private $app_key;
  
  /**
   * App URL
   * 
   * @var string
   */
  private $app_url;
  
  /**
   * Site title
   * 
   * @var string
   */
  private $site_title;

  /**
   * Meta Description
   * 
   * @var meta_description
   */
  private $meta_description;

  /**
   * Meta Keywords
   * 
   * @var string
   */
  private $meta_keywords;

  /**
   * Logo
   * 
   * @var string
   */
  private $logo;

  /**
   * E-mail
   * 
   * @var E-mail
   */
  private $email;

  /**
   * Facebook Account URL
   * 
   * @var string
   */
  private $facebook;

  /**
   * Twitter Account URL
   * 
   * @var string
   */
  private $twitter;
  
  /**
   * Instagram
   * 
   * @var string
   */
  private $instagram;

  /**
   * Configuration Dao
   * @var object
   */
  private $configDao;

  private $validator;

  private $sanitize;

  public function __construct(Configuration $configDao, FormValidator $validator, Sanitize $sanitize)
  {
    $this->configDao = $configDao;
    $this->validator = $validator;
    $this->sanitize = $sanitize;
  }

  public function setConfigId($configId)
  {
    $this->config_id = $configId;
  }

  public function setAppKey($appKey)
  {
    $this->app_key = $appKey;
  }

  public function setAppUrl($appUrl)
  {
    $this->app_url = $appUrl;
  }

  public function setSiteName($siteName)
  {
    $this->site_title = $siteName;
  }

  public function setMetaDesc($description)
  {
    $this->meta_description = $description;
  }

  public function setMetaKey($keyword)
  {
    $this->meta_keywords = $keyword;
  }

  public function setLogo($image)
  {
    $this->logo = $image;
  }

  public function setEmailAddress($email_address)
  {
    $this->email = $email_address;
  }

  public function setFacebook($facebook)
  {
    $this->facebook = $facebook;
  }

  public function setTwitter($twitter)
  {
    $this->twitter = $twitter;
  }

  public function setInstagram($instagram)
  {
    $this->instagram = $instagram;
  }

  public function grabSettings()
  {
    return $this->configDao->findConfigs();
  }

  public function grabSetting($id)
  {
    return $this->configDao->findConfig($id, $this->sanitize);
  }
  
  public function createSetting()
  {
    $uploadPath = __DIR__ . '/../../public/files/pictures/';
    $image_uploader = new ImageUploader('image', $uploadPath);

    $this->validator->sanitize($this->app_url. 'string');
    $this->validator->sanitize($this->site_title, 'string');
    $this->validator->sanitize($this->meta_description, 'string');
    $this->validator->sanitize($this->meta_keywords, 'string');
  
    if ($image_uploader -> isImageUploaded()) {
      
       return $this->configDao->createConfig([
        'app_url' => $this->app_url,
        'site_name' => $this->site_title,
        'meta_description' => $this->meta_description,
        'meta_keywords' => $this->meta_keywords,
        'email_address' => $this->email,
        'facebook' => $this->facebook,
        'twitter' => $this->twitter,
        'instagram' => $this->instagram
       ]);

    } else {

      $newFileName = $image_uploader -> renameImage();
      $uploadImageLogo = $image_uploader -> uploadImage('logo', $newFileName, 320, 215, 'crop');

      return $this->configDao->createConfig([
        'app_url' => $this->app_url,
        'site_name' => $this->site_title,
        'meta_description' => $this->meta_description,
        'meta_keywords' => $this->meta_keywords,
        'logo' => $newFileName,
        'email_address' => $this->email,
        'facebook' => $this->facebook,
        'twitter' => $this->twitter,
        'instagram' => $this->instagram
      ]);

    }
  }

  public function modifySetting()
  {
    $uploadPath = __DIR__ . '/../../public/files/pictures/';
    $image_uploader = new ImageUploader('image', $uploadPath);

    $this->validator->sanitize($this->app_url. 'string');
    $this->validator->sanitize($this->site_title, 'string');
    $this->validator->sanitize($this->meta_description, 'string');
    $this->validator->sanitize($this->meta_keywords, 'string');
  
    if ($image_uploader -> isImageUploaded()) {
      
      return $this->configDao->updateConfig([
        'app_url' => $this->app_url,
        'site_name' => $this->site_title,
        'meta_description' => $this->meta_description,
        'meta_keywords' => $this->meta_keywords,
        'email_address' => $this->email,
        'facebook' => $this->facebook,
        'twitter' => $this->twitter,
        'instagram' => $this->instagram
      ], $this->config_id);

    } else {

       $newFileName = $image_uploader -> renameImage();
       $uploadImageLogo = $image_uploader -> uploadImage('logo', $newFileName, 320, 251, 'crop');
       
       return $this->configDao->updateConfig([
        'app_url' => $this->app_url,
        'site_name' => $this->site_title,
        'meta_description' => $this->meta_description,
        'meta_keywords' => $this->meta_keywords,
        'logo' => $newFileName,
        'email_address' => $this->email,
        'facebook' => $this->facebook,
        'twitter' => $this->twitter,
        'instagram' => $this->instagram
       ], $this->config_id);

    }

  }

}