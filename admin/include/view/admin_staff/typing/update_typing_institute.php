<?php

$institute_id = isset($_GET['id']) ? $_GET['id'] : '';

$action = isset($_POST['update_typing_institute']) ? $_POST['update_typing_institute'] : '';

include_once('include/classes/typing.class.php');
$typing = new typing();

if ($action != '') {
  $institute_id = isset($_POST['institute_id']) ? $_POST['institute_id'] : '';

  $result = $typing->update_typing_institute($institute_id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=typing-institute-list');
  }
}
/* get institute details */
$res = $typing->list_typing_institute($institute_id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    extract($data);
  }
}
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Update Typing Institute

    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Update Typing Institute</li>
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


        <div class="col-md-10">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Update Typing Institute</h3>
            </div>
            <div class="box-body">
              <input type="hidden" name="institute_id" value="<?= isset($INSTITUTE_ID) ? $INSTITUTE_ID : '' ?>" />

              <div class="form-group <?= (isset($errors['institute_code'])) ? 'has-error' : '' ?>">
                <label for="institute_code" class="col-sm-3 control-label">Institute Code</label>
                <div class="col-sm-9">
                  <input class="form-control" id="institute_code" name="institute_code" placeholder="Institute Code" value="<?= isset($_POST['institute_code']) ? $_POST['institute_code'] : $INSTITUTE_CODE ?>" type="text">
                  <span class="help-block"><?= (isset($errors['institute_code'])) ? $errors['institute_code'] : '' ?></span>
                </div>
              </div>

              <div class="form-group <?= (isset($errors['institute_name'])) ? 'has-error' : '' ?>">
                <label for="institute_name" class="col-sm-3 control-label">Institute Name</label>
                <div class="col-sm-9">
                  <input class="form-control" id="institute_name" name="institute_name" placeholder="Institute Name" value="<?= isset($_POST['institute_name']) ? $_POST['institute_name'] : $INSTITUTE_NAME ?>" type="text">
                  <span class="help-block"><?= (isset($errors['institute_name'])) ? $errors['institute_name'] : '' ?></span>
                </div>
              </div>

              <div class="form-group <?= (isset($errors['owner_name'])) ? 'has-error' : '' ?>">
                <label for="owner_name" class="col-sm-3 control-label">Owner Name</label>
                <div class="col-sm-9">
                  <input class="form-control" id="owner_name" name="owner_name" placeholder="Owner Name" value="<?= isset($_POST['owner_name']) ? $_POST['owner_name'] : $OWNER_NAME ?>" type="text">
                  <span class="help-block"><?= (isset($errors['owner_name'])) ? $errors['owner_name'] : '' ?></span>
                </div>
              </div>

              <div class="form-group <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
                <label for="email" class="col-sm-3 control-label">Email</label>
                <div class="col-sm-9">
                  <input class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($_POST['email']) ? $_POST['email'] : $EMAIL ?>" type="email">
                  <span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
                </div>
              </div>

              <div class="form-group <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
                <label for="mobile" class="col-sm-3 control-label">Mobile</label>
                <div class="col-sm-9">
                  <input class="form-control" id="mobile" name="mobile" placeholder="Mobile" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $MOBILE ?>" type="text">
                  <span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
                </div>
              </div>

              <div class="form-group <?= (isset($errors['address'])) ? 'has-error' : '' ?>">
                <label for="address" class="col-sm-3 control-label">Address</label>
                <div class="col-sm-9">
                  <input class="form-control" id="address" name="address" placeholder="Address" value="<?= isset($_POST['address']) ? $_POST['address'] : $ADDRESS ?>" type="text">
                  <span class="help-block"><?= (isset($errors['address'])) ? $errors['address'] : '' ?></span>
                </div>
              </div>

              <div class="form-group <?= (isset($errors['pincode'])) ? 'has-error' : '' ?>">
                <label for="pincode" class="col-sm-3 control-label">Pincode</label>
                <div class="col-sm-9">
                  <input class="form-control" id="pincode" name="pincode" placeholder="Pincode" value="<?= isset($_POST['pincode']) ? $_POST['pincode'] : $PINCODE ?>" type="text">
                  <span class="help-block"><?= (isset($errors['pincode'])) ? $errors['pincode'] : '' ?></span>
                </div>
              </div>

              <div class="form-group <?= (isset($errors['username'])) ? 'has-error' : '' ?>">
                <label for="username" class="col-sm-3 control-label">Username</label>
                <div class="col-sm-9">
                  <input class="form-control" id="username" name="username" placeholder="Username" value="<?= isset($_POST['username']) ? $_POST['username'] : $USERNAME ?>" type="text">
                  <span class="help-block"><?= (isset($errors['username'])) ? $errors['username'] : '' ?></span>
                </div>
              </div>

              <div class="form-group <?= (isset($errors['password'])) ? 'has-error' : '' ?>">
                <label for="password" class="col-sm-3 control-label">Password</label>
                <div class="col-sm-9">
                  <input class="form-control" id="password" name="password" placeholder="Password" value="<?= isset($_POST['password']) ? $_POST['password'] : $PASSWORD ?>" type="text">
                  <span class="help-block"><?= (isset($errors['password'])) ? $errors['password'] : '' ?></span>
                </div>
              </div>

              <div class="form-group <?= (isset($errors['plan'])) ? 'has-error' : '' ?>">
                <label for="plan" class="col-sm-3 control-label">Plan</label>
                <div class="col-sm-9">
                  <select class="form-control" name="plan" id="plan">
                    <?php
                    $PLAN_ID = isset($_POST['plan']) ? $_POST['plan'] : $PLAN_ID;
                    echo $db->MenuItemsDropdown('typing_software_plans', 'PLAN_ID', 'PLAN_NAME', 'PLAN_ID,PLAN_NAME', $PLAN_ID, ' WHERE 1 ORDER BY PLAN_NAME ASC'); ?>
                  </select>
                  <span class="help-block"><?= (isset($errors['plan'])) ? $errors['plan'] : '' ?></span>
                </div>
              </div>

              <div class="form-group <?= (isset($errors['activation_key'])) ? 'has-error' : '' ?>">
                <label for="activation_key" class="col-sm-3 control-label">Activation Key</label>
                <div class="col-sm-9">
                  <input class="form-control" id="activation_key" name="activation_key" placeholder="Activation Key" value="<?= isset($_POST['activation_key']) ? $_POST['activation_key'] : $ACTIVATION_KEY ?>" type="text">
                  <span class="help-block"><?= (isset($errors['activation_key'])) ? $errors['activation_key'] : '' ?></span>
                </div>
              </div>

              <div class="form-group">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;
                ?>
                <label for="status" class="col-sm-3 control-label">Status</label>
                <div class="radio">
                  <label>
                    <input name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?> type="radio">
                    Active
                  </label>
                  <label>
                    <input name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?> type="radio">
                    Inactive
                  </label>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="page.php?page=typing-institute-list" class="btn btn-default">Cancel</a>
              <input type="submit" name="update_typing_institute" class="btn btn-info" value="Update Institute" />
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>