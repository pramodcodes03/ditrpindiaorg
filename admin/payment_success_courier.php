<?php
//print_r($_REQUEST);
if (!$_SESSION) {
	session_start();
}
if (isset($_POST["udf3"])) {
	$udf3  =  isset($_POST["udf3"]) ? $_POST["udf3"] : '';
	$udf4  =  isset($_POST["udf4"]) ? $_POST["udf4"] : ''; // user_role."/".ip_address."/".user_fullname."/".user_login_id."/".user_photo
	$split = explode("$", $udf4);
	$_SESSION['user_id'] = $udf3;
	$_SESSION['user_role'] = $split[0];
	$_SESSION['ip_address'] = $split[1];
	$_SESSION['user_name'] = $split[2];
	$_SESSION['user_login_id'] = $split[3];
	$_SESSION['user_photo'] = $split[4];
	$_SESSION['user_fullname'] = $split[2];
}


include('include/common/html_header.php');

if (!isset($_SESSION['user_login_id'])) {
	//error_log(print_r("no session found", true));
	header('location:login.php');
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
$ip_address = isset($_SESSION['ip_address']) ? $_SESSION['ip_address'] : '';
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';


//if user is institute staff
if ($user_role == 5) {
	$user_id = $db->get_parent_id($user_role, $user_id);
	$user_role = 2;
}
//if user is admin staff
if ($user_role == 6) {
	$user_id = $db->get_parent_id($user_role, $user_id);
	$user_role = 1;
}
?>

<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		<?php
		include('include/common/nav_top.php');
		//include('include/common/nav_left.php'); 
		?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Pay Online

				</h1>
				<ol class="breadcrumb">
					<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
					<li><a href="wallet">Wallet</a></li>
					<li class="active">Add Money</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Payment Success</h3>
							</div>
							<div class="box-body">
								<?php
								$mihpayid = isset($_REQUEST['mihpayid']) ? $_REQUEST['mihpayid'] : '';
								$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
								$unmappedstatus = isset($_REQUEST['unmappedstatus']) ? $_REQUEST['unmappedstatus'] : '';
								$addedon = isset($_REQUEST['addedon']) ? $_REQUEST['addedon'] : '';
								$PG_TYPE = isset($_REQUEST['PG_TYPE']) ? $_REQUEST['PG_TYPE'] : '';
								$bank_ref_num = isset($_REQUEST['bank_ref_num']) ? $_REQUEST['bank_ref_num'] : '';
								$bankcode = isset($_REQUEST['bankcode']) ? $_REQUEST['bankcode'] : '';
								$error = isset($_REQUEST['error']) ? $_REQUEST['error'] : '';
								$error_Message = isset($_REQUEST['error_Message']) ? $_REQUEST['error_Message'] : '';
								$payuMoneyId = isset($_REQUEST['payuMoneyId']) ? $_REQUEST['payuMoneyId'] : '';



								$status = isset($_POST["status"]) ? $_POST["status"] : '';
								$firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : '';

								//amount with gst
								$amount = isset($_POST["amount"]) ? $_POST["amount"] : '';

								$txnid = isset($_POST["txnid"]) ? $_POST["txnid"] : '';
								$posted_hash = isset($_POST["hash"]) ? $_POST["hash"] : '';
								$key = isset($_POST["key"]) ? $_POST["key"] : '';
								$productinfo = isset($_POST["productinfo"]) ? $_POST["productinfo"] : '';
								$email = isset($_POST["email"]) ? $_POST["email"] : '';
								$phone = isset($_POST["phone"]) ? $_POST["phone"] : '';

								$udf2  =  isset($_POST["udf2"]) ? $_POST["udf2"] : '';

								//base amount
								$udf1  =  isset($_POST["udf1"]) ? $_POST["udf1"] : '';


								$udf3  =  isset($_POST["udf3"]) ? $_POST["udf3"] : '';
								$udf4  =  isset($_POST["udf4"]) ? $_POST["udf4"] : '';
								$udf5  =  isset($_POST["udf5"]) ? $_POST["udf5"] : '';
								$udf6  =  isset($_POST["udf6"]) ? $_POST["udf6"] : '';
								$udf7  =  isset($_POST["udf7"]) ? $_POST["udf7"] : '';
								$udf8  =  isset($_POST["udf8"]) ? $_POST["udf8"] : '';
								//error_log(print_r($_POST, true));
								/*udf1 ->amount
udf2 ->gst*/


								$salt = SALT;

								if (isset($_POST["additionalCharges"])) {
									$additionalCharges = $_POST["additionalCharges"];
									$retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||' . $udf8 . '|' . $udf7 . '|' . $udf6 . '|' . $udf5 . '|' . $udf4 . '|' . $udf3 . '|' . $udf2 . '|' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
								} else {

									$retHashSeq = $salt . '|' . $status . '|||' . $udf8 . '|' . $udf7 . '|' . $udf6 . '|' . $udf5 . '|' . $udf4 . '|' . $udf3 . '|' . $udf2 . '|' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
								}
								$hash = hash("sha512", $retHashSeq);

								if ($hash != $posted_hash) {
								?>

									<div class="alert alert-danger alert-dismissible" id="messages">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
										<h4><i class="icon fa fa-check"></i> Invalid Transaction. Please try again!</h4>
										<a href="pay-online" class="btn btn-link">Click to Try Again</a>

									</div>
								<?php
								} else {
								?>
									<div class="alert alert-success alert-dismissible" id="messages">
										<h3>Thank You. Your order status is <?php echo $status; ?>".</h3>
										<h4>Your Transaction ID for this transaction is <?php echo $txnid; ?>.</h4>
										<h4>We have received a payment of Rs. <?php echo $amount; ?>". Your wallet has been charged.</h4>

									</div>

								<?php

									$resp_arr = isset($_REQUEST) ? json_encode($_REQUEST) : '';
									$sql = "INSERT INTO online_payments (PAYMENT_ID,TRANSACTION_NO,RESPONSE_ID,RESPONSE_PAYMENT_ID,USER_ID,USER_ROLE,USER_FULLNAME,USER_EMAIL,USER_MOBILE,TRANSACTION_ID,PAYMENT_AMOUNT,PAYMENT_MODE,PAYMENT_DATE,PAYMENT_STATUS,PAYMENT_ERROR,PAYMENT_ERROR_MESSAGE,PAYMENT_BANK_REF_NUM,PAYMENT_BANK_CODE,PAYMENT_GATEWAY_TYPE,RESPONSE_ARRAY,GST,TOTAL_AMOUNT,ACTIVE,CREATED_BY,CREATED_ON,CREATED_BY_IP,COURIER_WALLET_PAYMENT)
		 VALUES(NULL, get_payment_transaction_id_admin(),'$mihpayid','$payuMoneyId','$user_id','$user_role','$firstname','$email','$phone','$txnid','$udf1','$mode','$addedon','$status','$error','$error_Message','$bank_ref_num','$bankcode','$PG_TYPE','$resp_arr','$udf2','$amount','1','$user_name',NOW(),'$ip_address','1')";
									$res = $db->execQuery($sql);
									$PAYMENT_ID = $db->last_id();
									//update the wallet amount of the user

									if ($res) {
										if ($status == 'success') {
											$sql = "SELECT WALLET_ID FROM courier_wallet WHERE USER_ID='$user_id' AND USER_ROLE='$user_role' AND ACTIVE=1 AND DELETE_FLAG=0";
											$res = $db->execQuery($sql);
											if ($res && $res->num_rows > 0) {
												$data = $res->fetch_assoc();
												$WALLET_ID = $data['WALLET_ID'];
												$updSql = "UPDATE courier_wallet SET TOTAL_BALANCE = TOTAL_BALANCE + $udf1,  UPDATED_BY='$user_name', UPDATED_ON=NOW(), UPDATED_ON_IP='$ip_address' WHERE WALLET_ID='$WALLET_ID'";
												$exsql = $db->execQuery($updSql);
											} else {
												$insSql = "INSERT INTO courier_wallet (WALLET_ID,USER_ID,USER_ROLE,TOTAL_BALANCE, CREATED_BY,CREATED_ON,CREATED_ON_IP) 
        				 VALUES(NULL, '$user_id', '$user_role', '$udf1', '$user_name', NOW(), '$ip_address' )";
												$exsql = $db->execQuery($insSql);
												$WALLET_ID = $db->last_id();
											}

											$sql = "UPDATE online_payments SET COURIER_WALLET_ID='$WALLET_ID' WHERE PAYMENT_ID='$PAYMENT_ID'";
											$res = $db->execQuery($sql);
										}
									}
								}

								?>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>

		<?php
		include('include/common/footer.php');

		?>
	</div>
	<!-- ./wrapper -->
</body>

</html>