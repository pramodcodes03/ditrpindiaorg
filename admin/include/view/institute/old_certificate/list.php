<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">List Certificates
					<a href="page.php?page=addOldCert" class="btn btn-primary" style="float: right">Import</a>
					<a href="<?= OLD_CERTIFICATE_PATH ?>/samplelist.csv" class="btn btn-primary" style="float: right; margin-right:20px;">Sample Import Format</a>
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
								<th>Sr.</th>
								<th>Certificate Number</th>
								<th>Date</th>
								<th>Student Name</th>
								<th>Course Name</th>
								<th>Duration</th>
								<th>Marks</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							include_once('include/classes/tools.class.php');
							$tools = new tools();
							$res = $tools->list_oldcertificates('', '');
							if ($res != '') {
								$srno = 1;
								while ($data = $res->fetch_assoc()) {
									//print_r($data)							
									extract($data);

									$action = "";

									if ($db->permission('update_exam'))
										$action .= "<a href='page.php?page=updateOldCertficate&id=$id' class='btn btn-primary table-btn ' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";
									if ($db->permission('delete_exam'))
										$action .= "<a href='javascript:void(0)' onclick='deleteOldCertificate($id)' class='btn btn-danger table-btn ' title='Delete'><i class='mdi mdi-delete'></i></a>
									";

									echo " <tr id='id" . $id . "'>									
									<td>$srno</td>	
									<td>$cert_number</td>									
									<td>$cert_date</td>	
									<td>$name</td>	
									<td>$course_name</td>	
									<td>$course_duration</td>											
									<td>$marks</td>
									<td>$action</td>
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