<?php 
/**
 * Class Media extends Dao
 * 
 * @package  SCRIPTLOG
 * @category library\dao\Media
 * @author   M.Noermoehammad
 * @license  MIT
 * @version  1.0
 * @since    Since Release 1.0
 * 
 */
class Media extends Dao
{

/**
 * 
 */
public function __construct()
{
    parent::__construct();
}

/**
 * Find All Media
 * 
 * @method public findAllMedia()
 * @param integer $ID
 * @return array
 * 
 */
public function findAllMedia($orderBy = 'ID')
{

  $sql = "SELECT ID, 
         media_filename, 
         media_caption, 
         media_type, 
         media_target,
         media_user, 
         media_access,
         media_status
         FROM media
         ORDER BY :orderBy DESC";

  $this->setSQL($sql);
  
  $medias = $this->findAll([':orderBy' => $orderBy]);

  if(empty($medias)) return false;

  return $medias;
  
}

/**
 * Find media by Id
 * 
 * @method public findMediaById()
 * @param integer $mediaId
 * @param object $sanitize
 * @param string $fetchMode
 * 
 */
public function findMediaById($mediaId, $sanitize, $fetchMode = null)
{
  $idsanitized = $this->filteringId($sanitize, $mediaId, 'sql');

  $sql = "SELECT ID, 
            media_filename, 
            media_caption,
            media_type,
            media_target,
            media_user, 
            media_access,
            media_status
          FROM media
          WHERE ID = ?";

  $this->setSQL($sql);

  if(is_null($fetchMode)) {

    $mediaDetails = $this->findRow([$idsanitized]);

  } else {

    $mediaDetails = $this->findRow([$idsanitized], $fetchMode);

  }

  if(empty($mediaDetails)) return false;

  return $mediaDetails;

}

/**
 * 
 */
public function findMediaByType($type, $fetchMode = null)
{
  $sql = "SELECT ID,
            media_filename,
            media_caption,
            media_type, 
            media_target,
            media_user,
            media_access,
            media_status
          FROM media
          WHERE media_type = :media_type 
          AND media_status = '1'";

  $this->setSQL($sql);
  
  if(is_null($fetchMode)) {

     $mediaDetails = $this->findRow([':media_type' => $type]);

  } else {

     $mediaDetails = $this->findRow([':media_type' => $type], $fetchMode);

  }

  if(empty($mediaDetails)) return false;

  return $mediaDetails;
  
}

}