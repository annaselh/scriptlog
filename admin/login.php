<?php

if (file_exists(__DIR__ . '/../config.php')) {
    
  include(__DIR__ . '/../library/main.php');
    
} else {
    
    $config = require dirname(dirname(__FILE__)).'/config.sample.php';
    include(__DIR__ . '/../library/main-reserve.php');
}

$user = new User();
$authenticator = new Authentication();
$authenticator->setUser($user);
$userEvent = new UserEvent($user, $authenticator, $sanitizer);
$userApp = new UserApp($userEvent);

$loginFormSubmitted = isset($_POST['Login']);

if (empty($loginFormSubmitted) == false) {
    
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Log In | Scriptlog</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="assets/components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="assets/components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="assets/components/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="assets/dist/js/html5shiv.js"></script>
  <script src="assets/dist/js/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><img class="d-block mx-auto mb-4" src="assets/dist/img/icon612x612.png" alt="Scriptlog Installation Procedure" width="72" height="72"></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
  
    <form name="formlogin" action="login.php" method="post" onSubmit="return validasi(this)" role="form" autocomplete="off">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="user_email" placeholder="Email" required autofocus maxlength="186" value="<?= $authenticator->getValue("user_email"); ?>" >
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <p class="help-block"><?= "<span style=\"color:#ff0000;\">".$authenticator->getError("user_email")."</span>"; ?></p>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="user_pass" placeholder="Password" required maxlength="32" autocomplete="off" >
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <p class="help-block"><?= "<span style=\"color:#ff0000;\">".$authenticator->getError("user_pass")."</span>"; ?></p>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="rememberme" <?=($authenticator->getValue("rememberme") != "") ? "checked": ""?>> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
        <input type="hidden" name="csrfToken" value="<?= csrf_generate_token('csrfToken'); ?>">
        <input type="submit" class="btn btn-primary btn-block btn-flat" name="Login" value="Login">
        </div>
        <!-- /.col -->
      </div>
    </form>

    <a href="reset-password.php" class="text-center">Lost your password?</a>
    
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="assets/components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="assets/components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="assets/components/iCheck/icheck.min.js"></script>
<script src="assets/dist/js/checklogin.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>