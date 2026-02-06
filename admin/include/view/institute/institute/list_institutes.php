<?php

/* display exam results details */
//ini_set('display_errors', 1);
$atccode 	= $db->test(isset($_REQUEST['atccode']) ? $_REQUEST['atccode'] : '');
$institute = $db->test(isset($_REQUEST['institute']) ? $_REQUEST['institute'] : '');
$mobile	= $db->test(isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '');
$state 	= $db->test(isset($_REQUEST['state']) ? $_REQUEST['state'] : '');

$cond = '';
if ($institute != '') $cond .= " AND A.INSTITUTE_ID='$institute'";
if ($atccode != '') $cond  .= " AND A.INSTITUTE_ID='$atccode'";
if ($mobile != '') $cond .= " AND A.INSTITUTE_ID='$mobile'";
if ($state != '') $cond .= " AND A.STATE='$state'";


?>
<style>
	.error {
		color: red;
		font-size: 0.9em;
	}

	.success {
		color: green;
		font-size: 0.9em;
	}
</style>

<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">List Franchise
					<a href="page.php?page=addFranchise" class="btn btn-primary" style="float: right">Add Franchise</a>
					<form action="export.php" method="post" class="">
						<input type="hidden" value="institute_export" name="action" />
						<button type="submit" name="export" value="Export" class="btn btn-danger btn3">Export</button>
					</form>
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
				<?php

				// echo $_GET['pg'];
				// die;
				// Pagination variables


				$rec_limit = 10;
				$page = isset($_GET['pg']) != 0  ? (int)$_GET['pg'] : 1;

				// echo $page;
				// die;
				$offset = ($page - 1) * $rec_limit;

				include_once('include/classes/institute.class.php');
				include_once('include/classes/student.class.php');
				$institute = new institute();
				$student = new student();
				// Fetch paginated results

				$search = isset($_GET['search']) ? $db->test($_GET['search']) : null;

				$cond .= " AND B.USER_ROLE=8 ORDER BY  A.CREATED_ON DESC LIMIT $offset, $rec_limit";
				$res = $institute->list_institute('', $cond, null,  $search);


				// Total records count
				$sql_count = "SELECT A.*,DATE_FORMAT(A.VERIFIED_ON, '%Y-%m-%d') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.VERIFIED_ON, '%Y-%m-%d') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.DOB, '%Y-%m-%d') AS DOB_FORMATTED, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%Y-%m-%d') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%Y-%m-%d') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%i %p') AS UPDATED_DATE, B.USER_NAME, B.PASS_WORD ,B.USER_LOGIN_ID,(SELECT STATE_NAME FROM states_master WHERE STATE_ID=A.STATE) AS STATE_NAME  FROM institute_details A LEFT JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID WHERE A.DELETE_FLAG=0";
				$sql_count .= " AND B.USER_ROLE=8";
				if ($search != NULL) {
					$sql_count .= " AND (A.INSTITUTE_NAME LIKE '%$search%' 
						OR A.CITY LIKE '%$search%' 
						OR A.INSTITUTE_CODE LIKE '%$search%' 
						OR A.MOBILE LIKE '%$search%' 
						OR A.POSTCODE LIKE '%$search%' 
						OR A.AMC_CODE LIKE '%$search%' 
						OR A.EMAIL LIKE '%$search%' 
						OR B.USER_NAME LIKE '%$search%' 
						OR (SELECT STATE_NAME FROM states_master WHERE STATE_ID=A.STATE) LIKE '%$search%')";
				}
				$exc_count = $db->execQuery($sql_count);
				$total_records = $exc_count->num_rows;
				$total_pages = ceil($total_records / $rec_limit);


				?>
				<form method="GET" class="text-end mb-3" action="">
					<div class="row">
						<input type="hidden" value="listFranchise" name="page">
						<div class="col col-3">
							<input type="text" name="search" class="form-control me-2" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
						</div>
						<div class="col col-1">
							<button type="submit" class="btn btn-primary">Search</button>
						</div>
						<div class="col col-4">
							<a href="page.php?page=listFranchise" class="btn btn-warning">Reset</a>
						</div>
					</div>
				</form>
				<div class="table-responsive pt-3">
					<table id="" class="table">
						<thead>
							<tr>
								<th>Sr No</th>
								<th>Action</th>
								<th>Logo</th>
								<th>Student Admission</th>
								<th>Student Enquiry</th>
								<th>Institute Name</th>
								<th>Main Wallet</th>
								<th>Approved</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$logo = '../uploads/default_user.png';
							$logo = $institute->get_institute_docs_single($INSTITUTE_ID, 'logo');

							if ($res != '') {
								$srno = $offset + 1; // Adjust serial number based on page

								while ($data = $res->fetch_assoc()) {
									// Extracting data
									$INSTITUTE_ID = $data['INSTITUTE_ID'];
									$INSTITUTE_NAME = $data['INSTITUTE_NAME'];
									$SHOW_ON_WEBSITE = $data['SHOW_ON_WEBSITE'];
									$MOBILE = $data['MOBILE'];
									$EMAIL = $data['EMAIL'];
									$CITY = $data['CITY'];
									$STATE_NAME = $data['STATE_NAME'];
									$POSTCODE = $data['POSTCODE'];
									$ACTIVE 			= $data['ACTIVE'];
									$VERIFIED = $data['VERIFIED'];
									$LOCATION         = $data['LOCATION'];
									$USER_NAME             = $data['USER_NAME'];
									$PASS_WORD             = $data['PASS_WORD'];
									$INSTITUTE_CODE    = $data['INSTITUTE_CODE'];
									$AMC_CODE             = $data['AMC_CODE'];
									$params = "'$USER_NAME','" . $PASS_WORD . "'";
									$loginBtn = "<a href='javascript:void(0)' class='btn btn-primary btn-xs' title='LOGIN' onclick=\"loginToInst($params)\"><i class=' fa fa-sign-in'></i>Login</a>";


									if ($LOCATION !== '') {
										$LOCATION = $LOCATION;
									} else {
										$LOCATION = "#";
									}

									$printCert = "";

									if ($VERIFIED == 1) {
										$VERIFIED = '<span style="color:#3c763d"><i class="mdi mdi-check"></i> YES</span>';
										$printCert = "<a href='page.php?page=printFranchiseCertificate&inst[]=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Print Certificate' target='_blank'><i class=' mdi mdi-certificate'></i></a>";

										$printCert .= "<a href='page.php?page=printFranchiseAddress&inst[]=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Print Address' target='_blank'><i class=' mdi mdi-message-text'></i></a>";
									} elseif ($VERIFIED == 0) {
										$VERIFIED = '<span style="color:#f00"><i class="mdi mdi-close"></i> NO</span>';
									}


									$editLink = "";
									if ($db->permission('update_institute'))
										$editLink .= "<a href='page.php?page=updateFranchise&id=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Edit'><i class=' mdi mdi-grease-pencil'></i></a>";

									$editLink .= $printCert;

									$editLink .= $performanceCert;
									$editLink .= "<br/><a class='btn btn-success table-btn' title='Share' data-toggle='modal' data-target='#shareModal$INSTITUTE_ID'><i class='mdi mdi-share-variant'></i></a>";
									$editLink .= "<a class='btn btn-success table-btn' title='Share' data-toggle='modal' data-target='#changePassword' data-email='$EMAIL'><i class='mdi mdi-lock'></i></a>";


									if ($SHOW_ON_WEBSITE == 1)
										$SHOW_ON_WEBSITE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstStatusWebsite(' . $INSTITUTE_ID . ',0)"><i class="mdi mdi-check"></i> Shown On Website</a>';
									elseif ($SHOW_ON_WEBSITE == 0)
										$SHOW_ON_WEBSITE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstStatusWebsite(' . $INSTITUTE_ID . ',1)"><i class="mdi mdi-close"></i> Not Shown On Website</a>';

									$count = '';
									$cond21  = " AND INSTITUTE_ID = $INSTITUTE_ID";
									$count = $student->get_admission_count($cond21);

									$count_enquiry = $student->get_admission_count_enquiry($cond21);

									$main_wallet = 0;
									$res111 = $access->get_wallet('', $INSTITUTE_ID, 8);
									if ($res111 != '') {
										while ($data111 = $res111->fetch_assoc()) {
											$main_wallet = $data111['TOTAL_BALANCE'];
										}
									}

									if ($db->permission('update_institute')) {
										if ($ACTIVE == 1)
											$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstStatus(' . $INSTITUTE_ID . ',0)"><i class="mdi mdi-check"></i> YES</a>';
										elseif ($ACTIVE == 0)
											$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstStatus(' . $INSTITUTE_ID . ',1)"><i class="mdi mdi-close"></i> NO</a>';
									} else {
										if ($ACTIVE == 1)
											$ACTIVE = '<span style="color:#3c763d"><i class="mdi mdi-check"></i> YES</span>';
										elseif ($ACTIVE == 0)
											$ACTIVE = '<span style="color:#f00"><i class="mdi mdi-close"></i> NO</span>';
									}
									$color = '';
									if ($PRIMEMEMBER = 1 && $PRIMEMEMBER != NULL && $PRIMEMEMBER != 0) {
										$color = "style='background-color: #fffe47;font-weight: bold;'";
									}


									$courier_wallet = 0;
									$res11 = $access->get_courier_wallet('', $INSTITUTE_ID, 8);
									if ($res11 != '') {
										while ($data11 = $res11->fetch_assoc()) {
											$courier_wallet = $data11['TOTAL_BALANCE'];
										}
									}


									if ($db->permission('delete_institute'))
										$deleteLink = " <a href='javascript:void(0)' class='btn btn-danger table-btn' title='Delete' onclick='deleteInstitute($INSTITUTE_ID)'><i class='  mdi mdi-delete'></i></a>";


									echo "<tr id='row-$INSTITUTE_ID' $color>
                            <td id='website-$INSTITUTE_ID'>$srno</td>
							<td>$editLink <a href='$LOCATION' target='_blank' class='btn btn-primary table-btn'> <i class='mdi mdi-map-marker'></i></a></td>

                            <td>$logo <p>$loginBtn</p> $SHOW_ON_WEBSITE </td>
                            <td>$count </td>
							<td> $count_enquiry	</td>
                            <td>
								ATC Code : $INSTITUTE_CODE <br/><br/>
                                Institute Name: $INSTITUTE_NAME <br/><br/>
								Courier Wallet : $courier_wallet <br/><br/>
								Mobile Number : $MOBILE <br/><br/>
								Email : $EMAIL <br/><br/>
								AMC Code (Ref) : $AMC_CODE  <br/><br/>
								User Name : $USER_NAME  <br/><br/>  
                                City: $CITY <br/><br/>
                                State: $STATE_NAME <br/><br/>
                                Pincode: $POSTCODE
                            </td>
                            <td>$main_wallet</td>
								<td id='verify-$INSTITUTE_ID'>$VERIFIED </td>
                            <td id='status-$INSTITUTE_ID'>$ACTIVE $deleteLink</td>
                        </tr>";
									$srno++;

									echo '
							
									<div class="modal fade" id="shareModal' . $INSTITUTE_ID . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									  <div class="modal-dialog" role="document">
										<div class="modal-content">
										  <div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">Institute Details</h5>
											<button class="btn btn-warning btn" onclick="copyContent' . $INSTITUTE_ID . '()">Copy!</button>     
											
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											  <span aria-hidden="true">&times;</span>
											</button>
										  </div>
										  <div class="modal-body" id="p' . $INSTITUTE_ID . '">								 
												<p> Institute Name : ' . $INSTITUTE_NAME . '</p>
											
												<p> Username : ' . $USER_NAME . '</p>
												<p> Website Link : ' . HTTP_HOST . 'admin/login.php</p>
												<p> App Link : https://play.google.com/store/apps/details?id=com.app.ditrpindia&pcampaignid=web_share </p>
										  </div>     
										</div>
									  </div>
									</div>
									
									
							<script>
							  let text' . $INSTITUTE_ID . ' = document.getElementById("p' . $INSTITUTE_ID . '").innerHTML;  
							  
							  text' . $INSTITUTE_ID . '=text' . $INSTITUTE_ID . '.replace(/<p>/gi, "");
							  text' . $INSTITUTE_ID . '=text' . $INSTITUTE_ID . '.replace(/<\/?p>/gi, "");
							
							  //console.log(text);
							
							  const copyContent' . $INSTITUTE_ID . ' = async () => {
								try {
								  await navigator.clipboard.writeText(text' . $INSTITUTE_ID . ');
								   
								} catch (err) {
								  console.error("Failed to copy: ", err);
								   //console.log(err);
								}
							  }
							</script>
				
									';
								}
							} else {
								echo "<tr><td colspan='9'>No records found</td></tr>";
							}
							?>
						</tbody>
					</table>
				</div>

				<div class="d-flex justify-content-between align-items-center mb-3">
					<!-- Results Info -->
					<div>
						<?php
						$start_result = ($page - 1) * $rec_limit + 1;
						$end_result = min($page * $rec_limit, $total_records);
						echo "Showing $start_result to $end_result of $total_records results";
						?>
					</div>

					<!-- Pagination -->
					<nav>
						<ul class="pagination mb-0">
							<?php
							// Define the range of pages to show
							$range = 10;
							$start = max(1, $page - floor($range / 2));
							$end = min($total_pages, $start + $range - 1);

							if ($end - $start < $range - 1) {
								$start = max(1, $end - $range + 1);
							}

							// Previous button
							if ($page > 1): ?>
								<li class="page-item">
									<a class="page-link" href="?page=listFranchise&pg=<?php echo $page - 1; ?>&search=<?php echo $search; ?>">&laquo;</a>
								</li>
							<?php endif; ?>

							<?php
							// Show the range of pages
							for ($i = $start; $i <= $end; $i++): ?>
								<li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
									<a class="page-link" href="?page=listFranchise&pg=<?php echo $i; ?>&search=<?php echo $search; ?>"><?php echo $i; ?></a>
								</li>
							<?php endfor; ?>

							<?php
							// Next button
							if ($page < $total_pages): ?>
								<li class="page-item">
									<a class="page-link" href="?page=listFranchise&pg=<?php echo $page + 1; ?>&search=<?php echo $search; ?>">&raquo;</a>
								</li>
							<?php endif; ?>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade forgot-pass" tabindex="-1" id="changePassword" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="box box-primary modal-body">
				<div>
					<div class="box-header with-border">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="box-title">Change Password</h3>
					</div>
					<div id="modal-message" class="alert" style="display: none; margin-top: 10px;"></div>
					<!-- /.box-header -->
					<form id="forgot-password-form" action="forgot_password.php" onsubmit="return validatePassword()" method="post">
						<input type="hidden" name="action" id="action" value="reset_password" />
						<div class="box-body">

							<!-- Step 3: Set New Password -->
							<div id="step-3">
								<input type="hidden" class="form-control" id="emailInput" name="email">

								<div class="form-group">
									<label>New Password:</label>
									<div class="input-group">
										<input class="form-control" placeholder="New Password" oninput="validatePassword()" id="new-password" name="new_password" type="password" required>
										<span class="input-group-text" onclick="togglePasswordVisibility('new-password', this)">
											<i class="mdi mdi-eye" id="new-password-icon"></i>
										</span>
									</div>
									<div id="error-message" class="error"></div>
								</div>
								<div class="form-group">
									<label>Confirm Password:</label>
									<div class="input-group">
										<input class="form-control" placeholder="Confirm Password" id="confirm-password" name="confirm_password" type="password" required>
										<span class="input-group-text" onclick="togglePasswordVisibility('confirm-password', this)">
											<i class="mdi mdi-eye" id="confirm-password-icon"></i>
										</span>
									</div>
								</div>

								<!-- Add FontAwesome for icons -->

								<script>
									function togglePasswordVisibility(inputId, iconContainer) {
										const passwordField = document.getElementById(inputId);
										const icon = iconContainer.querySelector('i');

										// Toggle password field type
										if (passwordField.type === 'password') {
											passwordField.type = 'text';
											icon.classList.remove('mdi-eye');
											icon.classList.add('mdi-eye-off');
										} else {
											passwordField.type = 'password';
											icon.classList.remove('mdi-eye-off');
											icon.classList.add('mdi-eye');
										}
									}
								</script>

							</div>
						</div>

						<!-- Footer -->
						<div class="box-footer">
							<div class="pull-right">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								<button type="submit" id="submit-button" class="btn btn-primary">Submit</button>
							</div>
						</div>
					</form>
					<!-- /.box-footer -->
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		// Get all buttons with the modal trigger
		const modalButtons = document.querySelectorAll("a[data-toggle='modal']");

		modalButtons.forEach(function(button) {
			button.addEventListener("click", function() {
				// Get the email from the data attribute
				const email = button.getAttribute("data-email");

				// Set the email input value in the modal
				const emailInput = document.getElementById("emailInput");
				if (emailInput) {
					emailInput.value = email;
				}
			});
		});
	});

	document.addEventListener('DOMContentLoaded', () => {


		let step = 1;

		const form = document.getElementById('forgot-password-form');
		const messageBox = document.getElementById('modal-message');

		function showMessage(type, message) {
			messageBox.style.display = 'block';
			messageBox.className = `alert alert-${type}`;
			messageBox.textContent = message;
		}

		function clearMessage() {
			messageBox.style.display = 'none';
			messageBox.textContent = '';
		}
		form.addEventListener('submit', (event) => {
			event.preventDefault();
			clearMessage();

			const formData = new FormData(form);

			// Submit new password
			fetch('forgot_password.php', {
					method: 'POST',
					body: formData,
				})
				.then((response) => response.json())
				.then((data) => {
					if (data.status === 'success') {
						showMessage('success', data.message); // Display success message
						setTimeout(() => {
							location.reload();
						}, 1000);
					} else if (data.status === 'error') {
						showMessage('danger', data.message); // Display error message from backend
					}
				})
				.catch((error) => {
					showMessage('danger', 'An error occurred. Please try again.'); // Catch network or server errors
					console.error(error);
				});
		});
	});

	function validatePassword() {
		const password = document.getElementById('new-password').value;
		const errorMessage = document.getElementById('error-message');
		const specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/;
		const digitPattern = /\d/g;

		errorMessage.textContent = ''; // Clear previous error message

		if (password.length < 8) {
			errorMessage.textContent = 'Password must be at least 8 characters long.';
			return false;
		}
		if (!specialCharPattern.test(password)) {
			errorMessage.textContent = 'Password must contain at least one special character.';
			return false;
		}
		if ((password.match(digitPattern) || []).length < 3) {
			errorMessage.textContent = 'Password must contain at least three numbers.';
			return false;
		}

		errorMessage.textContent = 'Password is valid.';
		errorMessage.className = 'success';
		return true;
	}
</script>