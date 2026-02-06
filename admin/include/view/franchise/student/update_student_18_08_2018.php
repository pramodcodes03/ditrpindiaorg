<?php
//include('include/controller/institute/staff/add_staff.php');
?>
<?php
$student_id = $db->test(isset($_GET['id']) ? $_GET['id'] : '');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 5) {
	$institute_id = $db->get_parent_id($user_role, $user_id);
	$staff_id = $user_id;
} else {
	$institute_id = $user_id;
	$staff_id = 0;
}


$action		= isset($_POST['action']) ? $_POST['action'] : '';

include_once('include/classes/student.class.php');

$student = new student();
if ($action != '') {
	//print_r($_POST);
	$result = $student->update_student();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		//	header('location:page.php?page=list-students');
	}
}
$res = $student->list_student($student_id, $institute_id, $staff_id);
if ($res != '') {
	while ($resdata = $res->fetch_assoc()) {
		extract($resdata);
	}
}
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Update Student

		</h1>
		<ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="page.php?page=list-students">Students</a></li>
			<li class="active">Update Student</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
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
		<div class="row">
			<!-- left column -->

			<div class="col-md-1"></div>
			<div class="col-md-10">
				<!-- general form elements -->
				<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title pull-left">Update Student</h3>
							<div class="pull-right">
								<a href="page.php?page=list-students" class="btn btn-warning" title="Cancel">Cancel</a>
								&nbsp;&nbsp;&nbsp;
								<input type="submit" class="btn btn-primary" name="action" value="Update" />
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<!-- Custom Tabs -->
									<div class="nav-tabs-custom">
										<ul class="nav nav-tabs">
											<li class="active"><a href="#tab_1" data-toggle="tab"> Step 1 <br> Basic Details</a></li>
											<li><a href="#tab_2" data-toggle="tab">Step 2 <br> Photos / Documents</a></li>

										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="tab_1">
												<input type="hidden" name="student_id" value="<?= $STUDENT_ID ?>">
												<input type="hidden" name="enquiry_id" value="<?= $ENQUIRY_ID ?>">
												<input type="hidden" name="institute_id" value="<?= $INSTITUTE_ID ?>">
												<input type="hidden" name="staff_id" value="<?= $STAFF_ID ?>">
												<input type="hidden" name="studcode" value="<?= isset($_POST['studcode']) ? $_POST['studcode'] : $student->generate_student_code() ?>">

												<div class="col-sm-12">
													<div class="form-group col-sm-2">
														<label for="abbreviation">Abbreviation</label>
														<?php $ABBREVIATION = isset($_POST['abbreviation']) ? $_POST['abbreviation'] : $ABBREVIATION;	?>
														<select class="form-control" name="abbreviation">
															<option class="form-control" value="Mr" <?php echo ($ABBREVIATION == 'MR') ? 'selected="selected"' : '' ?>>Mr.</option>
															<option class="form-control" value="Miss" <?php echo ($ABBREVIATION == 'MISS') ? 'selected="selected"' : '' ?>>Miss </option>
															<option class="form-control" value="Mrs" <?php echo ($ABBREVIATION == 'MRS') ? 'selected="selected"' : '' ?>>Mrs</option>
															<option class="form-control" value="Ms" <?php echo ($ABBREVIATION == 'MS') ? 'selected="selected"' : '' ?>>Ms</option>
														</select>
													</div>
													<div class="col-sm-2">
														<div class="form-group <?= (isset($errors['fname'])) ? 'has-error' : '' ?>">
															<label for="fname">Student Name</label>
															<input type="text" name="fname" class="form-control" value="<?= isset($_POST['fname']) ? $_POST['fname'] : $STUDENT_FNAME ?>" id="fname" placeholder="Enter Student Name">
															<span class="help-block"><?= isset($errors['fname']) ? $errors['fname'] : '' ?></span>
														</div>
													</div>
													<div class="col-sm-3">
														<div class="form-group <?= (isset($errors['mname'])) ? 'has-error' : '' ?>">
															<label for="mname">Father Name / Husband Name</label>
															<input type="text" name="mname" class="form-control" value="<?= isset($_POST['mname']) ? $_POST['mname'] : $STUDENT_MNAME ?>" id="mname" placeholder="Father Name / Husband Name">
															<span class="help-block"><?= isset($errors['mname']) ? $errors['mname'] : '' ?></span>
														</div>
													</div>
													<div class="col-sm-3">
														<div class="form-group <?= (isset($errors['lname'])) ? 'has-error' : '' ?>">
															<label for="lname">Surname Name</label>
															<input type="text" name="lname" class="form-control" value="<?= isset($_POST['lname']) ? $_POST['lname'] : $STUDENT_LNAME ?>" id="lname" placeholder="Enter Surname Name">
															<span class="help-block"><?= isset($errors['lname']) ? $errors['lname'] : '' ?></span>
														</div>
													</div>
													<div class="form-group col-sm-2 <?= (isset($errors['mothername'])) ? 'has-error' : '' ?>">
														<label for="mothername">Mother Name</label>
														<input type="text" name="mothername" class="form-control" value="<?= isset($_POST['mothername']) ? $_POST['mothername'] : $STUDENT_MOTHERNAME ?>" id="mothername" placeholder="Mother Name ">
														<span class="help-block"><?= isset($errors['mothername']) ? $errors['mothername'] : '' ?></span>
													</div>
													<div class="clearfix"></div>
												</div>

												<div class="col-sm-6">
													<div class="form-group <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
														<label for="mobile">Student Mobile</label>
														<input type="text" name="mobile" class="form-control" pattern="\d*" id="mobile" maxlength="10" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $STUDENT_MOBILE ?>" placeholder="Student Mobile">
														<span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group <?= (isset($errors['mobile2'])) ? 'has-error' : '' ?>">
														<label for="mobile2">Alternate Mobile</label>
														<input type="text" name="mobile2" class="form-control" pattern="\d*" id="mobile2" maxlength="10" value="<?= isset($_POST['mobile2']) ? $_POST['mobile2'] : $STUDENT_MOBILE2 ?>" placeholder="Alternate Mobile">
														<span class="help-block"><?= (isset($errors['mobile2'])) ? $errors['mobile2'] : '' ?></span>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
														<label for="email">Email</label>
														<input type="email" name="email" class="form-control" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : $STUDENT_EMAIL ?>" placeholder="Email" onkeyup="document.getElementById('uname').value = this.value;" onchange="document.getElementById('uname').value = this.value;">
														<span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group <?= (isset($errors['dob'])) ? 'has-error' : '' ?>">
														<label>Date Of Birth:</label>
														<div class="input-group date">
															<div class="input-group-addon">
																<i class="fa fa-calendar"></i>
															</div>
															<input class="form-control pull-right" name="dob" value="<?= isset($_POST['dob']) ? $_POST['dob'] : $STUD_DOB_FORMATED ?>" id="dob" type="text">
														</div>
														<span class="help-block"><?= (isset($errors['dob'])) ? $errors['dob'] : '' ?></span>
														<!-- /.input group -->
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group <?= (isset($errors['gender'])) ? 'has-error' : '' ?>">
														<label>Gender</label>
														<?php $gender = isset($_POST['gender']) ? $_POST['gender'] : $STUDENT_GENDER; ?>
														<select class="form-control" name="gender" id="gender">
															<option <?= ($gender == '') ? 'selected="selected"' : '' ?> value="">--select--</option>
															<option value="male" <?= ($gender == 'male') ? 'selected="selected"' : '' ?>>Male</option>
															<option value="female" <?= ($gender == 'female') ? 'selected="selected"' : '' ?>>Female</option>
														</select>
														<span class="help-block"><?= (isset($errors['gender'])) ? $errors['gender'] : '' ?></span>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group <?= (isset($errors['adharid'])) ? 'has-error' : '' ?>">
														<label for="adharid">Adhar Card Number</label>
														<input type="text" class="form-control" id="adharid" placeholder="Adhar Card number" value="<?= isset($_POST['adharid']) ? $_POST['adharid'] : $STUDENT_ADHAR_NUMBER ?>" name="adharid">
														<span class="help-block"><?= (isset($errors['adharid'])) ? $errors['adharid'] : '' ?></span>
													</div>
												</div>
												<div class="form-group col-sm-6 <?= (isset($errors['qualification'])) ? 'has-error' : '' ?>">
													<label for="qualification">Educational Qualification</label>
													<input type="text" class="form-control" id="qualification" placeholder="Educational Qualification" name="qualification" value="<?= isset($_POST['qualification']) ? $_POST['qualification'] : $EDUCATIONAL_QUALIFICATION ?>">
													<span class="help-block"><?= (isset($errors['qualification'])) ? $errors['qualification'] : '' ?></span>
												</div>
												<div class="form-group col-sm-6 <?= (isset($errors['occupation'])) ? 'has-error' : '' ?>">
													<label for="occupation">Occupation</label>
													<input type="text" class="form-control" id="occupation" placeholder="Occupation" value="<?= isset($_POST['occupation']) ? $_POST['occupation'] : $OCCUPATION ?>" name="occupation">
													<span class="help-block"><?= (isset($errors['occupation'])) ? $errors['occupation'] : '' ?></span>
												</div>
												<div class="col-sm-6">
													<div class="form-group <?= (isset($errors['state'])) ? 'has-error' : '' ?>">
														<label>State</label>
														<?php
														$STUDENT_STATE = isset($_POST['state']) ? $_POST['state'] : $STUDENT_STATE;
														?>
														<select class="form-control" name="state" id="state" style="width: 100%;" onchange="getCitiesByState(this.value)">
															<?php echo $db->MenuItemsDropdown('states_master', "STATE_ID", "STATE_NAME", "STATE_ID, STATE_NAME", $STUDENT_STATE, " ORDER BY STATE_NAME"); ?>
														</select>
														<span class="help-block"><?= (isset($errors['state'])) ? $errors['state'] : '' ?></span>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
														<label>City</label>
														<?php $STUDENT_CITY = isset($_POST['city']) ? $_POST['city'] : $STUDENT_CITY;	?>
														<select class="form-control" name="city" id="city" style="width: 100%;">
															<?php echo $db->MenuItemsDropdown('city_master', "CITY_ID", "CITY_NAME", "CITY_ID, CITY_NAME", $STUDENT_CITY, " ORDER BY CITY_NAME"); ?>
														</select>
														<span class="help-block"><?= (isset($errors['city'])) ? $errors['city'] : '' ?></span>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group <?= (isset($errors['pincode'])) ? 'has-error' : '' ?>">
														<label for="pincode">Pincode</label>
														<input type="text" class="form-control" placeholder="Pincode" maxlength="6" name="pincode" value="<?= isset($_POST['pincode']) ? $_POST['pincode'] : $STUDENT_PINCODE ?>">
														<span class="help-block"><?= (isset($errors['pincode'])) ? $errors['pincode'] : '' ?></span>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group <?= (isset($errors['per_add'])) ? 'has-error' : '' ?>">
														<label>Permanent Address</label>
														<textarea class="form-control" rows="3" placeholder="Permanent Address ..." name="per_add"><?= isset($_POST['per_add']) ? $_POST['per_add'] : $STUDENT_PER_ADD ?></textarea>
														<span class="help-block"><?= (isset($errors['per_add'])) ? $errors['per_add'] : '' ?></span>
													</div>
												</div>
												<div class="clearfix"></div>
											</div>
											<!-- /.tab-pane -->
											<div class="tab-pane" id="tab_2">
												<div class="col-sm-12">
													<div class="form-group col-sm-6 <?= (isset($errors['stud_photo'])) ? 'has-error' : '' ?>">
														<label for="photo">Photo </label> <span> (Max file size 250 KB)</span>
														<input type="file" name="stud_photo" id="staff_photo">
														<p class="help-block"><?= (isset($errors['stud_photo'])) ? $errors['stud_photo'] : '' ?></p>
													</div>
													<?php
													$photo_path  = 'resources/dist/img/default_user.png';
													$photo_path_thumb  = 'resources/dist/img/default_user.png';
													$disp_photo = '<img src="resources/dist/img/default_user.png" class="img img-responsive thumbnail" style="height:100px;" />';

													$photo = $student->get_student_docs($STUDENT_ID, ' AND FILE_LABEL="' . STUD_PHOTO . '"');
													if (!empty($photo)) {
														foreach ($photo as $fileId => $photoinfo)
															extract($photoinfo);
														$photo_path  = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $FILE_NAME;
														$photo_path_thumb  = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/thumb/' . $FILE_NAME;
														$disp_photo = '<div id="img-' . $FILE_ID . '">';
														$disp_photo .= '<a href="javascript:void(0)" onclick="deleteStudFile(' . $FILE_ID . ')"><i class="fa fa-trash"></i></a>';
														$disp_photo .= '<a href="' . $photo_path . '" target="_blank"><img src="' . $photo_path_thumb . '" class="img img-responsive thumbnail" style="height:100px;" /></a>	';
														$disp_photo .= '</div>';
													}
													?>
													<div class="form-group col-sm-6 ">
														<?= $disp_photo ?>
													</div>
												</div>

												<div class="col-sm-12">
													<div class="form-group <?= (isset($errors['stud_photo_id'])) ? 'has-error' : '' ?>">
														<table class="table table-bordered">
															<tr>
																<th>Photo ID Type</th>
																<th>Photo ID Number</th>
																<th>Photo ID Proof <span style="font-weight:500;"> (Max file size 500 KB)</span></th>
															<tr>
															<tr>
																<td>
																	<?php
																	$photoid = $student->get_student_docs($STUDENT_ID, " AND FILE_LABEL='" . STUD_PHOTO_ID . "'");
																	$FILE_CATEGORY = '';
																	$FILE_DESC = '';
																	$disp_photo_id = '';
																	if (!empty($photoid)) {
																		foreach ($photoid as $fileId => $photoidinfo)
																			extract($photoidinfo);
																		$photo_path  = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $FILE_NAME;
																		$photo_path_thumb  = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/thumb/' . $FILE_NAME;
																		$disp_photo_id = '<div id="img-' . $FILE_ID . '">';
																		$disp_photo_id .= '<a href="javascript:void(0)" onclick="deleteStudFile(' . $FILE_ID . ')"><i class="fa fa-trash"></i></a>';
																		$disp_photo_id .= '<a href="' . $photo_path . '" target="_blank"><img src="' . $photo_path_thumb . '" class="img img-responsive thumbnail" style="height:100px;" /></a>	';
																		$disp_photo_id .= '</div>';
																	}
																	$photo_id_category = isset($_POST['photo_id_category']) ? $_POST['photo_id_category'] : $FILE_CATEGORY;

																	?>
																	<select class="form-control" name="photo_id_category" onchange="if(this.value=='Other') document.getElementById('photo_other').style.visibility='visible'; else document.getElementById('photo_other').style.visibility='hidden'; ">
																		<?php
																		echo $db->MenuItemsDropdown('documents_master', 'DOCUMENT_NAME', 'DOCUMENT_NAME', 'DOCUMENT_ID,DOCUMENT_NAME', $photo_id_category, ' WHERE ACTIVE=1 AND DELETE_FLAG=0 ');
																		?>

																	</select>
																	<input type="text" class="form-control" name="photo_id_category_other" id="photo_other" style="visibility:<?= ($photo_id_category == 'Other') ? 'visible' : 'hidden' ?>; margin-top:5px;" value="<?= $photo_other = isset($_POST['photo_other']) ? $_POST['photo_other'] : ''; ?>" />
																</td>
																<td><input class="form-control" type="text" name="stud_photo_id_desc" id="stud_photo_id_desc" value="<?= isset($_POST['stud_photo_id_desc']) ? $_POST['stud_photo_id_desc'] : $FILE_DESC ?>">
																	<input type="hidden" name="photo_id_desc_id" value="<?= $FILE_ID ?>" />
																</td>
																<td><input type="file" name="stud_photo_id" id="staff_photo_id">
																	<?= $disp_photo_id ?>
																</td>

															</tr>
														</table>
														<p class="help-block"><?= (isset($errors['stud_photo_id'])) ? $errors['stud_photo_id'] : '' ?></p>
													</div>
												</div>

												<div class="clearfix"></div>
											</div>
											<!-- /.tab-pane -->
											<!--
				  <div class="tab-pane" id="tab_3">							
						<div class="col-sm-1"></div>
						<div class="col-sm-10">
						<div class="form-group  <?= (isset($errors['inst_course_id'])) ? 'has-error' : '' ?>">
						  <label for="inst_course_id">Select Course</label>
						  <?php $sel_course = isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : ''; ?>
						  <select class="form-control select2" name="inst_course_id" id="inst_course_id" onchange="setInstCourseInfo(<?= $institute_id ?>,this.value)" style="width:100%">
							  <?php
								include_once('include/classes/course.class.php');
								$course 	= new course();
								echo $output =  $course->get_inst_course_detail($institute_id, '', $sel_course, true);
								?>
							</select>	
							<span class="help-block"><?= isset($errors['inst_course_id']) ? $errors['inst_course_id'] : '' ?></span>
						</div>
					
						<div class="form-group  amtpaid <?= (isset($errors['amtpaid'])) ? 'has-error' : '' ?>">
						  <label for="amtpaid">Amount Paid </label>
						  <input type="text" class="form-control" id="amtpaid" placeholder="Amount paid" value="<?= isset($_POST['amtpaid']) ? $_POST['amtpaid'] : '' ?>" name="amtpaid" onkeyup="calBalAmt(<?= $institute_id ?>, this.value)" onchange="calBalAmt(<?= $institute_id ?>, this.value)" maxlength="10">
						  <span class="help-block"><?= (isset($errors['amtpaid'])) ? $errors['amtpaid'] : '' ?></span>
						</div>
						
						<div class="form-group paymentnote <?= (isset($errors['paymentnote'])) ? 'has-error' : '' ?>">
						  <label for="paymentnote">Payment Description( if any) </label>
						  <textarea class="form-control" id="paymentnote" placeholder="payement description"><?= isset($_POST['paymentnote']) ? $_POST['paymentnote'] : '' ?></textarea>
						  <span class="help-block"><?= (isset($errors['paymentnote'])) ? $errors['paymentnote'] : '' ?></span>
						</div>
						<input type="hidden" name="disp_course_fees" id="disp_course_fees" value="<?= isset($_POST['disp_course_fees']) ? $_POST['disp_course_fees'] : 0 ?>" />
						<input type="hidden" name="disp_course_name" id="disp_course_name" value="<?= isset($_POST['disp_course_name']) ? $_POST['disp_course_name'] : '' ?>" />
						<input type="hidden" name="disp_course_type" id="disp_course_type" value="<?= isset($_POST['disp_course_type']) ? $_POST['disp_course_type'] : '' ?>" />
						<input type="hidden" name="disp_amtbalance" id="disp_amtbalance" value="<?= isset($_POST['disp_amtbalance']) ? $_POST['disp_amtbalance'] : 0 ?>" />
						<div class="col-sm-12" id="payment-details">
							<table class="table table-bordered">
								<tr>
									<th>Selected Course Name</th>
									<td><?= isset($_POST['disp_course_name']) ? $_POST['disp_course_name'] : 'Not selected' ?></td>
								</tr>
								<tr>
									<th>Total Course Fees</th>
									<td><?= isset($_POST['disp_course_fees']) ? $_POST['disp_course_fees'] : 0 ?></td>
								</tr>
								<tr>	
									<th>Amount Paid</th>
									<td><?= isset($_POST['amtpaid']) ? $_POST['amtpaid'] : 0 ?></td>
								</tr>
								<tr class="danger">	
									<th>Total Balance Fees</th>
									<td><?= isset($_POST['disp_amtbalance']) ? $_POST['disp_amtbalance'] : 0 ?></td>
								</tr>
							</table>
						</div>								
						</div>
						<div class="clearfix"></div>
						
				  </div>
				  -->
											<!-- /.tab-pane -->
										</div>
										<!-- /.tab-content -->
									</div>
									<!-- nav-tabs-custom -->
								</div>

							</div>
							<!-- /.box-body -->
						</div>

						<!-- /.box -->


						<!-- /.box -->

						<!-- /.box -->

						<!-- /.box -->

					</div>
				</form>
				<!--/.col (left) -->

				<!--/.col (right) -->
			</div>
			<!-- /.row -->
	</section>
	<!-- /.content -->
</div>