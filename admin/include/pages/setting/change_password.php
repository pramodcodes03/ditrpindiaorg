<?php

//include_once('include/controller/admin/setting/update_admin.php');
$admin_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_admin']) ? $_POST['update_admin'] : '';
include_once('include/classes/admin.class.php');
$admin = new admin();

if ($action != '') {

  $admin_id = isset($_POST['admin_id']) ? $_POST['admin_id'] : '';

  $result = $admin->update_admin($admin_id);

  $result = json_decode($result, true);

  $success = isset($result['success']) ? $result['success'] : '';

  $message = $result['message'];

  $errors = isset($result['errors']) ? $result['errors'] : '';

  if ($success == true) {

    $_SESSION['msg'] = $message;

    $_SESSION['msg_flag'] = $success;

    //header('location:change-password');

  }
}

/* get institute details */

$res = $admin->list_admin_data($admin_id, '');

if ($res != '') {



  while ($data = $res->fetch_assoc()) {

    $ADMIN_ID     = $data['ADMIN_ID'];

    $FIRST_NAME  = $data['FIRST_NAME'];

    $MIDDLE_NAME   = $data['MIDDLE_NAME'];

    $LAST_NAME = $data['LAST_NAME'];

    $USER_EMAIL    = $data['USER_EMAIL'];

    $MOBILE    = $data['MOBILE'];

    $PHOTO    = $data['PHOTO'];



    $ACTIVE       = $data['ACTIVE'];

    $DELETE_FLAG       = $data['DELETE_FLAG'];

    $CREATED_ON     = $data['CREATED_ON'];

    $CREATED_ON_IP    = $data['CREATED_ON_IP'];

    $CREATED_BY     = $data['CREATED_BY'];

    $UPDATED_ON     = $data['UPDATED_ON'];

    $UPDATED_ON_IP    = $data['UPDATED_ON_IP'];

    $UPDATED_BY     = $data['UPDATED_BY'];



    $USER_LOGIN_ID     = $data['USER_LOGIN_ID'];

    $USER_NAME       = $data['USER_NAME'];

    $REG_DATE       = $data['REG_DATE'];

    $EXP_DATE       = $data['EXP_DATE'];
  }
}



?>

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1>

      Settings



    </h1>

    <ol class="breadcrumb">

      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Settings</li>

    </ol>

  </section>



  <!-- Main content -->

  <section class="content">

    <div class="row">

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



      <div class="col-md-12">

        <div class="nav-tabs-custom">

          <ul class="nav nav-tabs">

            <li class="active"><a href="#account" data-toggle="tab">Account</a></li>

            <li><a href="#change-password" data-toggle="tab">Change Password</a></li>

          </ul>

          <div class="tab-content">

            <div class="active tab-pane" id="account">

              <?php

              $profile = $access->get_user_details($_SESSION['user_id'], $_SESSION['user_login_id'], $_SESSION['user_role']);

              if ($profile != '') {

                while ($data = $profile->fetch_assoc()) {

                  $ADMIN_ID     = $data['ADMIN_ID'];

                  $FIRST_NAME   = $data['FIRST_NAME'];

                  $MIDDLE_NAME   = $data['MIDDLE_NAME'];

                  $LAST_NAME     = $data['LAST_NAME'];

                  $USER_EMAIL   = $data['USER_EMAIL'];

                  $MOBILE     = $data['MOBILE'];

                  $PHOTO     = $data['PHOTO'];

                  $ACTIVE     = $data['ACTIVE'];

                  $DELETE_FLAG   = $data['DELETE_FLAG'];

                  $CREATED_BY   = $data['CREATED_BY'];

                  $CREATED_ON   = $data['CREATED_DATE'];

                  $UPDATED_BY   = $data['UPDATED_BY'];

                  $UPDATED_ON   = $data['UPDATED_DATE'];

                  $UPDATED_ON   = $data['UPDATED_DATE'];



                  //if($PHOTO!='')

                  //	$PHOTO = '';	

                  $PHOTO = 'resources/dist/img/default_user.png';





              ?>

                  <form class="form-horizontal" action="" method="post">

                    <div class="form-group col-sm-12 <?= (isset($errors['fname'])) ? 'has-error' : '' ?>">

                      <label for="fname" class="col-sm-2 control-label">First Name</label>



                      <div class="col-sm-6">

                        <input type="text" class="form-control" id="fname" name="fname" value="<?= isset($_POST['fname']) ? $_POST['fname'] : $FIRST_NAME ?>" placeholder="First Name">

                        <span class="help-block"><?= isset($errors['fname']) ? $errors['fname'] : '' ?></span>

                      </div>

                    </div>



                    <div class="form-group col-sm-12 <?= (isset($errors['mname'])) ? 'has-error' : '' ?>">

                      <label for="mname" class="col-sm-2 control-label">Middle Name</label>



                      <div class="col-sm-6">

                        <input type="text" class="form-control" id="mname" name="mname" value="<?= isset($_POST['mname']) ? $_POST['mname'] : $MIDDLE_NAME ?>" placeholder="Middle Name">

                        <span class="help-block"><?= isset($errors['mname']) ? $errors['mname'] : '' ?></span>

                      </div>

                    </div>



                    <div class="form-group col-sm-12 <?= (isset($errors['lname'])) ? 'has-error' : '' ?>">

                      <label for="lname" class="col-sm-2 control-label">Last Name</label>



                      <div class="col-sm-6">

                        <input type="text" class="form-control" id="lname" name="lname" value="<?= isset($_POST['lname']) ? $_POST['lname'] : $LAST_NAME ?>" placeholder="Last Name">

                        <span class="help-block"><?= isset($errors['lname']) ? $errors['lname'] : '' ?></span>

                      </div>

                    </div>



                    <div class="form-group col-sm-12 <?= (isset($errors['uemail'])) ? 'has-error' : '' ?>">

                      <label for="uemail" class="col-sm-2 control-label">Email</label>



                      <div class="col-sm-6">

                        <input type="email" class="form-control" id="uemail" name="uemail" value="<?= isset($_POST['uemail']) ? $_POST['uemail'] : $USER_EMAIL ?>" placeholder="Email">

                        <span class="help-block"><?= isset($errors['uemail']) ? $errors['uemail'] : '' ?></span>

                      </div>

                    </div>



                    <div class="form-group col-sm-12 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">

                      <label for="mobile" class="col-sm-2 control-label">Mobile</label>



                      <div class="col-sm-6">

                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $MOBILE ?>" placeholder="Mobile">

                        <span class="help-block"><?= isset($errors['mobile']) ? $errors['mobile'] : '' ?></span>

                      </div>

                    </div>



                    <div class="form-group col-sm-12">

                      <label for="photo" class="col-sm-2 control-label">Profile Photo</label>



                      <div class="col-sm-6">

                        <img src="<?= $PHOTO ?>" class="img img-responsive" style="height:50px; width:50px" />

                        <input type="file" id="photo" name="photo">

                      </div>

                    </div>



                    <div class="form-group">

                      <div class="col-sm-offset-2 col-sm-10">



                        <input type="submit" class="btn btn-primary" name="update_admin" value="Update" />

                      </div>

                    </div>

                  </form>

              <?php

                }
              }

              ?>

            </div>

            <!-- /.tab-pane -->





            <div class="tab-pane" id="change-password">

              <form class="form-horizontal" action="" method="post">

                <div class="form-group <?= (isset($errors['currentpassword'])) ? 'has-error' : '' ?>">

                  <label for="currentpassword" class="col-sm-2 control-label">Current Password</label>



                  <div class="col-sm-10">

                    <input type="password" class="form-control" style="width:400px;" id="currentpassword" value="<?= isset($_POST['currentpassword']) ? $_POST['currentpassword'] : '' ?>" placeholder="Current Password">

                    <span class="help-block"><?= isset($errors['currentpassword']) ? $errors['currentpassword'] : '' ?></span>

                  </div>

                </div>



                <div class="form-group">

                  <label for="newpassword" class="col-sm-2 control-label">New Password</label>



                  <div class="col-sm-10">

                    <input type="password" class="form-control" style="width:400px;" id="newpassword" placeholder="New Password">

                  </div>

                </div>



                <div class="form-group">

                  <label for="confpassword" class="col-sm-2 control-label">Confirm Password</label>



                  <div class="col-sm-10">

                    <input type="password" class="form-control" id="confpassword" style="width:400px;" placeholder="Confirm Password">

                  </div>

                </div>



                <div class="form-group">

                  <div class="col-sm-offset-2 col-sm-10">

                    <button type="submit" name="update_password" class="btn btn-danger">Submit</button>

                  </div>

                </div>

              </form>

            </div>

            <!-- /.tab-pane -->

          </div>

          <!-- /.tab-content -->

        </div>

        <!-- /.nav-tabs-custom -->

      </div>

      <!-- /.row -->

  </section>

  <!-- /.content -->

</div>