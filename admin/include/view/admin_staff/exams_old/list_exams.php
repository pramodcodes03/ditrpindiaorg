 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Exams</h4>
 				<a href="page.php?page=addExam" class="btn btn-primary" style="float: right">Add Exam</a>

 				<div class="table-responsive pt-3">
 					<table id="order-listing" class="table">
 						<thead>
 							<tr>
 								<th>Sr.</th>
 								<th>Course Code</th>
 								<th>Exam Name</th>
 								<th>Total Marks</th>
 								<th>Total Questions</th>
 								<th>Marks Per Question</th>
 								<th>Passing Marks</th>
 								<th>Exam Time</th>
 								<th>Display Result</th>
 								<th>Demos</th>
 								<th>Status</th>
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php
								include_once('include/classes/exam.class.php');
								$exam = new exam();
								$res = $exam->list_exams('', '');
								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										$EXAM_ID 		= $data['EXAM_ID'];
										$AICPE_COURSE_ID = $data['AICPE_COURSE_ID'];
										$COURSE_CODE 	= $data['COURSE_CODE'];
										$COURSE_NAME_MODIFY = $data['COURSE_NAME_MODIFY'];
										$TOTAL_MARKS 	= $data['TOTAL_MARKS'];
										$TOTAL_QUESTIONS = $data['TOTAL_QUESTIONS'];
										$MARKS_PER_QUE 	= $data['MARKS_PER_QUE'];
										$PASSING_MARKS 	= $data['PASSING_MARKS'];
										$EXAM_TIME	 	= $data['EXAM_TIME'];
										$EXAM_TITLE		= $data['EXAM_TITLE'];
										$SHOW_RESULT 	= $data['SHOW_RESULT'];
										$DEMO_TEST 		= $data['DEMO_TEST'];
										$ACTIVE			= $data['ACTIVE'];
										$CREATED_BY 	= $data['CREATED_BY'];
										$CREATED_ON 	= $data['CREATED_ON'];
										$rowclass		= ($ACTIVE == 0) ? 'class="danger"' : '';

										if ($db->permission('update_exam')) {
											if ($ACTIVE == 1)
												$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeExamStatus(' . $EXAM_ID . ',0)"><i class="fa fa-check"></i>Active</a>';
											elseif ($ACTIVE == 0)
												$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeExamStatus(' . $EXAM_ID . ',1)"><i class="fa fa-times"></i>In-Active</a> ';

											$SHOW_RESULT 	= ($SHOW_RESULT == 1) ? '<a href="javascript:void(0)" onclick="changeExamDispResultFlag(' . $EXAM_ID . ',0)"><i class="fa fa-check"></i></a>' : '<a href="javascript:void(0)" onclick="changeExamDispResultFlag(' . $EXAM_ID . ',1)"><i class="fa fa-close"></i></a>';

											$DEMO_TEST 		= ($DEMO_TEST == 1) ? '<a href="javascript:void(0)" onclick="changeExamDemoFlag(' . $EXAM_ID . ',0)"><i class="fa fa-check"></i></a>' : '<a href="javascript:void(0)" onclick="changeExamDemoFlag(' . $EXAM_ID . ',1)"><i class="fa fa-close"></i></a>';
										} else {
											if ($ACTIVE == 1)
												$ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i>Active</span>';
											elseif ($ACTIVE == 0)
												$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i>In-Active</span> ';

											$SHOW_RESULT 	= ($SHOW_RESULT == 1) ? '<span><i class="fa fa-check"></i></span>' : '<span><i class="fa fa-close"></i></span>';

											$DEMO_TEST 		= ($DEMO_TEST == 1) ? '<span><i class="fa fa-check"></i></span>' : '<span><i class="fa fa-close"></i></span>';
										}

										$action = "";

										if ($db->permission('update_exam'))
											$action .= "<a href='page.php?page=updateExam&id=$EXAM_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";
										if ($db->permission('delete_exam'))
											$action .= "<a href='javascript:void(0)' onclick='deleteExam($EXAM_ID)' class='btn btn-xs btn-link' title='Delete'><i class=' fa fa-trash'></i></a>
									";

										echo " <tr id='exam-id" . $EXAM_ID . "'>
											<td>$srno</td>
											<td>$COURSE_CODE</td>							
											<td>$COURSE_NAME_MODIFY</td>						
											<td>$TOTAL_MARKS</td>
											<td>$TOTAL_QUESTIONS</td>
											<td>$MARKS_PER_QUE</td>
											<td>$PASSING_MARKS</td>
											<td>$EXAM_TIME</td>
											<td id='disp-result-" . $EXAM_ID . "'>$SHOW_RESULT</td>
											<td id='demo-" . $EXAM_ID . "'>$DEMO_TEST</td>
											<td id='status-" . $EXAM_ID . "'>$ACTIVE</td>
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