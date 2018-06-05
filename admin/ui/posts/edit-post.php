<?php if (!defined('SCRIPTLOG')) exit(); ?>

<div class="content-wrapper">
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=(isset($pageTitle)) ? $pageTitle : ""; ?>
        <small>Control Panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?load=dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="index.php?load=posts">Posts</a></li>
        <li class="active"><?=(isset($pageTitle)) ? $pageTitle : ""; ?></li>
      </ol>
    </section>

 <!-- Main content -->
<section class="content">
<div class="row">
<div class="col-md-12">
<div class="box box-primary">
<div class="box-header with-border"></div>
<!-- /.box-header -->
<?php
if (isset($message['errorMessage'])) :
?>
<div class="alert alert-danger alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<h4><i class="icon fa fa-ban"></i> Alert!</h4>
<?= $message['errorMessage']; ?>
</div>
<?php 
endif;
?>
<form method="post" action="index.php?load=users&action=<?= $formAction;?>&userId=<?=(isset($userId)) ? $userId : 0; ?>&sessionId=<?=(isset($sessionId)) ? $sessionId : ""; ?>" role="form">
<input type="hidden" name="post_id" value="<?=(isset($postId)) ? $postId : 0; ?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="697856" />

<div class="box-body">
<div class="form-group">
<label>Title (required)</label>
<input type="text" class="form-control" name="post_title" placeholder="Enter title here" value="<?=(isset($_POST['user_login'])) ? htmlspecialchars($_POST['user_login'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>" required>
</div>
<?=(isset($topics)) ? $topics : ""; ?>

<?php 
if (isset($postImage)) :
?>
<div class="form-group">
<?php 
$image = __DIR__ . '/../public/files/pictures/'.$postImage;
$imageThumb = __DIR__ . '/../public/files/pictures/thumbs/thumb_'.$postImage;

if (!is_readable($imageThumb)) :
    $imageThumb = __DIR__ . '/../public/files/pictures/thumbs/nophoto.jpg';
endif;

if (is_readable($image)) :
?>
<br><a href="<?php echo $image; ?>"><img src="<?php  echo $imageThumb; ?>"></a><br> 
<label>change picture :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)" maxlength="512" />
<img id="output" />
<p class="help-block">Maximum file size: <?= format_size_unit(697856); ?></p>
<?php 
else :
?>
<br><img src="<?php echo $imageThumb; ?>"><br> 
<label>change picture :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)"  maxlength="512" />
<img id="output" />
<p class="help-block">Maximum file size: <?= format_size_unit(697856); ?></p>
<?php 
endif;
?>
</div>
<?php else : ?>
<div class="form-group">
<label>Upload Picture :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)"  maxlength="512" />
<img id="output" />
<p class="help-block">Maximum file size: <?= format_size_unit(697856); ?></p>
</div>
<?php 
endif;
?>

<div class="form-group">
<label>Meta Description</label>
<textarea class="form-control" name="meta_description" rows="3" maxlength="500">

</textarea>
</div>

<div class="form-group">
<label>Meta Keywords</label>
<textarea class="form-control" name="meta_keywords" rows="3" maxlength="200">

</textarea>
</div>

<div class="form-group">
<label>Content (required)</label>
<textarea class="form-control" id="sl" name="post_content" rows="10" maxlength="100000">

</textarea>
</div>

<div class="form-group">
<?=(isset($postStatus)) ? $postStatus : ""; ?>
</div>
<!-- /.post status -->

<div class="form-group">
<?=(isset($commentStatus)) ? $commentStatus : ""; ?>
</div>
<!-- /.comment status -->

</div>
<!-- /.box-body -->

<div class="box-footer">
   <input type="hidden" name="edit" value="1">
   <input type="submit" name="postFormSubmit" class="btn btn-primary" value="Add New User">
</div>
</form>
            
</div>
<!-- /.box -->
</div>
<!-- /.col-md-12 -->
</div>
<!-- /.row --> 
</section>

</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
  var loadFile = function(event) {
	    var output = document.getElementById('output');
	    output.src = URL.createObjectURL(event.target.files[0]);
	  };
</script>