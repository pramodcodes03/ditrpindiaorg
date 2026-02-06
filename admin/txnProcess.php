<?php
    //include_once('include/classes/config.php');
    include('include/common/html_header.php'); 
    if(!isset($_SESSION['user_login_id']))
    {
    	header('location:login.php');
    }
    
    error_reporting(1);
    require_once('include/classes/RechPayChecksum.php');
  
    $checkSum = "";
    $upiuid = "";
    $paramList = array();
    
    $gateway_type = "Robotics";
    $cust_Mobile = $_POST['cust_Mobile'];
    $cust_Email = $_POST['cust_Email'];
    $orderId = $_POST['orderId'];
    $txnAmount = $_POST['txnAmount'];
    $txnNote = $_POST['txnNote'];
    
    $studId = $_POST['studId'];
    $instId = $_POST['instId'];
    $walletId = $_POST['walletId'];
    $firstname = $_POST['firstname'];
    
    $callback_url = 'https://oscdcorporation.in/admin/payment_success.php';
    
    if($gateway_type=="Advanced"){
        
    $RECHPAY_TXN_URL='https://vlepay.in/order/payment';
    
    $upiuid = 'paytmqr1jw2ihob26@paytm'; // Its Your Self UPI ID.
    
    }else if($gateway_type=="Robotics"){
    
    $RECHPAY_TXN_URL= 'https://vlepay.in/order/paytm';
    
    //$RECHPAY_TXN_URL= 'https://vlepay.in/stage/process';
    
    $upiuid = 'paytmqr2810050501011m8kq4kqpru9@paytm'; // Its Paytm Business UPI Unique ID.
    
    $paramList["cust_Mobile"] = $cust_Mobile;
    $paramList["cust_Email"] = $cust_Email;
    
    }else if($gateway_type=="Normal"){
        
    $RECHPAY_TXN_URL='https:/vlepay.in/order/process';   
    
    $upiuid = 'paytmqr1jw2ihob26@paytm';  // Its UPI Unique ID, (Url:https://example.com/UPIsAccounts).
    
    }
    
    // Create an array having all required parameters for creating checksum.
    $paramList["upiuid"] = $upiuid;
    $paramList["token"] = '4a49bf-f5d31b-44e09b-952161-0a6f75';
    $paramList["orderId"] = $orderId ;
    $paramList["txnAmount"] = $txnAmount;
    $paramList["txnNote"] = $txnNote;
    $paramList["callback_url"] = $callback_url;
    $checkSum = RechPayChecksum::generateSignature($paramList,'p9jRa2qvYn');
?>
<html>
<head>
<title>Gateway Check Out Page</title>
</head>
<body>
	<center><h1>Please do not refresh this page...</h1></center>
		<form method="post" action="<?php echo $RECHPAY_TXN_URL ?>" name="f1">
		<table border="1">
			<tbody>
			<?php
			foreach($paramList as $name => $value) {
				echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
			}
			?>
			<input type="hidden" name="checksum" value="<?php echo $checkSum ?>">
			</tbody>
		</table>
		<script type="text/javascript">
			document.f1.submit();
		</script>
	</form>
</body>
</html>
