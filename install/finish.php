<?php  
$time_start = microtime(true);
require('include/settings.php');
require('include/setup.php');

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Scriptlog Installation">
    <link rel="icon" href="../favicon.ico">

    <title>Scriptlog Installed</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/form-validation.css" rel="stylesheet">
  </head>
<body class="bg-light">

<div class="container">
     <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="assets/img/icon612x612.png" alt="Scriptlog Installation Completed" width="72" height="72">
        <h2>Scriptlog</h2>
        <?php 
        if (!isset($_GET['status']) || empty($_GET['status']) || $_GET['status'] !== 'success' 
            || !isset($_GET['token']) || !isset($_SESSION['token']) || empty($_GET['token'])
            || $_GET['token'] !== $_SESSION['token']):  
        ?>
        <p class="lead">
           Oops!, Already Installed ... 
        </p>
        <script type="text/javascript">function leave() {  window.location = "<?php echo $protocol."://".$server_host.dirname(dirname($_SERVER['PHP_SELF']))."/cabin/login.php";; ?>";} setTimeout("leave()", 3640);</script>
        <?php 
         else:
        ?>
        <p class="lead">
        Installation is complete. Your blog is ready for population.
            Please <a href="<?= $protocol."://".$server_host.dirname(dirname($_SERVER['PHP_SELF']))."/cabin/login.php"; ?>">log in</a>
        </p>
        <?php
        endif;
        ?>
      </div>
     
 <div class="row"></div>
 <footer class="my-5 pt-5 text-muted text-center text-small">
    <p class="mb-1">&copy; 
       <?php 
          
     if (isset($_SESSION['token'])) purge_installation();
       
          $starYear = 2013;
          $thisYear = date ( "Y" );
          if ($starYear == $thisYear) {
             
              echo $starYear;
             
          } else {
              
              echo " {$starYear} &#8211; {$thisYear} ";
           }
                     
             echo "Scriptlog";
              
             $time_end = microtime(true);
             $time = $time_end - $time_start;
              
        ?>
         
        </p>
        
        <ul class="list-inline">
          <li class="list-inline-item"><a href="<?php echo $installURL . '../LICENSE'; ?>">License</a></li>
          <li class="list-inline-item"><a href="#"><?php echo 'Memory Consumption is '. round(memory_get_usage()/1048576,2).''.' MB'; ?></a></li>
          <li class="list-inline-item"><a href="#"><?php echo $time . ' seconds'; ?></a></li>
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
  
  </body>
</html>