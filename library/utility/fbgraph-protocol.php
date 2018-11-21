<?php
/**
 * 
 */
function fbgraph_protocol($locale, $site_name, $id, $title, $desc, $type, $post_url)
{
  
  $ogp = new OpenGraphProtocol();

  $ogp -> setLocale($locale);
  $ogp -> setSiteName($site_name);
  $ogp -> setTitle($title);
  $ogp -> setDescription($desc);
  $ogp -> setType($type);
  $ogp -> setURL($post_url);
  $ogp -> setDeterminer("");




}

function image_graph($post_image, $width, $height)
{

  $imageGraph = new OpenGraphProtocolImage();
  $site_info = app_info();
  
  $imageGraph -> setURL($site_info['app_url'].APP_PUBLIC.DS.'files'.DS.pictures.DS.$post_image);

}