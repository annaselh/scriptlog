<?php if (!defined('SCRIPTLOG')) exit(); ?>

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
      <h1>
        <?=(isset($pageTitle)) ? $pageTitle : ""; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">User</a></li>
        <li class="active">Profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              
             
              <h3 class="profile-username text-center"><?=(isset($userData['user_login'])) ? htmlspecialchars($userData['user_login']) : ""; ?></h3>

              <p class="text-muted text-center"><?=(isset($userData['user_fullname'])) ? htmlspecialchars($userData['user_fullname']) : ""; ?></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b><?=(isset($userData['user_url'])) ? htmlspecialchars($userData['user_url']) : "Site Address (URL)"; ?></b> <a class="pull-right" href="<?=($userData['user_url'] == '#') ? "#" : htmlspecialchars($userData['user_url']); ?>"><i class="fa fa-external-link"></i></a>
                </li>
              </ul>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">My profile</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
              <strong><i class="fa fa-user margin-r-5"></i> User Level</strong>

              <p class="text-muted">
                <?=(isset($userData['user_level'])) ? $userData['user_level'] : ""; ?>
              </p>

              <hr>

              <strong><i class="fa fa-envelope margin-r-5"></i> Email Address</strong>

              <p class="text-muted"><?=(isset($site_email)) ? $site_email : ""; ?></p>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
        <div class="box box-primary">
<div class="box-header with-border"></div>
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

<form name="scriptlogForm" method="post" action="index.php?load=users&action=<?=(isset($formAction)) ? $formAction : null; ?>&userId=<?=(isset($configData['ID'])) ? $configData['ID'] : 0; ?>" onSubmit="return checkFormSetting(this)" 
role="form" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="config_id" value="<?=(isset($configData['ID'])) ? $configData['ID'] : 0; ?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="697856" />

<div class="box-body">
<?php 
if ((isset($site_key)) && ($site_key != '')) : 
?>
<div class="form-group">
<label>License Key</label>
<input type="text" class="form-control" name="app_key" value="
<?=($site_key) ? htmlspecialchars($site_key) : ""; ?>"
<?=(!empty($site_key)) ? "disabled" : ""; ?>>
</div>
<?php endif;  ?>

<div class="form-group">
<label>Site Title (required)</label>
<input type="text" class="form-control" name="site_title" placeholder="Enter Site title here" value="
<?=(!empty($site_name)) ? htmlspecialchars($site_name) : ""; ?>
<?=(isset($formData['site_title'])) ? htmlspecialchars($formData['site_title'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>" required>
</div>

<div class="form-group">
<label>Site Address (required)</label>
<input type="url" class="form-control" name="app_url" placeholder="https://example.com" value="
<?=(isset($site_url) && $site_url != '#') ? htmlspecialchars($site_url) : ""; ?>
<?=(isset($formData['app_url'])) ? htmlspecialchars($formData['app_url'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>" required>
</div>

<div class="form-group">
<label>Email Address (required)</label>
<input type="email" class="form-control" name="email" placeholder="scriptlog@kartatopia.com" value="
<?=(!empty($site_email)) ? htmlspecialchars($site_email) : ""; ?>
<?=(isset($formData['email'])) ? htmlspecialchars($formData['email'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>" required>
<p class="help-block">This email address is used for admin purposes.</p>
</div>


</div>
<!-- /.box-body -->

<div class="box-footer">
<input type="hidden" name="csrfToken" value="<?=(isset($csrfToken)) ? $csrfToken : ""; ?>">  
<input type="submit" name="configFormSubmit" class="btn btn-primary" value="<?=(isset($config_id) && $config_id != '') ? "Update" : "Save Changes"; ?>">
</div>

</form>
            
</div>
<!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
</div>