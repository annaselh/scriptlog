<?php

require __DIR__ . '/../library/main-dev.php';

$upload_path = __DIR__ . '/';

if (isset($_POST['imageFormSubmit'])) {
    
    $imageUploader = new ImageUploader('image', $upload_path);
    
    $newFileImage = $imageUploader -> renameImage();
    $uploadImage = $imageUploader -> uploadImage(['post'], $newFileImage, 770, 400, 'crop');
    
    if ($uploadImage) {

        echo "This is your uploaded file : {$newFileImage} uploaded on directory : {$upload_path}";
    
    } else {

        echo "nothing happen dude!";

    }
    
} 

?>
<!DOCTYPE html>
<html lang="en">
<body>
<p>This is your current upload directory <?= $upload_path; ?></p>
<form method="post" action="<?= $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
<label>Upload picture</label><br>
<input type="file" name="image" >
<input type="submit" name="imageFormSubmit" value="Submit" >
</form>

</body>
</html>
