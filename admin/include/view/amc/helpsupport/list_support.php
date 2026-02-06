 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
     <h1>
       List Support
       <small>All Support</small>
     </h1>
     <ol class="breadcrumb">
       <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
       <li><a href="#"> Help Support </a></li>
       <li class="active"> List Support</li>
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
               <a href="page.php?page=add-support" class="btn btn-sm btn-primary pull-left"><i class="fa fa-plus"></i> Add Help Support</a>
             <?php } ?>
           </div>
           <div class="box-body">

             <table class="table table-bordered table-hover data-tbl">
               <thead>
                 <tr>
                   <th>Sr.</th>
                   <th>Support Type Name</th>
                   <th>Support Category Name</th>
                   <th>Description</th>
                   <th>Author</th>
                   <th>Current Status</th>
                   <th>Status</th>
                   <th>Date</th>
                   <th>Action</th>
                 </tr>
               </thead>
               <tbody>
                 <?php
                  include_once('include/classes/helpsupport.class.php');
                  $helpsupport = new helpsupport();
                  $res = $helpsupport->list_support('', ' AND USER_ROLE=7 ');
                  if ($res != '') {
                    $srno = 1;
                    while ($data = $res->fetch_assoc()) {
                      $TICKET_ID         = $data['TICKET_ID'];
                      $INSTITUTE_ID     = $data['INSTITUTE_ID'];
                      $SUPPORT_TYPE_ID  = $data['SUPPORT_TYPE_ID'];
                      $SUPPORT_CAT_ID   = $data['SUPPORT_CAT_ID'];
                      $DESCRIPTION      = $data['DESCRIPTION'];
                      $AUTHOR_NAME      = $data['AUTHOR_NAME'];
                      $MOBILE           = $data['MOBILE'];
                      $ALT_MOBILE       = $data['ALT_MOBILE'];
                      $EMAIL            = $data['EMAIL'];
                      $ALT_EMAIL        = $data['ALT_EMAIL'];
                      $CURRENT_STATUS   = $data['CURRENT_STATUS'];

                      $ACTIVE        = $data['ACTIVE'];
                      $CREATED_BY   = $data['CREATED_BY'];
                      $CREATED_ON   = $data['CREATED_ON'];

                      if ($db->permission('update_course')) {
                        if ($ACTIVE == 1)
                          $ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i>Active</span>';
                        elseif ($ACTIVE == 0)
                          $ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i>In-Active</span> ';
                      }

                      if ($db->permission('update_course')) {
                        if ($CURRENT_STATUS == 1)
                          $CURRENT_STATUS = '<span style="color:#3c763d">WORK IN PROGRESS</span>';
                        elseif ($CURRENT_STATUS == 2)
                          $CURRENT_STATUS = '<span style="color:#f00">CLOSED</span> ';
                      }

                      $action = '';
                      $action = "<a href='page.php?page=reply-support&id=$TICKET_ID' class='btn btn-primary' title='View Help Support'><i class='fa fa-envelope'> View</i></a>";

                      $supporttype_name = $db->get_support_type_name($SUPPORT_TYPE_ID);
                      $supportcat_name = $db->get_support_cat_name($SUPPORT_CAT_ID);

                      echo "<tr id='supportId-" . $TICKET_ID . "'>							
                  							<td>$srno</td>
                                <td>$supporttype_name</td>	
                                <td>$supportcat_name</td>                      
                                <td>$DESCRIPTION</td>     
                                <td>$AUTHOR_NAME</td>  
                                <td>$CURRENT_STATUS</td>                                 		
                  							<td id='status-$TICKET_ID'>$ACTIVE</td>
                                <td>$CREATED_ON</td> 
                  							<td>$action</td>
                          </tr>";
                      $srno++;
                    }
                  }

                  ?>
               </tbody>

             </table>
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