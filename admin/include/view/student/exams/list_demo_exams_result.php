<?php

include('include/classes/exam.class.php');
$exam = new exam();
$studid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

$sessionData = $exam->list_student_demo_exam_sessions('', $studid, '');


?>

<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">All Demo Exams Results
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
								<th>Sr No</th>
								<th>Exam</th>

								<th>Exam Mode</th>
								<th>Correct Answers</th>

								<th>Incorrect Answers</th>

								<th>Total Marks</th>

								<th>Marks Obtained</th>

								<th>Percentage</th>

								<th>Grade</th>

								<th>Result</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php



							if ($sessionData != '') {

								$courseSrNo = 1;

								while ($courseData = $sessionData->fetch_assoc()) {



									//extract($courseData);	
									//print_r($courseData); exit();
									$session_id = $courseData['session_id'];

									$res = $exam->list_student_demo_exam_results('', $studid, $session_id);
									while ($data = $res->fetch_assoc()) {
										extract($data);
										$exammode = '';
										$exammode = "ONLINE";
										$INCORRECT_ANSWER = $TOTAL_QUESTIONS - $CorrectAnswer;

										$MARKS_OBTAINED = $MARKS_PER_QUE * $CorrectAnswer;
										$MARKS_PER = ($MARKS_OBTAINED * 100) / $TOTAL_MARKS;

										if ($MARKS_PER >= 85) {
											$grade = "A+ : Excellent";
											$result_status = '<font color="#00CC66">Passed</font>';
										} elseif ($MARKS_PER >= 70 && $MARKS_PER < 85) {
											$grade = "A : Very Good";
											$result_status = '<font color="#00CC66">Passed</font>';
										} elseif ($MARKS_PER >= 55 && $MARKS_PER < 70) {
											$grade = "B : Good";
											$result_status = '<font color="#00CC66">Passed</font>';
										} elseif ($MARKS_PER >= 40 && $MARKS_PER < 55) {
											$grade = "C : Average";
											$result_status = '<font color="#00CC66">Passed</font>';
										} else {
											$grade = "";
											$result_status = '<font color="#FF0000">Failed</font>';
										}
										$editLink = '';
										$editLink .= "<a href='page.php?page=viewDemoResult&id=$session_id' class='btn  btn-primary table-btn' title='View'><i class='mdi mdi-eye'></i></a>";
							?>

										<tr>

											<td><?= $courseSrNo ?></td>
											<td><?= $EXAM_TITLE ?></td>

											<td><?= $exammode ?></td>

											<td><?= $CorrectAnswer ?></td>
											<td><?= $INCORRECT_ANSWER ?></td>

											<td><?= $TOTAL_MARKS ?></td>

											<td><?= $MARKS_OBTAINED ?></td>

											<td><?= $MARKS_PER ?></td>

											<td><?= $grade ?></td>

											<td><?= $result_status ?></td>

											<td><?= $editLink ?></td>

										</tr>

							<?php

										$courseSrNo++;
									}
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