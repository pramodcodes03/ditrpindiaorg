<?php

$id = isset($_POST['id']) ? $_POST['id'] : '';
$action = isset($_POST['edit_masterpassword']) ? $_POST['edit_masterpassword'] : '';

include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $websiteManage->edit_masterpassword($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    //header('location:page.php?page='.HTTP_HOST.'/website_management/ContactUs');
    //header('location:page.php?page=/website_management/ContactUs');
  }
}

$res = $websiteManage->list_masterpassword($id, '');
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
          <h4 class="card-title">Master Password
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
              <input type="hidden" name="id" value="<?= $id ?>" />

              <div class="form-group">
                <label for="exampleInputName1">Wallet Password </label>
                <input type="password" class="form-control" id="exampleInputName1" name="wallet_password" value="<?= isset($_POST['wallet_password']) ? $_POST['wallet_password'] : $wallet_password ?>">
              </div>

              <div class="form-group">
                <label for="exampleInputName1">Courier Wallet Password </label>
                <input type="password" class="form-control" id="exampleInputName1" name="courier_password" value="<?= isset($_POST['courier_password']) ? $_POST['courier_password'] : $courier_password ?>">
              </div>

              <input type="submit" name="edit_masterpassword" class="btn btn-success mr-2" value="Submit">
            </form>
        </div>
      </div>
    </div>
  </div>
</div>