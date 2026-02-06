 <?php
	include_once('include/classes/student.class.php');
	$student = new student();
	$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
	$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
	if ($user_role == 3) {
		$institute_id = $db->get_parent_id($user_role, $user_id);
		$staff_id = $user_id;
	} else {
		$institute_id = $user_id;
		$staff_id = 0;
	}
	$res = $student->list_student_enquiry('', $institute_id, '', '');
	?>
 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Student Enquiries
 					<?php if ($db->permission('add_enquiry')) { ?>
 						<a href="page.php?page=studentAddEnquiry" class="btn btn-primary btn2" style="float: right">New Student Enquiry</a>
 					<?php } ?>
 					<form action="export.php" method="post" class="">
 						<input type="hidden" value="student_export_enquiry" name="action" />
 						<button type="submit" name="export" value="Export" class="btn btn-danger btn3">Export</button>
 					</form>
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
 								<th>S/N</th>
 								<th>Remark</th>
 								<th>Action</th>
 								<th>Detail</th>
 								<th>Student Name</th>
 								<th>Course Interested</th>
 								<th>Email</th>
 								<th>Mobile</th>
 								<th>Referral Code</th>
 								<th>Referral Name</th>

 								<th>Date</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php
								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										//print_r($data);			
										extract($data);

										if ($db->permission('add_admission')) {
											if ($REGISTRATION == 1)
												$REGISTRATION_BTN = '<span class="label label-success">Registered</span>';
											if ($REGISTRATION == 0)
												$REGISTRATION_BTN = "	
    							<a href='page.php?page=studentRegisterEnquiry&enq=$ENQUIRY_ID' onclick='return confirm(\"Are You Sure You Want To Register Student?\")' class='btn btn-warning btn1' title='Register Now'>Register Now</a>";
										}

										$course_str = '';

										if ($INSTRESTED_COURSE != '') {
											$course_name = $db->get_inst_course_name($INSTRESTED_COURSE);
											$course_str .= $course_name;
										}
										$referal = '';
										if ($REFFERAL_CODE != '') {
											$referral_name = $db->get_refferar_name($REFFERAL_CODE);
											$referal = $referral_name['STUDENT_FNAME'] . ' ' . $referral_name['STUDENT_MNAME'] . ' ' . $referral_name['STUDENT_LNAME'];
										}

										$editLink = '';
										if ($db->permission('update_enquiry'))
											$editLink .= "<a href='page.php?page=studentUpdateEnquiry&id=$ENQUIRY_ID' class='btn  btn-primary table-btn' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";

										//if($db->permission('delete_enquiry'))	
										//$editLink .= "<a href='javascript:void(0)' onclick='deleteStudentEnquiry($ENQUIRY_ID)' class='btn btn-danger table-btn' title='Delete'><i class='mdi mdi-delete'></i></a>";							

										if ($db->permission('add_admission'))
											$editLink .= $REGISTRATION_BTN;
										echo " <tr id='row-$ENQUIRY_ID'>
									<td>$srno</td>
									<td>$REMARK</td>
									<td>$editLink</td>	
									<td>$ADMISSION_FROM</td>
									<td>$STUDENT_FULLNAME</td>
									<td>$course_str</td>
									<td>$STUDENT_EMAIL</td>
									<td>$STUDENT_MOBILE</td>
									<td>$REFFERAL_CODE</td>
									<td>$referal</td>
									
									<td>$CREATED_DATE</td>	
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