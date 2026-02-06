<?php
$stud_course_detail_id = isset($_GET['id']) ? $_GET['id'] : '';
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
	$result = $student->update_student_admission();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : array();
	if (!empty($errors)) {
		foreach ($errors as $key => $value) {
			$message .= "<br>" . $value;
		}
	}
	//print_r($result);
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=list-admissions');
	}
}
$sql = "SELECT *,get_student_name(STUDENT_ID) AS STUDENT_NAME FROM student_course_details WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id' AND DELETE_FLAG=0";
$res = $db->execQuery($sql);
if ($res && $res->num_rows > 0) {
	while ($data = $res->fetch_assoc()) {
		$STUD_COURSE_DETAIL_ID 		= $data['STUD_COURSE_DETAIL_ID'];
		$INSTITUTE_COURSE_ID 		= $data['INSTITUTE_COURSE_ID'];
		$INSTITUTE_ID 		= $data['INSTITUTE_ID'];
		$STUDENT_ID 		= $data['STUDENT_ID'];
		$STUDENT_NAME 		= $data['STUDENT_NAME'];
		$DISCOUNT_RATE 		= $data['DISCOUNT_RATE'];
		$DISCOUNT_AMOUNT 	= $data['DISCOUNT_AMOUNT'];
		$COURSE_FEES 	= $data['COURSE_FEES'];
		$TOTAL_COURSE_FEES 	= $data['TOTAL_COURSE_FEES'];
		$FEES_RECIEVED 		= $data['FEES_RECIEVED'];
		$FEES_BALANCE 		= $data['FEES_BALANCE'];
		$DOWN_PAYMENT_ID 		= $data['PAYMENT_ID'];
		$REMARKS 			= $data['REMARKS'];
	}
}
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Update Admission

		</h1>
		<ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="page.php?page=list-admissions">Admissions</a></li>
			<li class="active">Update Admission</li>
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

			<div class="col-md-12">
				<!-- general form elements -->
				<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title pull-left">Update Admission</h3>
							<div class="pull-right">
								<a href="page.php?page=list-admissions" class="btn btn-warning" title="Cancel">Cancel</a>
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

											<li class="active"><a href="#tab_3" data-toggle="tab">Payment</a></li>

										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="tab_3">
												<div class="col-sm-12">
													<input type="hidden" name="stud_course_detail_id" value="<?= $STUD_COURSE_DETAIL_ID ?>" />
													<input type="hidden" name="payment_id" value="<?= $DOWN_PAYMENT_ID ?>" />
													<a href="javascript:void(0)" onclick="$('#payment-history').toggle()">Payment History</a><br>
													<table class="table table-bordered table-hover" id="payment-history" style="display:none;">
														<thead>
															<tr>
																<th width="4%">#</th>
																<th width="15%">Reciept No</th>
																<th width="15%">Date</th>
																<th>Course Name</th>
																<th width="15%">Fees Paid</th>
																<!-- <th>Fees Balance</th> -->
																<!--
					<th>Action</th>
					-->
															</tr>
														</thead>
														<tbody>
															<?php
															include_once('include/classes/institute.class.php');
															$institute = new institute();

															$payments = $institute->list_student_payments_upd(
																'',
																$STUDENT_ID,
																$INSTITUTE_ID,
																'',
																" AND A.STUD_COURSE_DETAIL_ID='$STUD_COURSE_DETAIL_ID' "
															);
															if ($payments != '') {
																$courseSrNo = 1;
																while ($courseData = $payments->fetch_assoc()) {
																	extract($courseData);

																	$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
																	$recievedby = '';
																	if ($STAFF_ID != 0)
																		$recievedby = $INSTITUTE_STAFF_NAME;
																	else
																		$recievedby = $INSTITUTE_NAME;


																	if ($ACTIVE == 1)
																		$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentCourseStatus(' . $PAYMENT_ID . ',0)"><i class="fa fa-check"></i></a>';
																	elseif ($ACTIVE == 0)
																		$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentCourseStatus(' . $PAYMENT_ID . ',1)"><i class="fa fa-times"></i></a>';

																	$action = "<a href='page.php?page=update-student-payment&payid=$PAYMENT_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>
							<a href='javascript:void(0)' onclick='deleteStudentPayment($PAYMENT_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>
							<a href='javascript:void(0)' onclick='printPayReciept($PAYMENT_ID)' class='btn btn-link' title='Print Reciept'><i class=' fa fa-print'></i></a>
							";
																	$rowclass = ($PAYMENT_ID == $DOWN_PAYMENT_ID) ? 'class="warning"' : '';
																	echo $courseDetail = "<tr id='row-$PAYMENT_ID' $rowclass><td>$courseSrNo</td>
								<td>$RECIEPT_NO</td>	
								<td>$FEES_PAID_ON</td>										 	 
								<td>$COURSE_NAME</td>											 
								<td  align='right'>$FEES_PAID</td>											  	
								<!-- <td>$FEES_BALANCE</td> -->
								<!-- <td>$action</td> -->
								</tr>";
																	$courseSrNo++;
																}
															}

															?>
														</tbody>

													</table>
													<div class="col-sm-12">
														<div class="form-group col-sm-4">
															<label for="studname">Student Name</label>
															<input type="text" name="studname" class="form-control" id="studname" value="<?= $STUDENT_NAME ?>" placeholder="Student Name" readonly disabled>
															<span class="help-block"><?= (isset($errors['studname'])) ? $errors['studname'] : '' ?></span>
														</div>
														<div class="form-group col-sm-4">
															<label for="total_paid1">Total Paid </label>
															<input type="text" name="total_paid1" class="form-control" id="total_paid1" value="<?= isset($TOTAL_FEES_PAID) ? $TOTAL_FEES_PAID : 0 ?>" placeholder="Total Fees Paid" readonly disabled>
															<span class="help-block"><?= (isset($errors['total_paid1'])) ? $errors['total_paid1'] : '' ?></span>
														</div>
														<!--<div class="form-group col-sm-4">
				  <label for="mobile">Total Balance</label>
				  <input type="text" name="studname" class="form-control"  id="studname" value="<?= $FEES_BALANCE ?>" placeholder="Total Fees Paid" readonly disabled >
				   <span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
				</div> -->
													</div>

													<table class="table table-bordered">
														<thead>
															<tr>
																<!-- <th>#</th> -->
																<th>Course</th>
																<th>Course Fees</th>
																<th>Discount Rate</th>
																<th>Discount Amount</th>
																<th>Total Fees</th>
																<th>Fees Recieved</th>
																<th>Balance</th>
																<th>Remarks</th>
																<th></th>
															</tr>
														</thead>
														<tbody id="courses-rows">
															<?php



															$INSTRESTED_COURSE 	= isset($_POST['course']) ? $_POST['course'] : array($INSTITUTE_COURSE_ID);
															$countcourses 		= count($INSTRESTED_COURSE);

															if ($countcourses > 0) {
																$totalcoursefees = 0;
																for ($i = 0; $i < $countcourses; $i++) {
																	$class = isset($errors['discamt']) ? 'class="danger"' : '';
																	$class = isset($errors['amtrecieved']) ? 'class="danger"' : '';
																	$class = isset($errors['amtbalance']) ? 'class="danger"' : '';

															?>
																	<tr id="courserow-<?= $i ?>" <?= $class ?>>

																		<td>
																			<select class="form-control" name="course[]" id="course<?= $i ?>" onchange="getInstCourseFeesUpd(this.value, this.id)">
																				<?php
																				$sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.COURSE_TYPE FROM institute_courses A WHERE A.INSTITUTE_ID='$institute_id' AND A.DELETE_FLAG=0";
																				$ex = $db->execQuery($sql);
																				if ($ex && $ex->num_rows > 0) {
																					while ($data = $ex->fetch_assoc()) {
																						$INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
																						$COURSE_ID 			 = $data['COURSE_ID'];
																						$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
																						$COURSE_TYPE		 = $data['COURSE_TYPE'];

																						if ($COURSE_ID != '') {
																							$course = $db->get_course_detail($COURSE_ID, $COURSE_TYPE);
																							$course_name 		 = $course['COURSE_NAME_MODIFY'];
																						}

																						if ($MULTI_SUB_COURSE_ID != '') {
																							$course = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID, $COURSE_TYPE);
																							$course_name 		 = $course['COURSE_NAME_MODIFY'];
																						}

																						$selected = ($INSTRESTED_COURSE[$i] == $INSTITUTE_COURSE_ID) ? 'selected="selected"' : '';

																						echo '<option value="' . $INSTITUTE_COURSE_ID . '" ' . $selected . '>' . $course_name . '</option>';
																					}
																				}
																				?>
																			</select>
																		</td>

																		<td>
																			<?php
																			$coursefees = isset($_POST['coursefees']) ? $_POST['coursefees'] : array($COURSE_FEES);
																			$coursefeessel = isset($coursefees[$i]) ? $coursefees[$i] : '';

																			$ins_course_info = $db->get_inst_course_exam_fees($INSTRESTED_COURSE[$i]);
																			$course_fee = 0;
																			if ($ins_course_info != '') {
																				$course_fee = $ins_course_info['COURSE_FEES'];
																				$totalcoursefees += $course_fee;
																			}
																			?>
																			<input type="text" class="form-control" name="coursefees[]" id="coursefees<?= $i ?>" value="<?= $course_fee ?>" readonly />
																		</td>
																		<td>
																			<?php
																			$discrate = isset($_POST['discrate']) ? $_POST['discrate'] : array($DISCOUNT_RATE);
																			$discratesel = isset($discrate[$i]) ? $discrate[$i] : '';
																			?>
																			<select class="form-control" name="discrate[]" id="discrate<?= $i ?>" onchange="calDiscountedAmt(<?= $i ?>)">
																				<option value="amtminus" <?= ($discratesel == 'amtminus') ? 'selected="selected"' : '' ?>>Amount - </option>
																				<option value="amtplus" <?= ($discratesel == 'amtplus') ? 'selected="selected"' : '' ?>>Amount + </option>
																				<option value="perminus" <?= ($discratesel == 'perminus') ? 'selected="selected"' : '' ?>>Percent - </option>
																				<option value="perplus" <?= ($discratesel == 'perplus') ? 'selected="selected"' : '' ?>>Percent + </option>
																			</select>
																		</td>

																		<td>
																			<?php

																			$discamt 	= isset($_POST['discamt']) ? $_POST['discamt'] : array($DISCOUNT_AMOUNT);
																			$discamtsel = isset($discamt[$i]) ? $discamt[$i] : 0;
																			?>
																			<input type="text" class="form-control" name="discamt[]" id="discamt<?= $i ?>" onchange="calDiscountedAmtUpd(<?= $i ?>)" onkeyup="calDiscountedAmtUpd(<?= $i ?>)" value="<?= $discamtsel ?>" />
																		</td>
																		<td>
																			<?php
																			$totalcoursefee 	= isset($_POST['totalcoursefee']) ? $_POST['totalcoursefee'] : array($TOTAL_COURSE_FEES);
																			$totalcoursefeesel = isset($totalcoursefee[$i]) ? $totalcoursefee[$i] : 0;
																			//if($discamtsel==0)
																			//	$totalcoursefeesel = $course_fee;
																			?>
																			<input type="text" class="form-control" name="totalcoursefee[]" id="totalcoursefee<?= $i ?>" readonly value="<?= $totalcoursefeesel ?>" />
																		</td>
																		<td>
																			<?php
																			$amtrecieved 	= isset($_POST['amtrecieved']) ? $_POST['amtrecieved'] : array($FEES_RECIEVED);
																			$amtrecievedsel = isset($amtrecieved[$i]) ? $amtrecieved[$i] : 0;
																			?>
																			<input type="text" class="form-control" name="amtrecieved[]" id="amtrecieved<?= $i ?>" onchange="calTotalPerCourseUpd(<?= $i ?>)" onkeyup="calTotalPerCourseUpd(<?= $i ?>)" value="<?= $amtrecievedsel ?>" />
																			<input type="hidden" value="<?php echo $TOTAL_FEES_PAID - $amtrecievedsel ?>" id="total_paid" />
																		</td>
																		<td>
																			<?php
																			$amtbalance 	= isset($_POST['amtbalance']) ? $_POST['amtbalance'] : array($FEES_BALANCE);
																			$amtbalancesel = isset($amtbalance[$i]) ? $amtbalance[$i] : 0;
																			//if($discamtsel==0)
																			//	$amtbalancesel = $course_fee;
																			?>
																			<input type="text" class="form-control" name="amtbalance[]" id="amtbalance<?= $i ?>" readonly value="<?= $amtbalancesel ?>" />
																		</td>
																		<td>
																			<?php
																			$payremarks = isset($_POST['payremarks']) ? $_POST['payremarks'] : array($REMARKS);;
																			$payremarksl = isset($payremarks[$i]) ? $payremarks[$i] : '';
																			?>
																			<textarea class="form-control" name="payremarks[]" id="payremarks<?= $i ?>"><?= $payremarksl ?></textarea>
																		</td>

																		<!--<td><a href="javascript:void(0)" onclick="deleteCourseRow(<?= $i ?>)" class="btn btn-xs btn-danger"><i class="fa fa-minus-circle"></i></a></td> -->
																	</tr>
															<?php
																}
															}
															?>
														</tbody>
														<tfoot>
															<tr>
																<!--  <td>Total</td>
										<td><input class="form-control" readonly type="text" value="<?= floatval($totalcoursefees) ?>" name="total" /></td> -->
																<td colspan="8">
																	<input type="hidden" name="countcourses" id="countcourses" value="<?= $countcourses ?>" value="<?= isset($_POST['countcourses']) ? $_POST['countcourses'] : 0 ?>" />
																</td>
																<!-- <td><a href="javascript:void(0)" class="btn btn-primary" onclick="addCourseRow()"><i class="fa fa-plus-circle"></i></a></td> -->
															</tr>
														</tfoot>
													</table>

													<input type="checkbox" checked="checked" name="recievedpayment" value="1" /> I have recieved the payment.
												</div>
												<!--						
						<div class="col-sm-1"></div>
						<div class="col-sm-10">
						<div class="form-group  <?= (isset($errors['inst_course_id'])) ? 'has-error' : '' ?>">
						  <label for="inst_course_id">Select Course</label>
						  <?php $sel_course = isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : ''; ?>
						  <select class="form-control select2" name="inst_course_id" id="course" onchange="setInstCourseInfo(<?= $institute_id ?>,this.value)" style="width:100%">
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
						-->
												<div class="clearfix"></div>

											</div>
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