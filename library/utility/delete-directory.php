<?php
/**
 * Delete Directory Function
 * 
 * @param string $dirname
 * @return boolean
 * 
 */
function delete_directory($dirname)
{
    if (is_dir( $dirname ))
        $dir_handle = opendir( $dirname );
        if (!$dir_handle)
            return false;
            while ( $file = readdir( $dir_handle ) ) {
                if ($file != "." && $file != "..") {
                    if (!is_dir( $dirname . "/" . $file ))
                        unlink( $dirname . "/" . $file );
                        else
                            deleteDir( $dirname . '/' . $file );
                }
            }
            
            closedir( $dir_handle );
            
            rmdir( $dirname );
            
            return true;
            
}