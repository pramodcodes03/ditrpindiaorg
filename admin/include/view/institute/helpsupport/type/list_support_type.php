 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
     <h1>
       List Support Type
       <small>All Support Type</small>
     </h1>
     <ol class="breadcrumb">
       <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
       <li><a <a href="#"> Help Support</a></li>
       <li class="active"> List Support Type</li>
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
             <?php if ($db->permission('add_course')) { ?>
               <a href="page.php?page=add-support-type" class="btn btn-sm btn-primary pull-left"><i class="fa fa-plus"></i> Add Support Type</a>
             <?php } ?>
           </div>
           <!-- /.box-header -->
           <div class="box-body">
             <div class="table-responsive">
               <table class="table table-bordered table-hover data-tbl">
                 <thead>
                   <tr>
                     <th>Sr.</th>
                     <th>Support Name</th>
                     <th>Status</th>
                     <th>Action</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php
                    include_once('include/classes/helpsupport.class.php');
                    $helpsupport = new helpsupport();
                    $res = $helpsupport->list_support_type('', '');
                    if ($res != '') {
                      $srno = 1;
                      while ($data = $res->fetch_assoc()) {
                        $SUPPORT_TYPE_ID     = $data['SUPPORT_TYPE_ID'];
                        $SUPPORT_NAME       = $data['SUPPORT_NAME'];
                        $ACTIVE          = $data['ACTIVE'];
                        $CREATED_BY   = $data['CREATED_BY'];
                        $CREATED_ON   = $data['CREATED_ON'];

                        if ($db->permission('update_course')) {
                          if ($ACTIVE == 1)
                            $ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i>Active</span>';
                          elseif ($ACTIVE == 0)
                            $ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i>In-Active</span> ';
                        }


                        $action = '';
                        if ($db->permission('update_course'))
                          $action = "<a href='page.php?page=update-support-type&id=$SUPPORT_TYPE_ID' class='btn btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";
                        if ($db->permission('delete_exam'))
                          $action .= "<a href='javascript:void(0)' onclick='deleteSupportType($SUPPORT_TYPE_ID)' class='btn btn-xs btn-link' title='Delete'><i class=' fa fa-trash'></i></a>
            ";

                        echo "<tr id='supporttypeId-" . $SUPPORT_TYPE_ID . "'>							
      							<td>$srno</td>	
      							<td>$SUPPORT_NAME</td>							
      							<td id='status-$SUPPORT_TYPE_ID'>$ACTIVE</td>
      							<td>$action</td>
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