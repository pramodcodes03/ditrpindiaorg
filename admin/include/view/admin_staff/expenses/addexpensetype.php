<?php
$action 	= '';
$save		= isset($_POST['save']) ? $_POST['save'] : '';
$register	= isset($_POST['register']) ? $_POST['register'] : '';
if ($save != '')
	$action		= $save;
if ($register != '')
	$action		= $register;
include_once('include/classes/expense.class.php');
$expense = new expense();
if ($action != '') {
	$result = $expense->addexpensetype();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		$enquiry_id = isset($result['expensetype_id']) ? $result['expensetype_id'] : '';

		header('location:page.php?page=listExpenseCategory');
	}
}
?>

<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Expense Type</h4>
					<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_expensetype">
						<div class="box-body">
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
							<div class="row">
								<div class="form-group col-sm-6">
									<label for="category">Expense Type</label>
									<input type="text" name="expensetype" class="form-control" id="expensetype" placeholder="Expense Type">
								</div>
							</div>

							<div class="box-footer">
								<input type="submit" class="btn btn-primary btn1" name="register" value="Save Expense Type" />
								&nbsp;&nbsp;&nbsp;

								<a href="page.php?page=listExpenseCategory" class="btn btn-danger btn1" title="Cancel">Cancel</a>
								&nbsp;&nbsp;&nbsp;
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>