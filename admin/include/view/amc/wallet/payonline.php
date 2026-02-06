<?php

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
if (empty($posted['hash']) && sizeof($posted) > 0) {
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


$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 7) {
  $amc_id = $db->get_parent_id($user_role, $user_id);
  $staff_id = $user_id;
} else {
  $_id = $user_id;
  $staff_id = 0;
}
$sql = "SELECT AMC_NAME,AMC_CODE,EMAIL,MOBILE FROM amc_details WHERE AMC_ID='$institute_id'";
$AMC_NAME = '';
$AMC_CODE = '';
$AMC_EMAIL = '';
$AMC_MOBILE = '';

$res = $db->execQuery($sql);
if ($res && $res->num_rows == 1) {
  $data = $res->fetch_assoc();
  extract($data);
  $AMC_NAME   = $data['AMC_NAME'];
  $AMC_CODE   = $data['AMC_CODE'];
  $AMC_EMAIL   = $data['EMAIL'];
  $AMC_MOBILE   = $data['MOBILE'];
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
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Pay Online

    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="page.php?page=wallet">Wallet</a></li>
      <li class="active">Add Money</li>
    </ol>
  </section>
  <section class="content">
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
            <div class="box-header with-border">
              <h3 class="box-title">Pay Online</h3>
            </div>
            <div class="box-body">
              <input type="hidden" name="key" value="<?php echo MERCHANT_KEY ?>" />
              <input type="hidden" name="hash" value="<?php echo $hash ?>" />
              <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
              <input type="hidden" name="surl" value="<?php echo $surl; ?>" size="64" />
              <input type="hidden" name="furl" value="<?php echo $furl; ?>" size="64" />
              <input type="hidden" name="curl" value="<?php echo $curl ?>" />
              <input type="hidden" name="service_provider" value="payu_paisa" size="64" />

              <input type="hidden" name="productinfo" value="wallet_recharge" size="64" />



              <div class="form-group">
                <label for="amount" class="col-sm-4 control-label">Enter Amount</label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                    <input class="form-control" id="amount" name="amount" placeholder="Enter ammount to pay" value="<?= isset($_POST['amount']) ? $_POST['amount'] : '' ?>" type="text" autocomplete="off" required>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="amount" class="col-sm-4 control-label">Name</label>
                <div class="col-sm-6">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input class="form-control" id="firstname" name="firstname" placeholder="Enter user name" value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : $INSTITUTE_NAME ?>" readonly="readonly" autocomplete="off" type="text">

                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="amount" class="col-sm-4 control-label">Email</label>
                <div class="col-sm-6">

                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input class="form-control" id="email" name="email" placeholder="Enter email address" value="<?= isset($_POST['email']) ? $_POST['email'] : $INSTITUTE_EMAIL ?>" autocomplete="off" readonly="readonly" type="email">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="amount" class="col-sm-4 control-label">Mobile</label>
                <div class="col-sm-6">

                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-mobile-phone"></i></span>
                    <input class="form-control" id="phone" name="phone" placeholder="Enter ammount mobile number" value="<?= isset($_POST['phone']) ? $_POST['phone'] : $INSTITUTE_MOBILE ?>" autocomplete="off" readonly="readonly" type="text">
                  </div>
                </div>
              </div>


            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="page.php?page=wallet" class="btn bg-orange btn-flat margin">Cancel</a>

              <?php if (!$hash) { ?>
                <input type="submit" value="Make Payment" name="paymoney" class="btn bg-purple btn-flat margin" />
              <?php } ?>
            </div>
          </div>
        </div>

      </form>
    </div>
  </section>
</div>
<!--  </body> -->