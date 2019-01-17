<?php

function upload_photo($file_name, $width, $height, $mode, $folder)
{
    
    // picture directory
    $upload_path = __DIR__ . '/../../public/files/pictures/'.$folder . '/';
    $upload_path_thumb = __DIR__ .  '/../../public/files/pictures/'.$folder.'/thumbs/';
    $file_uploaded = $upload_path . $file_name;
    $file_type = $_FILES['image']['type'];
    $file_size = $_FILES['image']['size'];
    
    // save picture from resources
    
    if ($file_size > 52000) {
        
        move_uploaded_file($_FILES['image']['tmp_name'], $file_uploaded);
        
        // resize picture
        $resizeImage = new Resize($file_uploaded);
        $resizeImage ->resizeImage($width, $height, $mode);
        $resizeImage ->saveImage($file_uploaded, 100);
        
        
    } else {
        
        move_uploaded_file($_FILES['image']['tmp_name'], $file_uploaded);
        
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
    if ($_FILES['image']['type'] == "image/jpeg") {
        
        imagejpeg($img_processed, $upload_path_thumb . "thumb_" . $file_name);
        
    } elseif ($_FILES['image']['type'] == "image/png") {
        
        imagepng($img_processed, $upload_path_thumb . "thumb_" . $file_name);
        
    } elseif ($_FILES['image']['type'] == "image/gif") {
        
        imagegif($img_processed, $upload_path_thumb . "thumb_" . $file_name);
        
    } elseif ($_FILES['image']['type'] == "image/jpg") {
        
        imagejpeg($img_processed, $upload_path_thumb . "thumb_" . $file_name);
        
    }
    
    // Delete Picture in computer's memory
    imagedestroy($img_source);
    imagedestroy($img_processed);
    
}