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

$student_id = $db->test(isset($_GET['studid']) ? $_GET['studid'] : '');
$course_id = $db->test(isset($_GET['courseId']) ? $_GET['courseId'] : '');

$cond = '';
$cond = " AND A.INSTITUTE_COURSE_ID  = $course_id";

?>
<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">View Students Payments
					<form action="export.php" method="post" class="">
						<input type="hidden" value="studentfees_export" name="action" />
						<button type="submit" name="export" value="Export" class="btn btn-danger btn3">Export</button>
					</form>
				</h4>

				<div class="table-responsive pt-3">
					<table id="order-listing" class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Student Name</th>
								<th>Course Name</th>
								<th>Total Course Fees</th>
								<th>Fees Paid</th>
								<th>Fees Balance</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							include_once('include/classes/institute.class.php');
							$institute = new institute();
							$payments = $institute->view_student_payments_upd('', $student_id, $user_id, '', $cond);
							if ($payments != '') {
								$courseSrNo = 1;
								while ($courseData = $payments->fetch_assoc()) {
									extract($courseData);
									//print_r($courseData);
									$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);

									if ($ACTIVE == 1)
										$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentCourseStatus(' . $PAYMENT_ID . ',0)"><i class="fa fa-check"></i></a>';
									elseif ($ACTIVE == 0)
										$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentCourseStatus(' . $PAYMENT_ID . ',1)"><i class="fa fa-times"></i></a>';
									$action = '';

									//$action .= "<a href='viewPaymentHistory&studid=$STUDENT_ID&courseId=$INSTITUTE_COURSE_ID' target='_blank' class='btn btn-primary table-btn' title='View History'><i class='mdi mdi-eye'></i></a>";

									if ($db->permission('update_payment'))
										//$action .= "<a href='page.php?page=studentUpdateFees&payid=$PAYMENT_ID' class='btn btn-primary table-btn' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";

										if ($db->permission('delete_payment'))
											//$action .= "<a href='javascript:void(0)' onclick='deleteStudentPayment($PAYMENT_ID)' class='btn btn-danger table-btn' title='Delete'><i class='mdi mdi-delete'></i></a>";

											if ($db->permission('print_payment_reciept'))
												//$action .= "<a href='javascript:void(0)' onclick='printPayReciept($PAYMENT_ID)' class='btn btn-warning table-btn' title='Print Reciept'><i class='mdi mdi-file-pdf' ></i></a>";

												$action .= "<a href='page.php?page=viewStudentReceipt&payid=$PAYMENT_ID' target='_blank' class='btn btn-primary table-btn' title='Print Reciept'><i class='mdi mdi-file-pdf'></i></a>";



									$FEES_PAID_ON = date("d-m-Y", strtotime($FEES_PAID_ON));

									echo $courseDetail = "<tr id='row-$PAYMENT_ID'><td>$courseSrNo</td>
												<td>$STUDENT_NAME</td>	 
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
				<div class="table-responsive pt-3">
					<h4 class="card-title">Installment Details </h4>
					<?php
					include_once('include/classes/student.class.php');
					$student = new student();
					$docData1 = $student->get_student_installment_details($student_id, $course_id, false);
					$sr1 = 0;
					$tbl1 = '';
					if (!empty($docData1)) {

						$del = '';

						$tbl1 = '<table class="table table-bordered">';
						$tbl1 .= '<tr>
                                          <th>Sr.No</th>
                                          <th>Installment Name</th>
                                          <th>Installment Amount</th>
                                          <th>Date</th>
                                       </tr>';
						foreach ($docData1 as $key => $value) {
							extract($value);
							//print_r($value);
							$tbl1 .= '<tr id="id' . $INSTALLMENT_ID . '">';
							$tbl1 .= '<td>' . ++$sr1 . '</td>';
							$tbl1 .= '<td>';
							$tbl1 .= $INSTALLMENT_NAME;
							$tbl1 .= '</td>';
							$tbl1 .= '<td>';
							$tbl1 .= $AMOUNT;
							$tbl1 .= '</td>';
							$tbl1 .= '<td>';
							$tbl1 .= $DATE;
							$tbl1 .= '</td>';
							$tbl1 .= '</tr>';
						}
						$tbl1 .= '</table>';
					}

					echo $tbl1;
					?>

				</div>
			</div>
		</div>
	</div>
</div>