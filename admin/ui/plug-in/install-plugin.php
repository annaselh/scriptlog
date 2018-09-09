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
        <li><a href="index.php?load=plugins">Plugins</a></li>
        <li class="active"><?=(isset($pageTitle)) ? $pageTitle : ""; ?></li>
      </ol>
    </section>

 <!-- Main content -->
<section class="content">
<div class="row">
<div class="col-md-6">
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

<form method="post" action="index.php?load=plugins&action=<?=(isset($formAction)) ? $formAction : null; ?>&pluginId=<?=(isset($pluginData['ID'])) ? $pluginData['ID'] : 0; ?>" 
  role="form" enctype="multipart/form-data" autocomplete="off">
<div class="box-body">

<div class="form-group">
<label>Upload Plugin (required)</label>
<input type="file" name="zip_file" required>
<p class="help-block">If you have a plugin in a .zip format, you may install it by uploading it here.</p>
</div>

<div class="form-group">
<label>Description (required)</label>
<textarea class="form-control" id="sl" name="description" rows="10" maxlength="100000" required>
<?=(isset($pluginData['plugin_desc'])) ? $pluginData['plugin_desc'] : ""; ?>
<?=(isset($formData['description'])) ? $formData['description'] : ""; ?>
</textarea>
</div>

<div class="form-group">
<label>Level</label>
<?=(isset($pluginLevel)) ? $pluginLevel : ""; ?>
</div>
<!-- /.plugin level -->

</div>
<!-- /.box-body -->
<div class="box-footer">
<input type="hidden" name="csrfToken" value="<?=(isset($csrfToken)) ? $csrfToken : ""; ?>">  
<input type="submit" name="pluginFormSubmit" class="btn btn-primary" value="Install Now">

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
