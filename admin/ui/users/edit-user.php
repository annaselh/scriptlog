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
        <li><a href="index.php?load=users">Users</a></li>
        <li class="active"><?=(isset($pageTitle)) ? $pageTitle : ""; ?></li>
      </ol>
    </section>

 <!-- Main content -->
<section class="content">
<div class="row">
<div class="col-md-12">
<div class="box box-primary">
<div class="box-header with-border">
 <h3 class="box-title">Create a brand new user and add them to this site.</h3>
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
<input type="hidden" name="session_id" value="<?=(isset($sessionId)) ? $sessionId : ""; ?>" />
<input type="hidden" name="user_id" value="<?=(isset($userId)) ? $userId : 0; ?>" />

<div class="box-body">
<div class="form-group">
<label>Username (required)</label>
<input type="text" class="form-control" name="user_login" placeholder="Enter username" value="<?=(isset($_POST['user_login'])) ? htmlspecialchars($_POST['user_login'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>" required>
</div>
<div class="form-group">
<label>Fullname</label>
<input type="text" class="form-control" name="user_fullname" placeholder="Enter fullname" value="<?=(isset($_POST['user_fullname'])) ? htmlspecialchars($_POST['user_fullname'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>" >
</div>
<div class="form-group">
<label>Email (required)</label>
<input type="email" class="form-control" name="user_email" placeholder="Enter email" value="<?=(isset($_POST['user_email'])) ? htmlspecialchars($_POST['user_email'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>" required>
</div>
<div class="form-group">
<label>Password (required)</label>
<input type="password" class="form-control" name="user_pass" placeholder="Enter password" autocomplete="off">
</div>
<div class="form-group">
<label>URL</label>
<input type="text" class="form-control" name="user_url" placeholder="Enter url" value="<?=(isset($_POST['user_url'])) ? htmlspecialchars($_POST['user_url'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>">
</div>
<div class="form-group">
<?=(isset($role)) ? $role : ""; ?>
</div>
<div class="checkbox">
<label>
<input type="checkbox" name="send_user_notification" value="1"> Send the new user an email about their account 
</label>
</div>
</div>
<!-- /.box-body -->

<div class="box-footer">
   <input type="submit" class="btn btn-primary" name="submit" value="Add New User">
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