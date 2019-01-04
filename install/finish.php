<?php 
/**
 * File finish.php
 * 
 * @category  installation file finish.php
 * @package   SCRIPTLOG INSTALLATION
 * @author    M.Noermoehammad
 * @license   MIT
 * @version   1.0
 *   
 * */
require dirname(__FILE__) . '/include/settings.php';
require dirname(__FILE__) . '/include/setup.php';
require dirname(__FILE__) . '/install-layout.php';

$installation_path = preg_replace("/\/index\.php.*$/i", "", current_url());

install_header($installation_path, $protocol, $server_host);

?>

<div class="container">
     <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="<?= $installation_path; ?>assets/img/icon612x612.png" alt="Scriptlog Installation Completed" width="72" height="72">
        <h2>Scriptlog</h2>
        <?php 
        
        if (!isset($_GET['status']) || empty($_GET['status']) || $_GET['status'] !== 'success' 
            || !isset($_GET['token']) || !isset($_SESSION['token']) || empty($_GET['token'])
            || $_GET['token'] !== $_SESSION['token']):  
        ?>
        <p class="lead">
           Oops!, Already Installed ... 
        </p>
        
         <script>
         function leave() {
             window.location = "../admin/login.php";
         }
         
         setTimeout("leave()", 5000);
         </script>

        <?php 
         else:
        ?>
        <p class="lead">
        Installation is complete. Your blog is ready for population.
            Please 
        <a href="<?= $protocol."://".$server_host.dirname(dirname($_SERVER['PHP_SELF']))."/admin/login.php"; ?>">log in</a>
        </p>
        <?php
        endif;
        ?>
      </div>
     
 <div class="row"></div>

<?php

if (isset($_SESSION['token'])) purge_installation();
install_footer($installation_path, $protocol, $server_host);
