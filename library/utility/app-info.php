<?php
/**
 * App Info Function
 * 
 * @return array[][]
 */
function app_info()
{
 $configurations = new Configuration();
 $app_info = array();
 $results = $configurations -> findConfigs();

 if (is_array($results)) {

  foreach ($results as $data) {

    $app_info['ID'] = $data['ID'];
    $app_info['app_key'] = $data['app_key'];
    $app_info['app_url'] = $data['app_url'];
    $app_info['site_name'] = $data['site_name'];
    $app_info['meta_description'] = $data['meta_description'];
    $app_info['meta_keywords'] = $data['meta_keywords'];
    $app_info['logo'] = $data['logo'];
    $app_info['email_address'] = $data['email_address'];
    $app_info['facebook_url'] = $data['facebook'];
    $app_info['twitter_url'] = $data['twitter'];
    $app_info['instagram_url'] = $data['instagram']; 
    
  }
      
 }
 
 return $app_info;
 
}