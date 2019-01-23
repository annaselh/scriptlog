<?php
/**
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

 public function setMediaType($type)
 {
   $this->media_type = $type;
 }

 public function setMediaTarget($target)
 {
   $this->media_target = $target;
 }

 public function setMediaUser($user)
 {
   $this->media_user = $user;
 }

 public function setMediaAccess($access)
 {
   $this->media_access = $access;
 }

 public function setMediaStatus($status)
 {
   $this->media_status = $status;
 }

 public function grabAllMedia($orderBy = 'ID')
 {
   return $this->mediaDao->findAllMedia($orderBy);
 }

 public function grabMedia($id)
 {
   return $this->mediaDao->findMediaById($id, $this->sanitizer);
 }

 public function addMedia()
 {

   $this->validator->sanitize($this->media_caption, 'string');
   $this->validator->sanitize($this->media_filename, 'string');
   $this->validator->sanitize($this->media_user, 'string');
   
  
   
 }

 public function modifyMedia()
 {

 }

 public function removeMedia()
 {
   
 }

}