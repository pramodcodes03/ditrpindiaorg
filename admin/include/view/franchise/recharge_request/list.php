 <?php
	$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
	?>

 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Recharge Request
 					<a href="page.php?page=addRechargeRequest" class="btn btn-primary" style="float: right">Add Recharge Request</a>
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
 								<th>Recharge Type</th>
 								<th>Title</th>
 								<th>Amount</th>
 								<th>Status</th>
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php

								include_once('include/classes/websiteManage.class.php');
								$websiteManage = new websiteManage();

								$res = $websiteManage->list_rechargerequest('', " AND inst_id= $user_id ");
								if ($res != '') {

									$i = 1;
									while ($data = $res->fetch_assoc()) {
										extract($data);
										//print_r($data);
										$action = '';

										$action .= '<a href="page.php?page=updateRechargeRequest&id=' . $id . '"  class="btn btn-link" title="Edit"><i class="fa fa-pencil"></i></a>';

										if ($wallet_name == '1') {
											$wallet_name = "Main Wallet";
										}
										if ($wallet_name == '2') {
											$wallet_name = "Courier Wallet";
										}
										if ($wallet_name == '0') {
											$wallet_name = "Not Mentioned ";
										}

										if ($status == 0) {
											$status = "Pending";
										}
										if ($status == 1) {
											$status = "Done";
										}

										$action .= '<a href="javascript:void(0);" title="Delete" onclick="deleteRechargeRequest(' . $id . ')" class="btn btn-link"><i class="fa fa-trash-o"></i></a>';

										echo '<tr id="id' . $id . '">
						            <td>' . $i . '</td>
						            <td>' . $wallet_name . '</td>
									<td>' . $title . '</td>
									<td>' . $amount . '</td>
									<td>' . $status . '</td>
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