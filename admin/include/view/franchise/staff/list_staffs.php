<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">List Staff Members
					<a href="page.php?page=addStaff" class="btn btn-primary" style="float: right">Add Staff</a>
				</h4>

				<div class="table-responsive pt-3">
					<table id="order-listing" class="table">
						<thead>
							<tr class="tableRowColor">
								<th>S/N</th>
								<th>Photo</th>
								<th>Name</th>
								<th>Email</th>
								<th>Mobile</th>
								<th>Username</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							include_once('include/classes/account.class.php');
							$account = new account();
							$res = $account->list_admin_staff('', $_SESSION['user_id'], " AND B.USER_ROLE=3");
							if ($res != '') {
								$srno = 1;
								while ($data = $res->fetch_assoc()) {
									$STAFF_ID 		= $data['STAFF_ID'];
									$USER_LOGIN_ID 	= $data['USER_LOGIN_ID'];
									$ADMIN_ID 		= $data['ADMIN_ID'];
									$STAFF_FULLNAME = $data['STAFF_FULLNAME'];
									$STAFF_EMAIL 	= $data['STAFF_EMAIL'];
									$STAFF_MOBILE 	= $data['STAFF_MOBILE'];
									$STAFF_PHOTO 	= $data['STAFF_PHOTO'];
									$USER_NAME 		= $data['USER_NAME'];
									$ACTIVE 		= $data['ACTIVE'];
									if ($ACTIVE == 1) $ACTIVE = 'Active';
									elseif ($ACTIVE == 0) $ACTIVE = 'In-Active';
									$PHOTO = '../uploads/default_user.png';
									if ($STAFF_PHOTO != '')
										$PHOTO = SHOW_IMG_AWS . ADMIN_STAFF_PHOTO_PATH . '/' . $STAFF_ID . '/' . $STAFF_PHOTO;
									$editLink = "";
									if ($db->permission('update_staff'))
										$editLink .= "<a href='page.php?page=updateStaff&id=$STAFF_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";

									$deleteLink = "";
									if ($db->permission('delete_staff'))
										$deleteLink .= "<a href='javascript:void(0)' onclick='deleteStaff($STAFF_ID,$USER_LOGIN_ID)' class='btn btn-xs btn-link' title='Delete'><i class=' fa fa-trash'></i></a>";


									echo " <tr id='staff-id-$STAFF_ID'>
									<td>$srno</td>
									<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>
									<td>$STAFF_FULLNAME</td>
									<td>$STAFF_EMAIL</td>
									<td>$STAFF_MOBILE</td>
									<td>$USER_NAME</td>
									<td>$ACTIVE</td>
									<td>$editLink $deleteLink</td>
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