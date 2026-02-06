 <?php



	$student_id 		= isset($_REQUEST['student_id']) ? $_REQUEST['student_id'] : '';

	$institute_id = $_SESSION['user_id'];

	$payment_id = $db->test(isset($_GET['payment_id']) ? $_GET['payment_id'] : '');

	$condition = '';

	$search = isset($_POST['search']) ? $_POST['search'] : '';

	if ($search != '') {

		$datefrom 	= isset($_POST['datefrom']) ? $_POST['datefrom'] : '';

		$dateto 	= isset($_POST['dateto']) ? $_POST['dateto'] : '';

		$page 		= isset($_POST['page']) ? $_POST['page'] : '';



		if ($datefrom != '' && $dateto != '') {

			$datefrom 	 = date('Y-m-d H:i:s', strtotime($datefrom));

			$dateto	 = date('Y-m-d H:i:s', strtotime($dateto));

			$condition .= " AND A.FEES_PAID_DATE BETWEEN '$datefrom' AND '$dateto'";
		}
	}

	$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

	$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

	if ($user_role == 5) {

		$institute_id = $db->get_parent_id($user_role, $user_id);

		$staff_id = $user_id;
	} else {

		$institute_id = $user_id;

		$staff_id = 0;
	}

	?>

 <!-- Content Wrapper. Contains page content -->

 <div class="content-wrapper">

 	<!-- Content Header (Page header) -->

 	<section class="content-header">

 		<h1>

 			List Students Payments

 			<small>All Student Payments</small>

 		</h1>

 		<ol class="breadcrumb">

 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

 			<li>Student</li>

 			<li class="active">List Student Payments</li>

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

 				<div class="box box-primary">

 					<!-- /.box-header -->

 					<div class="box-header">



 						<h3 class="box-title">Search By Filters</h3>

 					</div>

 					<div class="box-body">

 						<form action="" method="post" onsubmit="pageLoaderOverlay('show')">

 							<input type="hidden" name="page" value="list-student-payments" />

 							<div class="form-group col-sm-2">

 								<label>Date From</la

 										<input class="form-control pull-right" name="datefrom" value="<?= isset($_REQUEST['datefrom']) ? $_REQUEST['datefrom'] : '' ?>" id="datefrom" type="text">

 							</div>

 							<div class="form-group col-sm-2">

 								<label>Date To</label>

 								<input class="form-control pull-right" name="dateto" value="<?= isset($_REQUEST['dateto']) ? $_REQUEST['dateto'] : '' ?>" id="dateto" type="text">

 							</div>

 							<div class="form-group col-sm-2">

 								<label>Student</label>

 								<?php $student_id = isset($_REQUEST['student_id']) ? $_REQUEST['student_id'] : ''; ?>

 								<select class="form-control select2" name="student_id" id="student_id">

 									<?php echo $db->MenuItemsDropdown('student_payments', "STUDENT_ID", "STUDENT_NAME", "DISTINCT STUDENT_ID, get_student_name(STUDENT_ID) AS STUDENT_NAME", $student_id, " WHERE DELETE_FLAG=0"); ?>

 								</select>

 							</div>





 							<div class="form-group col-sm-1">

 								<label> &nbsp;</label>

 								<input type="submit" class="form-control btn btn-sm btn-primary" value="Filter" name="search" />

 							</div>

 							<div class="form-group col-sm-1">

 								<label> &nbsp;</label>

 								<a class="form-control btn btn-sm btn-warning" onclick="pageLoaderOverlay('show'); location.href='page.php?page=list-student-payments';">Clear</a>

 							</div>

 						</form>

 					</div>

 				</div>

 			</div>

 		</div>

 		<div class="row">

 			<div class="col-xs-12">

 				<div class="box">

 					<div class="box-header">

 						<a href="page.php?page=add-student-payment" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add New Payment</a>

 						<div class="clearfix"></div>

 					</div>

 					<!-- /.box-header -->

 					<div class="box-body">

 						<table class="table table-bordered table-hover data-tbl">

 							<thead>

 								<tr>

 									<th>#</th>

 									<th>Reciept No</th>

 									<th>Date</th>

 									<th>Recieved By</th>

 									<th>Student Name</th>

 									<th>Course Name</th>

 									<!-- <th>Total Course Fees</th> -->

 									<th>Fees Paid</th>

 									<th>Fees Balance</th>





 									<th>Action</th>

 								</tr>

 							</thead>

 							<tbody>

 								<?php

									include_once('include/classes/institute.class.php');

									$institute = new institute();



									$payments = $institute->list_student_payments($payment_id, $student_id, $institute_id, $staff_id, $condition);

									if ($payments != '') {

										$courseSrNo = 1;

										while ($courseData = $payments->fetch_assoc()) {

											extract($courseData);

											$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);

											$recievedby = '';

											if ($STAFF_ID != 0)

												$recievedby = $INSTITUTE_STAFF_NAME;

											else

												$recievedby = $INSTITUTE_NAME;





											if ($ACTIVE == 1)

												$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentCourseStatus(' . $PAYMENT_ID . ',0)"><i class="fa fa-check"></i></a>';

											elseif ($ACTIVE == 0)

												$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentCourseStatus(' . $PAYMENT_ID . ',1)"><i class="fa fa-times"></i></a>';



											$action = "<a href='page.php?page=update-student-payment&payid=$PAYMENT_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>

							<a href='javascript:void(0)' onclick='deleteStudentPayment($PAYMENT_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>

							<a href='javascript:void(0)' onclick='printPayReciept($PAYMENT_ID)' class='btn btn-link' title='Print Reciept'><i class=' fa fa-print'></i></a>

							";

											echo $courseDetail = "<tr id='row-$PAYMENT_ID'><td>$courseSrNo</td>

										  <td>$RECIEPT_NO</td>	

										  <td>$FEES_PAID_ON</td>	

										  <td>$recievedby</td>	

										  <td>$STUDENT_NAME</td>	 

										  <td>$COURSE_NAME</td>	

										  <!-- <td>$TOTAL_COURSE_FEES</td>	-->

										  <td>$FEES_PAID</td>											  	

										  <td>$FEES_BALANCE</td>

										  

										 <td>$action</td>

										 </tr>";

											$courseSrNo++;
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

 <div class="modal fade add-stud-course-details" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">



 	<img src="resources/dist/img/loader.gif" class="loader-mg-modal" />

 	<div class="modal-dialog modal-md" role="document">

 		<div class="modal-content">



 			<div class="box box-primary modal-body">

 				<div class="">

 					<form id="add_stud_course_info_form" method="post">

 						<div class="box-header with-border">

 							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

 							<h3 class="box-title course-info-studname">Add Student Course Details</h3>

 						</div>

 						<div class="box-body" id="course-info-add">



 							<input type="hidden" name="stud_id" id="stud_id" value="" />

 							<input type="hidden" name="action" id="action" value="add_stud_course" />

 							<div class="form-group course-type-error">

 								<label for="course_type">Select Course Type</label>

 								<select class="form-control" name="course_type" id="course_type" onchange="getInstituteCourses(this.value)">

 									<?php echo $db->MenuItemsDropdown('course_type_master', "COURSE_TYPE_ID", "COURSE_TYPE", "COURSE_TYPE_ID, COURSE_TYPE", '', ""); ?>

 								</select>

 								<span class="help-block"></span>

 							</div>

 							<div class="form-group course-error" id="msg-error">

 								<label for="course">Select Course</label>

 								<select class="form-control" name="course" id="course">

 									<?php echo $db->MenuItemsDropdown('institute_courses A LEFT JOIN courses B ON A.COURSE_ID=B.COURSE_ID', "COURSE_ID", "COURSE_NAME", "A.COURSE_ID, B.COURSE_NAME", '', " WHERE A.INSTITUTE_ID ='" . $_SESSION['user_id'] . "' AND A.DELETE_FLAG=0 AND A.ACTIVE=1 ORDER BY B.COURSE_NAME ASC"); ?>

 								</select>

 								<span class="help-block"></span>

 							</div>



 						</div>

 						<!-- /.box-header -->



 						<!-- /.box-footer -->

 						<div class="box-footer">

 							<div class="pull-right">

 								<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>

 								<button type="submit" name="add" class="btn btn-primary"><i class="fa fa-plus"></i> Add Course</button>

 							</div>

 						</div>

 					</form>

 				</div>

 			</div>

 		</div>

 	</div>

 </div>



 <!-- modal to send email -->

 <div class="modal fade show-stud-course-details" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

 	<img src="resources/dist/img/loader.gif" class="loader-mg-modal" />

 	<div class="modal-dialog modal-md" role="document">

 		<div class="modal-content">

 			<div class="box box-primary modal-body">

 				<div class="">

 					<div class="box-header with-border">

 						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>



 						<h3 class="box-title course-info-studname">View Student Course Details</h3>

 						<span id="add-course-btn"><a href='javascript:void(0)' class='btn btn-sm btn-primary  add-stud-course-info' title='Add New Course Details' data-toggle='modal' data-id='' data-name='' data-email='' data-target='.add-stud-course-details'><i class='fa  fa-plus'></i> Add New Course </a></span>

 					</div>

 					<div class="box-body" id="course-info"></div>

 					<!-- /.box-header -->

 					<!-- /.box-footer -->

 					<div class="box-footer">

 						<div class="pull-right">

 							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>

 							<!-- <button type="submit" name="send" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button> -->

 						</div>

 					</div>

 				</div>

 			</div>

 		</div>

 	</div>

 </div>



 <!-- modal to view course details -->

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