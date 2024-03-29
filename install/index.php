<?php 
/**
 * File index.php 
 * 
 * @package   SCRIPTLOG INSTALLATION
 * @category  install\index.php file
 * @author    M.Noermoehammad
 * @license   MIT
 * @version   0.1
 * @since     Since Release 0.1
 * 
 */
require dirname(__FILE__) . '/include/settings.php';
require dirname(__FILE__) . '/include/check-engine.php';
require dirname(__FILE__) . '/include/setup.php';
require dirname(__FILE__) . '/install-layout.php';

use Sinergi\BrowserDetector\Browser;

if (file_exists(__DIR__ . '/../config.php')) {

  $set_config = require __DIR__ . '/../config.php';

  $dbconnect = make_connection($set_config['db']['host'], 
          $set_config['db']['user'], 
          $set_config['db']['pass'], 
          $set_config['db']['name']);
  
  if($dbconnect instanceof mysqli) {

     if($dbconnect->connect_errno) {

       $errors['errorSetup'] = "Faild to connect to MySQL " . $dbconnect->connect_error;

     }
     
  }

// check if database table exists or not
if((check_dbtable($dbconnect, 'users') == true) || (check_dbtable($dbconnect, 'user_token') == true)
|| (check_dbtable($dbconnect, 'topics') == true) || (check_dbtable($dbconnect, 'themes') == true)
|| (check_dbtable($dbconnect, 'settings') == true) || (check_dbtable($dbconnect, 'posts') == true)
|| (check_dbtable($dbconnect, 'post_topic') == true) || (check_dbtable($dbconnect, 'plugin') == true)
|| (check_dbtable($dbconnect, 'menu_child') == true) || (check_dbtable($dbconnect, 'menu') == true)
|| (check_dbtable($dbconnect, 'mediameta') == true) || (check_dbtable($dbconnect, 'media') == true)
|| (check_dbtable($dbconnect, 'comments') == true) 
|| (check_dbtable($dbconnect, 'comment_reply') == true)) {

  $create_db = $protocol . '://' . $server_host . dirname($_SERVER['PHP_SELF']) . DIRECTORY_SEPARATOR .'install.php';

  header("Location: $create_db");

} else {

   header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
   exit("Database has been installed!");

}

} else {

  $current_path = preg_replace("/\/index\.php.*$/i", "", current_url());

  $installation_path = $protocol . '://' . $server_host . dirname($_SERVER['PHP_SELF']) . DIRECTORY_SEPARATOR;

  $clean_setup = array();

  $completed = false;

  $install = isset($_POST['setup']) ? stripcslashes($_POST['setup']) : '';

if ($install != 'install') {
    
    if (version_compare(PHP_VERSION, '5.6', '>=')) {

        clearstatcache();

    } else {

        clearstatcache(true);
        
    }
    
    $_SESSION['install'] = false;

    header($installation_path);
    
  } else {
    
    $dbhost = isset($_POST['db_host']) ? $_POST['db_host'] : "";
    $dbname = filter_input(INPUT_POST, 'db_name', FILTER_SANITIZE_STRING);
    $dbuser = isset($_POST['db_user']) ? remove_bad_characters($_POST['db_user']) : "";
    $dbpass = isset($_POST['db_pass']) ? $_POST['db_pass'] : "";
    
    $username = isset($_POST['user_login']) ? remove_bad_characters($_POST['user_login']) : "";
    $password = isset($_POST['user_pass1']) ? $_POST['user_pass1'] : "";
    $confirm = isset($_POST['user_pass2']) ? $_POST['user_pass2'] : "";
    $email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
    
    if ($dbhost == '' || $dbname == '' || $dbuser == '' || $dbpass == '') {
        
        $errors['errorSetup'] = "database: requires name, hostname, user and password";
        
    } 

    $link = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        
    if ($link->connect_errno) {

        $errors['errorSetup'] = " Failed to connect to MySQL: " . $link->connect_error;

    }

    if(ctype_alnum($username) && (mb_strlen($username) > 0) && (mb_strlen($username) <= 32)) {
      
       $clean_setup['username'] = $username;

    } else {

       $errors['errorSetup'] = 'Please enter a valid username with only alphabetic and numeric characters, at least 0-32 characters length';

    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        $errors['errorSetup'] = 'Please enter a valid email address';
        
    }
    
    if (empty($password) && (empty($confirm))) { 

        $errors['errorSetup'] = 'Admin password should not be empty';

    } elseif ($password != $confirm) {

        $errors['errorSetup'] = 'Admin password should both be equal';

    } elseif (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,50}$/', $password)) {

        $errors['errorSetup'] = 'Admin password may contain letter and numbers, at least one number and one letter, any of these characters !@#$%';

    }
    
    if (!is_writable(__DIR__ . '/index.php')) {
       
       $errors['errorSetup'] = 'Permission denied. Directory installation is not writable';
       
    }

    if (false === check_php_version()) {

       $errors['errorSetup'] = 'Requires PHP 5.6 or newer';

    }

    if (true === check_pcre_utf8()) {

       $errors['errorSetup'] = 'PCRE has not been compiled with UTF-8 or Unicode property support';

    }

    if (false === check_spl_enabled('spl_autoload_register')) {

        $errors['errorSetup'] = 'spl autoload register is either not loaded or compiled in';

    }

    if (false === check_filter_enabled()) {

       $errors['errorSetup'] = 'The filter extension is either not loaded or compiled in';

    }

    if (false === check_iconv_enabled()) {

       $errors['errorSetup'] = 'The Iconv extension is not loaded';

    }

    if (true === check_character_type()) {

       $errors['errorSetup'] = 'The ctype extension is overloading PHP\'s native string functions';

    } 

    if (false === check_gd_enabled()) {
       
       $errors['errorSetup'] = 'requires GD v2 for the image manipulation';

    }

    if (false === check_pdo_mysql()) {

       $errors['errorSetup'] = 'requires PDO MySQL enabled';

    }

    if (false === check_mysqli_enabled()) {

       $errors['errorSetup'] = 'requires MySQLi enabled';
       
    }

    if (false === check_uri_determination()) {

      $errors['errorSetup'] = 'Neither $_SERVER[REQUEST_URI], $_SERVER[PHP_SELF] or $_SERVER[PATH_INFO] is available';

    }

    if (empty($errors['errorSetup']) === true) {
        
        $completed = true;
        
        $length = 32;

        $_SESSION['install'] = true;
        
        if (function_exists("random_bytes")) {
       
          $token = random_bytes(ceil($length / 2));
          
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
          
          $token = openssl_random_pseudo_bytes(ceil($length / 2));
          
        } else {
          
          trigger_error("No cryptographically secure random function available", E_USER_ERROR);
          
        }
              
        $key = bin2hex($token);
        
        $_SESSION['token'] = $key;
        
        if (check_mysql_version($link, "5.5")) {

          install_database_table($link, $protocol, $server_host, $clean_setup['username'], $password, $email, $key);

          if (true === write_config_file($protocol, $server_host, $dbhost, $dbuser, $dbpass, $dbname, $email, $key)) {

            header("Location:".$protocol."://".$server_host.dirname($_SERVER['PHP_SELF']).DIRECTORY_SEPARATOR."finish.php?status=success&token={$key}");

          }
        
        }
        
    }
    
}

install_header($current_path, $protocol, $server_host);

?>

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

    <?= get_sisfo(); ?>
   
    <h4 class="d-flex justify-content-between align-items-center mb-3">
      <span class="text-muted">Required PHP Settings</span>
    </h4>

    <?= required_settings(); ?>
    
    <?= check_mod_rewrite(); ?>
    
    <h4 class="d-flex justify-content-between align-items-center mb-3">
      <span class="text-muted">Directories and Files</span>
    </h4>
    
    <?= check_dir_file(); ?>

</div>
        
        <div class="col-md-8 order-md-1">
        <?php 
        if (isset($errors['errorSetup']) && (!$completed)):
        ?>
         <div class="alert alert-danger"  role="alert">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
         </button>
         <?= $errors['errorSetup']; ?>
        </div>
        <?php 
          endif;
        ?>
          <div class="alert alert-success" role="alert">
            We are going to use this information to create a config.php file. 
            You should enter your database connection details and administrator account. 
          </div>
          
          <form method="post" action="<?= $installation_path; ?>" class="needs-validation" novalidate>
          
            <h4 class="mb-3">Database Settings</h4>

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
                Please enter a valid email address.
              </div>
            </div>
             <div class="row"></div>
            <hr class="mb-4">
   
     <input type="hidden" name="setup" value="install">
     <button class="btn btn-success btn-lg btn-block" type="submit">Install</button>
    </form>
    
  </div>

  </div>

<?php

install_footer($current_path, $protocol, $server_host);

ob_end_flush();

} 