<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<a href="page.php?page=addCourse" class="btn btn-primary" style="float: right">Add Courses</a>

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
								<th>Course Code</th>
								<th>Course Name</th>
								<th>Duration</th>
								<th>Exam Status</th>
								<th>Question Bank Status</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							include_once('include/classes/course.class.php');
							$course = new course();
							$res = $course->list_courses('', '');
							if ($res != '') {
								$srno = 1;
								while ($data = $res->fetch_assoc()) {
									//print_r($data);
									$COURSE_ID 		= $data['COURSE_ID'];
									$COURSE_CODE 	= $data['COURSE_CODE'];
									$COURSE_DURATION = $data['COURSE_DURATION'];
									$COURSE_NAME 	= $data['COURSE_NAME'];
									$COURSE_FEES 	= $data['COURSE_FEES'];
									$COURSE_MRP 	= $data['COURSE_MRP'];
									$MINIMUM_AMOUNT 	= $data['MINIMUM_AMOUNT'];
									$COURSE_AWARD_NAME 	= $data['COURSE_AWARD_NAME'];
									$ACTIVE			= $data['ACTIVE'];
									$CREATED_BY 	= $data['CREATED_BY'];
									$CREATED_ON 	= $data['CREATED_ON'];
									//$EXAM_FEES 	= $data['EXAM_FEES'];
									if ($db->permission('update_course')) {
										if ($ACTIVE == 1)
											$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeCoureStatus(' . $COURSE_ID . ',0)"><i class="fa fa-check"></i>Active</a>';
										elseif ($ACTIVE == 0)
											$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeCoureStatus(' . $COURSE_ID . ',1)"><i class="fa fa-times"></i>In-Active</a> ';
									} else {
										if ($ACTIVE == 1)
											$ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i>Active</span>';
										elseif ($ACTIVE == 0)
											$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i>In-Active</span> ';
									}

									/*if($ACTIVE==1) $ACTIVE= 'Active';
							elseif($ACTIVE==0) $ACTIVE= 'In-Active';
							*/
									$PHOTO = '../uploads/default_user.png';
									$action = '';
									if ($db->permission('update_course'))
										$action = "<a href='page.php?page=updateCourse&id=$COURSE_ID' class='btn btn-primary table-btn' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";
									/*if($db->permission('delete_course'))
							$action .= "<a href='javascript:void(0)' onclick='deleteCourse($COURSE_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>
							";
		*/
									$valid_exam = $db->validate_apply_exam($COURSE_ID, '', '');
									extract($valid_exam);
									$ExamStatus = '';
									$QuestionBankStatus = '';

									if ($valid_exam['errors']['exam_unavailable'] != '') {
										$ExamStatus = "<p style='background-color:red; padding:5px;'>" . $valid_exam['errors']['exam_unavailable'] . "</p>";
									}
									if ($valid_exam['errors']['qb_unavailable'] != '') {
										$QuestionBankStatus = "<p style='background-color:red; padding:5px;'>" . $valid_exam['errors']['qb_unavailable'] . "</p>";
									}

									$rowColor = '';
									if ($ExamStatus != '' || $QuestionBankStatus != '') {
										$rowColor = "style='background-color:yellow'";
									}


									echo " <tr id='course-id" . $COURSE_ID . "' $rowColor >									
									<td>$srno</td>	
									<td>$COURSE_CODE</td>
									<td>$COURSE_AWARD_NAME IN $COURSE_NAME</td>	
									<td>$COURSE_DURATION</td>
									<td>$ExamStatus</td>
									<td>$QuestionBankStatus</td>
									<td id='status-$COURSE_ID'>$ACTIVE</td>
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