<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Wallet
				</h4>

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
							$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
							$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
							if ($user_role == 3) {
								$institute_id = $db->get_parent_id($user_role, $user_id);
								$staff_id = $user_id;
							} else {
								$institute_id = $user_id;
								$staff_id = 0;
							}
							$cond = '';
							$balance = isset($_REQUEST['balance']) ? $_REQUEST['balance'] : '';
							if ($balance != '') {
								if ($balance == 0)
									$cond .= " AND TOTAL_BALANCE=0";
								if ($balance == 1)
									$cond .= " AND TOTAL_BALANCE > 0";
							}
							$walletres = $access->get_wallet('', '', '4', $cond);
							if ($walletres != '') {
								$sr = 1;
								while ($data = $walletres->fetch_assoc()) {
									extract($data);
									//print_r($data);
									$usefinfo = $access->get_user_info($USER_ID, $USER_ROLE);
									//print_r($usefinfo)
									$studInstId = $db->get_student_institute_id($USER_ID);

									if ($studInstId == $institute_id) {


							?>
										<tr>
											<td><?= $sr ?></td>
											<td><?= $usefinfo['NAME'] ?></td>
											<td><?= $usefinfo['MOBILE'] ?></td>
											<td><?= $usefinfo['EMAIL'] ?></td>

											<td><?= ($data['LAST_ADDED_ON']) ? $data['LAST_ADDED_ON'] : $data['LAST_CREATED_ON'] ?></td>
											<td><?= $data['TOTAL_BALANCE'] ?></td>
											<td>
												<?php if ($db->permission('list_student_wallet')) {  ?>
													<a href="page.php?page=rechargeWallet&user_id=<?= $USER_ID ?>&user_role=<?= $USER_ROLE ?>" class="btn btn-primary btn1">Recharge</a>
												<?php } ?>
												<?php if ($db->permission('list_student_wallet')) {  ?>
													<a href="page.php?page=rechargeHistory&wallet=<?= $WALLET_ID ?>&user_id=<?= $USER_ID ?>&user_role=<?= $USER_ROLE ?>" class="btn btn-primary btn1">View History</a>
												<?php } ?>
											</td>
										</tr>

							<?php
										$sr++;
									}
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