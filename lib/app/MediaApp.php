<?php
/**
 * Class Media
 * 
 * @package  SCRIPTLOG LIBRARY
 * @category lib\app\MediaApp
 * @author   M.Noermoehammad 
 * @license  MIT
 * @version  1.0
 * @since    Since Release 1.0
 * 
 */
class MediaApp extends BaseApp
{

private $view;

private $mediaEvent;

public function __construct(MediaEvent $mediaEvent)
{
 $this->mediaEvent = $mediaEvent;
}

public function listItems()
{

  $errors = array();
  $status = array();
  $checkError = true;
  $checkStatus = false;

  if (isset($_GET['error'])) {

     $checkError = false;
     if ($_GET['error'] == 'mediaNotFound') array_push($errors, "Error: Media Not Found");

  }

  if (isset($_GET['status'])) {
      $checkStatus = true;
      if ($_GET['status'] == 'mediaAdded') array_push($status, "New media added");
      if ($_GET['status'] == 'mediaUpdated') array_push($status, "Media has been updated");
      if ($_GET['status'] == 'mediaDeleted') array_push($status, "Media deleted");
  }

  $this->setView('all-media');
  $this->setPageTitle('Media Library');
  $this->view->set('pageTitle', $this->getPageTitle());

  if (!$checkError) {
     $this->view->set('errors', $errors);
  }

  if ($checkStatus) {
     $this->view->set('status', $status);
  }

  $this->view->set('mediaTotal', $this->mediaEvent->totalMedia());
  $this->view->set('media', $this->mediaEvent->grabAllMedia());
  return $this->view->render();
  
}

public function insert()
{
  
  $errors = array();
  $checkError = true;

  if (isset($_POST['mediaFormSubmit'])) {

    $file_location = isset($_FILES['media']['tmp_name']) ? $_FILES['media']['tmp_name'] : '';
    $file_type = isset($_FILES['media']['type']) ? $_FILES['media']['type'] : '';
    $file_name = isset($_FILES['media']['name']) ? $_FILES['media']['name'] : '';
    $file_size = isset($_FILES['media']['size']) ? $_FILES['media']['size'] : '';
    $file_error = isset($_FILES['media']['error']) ? $_FILES['media']['error'] : '';

    $caption = isset($_POST['caption']) ? prevent_injection($_POST['caption']) : '';
    $media_type = $_POST['media_type'];
    $media_target = $_POST['media_target'];
    $media_access = $_POST['media_access'];
    $media_status = $_POST['media_status'];

    try {

      if (!csrf_check_token('csrfToken', $_POST, 60*10)) {
              
        header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
        throw new AppException("Sorry, unpleasant attempt detected!");
        
      }

      if (!isset($file_error) || is_array($file_error)) {

        $checkError = false;
        array_push($errors, "Invalid paramenter");
        
      }

      if (empty($file_location)) {

        $checkError = false;
        array_push($errors, "No file uploaded");

      }

      if ($file_size > 524876) {

         $checkError = false;
         array_push($errors, "Exceeded file size limit. Maximum file size is. ".format_size_unit(524876));

      }

      // get filename
	    $file_info = pathinfo($file_name);
	    $name = $file_info['filename'];
	    $file_extension = $file_info['extension'];
      $tmp = str_replace(array('.',' '), array('',''), microtime());
      $newFileName = rename_file(md5($name.$tmp)).'-'.date('Ymd').$file_extension;

      $fileUploaded = upload_media('media',true,true);

      if (is_array($fileUploaded['error'])) {

        $message = '';
        foreach ($fileUploaded['error'] as $msg) {
          
          $message .= $msg;
           
        }

        $checkError = false;
        array_push($errors, $message);

      }

      if (!$checkError) {

         $this->setView('edit-media');
         $this->setPageTitle('Upload New Media');
         $this->setFormAction('newMedia');
         $this->view->set('pageTitle', $this->getPageTitle());
         $this->view->set('formAction', $this->getFormAction());
         $this->view->set('errors', $errors);
         $this->view->set('formData', $_POST);
         $this->view->set('mediaType', $this->mediaEvent->mediaTypeDropDown());
         $this->view->set('mediaTarget', $this->mediaEvent->mediaTargetDropDown());
         $this->view->set('mediaAccess', $this->mediaEvent->mediaAccessDropDown());
         $this->view->set('mediaStatus', $this->mediaEvent->mediaStatusDropDown());
         $this->view->set('csrfToken', csrf_generate_token('csrfToken'));

      } else {

         $this->mediaEvent->setMediaFilename($newFileName);
         $this->mediaEvent->setMediaCaption($caption);
         $this->mediaEvent->setMediaType($type);
         $this->mediaEvent->setMediaTarget($target);
         $this->mediaevent->setMediaUser($this->mediaEvent->isMediaUser());
         $this->mediaEvent->setMediaAccess($access);
         $this->mediaEvent->setMediaStatus($status);
         $this->mediaEvent->addMedia();

         direct_page('index.php?load=media&status=mediaAdded', 200);

      }
      
    } catch(AppException $e) {

       LogError::setStatusCode(http_response_code());
       LogError::newMessage($e);
       LogError::customErrorMessage('admin');
       
    }

  } else {

     $this->setView('edit-media');
     $this->setPageTitle('Media Library');
     $this->setFormAction('newMedia');
     $this->view->set('pageTitle', $this->getPageTitle());
     $this->view->set('formAction', $this->getFormAction());
     $this->view->set('mediaType', $this->mediaEvent->mediaTypeDropDown());
     $this->view->set('mediaTarget', $this->mediaEvent->mediaTargetDropDown());
     $this->view->set('mediaAccess', $this->mediaEvent->mediaAccessDropDown());
     $this->view->set('mediaStatus', $this->mediaEvent->mediaStatusDropDown());
     $this->view->set('csrfToken', csrf_generate_token('csrfToken'));
 
  }

  return $this->view->render();

}

public function update($id)
{

  $errors = array();
  $checkError = true;

  if (!$getMedia = $this->mediaEvent->grabMedia($id)) {

     direct_page('index.php?load=media&error=mediaNotFound', 404);

  }

  $data_media = array(
    'ID' => $getMedia['ID'],
    'media_filename' => $getMedia['media_filename'],
    'media_caption' => $getMedia['media_caption'],
    'media_type' => $getMedia['media_type'],
    'media_target' => $getMedia['media_target'],
  );

  if (isset($_POST['mediaFormSubmit'])) {

  } else {
    
  }

}

public function remove($id)
{

}

protected function setView($viewName)
{
  $this->view = new View('admin'.'ui'.'media'.$viewName);
}

}