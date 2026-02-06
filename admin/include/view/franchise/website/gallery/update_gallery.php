<?php
$type = isset($_GET['type']) ? $_GET['type'] : '';
$gallery_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_gallery']) ? $_POST['update_gallery'] : '';
if ($action != '') {
	$result	= $db->update_gallery();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$type = isset($_POST['type']) ? $_POST['type'] : '';
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=list-gallery&type=' . $type);
	}
}
$res = $db->list_gallery($gallery_id, '', '');
while ($data = $res->fetch_assoc()) {
	$GALLERY_ID			= isset($data['GALLERY_ID']) ? $data['GALLERY_ID'] : '';
	$GALLERY_TYPE			= isset($data['GALLERY_TYPE']) ? $data['GALLERY_TYPE'] : '';
	$GALLERY_TITLE 		= isset($data['GALLERY_TITLE']) ? $data['GALLERY_TITLE'] : '';
	$GALLERY_IMAGE 		= isset($data['GALLERY_IMAGE']) ? $data['GALLERY_IMAGE'] : '';
	$GALLERY_DESC 		= isset($data['GALLERY_DESC']) ? $data['GALLERY_DESC'] : '';

	$ACTIVE				= isset($data['ACTIVE']) ? $data['ACTIVE'] : '';
	$CREATED_BY			= isset($data['CREATED_BY']) ? $data['CREATED_BY'] : '';
	$CREATED_ON			= isset($data['CREATED_DATE']) ? $data['CREATED_DATE'] : '';

	if ($GALLERY_IMAGE != '')
		$GALLERY_IMAGE = '../' . UPLOAD_DIR . '/' . GALLERY_DIR . "/" . $GALLERY_ID . "/" . $GALLERY_IMAGE;
}
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Update Gallery

		</h1>
		<ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="page.php?page=list-gallery">Gallery</a></li>
			<li class="active">Update Gallery</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<!-- left column -->
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
			<div class="col-md-2">
			</div>
			<div class="col-md-8">
				<!-- general form elements -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Update Gallery</h3>
					</div>
					<!-- /.box-header -->
					<!-- form start -->

					<div class="box-body">
						<form role="form" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
							<input type="hidden" name="gallery_id" value="<?= $GALLERY_ID ?>" />
							<input type="hidden" name="type" value="<?= $GALLERY_TYPE ?>" />

							<div class="form-group <?= (isset($errors['title'])) ? 'has-error' : '' ?>">
								<label for="inputEmail" class="control-label col-xs-3">Title</label>
								<div class="col-xs-6">
									<input type="text" name="title" class="form-control" id="title" placeholder="Title" value="<?= isset($_POST['title']) ? $_POST['title'] : $GALLERY_TITLE ?>" />
									<span class="help-block"><?= (isset($errors['title'])) ? $errors['title'] : '' ?></span>
								</div>

							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-xs-3">Description</label>
								<div class="col-xs-6">
									<textarea name="description" class="form-control" id="description"><?= isset($_POST['description']) ? $_POST['description'] : $GALLERY_DESC ?></textarea>
								</div>
								<p id="title_err" style="color:#f00;"></p>
							</div>

							<div class="form-group <?= (isset($errors['event_imgs'])) ? 'has-error' : '' ?>">
								<label for="inputEmail" class="control-label col-xs-3">Files (*)</label>
								<div class="col-xs-9 col-md-9">
									<input type="file" name="event_imgs[]" id="event_imgs" multiple />
									<span class="help-block"><?= (isset($errors['event_imgs'])) ? $errors['event_imgs'] : '' ?></span><!--(jpg,  png, gif, pdf, cdr format only)-->
									<br><br>

									<?php
									$imgres = $db->list_gallery_files_all($GALLERY_ID, '');
									$html = '';
									if ($imgres != '') {
										while ($img = $imgres->fetch_assoc()) {
											$GALLERY_FILE_ID 	= $img['GALLERY_FILE_ID'];
											$FILE_NAME 			= $img['FILE_NAME'];
											$FILE_MIME 			= $img['FILE_MIME'];
											$ACTIVE 			= $img['ACTIVE'];
											$GALLERY_TYPE 			= $img['GALLERY_TYPE'];
											$path = GALLERY;
											if ($GALLERY_TYPE == 'marketing')
												$path = '../uploads/marketing';

											//$filePath = $path.'/'.$GALLERY_ID.'/thumb/'.$FILE_NAME;
											$fileLink = $path . '/' . $GALLERY_ID . '/' . $FILE_NAME;

											$file_ico = $access->book_icon($FILE_MIME);


											$html .= '<div class="col-sm-2 thumbnail" style="margin:5px;" id="gallery-file-id-' . $GALLERY_FILE_ID . '"><a href="javascript:void(0)" title= "Delete File" onclick="deleteGalleryFile(' . $GALLERY_FILE_ID . ',' . $GALLERY_ID . ')" class="delete-icon"><i class="fa fa-trash-o"></i></a>
												&nbsp;&nbsp;&nbsp;<a href="' . $fileLink . '" download target="_blank" title="View File"><i class="fa fa-eye"></i></a>
												<a href="' . $fileLink . '" download target="_blank"><br>
												<i class="fa ' . $file_ico . ' fa-4x"></i> 
												</a></div>';
										}
									}
									echo $html;
									?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-xs-3">Status</label>
								<?php $status =  isset($_POST['description']) ? $_POST['description'] : $ACTIVE; ?>
								<div>
									<label class="radio-inline">
										<input type="radio" name="status" id="status" value="1" <?= ($status == 1) ? 'checked="checked"' : '' ?> />Active
									</label>
									<label class="radio-inline">
										<input type="radio" name="status" id="status2" value="0" <?= ($status == 0) ? 'checked="checked"' : '' ?> />Inactive
									</label>
								</div>
							</div>

							<!-- /.box-body -->

							<div class="box-footer text-center">
								<input type="submit" class="btn btn-primary" name="update_gallery" value="Update" /> &nbsp;&nbsp;&nbsp;
								<a href="page.php?page=list-gallery&type=<?= $type ?>" class="btn btn-warning" title="Cancel">Cancel</a>

							</div>
						</form>
					</div>
					<!-- /.box -->


					<!-- /.box -->

					<!-- /.box -->

					<!-- /.box -->

				</div>

				<!--/.col (left) -->

				<!--/.col (right) -->
			</div>
			<!-- /.row -->
	</section>
	<!-- /.content -->
</div>