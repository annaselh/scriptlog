<?php
/**
 * Class Scriptload
 * Load all class files in any directories selected
 * 
 * @author maoelana
 *
 */
class Scriptloader
{
 protected static $librayPaths = [];
 
 protected static $fileExtensionName = '.php';
 
 protected static $excludeDirName = '/^git|\..*$/';
 
 public function setLibraryPaths($paths)
 {
   self::$librayPaths = $paths;
 }
 
 public function addLibraryPath($path)
 {
   self::$librayPaths[] = $path;
 }
 
 public function setFileExtension($extension)
 {
    self::$fileExtensionName = $extension;
 }
 
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
 
 protected static function isLibraryFile($class, $directory)
 {
     if (is_dir($directory) && is_readable($directory)) {
         
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
 
 public function runLoader()
 {
    spl_autoload_register(null, false);
    spl_autoload_register(array('Scriptloader', 'loadLibrary'));
 }
 
}