<?php
include '../common/library.php';
include '../common/constant.php';

 error_log(print_r('make offline',true));
// $_POST['user_id']='1';
// $_POST['test_id']='2';
// $_POST["payment_id"]='razorpay12344';
if(isset($_POST['user_id']) && !empty($_POST['user_id']) &&
isset($_POST['test_id']) && !empty($_POST['test_id']) &&
isset($_POST["payment_id"]) && !empty($_POST["payment_id"]))
{
    $user_id = $_POST['user_id']; 
    $test_id = $_POST['test_id'];
    $payment_id = $_POST['payment_id'];
    $payment_date = CURRENTTIME;
    $wallet_balance = $obj->select("walletBalance", "users", "ID='$user_id'")[0][0];

    $username = $obj->select("username", "users", "ID='$user_id'")[0][0];
    
    $get_tests = $obj->select("test_name ","tests","ID='$test_id'");    
    $test_name =$get_tests[0][0];
    
    $remark_msg = "Test Purchased - ".$test_name;

    $is_deduction = $obj->select("deducted_amount","subscription","test_id='$test_id' AND user_id='$user_id' AND is_wallet='1'");
    if (is_array($is_deduction)) {
        $amount = $is_deduction[0][0];
        $balanceAmount = $wallet_balance - $amount;
        $obj->execute("UPDATE users set walletBalance='$balanceAmount' where ID='$user_id'");

         //add the same entry in wallet history
        $obj->insert("wallet_history","user_id, credit_amount, debit_amount, payment_date, remark",
        "'$user_id','0','$amount','$payment_date','$remark_msg'");
             
    }
    
    //send sms to center
    $center_contact=$obj->select("mobile","franchise_registration","ID='$payment_id'")[0][0];
    $center_msg=urlencode("Dear Center, one student ".$user_id." has opted for offline payment in your center. Kindly check the offline payment tab in the Center Panel. From DITRP");
    
    $api_url=file_get_contents("https://136.243.176.144/domestic/sendsms/bulksms.php?username=ditrpi&password=Ditrpi22&type=TEXT&sender=DITRPI&entityId=1201159403063881643&templateId=1207161943658140545&mobile=".$center_contact."&message=Dear%20Center,%20one%20student%20".$username."%20has%20opted%20for%20offline%20payment%20in%20your%20center.%20Kindly%20check%20the%20offline%20payment%20tab%20in%20the%20Center%20Panel.%20From%20DITRP");
    
    error_log(print_r("https://136.243.176.144/domestic/sendsms/bulksms.php?username=ditrpi&password=Ditrpi22&type=TEXT&sender=DITRPI&entityId=1201159403063881643&templateId=1207161943658140545&mobile=".$center_contact."&message=Dear%20Center,%20".$username."%20has%20opted%20for%20offline%20payment%20in%20your%20center.%20Kindly%20check%20the%20offline%20payment%20tab%20in%20the%20Center%20Panel.%20From%20DITRP",true));
    
    $obj->execute("UPDATE subscription SET payment_status='1',payment_id='$payment_id',payment_date='$payment_date' WHERE test_id='$test_id' AND user_id='$user_id'");

    $data['response'] = "y";
    $data['error'] = false;
    $data['message'] = "Test purchased successfully";
    echo json_encode($data);
    
}
else
{
    $data["response"] = 'n';
    $data['error'] = true;
    $data['message'] = "All field required";
    echo json_encode($data);
}
?>