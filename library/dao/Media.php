<?php 
/**
 * 
 */
class Media extends Dao
{
  
public function __construct()
{
    parent::__construct();
}

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
    
  }
}

}