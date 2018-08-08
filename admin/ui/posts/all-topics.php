<?php if (!defined('SCRIPTLOG')) exit(); ?>
 <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=(isset($pageTitle)) ? $pageTitle : ""; ?>
        <small><a href="index.php?load=topics&action=newTopic&topicId=0"
					class="btn btn-primary"> <i
					class="fa fa-plus-circle"></i> Add New
				</a></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php?load=dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="index.php?load=topics">All Topics</a></li>
        <li class="active">Data Topics</li>
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
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
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
                <h4><i class="icon fa fa-check"></i> Alert!</h4>
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
                   <?=(isset($topicsTotal)) ? $topicsTotal : 0; ?>
                   Topic<?=($topicsTotal != 1) ? 's' : ''; ?>
                   in Total  
                 </h3>
               </div>
              <!-- /.box-header -->
              
              <div class="box-body">
                  <table id="scriptlog-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Slug</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                    if(is_array($topics)) :
                      $no = 0;
                      foreach($topics as $topic) :
                        $no++;
                  ?>
                     <tr>
                      <td><?= $no; ?></td>
                      <td><?= htmlspecialchars($topic['topic_title']); ?></td>
                      <td><?= htmlspecialchars($topic['topic_slug']); ?></td>
                      <td><?= htmlspecialchars($topic['topic_status']); ?></td>

                      <td>
                       <a href="index.php?load=topics&action=editTopic&topicId=<?= htmlspecialchars((int)$topic['ID']);?>" class="btn btn-warning">
                       <i class="fa fa-pencil fa-fw"></i> Edit</a>
                       </td>
                       <td>
                       <a href="javascript:deleteTopic('<?= abs((int)$topic['ID']); ?>', '<?= $topic['topic_title']; ?>')" class="btn btn-danger">
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
                  <th>Name</th>
                  <th>Slug</th>
                  <th>Status</th>
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
  function deleteTopic(id, title)
  {
	  if (confirm("Are you sure want to delete Topic '" + title + "'"))
	  {
	  	window.location.href = 'index.php?load=topics&action=deleteTopic&topicId=' + id;
	  }
  }
</script>