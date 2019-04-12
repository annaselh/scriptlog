<?php
/**
 * Upload Media Function
 * 
 * @param string $field_name
 * @param bool $check_image
 * @param bool $random_name
 * 
 */
function upload_media($file_field = null, $check_image = false, $random_name = false)
{
  
 $errors = array();

 $max_size = 524867;
 
 $whitelist_ext = array('jpg', 'jpeg', 'png', 'gif', 'ppt', 'xls', 'pdf', 'doc');
 
 $whitelist_type = array('image/jpeg', 'image/png', 'image/gif', 
						 'application/vnd.ms-powerpoint', 
						 'application/vnd.ms-excel', 
						 'application/pdf', 'application/msword');

 $mime_types = array(

	 'jpg' => 'image/jpeg', 
	 'jpeg' => 'image/jpeg',
	 'png' => 'image/png', 
	 'gif' => 'image/gif',
	 'doc' => 'application/msword',
	 'pdf' => 'application/pdf',
	 'xls' => 'application/vnd.ms-excel',
	 'ppt' => 'application/vnd.ms-powerpoint',
	 'exe' => 'application/octet-stream',
	 'zip' => 'application/zip',
	 'rar' => 'application/rar'

  );

// Make sure that there is a file

if((!empty($_FILES[$file_field])) && ($_FILES[$file_field]['error'] == 0)) {

	// get filename
	$file_info = pathinfo($_FILES[$file_field]['name']);
	$name = $file_info['filename'];
	$ext = $file_info['extension'];

	// check file has the right extension
	if(!in_array($ext, $whitelist_ext)) {

		$errors['upload_failure'] = "Invalid file extension";

	} 

	// check that file is of the right type
	if(!in_array($_FILES[$file_field]['type'], $whitelist_type)) {

	   $errors['upload_failure'] = "Invalid file type";

	}

	// check that mime type of the file is the right mime types
	if(check_mime_type($mime_types, $_FILES[$file_field]['tmp_name']) === false) {

		$errors['upload_failure'] = "Invalid file format";

	}
	
	// check file size
	if($_FILES[$file_field]['size'] > $max_size) {

		$errors['upload_failure'] = "File is too big";

	}

	// create new filename 

	if($random_name) {

		$tmp = str_replace(array('.',' '), array('',''), microtime());

		if(!$tmp || $tmp == '' ) {

			$errors['upload_failure'] = "File must have a name";

		}

		// generate random filename
		$new_filename = rename_file(md5($name.$tmp)).'-'.date('Ymd').$ext;
		
	} 

	if(count($errors['upload_failure']) > 0) {

		return $errors;

	}

	$upload_time_path = date('Y').DS.date('m').DS.date('d').DS;
	$upload_path = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : '';
	
	switch ($ext) {

		case 'jpg':
		case 'jpeg':
		case 'gif':
		case 'png':

			$image_path = $upload_path.$upload_time_path.$new_filename;
			
			upload_photo($file_field, 770, 400, 'crop', $image_path);
			
			break;

		case 'pdf':
		case 'xls':
		case 'doc':
		case 'ppt':
		case 'zip':
		case 'rar':
		case 'exe':
			
		    $file_path =  $upload_path.$upload_time_path.$new_filename;

			upload_file($file_field, $file_path);
		    
			break;
   
	}

} else {

	$errors['upload_failure'] = "No file uploaded";

	return $errors;

}
 
}