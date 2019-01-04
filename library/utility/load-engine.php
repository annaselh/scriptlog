<?php
/**
 * Load engine function
 * 
 * @param array $directory
 * 
 */
function load_engine($directory = array())
{

  $loader = new Scriptloader();

  $loader -> setLibraryPaths($directory);

  return $loader -> runLoader(); 

}