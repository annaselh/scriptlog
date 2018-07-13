<?php 
/**
 * ImageUploader Class
 *
 * @package   SCRIPTLOG
 * @author    Maoelana Noermoehammad
 * @license   MIT
 * @version   1.0
 * @since     Since Release 1.0
 *
 */
class ImageUploader
{
 
 protected $file_name;
 
 protected $file_location;
 
 protected $file_type;
 
 protected $file_size;
 
 protected $file_error;
 
 protected $path_destination;
 
 private $file_basename;
 
 private $file_extension;
 
 private $image_source = null;
 
 private $max_size = 586000;
 
 private $image_permitted = array(
     'jpg' => 'image/jpeg', 
     'png' => 'image/png', 
     'gif' => 'image/gif'
 );
 
 private $compress_setting = array(
     'directory' => APP_ROOT . APP_PUBLIC . DS . 'picture'. DS,
     'file_type' => array( // file format allowed
         'image/jpeg',
         'image/png',
         'image/gif'
     )
 );
 
 private $error_message;
 
 public function __construct($key, $path)
 {
   $this->file_location = isset($_FILES[$key]['tmp_name']) ? $_FILES[$key]['tmp_name'] : "";
   $this->file_type = isset($_FILES[$key]['type']) ? $_FILES[$key]['type'] : "";
   $this->file_name = isset($_FILES[$key]['name']) ? $_FILES[$key]['name'] : "";
   $this->file_size = isset($_FILES[$key]['size']) ? $_FILES[$key]['size'] : "";
   $this->file_error = isset($_FILES[$key]['error']) ? $_FILES[$key]['error'] : "";
   
   $this->path_destination = $path;
   
 }
 
 private function setFileBaseName($file_name)
 {
   $this->file_basename = substr($file_name, 0, strripos($file_name, '.'));
 }
 
 private function getFileBaseName()
 {
   return $this->file_basename;
 }
 
 private function setFileExtension($file_extension)
 {
   $this->file_extension = substr($file_extension, strripos($file_extension, '.'));
 }
 
 private function getFileExtension()
 {
   return $this->file_extension;
 }
  
 private function checkImageSize($file_name)
 {
     
  $size_conf = substr($this->max_size, -1);
  $max_conf = (int)substr($this->max_size, 0, -1);
  
  switch($size_conf){
      case 'k':
      case 'K':
          $max_size *= 1024;
          break;
      case 'm':
      case 'M':
          $max_size *= 1024;
          $max_size *= 1024;
          break;
      default:
          $max_size = 1024000;
  }
  
  if (filesize($file_name) > $max_size) {
      
      return false;
      
  } else {
      
      return true;
      
  }
  
 }
 
 private function checkImageMimeType($file_location) 
 {
  $finfo = new finfo(FILEINFO_MIME_TYPE);
  $file_contents = file_get_contents($this->file_location);
  $mime_type = $finfo -> buffer($file_contents);
  
  try {
      
      $ext = array_search($mime_type, $this->image_permitted, true);
      
      if (false === $ext) {
           
          throw new Exception('Invalid file format');
      }
      
      return true;
      
  } catch (Exception $e) {
      
      $this->error_message = LogError::newMessage($e);
      $this->error_message = LogError::customErrorMessage();
      
  }
 
 }

 private function readyToUpload()
 {
     if (!isset($this->file_error) || is_array($this->file_error)) {
         
         throw new RuntimeException('Invalid parameters');
         
     }
   
     $writableFolder = is_writable($this->path_destination);
     
     $tempName = is_uploaded_file($this->file_location);
     $maxSize = ini_get('upload_max_filesize');
     
     if ($this->checkImageSize($this->file_location) === false) {
         
         $this->error_message = "Error: File is too big";
         $canUpload = false;
         
     }
     
     if ($this->checkImageMimeType($this->file_location) === false) {

         $this->error_message = "Exceeded filesize limit";
         $canUpload = false;
         
     }
     
     if ($writableFolder === false ) {
         
         $this->error_message = "Error: destination folder is ";
         $this->error_message .= "not writable";
         $canUpload = false;
         
     } elseif ($this->file_error === 1) {
        
         $this->error_message = "Error: File is too big ";
         $this->error_message = "Max file size is".format_size_unit($maxSize);
         $canUpload = false;
         
     } elseif ($this->file_error > 1) {
         
         $this->error_message = "Something went wrong";
         $this->error_message .= "Error Code: $this->error_message";
         
     } else {
         
         $canUpload = true;
         
     }
     
     return $canUpload;
     
 }

 protected function saveImagePost($file_name, $width, $height, $mode)
 {
     if ($this->readyToUpload()) {
         
         $upload_dir = $this->path_destination;
         $upload_dir_thumb = $this->path_destination . 'thumbs/';
         $file_uploaded = $upload_dir . $file_name;
         
         if (filesize($this->file_size) > 52000) {
             
             move_uploaded_file($this->file_location, $file_uploaded);
             
             $resizer = new Resize($file_uploaded);
             $resizer -> resizeImage($width, $height, $mode);
             $resizer -> saveImage($file_uploaded, 100);
             
         } else {
             move_uploaded_file($this->file_location, $file_uploaded);
         }
         
         // checking file type
         $img_source = null;
         
         if ($file_type == "image/jpeg") {
             
             $img_source = imagecreatefromjpeg($file_uploaded);
             
         } elseif ($file_type == "image/png") {
             
             $img_source = imagecreatefrompng($file_uploaded);
             
         } elseif ($file_type == "image/jpg") {
             
             $img_source = imagecreatefromjpeg($file_uploaded);
             
         } elseif ($file_type == "image/gif") {
             
             $img_source = imagecreatefromgif($file_uploaded);
             
         }
         
         $source_width = imagesx($img_source);
         $source_height = imagesy($img_source);
         
         // set picture's size
         $set_width = 320;
         $set_height = ($set_width/$source_width) * $source_height;
         
         // process
         $img_processed = imagecreatetruecolor($set_width, $set_height);
         imagecopyresampled($img_processed, $img_source, 0, 0, 0, 0, $set_width, $set_height, $source_width, $source_height);
         
         // save picture's thumbnail
         if ($this->file_type == "image/jpeg") {
             
             imagejpeg($img_processed, $upload_path_thumb . "thumb_" . $file_name);
             
         } elseif ($this->file_type == "image/png") {
             
             imagepng($img_processed, $upload_path_thumb . "thumb_" . $file_name);
             
         } elseif ($$this->file_type == "image/gif") {
             
             imagegif($img_processed, $upload_path_thumb . "thumb_" . $file_name);
             
         } elseif ($this->file_type == "image/jpg") {
             
             imagejpeg($img_processed, $upload_path_thumb . "thumb_" . $file_name);
             
         }
         
         // Delete Picture in computer's memory
         imagedestroy($img_source);
         imagedestroy($img_processed);
         
     } else {
         
         $exception = new Exception($this->error_message);
         $this->error_message = LogError::newMessage($exception);
         $this->error_message = LogError::customErrorMessage();
         
     }
     
 }
 
 public function renameImage()
 {
     $this->setFileBaseName($this->file_name);
     $this->setFileExtension($this->file_name);
     return rename_file(md5(rand(0,999).$this->getFileBaseName())).$this->getFileExtension();
 }
 
 public function isImageUploaded()
 {
     $isUploaded = (empty($this->file_location) || empty($this->file_basename));
     return $isUploaded;
 }
 
 public function uploadImage($uploadType, $file_name, $width, $height, $mode)
 {
     $allowedType = ['post', 'page', 'logo', 'media'];
     
     if (in_array($needle, $haystack)) {
         
     }
     switch ($uploadType) {
         
         case 'page':
         case 'post' :
             
             $this->saveImagePost($file_name, $width, $height, $mode);
             
             break;
             
         case 'logo':
             
             
             break;
     }
 }
}