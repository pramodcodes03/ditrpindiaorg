<?php

$inst_id = isset($_GET['id']) ? $_GET['id'] : '';
$report_id = '';

include_once('include/classes/admin.class.php');
$admin = new admin();

$action1 = isset($_POST['add_report']) ? $_POST['add_report'] : '';
$action2 = isset($_POST['update_report']) ? $_POST['update_report'] : '';



if ($action1 != '') {
  $result = $admin->add_institute_report_staff($inst_id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=report-institute-list');
  }
}


if ($action2 != '') {
  $result = $admin->update_institute_report_staff($report_id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=report-institute-list');
  }
}


/* get institute details */
include_once('include/classes/institute.class.php');
$institute = new institute();

$res1 = $institute->list_institute($inst_id, '');
if ($res1 != '') {
  $srno1 = 1;
  while ($data1 = $res1->fetch_assoc()) {
    $USER_LOGIN_ID     = $data1['USER_LOGIN_ID'];
    $REG_DATE       = $data1['REG_DATE'];
    $INSTITUTE_CODE   = $data1['INSTITUTE_CODE'];
    $INSTITUTE_NAME   = $data1['INSTITUTE_NAME'];
    $MOBILE       = $data1['MOBILE'];
    $ACTIVE       = $data1['ACTIVE'];
    $STATE             = $data1['STATE'];
    $CITY           = $data1['CITY'];
    $VERIFIED       = $data1['VERIFIED'];
    $verify_flag     = $data1['VERIFIED'];
    $PLAN_ID            = $data1['PLAN_ID'];

    $REG_DATE               = $data1['REG_DATE'];
    $VERIFIED_ON_FORMATTED  = $data1['VERIFIED_ON_FORMATTED'];
    $EXP_DATE               = $data1['EXP_DATE'];
  }
}

/* get report details */

$ADMISSION_DEMO         = '';
$ADMISSION_DETAILS          = '';
$TYPING_DEMO               = '';
$TYPING_DETAILS          = '';
$MOBILEAPP_DEMO       = '';
$MOBILEAPP_DETAILS         = '';
$ECONTEST_DEMO          = '';
$ECONTEST_DETAILS      = '';
$WELCOMEKIT_DEMO        = '';
$WELCOMEKIT_DETAILS         = '';

$REGISTRATION_FEE          = '';
$REGISTRATIONFEES_DETAILS  = '';
$REMARK                    = '';
$ACTIVE                    = '';
$CREATED_BY                = '';
$CREATED_ON                = '';


$res2 = $admin->list_institute_report_staff($report_id, $inst_id, '');
if ($res2 != '') {
  $srno2 = 1;
  while ($data2 = $res2->fetch_assoc()) {
    // print_r($data2);

    $report_id                 = $data2['REPORT_ID'];
    $ADMISSION_DEMO         = $data2['ADMISSION_DEMO'];
    $ADMISSION_DETAILS          = $data2['ADMISSION_DETAILS'];
    $TYPING_DEMO               = $data2['TYPING_DEMO'];
    $TYPING_DETAILS          = $data2['TYPING_DETAILS'];
    $MOBILEAPP_DEMO       = $data2['MOBILEAPP_DEMO'];
    $MOBILEAPP_DETAILS         = $data2['MOBILEAPP_DETAILS'];
    $ECONTEST_DEMO          = $data2['ECONTEST_DEMO'];
    $ECONTEST_DETAILS      = $data2['ECONTEST_DETAILS'];
    $WELCOMEKIT_DEMO        = $data2['WELCOMEKIT_DEMO'];
    $WELCOMEKIT_DETAILS         = $data2['WELCOMEKIT_DETAILS'];

    $REGISTRATION_FEE          = $data2['REGISTRATION_FEE'];
    $REGISTRATIONFEES_DETAILS  = $data2['REGISTRATIONFEES_DETAILS'];
    $REMARK                    = $data2['REMARK'];
    $ACTIVE                    = $data2['ACTIVE'];
    $CREATED_BY                = $data2['CREATED_BY'];
    $CREATED_ON                = $data2['CREATED_ON'];
  }
}
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Update Institutes Report (DITRP Staff Only)

    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="page.php?page=list-institutes">Institute</a></li>
      <li class="active"> Update Institutes Report (DITRP Staff Only)</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <form class="form-horizontal form-validate" action="" method="post" enctype="multipart/form-data">
      <!-- left column -->
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

      <div class="row">
        <div class="col-md-9">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> Update Institutes Report (DITRP Staff Only) </h3>
            </div>
            <div class="box-body">
              <input type="hidden" name="institute_id" value="<?= $inst_id ?>" />
              <input type="hidden" name="report_id" value="<?= $report_id ?>" />

              <input type="hidden" name="institute_login_id" value="<?= isset($USER_LOGIN_ID) ? $USER_LOGIN_ID : '' ?>" />

              <div class="form-group col-sm-6 <?= (isset($errors['instcode'])) ? 'has-error' : '' ?>">
                <label for="instcode" class="col-sm-3 control-label">Institute Code</label>
                <div class="col-sm-9">
                  <input class="form-control" id="instcode" name="instcode" placeholder="Institute Code" value="<?= isset($_POST['instcode']) ? $_POST['instcode'] : $INSTITUTE_CODE ?>" type="text" disabled="true">
                  <span class="help-block"><?= isset($errors['instcode']) ? $errors['instcode'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['instname'])) ? 'has-error' : '' ?>">
                <label for="instname" class="col-sm-3 control-label">Institute Name</label>
                <div class="col-sm-9">
                  <input class="form-control" id="instname" name="instname" placeholder="Institute name" value="<?= isset($_POST['instname']) ? $_POST['instname'] : $INSTITUTE_NAME ?>" type="text" disabled="true">
                  <span class="help-block"><?= isset($errors['instname']) ? $errors['instname'] : '' ?></span>
                </div>
              </div>


              <div class="form-group col-sm-6 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
                <label for="mobile" class="col-sm-3 control-label">Mobile</label>
                <div class="col-sm-9">
                  <input class="form-control" id="mobile" name="mobile" maxlength="10" placeholder="Mobile" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $MOBILE ?>" type="text" disabled="true">
                  <span class="help-block"><?= isset($errors['mobile']) ? $errors['mobile'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
                <label for="state" class="col-sm-3 control-label">State</label>
                <div class="col-sm-9">
                  <select class="form-control" name="state" id="state" disabled="true">
                    <?php
                    $state = isset($_POST['state']) ? $_POST['state'] : $STATE;
                    echo $db->MenuItemsDropdown('states_master', 'STATE_ID', 'STATE_NAME', 'STATE_ID,STATE_NAME', $state, ' ORDER BY STATE_NAME ASC'); ?>
                  </select>
                  <span class="help-block"><?= isset($errors['state']) ? $errors['state'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
                <label for="address2" class="col-sm-3 control-label">City</label>
                <div class="col-sm-9">
                  <select class="form-control" name="city" id="city" disabled="true">
                    <?php
                    $city = isset($_POST['city']) ? $_POST['city'] : $CITY;
                    echo $db->MenuItemsDropdown('city_master', 'CITY_ID', 'CITY_NAME', 'CITY_ID,CITY_NAME', $city, ' ORDER BY CITY_NAME ASC'); ?>
                  </select>
                  <span class="help-block"><?= isset($errors['city']) ? $errors['city'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['plan'])) ? 'has-error' : '' ?>">
                <label for="plan" class="col-sm-3 control-label">Plan Of Institute</label>
                <div class="col-sm-9">
                  <select class="form-control" id="plan" name="plan" disabled="true">
                    <?php
                    $plan = isset($_POST['plan']) ? $_POST['plan'] : $PLAN_ID;
                    echo $db->MenuItemsDropdown('institute_plans', 'PLAN_ID', 'PLAN_NAME', 'PLAN_ID,PLAN_NAME', $plan, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                    ?>
                  </select>
                  <span class="help-block"><?= isset($errors['plan']) ? $errors['plan'] : '' ?></span>
                </div>
              </div>

            </div>

          </div>
        </div>


        <div class="col-md-3">

          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">For DITRP Use</h3>
              </div>
              <div class="form-group">
                <label for="registrationdate" class="col-sm-4 control-label">Register On</label>
                <div class="col-sm-8">
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control pull-right" value="<?= isset($_POST['registrationdate']) ? $_POST['registrationdate'] : $REG_DATE; ?>" id="registrationdate" type="text" name="registrationdate" onchange="setAccExpDate(this.value)" disabled="true">
                  </div>
                </div>
              </div>
              <?php if ($VERIFIED == 1) { ?>
                <div class="form-group">
                  <label for="verifydate" class="col-sm-4 control-label">Verified On</label>
                  <div class="col-sm-8">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input class="form-control pull-right" value="<?= isset($_POST['verifydate']) ? $_POST['verifydate'] : $VERIFIED_ON_FORMATTED; ?>" id="dateto" type="text" name="verifydate" disabled="true" />
                    </div>
                  </div>
                </div>
              <?php } ?>
              <div class="form-group <?= (isset($errors['expirationdate'])) ? 'has-error' : '' ?>">
                <label for="expirationdate" class="col-sm-4 control-label">Expire On</label>
                <div class="col-sm-8">
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control pull-right" value="<?= isset($_POST['expirationdate']) ? $_POST['expirationdate'] : $EXP_DATE; ?>" id="expirationdate" type="text" name="expirationdate" disabled="true">
                  </div>
                  <span class="help-block"><?= (isset($errors['expirationdate'])) ? $errors['expirationdate'] : '' ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> Demo Section And FeedBack Of Institute </h3>
            </div>
            <div class="box-body">

              <div class="form-group col-sm-6 <?= (isset($errors['regis_fees_lable'])) ? 'has-error' : '' ?>">
                <label for="regis_fees_lable" class="col-sm-4 control-label">1) Registration Fees </label>
                <div class="radio col-sm-2">
                  <label>
                    <input name="regis_fees" id="regis_fees1" value="1" <?= ($REGISTRATION_FEE == 1) ? "checked=''" : ''  ?> type="radio">
                    Yes
                  </label>
                  <label>
                    <input name="regis_fees" id="regis_fees2" value="0" <?= ($REGISTRATION_FEE == 0) ? "checked=''" : ''  ?> type="radio">
                    No
                  </label>
                </div>
                <div class="col-sm-6">
                  <textarea class="form-control" id="regis_fees_details" name="regis_fees_details" placeholder="Details" type="text" rows="6"><?= isset($_POST['regis_fees_details']) ? $_POST['regis_fees_details'] : $REGISTRATIONFEES_DETAILS ?></textarea>
                  <span class="help-block"><?= (isset($errors['regis_fees_details'])) ? $errors['regis_fees_details'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['welcome_kit_lable'])) ? 'has-error' : '' ?>">
                <label for="welcome_kit_lable" class="col-sm-4 control-label">2) Welcome Kit </label>
                <div class="radio col-sm-2">
                  <label>
                    <input name="welcome_kit" id="welcome_kit1" value="1" <?= ($WELCOMEKIT_DEMO == 1) ? "checked=''" : ''  ?> type="radio">
                    Yes
                  </label>
                  <label>
                    <input name="welcome_kit" id="welcome_kit2" value="0" <?= ($WELCOMEKIT_DEMO == 0) ? "checked=''" : ''  ?> type="radio">
                    No
                  </label>
                </div>
                <div class="col-sm-6">
                  <textarea class="form-control" id="welcome_kit_details" name="welcome_kit_details" placeholder="Details" type="text" rows="6"><?= isset($_POST['welcome_kit_details']) ? $_POST['welcome_kit_details'] : $WELCOMEKIT_DETAILS ?></textarea>
                  <span class="help-block"><?= (isset($errors['welcome_kit_details'])) ? $errors['welcome_kit_details'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['admission_lable'])) ? 'has-error' : '' ?>">
                <label for="admission_lable" class="col-sm-4 control-label">3) Admission Details </label>
                <div class="radio col-sm-2">
                  <label>
                    <input name="admission" id="admission1" value="1" <?= ($ADMISSION_DEMO == 1) ? "checked=''" : ''  ?> type="radio">
                    Yes
                  </label>
                  <label>
                    <input name="admission" id="admission2" value="0" <?= ($ADMISSION_DEMO == 0) ? "checked=''" : ''  ?> type="radio">
                    No
                  </label>
                </div>
                <div class="col-sm-6">
                  <textarea class="form-control" id="admission_details" name="admission_details" placeholder="Details" type="text" rows="6"><?= isset($_POST['admission_details']) ? $_POST['admission_details'] : $ADMISSION_DETAILS ?></textarea>
                  <span class="help-block"><?= (isset($errors['admission_details'])) ? $errors['admission_details'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['contest_lable'])) ? 'has-error' : '' ?>">
                <label for="contest_lable" class="col-sm-4 control-label">4) E-Contest Details</label>
                <div class="radio col-sm-2">
                  <label>
                    <input name="contest" id="contest1" value="1" <?= ($ECONTEST_DEMO == 1) ? "checked=''" : ''  ?> type="radio">
                    Yes
                  </label>
                  <label>
                    <input name="contest" id="contest2" value="0" <?= ($ECONTEST_DEMO == 0) ? "checked=''" : ''  ?> type="radio">
                    No
                  </label>
                </div>
                <div class="col-sm-6">
                  <textarea class="form-control" id="contest_details" name="contest_details" placeholder="Details" type="text" rows="6"><?= isset($_POST['contest_details']) ? $_POST['contest_details'] : $ECONTEST_DETAILS ?></textarea>
                  <span class="help-block"><?= (isset($errors['contest_details'])) ? $errors['contest_details'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['typing_label'])) ? 'has-error' : '' ?>">
                <label for="typing_label" class="col-sm-4 control-label">5) Typing Software </label>
                <div class="radio col-sm-2">
                  <label>
                    <input name="typing" id="typing1" value="1" <?= ($TYPING_DEMO == 1) ? "checked=''" : ''  ?> type="radio">
                    Yes
                  </label>
                  <label>
                    <input name="typing" id="typing2" value="0" <?= ($TYPING_DEMO == 0) ? "checked=''" : ''  ?> type="radio">
                    No
                  </label>
                </div>
                <div class="col-sm-6">
                  <textarea class="form-control" id="typing_details" name="typing_details" placeholder="Details" type="text" rows="6"><?= isset($_POST['typing_details']) ? $_POST['typing_details'] : $TYPING_DETAILS ?></textarea>
                  <span class="help-block"><?= (isset($errors['typing_details'])) ? $errors['typing_details'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['mobile_lable'])) ? 'has-error' : '' ?>">
                <label for="mobile_lable" class="col-sm-4 control-label">6) Mobile Application </label>
                <div class="radio col-sm-2">
                  <label>
                    <input name="mobileapp" id="mobileapp1" value="1" <?= ($MOBILEAPP_DEMO == 1) ? "checked=''" : ''  ?> type="radio">
                    Yes
                  </label>
                  <label>
                    <input name="mobileapp" id="mobileapp2" value="0" <?= ($MOBILEAPP_DEMO == 0) ? "checked=''" : ''  ?> type="radio">
                    No
                  </label>
                </div>
                <div class="col-sm-6">
                  <textarea class="form-control" id="mobileapp_details" name="mobileapp_details" placeholder="Details" type="text" rows="6"><?= isset($_POST['mobileapp_details']) ? $_POST['mobileapp_details'] : $MOBILEAPP_DETAILS ?></textarea>
                  <span class="help-block"><?= (isset($errors['mobileapp_details'])) ? $errors['mobileapp_details'] : '' ?></span>
                </div>
              </div>
              <div class="clearfix"></div>

              <div class="form-group col-sm-12<?= (isset($errors['remark_lable'])) ? 'has-error' : '' ?>">
                <label for="remark_lable" class="col-sm-2 control-label">7) Remark / Follow Up </label>
                <div class="col-sm-1"></div>
                <div class="col-sm-4">
                  <textarea class="form-control" id="remark_details" name="remark_details" placeholder="Details" type="text" rows="3"><?= isset($_POST['remark_details']) ? $_POST['remark_details'] : $REMARK ?></textarea>
                  <span class="help-block"><?= (isset($errors['remark_details'])) ? $errors['remark_details'] : '' ?></span>
                </div>
              </div>
              <div class="clearfix"></div>

              <div class="form-group">
                <label for="status" class="col-sm-3 control-label">Status</label>
                <div class="radio">
                  <label>
                    <input name="status" id="optionsRadios1" value="1" <?= ($ACTIVE == 1) ? "checked=''" : ''  ?> type="radio">
                    Active
                  </label>
                  <label>
                    <input name="status" id="optionsRadios2" value="0" <?= ($ACTIVE == 0) ? "checked=''" : ''  ?> type="radio">
                    Inactive
                  </label>
                </div>
              </div>
              <div class="clearfix"></div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="page.php?page=report-institute-list" class="btn btn-default">Cancel</a>
              <?php if ($report_id == '') { ?>
                <input type="submit" name="add_report" class="btn btn-info" value="Save" />
              <?php } else { ?>

                <input type="submit" name="update_report" class="btn btn-info" value="Update" />
              <?php } ?>

            </div>



          </div>
        </div>
      </div>
</div>
</form>
<!-- /.row -->
</section>
<!-- /.content -->
</div>