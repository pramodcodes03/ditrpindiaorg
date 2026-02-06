<?php
$user_id = $_SESSION['user_id'];

$errors = array();

$startdate = $db->test(isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : '');
$enddate = $db->test(isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '');

if ($startdate != '' || $enddate != '') {
	$cond = " AND A.FEES_PAID_DATE BETWEEN '$startdate' AND '$enddate'";
}


?>

<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">List Students Payments
					<a href="page.php?page=studentAddFees" class="btn btn-primary" style="float: right">Add New Payment</a>
					<form action="export.php" method="post" class="">
						<input type="hidden" value="studentfees_export" name="action" />
						<button type="submit" name="export" value="Export" class="btn btn-danger btn3">Export</button>
					</form>
				</h4>
				<div class="row">
					<div class="col-12">
						<?php
						include_once('include/classes/student.class.php');
						$student  = new student();

						$ALL_COURSE_FEES = $TOTAL_FEES_PAID = $TOTAL_FEES_BALANCE = 0;

						$ALL_COURSE_FEES = $student->get_allcoursefeestotal($user_id);
						$TOTAL_FEES_PAID = $student->get_allpaidfeestotal($user_id);
						$TOTAL_FEES_BALANCE = $ALL_COURSE_FEES - $TOTAL_FEES_PAID;
						?>

						<p style="text-align: right; font-size: 18px;"> <strong>Paid Fees : </strong> Rs. <?= $TOTAL_FEES_PAID ?> /-<br />
							<strong>Balance Fees : </strong>Rs. <?= $TOTAL_FEES_BALANCE ?> /-<br />
							<strong>Total Fees : </strong>Rs. <?= $ALL_COURSE_FEES ?> /-
						</p>

					</div>
				</div>
				<!-- 
			  	<form name="form1" class="forms-sample" action="" method="post" enctype="multipart/form-data">
                    <div class="row" style="position: relative; top: -85px;">                    
                        <div class="form-group col-sm-3 <?= (isset($errors['startdate'])) ? 'has-error' : '' ?>" >
                            <label>Start Date</label>		
                            						
                            <input type="date" class="form-control" placeholder="startdate" name="startdate" value="" required>
                            <span class="help-block"><?= (isset($errors['startdate'])) ? $errors['startdate'] : '' ?></span>
                        </div>

                        <div class="form-group col-sm-3 <?= (isset($errors['enddate'])) ? 'has-error' : '' ?>" >
                            <label>End Date</label>		
                            						
                            <input type="date" class="form-control" placeholder="enddate" name="enddate" value="" required>
                            <span class="help-block"><?= (isset($errors['enddate'])) ? $errors['enddate'] : '' ?></span>
                        </div>

                        <div class="form-group col-sm-1">
                            <input type="submit" class="btn btn-danger btn1" value="Filter" name="search" style='border-radius:0%; position: absolute;
                             border-radius: 0%; top: 30px;'/>
                        </div>  
					</div>
			</form> -->
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
							$payments = $institute->list_student_payments_upd('', '', $user_id, '', $cond);
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

									$action .= "<a href='page.php?page=viewPaymentHistory&studid=$STUDENT_ID&courseId=$INSTITUTE_COURSE_ID' class='btn btn-primary table-btn' title='View History'><i class='mdi mdi-eye'></i></a>";

									if ($db->permission('update_payment'))
										//$action .= "<a href='page.php?page=studentUpdateFees&payid=$PAYMENT_ID' class='btn btn-primary table-btn' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";

										if ($db->permission('delete_payment'))
											//$action .= "<a href='javascript:void(0)' onclick='deleteStudentPayment($PAYMENT_ID)' class='btn btn-danger table-btn' title='Delete'><i class='mdi mdi-delete'></i></a>";

											if ($db->permission('print_payment_reciept'))
												//$action .= "<a href='javascript:void(0)' onclick='printPayReciept($PAYMENT_ID)' class='btn btn-warning table-btn' title='Print Reciept'><i class='mdi mdi-file-pdf' ></i></a>";

												//$action .= "<a href='page.php?page=viewStudentReceipt&payid=$PAYMENT_ID' target='_blank' class='btn btn-primary table-btn' title='Print Reciept'><i class='mdi mdi-file-pdf'></i></a>";



												$FEES_PAID_ON = date("d-m-Y", strtotime($FEES_PAID_ON));
									$FEES_BALANCE = $TOTAL_COURSE_FEES - $FEES_PAID;
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
			</div>
		</div>
	</div>
</div>