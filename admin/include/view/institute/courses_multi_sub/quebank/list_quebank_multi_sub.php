 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Question Banks For Courses With Multiple Subjects
 					<a href="page.php?page=addQueBankMultiSub" class="btn btn-primary" style="float: right">Add Question Banks</a>

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

 								<th>Course Name</th>

 								<th>Subject Name</th>

 								<th>Total Questions</th>

 								<th>Date</th>

 								<!-- <th>Status</th> -->

 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php

								include_once('include/classes/exammultisub.class.php');

								$exammultisub = new exammultisub();

								$res = $exammultisub->list_quetion_bank_master_multi_sub('', '');

								if ($res != '') {

									$srno = 1;

									while ($data = $res->fetch_assoc()) {

										$MULTI_SUB_COURSE_ID 	= $data['MULTI_SUB_COURSE_ID'];
										$COURSE_SUBJECT_ID 	= $data['COURSE_SUBJECT_ID'];

										$QUEBANK_ID		= $data['QUEBANK_ID'];



										$MULTI_SUB_COURSE_NAME	= $data['MULTI_SUB_COURSE_NAME'];

										$MULTI_SUB_COURSE_CODE	= $data['MULTI_SUB_COURSE_CODE'];

										$SUBJECT_NAME_MODIFY 		= $data['SUBJECT_NAME_MODIFY'];



										$TOTAL_QUE 		= $exammultisub->count_total_question_multi_sub($MULTI_SUB_COURSE_ID, $COURSE_SUBJECT_ID);



										$ACTIVE = '';



										$CREATED_ON 	= $data['CREATED_DATE'];

										$rowclass		= ($ACTIVE == 0) ? 'class="danger"' : '';

										//$ACTIVE 		= ($ACTIVE==1)?'<a href="javascript:void(0)" onclick="changeQueBankStatus('.$QUEBANK_ID.',0)"><small class="label bg-green">Active</small></a>':'<a href="javascript:void(0)" onclick="changeQueBankStatus('.$QUEBANK_ID.',1)"><small class="label bg-red">In-Active</small></a>';

										$action = "";

										$view_quebank = 'page.php?page=viewQueBankMultiSub&course=' . $MULTI_SUB_COURSE_ID . '&id=' . $QUEBANK_ID . '&subject=' . $COURSE_SUBJECT_ID;

										if ($db->permission('update_que_bank'))

											$action = "<a href='$view_quebank' class='btn btn-primary table-btn' title='View'><i class='mdi mdi-grease-pencil'></i></a>";

										if ($db->permission('delete_que_bank'))

											$action .= "<a href='javascript:void(0)' onclick='deleteQueBankMultiSub($QUEBANK_ID)' class='btn btn-danger table-btn' title='Delete'><i class='mdi mdi-delete'></i></a>";

										if ($db->permission('empty_que_bank'))

											$action .= "<a href='javascript:void(0)' onclick='emptyQueBankMultiSub($QUEBANK_ID)' class='btn btn-warning btn1' title='Delete'>Empty</a>

									";



										echo " <tr id='quebank-id" . $QUEBANK_ID . "'>

											<td>$srno</td>						

											<td>$MULTI_SUB_COURSE_NAME ($MULTI_SUB_COURSE_CODE)</td>

											<td>$SUBJECT_NAME_MODIFY</td>

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