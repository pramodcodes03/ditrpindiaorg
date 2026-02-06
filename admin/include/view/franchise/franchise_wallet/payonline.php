<?php
error_reporting(1);
//echo"<pre>";
//print_r($_SESSION); 
//exit();
$action = '';
$surl = SUCCESS_URL;
$furl = FAILURE_URL;
$curl = FAILURE_URL;

$action = '';
$posted = array();
if (!empty($_POST)) {
  //print_r($_POST);
  foreach ($_POST as $key => $value) {
    $posted[$key] = $value;
  }
}

$formError = 0;
if (empty($posted['txnid'])) {
  // Generate random transaction id
  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
} else {
  $txnid = $posted['txnid'];
}
$hash = '';
// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
error_log(print_r('Session Data', true));
error_log(print_r($_SESSION, true));

if (empty($posted['hash']) && sizeof($posted) > 0) {
  //error_log(print_r($_POST, true));
  if (
    empty($posted['key'])
    || empty($posted['txnid'])
    || empty($posted['amount'])
    || empty($posted['firstname'])
    || empty($posted['email'])
    || empty($posted['phone'])
    || empty($posted['productinfo'])
    || empty($posted['surl'])
    || empty($posted['furl'])
    || empty($posted['service_provider'])
    || empty($posted['udf1'])
    || empty($posted['udf2'])
    || empty($posted['udf3'])
    || empty($posted['udf4'])
    || empty($posted['udf5'])
    || empty($posted['udf6'])
    || empty($posted['udf7'])
    || empty($posted['udf8'])
  ) {
    $formError = 1;
  } else {
    //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
    $hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';
    foreach ($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }

    $hash_string .= SALT;


    $hash = strtolower(hash('sha512', $hash_string));
    $action = PAYU_BASE_URL . '/_payment';
  }
} elseif (!empty($posted['hash'])) {
  $hash = $posted['hash'];
  $action = PAYU_BASE_URL . '/_payment';
}


//error_log(print_r($_POST, true));
$user_login_id = isset($_SESSION['user_login_id']) ? $_SESSION['user_login_id'] : '';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
$user_fullname = isset($_SESSION['user_fullname']) ? $_SESSION['user_fullname'] : '';
$ip_address = isset($_SESSION['ip_address']) ? $_SESSION['ip_address'] : '';
$user_photo = isset($_SESSION['user_photo']) ? $_SESSION['user_photo'] : '';


if ($user_role == 5) {
  $institute_id = $db->get_parent_id($user_role, $user_id);
  $staff_id = $user_id;
} else {
  $institute_id = $user_id;
  $staff_id = 0;
}
$sql = "SELECT INSTITUTE_NAME,INSTITUTE_CODE,EMAIL,MOBILE FROM institute_details WHERE INSTITUTE_ID='$institute_id'";
$INSTITUTE_NAME = '';
$INSTITUTE_CODE = '';
$INSTITUTE_EMAIL = '';
$INSTITUTE_MOBILE = '';

$res = $db->execQuery($sql);
if ($res && $res->num_rows == 1) {
  $data = $res->fetch_assoc();
  //print_r($data);
  extract($data);
  $INSTITUTE_NAME   = $data['INSTITUTE_NAME'];
  $INSTITUTE_CODE   = $data['INSTITUTE_CODE'];
  $INSTITUTE_EMAIL   = $data['EMAIL'];
  $INSTITUTE_MOBILE   = $data['MOBILE'];
}


?>

<script>
  var hash = '<?php echo $hash ?>';

  function submitPayuForm() {
    if (hash == '') {
      return;
    }
    var payuForm = document.forms.payuForm;
    payuForm.submit();
  }
</script>

<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"> Pay Online
          </h4>

          <div class="row">
            <div class="col-sm-12">
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

              <?php if ($formError) { ?>


                <span style="color:red">Please fill all mandatory fields.</span>
                <br />
                <br />
              <?php } ?>
              <div class="row">

                <form class="form-horizontal form-validate" action="<?php echo $action; ?>" method="post" name="payuForm">
                  <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">

                      <p style="background-color: red;padding: 10px;color: #fff;
    font-size: 14px;
    font-weight: 900;">Important Note : From 18 December 2019 GST is Applicable For Your Payments. DITRP (OPC) PVT LTD. GSTIN No. 27AAGCD4905Q2Z5 . For Any Query Free To Call Us.<br />महत्वपूर्ण नोट : 18 दिसंबर 2019 से GST आपके भुगतान के लिए लागू होगया। DITRP (OPC) PVT LTD. GSTIN No. 27AAGCD4905Q2Z5। अधिक जानकारी के लिए हमें संपर्क करे ।</p>


                      <div class="box-body row">
                        <input type="hidden" name="key" value="<?php echo MERCHANT_KEY ?>" />
                        <input type="hidden" name="hash" value="<?php echo $hash ?>" />
                        <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
                        <input type="hidden" name="surl" value="<?php echo $surl; ?>" />
                        <input type="hidden" name="furl" value="<?php echo $furl; ?>" />
                        <input type="hidden" name="curl" value="<?php echo $curl ?>" />
                        <input type="hidden" name="service_provider" value="payu_paisa" />

                        <input type="hidden" name="productinfo" value="wallet_recharge" />

                        <input type="hidden" name="udf3" value="<?php echo $user_id; ?>" />
                        <input type="hidden" name="udf4" value="<?php echo $user_role . "$" . $ip_address . "$" . $user_fullname . "$" . $user_login_id . "$" . $user_photo; ?>" />
                        <input type="hidden" name="udf5" value="<?php echo $user_fullname; ?>" />
                        <input type="hidden" name="udf6" value="<?php echo $ip_address; ?>" />
                        <input type="hidden" name="udf7" value="<?php echo $user_login_id; ?>" />
                        <input type="hidden" name="udf8" value="<?php echo $user_photo; ?>" />

                        <div class="form-group col-md-3">
                          <label for="amount1">Enter Amount</label>
                          <input class="form-control" id="udf1" name="udf1" placeholder="Enter amount to pay" value="<?= isset($_POST['udf1']) ? $_POST['udf1'] : '' ?>" type="text" autocomplete="off" required onkeyup="getgstdetails()">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="amount">Name</label>
                          <input class="form-control" id="firstname" name="firstname" placeholder="Enter user name" value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : $INSTITUTE_NAME ?>" readonly="readonly" autocomplete="off" type="text">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="amount">Email</label>

                          <input class="form-control" id="email" name="email" placeholder="Enter email address" value="<?= isset($_POST['email']) ? $_POST['email'] : $INSTITUTE_EMAIL ?>" autocomplete="off" readonly="readonly" type="email">

                        </div>
                        <div class="form-group col-md-3">
                          <label for="amount">Mobile</label>

                          <input class="form-control" id="phone" name="phone" placeholder="Enter mobile number" value="<?= isset($_POST['phone']) ? $_POST['phone'] : $INSTITUTE_MOBILE ?>" autocomplete="off" readonly="readonly" type="text">

                        </div>


                        <div class="form-group col-md-3">
                          <label for="gst">GST</label>

                          <input class="form-control" id="udf2" name="udf2" placeholder="GST" value="<?= isset($_POST['udf2']) ? $_POST['udf2'] : '' ?>" type="text" autocomplete="off" required readonly>

                        </div>

                        <div class="form-group col-md-3">
                          <label for="amount">Total Payable Amount</label>

                          <input class="form-control" id="amount" name="amount" placeholder="Total Amount" value="<?= isset($_POST['amount']) ? $_POST['amount'] : '' ?>" type="text" autocomplete="off" required readonly>

                        </div>


                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer text-center">
                        <a href="page.php?page=WalletFranchise" class="btn btn-danger">Cancel</a>

                        <?php if (!$hash) { ?>
                          <input type="submit" value="Make Payment" name="paymoney" class="btn btn-primary" />
                        <?php } ?>
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
</div>
</div>
</div>
</div>
<!--  </body> -->