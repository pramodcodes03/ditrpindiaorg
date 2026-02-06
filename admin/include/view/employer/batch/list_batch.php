 <?php
	include_once('include/classes/institute.class.php');
	$institute = new institute();

	$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
	$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
	if ($user_role == 5) {
		$institute_id = $db->get_parent_id($user_role, $user_id);
		$staff_id = $user_id;
	} else {
		$institute_id = $user_id;
		$staff_id = 0;
	}

	$res = $institute->list_batch('', $institute_id, '');
	?>
 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Batches
 					<a href="page.php?page=addBatches" class="btn btn-primary" style="float: right">New Batch</a>
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
 								<th>Batch Name</th>
 								<th>Timing</th>
 								<th>Number Of Student</th>
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php
								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										extract($data);

										$editLink = '';
										if ($db->permission('update_enquiry'))
											$editLink .= "<a href='page.php?page=updateBatches&id=$id' class='btn  btn-primary table-btn' title='Edit'><i class=' mdi mdi-grease-pencil'></i></a>";

										if ($db->permission('delete_enquiry'))
											$editLink .= "<a href='javascript:void(0)' onclick='deleteBatches($id)' class='btn btn-danger table-btn' title='Delete'><i class=' mdi mdi-delete'></i></a>";

										echo " <tr id='id" . $id . "'>
									<td>$srno</td>
									<td>$batch_name</td>
									<td>$timing</td>
									<td>$numberofstudent</td>
									<td>$editLink</td>								
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