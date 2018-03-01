<?php

class Scriptloader
{
 protected $librayPaths = array();
 
 protected $fileExtensionName = '/^.+\.php$/i';
 
 protected $excludeDirName = '/^git|\..*$/';
 
 protected $directoryIterator;
 
 public function setLibraryPaths($paths)
 {
   $this->librayPaths = $paths;
 }
 
 public function addLibraryPath($path)
 {
   $this->librayPaths[] = $path;
 }
 
 public function setFileExtension($extension)
 {
    $this->fileExtension = $extension;
 }
 
 public function loadLibrary($class)
 {
     $libraryPath = '';
     foreach ($this->librayPaths as $path) {
         
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
         
         $this->directoryIterator = dir($directory);
         
        while ($filename = $this->directoryIterator->read()) {
            
            $subLibrary = $directory . $filename;
            
            if (is_dir($subLibrary)) {
                
                if (!preg_match($this->excludeDirName, $filename)) {
                    
                    if ($fileLibraryPath = self::isLibraryFile($class, $subLibrary. '/')) {
                        
                        return $fileLibraryPath;
                        
                    }
                    
                }
                
            } else {
                
                if ($filename == $class . $this->fileExtensionName) {
                    
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