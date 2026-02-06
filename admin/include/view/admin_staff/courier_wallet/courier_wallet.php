<?php
$user_id 	= $_SESSION['user_id'];
$user_role 	= $_SESSION['user_role'];

?>

<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Courier Wallet
					<a href="page.php?page=/courierWalletRecharge" class="btn btn-primary" style="float: right">Recharge Now</a>
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
								<th>Mobile</th>
								<th>Email</th>
								<th>Last Recharge Date</th>
								<th>Total Balance</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$cond = '';
							/*$balance = isset($_REQUEST['balance'])?$_REQUEST['balance']:'';
				if($balance!='')
				{
					if($balance==0)
					$cond .= " AND TOTAL_BALANCE=0";
					if($balance==1)
					$cond .= " AND TOTAL_BALANCE > 0";
				}*/
							$walletres = $access->get_courier_wallet('', '', '', $cond);
							if ($walletres != '') {
								$sr = 1;
								while ($data = $walletres->fetch_assoc()) {
									extract($data);
									$usefinfo = $access->get_user_info($USER_ID, $USER_ROLE);

							?>
									<tr>
										<td><?= $sr ?></td>
										<td><?= @$usefinfo['NAME'] ?></td>
										<td><?= @$usefinfo['MOBILE'] ?></td>
										<td><?= @$usefinfo['EMAIL'] ?></td>

										<td><?= ($data['LAST_ADDED_ON']) ? $data['LAST_ADDED_ON'] : $data['LAST_CREATED_ON'] ?></td>
										<td><?= $data['TOTAL_BALANCE'] ?></td>
										<td>
											<?php if ($db->permission('add_recharge')) {  ?>
												<a href="page.php?page=courierWalletRecharge&user_id=<?= $USER_ID ?>&user_role=<?= $USER_ROLE ?>" class="btn btn-primary btn1">Recharge</a>
											<?php } ?>
											<?php if ($db->permission('view_recharge_history')) {  ?>
												<a href="page.php?page=courierWalletHistory&wallet=<?= $WALLET_ID ?>&user_id=<?= $USER_ID ?>&user_role=<?= $USER_ROLE ?>" class="btn btn-primary btn1">View History</a>
											<?php } ?>
										</td>
									</tr>

							<?php
									$sr++;
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