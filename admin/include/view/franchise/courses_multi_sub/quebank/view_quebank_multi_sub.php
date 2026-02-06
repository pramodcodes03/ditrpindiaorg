 <?php
	$quebank_id = isset($_GET['id']) ? $_GET['id'] : 0;
	$course_id = isset($_GET['course']) ? $_GET['course'] : '';
	$subject_id = isset($_GET['subject']) ? $_GET['subject'] : '';
	include_once('include/classes/exammultisub.class.php');
	$exammultisub = new exammultisub();

	?>

 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">View Question Banks Details For Courses With Multiple Subjects
 					<a href="page.php?page=addQuestionMultiSub&course=<?= $course_id ?>&subject=<?= $subject_id ?>&quebank=<?= $quebank_id ?>" class="btn btn-primary" style="float: right">Add Question</a>
 				</h4>


 				<div class="box-header">
 					Course Name: <select class="form-control form-control-sm" onchange="changeCourseQueBankMultiSub(this.value)">
 						<?php
							echo $db->MenuItemsDropdown("multi_sub_exam_question_bank A", "MULTI_SUB_COURSE_ID", "COURSE_NAME", "DISTINCT A.MULTI_SUB_COURSE_ID,get_course_multi_sub_title_modify(A.MULTI_SUB_COURSE_ID) AS COURSE_NAME", $course_id, " ");
							?>
 					</select>

 					<div style="padding:15px 0px">
 						<h4> Subject Name: <select class="col-sm-6 select2 pull-left">
 								<?php
									echo $db->MenuItemsDropdown("multi_sub_exam_question_bank A", "COURSE_SUBJECT_ID", "SUBJECT_NAME", "DISTINCT A.COURSE_SUBJECT_ID,get_subject_title_multi_sub(A.COURSE_SUBJECT_ID) AS SUBJECT_NAME", $subject_id, " ");
									?>
 							</select> </h4>

 					</div>


 				</div>
 				<div class="table-responsive pt-3">
 					<table id="order-listing" class="table">
 						<thead>
 							<tr>
 								<th style='width:3%'>Sr.</th>
 								<th style='width:15%'>Question</th>
 								<th style='width:15%'>Option 1</th>
 								<th style='width:15%'>Option 2</th>
 								<th style='width:15%'>Option 3</th>
 								<th style='width:15%'>Option 4</th>
 								<th style='width:3%'>Img</th>
 								<th style='width:10%'>Correct Ans</th>
 								<th style='width:3%'>Act</th>
 								<th style='width:5%'>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php

								$res = $exammultisub->view_quetion_bank_multi_sub('', " AND MULTI_SUB_COURSE_ID='$course_id' AND COURSE_SUBJECT_ID='$subject_id'");
								$html = '';
								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										$QUESTION_ID 	= $data['QUESTION_ID'];
										$QUEBANK_ID		= $data['QUEBANK_ID'];
										$IMAGE 			= $data['IMAGE'];
										//$s = "abcde?cde?xtz?bb()*&b?";					
										$QUESTION 		= $data['QUESTION'];
										$QUESTION 		= preg_replace('/[^\x00-\x7f]/', '_', $QUESTION);

										$OPTION_A 		= $data['OPTION_A'];
										$OPTION_B 		= $data['OPTION_B'];
										$OPTION_C 		= $data['OPTION_C'];
										$OPTION_D 		= $data['OPTION_D'];
										$CORRECT_ANS 		= $data['CORRECT_ANS'];
										$imgPreview = '';
										if ($IMAGE != '') {
											$path = QUEBANK_PATH_FOR_MULTI_SUB . '/' . $QUEBANK_ID . '/images/' . $IMAGE;
											if (file_exists($path))
												$imgPreview = '<img src="' . $path . '" class="img img-responsive" style="height:35px; width:35px;" id="img_preview"/>';
										}
										$ACTIVE			= $data['ACTIVE'];
										$CREATED_BY 	= $data['CREATED_BY'];
										$action			= "";
										$rowclass		= ($ACTIVE == 0) ? 'class="danger"' : '';
										if ($db->permission('add_que_bank')) {
											$ACTIVE = ($ACTIVE == 1) ? '<small style="color:#3c763d;font-size: 26px;"><i class="mdi mdi-check"></i></small>' : '<small style="color:#f00"><i class="mdi mdi-close"></i></small>';
										} else {
											$ACTIVE = ($ACTIVE == 1) ? '<small style="color:#3c763d;font-size: 26px;">Active</small></a>' : '<small style="color:#f00"><i class="mdi mdi-close"></i></small>';
										}
										if ($db->permission('update_question'))
											$action .= "<a page.php?page=editQuestionMultiSub&id=$QUESTION_ID' class='btn btn-primary table-btn' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";

										if ($db->permission('delete_question'))
											$action .= "<a href='javascript:void(0)' onclick='deleteQuestionMultiSub($QUESTION_ID)' class='btn btn-danger table-btn' title='Delete'><i class='mdi mdi-delete'></i></a>";

										$html .=  " <tr id='row-" . $QUESTION_ID . "' $rowclass>
											<td>$srno</td>						
											<td>$QUESTION</td>
											
											<td>$OPTION_A</td>
											<td>$OPTION_B</td>
											<td>$OPTION_C</td>
											<td>$OPTION_D</td> 
											<td>$imgPreview</td>
											<td>$CORRECT_ANS</td>
											<td>$ACTIVE</td>
											<td>$action</td>
				                           </tr>";

										$srno++;
									}
									echo $html;
								}

								?>
 						</tbody>
 					</table>
 				</div>
 			</div>
 		</div>
 	</div>
 </div>