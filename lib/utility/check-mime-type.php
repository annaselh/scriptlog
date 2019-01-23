<?php
/**
 * Checking Mime Type 
 * 
 * @param array $accepted_type
 * @param array $tmp_name
 * @return bool
 * 
 */
function check_mime_type(array $accepted_type, array $tmp_name)
{
    
 $file_info = new finfo(FILEINFO_MIME_TYPE);
 $file_content = file_get_contents($tmp_name);
 $mime_type = $file_info -> buffer($file_content);
 
 $extension = array_search($mime_type, $accepted_type, true);

 if(false === $extension) {

    return false;

 }

 return true;

}