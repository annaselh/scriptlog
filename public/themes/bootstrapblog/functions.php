<?php

function grab_site_url()
{
 return app_info()['app_url'];
}

function load_template()
{
  $themeActived = is_theme('Y');

  $folder = $themeActived['theme_directory'].'/';

  return grab_site_url() . APP_PUBLIC. DS . $folder;
  
}

function grab_cdn($link)
{
  $cdn_url = filter_var($link, FILTER_SANITIZE_URL);

  if (filter_var($cdn_url, FILTER_VALIDATE_URL)) {

    return htmlspecialchars(autolink($cdn_url));
    
  }

}

function grab_title()
{
  
}

function grab_post($slug = null)
{
  return invoke_post($slug);
}

function grab_navigation()
{
  return front_navigation();
}

function page_not_found()
{
  header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
  include __DIR__ . '/404.php';
}
