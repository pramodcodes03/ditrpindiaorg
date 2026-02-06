 <!-- Content Wrapper. Contains page content -->

 <?php
  $active = isset($_POST['active']) ? $_POST['active'] : '';
  $cond = '';

  if ($active != '') $cond .= " AND A.ACTIVE ='$active'";
  $cond .= ' ORDER BY ACTIVATION_DATE DESC';
  ?>
 <div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
     <h1>
       List Of Institutes For Typing Software Registration
       <small>All Institutes</small>
     </h1>
     <ol class="breadcrumb">
       <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
       <li class="active">Typing Software</li>
     </ol>
   </section>
   <form action="" method="post">
     <div class="form-group col-sm-6 <?= (isset($errors['designation'])) ? 'has-error' : '' ?>" style=" margin-top: 50px;">
       <label for="designation" class="col-sm-3 control-label">Active/In-Active</label>
       <div class="col-sm-6">
         <select class="form-control" name="active" id="active">
           <option value="1">Active</option>
           <option value="0">In-Active</option>
         </select>
         <span class="help-block"><?= (isset($errors['designation'])) ? $errors['designation'] : '' ?></span>
       </div>
       <button type="submit" class="btn btn-lg btn-primary waves-effect" value="Filter" name="search">Filter</button>
     </div>
   </form>
   <!-- Main content -->
   <section class="content">
     <div class="row">
       <div class="col-xs-12">
         <div class="box">
           <!-- /.box-header -->
           <div class="box-body">
             <div class="table-responsive">
               <table class="table table-bordered table-striped table-hover data-tbl">
                 <thead>
                   <tr>
                     <th>Sr.</th>
                     <th>Action</th>
                     <th>SMS/EMAIL KEY</th>
                     <th>Institute Code</th>
                     <th>Institute Name</th>
                     <th>Owner Name</th>
                     <th>Email</th>
                     <th>Mobile</th>
                     <th>Activation Date</th>
                     <th>Expiry Date</th>
                     <th>Approved Status</th>
                     <th>Software Plan</th>

                   </tr>
                 </thead>
                 <tbody>
                   <?php
                    include_once('include/classes/typing.class.php');
                    $typing = new typing();
                    $res = $typing->list_typing_institute('', $cond);
                    if ($res != '') {
                      $srno = 1;
                      while ($data = $res->fetch_assoc()) {
                        $INSTITUTE_ID     = $data['INSTITUTE_ID'];
                        $INSTITUTE_CODE   = $data['INSTITUTE_CODE'];
                        $INSTITUTE_NAME   = $data['INSTITUTE_NAME'];
                        $OWNER_NAME     = $data['OWNER_NAME'];
                        $EMAIL         = $data['EMAIL'];
                        $MOBILE       = $data['MOBILE'];
                        $ADDRESS       = $data['ADDRESS'];
                        $PINCODE       = $data['PINCODE'];
                        $USERNAME       = $data['USERNAME'];
                        $PASSWORD       = $data['PASSWORD'];
                        $PLAN_NAME       = $data['PLAN_NAME'];
                        $VALIDITY      = $data['VALIDITY'];

                        $validityday = +$VALIDITY . ' days';
                        $PLAN_ID       = $data['PLAN_ID'];
                        $ACTIVATION_KEY   = $data['ACTIVATION_KEY'];

                        $ACTIVATION_DATE   = $data['ACTIVATION_DATE'];
                        $activaiondate = date('d-M-Y', strtotime($ACTIVATION_DATE));

                        $expirydate = strtotime($validityday, strtotime($activaiondate));
                        $expirydate = date('d-M-Y', $expirydate);

                        $ACTIVE       = ($data['ACTIVE'] == 1) ? 'ACTIVE' : 'IN-ACTIVE';
                        $ACTIVE_CLASS     = ($data['ACTIVE'] == 1) ? 'success' : 'danger';

                        $editLink = "";
                        $editLink .= "<a href='page.php?page=update-typing-institute&id=$INSTITUTE_ID' class='btn btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";

                        $editLink .= "<a href='javascript:void(0)' class='btn btn-link' title='Delete' onclick='deleteTypingInstitute($INSTITUTE_ID)'><i class=' fa fa-trash'></i></a>";

                        $SendLink = "";
                        $SendLink .= "<a href='javascript:void(0)' class='btn btn-link' title='EMAIL' onclick='sendActivactionkeyEMAIL($INSTITUTE_ID,7)'><i class=' fa fa-envelope'></i></a>";

                        $SendLink .= "<a href='javascript:void(0)' class='btn btn-link' title='SMS' onclick='sendActivactionkeySMS($INSTITUTE_ID,7)'><i class='fa fa-mobile' style='font-size:22px;'></i></a>";

                        echo " <tr id='row-$INSTITUTE_ID'>
										<td>$srno</td>	
										<td>$editLink</td>
                                        <td>$SendLink</td>
										<td>$INSTITUTE_CODE</td>
										<td>$INSTITUTE_NAME</td>							
										<td>$OWNER_NAME</td>
										<td>$EMAIL</td>
										<td>$MOBILE</td>							
										<td>$activaiondate</td>
										<td>$expirydate</td>										
										<td id='status-$INSTITUTE_ID' class='text-$ACTIVE_CLASS'>$ACTIVE</td>
										<td>$PLAN_NAME</td>
									
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
       </div>
       <!-- /.col -->
     </div>
     <!-- /.row -->
   </section>
   <!-- /.content -->
 </div>