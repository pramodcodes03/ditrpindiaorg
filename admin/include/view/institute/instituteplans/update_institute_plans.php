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
    header('location:page.php?page=listPlans');
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
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Update Institute Plan</h4>
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="plan_id" value="<?= isset($PLAN_ID) ? $PLAN_ID : '' ?>" />
            <?php
            if (isset($success)) {
            ?>
              <div class="row">
                <div class="col-sm-12">
                  <div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                    <h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
                    <?= isset($message) ? $message : 'Please correct the errors.'; ?>
                    <?php
                    echo "<ul>";
                    foreach ($errors as $error) {
                      echo "<li>$error</li>";
                    }
                    echo "<ul>";
                    ?>
                  </div>
                </div>
              </div>
            <?php
            }
            ?>
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="planname">Plan Name</label>
                <input class="form-control" id="planname" name="planname" placeholder="Plan Name" value="<?= isset($_POST['planname']) ? $_POST['planname'] : $PLAN_NAME ?>" type="text">
                <span class="help-block"><?= (isset($errors['planname'])) ? $errors['planname'] : '' ?></span>
              </div>

              <div class="col-md-12 row form-group">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;
                ?>
                <label for="status" class="col-sm-1 control-label">Status</label>
                <div class="radio">
                  <label class="col-md-6">
                    <input name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?> type="radio">
                    Active
                  </label>
                  <label class="col-md-8">
                    <input name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?> type="radio">
                    InActive
                  </label>
                </div>
              </div>
            </div>

            <input type="submit" name="update_plan" class="btn btn-primary mr-2" value="Submit">
            <a href="page.php?page=listPlans" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>