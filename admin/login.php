<?php

if (file_exists(__DIR__ . '/../config.php')) {
    
  include(__DIR__ . '/../library/main.php');
    
} else {
    
    $config = require dirname(dirname(__FILE__)).'/config.sample.php';
    include(__DIR__ . '/../library/main-dev.php');
}

$errors = [];
  $loginFormSubmitted = isset($_POST['Login']);
  
  if (empty($loginFormSubmitted) == false) {
    
    $userDao = new User();
    $validator = new FormValidator();
    $authenticator = new Authentication($userDao, $validator);
  
    $user_email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
    $user_pass = isset($_POST['user_pass']) ? prevent_injection($_POST['user_pass']) : "";
  
    $badCSRF = true;
  
    $_SESSION['userLoggedIn'] = false;
  
    if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf'])
     || $_POST['csrf'] !== $_SESSION['CSRF']) {
         
      $errors['errorMessage'] = 'Sorry, there was a security issue';
     
      $badCSRF = true;
     
   } elseif (empty($user_email) || empty($user_pass)) {
  
     $errors['errorMessage'] = "All Column must be filled";
  
   } elseif (email_validation($user_email) == 0) {
  
     $errors['errorMessage'] = "Please enter a valid email address";
  
   } elseif ($authenticator -> checkEmailExists($user_email) === false) {
  
     $errors['errorMessage'] = "Your email address is not registered";
  
   } elseif (strlen($user_pass) < 8) {
  
     $errors['errorMessage'] = "Your password must consist of least 8 characters";
  
   } elseif (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $user_pass)) {
  
     $errors['errorMessage'] = "Password does not meet the requirements";
     
   } elseif ($authenticator -> validateUserAccount($user_email, $user_pass) === false) { 
  
     $errors['errorMessage'] = "Your email or password is incorrect!";
  
   } else {
  
    $badCSRF = false;
    unset($_SESSION['CSRF']);

    $_SESSION['userLoggedIn'] = true;

    $authenticator -> login($_POST);
  
   }
  
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
    
    <?php if (isset($errors['errorMessage'])) { ?>
  
          
  <div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert"
      aria-hidden="true">&times;</button>
    <?php echo $errors['errorMessage']; ?>
  </div>
  
  <?php 	} 
  
  if (isset($_GET['status']) && $_GET['status'] == 'ganti'){
  
     echo '<div class="alert alert-info alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Kata sandi sudah di' . $_GET['status'] . '. Silahkan masuk!</div>';
  
  }elseif (isset($_GET['status']) && $_GET['status'] == 'aktif')
  {
    echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Akun sudah di' . $_GET['status'] . 'kan. Silahkan masuk!</div>';
  }
  
  ?>
  
    <form name="formlogin" action="login.php" method="post" onSubmit="return validasi(this)" role="form" autocomplete="off">
        <div class="form-group has-feedback">
          <input type="email" class="form-control" name="user_email" placeholder="Email" required autofocus maxlength="186" 
          value="<?=(isset($_POST['user_email'])) ? htmlspecialchars(stripslashes($_POST['user_email']), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : ""; ?> " >
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" name="user_pass" placeholder="Password" required maxlength="32" autocomplete="off" >
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          
        </div>
        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox" name="rememberme" <?=(isset($_POST['rememberme']) != "") ? "checked": ""; ?>> Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-xs-4">
          <?php 
      // prevent CSRF
      $key= random_generator(13);
      $CSRF = bin2hex(openssl_random_pseudo_bytes(32).$key);
      $_SESSION['CSRF'] = $CSRF;
      ?>
       <input type="hidden" name="csrf" value="<?php echo $CSRF; ?>">
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
