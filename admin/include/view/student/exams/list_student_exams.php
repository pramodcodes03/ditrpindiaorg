<?php

include('include/classes/exam.class.php');
$exam = new exam();
include('include/classes/exammultisub.class.php');
$exammultisub = new exammultisub();
include('include/classes/coursetypingexam.class.php');
$coursetypingexam = new coursetypingexam();


$studid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

$res = $exam->list_student_exam_results('', $studid, '', '', '');

$exam_result_info_multi_sub = $exammultisub->list_student_exam_results_multi_sub('', $studid, '', '', '');

$exam_result_typing = $coursetypingexam->list_student_exam_results_typing('', $studid, '', '', '');

?>

<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">All Exams Results
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
								<th>Exam</th>

								<th>Exam Mode</th>
								<th>Correct Answers</th>

								<th>Incorrect Answers</th>

								<th>Total Marks</th>

								<th>Marks Obtained</th>

								<th>Percentage</th>

								<th>Grade</th>

								<th>Result</th>

								<th>Date</th>
							</tr>
						</thead>
						<tbody>
							<?php



							if ($res != '') {

								$courseSrNo = 1;

								while ($courseData = $res->fetch_assoc()) {
									extract($courseData);
									$exammode = '';

									if ($EXAM_TYPE == 1) {
										$exammode = "ONLINE";
									}
									if ($EXAM_TYPE == 3) {
										$exammode = "OFFLINE";
									}

							?>

									<tr id="row-<?= $EXAM_RESULT_ID ?>">



										<td><?= $EXAM_TITLE ?></td>

										<td><?= $exammode ?></td>

										<td><?= $CORRECT_ANSWER ?></td>
										<td><?= $INCORRECT_ANSWER ?></td>

										<td><?= $EXAM_TOTAL_MARKS ?></td>

										<td><?= $MARKS_OBTAINED ?></td>

										<td><?= $MARKS_PER ?></td>

										<td><?= $GRADE ?></td>

										<td><?= $RESULT_STATUS ?></td>

										<td><?= $CREATED_DATE ?></td>



									</tr>

								<?php

									$courseSrNo++;
								}
							}

							if ($exam_result_info_multi_sub != '') {

								$courseSrNo = 1;

								while ($courseData = $exam_result_info_multi_sub->fetch_assoc()) {



									extract($courseData);
									//print_r($courseData);

									$exammode = '';

									if ($EXAM_TYPE == 1) {
										$exammode = "ONLINE";
									}
									if ($EXAM_TYPE == 3) {
										$exammode = "OFFLINE";
									}

									/*$COURSE 	= $db->get_course_detail_multi_sub($COURSE_ID,1);

						$MULTI_SUB_COURSE_NAME = $COURSE['MULTI_SUB_COURSE_NAME'];*/

									$CORRECT_ANSWER = '';
									$INCORRECT_ANSWER = '';

								?>

									<tr id="row-<?= $EXAM_RESULT_ID ?>">



										<td><?= $EXAM_TITLE ?></td>

										<td><?= $exammode ?></td>

										<td><?= $CORRECT_ANSWER ?></td>

										<td><?= $INCORRECT_ANSWER ?></td>

										<td><?= $EXAM_TOTAL_MARKS ?></td>

										<td><?= $MARKS_OBTAINED ?></td>

										<td><?= $MARKS_PER ?></td>

										<td><?= $GRADE ?></td>

										<td><?= $RESULT_STATUS ?></td>

										<td><?= $CREATED_DATE ?></td>



									</tr>

							<?php

									$courseSrNo++;
								}
							}
							if ($exam_result_typing != '') {
								$srno = 1;
								while ($data = $exam_result_typing->fetch_assoc()) {
									extract($data);
									//print_r($data);			
									/*	$PHOTO = SHOW_IMG_AWS.'default_user.png';*/
									if ($STUDENT_PHOTO != '') {
										$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;
									} else {
										$PHOTO = '../uploads/default_user.png';
									}

									$GRADE = !empty($GRADE) ? $GRADE : '-';
									$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
									//$action = "<!-- <a href='update-exam-results&id=$EXAM_RESULT_FINAL_ID' class='btn' title='Edit'><i class='fa fa-pencil'></i></a> -->";
									$action = "";
									if ($db->permission('delete_exam_result'))
										$action .= "<a href='javascript:void(0)' onclick='deleteStudentResult_MultiSub(this.id)' id='result$EXAM_RESULT_FINAL_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";

									if ($APPLY_FOR_CERTIFICATE == 0)
										$APPLY_FOR_CERTIFICATE_LABEL = ($APPLY_FOR_CERTIFICATE == 0) ? 'No' : 'Yes';
									$disableCheck = ($APPLY_FOR_CERTIFICATE == 1) ? 'disabled' : '';

									$checkstud_typing = isset($_POST['checkstud_typing']) ? $_POST['checkstud_typing'] : '';
									$checkbox1 = "<td>";
									if ($db->permission('apply_certificate') && $APPLY_FOR_CERTIFICATE == 0)
										$checkbox1 .= "<input type='checkbox' name='checkstud_typing[]' id='checkstud_typing$EXAM_RESULT_FINAL_ID' value='$EXAM_RESULT_FINAL_ID' $disableCheck />";
									$checkbox1 .= "</td>";


									if ($RESULT_STATUS == 'Passed') {

										echo "<tr id='row-result$EXAM_RESULT_FINAL_ID'>													
							<td>$COURSE_NAME</td>							
							<td> OFFLINE </td>
							<td></td>
							<td></td>
							<td>$EXAM_TOTAL_MARKS</td>
							<td>$MARKS_OBTAINED</td>
							<td>$MARKS_PER</td>
							<td>$GRADE</td>
							<td>$RESULT_STATUS</td>	
							<td>$CREATED_DATE</td>
							</tr>
							";
									}
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