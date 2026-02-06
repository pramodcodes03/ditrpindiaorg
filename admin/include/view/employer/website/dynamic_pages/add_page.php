 <?php
	$page_id = isset($_GET['page_id']) ? $_GET['page_id'] : '';
	$blockno = isset($_GET['blockno']) ? $_GET['blockno'] : '';
	$delete = isset($_GET['action']) ? $_GET['action'] : '';
	$action = isset($_POST['action']) ? $_POST['action'] : '';
	if ($delete == 'delete') {

		$sqlDel = "DELETE FROM pages_dynamic WHERE BLOCK_NO='$blockno' AND PAGE_ID='$page_id'";
		$resDel = $db->execQuery($sqlDel);
		if ($resDel) {
			$_SESSION['msg'] = "Deleted successfully!";
			$_SESSION['msg_flag'] = true;
		}
		header('location:page.php?page=add-dynamic-page&page_id=' . $page_id);
	}
	if ($action == 'add') {

		//print_r($_POST);
		$result = $db->add_dynamic_page();


		$result = json_decode($result, true);
		$success = isset($result['success']) ? $result['success'] : '';
		$message = isset($result['message']) ? $result['message'] : '';
		$errors = isset($result['errors']) ? $result['errors'] : '';
		if ($success == true) {
			$_SESSION['msg'] = $message;
			$_SESSION['msg_flag'] = $success;
			header('location:page.php?page=add-dynamic-page&page_id=' . $page_id);
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
 			<li><a href="#">Pages</a></li>
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
 						<h3 class="box-title">Add New Fields</h3>
 					</div>
 					<!-- /.box-header -->
 					<!-- form start -->

 					<div class="box-body">
 						<form role="form" action="" class="form-horizontal" name="colg_detail" method="post" enctype="multipart/form-data" onsubmit="return eventFormValidation();">
 							<br>
 							<?php
								$blockno = 1;
								$sqlBlock = "SELECT MAX(BLOCK_NO)+1 as MAX_BLOCK FROM pages_dynamic WHERE PAGE_ID='$page_id'";
								$resBlock = $db->execQuery($sqlBlock);
								$dataBlock = $resBlock->fetch_assoc();
								$blockno = !empty($dataBlock['MAX_BLOCK']) ? $dataBlock['MAX_BLOCK'] : $blockno;


								?>
 							<input type="hidden" name="page_id" value="<?= $page_id ?>" />
 							<input type="hidden" name="blockno" value="<?= $blockno ?>" />

 							<table class="table table-bordered">
 								<thead>
 									<tr>
 										<th>Field</th>
 										<th>Value</th>
 									</tr>
 								</thead>
 								<tbody id="drow">
 									<?php
										$values = array("Teachers Name" => "", "Institute Name" => "", "City" => "", "State,Country" => "");
										$rowcount = count($values);
										foreach ($values as $key => $value) {
										?>
 										<tr id="row<?= $rowcount ?>">
 											<td><input type="text" name="field_name[]" class="form-control" id="field_name<?= $rowcount ?>" placeholder="Field name" value="<?= $key ?>" readonly /></td>
 											<td><input type="text" name="field_value[]" class="form-control" id="field_value<?= $rowcount ?>" placeholder="Field value" value="<?= $value ?>" /></td>
 										</tr>
 									<?php } ?>
 								</tbody>
 								<tfoot>
 									<tr>
 										<td colspan="2" align="right">
 											<input id="rowcount" value="<?= $rowcount ?>" type="hidden" />
 											<a href="javascript:void(0)" id="addMoreDynamicFields" class="btn btn-link"><i class="fa fa-plus"></i> Add More Fields</a>
 										</td>
 									</tr>
 								</tfoot>
 							</table>
 							<div class="clearfix"></div>

 							<div class="form-group <?= (isset($errors['field_image'])) ? 'has-error' : '' ?>">
 								<label for="inputEmail" class="control-label col-xs-2">Image</label>
 								<div class="col-xs-6 col-md-6">
 									<input type="file" name="field_image" id="field_image" multiple />
 									<span class="help-block"><?= (isset($errors['field_image'])) ? $errors['field_image'] : '(jpg,  png, gif, pdf, cdr format only)' ?></span>
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

 							<div class="form-group">
 								<label for="inputEmail" class="control-label col-xs-3"></label>
 								<div class="col-lg-5">
 									<input type="submit" class="btn btn-primary" value="add" name="action" />
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
 				<div class="box box-primary">
 					<div class="box-header with-border">
 						<h3 class="box-title">List Fields</h3>
 					</div>
 					<!-- /.box-header -->
 					<!-- form start -->

 					<div class="box-body">

 						<?php
							$page = isset($_GET['p']) ? $_GET['p'] : 'home';
							//get page content
							$pageres = $db->list_pages(" WHERE PAGE_ID='$page_id'");
							if ($pageres != '') {
								while ($pagedata = $pageres->fetch_assoc()) {
									$PAGE_ID = $pagedata['PAGE_ID'];
									$PAGE_NAME = $pagedata['PAGE_NAME'];
									$PAGE_DATA = $pagedata['PAGE_DATA'];
									$PAGE_LINK = $pagedata['PAGE_LINK'];
									$META_TAGS = $pagedata['META_TAGS'];
									$META_DESCRIPTION = $pagedata['META_DESCRIPTION'];
									$PAGE_TITLE = $pagedata['PAGE_TITLE'];
									$IS_DYNAMIC = $pagedata['IS_DYNAMIC'];
									if ($IS_DYNAMIC == 0)
										echo $PAGE_DATA;
									else {
										$dsql = "SELECT DISTINCT BLOCK_NO FROM pages_dynamic WHERE PAGE_ID='$PAGE_ID' ORDER BY BLOCK_NO ASC";
										$dpage = $db->execQuery($dsql);
										while ($ddata = $dpage->fetch_assoc()) {


											$BLOCK_NO = $ddata['BLOCK_NO'];

											echo '<table class="table table-bordered" style="width:50%">
					';
											$dpage1 = $db->list_dynamic_pages(" WHERE PAGE_ID='$PAGE_ID' AND BLOCK_NO='$BLOCK_NO' ");
											while ($ddata1 = $dpage1->fetch_assoc()) {
												$FIELD_NAME = $ddata1['FIELD_NAME'];
												$FIELD_VALUE = $ddata1['FIELD_VALUE'];
												$photo = '';
												if ($FIELD_NAME == 'photo')
													$photo = '../uploads/pages/' . $FIELD_VALUE;
							?>
 											<tr>
 												<?php if ($FIELD_NAME == 'photo') { ?>
 													<td rowspan="6" align="center" valign="middle" width="40%"><img src="<?= $photo ?>" class="img img-responsive" style="height:100px;" /></td>
 												<?php } else { ?>
 													<td><?= $FIELD_NAME ?></td>
 													<td><?= $FIELD_VALUE ?></td>
 												<?php } ?>
 											</tr>
 										<?php
											}
											?>
 										<tr>
 											<td colspan="3">
 												<a href="page.php?page=update-dynamic-page&page_id=<?= $PAGE_ID ?>&blockno=<?= $BLOCK_NO ?>" class="btn btn-primary">Edit</a>
 												<a href="page.php?page=add-dynamic-page&page_id=<?= $page_id ?>&blockno=<?= $BLOCK_NO ?>&action=delete" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
 											</td>
 										</tr>
 										<?php
											echo '</table><br>';

											?>



 						<?php

										}
									}
								}
							}
							?>
 					</div>

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