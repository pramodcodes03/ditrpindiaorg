 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
     <h1>
       Institute Plans
       <small>Institute Plans</small>
     </h1>
     <ol class="breadcrumb">
       <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
       <li> Institute Plans</li>
     </ol>
   </section>

   <!-- Main content -->
   <section class="content">
     <?php
      if (isset($_SESSION['msg'])) {
        $message = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
        $msg_flag = $_SESSION['msg_flag'];
      ?>
       <div class="row">
         <div class="col-sm-12">
           <div class="alert alert-<?= ($msg_flag == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
             <h4><i class="icon fa fa-check"></i> <?= ($msg_flag == true) ? 'Success' : 'Error' ?>:</h4>
             <?= ($message != '') ? $message : 'Sorry! Something went wrong!'; ?>
           </div>
         </div>
       </div>
     <?php
        unset($_SESSION['msg']);
        unset($_SESSION['msg_flag']);
      }
      ?>
     <div class="row">


       <div class="col-xs-12">
         <div class="box">
           <div class="box-header">
             <h3 class="box-title">List Institute Plans</h3>
             <?php if ($db->permission('add_institute')) { ?>
               <a href="page.php?page=add-institute-plans" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add Institute Plans</a>
             <?php } ?>
           </div>
           <!-- /.box-header -->
           <div class="box-body">
             <div class="table-responsive">
               <table class="table table-bordered table-striped table-hover data-tbl">
                 <thead>
                   <tr>
                     <th>Sr.</th>
                     <th>Institute Plan Name</th>
                     <th>Status</th>
                     <th>Action</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php

                    include_once('include/classes/instituteplans.class.php');
                    $instituteplans = new instituteplans();
                    $res = $instituteplans->list_institue_plan('', '');
                    if ($res != '') {
                      $srno = 1;
                      while ($data = $res->fetch_assoc()) {
                        $PLAN_ID     = $data['PLAN_ID'];
                        $PLAN_NAME     = $data['PLAN_NAME'];

                        $ACTIVE     = ($data['ACTIVE'] == 1) ? 'ACTIVE' : 'IN-ACTIVE';

                        $editLink = "";
                        $editLink .= "<a href='page.php?page=update-institute-plans&id=$PLAN_ID' class='btn btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";

                        $editLink .= "<a href='javascript:void(0)' class='btn btn-link' title='Delete' onclick='deleteInstitutePlan($PLAN_ID)'><i class=' fa fa-trash'></i></a>";

                        echo " <tr id='row-$PLAN_ID'>
								<td>$srno</td>							
								<td>$PLAN_NAME</td>							
								<td id='status-$PLAN_ID'>$ACTIVE</td>
								<td>$editLink</td>
	                           </tr>";
                        $srno++;
                      }
                    }

                    ?>
                 </tbody>
               </table>
             </div>
           </div>
           <!-- /.box-body -->
         </div>
         <!-- /.box -->
         <!-- /.box -->
       </div>
       <!-- /.col -->
     </div>
     <!-- /.row -->
   </section>
   <!-- /.content -->
 </div>