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
 * @param string $fetchMode default  null
 * 
 */
public function findMediaById($mediaId, $sanitize)
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

  $mediaDetails = $this->findRow([$idsanitized]);

  if(empty($mediaDetails)) return false;

  return $mediaDetails;

}

/**
 * Find media by media format type
 * 
 * @method public findMediaByType()
 * @param string $type
 * @param string $fetchMode default null
 * 
 */
public function findMediaByType($type)
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
  
  $mediaDetails = $this->findRow([':media_type' => $type]);

  if(empty($mediaDetails)) return false;

  return $mediaDetails;
  
}

/**
 * Add new media
 * 
 * @method public addMedia()
 * @param string $name
 * 
 */
public function addMedia($bind)
{
  
  $stmt = $this->create("media", [

      'media_filename' => $bind['filename'],
      'media_caption'  => $bind['caption'],
      'media_type'     => $bind['type'],
      'media_target'   => $bind['target'],
      'media_user'     => $bind['user'],
      'media_access'   => $bind['access'],
      'media_status'   => $bind['status']

  ]);

  $media_id = $this->lastId();

  if($media_id) {

    $stmt2 = $this->create("mediameta", [

        'media_id'   => $media_id,
        'meta_key'   => $bind['meta_key'],
        'meta_value' => $bind['meta_value']

    ]);

  }

}

/**
 * Update Media
 * 
 * @method public updateMedia()
 * @param object $sanitize
 * @param array $bind
 * @param integer $ID
 * 
 */
public function updateMedia($sanitize, $bind, $ID)
{
  
  $id_sanitized = $this->filteringId($sanitize, $ID, 'sql');
 
  if(!empty($bind['filename'])) {

     $stmt = $this->modify("media", [
        
         'media_filename' => $bind['filename'],
         'media_caption'  => $bind['caption'],
         'media_target'   => $bind['target'],
         'media_access'   => $bind['access'],
         'media_status'   => $bind['status']

     ], "ID = {$id_sanitized}");

  } else {
    
     $stmt = $this->modify("media", [
        
        'media_caption' => $bind['caption'],
        'media_target'  => $bind['target'],
        'media_access'  => $bind['access'],
        'media_status'  => $bind['status']

     ], "ID = {$id_sanitized}");

  }

  // query Id
  $this->setSQL("SELECT ID from media WHERE ID = ?");
  $media_id = $this->findColumn([$id_sanitized]);

  // update media meta
  if(!empty($bind['filename'])) {

     $stmt2 = $this->modify("mediameta", [

         'meta_value' => $bind['meta_value']

     ], "ID = {$media_id['ID']}");
     
  }

}

/**
 * Delete Media
 * 
 * @method public deleteMedia()
 * @param integer $ID
 * @param object $sanitize
 * 
 */
public function deleteMedia($ID, $sanitize)
{
  $id_sanitized = $this->filteringId($sanitize, $ID, 'sql');
  $stmt = $this->deleteRecord("media", "ID = {$id_sanitized}");
}

/**
 * Check media's Id
 * 
 * @method public checkMediaId()
 * @param integer|numeric $id
 * @param object $sanitize
 * @return numeric
 * 
 */
public function checkMediaId($id, $sanitize)
{
 
   $sql = "SELECT ID from media WHERE ID = ?";
   $id_sanitized = $this->filteringId($sanitize, $id, 'sql');
   $this->setSQL($sql);
   $stmt = $this->checkCountValue([$id_sanitized]);
   return($stmt > 0);

}

/**
 * drop down media type
 * set media type
 * 
 * @param string $selected
 * @return string
 * 
 */
public function dropDownMediaType($selected = "")
{

 $name = 'media_type';

 $media_type = array('audio' => 'Audio', 'document' => 'Document', 'picture' => 'Picture', 'video' => 'Video');

 if($selected != '') {
    
    $selected = $selected;

 }

 return dropdown($name, $media_type, $selected);

}

/**
 * drop down media target
 * set media target
 * 
 * @param string $selected
 * @return string
 * 
 */
public function dropDownMediTarget($selected = "")
{
 $name = 'media_target';

 $media_target = array('album' => 'Album', 'blog' => 'Blog', 'download' => 'Download', 'page' => 'Page');

 if($selected != '') {

    $selected = $selected;

 }

 return dropdown($name, $media_target, $selected);

}

/**
 * Total media records
 * 
 * @method public totalMediaRecords()
 * @param array $data = null
 * @return integer|numeric
 * 
 */
public function totalMediaRecords($data = null)
{
  $sql = "SELECT ID FROM media";
  $this->setSQL($sql);
  return $this->checkCountValue($data);  
}

}