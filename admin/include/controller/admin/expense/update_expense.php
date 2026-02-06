<?php
$expense_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_expense']) ? $_POST['update_expense'] : '';
include_once('include/classes/expense.class.php');
$expense = new expense();
if ($action != '') {
	$result = $expense->update_expense($expense_id);
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:listExpenses');
	}
}
/* get course details */
$res = $expense->list_expenses($expense_id, '');
if ($res != '') {
	$srno = 1;
	while ($data = $res->fetch_assoc()) {
		$EXPENSE_ID 		= $data['EXPENSE_ID'];
		$INSTITUTE_ID 	= $data['INSTITUTE_ID'];
		$CATEGORY 	= $data['CATEGORY'];
		$SUBCATEGORY = $data['SUBCATEGORY'];
		$ISSUE_NAME 	= $data['ISSUE_NAME'];
		$NAME_OF_PERSON 	= $data['NAME_OF_PERSON'];
		$AMOUNT 	= $data['AMOUNT'];
		$EDATE = $data['EDATE'];
		$VNO 	= $data['VNO'];
		$CBFNO 	= $data['CBFNO'];
		$REMARKS 	= $data['REMARKS'];
		$PAYMENT_MODE 	= $data['PAYMENT_MODE'];
		$GSTNO 	= $data['GSTNO'];
		$ACTIVE			= $data['ACTIVE'];
		$CREATED_BY 	= $data['CREATED_BY'];
		$CREATED_ON 	= $data['CREATED_ON'];
		$UPDATED_BY 	= $data['UPDATED_BY'];
		$UPDATED_ON 	= $data['UPDATED_ON'];
		$IS_MULTIPLE 	= $data['IS_MULTIPLE'];
	}
}
