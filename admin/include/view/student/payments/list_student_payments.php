<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Fees Details </h4>
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
								<th>#</th>
								<th>Reciept No</th>
								<th>Date</th>
								<th>Course Name</th>
								<th>Total Course Fees</th>
								<th>Fees Paid</th>
								<th>Fees Balance</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$student_id 	= $db->test(isset($_GET['id']) ? $_GET['id'] : '');
							$stud_course_id = $db->test(isset($_GET['studCourse']) ? $_GET['studCourse'] : '');

							include_once('include/classes/institute.class.php');
							$institute = new institute();
							$cond = '';
							$cond = " AND A.STUD_COURSE_DETAIL_ID = $stud_course_id";
							$payments = $institute->list_student_payments_upd_history('', $student_id, '', '', $cond);
							if ($payments != '') {
								$courseSrNo = 1;
								while ($courseData = $payments->fetch_assoc()) {
									extract($courseData);
									$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);

									if ($ACTIVE == 1)
										$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentCourseStatus(' . $PAYMENT_ID . ',0)"><i class="fa fa-check"></i></a>';
									elseif ($ACTIVE == 0)
										$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentCourseStatus(' . $PAYMENT_ID . ',1)"><i class="fa fa-times"></i></a>';
									$action = '';
									// if($db->permission('update_payment'))	
									// $action = "<a href='page.php?page=studentUpdateFees&payid=$PAYMENT_ID' class='btn btn-primary table-btn' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";

									// if($db->permission('delete_payment'))	
									// $action .= "<a href='javascript:void(0)' onclick='deleteStudentPayment($PAYMENT_ID)' class='btn btn-danger table-btn' title='Delete'><i class='mdi mdi-delete'></i></a>";

									if ($db->permission('print_payment_reciept'))
										// $action .= "<a href='javascript:void(0)' onclick='printPayReciept($PAYMENT_ID)' class='btn btn-warning table-btn' title='Print Reciept'><i class='mdi mdi-file-pdf' ></i></a>";

										$display_status = $db->get_student_displayform_status($student_id);

									if ($display_status['DISPLAY_FORM_STATUS'] == '1') {
										$action .= "<a href='page.php?page=viewStudentReceipt&payid=$PAYMENT_ID' target='_blank' class='btn btn-primary table-btn' title='Print Reciept'><i class='mdi mdi-file-pdf'></i></a>";
									}



									echo $courseDetail = "<tr id='row-$PAYMENT_ID'><td>$courseSrNo</td>
												<td>$RECIEPT_NO</td>	
												<td>$FEES_PAID_ON</td>													
												<td>$COURSE_NAME</td>	
												<td>$TOTAL_COURSE_FEES</td>
												<td>$FEES_PAID</td>											  	
												<td>$FEES_BALANCE</td>
												<td>$action</td>
												</tr>";
									$courseSrNo++;
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