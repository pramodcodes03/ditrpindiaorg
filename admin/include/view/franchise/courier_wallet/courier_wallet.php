<?php
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
if ($user_role == 5) {
	$institute_id = $db->get_parent_id($user_role, $user_id);
	$staff_id = $user_id;
} else {
	$institute_id = $user_id;
	$staff_id = 0;
}
?>
<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Courier Wallet Details
				</h4>
				<p style="background-color: red;padding: 10px;color: #fff;
    font-size: 12px;
    font-weight: 900;">Important Note : From 18 December 2019 GST is Applicable For Your Payments. DITRP (OPC) PVT LTD. GSTIN No. 27AAGCD4905Q2Z5 . For Any Query Free To Call Us. महत्वपूर्ण नोट : 18 दिसंबर 2019 से GST आपके भुगतान के लिए लागू होगया। DITRP (OPC) PVT LTD. GSTIN No. 27AAGCD4905Q2Z5। अधिक जानकारी के लिए हमें संपर्क करे ।</p>
				<?php
				if (isset($success)) {
				?>
					<div class="row">
						<div class="col-sm-12">
							<div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
								<h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
								<?= isset($message) ? $message : 'Please correct the errors.'; ?>
							</div>
						</div>
					</div>
				<?php
				}
				?>


				<div class="row">
					<div class="col-md-12 grid-margin transparent">
						<div class="row">
							<div class="col-md-6 mb-4 stretch-card transparent">
								<div class="card card-tale" style="background: #bae9ff !important;">
									<div class="card-body">
										<p class="card-title">Courier Wallet Details</p>
										<div class="row">
											<div class="col-8 text-white">
												<table class="table">
													<tbody>
														<?php

														$res = $access->get_courier_wallet('', $institute_id, 8);
														if ($res != '') {
															while ($data = $res->fetch_assoc()) {
														?>
																<tr>
																	<th>Total Balance</th>
																	<td><i class="fa fa-inr"></i> &nbsp;<?= $data['TOTAL_BALANCE'] ?> </td>
																</tr>
																<tr>
																	<th>Last Recharge Date</th>
																	<td><?= ($data['LAST_ADDED_ON']) ? $data['LAST_ADDED_ON'] : $data['LAST_CREATED_ON'] ?> </td>
																</tr>
															<?php
															}
														} else {
															if ($db->permission('add_wallet_recharge')) {
															?>
																<tr>
																	<td colspan="4">Your wallet balance is empty!
																		<!-- <a href="pay-online" class="btn btn-primary btn1">Recharge Now</a> -->
																	</td>
																</tr>

														<?php
															}
														}
														?>
													<tbody>
												</table>
												<br />
												<?php if ($db->permission('add_wallet_recharge')) { ?>
													<!-- <a href="courier-pay-online" class="btn btn-primary btn1">Recharge Now</a> -->
												<?php } ?>

												<?php if ($db->permission('view_recharge_history')) { ?>
													<a href="page.php?page=rechargeHistoryCourier" class="btn btn-warning btn1">View History</a>
												<?php } ?>


											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>