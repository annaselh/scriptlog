<?php ?>

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
<div class="box-header with-border">
 <h3 class="box-title"><?=(isset($pageTitle)) ? $pageTitle : ""; ?></h3>
</div>
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
<input type="text" class="form-control" name="title" placeholder="Enter title here" value="<?=(isset($_POST['user_login'])) ? htmlspecialchars($_POST['user_login'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>" required>
</div>
<div class="form-group">
<label>Content (required)</label>
<textarea class="form-control" id="sc" name="content" rows="10" maxlength="100000">
<?php ?> 
</textarea>
</div>

<div class="form-group">

</div>
<!-- /.post status -->
<div class="form-group">

</div>
<!-- /.comment status -->
</div>
<!-- /.box-body -->

<div class="box-footer">
   <input type="hidden" name="edit" value="1">
   <input type="submit" class="btn btn-primary" value="Add New User">
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