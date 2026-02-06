<?php
//print_r($_SESSION);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
/*if($user_role==5){
   $institute_id = $db->get_parent_id($user_role,$user_id);
   $staff_id = $user_id;
}
else{
   $institute_id = $user_id;
   $staff_id = 0;
}
*/
//$inst_mobile = $db->get_institute_mobile($institute_id);
//$inst_email = $db->get_institute_email($institute_id);

$action = isset($_POST['add_support']) ? $_POST['add_support'] : '';
include_once('include/classes/amc.class.php');
$amc = new amc();

if ($action != '') {


  $result = $amc->add_support();
  $result = json_decode($result, true);
  print_r($result);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=list-support');
  }
}
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add New Support

    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Help Support</a></li>
      <li class="active">Add New Support</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <form class="form-horizontal form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');">

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
              <?php
              if (isset($errors) && !empty($errors)) {
                echo '<ul>';
                foreach ($errors as $key => $value) {
                  echo '<li>' . $value . '</li>';
                }
                echo '</ul>';
              }
              ?>
            </div>
          </div>
        </div>
      <?php
      }
      ?>

      <div class="row">
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add New Support</h3>
            </div>
            <div class="box-body">
              <input type="hidden" name="user_id" value="<?= $user_id ?>" />
              <div class="form-group col-md-6 <?= (isset($errors['supporttype'])) ? 'has-error' : '' ?>">
                <label for="supporttype" class="col-sm-4 control-label">Selcct Support Type</label>
                <div class="col-sm-6">
                  <select class="form-control" name="supporttype" id="supporttype" onchange="Category_by_type(this.value)">
                    <?php
                    $supporttype = isset($_POST['supporttype']) ? $_POST['supporttype'] : '';
                    echo $db->MenuItemsDropdown('help_support_type', 'SUPPORT_TYPE_ID', 'SUPPORT_NAME', 'SUPPORT_TYPE_ID,SUPPORT_NAME', $supporttype, ' WHERE ACTIVE=1 AND DELETE_FLAG=0'); ?>
                  </select>
                  <span class="help-block"><?= (isset($errors['supporttype'])) ? $errors['supporttype'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['supportcat'])) ? 'has-error' : '' ?>">
                <label for="supportcat" class="col-sm-4 control-label">Select Support Category</label>
                <div class="col-sm-6">
                  <select class="form-control" name="supportcat" id="supportcat1">
                    <?php
                    $supportcat = isset($_POST['supportcat']) ? $_POST['supportcat'] : '';
                    echo $db->MenuItemsDropdown('help_support_category', 'SUPPORT_CAT_ID', 'CATEGORY_NAME', 'SUPPORT_CAT_ID,CATEGORY_NAME', $supportcat, ' WHERE ACTIVE=1 AND DELETE_FLAG=0'); ?>
                  </select>
                  <span class="help-block"><?= (isset($errors['supportcat'])) ? $errors['supportcat'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['authorname'])) ? 'has-error' : '' ?>">
                <label for="authorname" class="col-sm-4 control-label">Author Name</label>
                <div class="col-sm-6">
                  <input class="form-control" id="authorname" name="authorname" placeholder="Author Name" value="<?= isset($_POST['authorname']) ? $_POST['authorname'] : '' ?>" type="text">
                  <span class="help-block"><?= (isset($errors['authorname'])) ? $errors['authorname'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
                <label for="mobile" class="col-sm-4 control-label">Mobile</label>
                <div class="col-sm-6">
                  <input class="form-control" id="mobile" name="mobile" maxlength="10" placeholder="Mobile" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : '' ?>" type="text">
                  <span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['altmobile'])) ? 'has-error' : '' ?>">
                <label for="altmobile" class="col-sm-4 control-label">Alternate Mobile</label>
                <div class="col-sm-6">
                  <input class="form-control" id="altmobile" name="altmobile" maxlength="10" placeholder="Alternate Mobile" value="<?= isset($_POST['altmobile']) ? $_POST['altmobile'] : '' ?>" type="text">
                  <span class="help-block"><?= (isset($errors['altmobile'])) ? $errors['altmobile'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
                <label for="email" class="col-sm-4 control-label">Email</label>
                <div class="col-sm-6">
                  <input class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" type="email" onchange="document.getElementById('uname').value = this.value;">
                  <span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['altemail'])) ? 'has-error' : '' ?>">
                <label for="altemail" class="col-sm-4 control-label">Alternate Email</label>
                <div class="col-sm-6">
                  <input class="form-control" id="altemail" name="altemail" placeholder="altEmail" value="<?= isset($_POST['altemail']) ? $_POST['altemail'] : '' ?>" type="altemail" onchange="document.getElementById('uname').value = this.value;">
                  <span class="help-block"><?= (isset($errors['altemail'])) ? $errors['altemail'] : '' ?></span>
                </div>
              </div>

              <div class="form-group  col-md-12 <?= (isset($errors['description'])) ? 'has-error' : '' ?>">
                <label for="description" class="col-sm-2 control-label">Description </label>
                <div class="col-sm-5">
                  <textarea class="form-control" id="description" name="description" placeholder="Description" type="text" rows="6"><?= isset($_POST['description']) ? $_POST['description'] : '' ?></textarea>
                  <span class="help-block"><?= (isset($errors['description'])) ? $errors['description'] : '' ?></span>
                </div>
              </div>

              <div class="form-group <?= (isset($errors['supportfiles'])) ? 'has-error' : '' ?>">
                <label for="supportfiles" class="col-sm-2 control-label">Attach Files</label>
                <div class="col-sm-8">
                  <input id="supportfiles" name="supportfiles[]" multiple type="file">
                  <p class="help-block"><?= (isset($errors['supportfiles'])) ? $errors['supportfiles'] : 'Please Upload Issues Photos Here' ?></p>
                </div>
              </div>


              <div class="form-group">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : 0;
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
              <a href="page.php?page=list-support" class="btn btn-default">Cancel</a>
              <input type="submit" name="add_support" class="btn btn-info" value="Add Support" />
            </div>
          </div>
        </div>
      </div>
    </form>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>