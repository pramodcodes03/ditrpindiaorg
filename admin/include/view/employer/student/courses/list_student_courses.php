 <?php

	$student_id = $db->test(isset($_GET['id']) ? $_GET['id'] : '');

	?>

 <!-- Content Wrapper. Contains page content -->

 <div class="content-wrapper">

 	<!-- Content Header (Page header) -->

 	<section class="content-header">

 		<h1>

 			List Students Courses

 			<small>All Courses</small>

 		</h1>

 		<ol class="breadcrumb">

 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

 			<li><a href="page.php?page=list-students"> Students</a></li>

 			<li class="#"> List Student Courses</li>

 			<li class="active"> <?= $db->get_stud_name($student_id) ?></li>

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

 						<select class="pull-left select2" name="course_type" id="course_type" onchange="window.location.assign('list-student-courses&id='+this.value)">

 							<?php echo $db->MenuItemsDropdown('student_details', "STUDENT_ID", "STUDENT_NAME", "STUDENT_ID, CONCAT(CONCAT(STUDENT_FNAME,' ',STUDENT_MNAME),' ',STUDENT_LNAME) STUDENT_NAME", $student_id, ""); ?>

 						</select>



 						<a href="page.php?page=add-student-course&id=<?= $student_id ?>" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Register New Course</a>

 						<div class="clearfix"></div>

 					</div>

 					<!-- /.box-header -->

 					<div class="box-body">

 						<table class="table table-bordered table-hover data-tbl">

 							<thead>

 								<tr>

 									<th>#</th>

 									<th>Course Name</th>

 									<!-- <th>Course Duration</th>

					<th>Exam Fees</th> -->

 									<th>Course Type</th>

 									<th>Exam Type</th>

 									<th>Exam Status</th>

 									<th>Status</th>

 									<th>Action</th>

 								</tr>

 							</thead>

 							<tbody>

 								<?php

									include_once('include/classes/student.class.php');

									$student = new student();

									$courses = $student->list_student_courses('', $student_id, '');

									if ($courses != '') {

										$courseSrNo = 1;

										while ($courseData = $courses->fetch_assoc()) {

											$STUD_COURSE_DETAIL_ID = $courseData['STUD_COURSE_DETAIL_ID'];

											$STUDENT_ID 	= $courseData['STUDENT_ID'];

											$COURSE_ID 	= $courseData['COURSE_ID'];

											$COURSE_TYPE = $courseData['COURSE_TYPE'];

											$ACTIVE = $courseData['ACTIVE'];



											$COURSE_TYPE_NAME = $courseData['COURSE_TYPE_NAME'];

											$EXAM_STATUS 	= $courseData['EXAM_STATUS'];

											$EXAM_TYPE 		= $courseData['EXAM_TYPE'];

											$EXAM_STATUS_NAME = $courseData['EXAM_STATUS_NAME'];

											$EXAM_TYPE_NAME = $courseData['EXAM_TYPE_NAME'];



											$COURSE_INFO = $db->get_course_detail($COURSE_ID, $COURSE_TYPE);

											$COURSE_NAME = isset($COURSE_INFO['COURSE_NAME']) ? $COURSE_INFO['COURSE_NAME'] : '';

											$COURSE_FEES = isset($COURSE_INFO['COURSE_FEES']) ? $COURSE_INFO['COURSE_FEES'] : '';

											$COURSE_DURATION = isset($COURSE_INFO['COURSE_DURATION']) ? $COURSE_INFO['COURSE_DURATION'] : '';

											$COURSE_FEES = $COURSE_INFO['COURSE_FEES'];

											if ($ACTIVE == 1)

												$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentCourseStatus(' . $STUD_COURSE_DETAIL_ID . ',0)"><i class="fa fa-check"></i></a>';

											elseif ($ACTIVE == 0)

												$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentCourseStatus(' . $STUD_COURSE_DETAIL_ID . ',1)"><i class="fa fa-times"></i></a>';



											$action = "<a href='page.php?page=update-student-course&stud=$STUDENT_ID&cdetail=$STUD_COURSE_DETAIL_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>

					<a href='javascript:void(0)' onclick='deleteStudentCourse($STUDENT_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>";

											echo $courseDetail = "<tr><td>$courseSrNo</td>

										  <td>$COURSE_NAME</td>	

										  <!-- <td>$COURSE_DURATION</td>	

										  <td>$COURSE_FEES</td>	 -->										 

										  <td>$COURSE_TYPE_NAME</td>	

										  <td>$EXAM_TYPE_NAME</td>	

										  <td>$EXAM_STATUS_NAME</td>	

										  <td>$ACTIVE</td>	

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