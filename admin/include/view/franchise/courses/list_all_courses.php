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
	?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
 		<h1>
 			List All Courses
 			<small>All Courses</small>
 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
 			<li><a href="#"> Courses</a></li>

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
 						<?php if ($db->permission('view_all_courses')) { ?>
 							<a href="page.php?page=list-all-courses" class="btn btn-sm btn-primary"><i class="fa fa-list"></i> List All Course</a> &nbsp;&nbsp;
 						<?php } ?>

 						<?php if ($db->permission('view_ditrp_courses')) { ?>
 							<a href="page.php?page=list-ditrp-courses" class="btn btn-sm btn-primary"><i class="fa fa-list"></i> List DITRP Course</a> &nbsp;&nbsp;
 						<?php } ?>

 						<?php if ($db->permission('view_nonditrp_courses')) { ?>
 							<a href="page.php?page=list-nonditrp-courses" class="btn btn-sm btn-primary"><i class="fa fa-list"></i> List NON-DITRP Course</a> &nbsp;&nbsp;
 						<?php } ?>

 						<?php if ($db->permission('add_ditrp_courses')) { ?>
 							<a href="page.php?page=add-ditrp-course" class="btn btn-sm btn-warning"><i class="fa fa-plus"></i> Add DITRP Course</a> &nbsp;&nbsp;
 						<?php } ?>

 						<?php if ($db->permission('add_nonditrp_courses')) { ?>
 							<a href="page.php?page=add-nonditrp-course" class="btn btn-sm btn-warning"><i class="fa fa-plus"></i> Add NON-DITRP Course</a>
 						<?php } ?>

 						<?php if ($db->permission('delete_all_courses')) { ?>
 							<a href="javascript:void(0)" class="btn btn-sm btn-danger pull-right" onclick="bulkDeleteInstCourse()"><i class="fa fa-trash"></i> Delete</a>
 						<?php } ?>


 					</div>
 					<!-- /.box-header -->
 					<div class="box-body">

 						<table class="table table-bordered table-hover data-tbl">
 							<thead>
 								<tr>
 									<?php if ($db->permission('delete_all_courses')) { ?> <th><label><input type='checkbox' value='1' id='selectall' class='edit-course'></label></th> <?php } ?>
 									<th>Sr.</th>
 									<th>Course Type</th>
 									<th>Course Name</th>
 									<th>Certifying Authority</th>
 									<!--    <th>Course Award</th> --->
 									<th>Exam Fees</th>
 									<th>Course Fees</th>
 									<th>Duration</th>
 									<th>Status</th>
 									<th>Action</th>
 								</tr>
 							</thead>
 							<tbody>
 								<?php
									include_once('include/classes/course.class.php');
									$course = new course();
									$user_id = $_SESSION['user_id'];
									$user_role = $_SESSION['user_role'];
									if ($user_role == 5) {
										$institute_id = $db->get_parent_id($user_role, $user_id);
										$staff_id = $user_id;
									} else {
										$institute_id = $user_id;
										$staff_id = 0;
									}
									$res = $course->list_all_courses($institute_id);
									if ($res != '') {
										$srno = 1;
										while ($data = $res->fetch_assoc()) {
											extract($data);
											$info = $db->get_course_detail($COURSE_ID, $COURSE_TYPE);
											//print_r($info);

											$COURSE_NAME = isset($info['COURSE_NAME_MODIFY']) ? $info['COURSE_NAME_MODIFY'] : '';

											$COURSE_DURATION = isset($info['COURSE_DURATION']) ? $info['COURSE_DURATION'] : '';
											//$COURSE_CODE = $info['COURSE_CODE'];
											$COURSE_AUTHORITY = isset($info['COURSE_AUTHORITY']) ? $info['COURSE_AUTHORITY'] : '';
											$COURSE_AWARD = isset($info['COURSE_AWARD']) ? $info['COURSE_AWARD'] : '';
											$COURSE_FEES_ORG = isset($info['COURSE_FEES']) ? $info['COURSE_FEES'] : '';
											//$ACTIVE = $info['ACTIVE'];
											if ($ACTIVE == 1)
												$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstCourseStatus(' . $INSTITUTE_COURSE_ID . ',0)"><i class="fa fa-check"></i></a>';
											else if ($ACTIVE == 0)
												$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstCourseStatus(' . $INSTITUTE_COURSE_ID . ',1)"><i class="fa fa-times"></i></a>';

											$COURSE_TYPE_NAME = ($COURSE_TYPE == 1) ? 'DITRP' : 'NON-DITRP';
											$action = "";
											if ($COURSE_TYPE == 2 && $db->permission('update_nonaicpe_courses')) {
												$action = "<a href='page.php?page=update-non-course&id=$COURSE_ID' class='btn btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";
											} else if ($COURSE_TYPE == 1 && $db->permission('update_aicpe_courses')) {
												$action = "<a href='page.php?page=update-ditrp-course&id=$INSTITUTE_COURSE_ID' class='btn btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";
											}
											if ($db->permission('delete_all_courses'))
												$action .= "<a href='javascript:void(0)' onclick='deleteInstCourse($INSTITUTE_COURSE_ID)' class='btn btn-link' title='Remove'><i class=' fa fa-trash'></i></a>";

											$checkbox = "";
											if ($db->permission('delete_all_courses'))
												$checkbox = "<td><label><input type='checkbox' name='check_course' value='$INSTITUTE_COURSE_ID' id='check-$INSTITUTE_COURSE_ID' class='check-course' ></label></td>	";
											echo " <tr id='row-" . $INSTITUTE_COURSE_ID . "'>
							$checkbox
							<td>$srno</td>	
							<td>$COURSE_TYPE_NAME</td>								
							<td>$COURSE_NAME</td>
							<td>$COURSE_AUTHORITY</td>
							<!-- <td>$COURSE_AWARD</td> -->
							<td>$COURSE_FEES_ORG</td>
							<td>$COURSE_FEES</td>
							<td>$COURSE_DURATION</td>
							<td id='status-$INSTITUTE_COURSE_ID'>$ACTIVE</td>
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


 <!-- modal to send email -->
 <div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
 	<div class="modal-dialog modal-md" role="document">
 		<div class="modal-content">

 			<div class="box box-primary modal-body">
 				<form action="" method="post" id="bulk_edit_course_form">
 					<div class="">
 						<div class="box-header with-border">
 							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 							<h3 class="box-title">Bulk Edit</h3>
 						</div>
 						<!-- /.box-header -->
 						<div class="box-body">
 							<div class="form-group">
 								<p class="help-block" id="ajax-data"></p>
 							</div>
 							<div class="form-group" id="exam_fees_err">
 								Update Price Of Selected Courses
 								<input class="form-control" name="exam_fees" id="exam_fees" placeholder="Enter Price">
 								<span class="help-block"></span>
 								<input class="form-control" type="hidden" name="action" id="action" value="bulk_update_submit_course">
 							</div>
 							<div class="form-group text-center loader-img">
 								<img src="resources/dist/img/loader.gif" />
 							</div>
 						</div>
 						<!-- /.box-body -->
 						<div class="box-footer">
 							<div class="pull-right">
 								<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
 								<input type="submit" name="submit" id="submit_btn" class="btn btn-primary" value="Update" />
 							</div>
 						</div>
 						<!-- /.box-footer -->
 					</div>
 				</form>
 			</div>
 		</div>
 	</div>
 </div>