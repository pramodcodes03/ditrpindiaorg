<?php
include_once('include/classes/student.class.php');
$student = new student();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';



$res = $student->list_student_direct_admission('', $user_id, '', '');
?> <p style="text-align: center;
padding: 10px;
background-color: yellow;
color: #000;
font-size: 16px;
font-weight: 500;"> Please assign a batch to student for attendance purpose. Otherwise you can not use attendance facilities for student.</p>
<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">

				<h4 class="card-title">List Student Admission

					<a href="page.php?page=studentAddAdmission" class="btn btn-primary btn2">Add New Student</a>

					<form action="export.php" method="post" class="">
						<input type="hidden" value="student_export" name="action" />
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
								<th>Action</th>
								<th>Status</th>
								<th>Photo</th>
								<th>Batch</th>
								<th>Student Name</th>
								<th>Course Interested</th>
								<th>Username</th>
								<th>Mobile</th>
								<th>Referral Code</th>
								<th>Referral Name</th>
								<th>Admission Date</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if ($res != '') {
								$srno = 1;
								while ($data = $res->fetch_assoc()) {
									extract($data);
									//print_r($data); exit();
									$course_name = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
									$couurseInfo = $db->get_inst_course_info($INSTITUTE_COURSE_ID);
									//print_r($couurseInfo); 
									$COURSE_ID = '';
									$MULTI_SUB_COURSE_ID = '';
									$TYPING_COURSE_ID = '';
									if ($couurseInfo != '') {
										$COURSE_ID = isset($couurseInfo['COURSE_ID']) ? $couurseInfo['COURSE_ID'] : '';
										$MULTI_SUB_COURSE_ID = isset($couurseInfo['MULTI_SUB_COURSE_ID']) ? $couurseInfo['MULTI_SUB_COURSE_ID'] : '';
										$TYPING_COURSE_ID = isset($couurseInfo['TYPING_COURSE_ID']) ? $couurseInfo['TYPING_COURSE_ID'] : '';
									}

									$editLink = '';
									//check if the certificate is under process or not
									if (!$db->certificate_pending($STUDENT_ID, $COURSE_ID, $MULTI_SUB_COURSE_ID) && !$db->exam_pending($STUDENT_ID, $STUD_COURSE_DETAIL_ID)) {
										$editLink .= "<a href='page.php?page=studentUpdateAdmission&id=$STUDENT_ID&courseid=$INSTITUTE_COURSE_ID' class='btn btn-primary table-btn' title='Edit'><i class=' mdi mdi-grease-pencil'></i></a>&nbsp;&nbsp";

										//$editLink .="<a href='javascript:void(0)' onclick='deleteStudentAdmission($STUDENT_ID)' class='btn btn-danger table-btn ' title='Delete'><i class='mdi mdi-delete'></i></a>";
									}

									if ($DISPLAY_FORM_STATUS == '1') {

										$editLink .= "<a href='page.php?page=viewStudentForm&id=$STUDENT_ID&courseid=$INSTITUTE_COURSE_ID' class='btn btn-warning table-btn' title='View Form' target='_blank'><i class='mdi mdi-file-pdf'></i></a>";
										$editLink .= "<a href='page.php?page=viewStudentIdcard&id=$STUDENT_ID&courseid=$INSTITUTE_COURSE_ID' class='btn btn-success table-btn' title='View ID Card' target='_blank'><i class='mdi mdi-account-card-details'></i></a>";
									}

									$editLink .= "<a class='btn btn-success table-btn' title='Share' data-toggle='modal' data-target='#shareModal$STUDENT_ID'><i class='mdi mdi-share-variant'></i></a>";


									if ($ACTIVE == 1)
										$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d;font-size: 14px;font-weight: 900;" onclick="changeStudentStatus(' . $STUDENT_ID . ',0)"><i class="mdi mdi-account-check"></i>Active</a>';
									elseif ($ACTIVE == 0)
										$ACTIVE = '<a href="javascript:void(0)" style="color:#f00;font-size: 14px; font-weight: 900;" onclick="changeStudentStatus(' . $STUDENT_ID . ',1)"><i class="mdi mdi-account-off"></i>In-Active</a> ';

									if ($STUD_PHOTO != '') {
										$STUD_PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUD_PHOTO;
									} else {
										$STUD_PHOTO = HTTP_HOST . 'uploads/default_user.png';
									}

									$batch_name = '';

									if (!empty($BATCH_ID) && $BATCH_ID !== 0 && $BATCH_ID !== '') {
										$batch_name = $db->get_batchname($BATCH_ID);
									}
									$referal = '';
									if ($REFFERAL_CODE != '') {
										$referral_name = $db->get_refferar_name($REFFERAL_CODE);
										$referal = $referral_name['STUDENT_FNAME'] . ' ' . $referral_name['STUDENT_MNAME'] . ' ' . $referral_name['STUDENT_LNAME'];
									}
									$ADMISSION_DATE = @date('d-m-Y', strtotime($ADMISSION_DATE));

									$editLink .= "<a href='page.php?page=viewResume&id=$STUDENT_ID' target='_blank' class='btn btn-success btn1' title='Resume'> Resume </a>";

									/*if($db->permission('update_enquiry'))						
							$editLink .= "<a href='update-direct-admission&id=$STUDENT_ID' class='btn btn-sm btn-primary' title='Edit'><i class='fa fa-edit'></i>Edit</a> &nbsp;&nbsp;";				
							*/
									echo " <tr id='id$STUDENT_ID'>
									<td>$srno</td>	
									<td>$editLink</td>	
									<td id='status-$STUDENT_ID'>$ACTIVE</td>
									<td><img src='$STUD_PHOTO' class='img img-responsive img-thumbnail' style='width:50px; height:50px; border-radius:0px'></td> 
									<td>$batch_name</td>
									<td>$STUDENT_FULLNAME</td>
									<td>$course_name</td>
									<td>$USER_NAME</td>	
									<td>$STUDENT_MOBILE</td>
									<td>$REFFERAL_CODE</td>
									<td>$referal</td>														
									<td>$ADMISSION_DATE	</td>	
								</tr>";
									$srno++;

									echo '
							<div class="modal fade" id="shareModal' . $STUDENT_ID . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							  <div class="modal-dialog" role="document">
								<div class="modal-content">
								  <div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Share Details : Copy below data and share with students</h5>
									<button class="btn btn-warning btn" onclick="copyContent()">Copy!</button>     
									
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									  <span aria-hidden="true">&times;</span>
									</button>
								  </div>
								  <div class="modal-body" id="p1">								 
										<p> Student Name : ' . $STUDENT_FULLNAME . '</p>
										<p> Course Name : ' . $course_name . '</p>
										<p> Username : ' . $USER_NAME . '</p>
										<p> Website Link : ' . HTTP_HOST . '</p>
								  </div>     
								</div>
							  </div>
							</div>
							';
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
<script>
	let text = document.getElementById('p1').innerHTML;

	text = text.replace(/<p>/gi, "");
	text = text.replace(/<\/?p>/gi, "");

	console.log(text);

	const copyContent = async () => {
		try {
			await navigator.clipboard.writeText(text);
			document.getElementById("p1").style.backgroundColor = "#fffb98";
		} catch (err) {
			console.error('Failed to copy: ', err);
			//console.log(err);
		}
	}
</script>