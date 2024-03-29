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
        <li><a href="index.php?load=users">Users</a></li>
        <li class="active"><?=(isset($pageTitle)) ? $pageTitle : ""; ?></li>
      </ol>
    </section>

 <!-- Main content -->
<section class="content">
<div class="row">
<div class="col-md-6">
<div class="box box-primary">
<div class="box-header with-border">
 <h3 class="box-title"><?=(isset($userData['ID']) && $userData != '') ? "Personal Detail"  : "Create a brand new user and add them to this site"; ?></h3>
</div>
<!-- /.box-header -->
<?php
if (isset($errors)) :
?>
<div class="alert alert-danger alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<h4><i class="icon fa fa-warning"></i> Invalid Form Data!</h4>
<?php 
foreach ($errors as $e) :
echo '<p>' . $e . '</p>';
endforeach;
?>
</div>
<?php 
endif;
?>

<?php
if (isset($saveError)) :
?>
<div class="alert alert-danger alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<h4><i class="icon fa fa-ban"></i> Alert!</h4>
<?php 
echo "Error saving data. Please try again." . $saveError;
?>
</div>
<?php 
endif;
?>

<form method="post" action="index.php?load=users&action=<?=(isset($formAction)) ? $formAction : null; ?>&userId=<?=(isset($userData['ID'])) ? $userData['ID'] : 0; ?>&sessionId=<?=(isset($sessionId)) ? $sessionId : md5(get_ip_address()); ?>" role="form">
<input type="hidden" name="session_id" value="<?=(isset($userData['user_session'])) ? $userData['user_session'] : md5(get_ip_address()); ?>" />
<input type="hidden" name="user_id" value="<?=(isset($userData['ID'])) ? $userData['ID'] : 0; ?>" />
<div class="box-body">

<?php
if(isset($userData['user_registered'])) :
?>
<div class="form-group">
<label>Registered</label>
<?= read_datetime($userData['user_registered']); ?>
</div>
<?php 
endif;
?>

<div class="form-group">
<label>Username <?=(isset($userData['user_login'])) ? "" : "(required)" ?></label>
<input type="text" class="form-control" name="user_login" placeholder="Enter username" value="<?=(isset($formData['user_login'])) ? htmlspecialchars($formData['user_login'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>
<?=(isset($userData['user_login'])) ? htmlspecialchars($userData['user_login']) : ""; ?>" 
  required <?=(isset($userData['user_login']) && $userData['user_login'] !== '') ? "disabled" : ""; ?>>
</div>

<div class="form-group">
<label>Fullname</label>
<input type="text" class="form-control" name="user_fullname" placeholder="Enter real name" value="<?=(isset($userData['user_fullname'])) ? htmlspecialchars($userData['user_fullname']) : ""; ?>
<?=(isset($formData['user_fullname'])) ? htmlspecialchars($formData['user_fullname'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : "";  ?>" >
</div>

<div class="form-group">
<label>Email (required)</label>
<input type="email" class="form-control" name="user_email" placeholder="Enter email" value="<?=(isset($userData['user_email'])) ? htmlspecialchars($userData['user_email']) : ""; ?>
<?=(isset($formData['user_email'])) ? htmlspecialchars($formData['user_email'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>" 
  required>
</div>

<div class="form-group">
<label>Password (required)</label>
<input type="password" class="form-control" name="user_pass" placeholder="Enter password" maxlength="50" autocomplete="off">
</div>

<?php if(!empty($userData['user_email'])) :?>
<div class="form-group">
<label>confirm Password (required)</label>
<input type="password" class="form-control" name="user_pass2" placeholder="Confirm password" maxlength="50" autocomplete="off">
</div>
<?php  endif; ?>

<div class="form-group">
<label>Website</label>
<input type="text" class="form-control" name="user_url" placeholder="Enter url" value="<?=(isset($formData['user_url'])) ? htmlspecialchars($formData['user_url'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>
<?=(isset($userData['user_url'])) ? $userData['user_url'] : ""; ?>" >
</div>

<div class="form-group">
<label>Role</label>
<?=(isset($userRole)) ? $userRole : ""; ?>
</div>

<?php 
if (empty($userData['user_email'])) :
?>
<div class="checkbox">
<label>
<input type="checkbox" name="send_user_notification" value="1"> Send the new user an email about their account 
</label>
</div>
<?php 
endif;
?>
</div>
<!-- /.box-body -->

<div class="box-footer">
<input type="hidden" name="csrfToken" value="<?=(isset($csrfToken)) ? $csrfToken : ""; ?>">
<input type="submit" class="btn btn-primary" name="userFormSubmit" value="<?=(isset($userData['ID']) && $userData['ID'] != '') ? "Update Profile" : "Add New User"; ?>" >
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