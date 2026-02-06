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

include_once('include/controller/admin/expense/update_expense.php');
?>
<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Update Expense</h4>
					<form class="forms-sample" action="" method="post">
						<?php
						if (isset($success)) {
						?>
							<div class="row">
								<div class="col-sm-12">
									<div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
										<h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
										<?= isset($message) ? $message : 'Please correct the errors.'; ?>
										<?php
										echo "<ul>";
										foreach ($errors as $error) {
											echo "<li>$error</li>";
										}
										echo "<ul>";
										?>
									</div>
								</div>
							</div>
						<?php
						}
						?>
						<input type="hidden" class="form-control" name="expense_id" placeholder="expense_id" value="<?= $expense_id ?>">

						<div class="card" style="border:1px solid #e3e3e3;width: 18rem;float:right;">
							<div class="card-header">
								Cash Current Status
							</div>
							<div class="card-body">
								<?php
								include_once('include/classes/student.class.php');
								$student 	  = new student();
								$TOTAL_FEES_PAID1 = $student->get_allpaidfeestotal($institute_id);
								$total_expense = $db->get_totalexpenses($institute_id);
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
									echo $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : $CATEGORY;
									echo $db->MenuItemsDropdown('expense_category', 'CATEGORY_ID', 'CATEGORY', 'CATEGORY_ID,CATEGORY', $category_id, " WHERE ACTIVE=1 AND DELETE_FLAG=0 AND INSTITUTE_ID = $institute_id");
									?>
								</select>

							</div>
							<div class="form-group col-sm-6">
								<label for="subcategory">Expense Sub-Type</label>
								<select class="form-control" id="subcategory_id" name="subcategory_id">
									<?php
									$subcategory_id = isset($_POST['subcategory_id']) ? $_POST['subcategory_id'] : $SUBCATEGORY;
									echo $db->MenuItemsDropdown('expense_subcategory', 'SUBCATEGORY_ID', 'SUBCATEGORY', 'SUBCATEGORY_ID,SUBCATEGORY', $subcategory_id, " WHERE ACTIVE=1 AND DELETE_FLAG=0 AND INSTITUTE_ID = $institute_id");
									?>
								</select>
							</div>

							<div class="form-group col-sm-6" style="width:110px;">
								<label for="sonof">Issue Name</label>
								<input type="text" name="issue_name" value="<?= isset($_POST['issue_name']) ? $_POST['issue_name'] : $ISSUE_NAME ?>" class="form-control" id="issue_name" placeholder="Issue Name">
							</div>

							<div class="form-group col-sm-6">
								<label for="name_of_person">Name of person</label>
								<input type="text" name="name_of_person" value="<?= isset($_POST['name_of_person']) ? $_POST['name_of_person'] : $NAME_OF_PERSON ?>" class="form-control" id="name_of_person" placeholder="Name of person">
							</div>

							<div class="form-group col-sm-6">
								<label for="amount">Amount</label>
								<input type="hidden" name="amountprevious" class="form-control" value="<?= isset($_POST['amountprevious']) ? $_POST['amountprevious'] : $AMOUNT ?>" id="amountprevious">

								<input type="text" name="amount" class="form-control" value="<?= isset($_POST['amount']) ? $_POST['amount'] : $AMOUNT ?>" id="amount" placeholder="Amount">
							</div>

							<div class="form-group col-sm-6">
								<label for="date">Date</label>
								<input type="date" name="edate" value="<?= isset($_POST['edate']) ? $_POST['edate'] : $EDATE ?>" class="form-control" id="edate" />
							</div>

							<div class="form-group col-sm-6">
								<label for="VNo">VNo</label>
								<input type="text" name="vno" value="<?= isset($_POST['vno']) ? $_POST['vno'] : $VNO ?>" class="form-control" id="vno" placeholder="VNo">
							</div>

							<div class="form-group col-sm-6">
								<label for="cbf">CBF No.</label>
								<input type="text" name="cbfno" value="<?= isset($_POST['cbfno']) ? $_POST['cbfno'] : $CBFNO ?>" class="form-control" id="cbfno" placeholder="CBF No">
							</div>

							<div class="form-group col-sm-6">
								<label for="remarks">Remarks</label>
								<textarea name="remarks" class="form-control" id="remarks"><?= isset($_POST['remarks']) ? $_POST['remarks'] : $REMARKS ?></textarea>
							</div>

							<div class="form-group col-sm-6">
								<label>Pay Mode</label>
								<?php
								$payment = isset($_POST['payment_mode']) ? $_POST['payment_mode'] : $PAYMENT_MODE; ?>
								<select class="form-control" id="payment_mode" name="payment_mode">
									<option class="form-control" value="">Select Pay Mode</option>
									<option class="form-control" <?php if (@$payment == 'cash') {
																		echo "selected";
																	} ?> value="cash">CASH</option>
									<option class="form-control" <?php if (@$payment == 'UPI') {
																		echo "selected";
																	} ?> value="UPI">UPI</option>
								</select>
							</div>

							<div class="form-group col-sm-6">
								<label>GST No.</label>
								<input class="form-control pull-right" value="<?= isset($_POST['gstno']) ? $_POST['gstno'] : $GSTNO ?>" name="gstno" id="gstno" type="text">
							</div>
						</div>
						<input type="submit" name="update_expense" class="btn btn-primary mr-2" value="Submit">
						<a href="page.php?page=listExpenses" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>