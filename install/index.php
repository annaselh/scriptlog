<?php
require('include/settings.php');
require('include/check-engine.php');
require('include/setup.php');

if (file_exists(__DIR__ . '/../config.php')) exit();

use Sinergi\BrowserDetector\Browser;

$completed = false;
$key = rand(0,9);
$token = bin2hex(openssl_random_pseudo_bytes(32).$key);

$install = isset($_POST['setup']) ? stripcslashes($_POST['setup']) : '';

if ($install != 'install') {
    
    if (version_compare(PHP_VERSION, '5.6', '>=')) {
        
        clearstatcache();
        
    } else {
        
        clearstatcache(true);
        
    }
    
    $_SESSION['install'] = false;
    
    header($installURL);
    
} else {
    
    $dbhost = isset($_POST['db_host']) ? $_POST['db_host'] : "";
    $dbname = filter_input(INPUT_POST, 'db_name', FILTER_SANITIZE_STRING);
    $dbuser = isset($_POST['db_user']) ? remove_bad_characters($_POST['db_user']) : "";
    $dbpass = isset($_POST['db_pass']) ? $_POST['db_pass'] : "";
    
    $username = isset($_POST['user_login']) ? remove_bad_characters($_POST['user_login']) : "";
    $password = isset($_POST['user_pass1']) ? $_POST['user_pass1'] : "";
    $confirm = isset($_POST['user_pass2']) ? $_POST['user_pass2'] : "";
    $email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
    
    $badCSRF = true; // check CSRF
    
    if (!isset($_POST['csrf']) || !isset($_SESSION['CSRF']) || empty($_POST['csrf'])
        || $_POST['csrf'] !== $_SESSION['CSRF']) {
            
         header($_SERVER['SERVER_PROTOCOL']." 400 Bad Request");   
         $errors['errorSetup'] = "Sorry, There is a security issue";
         $badCSRF = true;
         
     }
     
    if ($dbhost == '' || $dbname == '' || $dbuser == '' || $dbpass == '') {
        
        $errors['errorSetup'] = "database: requires name, hostname, user and password";
        
    } else {
        
        $link = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        
        if ($link -> connect_errno)
            $errors['errorSetup'] = 'Failed to connect to MySQL: (' . $link->connect_errno . ') '.$link->connect_error;
            
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        $errors['errorSetup'] = 'Please enter a valid email address';
        
    }
    
    if (empty($password) && empty($confirm)) $errors['errorSetup'] = 'Admin password must not be empty';
    
    elseif ($password != $confirm) $errors['errorSetup'] = 'Admin password must both be equal';
    
    if (empty($errors['errorSetup'])) {
        
        $badCSRF = false;
        unset($_SESSION['CSRF']);
        
        $completed = true;
        
        $_SESSION['install'] = true;
        
        $_SESSION['token'] = $token;
        
        if (check_mysql_version($link,'5.6.0')) 
            install_database_table($link, $username, $password, $email, $token);
            write_config_file($dbhost, $dbuser, $dbpass, $dbname, $email, $token);
            header("Location:".$protocol."://".$server_host.dirname($_SERVER['PHP_SELF'])."/finish.php?status=success&token=".$token);
        
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Scriptlog Installation">
    <link rel="icon" href="../favicon.ico">

    <title>Scritplog Installation</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/form-validation.css" rel="stylesheet">
  </head>

  <body class="bg-light">

    <div class="container">

      <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="assets/img/icon612x612.png" alt="Scriptlog Installation Procedure" width="72" height="72">
        <h2>Scriptlog</h2>
        <p class="lead">Installation procedure</p>
      </div>

      <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Getting System Info</span>
          </h4>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">PHP version</h6>
                 <?php 
                if (check_php_version()) :
                
                $php_passed = 'text-success';
                $php_checked = 'fa fa-check fa-lg';
                
                endif;
                
                ?>
                <small class="<?=(isset($php_passed)) ? $php_passed : 'text-danger'; ?>"><?=(isset($php_passed)) ? PHP_VERSION : $errors['errorChecking'] = 'Requires PHP 5.4 or newer'; ?></small>
              </div>
              <span class="<?=(isset($php_passed)) ? $php_passed : 'text-danger'; ?>"><i class="<?=(isset($php_checked)) ? $php_checked : 'fa fa-close fa-lg'; ?>"></i></span>
               
            </li>
            
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Operating System</h6>
                <?php 
             
                  $osname = check_os()['Operating_system'];
                  $oslist = array('Linux', 'OS X', 'FreeBSD', 'Chrome OS', 
                      'OpenBSD',  'NetBSD', 'OpenSolaris', 'Windows');
                  
                  foreach ($oslist as $operating_system) :
                  
                   if ($osname == $operating_system) {
                      
                       $os_passed = 'text-success';
                       $os_checked = 'fa fa-check fa-lg';
                       
                   }
                    
                  endforeach;
                ?>
                   <small class="<?=(isset($os_passed)) ? $os_passed : 'text-danger'; ?>"><?=(isset($os_passed)) ? $osname : $errors['errorChecking'] = 'Operating System Not Supported'; ?></small>
                </div>
                <span class="<?=(isset($os_passed)) ? $os_passed : 'text-danger'; ?>"><i class="<?=(isset($os_checked)) ? $os_checked : 'fa fa-close fa-lg'; ?>"></i></span>
             
            </li>
            
             <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Browser</h6>
                <?php 
               
                $browser = new Browser();
                
                $browserslist = array('Chrome', 'Firefox', 'Internet Explorer', 'Opera', 'Vivaldi');
                  
                  foreach ($browserslist as $browser_name) :
                      
                      if (check_browser() == $browser_name) {
                     
                          if (check_browser_version() === true) {
                              
                              $browser_failed = "text-danger";
                              $fabrowser_close = "fa fa-close fa-lg";
                          }
                         
                      }
                      
                  endforeach;
                  
                ?>
                   <small class="<?=(isset($browser_failed)) ? $browser_failed : 'text-success'; ?>"><?=(isset($browser_failed)) ? $errors['errorChecking'] = 'Please upgrade your browser' : $browser->getName(). ' ' .$browser->getVersion(); ?></small>
                </div>
                <span class="<?=(isset($browser_failed)) ? $browser_failed : 'text-success'; ?>"><i class="<?=(isset($fabrowser_close)) ? $fabrowser_close : 'fa fa-check fa-lg'; ?> ?>"></i></span>
             
            </li>
           
             <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Server</h6>
                <?php 
                 $web_server = check_web_server();
                 $server_name = $web_server['WebServer'];
                 $server_version = $web_server['Version'];
                 
                 $serverList = array('Apache', 'Litespeed');
                 
                 foreach ($serverList as $server) :
                 if ($server_name === $server) {
                 
                      $server_success = "text-success";
                      $server_checked = "fa fa-check fa-lg";
                      
                 }
                 endforeach;
                ?>
                <small class="<?=(isset($server_success)) ? $server_success : 'text-danger'; ?>"><?=(isset($server_name)) ? $server_name.' '.$server_version : $errors['errorChecking'] = 'Web server not supported'; ?></small>
              </div>
              <span class="<?=(isset($server_success)) ? $server_success : 'text-danger'; ?>"><i class="<?=(isset($server_checked)) ? $server_checked : 'fa fa-close fa-lg'; ?>"></i></span>
             
            </li>
            
          </ul>

         
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Required PHP Settings</span>
            
          </h4>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">PCRE UTF-8</h6>
                <?php 
                if ( check_pcre_utf8() == true ) {
                    $pcre_failed = 'text-danger';
                    $pcre_close = 'fa fa-close fa-lg';
                }
                ?>
                <small class="<?=(isset($pcre_failed)) ? $pcre_failed : 'text-success'; ?>"><?=(isset($pcre_failed)) ? $errors['errorChecking'] = 'PCRE has not been compiled with UTF-8 or Unicode property support' : 'Pass' ?></small>
              </div>
              <span class="<?=(isset($pcre_failed)) ? $pcre_failed : 'text-success'; ?>"><i class="<?=(isset($pcre_close)) ? $pcre_close : 'fa fa-check fa-lg' ?>"></i></span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">SPL Autoload Register</h6>
                <?php 
                if (check_spl_enabled('spl_autoload_register')) {
                    $spl_passed = 'text-success';
                    $spl_checked = 'fa fa-check fa-lg';
                }
                ?>
                <small class="<?=(isset($spl_passed)) ? $spl_passed : 'text-danger'; ?>"><?=(isset($spl_passed)) ? 'Pass' : $errors['errorChecking'] = 'spl autoload register is either not loaded or compiled in'; ?></small>
              </div>
              <span class="<?=(isset($spl_passed)) ? $spl_passed : 'text-danger'; ?>"><i class="<?=(isset($spl_checked)) ? $spl_checked : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>
             <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Filters Enabled</h6>
                <?php 
                if (check_filter_enabled()) {
                    $filter_passed = 'text-success';
                    $filter_checked = 'fa fa-check fa-lg';
                }
                ?>
                <small class="<?=(isset($filter_passed)) ? $filter_passed : 'text-danger'; ?>"><?=(isset($filter_passed)) ? 'Pass' : $errors['errorChecking'] = 'The filter extension is either not loaded or compiled in'; ?></small>
              </div>
              <span class="<?=(isset($spl_passed)) ? $spl_passed : 'text-danger'; ?>"><i class="<?=(isset($spl_checked)) ? $spl_checked : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Iconv Extension Loaded</h6>
                <?php 
                if (check_iconv_enabled()) {
                    
                    $iconv_passed = 'text-success';
                    $iconv_checked = 'fa fa-check fa-lg';
                }
                ?>
                <small class="<?=(isset($iconv_passed)) ? $iconv_passed : 'text-danger'; ?>"><?=(isset($iconv_passed)) ? 'Pass' : $errors['errorChecking'] = 'The Iconv extension is not loaded'; ?></small>
              </div>
              <span class="<?=(isset($iconv_passed)) ? $iconv_passed : 'text-danger'; ?>"><i class="<?=(isset($iconv_checked)) ? $iconv_checked : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>
             
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Character Type Extension</h6>
                <?php 
                if (check_character_type()) {
                    
                    $ctype_failed = 'text-danger';
                    $ctype_close = 'fa fa-close fa-lg';
                }
                ?>
                <small class="<?=(isset($ctype_failed)) ? $ctype_failed : 'text-success'; ?>"><?=(isset($ctype_failed)) ? $errors['errorChecking'] = 'The ctype extension is is overloading PHP\'s native string functions' : 'Pass' ; ?></small>
              </div>
              <span class="<?=(isset($ctype_failed)) ? $ctype_failed : 'text-success' ; ?>"><i class="<?=(isset($ctype_close)) ?  $ctype_close : 'fa fa-check fa-lg'; ?>"></i></span>
            </li>
            
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">GD Enabled</h6>
                <?php 
                if (check_gd_enabled()) {
                    
                    $gd_passed = 'text-success';
                    $gd_check = 'fa fa-check fa-lg';
                }
                ?>
                <small class="<?=(isset($gd_passed)) ? $gd_passed : 'text-danger'; ?>"><?=(isset($gd_passed)) ? 'Pass' : $errors['errorChecking'] = 'requires GD v2 for the image manipulation'; ?></small>
              </div>
              <span class="<?=(isset($gd_passed)) ? $gd_passed : 'text-danger' ; ?>"><i class="<?=(isset($gd_check)) ?  $gd_check : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>
            
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">PDO MySQL Enabled</h6>
                <?php 
                if (check_pdo_mysql()) {
                    
                    $pdo_passed = 'text-success';
                    $pdo_check = 'fa fa-check fa-lg';
                }
                ?>
                <small class="<?=(isset($pdo_passed)) ? $pdo_passed : 'text-danger'; ?>"><?=(isset($pdo_passed)) ? 'Pass' : $errors['errorChecking'] = 'requires PDO MySQL enabled'; ?></small>
              </div>
              <span class="<?=(isset($pdo_passed)) ? $pdo_passed : 'text-danger' ; ?>"><i class="<?=(isset($pdo_check)) ?  $pdo_check : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>
            
             <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">MySQL Improved Enabled</h6>
                <?php 
                if (check_mysqli_enabled()) {
                    
                    $mysqli_passed = 'text-success';
                    $mysqli_check = 'fa fa-check fa-lg';
                }
                ?>
                <small class="<?=(isset($mysqli_passed)) ? $mysqli_passed : 'text-danger'; ?>"><?=(isset($mysqli_passed)) ? 'Pass' : $errors['errorChecking'] = 'requires MySQL improved enabled'; ?></small>
              </div>
              <span class="<?=(isset($mysqli_passed)) ? $mysqli_passed : 'text-danger' ; ?>"><i class="<?=(isset($mysqli_check)) ?  $mysqli_check : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>
            
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">URI Determination</h6>
                <?php 
                if (check_uri_determination()) {
                    
                    $uri_passed = 'text-success';
                    $uri_check = 'fa fa-check fa-lg';
                }
                ?>
                <small class="<?=(isset($uri_passed)) ? $uri_passed : 'text-danger'; ?>"><?=(isset($uri_passed)) ?  'Pass' : $errors['errorChecking'] = 'Neither $_SERVER[REQUEST_uri], $_SERVER[PHP_SELF] or $_SERVER[PATH_INFO] is available' ; ?></small>
              </div>
              <span class="<?=(isset($uri_passed)) ? $uri_passed : 'text-danger' ; ?>"><i class="<?=(isset($uri_check)) ?  $uri_check : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>
          </ul>
          
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Modes</span>
          </h4>
           <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Mode Rewrite</h6>
                <?php 
                if (check_modrewrite()) :
                  $mode_rewrite_passed = 'text-success';
                  $mode_rewrite_check = 'fa fa-check fa-lg';
                endif;
                ?>
                <small class="<?=(isset($mode_rewrite_passed)) ? $mode_rewrite_passed : 'text-danger'; ?>"><?=(isset($mode_rewrite_passed)) ? 'Pass' : $errors['errorChecking'] = 'Requires mode rewrite enabled'; ?></small>
              </div>
              <span class="<?=(isset($mode_rewrite_passed)) ? $mode_rewrite_passed : 'text-danger'; ?>"><i class="<?=(isset($mode_rewrite_check)) ? $mode_rewrite_check : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>
         
          </ul>
          
          
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Directories and Files</span>
          </h4>
          <ul class="list-group mb-3">
          <li class="list-group-item d-flex justify-content-between lh-condensed" >
              <div>
                 <h6 class="my-0">Main Engine</h6>
                 <?php 
                  if (check_main_dir()) :
                   
                   $main_passed = 'text-success';
                   $main_checked = 'fa fa-check fa-lg';
                   
                  endif;
                 ?>
                  <small class="<?=(isset($main_passed)) ? $main_passed : 'text-danger'; ?>"><?=(isset($main_passed)) ? 'Pass' : $errors['errorChecking'] = 'Required file not found'; ?></small>
              </div>
              <span class="<?=(isset($main_passed)) ? $main_passed : 'text-danger' ?>"><i class="<?=(isset($main_checked)) ? $main_checked : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>
            
            <li class="list-group-item d-flex justify-content-between lh-condensed" >
              <div>
                 <h6 class="my-0">Load Engine</h6>
                 <?php 
                  if (check_loader()) :
                   
                   $init_passed = 'text-success';
                   $init_checked = 'fa fa-check fa-lg';
                   
                  endif;
                 ?>
                  <small class="<?=(isset($init_passed)) ? $init_passed : 'text-danger'; ?>"><?=(isset($init_passed)) ? 'Pass' : $errors['errorChecking'] = 'Required file not found'; ?></small>
              </div>
              <span class="<?=(isset($init_passed)) ? $init_passed : 'text-danger' ?>"><i class="<?=(isset($init_checked)) ? $init_checked : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>
            
             <li class="list-group-item d-flex justify-content-between lh-condensed" >
              <div>
                 <h6 class="my-0">Logs Directory</h6>
                 <?php 
                  if (check_log_dir()) :
                   
                   $log_passed = 'text-success';
                   $log_checked = 'fa fa-check fa-lg';
                   
                  endif;
                 ?>
                  <small class="<?=(isset($log_passed)) ? $log_passed : 'text-danger'; ?>"><?=(isset($log_passed)) ? 'public/log writeable' : $errors['errorChecking'] = 'public/log directory is not writeable'; ?></small>
              </div>
              <span class="<?=(isset($log_passed)) ? $log_passed : 'text-danger' ?>"><i class="<?=(isset($log_checked)) ? $log_checked : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>
            
             <li class="list-group-item d-flex justify-content-between lh-condensed" >
              <div>
                 <h6 class="my-0">Cache Directory</h6>
                 <?php 
                  if (check_cache_dir()) :
                   
                   $cache_passed = 'text-success';
                   $cache_checked = 'fa fa-check fa-lg';
                   
                  endif;
                 ?>
                  <small class="<?=(isset($cache_passed)) ? $cache_passed : 'text-danger'; ?>"><?=(isset($cache_passed)) ? 'public/cache writeable' : $errors['errorChecking'] = 'public/cache directory is not writeable'; ?></small>
              </div>
              <span class="<?=(isset($cache_passed)) ? $cache_passed : 'text-danger' ?>"><i class="<?=(isset($cache_checked)) ? $cache_checked : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>

            <li class="list-group-item d-flex justify-content-between lh-condensed" >
              <div>
                 <h6 class="my-0">Theme Directory</h6>
                 <?php 
                  if (check_theme_dir()) :
                   
                   $theme_passed = 'text-success';
                   $theme_checked = 'fa fa-check fa-lg';
                   
                  endif;
                 ?>
                  <small class="<?=(isset($theme_passed)) ? $theme_passed : 'text-danger'; ?>"><?=(isset($theme_passed)) ? 'public/themes writeable' : $errors['errorChecking'] = 'public/themes directory is not writeable'; ?></small>
              </div>
              <span class="<?=(isset($theme_passed)) ? $theme_passed : 'text-danger' ?>"><i class="<?=(isset($theme_checked)) ? $theme_checked : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>

            <li class="list-group-item d-flex justify-content-between lh-condensed" >
              <div>
                 <h6 class="my-0">Plug-in Directory</h6>
                 <?php 
                  if (check_plugin_dir()) :
                   
                   $plugin_passed = 'text-success';
                   $plugin_checked = 'fa fa-check fa-lg';
                   
                  endif;
                 ?>
                  <small class="<?=(isset($plugin_passed)) ? $plugin_passed : 'text-danger'; ?>"><?=(isset($plugin_passed)) ? 'library/plugins writeable' : $errors['errorChecking'] = 'library/plugins directory is not writeable'; ?></small>
              </div>
              <span class="<?=(isset($plugin_passed)) ? $plugin_passed : 'text-danger' ?>"><i class="<?=(isset($plugin_checked)) ? $plugin_checked : 'fa fa-close fa-lg'; ?>"></i></span>
            </li>

          </ul>
        </div>
        
        <div class="col-md-8 order-md-1">
        <?php 
        if (count($errors) && !empty($errors['errorChecking']) === true) :
        ?>
         <div class="alert alert-danger" role="alert">
          Scriptlog may not work correctly with your environment
        </div>
        <?php 
        elseif (isset($errors['errorSetup']) && (!$completed)) :
        ?>
         <div class="alert alert-danger" role="alert">
        <?= $errors['errorSetup']; ?>
         </div>
        <?php 
        else:
        ?>
          <div class="alert alert-success" role="alert">
              Below you should enter all information needed. We’re going to use this information to create a config.php file 
          </div>
       
          <h4 class="mb-3">Database Settings</h4>
          <form method="post" action="<?php echo $installURL; ?>" class="needs-validation" novalidate>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="databaseHost">Database Host</label>
                <input type="text" class="form-control" id="databaseHost" name="db_host" placeholder="Hostname or the database server IP" value="<?=(isset($_POST['db_host'])) ? escapeHTML($_POST['db_host'])  : ""; ?>"  required>
                <div class="invalid-feedback">
                  Valid database host is required.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName">Database Name</label>
                <input type="text" class="form-control" id="databaseName" name="db_name" placeholder="Database name" value="<?=(isset($_POST['db_name'])) ? escapeHTML($_POST['db_name']) : "";  ?>" required>
                <div class="invalid-feedback">
                  Valid database name is required.
                </div>
              </div>
            </div>

            <div class="row">
                 <div class="col-md-6 mb-3">
                <label for="databaseUser">Database Username</label>
                <input type="text" class="form-control" id="databaseUser" name="db_user" placeholder="Database username" value="<?=(isset($_POST['db_user'])) ? escapeHTML($_POST['db_user']) : ""; ?>" required>
                <div class="invalid-feedback">
                  Valid database username is required.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="databasePass">Database Password</label>
                <input type="password" class="form-control" id="databasePass" name="db_pass" placeholder="Database password" required>
                <div class="invalid-feedback">
                  Valid database password is required.
                </div>
              </div>
            </div>
            
            <hr class="mb-4">
                       
            <h4 class="mb-3">Administrator Account</h4>

            <div class="mb-3">
              <label for="username">Username</label>
              <input type="text" class="form-control" name="user_login" id="username" placeholder="username for administrator" value="<?=(isset($_POST['user_login'])) ? escapeHTML($_POST['user_login']) : ""; ?>" required>
              <div class="invalid-feedback">
                Your username is required.
              </div>
            </div>
             <div class="mb-3">
              <label for="pass">Password</label>
              <input type="password" class="form-control" name="user_pass1" id="pass1" placeholder="Enter your password" required>
              <div class="invalid-feedback">
                Please enter your password.
              </div>
            </div>
             <div class="mb-3">
              <label for="pass2">Confirm password</label>
              <input type="password" class="form-control" name="user_pass2" id="pass2" placeholder="Confirm your password" required>
              <div class="invalid-feedback">
                Please confirm your password.
              </div>
            </div>
             <div class="mb-3">
              <label for="email">Email <span class="text-muted">(Administrator's E-mail)</span></label>
              <input type="email" class="form-control" id="email" name="user_email" placeholder="you@example.com" value="<?=(isset($_POST['user_email'])) ? escapeHTML($_POST['user_email']) : ""; ?>" required>
              <div class="invalid-feedback">
                You must provide an email address.
              </div>
            </div>
             <div class="row"></div>
            <hr class="mb-4">
     <?php
     $bytes = null;
     $length = 64;
     if (function_exists("random_bytes")) {
         
         $bytes = random_bytes(ceil($length / 2));
         
     } elseif (function_exists("openssl_random_pseudo_bytes")) {
         
         $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
         
     } else {
         
         throw new Exception("no cryptographically secure random function available");
         
     }
     
     $CSRF = substr(bin2hex($bytes.$key), 0, $length);
     $_SESSION['CSRF'] = $CSRF;
     
     ?>
     
     <input type="hidden" name="csrf" value="<?= $CSRF; ?>"/>
     <input type="hidden" name="setup" value="install">
     <button class="btn btn-success btn-lg btn-block" type="submit">Install</button>
    </form>
    
     <?php 
        endif;
      ?>
      
  </div>

  </div>

  <footer class="my-5 pt-5 text-muted text-center text-small">
    <p class="mb-1">&copy; 
       <?php 
               
          $starYear = 2013;
          $thisYear = date ( "Y" );
          if ($starYear == $thisYear) {
             
              echo $starYear;
             
          } else {
              
              echo " {$starYear} &#8211; {$thisYear} ";
           }
                     
             echo "Scriptlog";
              
             $execution_time = (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]);
              
        ?>
         
        </p>
        
        <ul class="list-inline">
          <li class="list-inline-item"><a href="<?php echo $installURL . '../LICENSE'; ?>" target="_blank">License</a></li>
          <li class="list-inline-item"><a href="#"><?php echo 'Memory used '. round(memory_get_usage()/1048576,2).''.' MB'; ?></a></li>
          <li class="list-inline-item"><a href="#"><?php echo 'Execution time '. $execution_time; ?></a></li>
        </ul>
      </footer>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/vendor/bootstrap/js/jquery-3.3.1.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="assets/vendor/bootstrap/js/jquery-slim.min.js"><\/script>')</script>
    <script src="assets/vendor/bootstrap/js/vendor/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/vendor/bootstrap/js/holder.min.js"></script>
    <script>
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function() {
        'use strict';

        window.addEventListener('load', function() {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');

          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
    </script>
  </body>
</html>