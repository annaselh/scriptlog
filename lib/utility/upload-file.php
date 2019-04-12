<?php
/**
 * Upload file
 * 
 * @param string $filename
 * @param string $folder
 * 
 */
function upload_file($field_name, $folder)
{

 if (!is_dir(__DIR__ . '/../../public/files/docs/'.$folder . DS)) {

    $file_path = mkdir(__DIR__ . '/../../public/files/docs/'.$folder.DS);

 }

 move_uploaded_file($_FILES[$field_name]['tmp_name'], $file_path);
    
}