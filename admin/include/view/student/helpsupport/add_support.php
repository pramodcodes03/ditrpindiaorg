<?php
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

$inst_id = $db->get_student_institute_id($user_id);

$mobile = $db->get_student_mobile($user_id);
$email  =  $db->get_student_email($user_id);

$action = isset($_POST['add_support']) ? $_POST['add_support'] : '';
include_once('include/classes/helpsupport.class.php');
$helpsupport = new helpsupport();

if ($action != '') {
  $result = $helpsupport->add_support();
  $result = json_decode($result, true);
  //print_r($result);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listSupport');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add New Support</h4>
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
            <div class="row">
              <input type="hidden" value="<?= $user_id ?>" name="student_id" />
              <input type="hidden" value="<?= $inst_id ?>" name="inst_id" />
              <div class="form-group  col-md-12 <?= (isset($errors['description'])) ? 'has-error' : '' ?>">
                <label>Please add your queries here. </label>
                <textarea class="form-control" id="description" name="description" placeholder="Description" type="text" rows="6"><?= isset($_POST['description']) ? $_POST['description'] : '' ?></textarea>
                <span class="help-block"><?= (isset($errors['description'])) ? $errors['description'] : '' ?></span>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
                <label>Mobile</label>
                <input class="form-control" id="mobile" name="mobile" maxlength="10" placeholder="Mobile" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $mobile ?>" type="text">
                <span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
                <label>Email</label>
                <input class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($_POST['email']) ? $_POST['email'] : $email ?>" type="email">
                <span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
              </div>

              <div class="form-group col-md-12 <?= (isset($errors['supportfiles'])) ? 'has-error' : '' ?>">
                <label>Attach Files</label>
                <input id="supportfiles" name="supportfiles[]" multiple type="file">
                <p class="help-block"><?= (isset($errors['supportfiles'])) ? $errors['supportfiles'] : 'Please Upload Issues Photos Here' ?></p>
              </div>

              <div class="col-md-12 form-group row">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : 1;
                ?>
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?>>
                      Active
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?>>
                      Inactive
                    </label>
                  </div>
                </div>
              </div>

              <!-- /.box-body -->
              <div class="box-footer text-center">
                <a href="page.php?page=listSupport" class="btn btn-danger btn1">Cancel</a>
                <input type="submit" name="add_support" class="btn btn-info btn1" value="Add Support" />
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>