 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Recharge Offers
 					<a href="page.php?page=addRechargeOffers" class="btn btn-primary" style="float: right">Add Recharge Offers</a>
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
 								<th>Name</th>
 								<th>Description</th>
 								<th>Start Date</th>
 								<th>End Date</th>
 								<th>Time</th>
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php

								include_once('include/classes/websiteManage.class.php');
								$websiteManage = new websiteManage();

								$res = $websiteManage->list_rechargeoffers('', '');
								if ($res != '') {

									$i = 1;
									while ($data = $res->fetch_assoc()) {
										extract($data);
										$action = '';
										if ($db->permission('update_gallery'))
											$action .= '<a href="page.php?page=updateRechargeOffers&id=' . $id . '"  class="btn btn-link" title="Edit"><i class="fa fa-pencil"></i></a>';
										if ($db->permission('delete_gallery'))
											$action .= '<a href="javascript:void(0);" title="Delete" onclick="deleteRechargeOffers(' . $id . ')" class="btn btn-link"><i class="fa fa-trash-o"></i></a>';

										echo '<tr id="id' . $id . '">
						            <td>' . $i . '</td>
									<td>' . $name . '</td>
									<td>' . $description . '</td>
									<td>' . date("d-m-Y", strtotime($date)) . '</td>
									<td>' . date("d-m-Y", strtotime($end_date)) . '</td>
									<td>' . $time . '</td>
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