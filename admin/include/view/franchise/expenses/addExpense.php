<?php
$user_id = $_SESSION['user_id'];

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
	$result = $expense->add_expense();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		$enquiry_id = isset($result['expense_id']) ? $result['expense_id'] : '';

		header('location:page.php?page=listExpenses');
	}
}
?>

<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Expenses</h4>
					<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_expenses">
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
							<div class="card" style="border:1px solid #e3e3e3;width: 18rem;float:right;">
								<div class="card-header">
									Cash Current Status
								</div>
								<div class="card-body">
									<?php
									include_once('include/classes/student.class.php');
									$student 	  = new student();
									$TOTAL_FEES_PAID1 = $student->get_allpaidfeestotal($user_id);
									$total_expense = $db->get_totalexpenses($user_id);
									$inst_wallet = $TOTAL_FEES_PAID1 - $total_expense;
									?>
									<p class="card-text">Wallet Amount : INR <?= $inst_wallet ?> </p>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-6">
									<label for="category">Expense Type</label>
									<select onchange="dispSubexpense(this.value)" class="form-control" name="category_id" id="category_id">
										<?php
										$category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
										echo $db->MenuItemsDropdown('expense_category', 'CATEGORY_ID', 'CATEGORY', 'CATEGORY_ID,CATEGORY', $category_id, " WHERE ACTIVE=1 AND DELETE_FLAG=0 AND INSTITUTE_ID = $user_id");
										?>
									</select>
								</div>
								<div class="form-group col-sm-6">
									<label for="subcategory">Expense Sub-Type</label>
									<select class="form-control" id="subcategory_id" name="subcategory_id">

									</select>
								</div>

								<div class="form-group col-sm-6" style="width:110px;">
									<label for="sonof">Issue Name</label>
									<input type="text" name="issue_name" class="form-control" id="issue_name" placeholder="Issue Name">
								</div>

								<div class="form-group col-sm-6">
									<label for="name_of_person">Name of person</label>
									<input type="text" name="name_of_person" class="form-control" id="name_of_person" placeholder="Name of person">
								</div>

								<div class="form-group col-sm-6">
									<label for="amount">Amount</label>
									<input type="text" name="amount" class="form-control" id="amount" placeholder="Amount">
								</div>

								<div class="form-group col-sm-6">
									<label for="date">Date</label>
									<input type="date" name="edate" class="form-control" id="edate" max="2999-12-31" />
								</div>

								<div class="form-group col-sm-6">
									<label for="VNo">VNo</label>
									<input type="text" name="vno" class="form-control" id="vno" placeholder="VNo">
								</div>

								<div class="form-group col-sm-6">
									<label for="cbf">CBF No.</label>
									<input type="text" name="cbfno" class="form-control" id="cbfno" placeholder="CBF No">
								</div>

								<div class="form-group col-sm-6">
									<label for="remarks">Remarks</label>
									<textarea name="remarks" class="form-control" id="remarks"></textarea>
								</div>

								<div class="form-group col-sm-6">
									<label>Pay Mode</label>
									<select class="form-control" id="payment_mode" name="payment_mode">
										<option class="form-control" value="">Select Pay Mode</option>
										<option class="form-control" value="cash">CASH</option>
										<option class="form-control" value="UPI">UPI</option>
									</select>
								</div>

								<div class="form-group col-sm-6">
									<label>GST No.</label>
									<input class="form-control pull-right" name="gstno" id="gstno" type="text">
								</div>

							</div>

							<div class="box-footer text-center">
								<input type="submit" class="btn btn-primary" name="register" value="Save Expenses" />
								&nbsp;&nbsp;&nbsp;

								<a href="page.php?page=listExpenses" class="btn btn-danger" title="Cancel">Cancel</a>
								&nbsp;&nbsp;&nbsp;
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>