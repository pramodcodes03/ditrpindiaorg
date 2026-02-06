<?php
$type = isset($_GET['type']) ? $_GET['type'] : '';
$title = ($type == 'marketing') ? 'Marketing Material' : 'Gallery';
$action = isset($_POST['add_gallery']) ? $_POST['add_gallery'] : '';

if ($action != '') {
	$result	= $db->add_gallery();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=list-gallery&type=' . $type);
	}
}
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Add New <?= $title ?>

		</h1>
		<ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#"><?= $title ?></a></li>
			<li class="active">Add <?= $title ?></li>
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
						<h3 class="box-title">Add <?= $title ?></h3>
					</div>
					<!-- /.box-header -->
					<!-- form start -->

					<div class="box-body">
						<form role="form" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
							<input type="hidden" name="type" value="<?= $type ?>" />
							<div class="form-group <?= (isset($errors['title'])) ? 'has-error' : '' ?>">
								<label for="inputEmail" class="control-label col-xs-3">Title</label>
								<div class="col-xs-6">
									<input type="text" name="title" class="form-control" id="title" placeholder="Title" value="<?= isset($_POST['title']) ? $_POST['title'] : '' ?>" />
									<span class="help-block"><?= (isset($errors['title'])) ? $errors['title'] : '' ?></span>
								</div>

							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-xs-3">Description</label>
								<div class="col-xs-6">
									<textarea name="description" class="form-control" id="description"><?= isset($_POST['description']) ? $_POST['description'] : '' ?></textarea>
								</div>
								<p id="title_err" style="color:#f00;"></p>
							</div>

							<div class="form-group <?= (isset($errors['event_imgs'])) ? 'has-error' : '' ?>">
								<label for="inputEmail" class="control-label col-xs-3">Files (*)</label>
								<div class="col-xs-6 col-md-6">
									<input type="file" name="event_imgs[]" id="event_imgs" multiple />
									<span class="help-block"><?= (isset($errors['event_imgs'])) ? $errors['event_imgs'] : '' ?></span> <!--(jpg,  png, gif, pdf, cdr format only)-->
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="control-label col-xs-3">Status</label>
								<?php $status =  isset($_POST['description']) ? $_POST['description'] : 1; ?>
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
								<input type="submit" class="btn btn-primary" name="add_gallery" value="Add" /> &nbsp;&nbsp;&nbsp;
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