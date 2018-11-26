<?php
/**
 * Class Scriptload
 * Load all class files in any directories selected
 * 
 * @package     SCRIPTLOG
 * @author      Maoelana Noermoehammad 
 * @license     MIT
 * @version     1.0
 * @since       Since Release 1.0
 *
 */
class Scriptloader
{

 /**
  * Library Path
  * 
  * @var array
  */
 protected static $librayPaths = [];
 
 /**
  * File extension
  * 
  * @var string
  */
 protected static $fileExtensionName = '.php';
 
 /**
  * exclude directory
  * 
  * @var string
  */
 protected static $excludeDirName = '/^git|\..*$/';
 
 /**
  * set library path
  * @param string $paths
  */
 public function setLibraryPaths($paths)
 {
   self::$librayPaths = $paths;
 }
 
 /**
  * add library path to load
  * @param string $path
  */
 public function addLibraryPath($path)
 {
   self::$librayPaths[] = $path;
 }
 
 /**
  * set file extension name
  * @param string $extension
  */
 public function setFileExtension($extension)
 {
    self::$fileExtensionName = $extension;
 }
 
 /**
  * load library
  * @param string $class
  * @return boolean
  */
 public function loadLibrary($class)
 {
     $libraryPath = '';
     
     foreach (self::$librayPaths as $path) {
         
         if ($libraryPath = self::isLibraryFile($class, $path)) {

          include($libraryPath);

          return true;
            
         }
     }
     
     return false;
 }
 
 /**
  * checking library file and it's directory
  *
  * @param string $class
  * @param string $directory
  * @return boolean|string|string|boolean
  *
  */
 protected static function isLibraryFile($class, $directory)
 {
     if ((is_dir($directory)) && (is_readable($directory))) {
         
        $directoryIterator = dir($directory);
         
        while ($filename = $directoryIterator->read()) {
            
            $subLibrary = $directory . $filename;
            
            if (is_dir($subLibrary)) {
                
                if (!preg_match(self::$excludeDirName, $filename)) {
                    
                    if ($fileLibraryPath = self::isLibraryFile($class, $subLibrary. '/')) {
                        
                        return $fileLibraryPath;
                        
                    }
                    
                }
                
            } else {
                
                if ($filename == $class . self::$fileExtensionName) {
                    
                    return $subLibrary;
                    
                }
                
            }
            
        }
        
     }
     
     return false;
     
 }
 
 /**
  * Loader
  * load all library path selected
  * spl_autoload_register
  */
 public function runLoader()
 {
    spl_autoload_register(null, false);
    spl_autoload_register(array('Scriptloader', 'loadLibrary'));
 }
 
}