<?php
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

$student_id = $db->test(isset($_GET['id']) ? $_GET['id'] : '');
$action = isset($_POST['action']) ? $_POST['action'] : '';
include_once('include/classes/student.class.php');

include_once('include/classes/institute.class.php');
$institute = new institute();
$student = new student();
if ($action != '') {

	$result = $institute->add_student_payment();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=studentFees');
	}
}

?>
<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Student Payment Details</h4>
					<form class="forms-sample" action="" method="post" enctype="multipart/form-data">
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
							<input type="hidden" name="coursefees" id="coursefees" value="" />
							<div class="form-group col-md-6  <?= (isset($errors['student_id'])) ? 'has-error' : '' ?>">
								<label for="student_id">Student Name</label>
								<?php
								$student_id = isset($_POST['student_id']) ? $_POST['student_id'] : '';
								?>
								<select class="form-control select2" name="student_id" id="student_id" onchange="getStudentAllCourses(this.value); getStudPaymentInfo(); getBalAmtCourse();">
									<?php //echo $db->MenuItemsDropdown ('student_payments',"STUDENT_ID","STUD_NAME","DISTINCT STUDENT_ID, get_student_name(STUDENT_ID) AS STUD_NAME",$student_id," WHERE  INSTITUTE_ID='$institute_id' AND DELETE_FLAG=0");
									echo $db->MenuItemsDropdown('student_details', "STUDENT_ID", "STUDENT_NAME", "STUDENT_ID, CONCAT(CONCAT(STUDENT_FNAME,' ',STUDENT_MNAME),' ',STUDENT_LNAME) STUDENT_NAME", $student_id, " WHERE DELETE_FLAG=0");
									?>
								</select>
								<span class="help-block"><?= isset($errors['student_id']) ? $errors['student_id'] : '' ?></span>
							</div>

							<div class="form-group col-md-6 <?= (isset($errors['course'])) ? 'has-error' : '' ?>">
								<label for="course">Select Course</label>
								<?php $course = isset($_POST['course']) ? $_POST['course'] : ''; ?>
								<select class="form-control select2" name="course" id="course" onchange="getStudPaymentInfo(); getBalAmtCourse();">
									<?php
									if ($student_id != '') {
										$course = isset($_POST['course']) ? $_POST['course'] : '';
										$res = $student->get_student_allcourses($student_id);
										if ($res != '') {
											echo '<option value="">--select--</option>';
											while ($data = $res->fetch_assoc()) {
												$INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
												$output = $db->get_inst_course_info($INSTITUTE_COURSE_ID);
												if (!empty($output)) {
													print_r($output);
													$selected = ($course == $INSTITUTE_COURSE_ID) ? 'selected="selected"' : '';
													echo '<option value="' . $INSTITUTE_COURSE_ID . '" ' . $selected . '>' . $output['COURSE_NAME_MODIFY'] . '</option>';
												}
											}
										}
									}
									?>
								</select>
								<span class="help-block"><?= isset($errors['course']) ? $errors['course'] : '' ?></span>
							</div>
							<div class="form-group col-md-6 <?= (isset($errors['amountpaid'])) ? 'has-error' : '' ?>">
								<label for="amountpaid">Enter Payment Amount</label>
								<input type="text" name="amountpaid" id="amountpaid" class="form-control" value="<?= isset($_POST['amountpaid']) ? $_POST['amountpaid'] : 0; ?>" onkeyup="addPayShowBal()" onchange="addPayShowBal()" />
								<span class="help-block"><?= isset($errors['amountpaid']) ? $errors['amountpaid'] : '' ?></span>
							</div>
							<div class="form-group col-md-6 <?= (isset($errors['amountbalance'])) ? 'has-error' : '' ?>">
								<label for="amountpaid">Total Balance Amount</label>
								<input type="hidden" id="totalbal" value="" />
								<input type="text" name="amountbalance" id="amountbalance" class="form-control" value="<?= isset($_POST['amountbalance']) ? $_POST['amountbalance'] : ''; ?>" readonly />
								<span class="help-block"><?= isset($errors['amountbalance']) ? $errors['amountbalance'] : '' ?></span>
							</div>
							<div class="form-group col-md-6 <?= (isset($errors['paymentmode'])) ? 'has-error' : '' ?>">
								<label for="paymentmode">Payment Mode</label>
								<?php $paymentmode = isset($_POST['paymentmode']) ? $_POST['paymentmode'] : ''; ?>
								<select name="paymentmode" id="paymentmode" class="form-control">
									<?php echo $db->MenuItemsDropdown('payment_modes_master', "PAYMENT_MODE_ID", "PAYMENT_MODE", "PAYMENT_MODE_ID, PAYMENT_MODE", $paymentmode, ""); ?>
								</select>
								<span class="help-block"><?= isset($errors['paymentmode']) ? $errors['paymentmode'] : '' ?></span>
							</div>
							<div class="form-group col-md-6 <?= (isset($errors['paymentnote'])) ? 'has-error' : '' ?>">
								<label for="paymentnote">Any Notes:</label>
								<textarea name="paymentnote" id="paymentnote" class="form-control"><?= isset($_POST['paymentnote']) ? $_POST['paymentnote'] : ''; ?></textarea>
								<span class="help-block"><?= isset($errors['paymentnote']) ? $errors['paymentnote'] : '' ?></span>
							</div>
							<div class="col-md-12 box-footer">
								<input type="submit" class="btn btn-primary" name="action" value="Add Payment" /> &nbsp;&nbsp;&nbsp;
								<a href="page.php?page=studentFees" class="btn btn-warning" title="Cancel">Cancel</a>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 grid-margin stretch-card">
								<div class="card">
									<div class="row card-body">
										<h4 class="col-md-6 card-title">Payment History</h4>
										<h3 cass="col-md-6" style="float: right;position: absolute;right: 0;"><strong>Total Balance Amount</strong><input type="text" id="totalBAmount" value="" style="border: 0px;font-size: 25px;font-weight: 600;    display: block;text-align: center;padding: 8px 0px;" /></h3>
									</div>
									<div id="payment_info">


									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>