<?php if (!defined('SCRIPTLOG')) exit(); ?>
 <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=(isset($pageTitle)) ? $pageTitle : ""; ?>
        <small><a href="index.php?load=posts&action=newPost&postId=0"
					class="btn btn-primary"> <i
					class="fa fa-plus-circle"></i> Add New
				</a></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?load=dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="index.php?load=posts">All Posts</a></li>
        <li class="active">Data Posts</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
         <div class="col-xs-12">
         <?php 
         if (isset($errors)) :
         ?>
         <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
           <?php 
              foreach ($errors as $e) :
                echo $e;
              endforeach;
           ?>
          </div>
         <?php 
         endif;
         ?>
         
         <?php 
         if (isset($status)) :
         ?>
         <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
           <?php 
              foreach ($status as $s) :
                echo $s;
              endforeach;
           ?>
          </div>
         <?php 
         endif;
         ?>
         
            <div class="box box-primary">
               <div class="box-header with-border">
                <h3 class="box-title">
              <?=(isset($postsTotal)) ? $postsTotal : 0; ?> 
               Post<?=($postsTotal != 1) ? 's' : ''; ?>
               in Total  
              </h3>
               </div>
              <!-- /.box-header -->
              
              <div class="box-body">
               <table id="scriptlog-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Title</th>
                  <th>Author</th>
                  <th>Date</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                 <?php 
                   if (is_array($posts)) : 
                   $no = 0;
                   foreach ($posts as $p => $post) :
                   $no++;
                  ?>
              
                    <tr>
                       <td><?= $no; ?></td>
                       <td><?= htmlspecialchars($post['post_title']); ?></td>
                       <td><?= htmlspecialchars($post['author']); ?></td>
                       <td><?= htmlspecialchars(make_date($post['post_date'])); ?></td>
                      
                       <td>
                       <a href="index.php?load=posts&action=editPost&postId=<?= htmlspecialchars((int)$post['ID']);?>" class="btn btn-warning">
                       <i class="fa fa-pencil fa-fw"></i> Edit</a>
                       </td>
                       <td>
                       <a href="javascript:deletePost('<?= abs((int)$post['ID']); ?>', '<?= $post['post_title']; ?>')" class="btn btn-danger">
                       <i class="fa fa-trash-o fa-fw"></i> Delete</a>
                       </td>
                    
                    </tr>
                
                <?php 
                endforeach;
                endif; 
                ?>
                
                </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                  <th>Title</th>
                  <th>Author</th>
                  <th>Date</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
                </tfoot>
              </table>
              </div>
                  <!-- /.box-body -->
            </div>
               <!-- /.box -->
         </div>
            <!-- /.col-xs-12 -->
      </div>
           <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<script type="text/javascript">
  function deletePost(id, title)
  {
	  if (confirm("Are you sure want to delete Post '" + title + "'"))
	  {
	  	window.location.href = 'index.php?load=posts&action=deletePost&postId=' + id;
	  }
  }
</script>