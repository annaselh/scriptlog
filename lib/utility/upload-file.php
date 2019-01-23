<?php
/**
 * Upload file
 * 
 * @param string $filename
 * 
 */
function upload_file($file_name)
{
    // picture directory
    $upload_path = __DIR__ . '/../../public/files/docs/';
    $file_uploaded = $upload_path . $file_name;
    $file_size = $_FILES['fdoc']['size'];
    
    if ($file_size < 524867) {
        
        move_uploaded_file($_FILES['fdoc']['tmp_name'], $file_uploaded);
        
    } else {
        
        throw new Exception("Your file is too big !. Maximum file size :" . format_size_unit(524867));
        
    }
    
}