<?php
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

if ($user_role == 3) {
	$institute_id = $db->get_parent_id($user_role, $user_id);
	$staff_id = $user_id;
} else {
	$institute_id = $user_id;
	$staff_id = 0;
}
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
	$result = $expense->addexpensesubtype();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		$enquiry_id = isset($result['expensesubtype_id']) ? $result['expensesubtype_id'] : '';

		header('location:page.php?page=listSubExpenseCategory');
	}
}

?>

<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Expense Subcategory Type</h4>
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
									<select class="form-control" name="expensetype" id="expensetype">
										<?php
										$expensetype = isset($_POST['expensetype']) ? $_POST['expensetype'] : '';
										echo $db->MenuItemsDropdown('expense_category', 'CATEGORY_ID', 'CATEGORY', 'CATEGORY_ID,CATEGORY', $expensetype, " WHERE ACTIVE=1 AND DELETE_FLAG=0 AND INSTITUTE_ID = $institute_id");
										?>
									</select>
								</div>
								<div class="form-group col-sm-6">
									<label for="category">Expense Sub Type</label>
									<input type="text" name="expensesubtype" class="form-control" id="expensesubtype" placeholder="Expense Sub Type">
								</div>
							</div>

							<div class="box-footer text-center">
								<input type="submit" class="btn btn-primary" name="register" value="Save Expense Type" />
								&nbsp;&nbsp;&nbsp;

								<a href="page.php?page=listSubExpenseCategory" class="btn btn-danger" title="Cancel">Cancel</a>
								&nbsp;&nbsp;&nbsp;
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>