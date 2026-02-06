<?php
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 8)
  $staff_id = isset($_GET['id']) ? $_GET['id'] : '';
else if ($user_role == 3) $staff_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
include_once('include/classes/account.class.php');
$account = new account();
$res = $account->list_admin_staff($staff_id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    $STAFF_ID       = $data['STAFF_ID'];
    $ADMIN_ID       = $data['ADMIN_ID'];
    $STAFF_FULLNAME   = $data['STAFF_FULLNAME'];
    $STAFF_GENDER     = $data['STAFF_GENDER'];
    $STAFF_DOB       = $data['STAFF_DOB'];
    $STAFF_DOB_FORMATED = $data['STAFF_DOB_FORMATED'];
    $STAFF_EMAIL     = $data['STAFF_EMAIL'];
    $STAFF_MOBILE     = $data['STAFF_MOBILE'];
    $STAFF_PER_ADDRESS   = $data['STAFF_PER_ADDRESS'];
    $STAFF_PHOTO     = $data['STAFF_PHOTO'];
    $STAFF_PHOTO_ID     = $data['STAFF_PHOTO_ID'];
    $STAFF_RESPONSIBILITIES   = json_decode($data['STAFF_RESPONSIBILITIES']);
    $ACTIVE       = $data['ACTIVE'];
    $USER_LOGIN_ID       = $data['USER_LOGIN_ID'];
    $USER_NAME       = $data['USER_NAME'];
    $PHOTO = '../uploads/default_user.png';
    $PHOTO_ID = '../uploads/default_user.png';
    if ($STAFF_PHOTO != '')
      $PHOTO = ADMIN_STAFF_PHOTO_PATH . '/' . $STAFF_ID . '/' . $STAFF_PHOTO;
    if ($STAFF_PHOTO_ID != '')
      $PHOTO = ADMIN_STAFF_PHOTO_PATH . '/' . $STAFF_ID . '/' . $STAFF_PHOTO_ID;
  }
}
$action = isset($_POST['update_staff']) ? $_POST['update_staff'] : '';

if ($action != '') {

  $account = new account();
  $result = $account->update_admin_staff();
  $result = json_decode($result, true);
  $success = $result['success'];
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listStaff');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Update Staff Member</h4>
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
            <?php
            if (isset($success)) {
            ?>
              <div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                <h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
                <?= isset($message) ? $message : 'Please correct the errors.'; ?>
              </div>
            <?php
            }
            ?>
            <input type="hidden" name="staff_id" id="staff_id" value="<?= $STAFF_ID ?>" />
            <input type="hidden" name="admin_id" id="admin_id" value="<?= $ADMIN_ID ?>" />
            <input type="hidden" name="login_id" id="login_id" value="<?= $USER_LOGIN_ID ?>" />
            <input type="hidden" name="role" class="form-control" id="role" value="3">

            <div class="box-body">
              <div class="row">
                <div class="form-group col-md-4 <?= (isset($errors['fullname'])) ? 'has-error' : '' ?>">
                  <label for="fullname">Fullname</label>
                  <input type="text" name="fullname" class="form-control" value="<?= isset($_POST['fullname']) ? $_POST['fullname'] : $STAFF_FULLNAME ?>" id="fullname" placeholder="Enter fullname" required>
                  <span class="help-block"><?= isset($errors['fullname']) ? $errors['fullname'] : '' ?></span>
                </div>

                <div class="form-group col-md-4 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
                  <label for="email">Email</label>
                  <input type="email" name="email" class="form-control" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : $STAFF_EMAIL ?>" placeholder="Email" required>
                  <span class="help-block"><?= isset($errors['email']) ? $errors['email'] : '' ?></span>
                </div>

                <div class="form-group col-md-4 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
                  <label for="mobile">Mobile</label>
                  <input type="text" name="mobile" class="form-control" id="mobile" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $STAFF_MOBILE ?>" placeholder="Mobile" required>
                  <span class="help-block"><?= isset($errors['mobile']) ? $errors['mobile'] : '' ?></span>
                </div>

                <div class="form-group col-md-4">
                  <label>Date Of Birth:</label>
                  <input class="form-control pull-right" name="dob" id="dob" type="date" value="<?= isset($_POST['dob']) ? $_POST['dob'] : $STAFF_DOB_FORMATED ?>">
                </div>

                <div class="form-group col-md-4">
                  <label>Gender</label>
                  <?php
                  $STAFF_GENDER = isset($_POST['gender']) ? $_POST['gender'] : $STAFF_GENDER;
                  ?>
                  <select class="form-control" name="gender">
                    <option value="male" <?= ($STAFF_GENDER == 'male') ? 'selected="selected"' : '' ?>>Male</option>
                    <option value="female" <?= ($STAFF_GENDER == 'female') ? 'selected="selected"' : '' ?>>Female</option>
                    <option value="other" <?= ($STAFF_GENDER == 'other') ? 'selected="selected"' : '' ?>>Other</option>
                  </select>
                </div>

                <div class="form-group col-md-4">
                  <label>Address</label>
                  <textarea class="form-control" rows="3" placeholder="Permanent Address ..." name="per_add"><?= isset($_POST['per_add']) ? $_POST['per_add'] : $STAFF_PER_ADDRESS ?></textarea>
                </div>


                <div class="form-group col-md-4 <?= (isset($errors['uname'])) ? 'has-error' : '' ?>">
                  <label for="uname">Username</label>
                  <input type="text" class="form-control" id="uname" placeholder="Username" name="uname" value="<?= isset($_POST['uname']) ? $_POST['uname'] : $USER_NAME ?>" required>
                  <span class="help-block"><?= isset($errors['uname']) ? $errors['uname'] : '' ?></span>
                </div>

                <div class="form-group col-md-4 <?= (isset($errors['pword'])) ? 'has-error' : '' ?>">
                  <label for="pword">Password</label>
                  <input type="password" class="form-control" id="pword" placeholder="New Password" value="<?= isset($_POST['pword']) ? $_POST['pword'] : '' ?>" name="pword">
                  <span class="help-block"><?= isset($errors['pword']) ? $errors['pword'] : '' ?></span>
                </div>

                <div class="form-group col-md-4 <?= (isset($errors['pword'])) ? 'has-error' : '' ?>">
                  <label for="confpword">Confirm Password</label>
                  <input type="password" class="form-control" id="confpword" placeholder="Confirm new password" value="<?= isset($_POST['confpword']) ? $_POST['confpword'] : '' ?>" name="confpword">
                  <span class="help-block"><?= isset($errors['confpword']) ? $errors['confpword'] : '' ?></span>
                </div>

                <div class="form-group col-md-4">
                  <label for="photo">Photo</label>
                  <input type="file" name="staff_photo" id="staff_photo">
                  <p class="help-block">Upload Photo</p>
                  <img src="<?= $PHOTO ?>" id="img_preview" class="img img-responsive" style="height:150px" />
                </div>

                <div class="form-group col-md-4">
                  <label for="photo">Photo ID Proof</label>
                  <input type="file" name="staff_photoid" id="staff_photoid">
                  <p class="help-block">Upload Photo ID Proof</p>
                  <img src="<?= $PHOTO_ID ?>" id="staff_photoid_preview" class="img img-responsive" style="height:150px" />
                </div>

                <div class="form-group col-md-4">
                  <label for="status">Status</label>
                  <?php
                  $ACTIVE =  isset($_POST['status']) ? $_POST['status'] : $ACTIVE;
                  ?>
                  <div class="radio">
                    <label>
                      <input name="status" id="status1" value="1" <?= ($ACTIVE == 1) ? 'checked="checked"' : '' ?> type="radio">
                      Active
                    </label>
                    <label>
                      <input name="status" id="status2" value="0" <?= ($ACTIVE == 0) ? 'checked="checked"' : '' ?> type="radio">
                      In-Active
                    </label>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="box-header with-border">
                    <h3 class="box-title mb-4" style="text-align:left">Add/Update Responsibilities</h3>
                  </div>
                  <div class="form-group row <?= (isset($errors['responsibilities'])) ? 'has-error' : '' ?>">
                    <span class="help-block"><?= isset($errors['responsibilities']) ? $errors['responsibilities'] : '' ?></span>
                    <?php
                    $responsibilities = isset($_POST['responsibilities']) ? $_POST['responsibilities'] : $STAFF_RESPONSIBILITIES;
                    $sqlmenu = "SELECT DISTINCT MENU_NAME FROM user_responsibilities_master WHERE USER_ROLE=8";
                    $resmenu = $db->execQuery($sqlmenu);
                    if ($resmenu && $resmenu->num_rows > 0) {
                      //echo '<ul>';
                      while ($datamenu = $resmenu->fetch_assoc()) {
                        $menuname = $datamenu['MENU_NAME'];
                        echo "<div class='mb-4 col-md-3'><h4>$menuname</h4>";
                        $resp = $db->get_responsibilities('', 8, " AND MENU_NAME='$menuname'");
                        if ($resp != '') {

                          while ($data = $resp->fetch_assoc()) {
                            extract($data);
                            $checked = '';
                            if (is_array($responsibilities) && !empty($responsibilities)) {
                              $checked = in_array($RESPONSIBILITY_STR, $responsibilities) ? 'checked="checked"' : '';
                            }
                    ?>
                            <div class="checkbox">
                              <label>
                                <input type="checkbox" value="<?= $RESPONSIBILITY_STR ?>" name="responsibilities[]" <?= $checked ?>>
                                <?= $RESPONSIBILITY ?>
                              </label>
                            </div>

                    <?php

                          }
                        }
                        echo "</div>";
                      }
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="box-footer text-center">
              <input type="submit" class="btn btn-primary" name="update_staff" value="Update Staff" /> &nbsp;&nbsp;&nbsp;
              <a href="page.php?page=listStaff" class="btn btn-warning" title="Cancel">Cancel</a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>