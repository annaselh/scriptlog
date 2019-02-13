<?php
/**
 * Class MediaEvent
 * 
 * 
 */
class MediaEvent
{
 
/**
 * Id
 * 
 * @var integer
 * 
 */
 private $mediaId;

/**
 * Media's filename
 * 
 * @var string
 * 
 */
 private $media_filename;

/**
 * Caption
 * 
 * @var string
 * 
 */
 private $media_caption;

/**
 * Media's type
 * 
 * @var string
 * 
 */
 private $media_type;

/**
 * Media's target
 * 
 * @var string
 * 
 */
 private $media_target;

/**
 * Media's user
 * 
 * @var string
 * 
 */
 private $media_user;

/**
 * Media's access
 * 
 * @var string
 * 
 */
 private $media_access;

/**
 * Media's status
 * 
 * @var string
 * 
 */
 private $media_status;

/**
 * Media DAO 
 * 
 * @var object
 * 
 */
 private $mediaDao;

/**
 * Validator
 * 
 * @var object
 * 
 */
 private $validator;

/**
 * Sanitizer
 * 
 * @var object
 * 
 */
 private $sanitizer;

/**
 * Initialize an intanciates of object properties or method
 * 
 * @param object $mediaDao
 * @param object $validator
 * @param object $sanitizer
 * 
 */
 public function __construct(Media $mediaDao, FormValidator $validator, Sanitize $sanitizer)
 {
   $this->mediaDao  = $mediaDao;
   $this->validator = $validator;
   $this->sanitizer = $sanitizer;
 }

/**
 * set media id
 * 
 * @param integer $mediaId
 * 
 */
 public function setMediaId($mediaId)
 {
   $this->mediaId = $mediaId;
 }

/**
 * set media filename
 * 
 * @param string $string
 * 
 */
 public function setMediaFilename($filename)
 {
   $this->media_filename = $filename;
 }

/**
 * set media caption
 * 
 * @param string $caption
 * 
 */
 public function setMediaCaption($caption)
 {
   $this->media_caption = $caption;
 }

/**
 * Set media type
 * 
 * @param string $type
 * 
 */
 public function setMediaType($type)
 {
   $this->media_type = $type;
 }

/**
 * Set media target
 * 
 * @param string $target
 * 
 */
 public function setMediaTarget($target)
 {
   $this->media_target = $target;
 }

/**
 * Set media user
 * 
 * @param string $user
 * 
 */
 public function setMediaUser($user)
 {
   $this->media_user = $user;
 }

/**
 * Set media access
 * 
 * @param mixed $string
 * 
 */
 public function setMediaAccess($access)
 {
   $this->media_access = $access;
 }

/**
 * Set media status
 * 
 * @param string $status
 * 
 */
 public function setMediaStatus($status)
 {
   $this->media_status = $status;
 }

/**
 * Grab all media
 * retrieve all media records
 * 
 * @param integer $orderBy
 * 
 */
 public function grabAllMedia($orderBy = 'ID')
 {
   return $this->mediaDao->findAllMedia($orderBy);
 }

/**
 * GrabMedia
 * retrieve single record of media
 * 
 * @param integer $id
 * 
 */
 public function grabMedia($id)
 {
   return $this->mediaDao->findMediaById($id, $this->sanitizer);
 }

 public function addMedia()
 {

   $this->validator->sanitize($this->media_caption, 'string');
   $this->validator->sanitize($this->media_filename, 'string');
   $this->validator->sanitize($this->media_user, 'string');
   
   return $this->mediaDao([
     'media_filename' => $this->media_filename,
     'media_caption' => $this->media_caption,
     'media_type' => $this->media_type,
     'media_target' => $this->media_target,
     'media_user' => $this->media_user,
     'media_access' => $this->media_access,
     'media_status' => $this->media_status
   ]);
  
 }

 public function modifyMedia()
 {

   $this->validator->sanitize($this->mediaId, 'int');
   $this->validator->sanitize($this->media_caption, 'string');

   if(empty($this->media_filename)) {

      return $this->mediaDao->updateMedia($this->sanitizer, [
        'media_caption' => $this->media_caption,
        'media_target' => $this->media_target,
        'media_access' => $this->media_access,
        'media_status' => $this->media_status
      ], $this->mediaId);

   } else {

      return $this->mediaDao->updateMedia($this->sanitizer, [
         'media_filename' => $this->media_filename,
         'media_caption' => $this->media_caption,
         'media_access' => $this->media_access,
         'media_status' => $this->media_status
      ], $this->mediaId);

   }

 }

 public function removeMedia()
 {
   $this->validator->sanitize($this->mediaId, 'int');

   if(!$data_media = $this->mediaDao->findMediaById($this->mediaId, $this->sanitizer)) {
      direct_page('index.php?load=media&error=mediaNotFound', 404);
   }
 }

}