 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
     <h1>
       Typing Activation Plans
       <small>Activation Plans</small>
     </h1>
     <ol class="breadcrumb">
       <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
       <li> Activation Plans</li>
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
             <h3 class="box-title">List Activation Plans</h3>
             <?php if ($db->permission('add_institute')) { ?>
               <a href="page.php?page=add-activation-plans" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add Plans</a>
             <?php } ?>
           </div>
           <!-- /.box-header -->
           <div class="box-body">
             <div class="table-responsive">
               <table class="table table-bordered table-striped table-hover data-tbl">
                 <thead>
                   <tr>
                     <th>Sr.</th>
                     <th>Plan Name</th>
                     <th>Validity (DAYS)</th>
                     <th>Amount (RS.)</th>
                     <th>Status</th>
                     <th>Action</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php

                    include_once('include/classes/typing.class.php');
                    $typing = new typing();
                    $res = $typing->list_typing_activation_plan('', '');
                    if ($res != '') {
                      $srno = 1;
                      while ($data = $res->fetch_assoc()) {
                        $PLAN_ID     = $data['PLAN_ID'];
                        $PLAN_NAME     = $data['PLAN_NAME'];
                        $VALIDITY     = $data['VALIDITY'];
                        $AMOUNT     = $data['AMOUNT'];

                        $ACTIVE     = $data['ACTIVE'];

                        $editLink = "";
                        $editLink .= "<a href='page.php?page=update-activation-plans&id=$PLAN_ID' class='btn btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";

                        $editLink .= "<a href='javascript:void(0)' class='btn btn-link' title='Delete' onclick='deletePlan($PLAN_ID)'><i class=' fa fa-trash'></i></a>";

                        echo " <tr id='row-$PLAN_ID'>
							<td>$srno</td>							
							<td>$PLAN_NAME</td>
							<td>$VALIDITY</td>							
							<td>$AMOUNT</td>
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