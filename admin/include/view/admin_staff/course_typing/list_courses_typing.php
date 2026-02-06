 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Typing Courses
 					<a href="page.php?page=addTypingCourses" class="btn btn-primary" style="float: right">Add Typing Course</a>
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
 								<th>Status</th>
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php
								include_once('include/classes/coursetyping.class.php');
								$coursetyping = new coursetyping();
								$res = $coursetyping->list_courses_typing('', '');
								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										//print_r($data);
										$TYPING_COURSE_ID 			= $data['TYPING_COURSE_ID'];
										$TYPING_COURSE_CODE 		= $data['TYPING_COURSE_CODE'];
										$TYPING_COURSE_DURATION		= $data['TYPING_COURSE_DURATION'];
										$TYPING_COURSE_NAME 		= $data['TYPING_COURSE_NAME'];
										$TYPING_COURSE_FEES 		= $data['TYPING_COURSE_FEES'];
										$TYPING_COURSE_MRP 			= $data['TYPING_COURSE_MRP'];
										$TYPING_MINIMUM_AMOUNT 		= $data['TYPING_MINIMUM_AMOUNT'];

										$ACTIVE1					= $data['ACTIVE'];
										$CREATED_BY 				= $data['CREATED_BY'];
										$CREATED_ON 				= $data['CREATED_ON'];
										/*$EXAM_FEES 	= $data['EXAM_FEES'];*/
										if ($db->permission('update_course')) {
											if ($ACTIVE1 == 1)
												$ACTIVE1 = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeCoureStatusTyping(' . $TYPING_COURSE_ID . ',0)"><i class="mdi mdi-check"></i>Active</a>';
											elseif ($ACTIVE1 == 0)
												$ACTIVE1 = '<a href="javascript:void(0)" style="color:#f00" onclick="changeCoureStatusTyping(' . $TYPING_COURSE_ID . ',1)"><i class="fa fa-times"></i>In-Active</a> ';
										} else {
											if ($ACTIVE1 == 1)
												$ACTIVE1 = '<span style="color:#3c763d"><i class="mdi mdi-check"></i>Active</span>';
											elseif ($ACTIVE1 == 0)
												$ACTIVE1 = '<span style="color:#f00"><i class="fa fa-times"></i>In-Active</span> ';
										}

										$PHOTO = '../uploads/default_user.png';
										$action = '';
										if ($db->permission('update_course'))
											$action = "<a page.php?page=updateTypingCourses&id=$TYPING_COURSE_ID' class='btn btn-primary table-btn' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";
										/*if($db->permission('delete_course'))
									$action .= "<a href='javascript:void(0)' onclick='deleteCourse($TYPING_COURSE_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>
									";
				*/
										$docData1 = $coursetyping->get_course_typing_subjects($TYPING_COURSE_ID, false);
										$sr1 = 0;
										$tbl1 = '';
										if (!empty($docData1)) {

											$del = '';

											$tbl1 = '<table class="table table-bordered">';
											$tbl1 .= '<tr>
				                        <th>Sr.No</th>
				                        <th>Subject Name</th>
										<th>Speed</th>
									
				                        </tr>';
											foreach ($docData1 as $key => $value) {
												extract($value);
												//print_r($value);
												$del = "<a href='javascript:void(0)' onclick='deleteSubjectTyping($TYPING_COURSE_SUBJECT_ID ,$TYPING_COURSE_ID)' class='btn btn-danger table-btn' title='Delete'><i class=' mdi mdi-delete'></i></a>";
												$tbl1 .= '<tr id="id' . $TYPING_COURSE_SUBJECT_ID . '">';
												$tbl1 .= '<td>' . ++$sr1 . '</td>';
												$tbl1 .= '<td>';
												$tbl1 .= $TYPING_COURSE_SUBJECT_NAME;
												$tbl1 .= '</td>';
												$tbl1 .= '<td>';
												$tbl1 .= $TYPING_COURSE_SPEED;
												$tbl1 .= '</td>';
												$tbl1 .= '</tr>';
											}
											$tbl1 .= '</table>';
										}


										echo " <tr id='course-id" . $TYPING_COURSE_ID . "'>
											<td>$srno</td>	
											<td>$TYPING_COURSE_CODE</td>											
											<td>$TYPING_COURSE_NAME</td>
											<td>$tbl1</td>
										
											<td>$TYPING_COURSE_DURATION</td>							
											<td id='status-$TYPING_COURSE_ID'>$ACTIVE1</td>
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