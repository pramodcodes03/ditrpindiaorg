 <?php



	$action = isset($_POST['action']) ? $_POST['action'] : '';

	if ($action != '') {



		$result = $db->add_page();

		$result = json_decode($result, true);

		$success = isset($result['success']) ? $result['success'] : '';

		$message = isset($result['message']) ? $result['message'] : '';

		$errors = isset($result['errors']) ? $result['errors'] : '';

		if ($success == true) {

			$_SESSION['msg'] = $message;

			$_SESSION['msg_flag'] = $success;

			header('location:page.php?page=list-pages');
		}
	}

	?>

 <div class="content-wrapper">

 	<!-- Content Header (Page header) -->

 	<section class="content-header">

 		<h1>

 			Add New Page



 		</h1>

 		<ol class="breadcrumb">

 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

 			<li><a <a href="#">Pages</a></li>

 			<li class="active">Add New Page</li>

 		</ol>

 	</section>





 	<!-- Main content -->

 	<section class="content">

 		<div class="row">

 			<!-- left column -->





 			<div class="col-md-12">

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

 				<!-- general form elements -->

 				<div class="box box-primary">

 					<div class="box-header with-border">

 						<h3 class="box-title">Add New Page</h3>

 					</div>

 					<!-- /.box-header -->

 					<!-- form start -->



 					<div class="box-body">

 						<form role="form" action="" class="form-horizontal" name="colg_detail" method="post" enctype="multipart/form-data" onsubmit="return eventFormValidation();">

 							<br>

 							<div class="form-group <?= (isset($errors['page_name'])) ? 'has-error' : '' ?>">

 								<label for="inputEmail" class="control-label col-xs-2">Page Name</label>

 								<div class="col-xs-9">

 									<input type="text" name="page_name" class="form-control" id="page_name" placeholder="Page name" />

 									<span class="help-block"><?= isset($errors['page_name']) ? $errors['page_name'] : '' ?></span>

 								</div>



 							</div>

 							<div class="form-group <?= (isset($errors['page_title'])) ? 'has-error' : '' ?>">

 								<label for="inputEmail" class="control-label col-xs-2">Title</label>

 								<div class="col-xs-9">

 									<input type="text" name="page_title" class="form-control" id="page_title" placeholder="Page title" />

 									<span class="help-block"><?= isset($errors['page_title']) ? $errors['page_title'] : '' ?></span>

 								</div>



 							</div>

 							<div class="form-group <?= (isset($errors['page_link'])) ? 'has-error' : '' ?>">

 								<label for="inputEmail" class="control-label col-xs-2">Link ID</label>

 								<div class="col-xs-9">

 									<input type="text" name="page_link" class="form-control" id="page_link" placeholder="Page Link" />

 									<span class="help-block"><?= isset($errors['page_link']) ? $errors['page_link'] : '' ?></span>

 								</div>



 							</div>



 							<div class="form-group <?= (isset($errors['meta_keys'])) ? 'has-error' : '' ?>">

 								<label for="inputEmail" class="control-label col-xs-2">Meta Keywords</label>

 								<div class="col-xs-9">

 									<input type="text" name="meta_keys" class="form-control" id="meta_keys" placeholder="Meta keywords" />

 									<span class="help-block"><?= isset($errors['meta_keys']) ? $errors['meta_keys'] : '' ?></span>

 								</div>



 							</div>

 							<div class="form-group <?= (isset($errors['meta_desc'])) ? 'has-error' : '' ?>">

 								<label for="inputEmail" class="control-label col-xs-2">Meta Description</label>

 								<div class="col-xs-9">

 									<input type="text" name="meta_desc" class="form-control" id="meta_desc" placeholder="Meta description" />

 									<span class="help-block"><?= isset($errors['meta_desc']) ? $errors['meta_desc'] : '' ?></span>

 								</div>



 							</div>

 							<div class="form-group <?= (isset($errors['page_data'])) ? 'has-error' : '' ?>">

 								<label for="inputEmail" class="control-label col-xs-2">Page Data</label>

 								<div class="col-xs-10">

 									<textarea class="form-control" name="page_data" id="page_data" style="height:300px"></textarea>

 									<script type="text/javascript">
 										CKEDITOR.replace('page_data');
 									</script>

 									<span class="help-block"><?= isset($errors['page_data']) ? $errors['page_data'] : '' ?></span>

 								</div>



 							</div>

 							<div class="form-group <?= (isset($errors['status'])) ? 'has-error' : '' ?>">

 								<label for="inputEmail" class="control-label col-xs-2">Status</label>

 								<div>

 									<label class="radio-inline">

 										<input type="radio" name="status" id="status" value="1" checked="checked" />Active

 									</label>

 									<label class="radio-inline">

 										<input type="radio" name="status" id="status2" value="0" />Inactive

 									</label>

 								</div>

 								<span class="help-block"><?= isset($errors['status']) ? $errors['status'] : '' ?></span>

 							</div>

 							<div class="form-group <?= (isset($errors['is_dynamic'])) ? 'has-error' : '' ?>">

 								<label for="inputEmail" class="control-label col-xs-2">Having Dynamic Fields</label>

 								<div>

 									<label class="radio-inline">

 										<input type="radio" name="is_dynamic" id="is_dynamic1" value="1" checked="checked" />Yes

 									</label>

 									<label class="radio-inline">

 										<input type="radio" name="is_dynamic" id="is_dynamic2" value="0" />No

 									</label>

 								</div>

 								<span class="help-block"><?= isset($errors['is_dynamic']) ? $errors['is_dynamic'] : '' ?></span>

 							</div>



 							<div class="form-group">

 								<label for="inputEmail" class="control-label col-xs-3"></label>

 								<div class="col-lg-5">

 									<input type="submit" class="btn btn-primary" value="Add" name="action" />

 									<a href="page.php?pg=list_pages" class="btn btn-danger">Cancel</a>



 								</div>



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





 </div>

 </div>

 </div>





 </div><!-- row -->

 </form>