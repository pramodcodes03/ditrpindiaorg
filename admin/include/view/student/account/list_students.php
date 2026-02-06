 <!-- Content Wrapper. Contains page content -->

 <div class="content-wrapper">

 	<!-- Content Header (Page header) -->

 	<section class="content-header">

 		<h1>

 			List Students

 			<small>All Students</small>

 		</h1>

 		<ol class="breadcrumb">

 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

 			<li><a <a href="#"> Students</a></li>

 			<li class="active"> List Students</li>

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

 						<a href="page.php?page=add-student" class="btn btn-sm btn-primary pull-left"><i class="fa fa-plus"></i> Add Student</a>

 					</div>

 					<!-- /.box-header -->

 					<div class="box-body">

 						<table class="table table-bordered table-hover data-tbl">

 							<thead>

 								<tr>

 									<th>S/N</th>

 									<th>Photo</th>

 									<th>Name</th>

 									<th>Student ID</th>

 									<th>Email</th>

 									<th>Mobile</th>

 									<th>Admission Date</th>

 									<th>Status</th>

 									<th>Action</th>

 								</tr>

 							</thead>

 							<tbody>

 								<?php

									$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

									$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

									if ($user_role == 5) {

										$institute_id = $db->get_parent_id($user_role, $user_id);

										$staff_id = $user_id;
									} else {

										$institute_id = $user_id;

										$staff_id = 0;
									}

									include_once('include/classes/student.class.php');

									$student = new student();

									$res = $student->list_student('', $institute_id, '');

									if ($res != '') {

										$srno = 1;

										while ($data = $res->fetch_assoc()) {

											$STUDENT_ID 		= $data['STUDENT_ID'];

											$INSTITUTE_ID 		= $data['INSTITUTE_ID'];

											$STUDENT_CODE 		= $data['STUDENT_CODE'];

											$STUDENT_FULLNAME 	= $data['STUDENT_FULLNAME'];

											$STUDENT_MOBILE		= $data['STUDENT_MOBILE'];

											$STUDENT_EMAIL		= $data['STUDENT_EMAIL'];

											$ACCOUNT_REGISTERED_DATE		= $data['ACCOUNT_REGISTERED_DATE'];

											$STUD_PHOTO		= $data['STUD_PHOTO'];

											$ACTIVE 			= $data['ACTIVE'];



											if ($ACTIVE == 1)

												$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentStatus(' . $STUDENT_ID . ',0)"><i class="fa fa-check"></i></a>';

											elseif ($ACTIVE == 0)

												$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentStatus(' . $STUDENT_ID . ',1)"><i class="fa fa-times"></i></a>';





											$PHOTO = '../uploads/default_user.png';

											if ($STUD_PHOTO != '')

												$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUD_PHOTO;

											$editLink = "<a href='page.php?page=update-student&id=$STUDENT_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>

					<a href='javascript:void(0)' onclick='deleteStudent($STUDENT_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>

					

					<a href='javascript:void(0)' class='btn btn-link send-email-inst' title='Send Email' data-toggle='modal' data-target='.bs-example-modal-md' data-email='$STUDENT_EMAIL' data-id='$STUDENT_ID' data-name='$STUDENT_FULLNAME'><i class=' fa fa-envelope'></i></a>

					

					<a href='page.php?page=list-student-payments&student_id=$STUDENT_ID' class='btn btn-xs btn-link' title='Payment Info'><i class=' fa fa-rupee'></i></a>

					";

											// get student courses details

											$courseInfo = "<a href='javascript:void(0)' class='btn btn-link show-stud-course-info' title='View Course Details' data-toggle='modal' data-target='.show-stud-course-details'  data-id='$STUDENT_ID' data-name='$STUDENT_FULLNAME' data-email='$STUDENT_EMAIL'><i class='fa  fa-info'></i></a>

					

					<a href='page.php?page=list-student-courses&id=$STUDENT_ID' class='btn btn-xs btn-link' title='View Course Info'><i class=' fa fa-eye'></i></a>

					

					

					";



											echo " <tr id='row-$STUDENT_ID'>

							<td>$srno</td>

							<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>

							<td>$STUDENT_FULLNAME</td>

							<td>$STUDENT_CODE</td>

							<td>$STUDENT_EMAIL</td>

							<td>$STUDENT_MOBILE</td>

							<td>$ACCOUNT_REGISTERED_DATE</td>

							<td id='status-$STUDENT_ID'>$ACTIVE</td>

							<td>$editLink</td>								

                           </tr>

						  

						   ";



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