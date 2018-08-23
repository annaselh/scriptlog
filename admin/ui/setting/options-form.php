<?php if (!defined('SCRIPTLOG')) exit(); ?>

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
      <h1>
        <?=(isset($pageTitle)) ? $pageTitle : ""; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Setting</a></li>
        <li class="active">General Setting</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <?php 
              if ($configData['logo'] != '') : 

                $logo = __DIR__ . '/../public/files/pictures/'.$configData['logo'];
                $logo_thumbs = __DIR__ . '/../public/files/pictures/thumbs/thumb_'.$configData['logo'];
                
                if (!is_file($logo_thumbs)) {
                   $logo = __DIR__ . '/../public/files/pictures/thumbs/nophoto.jpg';
                }

                
              ?>

              <img class="profile-user-img img-responsive img-circle" src="<?= $logo_thumbs; ?>" alt="<?= 'logo of '.$configData['site_name'] ?>">

             <?php endif; ?>

              <h3 class="profile-username text-center"><?=(isset($configData['site_name'])) ? htmlspecialchars($configData['site_name']) : ""; ?></h3>

              <p class="text-muted text-center"><?=(isset($configData['app_key'])) ? htmlspecialchars($configData['app_key']) : ""; ?></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Site Address (URL)</b> <a class="pull-right"><?=(isset($configData['app_url'])) ? htmlspecialchars($configData['app_url']) : ""; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Facebook</b> <a class="pull-right"><?=(isset($configData['facebook'])) ? $configData['facebook'] : ""; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Twitter</b> <a class="pull-right"><?=(isset($configData['twitter'])) ? $configData['twitter'] : ""; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Instagram</b> <a class="pull-right"><?=(isset($configData['instagram'])) ? $configData['instagram'] : ""; ?></a>
                </li>
              </ul>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Site Info</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Meta Description</strong>

              <p class="text-muted">
                <?=(isset($configData['meta_description'])) ? $configData['meta_description'] : ""; ?>
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Meta Keywords</strong>

              <p class="text-muted"><?=(isset($configData['meta_keywords'])) ? $configData['meta_keywords'] : ""; ?></p>

              <hr>

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

<form method="post" action="index.php?load=settings&action=<?=(isset($formAction)) ? $formAction : null; ?>&settingId=<?=(isset($configData['ID'])) ? $configData['ID'] : 0; ?>" role="form">
<input type="hidden" name="config_id" value="<?=(isset($configData['ID'])) ? $configData['ID'] : 0; ?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="697856" />

<div class="box-body">
<?php 
if (isset($configData['app_key']) && $configData['app_key'] != '') : 
?>
<div class="form-group">
<label>License Key</label>
<input type="text" class="form-control" name="app_key" value="
<?=(isset($configData['app_key'])) ? htmlspecialchars($configData['app_key']) : ""; ?>"
<?=(isset($configData['app_key']) && $configData['app_key'] != 0) ? "disabled" : ""; ?>>
</div>
<?php endif;  ?>

<div class="form-group">
<label>Site Title</label>
<input type="text" class="form-control" name="site_title" placeholder="Enter Site title here" value="
<?=(isset($configData['site_name'])) ? htmlspecialchars($configData['site_name']) : ""; ?>
<?=(isset($formData['site_title'])) ? htmlspecialchars($formData['site_title'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>">
</div>

<div class="form-group">
  <label>Site Address (URL)</label>
  <input type="text" class="form-control" name="app_url" placeholder="Enter the site address here"  value="
  <?=(isset($configData['app_url'])) ? htmlspecialchars($configData['app_url']) : ""; ?>" >
</div>

<?php 
if (isset($configData['logo'])) :
?>
<div class="form-group">
<?php 
$imageThumb = __DIR__ . '/../public/files/pictures/thumbs/thumb_'.$configData['logo'];

if (!is_readable($imageThumb)) :
    $imageThumb = __DIR__ . '/../public/files/pictures/thumbs/nophoto.jpg';
endif;

if (is_readable($image)) :
?>
<br><a href="<?php echo $image; ?>"><img src="<?php  echo $imageThumb; ?>" class="img-responsive pad"></a><br> 
<label>change logo :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)" maxlength="512" />
<img id="output" class="img-responsive pad" />
<p class="help-block">Maximum file size: <?= format_size_unit(697856); ?></p>
<?php 
else :
?>
<br><img src="<?php echo $imageThumb; ?>" class="img-responsive pad"><br> 
<label>change logo :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)"  maxlength="512" />
<img id="output" class="img-responsive pad" />
<p class="help-block">Maximum file size: <?= format_size_unit(697856); ?></p>
<?php 
endif;
?>
</div>
<?php else : ?>
<div class="form-group">
<label>Upload logo :</label> 
<input type="file" name="image" id="file" accept="image/*" onchange="loadFile(event)"  maxlength="512" />
<img id="output" class="img-responsive pad" />
<p class="help-block">Maximum file size: <?= format_size_unit(697856); ?></p>
</div>
<?php 
endif;
?>

<div class="form-group">
<label>Meta Description</label>
<textarea class="form-control" name="meta_description" rows="3" maxlength="500" >
<?=(isset($configData['meta_description'])) ? $configData['meta_description'] : ""; ?>
<?=(isset($formData['meta_description'])) ? htmlspecialchars($formData['meta_description'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>
</textarea>
</div>

<div class="form-group">
<label>Meta Keywords</label>
<textarea class="form-control" name="meta_keywords" rows="3" maxlength="200" >
<?=(isset($configData['meta_keywords'])) ? $configData['meta_keywords'] : ""; ?>
<?=(isset($formData['meta_keywords'])) ? htmlspecialchars($formData['meta_keywords'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?>
</textarea>
</div>

<div class="form-group">
  <label>Facebook</label>
  <input type="text" class="form-control" name="facebook" placeholder="facebook.com/mnoermoehammad"  value="
  <?=(isset($configData['facebook'])) ? htmlspecialchars($configData['facebook']) : ""; ?>" >
</div>

<div class="form-group">
  <label>Twitter</label>
  <input type="text" class="form-control" name="twitter" placeholder="@MaleoSenkawor"  value="
  <?=(isset($configData['twitter'])) ? htmlspecialchars($configData['twitter']) : ""; ?>" >
</div>

<div class="form-group">
  <label>Instagram</label>
  <input type="text" class="form-control" name="instagram" placeholder="MaleoSenkawor"  value="
  <?=(isset($configData['instagram'])) ? htmlspecialchars($configData['instagram']) : ""; ?>" >
</div>

</div>
<!-- /.box-body -->

<div class="box-footer">
<input type="hidden" name="csrfToken" value="<?=(isset($csrfToken)) ? $csrfToken : ""; ?>">  
<input type="submit" name="configFormSubmit" class="btn btn-primary" value="<?=(isset($configData['ID']) && $configData['ID'] != '') ? "Update" : "Publish"; ?>">
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