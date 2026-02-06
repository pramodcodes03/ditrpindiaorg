 <!-- Content Wrapper. Contains page content -->

 <div class="content-wrapper">

 	<!-- Content Header (Page header) -->

 	<section class="content-header">

 		<h1>

 			List Staff Members

 			<small>Staff Members</small>

 		</h1>

 		<ol class="breadcrumb">

 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

 			<li><a href="#"> Staff</a></li>

 			<li class="active"> List Staff Members</li>

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

 						<a href="page.php?page=add-staff" class="btn btn-sm btn-primary pull-left"><i class="fa fa-plus"></i> Add Staff Member</a>

 					</div>

 					<!-- /.box-header -->

 					<div class="box-body">

 						<table class="table table-bordered table-hover data-tbl">

 							<thead>

 								<tr>

 									<th>S/N</th>

 									<th>Photo</th>

 									<th>Name</th>

 									<th>Email/Username</th>

 									<th>Mobile</th>

 									<th>Status</th>

 									<th>Action</th>

 								</tr>

 							</thead>

 							<tbody>

 								<?php

									include_once('include/classes/institute.class.php');

									$institute = new institute();

									$res = $institute->list_institute_staff('', $_SESSION['user_id']);

									if ($res != '') {

										$srno = 1;

										while ($data = $res->fetch_assoc()) {

											$STAFF_ID 		= $data['STAFF_ID'];

											$INSTITUTE_ID 	= $data['INSTITUTE_ID'];

											$STAFF_FULLNAME = $data['STAFF_FULLNAME'];

											$STAFF_EMAIL 	= $data['STAFF_EMAIL'];


											$STAFF_MOBILE 	= $data['STAFF_MOBILE'];

											$STAFF_PHOTO 	= $data['STAFF_PHOTO'];

											$ACTIVE 		= $data['ACTIVE'];

											/*	if($ACTIVE==1) $ACTIVE= 'Active';

					elseif($ACTIVE==0) $ACTIVE= 'In-Active';*/



											if ($ACTIVE == 1)

												$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstStaffStatus(' . $STAFF_ID . ',0)"><i class="fa fa-check"></i></a>';

											elseif ($ACTIVE == 0)

												$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstStaffStatus(' . $STAFF_ID . ',1)"><i class="fa fa-times"></i></a>';





											$PHOTO = SHOW_IMG_AWS . '/default_user.png';

											if ($STAFF_PHOTO != '')

												$PHOTO = SHOW_IMG_AWS . INSTITUTE_STAFF_PHOTO_PATH . '/' . $STAFF_ID . '/thumb/' . $STAFF_PHOTO;

											$editLink = "<a href='page.php?page=update-staff&id=$STAFF_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>

					<a href='javascript:void(0)' onclick='deleteInstStaff($STAFF_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>

					

					<a href='page.php?page=list-incentives&id=$STAFF_ID' class='btn btn-xs btn-link' title='Incentives'><i class=' fa fa-inr'></i></a>

					

					<a href='javascript:void(0)' class='btn btn-link send-email-inst' title='Send Email' data-toggle='modal' data-target='.bs-example-modal-md' data-email='$STAFF_EMAIL' data-id='$STAFF_ID' data-name='$STAFF_FULLNAME'><i class=' fa fa-envelope'></i></a>

					";







											echo " <tr id='row-$STAFF_ID'>

							<td>$srno</td>

							<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>

							<td>$STAFF_FULLNAME</td>

							<td>$STAFF_EMAIL</td>							


							<td>$STAFF_MOBILE</td>

							<td id='status-$STAFF_ID'>$ACTIVE</td>

							<td>$editLink</td>

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





 <!-- modal to send email -->

 <div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">



 	<img src="resources/dist/img/loader.gif" class="loader-mg-modal" />

 	<div class="modal-dialog modal-md" role="document">

 		<div class="modal-content">



 			<div class="box box-primary modal-body">

 				<div class="">

 					<div class="box-header with-border">

 						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

 						<h3 class="box-title">Compose New Message</h3>

 					</div>

 					<!-- /.box-header -->

 					<form id="send_email_form" method="post">



 						<input type="hidden" name="inst_id" id="inst_id" value="" />

 						<input type="hidden" name="action" id="action" value="send_email" />

 						<div class="box-body">

 							<div class="form-group" id="email-error">

 								<input class="form-control" placeholder="To:" id="inst_email" name="inst_email">

 								<p class="help-block"></p>

 							</div>

 							<div class="form-group">

 								<input class="form-control" placeholder="Subject:" id="subject" name="subject">

 							</div>

 							<div class="form-group" id="msg-error">

 								<textarea id="compose-textarea" class="form-control" name="message" id="message" style="height: 150px">



								</textarea>

 								<p class="help-block"></p>

 							</div>

 							<div class="form-group msg">

 								<p class="help-block"></p>

 							</div>

 						</div>



 						<!-- /.box-body -->

 						<div class="box-footer">

 							<div class="pull-right">

 								<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>

 								<button type="submit" name="send" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>

 							</div>

 						</div>

 					</form>

 					<!-- /.box-footer -->

 				</div>

 			</div>

 		</div>

 	</div>

 </div>