<?php
$action = isset($_POST['add_staff']) ? $_POST['add_staff'] : '';
include_once('include/classes/account.class.php');
$account = new account();
if ($action != '') {
  $result = $account->add_admin_staff();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = isset($result['message']) ? $result['message'] : '';
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
          <h4 class="card-title">Add New Staff Member</h4>
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
                  </div>
                </div>
              </div>
            <?php
            }
            ?>


            <div class="box-body">
              <div class="row">
                <input type="hidden" name="role" class="form-control" id="role" value="3">
                <div class="form-group col-md-4 <?= (isset($errors['fullname'])) ? 'has-error' : '' ?>">
                  <label for="fullname">Fullname</label>
                  <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Enter fullname" value="<?= isset($_POST['fullname']) ? $_POST['fullname'] : '' ?>">
                  <span class="help-block"><?= isset($errors['fullname']) ? $errors['fullname'] : '' ?></span>
                </div>

                <div class="form-group col-md-4 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
                  <label for="email">Email</label>
                  <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                  <span class="help-block"><?= isset($errors['email']) ? $errors['email'] : '' ?></span>
                </div>

                <div class="form-group col-md-4 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
                  <label for="mobile">Mobile</label>
                  <input type="text" name="mobile" class="form-control" maxlength="10" id="mobile" placeholder="Mobile" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : '' ?>">
                  <span class="help-block"><?= isset($errors['mobile']) ? $errors['mobile'] : '' ?></span>
                </div>

                <div class="form-group col-md-4">
                  <label>Date Of Birth:</label>
                  <input class="form-control pull-right" name="dob" id="dob" type="date">
                </div>

                <div class="form-group col-md-4">
                  <label>Gender</label>
                  <select class="form-control" name="gender" id="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                  </select>
                </div>

                <div class="form-group col-md-4">
                  <label>Address</label>
                  <textarea class="form-control" rows="3" placeholder="Permanent address..." name="per_add" id="per_add"></textarea>
                </div>


                <div class="form-group col-md-4 <?= (isset($errors['uname'])) ? 'has-error' : '' ?>">
                  <label for="uname">Username</label>
                  <input type="text" class="form-control" id="uname" placeholder="Username" name="uname" value="<?= isset($_POST['uname']) ? $_POST['uname'] : '' ?>" style="text-transform:none !important;">
                  <span class="help-block"><?= isset($errors['uname']) ? $errors['uname'] : '' ?></span>
                </div>

                <div class="form-group col-md-4 <?= (isset($errors['pword'])) ? 'has-error' : '' ?>">
                  <label for="pword">Password</label>
                  <input type="password" class="form-control" id="pword" placeholder="Password" name="pword" value="<?= isset($_POST['pword']) ? $_POST['pword'] : '' ?>">
                  <span class="help-block"><?= isset($errors['pword']) ? $errors['pword'] : '' ?></span>
                </div>

                <div class="form-group col-md-4 <?= (isset($errors['pword'])) ? 'has-error' : '' ?>">
                  <label for="confpword">Confirm Password</label>
                  <input type="password" class="form-control" id="confpword" placeholder="Confirm password" name="confpword" value="<?= isset($_POST['confpword']) ? $_POST['confpword'] : '' ?>">
                  <span class="help-block"><?= isset($errors['confpword']) ? $errors['confpword'] : '' ?></span>
                </div>

                <div class="form-group col-md-4">
                  <label for="photo">Photo</label>
                  <input type="file" name="photo" id="photo">
                  <p class="help-block">Upload Photo</p>
                </div>

                <div class="form-group col-md-4">
                  <label for="photo">Photo ID Proof</label>
                  <input type="file" name="photoid" id="photoid">
                  <p class="help-block">Upload Photo ID Proof</p>
                </div>

                <div class="form-group col-md-4">
                  <label for="status">Status</label>
                  <?php
                  $status = isset($_POST['status']) ? $_POST['status'] : 1;
                  ?>
                  <div class="radio">
                    <label>
                      <input name="status" id="status1" value="1" <?= ($status == 1) ? 'checked=""' : '' ?> type="radio">
                      Active
                    </label>
                    <label>
                      <input name="status" id="status2" value="0" <?= ($status == 0) ? 'checked=""' : '' ?> type="radio">
                      In-Active
                    </label>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="box-header with-border">
                    <h3 class="box-title mb-4" style="text-align:left">Add Responsibilities</h3>
                  </div>
                  <div class="form-group row <?= (isset($errors['responsibilities'])) ? 'has-error' : '' ?>">
                    <span class="help-block"><?= isset($errors['responsibilities']) ? $errors['responsibilities'] : '' ?></span>
                    <?php
                    $responsibilities = isset($_POST['responsibilities']) ? $_POST['responsibilities'] : '';
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
              <input type="submit" class="btn btn-primary" name="add_staff" value="Add Staff" /> &nbsp;&nbsp;&nbsp;
              <a href="page.php?page=listStaff" class="btn btn-warning" title="Cancel">Cancel</a>

            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>