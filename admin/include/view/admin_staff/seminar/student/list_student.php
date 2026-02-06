<?php
include_once('include/classes/seminar.class.php');
$seminar = new seminar();

$res = $seminar->list_seminar_student('', '');
?>
<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">

				<h4 class="card-title">List Seminar Student

					<a href="page.php?page=addSeminarStudent" class="btn btn-primary btn2">Add New Student</a>

					<form action="export.php" method="post" class="">
						<input type="hidden" value="seminar_student_export" name="action" />
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
								<th>S/N</th>
								<th>Action</th>
								<th>Student Name</th>
								<th>Seminar Name</th>
								<th>Student Link</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if ($res != '') {
								$srno = 1;
								while ($data = $res->fetch_assoc()) {
									extract($data);
									//print_r($data); exit();
									if ($active == 1) $active = 'Active';
									elseif ($active == 0) $active = 'In-Active';
									$editLink = '';

									$editLink .= "<a href='page.php?page=updateSeminarStudent&id=$id' class='btn btn-primary table-btn' title='Edit'><i class=' mdi mdi-grease-pencil'></i></a>&nbsp;&nbsp";

									$editLink .= "<a href='javascript:void(0)' onclick='deleteSeminarStudent($id)' class='btn btn-danger table-btn ' title='Delete'><i class='mdi mdi-delete'></i></a>";

									$editLink .= "<a href='page.php?page=viewStudentCertificate&id=$id' target='_blank' class='btn btn-primary table-btn' title='View Certificate'><i class='mdi mdi-eye'></i></a>";

									$editLink .= "<a href='page.php?page=printStudentCertificate&id=$id' target='_blank' class='btn btn-warning table-btn' title='Print Certificate'><i class='mdi mdi-eye'></i></a>";

									$link = '';
									$link .= HTTP_HOST . 'seminarCertificate.php?id=' . $id;


									echo " <tr id='id$id'>
									<td>$srno</td>	
									<td>$editLink</td>	
									<td>$name</td>
									<td>$topic_name</td>
									<td>$link</td>											
									<td>$active</td>	
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