<?php
$quebank_id = isset($_GET['id']) ? $_GET['id'] : 0;
$course_id = isset($_GET['course']) ? $_GET['course'] : '';
include_once('include/classes/exam.class.php');
$exam = new exam();
?>
<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">View Question Banks Details</h4>
				<a href="page.php?page=addQuestion&course=<?= $course_id ?>&quebank=<?= $quebank_id ?>" class="btn btn-primary" style="float: right">Add Question</a>

				<div class="box-header">
					Course Name: <select class="form-control form-control-sm" onchange="changeCourseQueBank(this.value)">
						<?php
						echo $db->MenuItemsDropdown("exam_question_bank A", "AICPE_COURSE_ID", "COURSE_NAME", "DISTINCT A.AICPE_COURSE_ID, 	get_course_title_modify(A.AICPE_COURSE_ID) AS COURSE_NAME", $course_id, " ");
						?>
					</select>
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
							$res = $exam->view_quetion_bank('', " AND AICPE_COURSE_ID='$course_id'");
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
										$path = QUEBANK_PATH . '/' . $QUEBANK_ID . '/images/' . $IMAGE;
										if (file_exists($path))
											$imgPreview = '<img src="' . $path . '" class="img img-responsive" style="height:35px; width:35px;" id="img_preview"/>';
									}
									$ACTIVE			= $data['ACTIVE'];
									$CREATED_BY 	= $data['CREATED_BY'];
									$action			= "";
									$rowclass		= ($ACTIVE == 0) ? 'class="danger"' : '';
									if ($db->permission('add_que_bank')) {
										$ACTIVE = ($ACTIVE == 1) ? '<small style="color:#3c763d"><i class="fa fa-check"></i></small>' : '<small style="color:#f00"><i class="fa fa-times"></i></small>';
									} else {
										$ACTIVE = ($ACTIVE == 1) ? '<small style="color:#3c763d">Active</small></a>' : '<small style="color:#f00"><i class="fa fa-times"></i></small>';
									}
									if ($db->permission('update_question'))
										$action .= "<a href='page.php?page=editQuestion&id=$QUESTION_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";

									if ($db->permission('delete_question'))
										$action .= "<a href='javascript:void(0)' onclick='deleteQuestion($QUESTION_ID)' class='btn btn-xs btn-link' title='Delete'><i class=' fa fa-trash'></i></a>";

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