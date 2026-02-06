<?php
$type = isset($_GET['type']) ? $_GET['type'] : '';
$title = ($type == 'marketing') ? 'Marketing Material' : 'Gallery';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			List <?= $title ?>

		</h1>
		<ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#"> <?= $title ?></a></li>
			<li class="active"> List <?= $title ?></li>
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
						<h3 class="box-title"><?= $title ?></h3>
						<?php if ($db->permission('add_gallery')) { ?>
							<a href="page.php?page=add-gallery&type=<?= $type ?>" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add New <?= $title ?></a>
						<?php } ?>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-responsive table-striped table-bordered data-tbl">
								<thead>
									<tr>
										<th>#</th>
										<th>Title</th>
										<th>Description</th>
										<th>Date</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$res = $db->list_gallery('', $type, '');
									if ($res == '') {
										echo '<tr align="center"><td colspan="5">No records found.</td></tr>';
									} else {
										$i = 1;
										while ($data = $res->fetch_assoc()) {
											$GALLERY_ID			= isset($data['GALLERY_ID']) ? $data['GALLERY_ID'] : '';
											$GALLERY_TITLE 		= isset($data['GALLERY_TITLE']) ? $data['GALLERY_TITLE'] : '';
											$TOTAL_FILES 		= isset($data['TOTAL_FILES']) ? $data['TOTAL_FILES'] : '';
											$GALLERY_DESC 		= isset($data['GALLERY_DESC']) ? $data['GALLERY_DESC'] : '';
											$ACTIVE				= isset($data['ACTIVE']) ? $data['ACTIVE'] : '';
											$CREATED_BY			= isset($data['CREATED_BY']) ? $data['CREATED_BY'] : '';
											$CREATED_ON			= isset($data['CREATED_DATE']) ? $data['CREATED_DATE'] : '';
											$GALLERY_TYPE			= isset($data['GALLERY_TYPE']) ? $data['GALLERY_TYPE'] : '';

											$updateLink 		= "update-gallery&id=$GALLERY_ID&type=$type";
											//$deleteLink 		= "list_gallery&id=$GALLERY_ID";
											if ($db->permission('update_gallery')) {
												if ($ACTIVE == 1)
													$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeGalleryStatus(' . $GALLERY_ID . ',0)"><i class="fa fa-check"></i></a>';
												elseif ($ACTIVE == 0)
													$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeGalleryStatus(' . $GALLERY_ID . ',1)"><i class="fa fa-times"></i></a>';
											} else {
												if ($ACTIVE == 1)
													$ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i></span>';
												elseif ($ACTIVE == 0)
													$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i></span>';
											}

											$GALLERY_IMAGE = $db->list_gallery_files_single($GALLERY_ID);


											if ($GALLERY_IMAGE != '') {
												$path = SHOW_IMG_AWS . GALLERY;
												if ($GALLERY_TYPE == 'marketing')
													$path = '../uploads/marketing';
												$GALLERY_IMAGE = $path . '/' . $GALLERY_ID . "/" . $GALLERY_IMAGE;

												if (file_exists($GALLERY_IMAGE)) {
													$GALLERY_IMAGE	= '<img src="' . $GALLERY_IMAGE . '" style="height:50px; width:50px"/>';
												} else {
													$GALLERY_IMAGE = '<img src="resources/dist/img/ditrp_default_material.png" style="height:50px; width:50px"/>';
												}
											} else {
												$GALLERY_IMAGE = '<img src="resources/dist/img/ditrp_default_material.png" style="height:50px; width:50px"/>';
											}
											$action = '';
											if ($db->permission('update_gallery'))
												$action .= '<a href="' . $updateLink . '"  class="btn btn-link" title="Edit"><i class="fa fa-pencil"></i></a>';
											if ($db->permission('delete_gallery'))
												$action .= '<a href="javascript:void(0);" title="Delete" onclick="deleteGallery(' . $GALLERY_ID . ')" class="btn btn-link"><i class="fa fa-trash-o"></i></a>';

											echo '<tr id="row-' . $GALLERY_ID . '">
									<td><a href="' . $updateLink . '">' . $GALLERY_IMAGE . '</a></td>
									<td>' . $GALLERY_TITLE . '</td>
									<td>' . $GALLERY_DESC . '</td>
									<td>' . $CREATED_ON . '</td>	
									<td id="status-' . $GALLERY_ID . '">' . $ACTIVE . '</td>	
									<td>' . $action . '</td>
							</tr>';
											$i++;
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