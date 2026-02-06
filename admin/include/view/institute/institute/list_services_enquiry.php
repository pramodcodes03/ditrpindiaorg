<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">List Services Enquiry

					<!--<form action="export.php" method="post" class="">-->
					<!--	<input type="hidden" value="franchise_enquiry_export" name="action" />-->
					<!--	<button type="submit" name="export" value="Export" class="btn btn-danger btn3">Export</button>-->
					<!--</form>-->

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
								<h4><i class="icon mdi mdi-check"></i> <?= ($msg_flag == true) ? 'Success' : 'Error' ?>:</h4>
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
								<th>Service Name</th>
								<th>Center Name</th>
								<th>Email Id</th>
								<th>Mobile</th>
								<th>State</th>
								<th>City</th>
								<th>Pincode</th>
								<th>Remark</th>
								<th>Date</th>

							</tr>
						</thead>
						<tbody>
							<?php

							include_once('include/classes/institute.class.php');
							$institute = new institute();

							$res = $institute->list_services_enquiry('', '');
							if ($res != '') {
								$srno = 1;

								while ($data = $res->fetch_assoc()) {
									extract($data);
									$editLink = "";
									//$editLink .= "<a href='updateFranchiseEnquiry&id=$id' class='btn btn-primary table-btn' title='Edit'><i class=' mdi mdi-grease-pencil'></i></a>";


									//$editLink .= " <a href='javascript:void(0)' class='btn btn-danger table-btn' title='Delete' onclick='deleteFranchiseEnquiry($id)'><i class='  mdi mdi-delete'></i></a>";


									echo " <tr id='id$id'>
							<td>$srno</td>
							<td>$SERVICE_NAME</td>
							<td>$name</td>
							<td>$email</td>
							<td>$mobile</td>
							<td>$STATE_NAME</td>
							<td>$city</td>
							<td>$pincode</td>
							<td>$remark</td>
							<td>" . date("d-m-Y", strtotime($created_at)) . "</td>
							
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