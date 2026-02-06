<?php
    error_reporting(0);
    include_once('include/classes/config.php');
    require_once('include/classes/RechPayChecksum.php');
    //print_r($_POST);
    $verifySignature = '';
    $array = array();
    $paramList = array();
    
    
    $secret = upisecret; // Your Secret Key.
    $status = $_POST['status']; // Its Payment Status Only, Not Txn Status.
    $message = $_POST['message']; // Txn Message.
    $cust_Mobile = $_POST['cust_Mobile']; // Txn Message.
    $cust_Email = $_POST['cust_Email']; // Txn Message.
    $hash = $_POST['hash']; // Encrypted Hash / Generated Only SUCCESS Status.
    $checksum = $_POST['checksum'];  // Checksum verifySignature / Generated Only SUCCESS Status.
    
    // Payment Status.
    if($status=="SUCCESS"){
    	
    $paramList = hash_decrypt($hash,$secret);
    $verifySignature = RechPayChecksum::verifySignature($paramList, $secret, $checksum);
    
    // Checksum verify.
    if($verifySignature){
    $array = json_decode($paramList);
    print_r($array); exit();
    // Payment Response
    //echo "<pre>";
    //echo "Payment Status: $status<br>";	
    //echo "Payment Message: $message<br>";	
    //echo "Customer Mobile: $cust_Mobile<br>";
    //echo "Customer Email: $cust_Email<br>";
    //echo "Payment hash: $hash<br>";
    //echo "Payment Checksum: $checksum<hr>";
    
    
    echo '<img src="https://media.tenor.com/HbpenGYruKEAAAAM/verifi.gif" width="300" height="250" alt="A GIF image"><hr>';
    
    
    foreach ($array as $key => $value) {
      echo "<b>$key:</b> <b style='color:green'>$value</b><hr>";
    }	
    echo '<h2><b style="color:green">Checksum Verified!</b></h2>';	
    	
    }else{
    	
    // Payment Response
    echo "<pre>";
    echo "Payment Status: $status<br>";	
    echo "Payment Message: $message<br>";		
    echo '<h2><b style="color:red">Checksum Invalid!</b></h2>';
    	
    }	
    	
    
    }


?>