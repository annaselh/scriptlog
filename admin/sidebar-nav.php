<?php 
function sidebarNavigation($module, $url, $level = null)
{
?>
 <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel"></div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <!-- Optionally, you can add icons to the links -->
        <li <?php if ($module == 'dashboard') echo 'class="active"'; ?>>
          <a href="<?= $url.'index.php?load=dashboard'; ?>"><i class="fa fa-dashboard"></i> 
          <span>Dashboard</span></a>
          </li>
        
        <li <?php if ($module == 'posts' || $module == 'topics') echo 'class="treeview active"'; ?>>
          <a href="<?= $url.'index.php?load=posts'; ?>"><i class="fa fa-thumb-tack"></i> 
          <span>Posts</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= $url.'index.php?load=posts'; ?>">All Posts</a></li>
            <li><a href="<?= $url.'index.php?load=posts&action=newPost&postId=0'; ?>">Add New</a></li>
            <li><a href="<?= $url.'index.php?load=topics'; ?>">Topics</a></li>
          </ul>
        </li>
        
         <li <?php if ($module == 'comments') echo 'class="active"'; ?>>
        <a href="<?= $url.'index.php?load=comments'; ?>"><i class="fa fa-comments"></i> 
        <span>Comments</span></a>
        </li>
        
         <li <?php if ($module == 'pages') echo 'class="treeview active"'; ?>>
          <a href="<?= $url.'index.php?load=pages'; ?>"><i class="fa fa-clone"></i> 
          <span>Pages</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= $url.'index.php?load=pages'; ?>">All Pages</a></li>
            <li><a href="<?= $url.'index.php?load=pages&action=newPage&pageId=0'; ?>">Add New</a></li>
          </ul>
        </li>
        
       <li <?php if ($module == 'users') echo 'class="treeview active"'; ?>>
          <a href="<?= $url.'index.php?load=users'; ?>"><i class="fa fa-user"></i> 
          <span>Users</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= $url.'index.php?load=users'; ?>">All Users</a></li>
            <li><a href="<?= $url.'index.php?load=users&action=newUser&userId=0'; ?>">Add New</a></li>
          </ul>
        </li>
        
        <li class="treeview">
          <a href="#"><i class="fa fa-paint-brush"></i> 
          <span>Appearance</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= $url.'index.php?load=templates'; ?>">Themes</a></li>
            <li><a href="<?= $url.'index.php?load=menu'; ?>">Menu</a></li>
          </ul>
        </li>
        
        <li <?php if ($module == 'plugins') echo 'class="treeview active"'; ?>>
          <a href="<?= $url.'index.php?load=plugins'; ?>"><i class="fa fa-plug"></i> 
          <span>Plugins</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= $url.'index.php?load=plugins'; ?>">Installed Plugins</a></li>
            <li><a href="<?= $url.'index.php?load=plugins&action=newPlugin&pluginId=0'; ?>">Add New</a></li>
          </ul>
        </li>

        <li <?php if ($module == 'settings') echo 'class="active"'; ?>>
        <a href="<?= $url.'index.php?load=settings'; ?>"><i class="fa fa-sliders"></i> 
        <span>Settings</span></a>
        </li>
        
        <li class="header">PLUGIN NAVIGATION</li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
<?php 
}
?>