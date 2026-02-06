<?php

include_once('include/classes/student.class.php');
$student = new student();

//require_once('include/classes/RechPayChecksum.php');

$user_login_id = isset($_SESSION['user_login_id']) ? $_SESSION['user_login_id'] : '';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
$user_fullname = isset($_SESSION['user_fullname']) ? $_SESSION['user_fullname'] : '';
$ip_address = isset($_SESSION['ip_address']) ? $_SESSION['ip_address'] : '';
$user_photo = isset($_SESSION['user_photo']) ? $_SESSION['user_photo'] : '';

$sql = "SELECT B.WALLET_ID, A.STUDENT_ID, A.STUDENT_FNAME, A.STUDENT_LNAME, A.STUDENT_EMAIL,A.STUDENT_MOBILE, A.INSTITUTE_ID FROM student_details A LEFT JOIN wallet B ON A.STUDENT_ID = B.USER_ID WHERE A.STUDENT_ID='$user_id' AND B.USER_ROLE = 4";

$STUDENT_FNAME = '';
$STUDENT_LNAME = '';
$STUDENT_EMAIL = '';
$STUDENT_MOBILE = '';
$fullname = '';
$res = $db->execQuery($sql);
if ($res && $res->num_rows == 1) {
  $data = $res->fetch_assoc();
  extract($data);
  $STUDENT_FNAME   = $data['STUDENT_FNAME'];
  $STUDENT_LNAME   = $data['STUDENT_LNAME'];
  $STUDENT_EMAIL   = $data['STUDENT_EMAIL'];
  $STUDENT_MOBILE   = $data['STUDENT_MOBILE'];
  $fullname = $STUDENT_FNAME . ' ' . $STUDENT_LNAME;
}

//$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
$orderId = "RTXN" . time();
?>

<div class="content-wrapper">
  <div class="col-lg-6 stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title"> Recharge Your Wallet </h4>
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

        <div class="box">
          <div class="box-body">
            <div class="row">
              <form class="form-horizontal form-validate" action="txnProcess.php" method="post">
                <div class="col-md-12">
                  <!-- general form elements -->
                  <div class="box box-primary">
                    <div class="box-body">

                      <input type="hidden" id="studId" name="studId" value="<?= $STUDENT_ID ?>" readonly>
                      <input type="hidden" id="instId" name="instId" value="<?= $INSTITUTE_ID ?>" readonly>
                      <input type="hidden" id="orderId" name="orderId" value="<?= $orderId ?>" readonly>
                      <input type="hidden" id="walletId" name="walletId" value="<?= $WALLET_ID ?>" readonly>
                      <input type="hidden" id="txnNote" name="txnNote" value="UPI Payment" readonly>

                      <div class="row">
                        <div class="col-md-12 form-group">
                          <label>Enter Amount</label>
                          <input class="form-control" id="udf1" name="udf1" placeholder="Enter amount to pay" value="<?= isset($_POST['udf1']) ? $_POST['udf1'] : '' ?>" type="text" autocomplete="off" required onkeyup="getgstdetails()">
                        </div>
                        <div class="col-md-12  form-group">
                          <label>Name</label>
                          <input class="form-control" id="firstname" name="firstname" placeholder="Enter user name" value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : $fullname ?>" readonly="readonly" autocomplete="off" type="text">
                        </div>
                        <div class="col-md-12  form-group">
                          <label>Email</label>
                          <input class="form-control" id="cust_Email" name="cust_Email" placeholder="Enter email address" value="<?= isset($_POST['email']) ? $_POST['email'] : $STUDENT_EMAIL ?>" autocomplete="off" readonly="readonly" type="email">
                        </div>
                        <div class="col-md-12 form-group">
                          <label>Mobile</label>
                          <input class="form-control" id="cust_Mobile" name="cust_Mobile" placeholder="Enter mobile number" value="<?= isset($_POST['phone']) ? $_POST['phone'] : $STUDENT_MOBILE ?>" autocomplete="off" readonly="readonly" type="text">
                        </div>

                        <div class="col-md-12 form-group">
                          <label>Total Payable Amount</label>
                          <input class="form-control" id="txnAmount" name="txnAmount" placeholder="Total Amount" value="<?= isset($_POST['txnAmount']) ? $_POST['txnAmount'] : '' ?>" type="text" autocomplete="off" required readonly>
                        </div>
                      </div>
                    </div>
                    <div class="box-footer text-center">
                      <a href="page.php?page=Wallet" class="btn btn-danger btn1">Cancel</a>
                      <input type="submit" value="paymoney" name="paymoney" class="btn btn-primary btn1" />

                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>