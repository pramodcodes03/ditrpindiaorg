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
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1> Wallet</h1>
		<ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Wallet</li>
		</ol>
	</section>
	<section class="content">
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
			<div class="box-header with-border">
				<h3 class="box-title">Wallet Details</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-6">
						<table class="table">
							<tbody>
								<?php

								$res = $access->get_wallet('', $institute_id, 2);
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
											<td colspan="4" align="middle">Your wallet balance is empty! <a href="page.php?page=pay-online" class="btn btn-link">Recharge Now</a></td>
										</tr>

								<?php
									}
								}
								?>
							<tbody>
						</table>
						<?php if ($db->permission('add_wallet_recharge')) { ?><a href="page.php?page=pay-online" class="btn bg-maroon btn-flat margin">Recharge Now</a><?php } ?>

						<?php if ($db->permission('view_recharge_history')) { ?>
							<a href="page.php?page=recharge-history" class="btn bg-olive btn-flat margin">View History</a>
						<?php } ?>

					</div>
				</div>



			</div>

			<!-- /.box-footer-->
		</div>
	</section>
</div>
<!--  </body> -->