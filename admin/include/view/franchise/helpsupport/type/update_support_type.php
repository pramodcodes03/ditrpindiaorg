<?php
$supporttype_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_support_type']) ? $_POST['update_support_type'] : '';
include_once('include/classes/helpsupport.class.php');
$helpsupport = new helpsupport();
if ($action != '') {
  $result = $helpsupport->update_support_type($supporttype_id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=list-support-type');
  }
}
/* get course details */
$res = $helpsupport->list_support_type($supporttype_id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    $SUPPORT_TYPE_ID    = $data['SUPPORT_TYPE_ID'];
    $SUPPORT_NAME       = $data['SUPPORT_NAME'];
    $ACTIVE         = $data['ACTIVE'];
    $CREATED_BY   = $data['CREATED_BY'];
    $CREATED_ON   = $data['CREATED_ON'];
  }
}
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Update Support Type

    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="list-support-type">Help Support</a></li>
      <li class="active">Update Support Type </li>
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



        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Update Support Type</h3>
            </div>
            <div class="box-body">
              <input type="hidden" name="supporttype_id" value="<?= isset($SUPPORT_TYPE_ID) ? $SUPPORT_TYPE_ID : '' ?>" />

              <div class="form-group col-sm-6 <?= (isset($errors['supporttype'])) ? 'has-error' : '' ?>">
                <label for="supporttype" class="col-sm-4 control-label">Support Type Name</label>
                <div class="col-sm-8">
                  <input class="form-control" id="supporttype" name="supporttype" placeholder="Support Type" value="<?= isset($_POST['supporttype']) ? $_POST['supporttype'] : $SUPPORT_NAME ?>" type="text">
                  <span class="help-block"><?= isset($errors['supporttype']) ? $errors['supporttype'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-12">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;
                ?>
                <label for="status" class="col-sm-2 control-label">Status</label>
                <div class="radio col-sm-10">
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
              <a href="page.php?page=list-support-type" class="btn btn-default">Cancel</a>
              <input type="submit" name="update_support_type" class="btn btn-info" value="Update Support Type" />
            </div>
          </div>
        </div>




      </div>
    </form>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>