<?php
include_once('include/classes/student.class.php');
$student = new student();
$student_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';
?>
<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">My Courses
				</h4>
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

				<div class="table-responsive pt-3">
					<table id="order-listing" class="table">
						<thead>
							<tr>
								<th>Sr.</th>
								<th>Course Name</th>
								<th>Course Fees</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php

							$res = $student->list_student_courses('', $student_id, '');
							if ($res != '') {
								$srno = 1;
								while ($data = $res->fetch_assoc()) {
									extract($data);
									//print_r($data);
									$course_name = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
									$action = '';
									$action .= "<a href='page.php?page=coursesDetails&id=$INSTITUTE_COURSE_ID' class='btn btn-primary btn1' title='Details'>Details</a>&nbsp;&nbsp;";

									$action .= "<a href='page.php?page=feesDetails&id=$student_id&studCourse=$STUD_COURSE_DETAIL_ID' class='btn btn-info btn1' title='Fees Details'>Fees Details</a>";

									$display_status = $db->get_student_displayform_status($student_id);

									if ($display_status['DISPLAY_FORM_STATUS'] == '1') {
										$action .= "<a href='page.php?page=viewStudentForm&id=$student_id&courseid=$INSTITUTE_COURSE_ID' class='btn btn-warning table-btn' title='View Form' target='_blank'><i class='mdi mdi-file-pdf'></i></a>";
										$action .= "<a href='page.php?page=viewStudentIdcard&id=$student_id&courseid=$INSTITUTE_COURSE_ID' class='btn btn-success table-btn' title='View ID Card' target='_blank'><i class='mdi mdi-account-card-details'></i></a>";
									}

									echo " <tr id='id" . $STUD_COURSE_DETAIL_ID . "'>
									<td>$srno</td>										
									<td>$course_name</td>
									<td>$COURSE_FEES</td>
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
		</div>
	</div>
</div>