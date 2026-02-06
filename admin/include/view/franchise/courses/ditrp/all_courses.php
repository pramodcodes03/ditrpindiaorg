 <!-- Content Wrapper. Contains page content -->

 <div class="content-wrapper">

 	<!-- Content Header (Page header) -->

 	<section class="content-header">

 		<h1>

 			List NON-DITRP Courses

 			<small>All NON-DITRP Courses</small>

 		</h1>

 		<ol class="breadcrumb">

 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

 			<li><a href="#"> Courses</a></li>

 			<li class="active"> List NON-DITRP Courses</li>

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

 					<!-- /.box-header -->

 					<div class="box-body">



 						<table class="table table-bordered table-hover data-tbl">

 							<thead>

 								<tr>

 									<th><label><input type='checkbox' value='1' id='check-all' class='minimal edit-course'></label></th>

 									<th>Sr.</th>

 									<th>Course Authority</th>

 									<th>Course Name</th>

 									<th>Copurse Fees</th>

 									<th>Exam Fees</th>

 									<th>Duration</th>

 								</tr>

 							</thead>

 							<tbody>

 								<?php

									include_once('include/classes/course.class.php');

									$course = new course();

									$user_id 	= $_SESSION['user_id'];

									$user_role 	= $_SESSION['user_role'];

									if ($user_role == 5) {

										$institute_id = $db->get_parent_id($user_role, $user_id);

										$staff_id = $user_id;
									} else {

										$institute_id = $user_id;

										$staff_id = 0;
									}

									$res = $course->list_nonaicpe_courses('', $institute_id);



									$sql = "SELECT A.INSTITUTE_COURSE_ID, A.INSTITUTE_ID, A.COURSE_ID, A.COURSE_TYPE FROM institute_courses A WHERE A.INSTITUTE_ID='$institute_id'";

									$sql1 = $db->execQuery($sql);



									while ($newsql = $sql1->fetch_assoc()) {

										//print_r($newsql);

										$cid 		= $newsql['COURSE_ID'];

										$ctypeid 	= $newsql['COURSE_TYPE'];

										$icid 		= $newsql['INSTITUTE_COURSE_ID'];

										$iid 		= $newsql['INSTITUTE_ID'];

										echo $sqla  = "SELECT * FROM `courses` WHERE COURSE_ID='1'";

										echo "<br>";

										echo $sqln  = "SELECT * FROM  non_aicpe_courses  WHERE INSTITUTE_ID='$iid' AND COURSE_ID='$cid'" . "<br>";
									}



									if ($res != '') {

										$srno = 1;

										while ($data = $res->fetch_assoc()) {

											$COURSE_ID 			= $data['COURSE_ID'];

											$COURSE_AUTHORITY 	= $data['COURSE_AUTHORITY'];

											$COURSE_DURATION	= $data['COURSE_DURATION'];

											$COURSE_NAME 		= $data['COURSE_NAME'];

											$COURSE_FEES 		= $data['COURSE_FEES'];

											$EXAM_FEES 			= $data['EXAM_FEES'];

											$ACTIVE				= $data['ACTIVE'];

											$CREATED_BY 		= $data['CREATED_BY'];

											$CREATED_ON 		= $data['CREATED_ON'];



											if ($ACTIVE == 1)

												$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeCourseStatus(' . $COURSE_ID . ',0)"><i class="fa fa-check"></i></a>';

											elseif ($ACTIVE == 0)

												$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeCourseStatus(' . $COURSE_ID . ',1)"><i class="fa fa-times"></i></a>';

											$PHOTO = '../uploads/default_user.png';



											$action = "<a href='page.php?page=update-non-course&id=$COURSE_ID' class='btn btn-link' title='Edit'><i class=' fa fa-pencil'></i></a> 

					<a href='javascript:void(0)' onclick='deleteNonCourse($COURSE_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>

					";



											echo " <tr id='course-id" . $COURSE_ID . "'>

							<td><label><input type='checkbox' name='check_course' value='$COURSE_ID' id='check-$COURSE_ID' class='minimal check-course' ></label></td>	

							<td>$srno</td>	

							<td>$COURSE_AUTHORITY</td>

							<td>$COURSE_NAME</td>

							<td>$COURSE_FEES</td>

							<td>$EXAM_FEES</td>

							<td>$COURSE_DURATION</td>											

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