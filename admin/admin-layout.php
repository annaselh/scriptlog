<?php 

function admin_header($stylePath, $breadCrumbs = null) {
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=(isset($breadCrumbs)) ? $breadCrumbs : "Scriptlog"; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= $stylePath; ?>/components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= $stylePath; ?>/components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= $stylePath; ?>/components/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= $stylePath; ?>/components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $stylePath; ?>/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?= $stylePath; ?>/dist/css/skins/scriptlog-skin.css">
 
<!-- wysiwyg editor-->
<script src="<?= $stylePath; ?>/wysiwyg/tiny_mce/jquery.tinymce.min.js" type="text/javascript"></script>
<script src="<?= $stylePath; ?>/wysiwyg/tiny_mce/tinymce.min.js" type="text/javascript"></script>
<script src="<?= $stylePath; ?>/wysiwyg/tiny_mce/tinysc.js" type="text/javascript"></script>

  
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<!-- Icon -->
<link href="<?= $stylePath; ?>/dist/img/favicon.ico" rel="Shortcut Icon" />
   
</head>

<body class="hold-transition skin-scriptlog sidebar-mini">

<div class="wrapper">
<?php 
}

function admin_footer($stylePath)
{
?>
 <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      <?php 
        echo APP_CODENAME;
       ?>
    </div>
    <!-- Default to the left -->
    <strong>Thank you for creating with 
    <a href="https://scriptlog.kartatopia.com">Scriptlog</a>
     <?php echo APP_VERSION; ?></strong>
  </footer>
  
   <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
  </div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?= $stylePath; ?>/components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= $stylePath; ?>/components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="<?= $stylePath; ?>/components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= $stylePath; ?>/components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= $stylePath; ?>/dist/js/adminlte.min.js"></script>
<!-- Slimscroll -->
<script src="<?= $stylePath; ?>/components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?= $stylePath; ?>/components/fastclick/lib/fastclick.js"></script>
<!-- Validate Image -->
<script src="<?= $stylePath; ?>/dist/js/imagevalidation.js"></script>
<script src="<?= $stylePath; ?>/dist/js/imagesizechecker.js"></script>

<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
</body>
</html>
<?php 
}