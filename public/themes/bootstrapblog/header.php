<?php 
require dirname(__FILE__) . '/functions.php';
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= grab_title(); ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <base href="<?= grab_site_url(); ?>">
    
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?= load_template(); ?>vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="<?= load_template(); ?>vendor/font-awesome/css/font-awesome.min.css">
    <!-- Custom icon font-->
    <link rel="stylesheet" href="<?= load_template(); ?>css/fontastic.css">
    <!-- Google fonts - Open Sans-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
    <!-- Fancybox-->
    <link rel="stylesheet" href="<?= load_template(); ?>vendor/@fancyapps/fancybox/jquery.fancybox.min.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="<?= load_template(); ?>css/style.sea.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="<?= load_template(); ?>css/custom.css">
    <!-- Multi Dropdown Menu -->
    <link rel="stylesheet" href="<?= load_template(); ?>css/dropdown.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="favicon.png">
    
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
    <header class="header">
      <!-- Main Navbar-->
      <nav class="navbar navbar-expand-md navbar-light bg-light btco-hover-menu">
        <div class="search-area">
          <div class="search-area-inner d-flex align-items-center justify-content-center">
            <div class="close-btn"><i class="icon-close"></i></div>
            <div class="row d-flex justify-content-center">
              <div class="col-md-8">
                <form action="#">
                  <div class="form-group">
                    <input type="search" name="search" id="search" placeholder="What are you looking for?">
                    <button type="submit" class="submit"><i class="icon-search-1"></i></button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <!-- Navbar Brand -->
          <div class="navbar-header d-flex align-items-center justify-content-between">
            <!-- Navbar Brand --><a href="index.html" class="navbar-brand">Bootstrap Blog</a>
            <!-- Toggle Button-->
            <button type="button" data-toggle="collapse" data-target="#navbarcollapse" aria-controls="navbarcollapse" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span></span><span></span><span></span></button>
          </div>
          <!-- Navbar Menu -->
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
              <li class="nav-item"><a href="index.html" class="nav-link active ">Home</a>
              </li>
              <li class="nav-item"><a href="blog.html" class="nav-link ">Blog</a>
              </li>
              <li class="nav-item"><a href="post.html" class="nav-link ">Post</a>
              </li>

              <li class="nav-item dropdown">
                 <a class="nav-link dropdown-toggle" href="https://bootstrapthemes.co" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Dropdown link
                 </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><a class="dropdown-item dropdown-toggle" href="#">Submenu</a>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Submenu action</a></li>
                        <li><a class="dropdown-item" href="#">Another submenu action</a></li>

                        <li><a class="dropdown-item dropdown-toggle" href="#">Subsubmenu</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Subsubmenu action aa</a></li>
                                <li><a class="dropdown-item" href="#">Another subsubmenu action</a></li>
                            </ul>
                        </li> <!-- Subsubmenu-->
                        <li><a class="dropdown-item dropdown-toggle" href="#">Second subsubmenu</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Subsubmenu action bb</a></li>
                                <li><a class="dropdown-item" href="#">Another subsubmenu action</a></li>
                            </ul>
                        </li> <!-- Second subsubmenu -->
                      </ul><!-- ./dropdown-menu -->                      
                  </li> <!-- #Submenu -->
                                
                  <li><a class="dropdown-item dropdown-toggle" href="#">Submenu 2</a>
                        <ul class="dropdown-menu">
                           <li><a class="dropdown-item" href="#">Submenu action 2</a></li>
                           <li><a class="dropdown-item" href="#">Another submenu action 2</a></li>

                        <li><a class="dropdown-item dropdown-toggle" href="#">Subsubmenu</a>
                            <ul class="dropdown-menu">
                               <li><a class="dropdown-item" href="#">Subsubmenu action 1 3</a></li>
                               <li><a class="dropdown-item" href="#">Another subsubmenu action 2 3</a></li>
                            </ul>
                        </li> <!-- Subsubmenu -->
                        <li><a class="dropdown-item dropdown-toggle" href="#">Second subsubmenu 3</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Subsubmenu action 3 </a></li>
                                <li><a class="dropdown-item" href="#">Another subsubmenu action 3</a></li>
                            </ul>
                        </li> <!-- Second subsubmenu 3 -->
                        </ul> <!-- ./dropdown-menu -->
                    </li><!-- #Submenu 2 -->

                    </ul><!-- ./dropdown-menu aria-labelledby="navbarDropdownMenuLink" -->
                </li><!-- ./nav-item dropdown -->

              <li class="nav-item"><a href="#" class="nav-link ">Contact</a>
              </li>
            </ul>
            <div class="navbar-text"><a href="#" class="search-btn"><i class="icon-search-1"></i></a></div>
            <ul class="langs navbar-text"><a href="#" class="active">EN</a><span> </span><a href="#">ES</a></ul>
          </div>
        </div>
      </nav>
    </header>