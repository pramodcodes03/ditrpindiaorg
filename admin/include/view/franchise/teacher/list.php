 <?php
	$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
	?>

 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Teacher
 					<a href="page.php?page=addTeacher" class="btn btn-primary" style="float: right">Add Teacher</a>
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
 								<th>#</th>
 								<th>Photo</th>
 								<th>Id No</th>
 								<th>Name</th>
 								<th>Designation</th>
 								<th>Mobile</th>
 								<th>Email</th>
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php

								include_once('include/classes/websiteManage.class.php');
								$websiteManage = new websiteManage();

								$res = $websiteManage->list_teacher('', " AND inst_id= $user_id ");
								if ($res != '') {

									$i = 1;
									while ($data = $res->fetch_assoc()) {
										extract($data);
										//print_r($data);
										$action = '';

										$action .= '<a href="page.php?page=updateTeacher&id=' . $id . '"  class="btn btn-link" title="Edit"><i class="fa fa-pencil"></i></a>';

										$action .= '<a href="javascript:void(0);" title="Delete" onclick="deleteTeacher(' . $id . ')" class="btn btn-link"><i class="fa fa-trash-o"></i></a>';

										$action .= '<a href="page.php?page=viewTeacher&id=' . $id . '"  class="btn btn-primary" title="View" target="_blank">View</a>';

										if ($photo != '')
											$photo = TEACHERPHOTO_PATH . '/' . $id . '/' . $photo;

										echo '<tr id="id' . $id . '">
						            <td>' . $i . '</td>
						            <td><img src="' . $photo . '" style="width:150px; height:100%; border-radius:0;"/></td>
						            <td>' . $code . '</td>
									<td>' . $name . '</td>
									<td>' . $designation . '</td>
									<td>' . $mobile . '</td>
									<td>' . $email . '</td>
									<td>' . $action . '</td>
							</tr>';
										$i++;
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