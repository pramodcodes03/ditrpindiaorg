<?php
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
?>

<div class="content-wrapper">
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
	<div class="box">
		<div class="box-body">
			<div class="row">
				<div class="col-md-4 mb-4 stretch-card transparent">
					<div class="card data-icon-card-primary bgred">
						<div class="card-body">
							<p class="card-title text-white">Your Wallet Amount</p>
							<div class="row">
								<div class="col-12 text-white">
									<?php

									$res = $access->get_wallet('', $user_id, 4);
									if ($res != '') {
										while ($data = $res->fetch_assoc()) {
									?>
											<h3 class="dashboard-text"> INR <?= $data['TOTAL_BALANCE'] ?> </h3>

										<?php
										}
									} else {
										if ($db->permission('add_wallet_recharge')) {
										?>
											<p class="mb-xl-4 text-primary"><?= $link ?></p>
									<?php
										}
									}
									?>

									<!-- pay-online ourRechargeHistory -->
									<a <a href="#" class="btn btn-primary btn1" style="padding: 10px 10px !important;">Recharge Now</a>
									<!--  rechargeHistory -->
									<a href="page.php?page=rechargeHistory" class="btn btn-warning btn1" style="padding: 10px 10px !important;">View History</a>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>