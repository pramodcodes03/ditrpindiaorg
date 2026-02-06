 <?php
	$page_id = isset($_GET['page_id']) ? $_GET['page_id'] : '';
	$blockno = isset($_GET['blockno']) ? $_GET['blockno'] : '';
	$action = isset($_POST['action']) ? $_POST['action'] : '';

	if ($action == 'Update') {

		//print_r($_POST);
		$result = $db->update_dynamic_page();


		$result = json_decode($result, true);
		$success = isset($result['success']) ? $result['success'] : '';
		$message = isset($result['message']) ? $result['message'] : '';
		$errors = isset($result['errors']) ? $result['errors'] : '';
		if ($success == true) {
			$_SESSION['msg'] = $message;
			$_SESSION['msg_flag'] = $success;
			header('location:/website_management/add-dynamic-page&page_id=' . $page_id);
		}
	}

	?>
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
 		<h1>
 			Update Fields

 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
 			<li><a href="#">Pages</a></li>
 			<li class="active">Update Fields</li>
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
 						<h3 class="box-title">Update Fields</h3>
 					</div>
 					<!-- /.box-header -->
 					<!-- form start -->

 					<div class="box-body">
 						<form role="form" action="" class="form-horizontal" name="colg_detail" method="post" enctype="multipart/form-data" onsubmit="return eventFormValidation();">
 							<br>

 							<input type="hidden" name="page_id" value="<?= $page_id ?>" />
 							<input type="hidden" name="blockno" value="<?= $blockno ?>" />

 							<table class="table table-bordered" style="width:100%">
 								<?php
									$dpage1 = $db->list_dynamic_pages(" WHERE PAGE_ID='$page_id' AND BLOCK_NO='$blockno' ");
									while ($ddata1 = $dpage1->fetch_assoc()) {
										$FIELD_NAME = $ddata1['FIELD_NAME'];
										$FIELD_VALUE = $ddata1['FIELD_VALUE'];
										$BLOCK_NO = $ddata1['BLOCK_NO'];
										$photo = '';
										if ($FIELD_NAME == 'photo')
											$photo = '../uploads/pages/' . $FIELD_VALUE;
									?>
 									<tr>
 										<?php if ($FIELD_NAME == 'photo') { ?>
 											<td rowspan="6" align="center" valign="middle" width="30%">
 												<img src="<?= $photo ?>" class="img img-responsive" style="height:200px;" /><br>
 												<div class="form-group <?= (isset($errors['field_image'])) ? 'has-error' : '' ?>">
 													<label for="inputEmail" class="control-label col-xs-2">Image</label>
 													<div class="col-xs-8 col-md-6">
 														<input type="file" name="field_image" id="field_image" />
 														<input type="hidden" name="field_image_name" value="<?= $FIELD_VALUE ?>" />
 														<span class="help-block"><?= (isset($errors['field_image'])) ? $errors['field_image'] : '(jpg,  png, gif format)' ?></span>
 													</div>
 												</div>

 											</td>
 										<?php } else { ?>
 											<td><input type="text" name="field_name[]" class="form-control" id="field_name<?= $rowcount ?>" placeholder="Field name" value="<?= $FIELD_NAME ?>" /></td>
 											<td><input type="text" name="field_value[]" class="form-control" id="field_value<?= $rowcount ?>" placeholder="Field value" value="<?= $FIELD_VALUE ?>" /></td>
 										<?php } ?>
 									</tr>
 								<?php
									}
									?>
 								<tr>
 									<td colspan="3">
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
 									</td>
 								</tr>
 								<tr>
 									<td colspan="3" align="center">
 										<input type="submit" class="btn btn-primary" value="Update" name="action" />
 										<a href="/website_management/add-dynamic-page&page_id=<?= $page_id ?>" class="btn btn-danger">Cancel</a>
 									</td>
 								</tr>
 							</table>
 						</form>
 					</div>
 				</div>
 			</div>
 		</div>
 	</section>
 </div>