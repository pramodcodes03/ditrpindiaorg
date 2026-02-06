<?php

$id = isset($_POST['id']) ? $_POST['id'] : '';
$action = isset($_POST['edit_policy']) ? $_POST['edit_policy'] : '';

include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $websiteManage->edit_policy($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:/website_management/managePolicies');
  }
}

$res = $websiteManage->list_policy($id, '');
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
          <h4 class="card-title">Our Policies</h4>
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
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
            <input type="hidden" name="id" value="1" />

            <div class="form-group">
              <label>Terms & Condtions</label>
              <textarea class="form-control" id="tinyMceExample" rows="4" name="terms_condition"><?= isset($_POST['terms_condition']) ? $_POST['terms_condition'] : $terms_condition ?></textarea>
            </div>

            <div class="form-group">
              <label>Privacy Policies </label>
              <textarea class="form-control" id="tinyMceExample1" rows="4" name="privacy_policies"><?= isset($_POST['privacy_policies']) ? $_POST['privacy_policies'] : $privacy_policies ?></textarea>
            </div>

            <div class="form-group">
              <label>Disclaimer</label>
              <textarea class="form-control" id="tinyMceExample2" rows="4" name="disclaimer"><?= isset($_POST['disclaimer']) ? $_POST['disclaimer'] : $disclaimer ?></textarea>
            </div>

            <div class="form-group">
              <label>Refund Policies</label>
              <textarea class="form-control" id="tinyMceExample3" rows="4" name="refund_policy"><?= isset($_POST['refund_policy']) ? $_POST['refund_policy'] : $refund_policy ?></textarea>
            </div>

            <input type="submit" name="edit_policy" class="btn btn-success mr-2" value="Submit">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>