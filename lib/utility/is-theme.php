<?php
/**
 * is_theme Function
 * checking which theme actived and retrieve necessary theme data
 * 
 */
function is_theme($status)
{
  $theme = new Theme();
  return $theme->loadTheme('Y');
}