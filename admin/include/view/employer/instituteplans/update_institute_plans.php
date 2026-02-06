<?php

$plan_id = isset($_GET['id']) ? $_GET['id'] : '';

$action = isset($_POST['update_plan']) ? $_POST['update_plan'] : '';

include_once('include/classes/instituteplans.class.php');
$instituteplans = new instituteplans();

if ($action != '') {
  $plan_id = isset($_POST['plan_id']) ? $_POST['plan_id'] : '';

  $result = $instituteplans->update_institue_plan($plan_id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=list-institute-plans');
  }
}
/* get institute details */
$res = $instituteplans->list_institue_plan($plan_id, '');
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
      Update Institute Plan

    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Update Institute Plan</li>
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
              <h3 class="box-title">Update Institute Plan</h3>
            </div>
            <div class="box-body">
              <input type="hidden" name="plan_id" value="<?= isset($PLAN_ID) ? $PLAN_ID : '' ?>" />

              <div class="form-group <?= (isset($errors['planname'])) ? 'has-error' : '' ?>">
                <label for="planname" class="col-sm-3 control-label">Plan Name</label>
                <div class="col-sm-9">
                  <input class="form-control" id="planname" name="planname" placeholder="Plan Name" value="<?= isset($_POST['planname']) ? $_POST['planname'] : $PLAN_NAME ?>" type="text">
                  <span class="help-block"><?= (isset($errors['planname'])) ? $errors['planname'] : '' ?></span>
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
              <a href="page.php?page=list-institute-plans" class="btn btn-default">Cancel</a>
              <input type="submit" name="update_plan" class="btn btn-info" value="Update Institute Plan" />
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>