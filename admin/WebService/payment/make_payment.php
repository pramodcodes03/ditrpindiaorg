 <?php
include '../common/library.php';
include '../common/constant.php';

$section_array = array();

// $_POST['user_id']='17';
// $_POST['test_id']='11';
// $_POST['wallet']='false';
// $_POST['amount']='200';
// $_POST['type']='Offline';

// error_log(print_r('== Use wallet ===',true));
error_log(print_r($_POST,true));
//error_log(print_r('== Use wallet ===',true));

if(isset($_POST['user_id']) and $_POST['user_id']!='' &&
isset($_POST['test_id']) and $_POST['test_id']!='' &&
isset($_POST['type']) and $_POST['type']!='' &&
isset($_POST['wallet']) and $_POST['wallet']!='' &&
isset($_POST['amount']) and $_POST['amount']!='')
{
    $user_id = $_POST['user_id'];
    $test_id = $_POST['test_id'];
    $wallet = $_POST['wallet'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];
    $balanceAmount = "";
    $final_amount = "";
    $payment_date = CURRENTTIME;
    $random_no=rand();

    $obj->execute("DELETE FROM subscription WHERE test_id='$test_id' AND user_id='$user_id'");

    $get_tests = $obj->select("test_name,no_of_questions,duration","tests","ID='$test_id'");    
    $test_name = $get_tests[0][0];
    $no_of_qtn =  $get_tests[0][1];
    $duration = $get_tests[0][2];
    
    $center_code=random_strings(4);
    
    if($type == 'Online') 
    {
        $remark_msg = "Test Purchased - ".$test_name;
        $wallet_balance = $obj->select("walletBalance", "users", "ID='$user_id'") [0][0];
        if ($wallet == 'true')
        {
            if ($wallet_balance > 0)
            {
                if ($wallet_balance >= $amount)
                {
                    $final_amount = $wallet_balance - $amount;
                    $wallet_use = '1';
                    //update user wallet
                    $obj->execute("UPDATE users set walletBalance = '$final_amount' where ID='$user_id'");
                    $subscription_id = $obj->insert("subscription", "test_id, user_id, payment_status,payment_id, payment_date, payment_amount, is_wallet, deducted_amount,payment_type,is_purchase",
                    "'$test_id','$user_id','1','$random_no','$payment_date','$amount','$wallet_use','$amount','Wallet','1'");
    
                    //add the same entry in wallet history
                    $obj->insert("wallet_history","user_id, credit_amount, debit_amount, payment_date, remark","'$user_id','0','$amount','$payment_date','$remark_msg'");
    
                     //add demo test entry in demo_test_details table
                     
                    for($i=1;$i<=20;$i++)
                    {
                        $demo_test_name = 'DEMO '.$i;
                        $insert = $obj->insert("demo_test_details","`subscription_id`, `demo_no`, `demo_test_name`,`no_of_qtn`,`test_time`",
                                      "'$subscription_id','$i','$demo_test_name','20','$duration'");
                    }
                     
                    $data["response"] = 'y';
                    $data['error'] = false;
                    $data['message'] = "Test purchased successfully. Amount deducted from wallet";
                    $data['dialog'] = "yes";
                    $data['amount'] = "0";
                    $data['type'] = $type;
                    echo json_encode($data);
                }
                else
                {
                    $final_amount = $amount - $wallet_balance;
                    $subscription_id = $obj->insert("subscription", "test_id, user_id, payment_status, payment_date, payment_amount, is_wallet, deducted_amount,payment_type", 
                    "'$test_id','$user_id','0','$payment_date','$amount','1','$wallet_balance','online'");
                     
                      
                    for($i=1;$i<=20;$i++)
                    {
                        $demo_test_name = 'DEMO '.$i;
                        $insert = $obj->insert("demo_test_details","`subscription_id`, `demo_no`,  `demo_test_name`,`no_of_qtn`,`test_time`",
                                      "'$subscription_id','$i','$demo_test_name','20','$duration'");
                    }
                      
                    $data["response"] = 'y';
                    $data['error'] = false;
                    $data['message'] = "Remaining amount is &#8377;" . $final_amount . " Pay Online.";
                    $data['dialog'] = "no";
                    $data['amount'] = $final_amount;
                    $data['type'] = $type;
                    echo json_encode($data);
                }
            }
            else
            {
                $subscription_id = $obj->insert("subscription", "test_id, user_id, payment_status, payment_amount, is_wallet,deducted_amount,payment_type,is_purchase", "'$test_id','$user_id','0','$amount','0','$amount','online','0'");
                  
                for($i=1;$i<=20;$i++)
                {
                    $demo_test_name = 'DEMO '.$i;
                    $insert = $obj->insert("demo_test_details","`subscription_id`, `demo_no`,  `demo_test_name`,`no_of_qtn`,`test_time`",
                                  "'$subscription_id','$i','$demo_test_name','20','$duration'");
                }
                     
                $data["response"] = 'y';
                $data['error'] = false;
                $data['message'] = "Remaining amount is &#8377;" . $amount . "Pay Online.";
                $data['dialog'] = "no";
                $data['amount'] = $amount;
                $data['type'] = $type;
                echo json_encode($data);
                // $data["response"] = 'n';
                // $data['error'] = true;
                // $data['message'] = "Don't have sufficient balance in wallet";
                // echo json_encode($data);
            }
        }
        else{
               
                $subscription_id = $obj->insert("subscription", "test_id, user_id, payment_status, payment_amount, is_wallet,deducted_amount,payment_type,is_purchase", "'$test_id','$user_id','0','$amount','0','$amount','online','1'");
                  
                for($i=1;$i<=20;$i++)
                {
                    $demo_test_name = 'DEMO '.$i;
                    $insert = $obj->insert("demo_test_details","`subscription_id`, `demo_no`,  `demo_test_name`,`no_of_qtn`,`test_time`",
                                  "'$subscription_id','$i','$demo_test_name','20','$duration'");
                }
                     
                $data["response"] = 'y';
                $data['error'] = false;
                $data['message'] = "Remaining amount is &#8377;" . $amount . "Pay Online.";
                $data['dialog'] = "no";
                $data['amount'] = $amount;
                $data['type'] = $type;
                echo json_encode($data);
        }
    }
    else if($type =='Offline')
    {
        //error_log(print_r('Offline',true));
         
        //write the code of cash payment
        $remark_msg = "Test Purchased - ".$test_name;
        $wallet_balance = $obj->select("walletBalance", "users", "ID='$user_id'") [0][0];
        if ($wallet == 'true')
        {
            //echo "hii";
            
            if ($wallet_balance > 0)
            {
                if ($wallet_balance >= $amount)
                {
                    $final_amount = $wallet_balance - $amount;
                    $wallet_use = '1';
                    //update user wallet
                    $obj->execute("UPDATE users set walletBalance = '$final_amount' where ID='$user_id'");
                    $subscription_id = $obj->insert("subscription", "test_id, user_id, payment_status,payment_id, payment_date, payment_amount, is_wallet, deducted_amount,payment_type,is_purchase",
                    "'$test_id','$user_id','1','$random_no','$payment_date','$amount','$wallet_use','$amount','Wallet','1'");
    
                    //add the same entry in wallet history
                    $obj->insert("wallet_history","user_id, credit_amount, debit_amount, payment_date, remark","'$user_id','0','$amount','$payment_date','$remark_msg'");
    
                    //add demo test entry in demo_test_details table
                     
                    for($i=1;$i<=20;$i++)
                    {
                        $demo_test_name = 'DEMO '.$i;
                        $insert = $obj->insert("demo_test_details","`subscription_id`, `demo_no`,  `demo_test_name`,`no_of_qtn`,`test_time`",
                                      "'$subscription_id','$i','$demo_test_name','20','$duration'");
                    }
                     
                    $data["response"] = 'y';
                    $data['error'] = false;
                    $data['message'] = "Test purchased successfully. Amount deducted from wallet";
                    $data['dialog'] = "yes";
                    $data['subscription_id']=$subscription_id;
                    $data['amount'] = "0";
                    $data['type'] = $type;
                    echo json_encode($data);
                }
                else
                {
                    $final_amount = $amount - $wallet_balance;
                    $subscription_id = $obj->insert("subscription", "test_id, user_id, payment_status, payment_date, payment_amount, is_wallet, deducted_amount,payment_type", 
                    "'$test_id','$user_id','0','$payment_date','$amount','1','$wallet_balance','offline'");
                     
                      
                    for($i=1;$i<=20;$i++)
                    {
                        $demo_test_name = 'DEMO '.$i;
                        $insert = $obj->insert("demo_test_details","`subscription_id`, `demo_no`,  `demo_test_name`,`no_of_qtn`,`test_time`",
                                      "'$subscription_id','$i','$demo_test_name','20','$duration'");
                    }
                      
                    $data["response"] = 'y';
                    $data['error'] = false;
                    $data['message'] = "Remaining amount is &#8377;" . $final_amount . " pay to the center.";
                    $data['dialog'] = "no";
                    $data['subscription_id']=$subscription_id;
                    $data['amount'] = $final_amount;
                    $data['type'] = $type;
                    echo json_encode($data);
                }
            }
            else
            {
                //echo "else";
                //error_log(print_r("subscription", "test_id, user_id, payment_status, payment_amount, is_wallet, deducted_amount,payment_type", "'$test_id','$user_id','0','$amount','0','offline'",true));
                    
                $subscription_id = $obj->insert("subscription", "test_id, user_id, payment_status, payment_amount, is_wallet, deducted_amount,payment_type", "'$test_id','$user_id','0','$amount','0','$amount','offline'");
                for($i=1;$i<=20;$i++)
                {
                    $demo_test_name = 'DEMO '.$i;
                    $insert = $obj->insert("demo_test_details","`subscription_id`, `demo_no`,  `demo_test_name`,`no_of_qtn`,`test_time`",
                                  "'$subscription_id','$i','$demo_test_name','20','$duration'");
                }
                $data["response"] = 'y';
                $data['error'] = false;
                $data['message'] = "Remaining amount is " . $amount . " pay to the center.";
                $data['dialog'] = "no";
                $data['subscription_id']=$subscription_id;
                $data['amount'] = $amount;
                $data['type'] = $type;
                echo json_encode($data);
                // $data["response"] = 'n';
                // $data['error'] = true;
                // $data['message'] = "Don't have sufficient balance in wallet";
                // echo json_encode($data);
            }
        }
        else
        {
            
            //error_log(print_r("subscription", "test_id, user_id, payment_status, payment_amount, is_wallet, deducted_amount,payment_type", "'$test_id','$user_id','0','$amount','0','$amount','offline'",true));
            
            $subscription_id = $obj->insert("subscription", "test_id, user_id, payment_status, payment_amount, is_wallet, deducted_amount,payment_type", "'$test_id','$user_id','0','$amount','0','$amount','offline'");
            for($i=1;$i<=20;$i++)
            {
                $demo_test_name = 'DEMO '.$i;
                $insert = $obj->insert("demo_test_details","`subscription_id`, `demo_no`,  `demo_test_name`,`no_of_qtn`,`test_time`",
                              "'$subscription_id','$i','$demo_test_name','20','$duration'");
            }
            $data["response"] = 'y';
            $data['error'] = false;
            $data['message'] = "Remaining amount is &#8377;" . $amount . "pay to the center.";
            $data['dialog'] = "no";
            $data['subscription_id']=$subscription_id;
            $data['amount'] = $amount;
            $data['type'] = $type;
            echo json_encode($data);
        }
    }
    
}
else
{
    $data["response"] = 'n';
    $data['error'] = true;
    $data['message'] = "All field required";
    echo json_encode($data);
}

function random_strings($length_of_string) 
{ 
  
    // String of all alphanumeric character 
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
  
    // Shufle the $str_result and returns substring 
    // of specified length 
    return substr(str_shuffle($str_result), 0, $length_of_string); 
} 
?>