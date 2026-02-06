<?php
$student_id = $db->test(isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '');
$action		= isset($_POST['action']) ? $_POST['action'] : '';

include_once('include/classes/student.class.php');
$student = new student();
if ($action != '') {
	//print_r($_POST);
	$result = $student->update_student_education();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=studentDetails');
	}
}
$res = $student->list_student($student_id, '', '');
if ($res != '') {
	while ($resdata = $res->fetch_assoc()) {
		extract($resdata);
		//print_r($resdata);
	}
}

?>

<div class="content-wrapper">
	<div class="row">
		<div class="col-md-12 col-xl-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Edit Profile</h4>
					<?php
					if (isset($success)) {
					?>
						<div class="row">
							<div class="col-sm-12">
								<div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
									<h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
									<?= isset($message) ? $message : 'Please correct the errors.'; ?>
								</div>
							</div>
						</div>
					<?php
					}
					?>
					<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">
						<div style="float:right">
							<a href="page.php?page=studentDetails" class="btn btn-warning btn1" title="Cancel">Cancel</a>
							&nbsp;&nbsp;&nbsp;
							<input type="submit" class="btn btn-primary btn1" name="action" value="Update Profile" />
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="nav-item active"><a class="nav-link" href="#tab_3" data-toggle="tab">Educational Details</a></li>
										<li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">Work Experience Details</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tab_3">
											<input type="hidden" name="student_id" value="<?= $STUDENT_ID ?>">
											<input type="hidden" name="enquiry_id" value="<?= $ENQUIRY_ID ?>">
											<input type="hidden" name="institute_id" value="<?= $INSTITUTE_ID ?>">
											<input type="hidden" name="staff_id" value="<?= $STAFF_ID ?>">
											<input type="hidden" name="studcode" value="<?= isset($_POST['studcode']) ? $_POST['studcode'] : $student->generate_student_code() ?>">

											<div class="box box-solid">
												<div class="box-body">
													<div class="box-group" id="accordion">
														<?php
														$edustud = $student->list_student_educational_info('', $STUDENT_ID);
														$i = 1;
														$max_edu = 4;
														echo '<input type="hidden" name="max_edu" value="' . $max_edu . '" />';
														if ($edustud != '') {

															while ($studed = $edustud->fetch_assoc()) {
																extract($studed);
																if ($START_DATE == '01-01-1970' || $START_DATE == '00-00-0000')
																	$START_DATE = '';
																if ($END_DATE == '01-01-1970' || $END_DATE == '00-00-0000')
																	$END_DATE = '';

														?>
																<div class="accordion accordion-multi-colored panel box box-danger">
																	<div class="card box-header with-border">
																		<h4 class="card-header box-title">
																			<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $i ?>" aria-expanded="false" class="collapsed">Educational Details #<?= $i ?></a>
																		</h4>
																	</div>
																	<div id="collapse<?= $i ?>" class="panel-collapse collapse" aria-expanded="false" style="">
																		<div class="box-body">
																			<div class="row col-md-12">
																				<div class="form-group col-md-4 <?= (isset($errors['edu_coursename' . $i])) ? 'has-error' : '' ?>">
																					<label for="edu_coursename<?= $i ?>">Course Name</label>
																					<input type="text" class="form-control" placeholder="Course Name" name="edu_coursename<?= $i ?>" value="<?= isset($_POST['edu_coursename' . $i]) ? $_POST['edu_coursename' . $i] : $COURSE_NAME ?>">
																					<span class="help-block"><?= (isset($errors['edu_coursename' . $i])) ? $errors['edu_coursename' . $i] : '' ?></span>
																				</div>

																				<div class="form-group col-md-4 <?= (isset($errors['edu_instname' . $i])) ? 'has-error' : '' ?>">
																					<label for="edu_instname<?= $i ?>">Institute Name</label>
																					<input type="text" class="form-control" placeholder="Institute Name" name="edu_instname<?= $i ?>" value="<?= isset($_POST['edu_instname' . $i]) ? $_POST['edu_instname' . $i] : $INSTITUTE_NAME ?>">
																					<span class="help-block"><?= (isset($errors['edu_instname' . $i])) ? $errors['edu_instname' . $i] : '' ?></span>
																				</div>

																				<div class="form-group col-md-4 <?= (isset($errors['edu_universityname' . $i])) ? 'has-error' : '' ?>">
																					<label for="edu_universityname<?= $i ?>">Unviersity / Borad Name</label>
																					<input type="text" class="form-control" placeholder="University or Board Name" name="edu_universityname<?= $i ?>" value="<?= isset($_POST['edu_universityname' . $i]) ? $_POST['edu_universityname' . $i] : $UNIVERSITY_NAME ?>">
																					<span class="help-block"><?= (isset($errors['edu_universityname' . $i])) ? $errors['edu_universityname' . $i] : '' ?></span>
																				</div>
																				<div class="form-group col-md-4 <?= (isset($errors['edu_startdate' . $i])) ? 'has-error' : '' ?>">
																					<label for="edu_startdate<?= $i ?>">Start Date</label>
																					<input type="date" class="form-control" placeholder="dd-mm-yyy" name="edu_startdate<?= $i ?>" id="datefrom" value="<?= isset($_POST['edu_startdate' . $i]) ? $_POST['edu_startdate' . $i] : $START_DATE ?>">
																					<span class="help-block"><?= (isset($errors['edu_startdate' . $i])) ? $errors['edu_startdate' . $i] : '' ?></span>
																				</div>
																				<div class="form-group col-md-4 <?= (isset($errors['edu_enddate' . $i])) ? 'has-error' : '' ?>">
																					<label for="edu_enddate<?= $i ?>">End Date</label>
																					<input type="date" class="form-control" placeholder="dd-mm-yyy" id="dateto" name="edu_enddate<?= $i ?>" value="<?= isset($_POST['edu_enddate' . $i]) ? $_POST['edu_enddate' . $i] : $END_DATE ?>">
																					<span class="help-block"><?= (isset($errors['edu_enddate' . $i])) ? $errors['edu_enddate' . $i] : '' ?></span>
																				</div>
																				<div class="form-group col-md-4 <?= (isset($errors['edu_otherinfo' . $i])) ? 'has-error' : '' ?>">
																					<label>Other Information</label>
																					<textarea class="form-control" rows="3" placeholder="Other information" name="edu_otherinfo<?= $i ?>"><?= isset($_POST['edu_otherinfo' . $i]) ? $_POST['edu_otherinfo' . $i] : $DESCRIPTION ?></textarea>
																					<span class="help-block"><?= (isset($errors['edu_otherinfo' . $i])) ? $errors['edu_otherinfo' . $i] : '' ?></span>
																				</div>
																				<div class="form-group col-md-4 <?= (isset($errors['edu_marks' . $i])) ? 'has-error' : '' ?>">
																					<label for="edu_marks<?= $i ?>">Marks / Grade</label>
																					<input type="text" class="form-control" placeholder="Marks / Grades obtained" id="edu_marks" name="edu_marks<?= $i ?>" value="<?= isset($_POST['edu_marks' . $i]) ? $_POST['edu_marks' . $i] : $MARKS ?>">
																					<span class="help-block"><?= (isset($errors['edu_marks' . $i])) ? $errors['edu_marks' . $i] : '' ?></span>
																				</div>
																			</div>
																		</div>

																	</div>

																</div>

															<?php
																$i++;
															}
														}
														$j = $i;
														for ($i = $j; $i <= $max_edu; $i++) {
															?>

															<div class="accordion accordion-multi-colored panel box box-danger">
																<div class="card box-header with-border">
																	<h4 class="card-header box-title">
																		<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $i ?>" aria-expanded="false" class="collapsed"> Educational Details #<?= $i ?> </a>
																	</h4>
																</div>
																<div id="collapse<?= $i ?>" class="panel-collapse collapse" aria-expanded="false" style="">
																	<div class="box-body">
																		<div class="row col-md-12">
																			<div class="form-group col-md-4 <?= (isset($errors['edu_coursename' . $i])) ? 'has-error' : '' ?>">
																				<label for="edu_coursename<?= $i ?>">Course Name</label>
																				<input type="text" class="form-control" placeholder="Course Name" name="edu_coursename<?= $i ?>" value="<?= isset($_POST['edu_coursename' . $i]) ? $_POST['edu_coursename' . $i] : '' ?>">
																				<span class="help-block"><?= (isset($errors['edu_coursename' . $i])) ? $errors['edu_coursename' . $i] : '' ?></span>
																			</div>
																			<div class="form-group col-md-4 <?= (isset($errors['edu_instname' . $i])) ? 'has-error' : '' ?>">
																				<label for="edu_instname<?= $i ?>">Institute Name</label>
																				<input type="text" class="form-control" placeholder="Institute Name" name="edu_instname<?= $i ?>" value="<?= isset($_POST['edu_instname' . $i]) ? $_POST['edu_instname' . $i] : '' ?>">
																				<span class="help-block"><?= (isset($errors['edu_instname' . $i])) ? $errors['edu_instname' . $i] : '' ?></span>
																			</div>
																			<div class="form-group col-md-4 <?= (isset($errors['edu_universityname' . $i])) ? 'has-error' : '' ?>">
																				<label for="edu_universityname<?= $i ?>">Unviersity / Board Name</label>
																				<input type="text" class="form-control" placeholder="University or Board Name" name="edu_universityname<?= $i ?>" value="<?= isset($_POST['edu_universityname' . $i]) ? $_POST['edu_universityname' . $i] : '' ?>">
																				<span class="help-block"><?= (isset($errors['edu_universityname' . $i])) ? $errors['edu_universityname' . $i] : '' ?></span>
																			</div>
																			<div class="form-group col-md-4 <?= (isset($errors['edu_startdate1'])) ? 'has-error' : '' ?>">
																				<label for="edu_startdate<?= $i ?>">Start Date</label>
																				<input type="date" class="form-control" placeholder="dd-mm-yyy" name="edu_startdate<?= $i ?>" id="datefrom" value="<?= isset($_POST['edu_startdate' . $i]) ? $_POST['edu_startdate' . $i] : '' ?>">
																				<span class="help-block"><?= (isset($errors['edu_startdate' . $i])) ? $errors['edu_startdate' . $i] : '' ?></span>
																			</div>
																			<div class="form-group col-md-4 <?= (isset($errors['edu_enddate' . $i])) ? 'has-error' : '' ?>">
																				<label for="edu_enddate<?= $i ?>">End Date</label>
																				<input type="date" class="form-control" placeholder="dd-mm-yyy" id="dateto" name="edu_enddate<?= $i ?>" value="<?= isset($_POST['edu_enddate' . $i]) ? $_POST['edu_enddate' . $i] : '' ?>">
																				<span class="help-block"><?= (isset($errors['edu_enddate' . $i])) ? $errors['edu_enddate' . $i] : '' ?></span>
																			</div>
																			<div class="form-group  col-md-4  <?= (isset($errors['edu_otherinfo' . $i])) ? 'has-error' : '' ?>">
																				<label>Other Information</label>
																				<textarea class="form-control" rows="3" placeholder="Other information" name="edu_otherinfo<?= $i ?>"><?= isset($_POST['edu_otherinfo' . $i]) ? $_POST['edu_otherinfo' . $i] : '' ?></textarea>
																				<span class="help-block"><?= (isset($errors['edu_otherinfo' . $i])) ? $errors['edu_otherinfo' . $i] : '' ?></span>
																			</div>
																			<div class="form-group  col-md-4  <?= (isset($errors['edu_marks' . $i])) ? 'has-error' : '' ?>">
																				<label for="edu_marks<?= $i ?>">Marks / Grade</label>
																				<input type="text" class="form-control" placeholder="Marks / Grades obtained" id="edu_marks" name="edu_marks<?= $i ?>" value="<?= isset($_POST['edu_marks' . $i]) ? $_POST['edu_marks' . $i] : '' ?>">
																				<span class="help-block"><?= (isset($errors['edu_marks' . $i])) ? $errors['edu_marks' . $i] : '' ?></span>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														<?php
														}
														?>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab_4">
											<div class="box box-solid">
												<div class="box-body">
													<div class="box-group" id="accordion">
														<?php
														$expstud = $student->list_student_experience_info('', $STUDENT_ID);
														$i = 1;
														$max_exp = 4;
														echo '<input type="hidden" name="max_exp" value="' . $max_exp . '" />';
														if ($expstud != '') {
															while ($studexp = $expstud->fetch_assoc()) {
																extract($studexp);
																if ($START_DATE == '01-01-1970' || $START_DATE == '00-00-0000')
																	$START_DATE = '';
																if ($END_DATE == '01-01-1970' || $END_DATE == '00-00-0000')
																	$END_DATE = '';

														?>
																<div class="accordion accordion-multi-colored panel box box-danger">
																	<div class="card box-header with-border" style="padding:1rem">
																		<h4 class="card-header  box-title">
																			<a data-toggle="collapse" data-parent="#accordion" href="#expcollapse<?= $i ?>" aria-expanded="false" class="collapsed"> Work Experience #<?= $i ?> </a>
																		</h4>
																	</div>

																	<div id="expcollapse<?= $i ?>" class="panel-collapse collapse" aria-expanded="false" style="">
																		<div class="box-body">
																			<div class="row">
																				<div class="form-group col-md-4 <?= (isset($errors['exp_jobtitle1'])) ? 'has-error' : '' ?>">
																					<label for="exp_jobtitle<?= $i ?>">Job Title</label>
																					<input type="text" class="form-control" placeholder="Job title" name="exp_jobtitle<?= $i ?>" value="<?= isset($_POST['exp_jobtitle' . $i]) ? $_POST['exp_jobtitle' . $i] : $JOB_TITLE ?>">
																					<span class="help-block"><?= (isset($errors['exp_jobtitle1'])) ? $errors['exp_jobtitle1'] : '' ?></span>
																				</div>
																				<div class="form-group col-md-4 <?= (isset($errors['exp_companyname' . $i])) ? 'has-error' : '' ?>">
																					<label for="exp_companyname<?= $i ?>">Company Name</label>
																					<input type="text" class="form-control" placeholder="Company Name" name="exp_companyname<?= $i ?>" value="<?= isset($_POST['exp_companyname' . $i]) ? $_POST['exp_companyname' . $i] : $COMPANY_NAME ?>">
																					<span class="help-block"><?= (isset($errors['exp_companyname' . $i])) ? $errors['exp_companyname' . $i] : '' ?></span>
																				</div>
																				<div class="form-group col-md-4 <?= (isset($errors['exp_startdate' . $i])) ? 'has-error' : '' ?>">
																					<label for="exp_startdate<?= $i ?>">Start Date</label>
																					<input type="date" class="form-control" placeholder="dd-mm-yyy" name="exp_startdate<?= $i ?>" id="datefrom" value="<?= isset($_POST['exp_startdate' . $i]) ? $_POST['exp_startdate' . $i] : $START_DATE ?>">
																					<span class="help-block"><?= (isset($errors['exp_startdate' . $i])) ? $errors['exp_startdate' . $i] : '' ?></span>
																				</div>
																				<div class="form-group col-md-4 <?= (isset($errors['exp_enddate' . $i])) ? 'has-error' : '' ?>">
																					<label for="exp_enddate<?= $i ?>">End Date</label>
																					<input type="date" class="form-control" placeholder="dd-mm-yyy" id="dateto" name="exp_enddate<?= $i ?>" value="<?= isset($_POST['exp_enddate' . $i]) ? $_POST['exp_enddate' . $i] : $END_DATE ?>">
																					<span class="help-block"><?= (isset($errors['exp_enddate' . $i])) ? $errors['exp_enddate' . $i] : '' ?></span>
																				</div>
																				<div class="form-group col-md-4 <?= (isset($errors['exp_otherinfo' . $i])) ? 'has-error' : '' ?>">
																					<label>Other Information</label>
																					<textarea class="form-control" rows="3" placeholder="Other information" name="exp_otherinfo<?= $i ?>"><?= isset($_POST['exp_otherinfo' . $i]) ? $_POST['exp_otherinfo' . $i] : $DESCRIPTION ?></textarea>
																					<span class="help-block"><?= (isset($errors['exp_otherinfo' . $i])) ? $errors['exp_otherinfo' . $i] : '' ?></span>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															<?php
																$i++;
															}
														}
														$j = $i;
														for ($i = $j; $i <= $max_exp; $i++) {
															?>
															<div class="accordion accordion-multi-colored panel box box-danger">
																<div class="card box-header with-border">
																	<h4 class="card-header box-title" style="padding:1rem">
																		<a data-toggle="collapse" data-parent="#accordion" href="#expcollapse<?= $i ?>" aria-expanded="false" class="collapsed"> Work Experience #<?= $i ?></a>
																	</h4>
																</div>
																<div id="expcollapse<?= $i ?>" class="panel-collapse collapse" aria-expanded="false" style="">
																	<div class="box-body">
																		<div class="row">
																			<div class="form-group col-md-4 <?= (isset($errors['exp_jobtitle1'])) ? 'has-error' : '' ?>">
																				<label for="exp_jobtitle<?= $i ?>">Job Title</label>
																				<input type="text" class="form-control" placeholder="Job title" name="exp_jobtitle<?= $i ?>" value="<?= isset($_POST['exp_jobtitle' . $i]) ? $_POST['exp_jobtitle' . $i] : '' ?>">
																				<span class="help-block"><?= (isset($errors['exp_jobtitle1'])) ? $errors['exp_jobtitle1'] : '' ?></span>
																			</div>
																			<div class="form-group col-md-4  <?= (isset($errors['exp_companyname' . $i])) ? 'has-error' : '' ?>">
																				<label for="exp_companyname<?= $i ?>">Company Name</label>
																				<input type="text" class="form-control" placeholder="Company Name" name="exp_companyname<?= $i ?>" value="<?= isset($_POST['exp_companyname' . $i]) ? $_POST['exp_companyname' . $i] : '' ?>">
																				<span class="help-block"><?= (isset($errors['exp_companyname' . $i])) ? $errors['exp_companyname' . $i] : '' ?></span>
																			</div>
																			<div class="form-group col-md-4  <?= (isset($errors['exp_startdate' . $i])) ? 'has-error' : '' ?>">
																				<label for="exp_startdate<?= $i ?>">Start Date</label>
																				<input type="date" class="form-control" placeholder="dd-mm-yyy" name="exp_startdate<?= $i ?>" id="datefrom" value="<?= isset($_POST['exp_startdate' . $i]) ? $_POST['exp_startdate' . $i] : '' ?>">
																				<span class="help-block"><?= (isset($errors['exp_startdate' . $i])) ? $errors['exp_startdate' . $i] : '' ?></span>
																			</div>
																			<div class="form-group col-md-4  <?= (isset($errors['exp_enddate' . $i])) ? 'has-error' : '' ?>">
																				<label for="exp_enddate<?= $i ?>">End Date</label>
																				<input type="date" class="form-control" placeholder="dd-mm-yyy" id="dateto" name="exp_enddate<?= $i ?>" value="<?= isset($_POST['exp_enddate' . $i]) ? $_POST['exp_enddate' . $i] : '' ?>">
																				<span class="help-block"><?= (isset($errors['exp_enddate' . $i])) ? $errors['exp_enddate' . $i] : '' ?></span>
																			</div>
																			<div class="form-group col-md-4  <?= (isset($errors['exp_ontherinfo' . $i])) ? 'has-error' : '' ?>">
																				<label>Other Information</label>
																				<textarea class="form-control" rows="3" placeholder="Other information" name="exp_ontherinfo<?= $i ?>"><?= isset($_POST['exp_ontherinfo' . $i]) ? $_POST['exp_ontherinfo' . $i] : '' ?></textarea>
																				<span class="help-block"><?= (isset($errors['exp_ontherinfo' . $i])) ? $errors['exp_ontherinfo' . $i] : '' ?></span>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														<?php
														}
														?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>