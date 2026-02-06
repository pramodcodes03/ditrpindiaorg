 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Courses With Multiple Subjects
 					<a href="addCourseMultiSub" class="btn btn-primary" style="float: right">Add Courses</a>
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
 								<th>Sr.</th>
 								<th>Course Code</th>
 								<th>Course Name</th>
 								<th>Subject Name</th>
 								<th>Duration</th>
 								<th>Exam Status</th>
 								<th>Status</th>
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php
								include_once('include/classes/coursemultisub.class.php');
								$coursemultisub = new coursemultisub();
								$res = $coursemultisub->list_courses_multi_sub('', '');
								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										//print_r($data);
										$MULTI_SUB_COURSE_ID 		= $data['MULTI_SUB_COURSE_ID'];
										$MULTI_SUB_COURSE_CODE 		= $data['MULTI_SUB_COURSE_CODE'];
										$MULTI_SUB_COURSE_DURATION	= $data['MULTI_SUB_COURSE_DURATION'];
										$MULTI_SUB_COURSE_NAME 		= $data['MULTI_SUB_COURSE_NAME'];
										$MULTI_SUB_COURSE_FEES 		= $data['MULTI_SUB_COURSE_FEES'];
										$MULTI_SUB_COURSE_MRP 		= $data['MULTI_SUB_COURSE_MRP'];
										$MULTI_SUB_MINIMUM_AMOUNT 		= $data['MULTI_SUB_MINIMUM_AMOUNT'];
										$COURSE_AWARD_NAME 	= $data['COURSE_AWARD_NAME'];
										$ACTIVE1				= $data['ACTIVE'];
										$CREATED_BY 		= $data['CREATED_BY'];
										$CREATED_ON 		= $data['CREATED_ON'];
										/*$EXAM_FEES 	= $data['EXAM_FEES'];*/
										if ($db->permission('update_course')) {
											if ($ACTIVE1 == 1)
												$ACTIVE1 = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeCoureStatusMultiSub(' . $MULTI_SUB_COURSE_ID . ',0)"><i class="mdi mdi-check"></i>Active</a>';
											elseif ($ACTIVE1 == 0)
												$ACTIVE1 = '<a href="javascript:void(0)" style="color:#f00" onclick="changeCoureStatusMultiSub(' . $MULTI_SUB_COURSE_ID . ',1)"><i class="fa fa-times"></i>In-Active</a> ';
										} else {
											if ($ACTIVE1 == 1)
												$ACTIVE1 = '<span style="color:#3c763d"><i class="mdi mdi-check"></i>Active</span>';
											elseif ($ACTIVE1 == 0)
												$ACTIVE1 = '<span style="color:#f00"><i class="fa fa-times"></i>In-Active</span> ';
										}

										$PHOTO = '../uploads/default_user.png';
										$action = '';
										if ($db->permission('update_course'))
											$action = "<a href='page.php?page=updateCourseMultiSub&id=$MULTI_SUB_COURSE_ID' class='btn btn-primary table-btn' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";
										/*if($db->permission('delete_course'))
									$action .= "<a href='javascript:void(0)' onclick='deleteCourse($MULTI_SUB_COURSE_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>
									";
				*/
										$docData1 = $coursemultisub->get_course_multi_sub($MULTI_SUB_COURSE_ID, false);
										$sr1 = 0;
										$tbl1 = '';
										if (!empty($docData1)) {

											$del = '';

											$tbl1 = '<table class="table table-bordered">';
											$tbl1 .= '<tr>
				                        <th>Sr.No</th>
				                        <th>Subject Name</th>
									
				                        </tr>';
											foreach ($docData1 as $key => $value) {
												extract($value);
												//print_r($value);
												$del = "<a href='javascript:void(0)' onclick='deleteSubjectMultiSub($COURSE_SUBJECT_ID,$MULTI_SUB_COURSE_ID)' class='btn btn-danger table-btn' title='Delete'><i class=' mdi mdi-delete'></i></a>";
												$tbl1 .= '<tr id="id' . $COURSE_SUBJECT_ID . '">';
												$tbl1 .= '<td>' . ++$sr1 . '</td>';
												$tbl1 .= '<td>';
												$tbl1 .= $COURSE_SUBJECT_NAME;
												$tbl1 .= '</td>';
												$tbl1 .= '</tr>';
											}
											$tbl1 .= '</table>';
										}

										$valid_exam = $db->validate_apply_exam('', $MULTI_SUB_COURSE_ID, '');
										extract($valid_exam);
										$ExamStatus = '';
										$QuestionBankStatus = '';

										if ($valid_exam['errors']['exam_unavailable'] != '') {
											$ExamStatus = "<p style='background-color:red; padding:5px;'>" . $valid_exam['errors']['exam_unavailable'] . "</p>";
										}
										if ($valid_exam['errors']['qb_unavailable'] != '') {
											$QuestionBankStatus = "<p style='background-color:red; padding:5px;'>" . $valid_exam['errors']['qb_unavailable'] . "</p>";
										}

										$rowColor = '';
										if ($ExamStatus != '') {
											$rowColor = "style='background-color:yellow'";
										}


										echo " <tr id='course-id" . $MULTI_SUB_COURSE_ID . "' $rowColor >
											<td>$srno</td>	
											<td>$MULTI_SUB_COURSE_CODE</td>											
											<td>$COURSE_AWARD_NAME IN $MULTI_SUB_COURSE_NAME</td>
											<td>$tbl1</td>
											<td>$MULTI_SUB_COURSE_DURATION</td>
											<td>$ExamStatus</td>											
											<td id='status-$MULTI_SUB_COURSE_ID'>$ACTIVE1</td>
											<td>$action</td>
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