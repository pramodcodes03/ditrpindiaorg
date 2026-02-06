 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
     <h1>
       List Slider
       <small>All Slider</small>
     </h1>
     <ol class="breadcrumb">
       <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
       <li><a <a href="#"> Slider</a></li>
       <li class="active"> List Slider</li>
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
             <h3 class="box-title">List Slider Details</h3>
             <?php if ($db->permission('add_exam')) { ?>
               <a href="add-slider" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add Slider Details</a>
             <?php } ?>
           </div>
           <!-- /.box-header -->
           <div class="box-body">
             <div class="table-responsive">
               <table class="table table-bordered table-hover list-exams data-tbl">
                 <thead>
                   <tr>
                     <th>Sr.</th>

                     <th>Slider Image</th>

                     <th>Action</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php
                    include_once('include/classes/tools.class.php');
                    $exam = new tools();
                    $res = $exam->list_slider('', '');
                    if ($res != '') {
                      $srno = 1;
                      while ($data = $res->fetch_assoc()) {
                        $CONTEST_ID   = $data['SLIDER_ID'];
                        $CONTEST_IMG   = $data['SLIDER_IMG'];
                        $CREATED_BY   = $data['CREATED_BY'];
                        $CREATED_ON   = $data['CREATED_ON'];

                        $PHOTO = '../upload/default/default.png';
                        if ($CONTEST_IMG != '')
                          $PHOTO = SLIDERNEW_MATERIAL_PATH . '/' . $CONTEST_ID . '/thumb/' . $CONTEST_IMG;
                        $PHOTOLINK = SLIDERNEW_MATERIAL_PATH . '/' . $CONTEST_ID . '/' . $CONTEST_IMG;




                        $action = "";


                        if ($db->permission('delete_exam'))
                          $action .= "<a href='javascript:void(0)' onclick='deleteSlidernew($CONTEST_ID)' class='btn btn-xs btn-link' title='Delete'><i class=' fa fa-trash'></i></a>
					";

                        echo " <tr id='exam-id" . $CONTEST_ID . "'>
							<td>$srno</td>						
												
							<td><a href='$PHOTOLINK' target='_blank'> <img src='$PHOTO' class='img img-responsive' style='width:150px; height:100px'></a></td>	
					
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


 <!-- modal to send email -->