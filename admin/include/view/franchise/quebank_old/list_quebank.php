 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Question Banks</h4>
 				<a href="page.php?page=addQueBank" class="btn btn-primary" style="float: right">Add Question Banks</a>

 				<div class="table-responsive pt-3">
 					<table id="order-listing" class="table">
 						<thead>
 							<tr>
 								<th>Sr.</th>
 								<th>Course Name</th>
 								<th>Exam Name</th>
 								<th>Total Questions</th>
 								<th>Date</th>
 								<!-- <th>Status</th> -->
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php

								include_once('include/classes/exam.class.php');

								$exam = new exam();

								$res = $exam->list_quetion_bank_master('', '');

								if ($res != '') {
									$srno = 1;

									while ($data = $res->fetch_assoc()) {

										$AICPE_COURSE_ID 	= $data['AICPE_COURSE_ID'];

										$QUEBANK_ID		= $data['QUEBANK_ID'];

										$COURSE_NAME	= $data['COURSE_NAME'];

										$EXAM_NAME 		= $data['EXAM_NAME'];

										$TOTAL_QUE 		= $exam->count_total_question($AICPE_COURSE_ID);

										$ACTIVE = '';

										$CREATED_ON 	= $data['CREATED_DATE'];

										$rowclass		= ($ACTIVE == 0) ? 'class="danger"' : '';

										//$ACTIVE 		= ($ACTIVE==1)?'<a href="javascript:void(0)" onclick="changeQueBankStatus('.$QUEBANK_ID.',0)"><small class="label bg-green">Active</small></a>':'<a href="javascript:void(0)" onclick="changeQueBankStatus('.$QUEBANK_ID.',1)"><small class="label bg-red">In-Active</small></a>';

										$action = "";

										$view_quebank = 'page.php?page=viewQueBank&course=' . $AICPE_COURSE_ID . '&id=' . $QUEBANK_ID;

										if ($db->permission('update_que_bank'))

											$action = "<a href='$view_quebank' class='btn btn-xs btn-link' title='View'><i class=' fa fa-pencil'></i></a>";

										if ($db->permission('delete_que_bank'))

											$action .= "<a href='javascript:void(0)' onclick='deleteQueBank($QUEBANK_ID)' class='btn btn-xs btn-link' title='Delete'><i class=' fa fa-trash'></i></a>";

										if ($db->permission('empty_que_bank'))

											$action .= "<a href='javascript:void(0)' onclick='emptyQueBank($QUEBANK_ID)' class='btn btn-xs btn-link' title='Delete'>Empty</a>

									";

										echo " <tr id='quebank-id" . $AICPE_COURSE_ID . "'>

											<td>$srno</td>						

											<td>$COURSE_NAME</td>

											<td>$EXAM_NAME</td>

											<td id='total-$QUEBANK_ID'>$TOTAL_QUE</td>

											<td>$CREATED_ON</td>							

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