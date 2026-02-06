<?php
$student_id = $db->test(isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '');
$action    = isset($_POST['action']) ? $_POST['action'] : '';
if ($action != '') {
  //print_r($_POST);
  $result = $access->change_password();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = isset($result['message']) ? $result['message'] : '';
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=change-password');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Change Password</h4>
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
            <?php
            if (isset($success)) {
            ?>
              <div class="row">
                <div class="col-md-12">
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
            <div class="row col-md-12">
              <div class="form-group col-md-4 <?= (isset($errors['current_password'])) ? 'has-error' : '' ?>">
                <label for="current_password">Current Password</label>
                <input class="form-control" id="current_password" name="current_password" placeholder="Enter current password" type="password" value="<?= isset($_POST['current_password']) ? $_POST['current_password'] : '' ?>" />
                <span class="help-block"><?= isset($errors['current_password']) ? $errors['current_password'] : '' ?></span>
              </div>
              <div class="form-group col-md-4  <?= (isset($errors['new_password'])) ? 'has-error' : '' ?>">
                <label for="new_password">New Password</label>
                <input class="form-control" id="new_password" name="new_password" placeholder="Enter new password" type="password" value="<?= isset($_POST['new_password']) ? $_POST['new_password'] : '' ?>" />
                <span class="help-block"><?= isset($errors['new_password']) ? $errors['new_password'] : '' ?></span>
              </div>
              <div class="form-group col-md-4  <?= (isset($errors['confirm_new_password'])) ? 'has-error' : '' ?>">
                <label for="confirm_new_password">Confirm New Password</label>
                <input class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="Re-Enter new password" type="password" value="<?= isset($_POST['confirm_new_password']) ? $_POST['confirm_new_password'] : '' ?>" />
                <span class="help-block"><?= isset($errors['confirm_new_password']) ? $errors['confirm_new_password'] : '' ?></span>
              </div>
              <div class="box-footer col-md-12">
                <input type="submit" class="btn btn-primary" name="action" value="Change Password" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>