<?php

$delete_id = isset($_GET['delete_id']) ? $_GET['delete_id'] : '';

if ($delete_id != '') {

	$sql = "DELETE FROM page  WHERE PAGE_ID='$delete_id'";

	$res = $db->execQuery($sql);

	if ($res && $db->mysqli->affected_rows > 0) {

		$msg = "Deleted successfully!";

		header('location:list-pages');
	}
}



?>

<div class="content-wrapper">

	<!-- Content Header (Page header) -->

	<section class="content-header">

		<h1>

			List Pages



		</h1>

		<ol class="breadcrumb">

			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

			<li><a href="#"> Pages</a></li>

			<li class="active"> List Pages</li>

		</ol>

	</section>



	<!-- Main content -->

	<section class="content">

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

		<div class="row">





			<div class="col-xs-12">

				<div class="box">

					<div class="box-header">

						<h3 class="box-title">List Pages</h3>

						<?php if ($db->permission('add_page')) { ?>

							<a href="add-page" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add New Page</a>

						<?php } ?>

					</div>

					<!-- /.box-header -->

					<div class="box-body">

						<div class="table-responsive">

							<table class="table table-responsive table-bordered data-tbl">

								<thead>

									<tr>

										<th width="5%">Sr No</th>

										<th width="30%">Name</th>

										<th width="10%">Status</th>

										<th width="20%">Action</th>

									</tr>

								</thead>

								<tbody>

									<?php

									$query = " SELECT PAGE_ID,PAGE_NAME,ACTIVE,IS_DYNAMIC FROM page WHERE DELETE_FLAG=0";

									$exec   = $db->list_pages(" ORDER BY CREATED_ON DESC");

									$srNo = 0;

									if ($exec && $exec->num_rows > 0) {



										$class = "";

										while ($row = $exec->fetch_assoc()) {



											$PAGE_ID = $row['PAGE_ID'];

											$NAME = $row['PAGE_NAME'];

											$IS_DYNAMIC = $row['IS_DYNAMIC'];

											$STATUS 	 = $row['ACTIVE'];

											if ($STATUS == 1) {

												$STATUS = 'Active';
											} else {

												$STATUS = 'Inactive';
											}

											$deleteLink = "list-pages&delete_id=$PAGE_ID";



											$srNo++;



									?>



											<tr>

												<td><?php echo $srNo ?></td>

												<td><?php echo $NAME ?></td>

												<td><?php echo $STATUS ?></td>

												<td>

													<?php if ($IS_DYNAMIC == 1) { ?>

														<a href="add-dynamic-page&page_id=<?php echo $PAGE_ID; ?>" class="btn btn-xs btn-primary">View Dynamic Fields</a>

													<?php } ?>

													<?php if ($db->permission('update_page')) { ?>

														<a href="update-page&page_id=<?php echo $PAGE_ID; ?>"><button class="btn btn-xs btn-primary" type="button">Edit</button></a>

													<?php } ?>



													<?php if ($db->permission('delete_page')) { ?>

														<a href="<?= $deleteLink ?>" onclick="return confirm('Are you sure? ');" class="btn btn-xs btn-danger">Delete</a>

													<?php } ?>

												</td>

											</tr>

									<?php

										}
									}

									?>

								</tbody>

								<tfoot>

								</tfoot>

							</table>

						</div>
					</div>

					<!-- /.box-body -->

				</div>

				<!-- /.box -->





				<!-- /.box -->

			</div>

			<!-- /.col -->

		</div>

		<!-- /.row -->

	</section>

	<!-- /.content -->

</div>





<!-- modal to send email -->

<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

	<div class="modal-dialog modal-md" role="document">

		<div class="modal-content">



			<div class="box box-primary modal-body">

				<div class="">

					<div class="box-header with-border">

						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

						<h3 class="box-title">Compose New Message</h3>

					</div>

					<!-- /.box-header -->

					<div class="box-body">

						<div class="form-group">

							<input class="form-control" placeholder="To:">

						</div>

						<div class="form-group">

							<input class="form-control" placeholder="Subject:">

						</div>

						<div class="form-group">

							<textarea id="compose-textarea" class="form-control" style="height: 150px">



							</textarea>

						</div>

						<div class="form-group">



							<p class="help-block">Messages</p>

						</div>

					</div>

					<!-- /.box-body -->

					<div class="box-footer">

						<div class="pull-right">

							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>

							<button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>

						</div>

					</div>

					<!-- /.box-footer -->

				</div>

			</div>

		</div>

	</div>

</div>