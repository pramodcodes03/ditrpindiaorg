<?php
$expense_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['updateexpense']) ? $_POST['updateexpense'] : '';
include_once('include/classes/expense.class.php');
$expense = new expense();
if ($action != '') {
	$result = $expense->updateexpensetype($expense_id);
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:listExpenseCategory');
	}
}
/* get course details */
$res = $expense->list_expensestype($expense_id, '');
if ($res != '') {
	$srno = 1;
	while ($data = $res->fetch_assoc()) {
		$CATEGORY_ID 		= $data['CATEGORY_ID'];
		$CATEGORY 	= $data['CATEGORY'];
		$ACTIVE			= $data['ACTIVE'];
		$CREATED_BY 	= $data['CREATED_BY'];
		$CREATED_ON 	= $data['CREATED_ON'];
		$UPDATED_BY 	= $data['UPDATED_BY'];
		$UPDATED_ON 	= $data['UPDATED_ON'];
		$IS_MULTIPLE 	= $data['IS_MULTIPLE'];
	}
}
