<?php
$action = isset($_POST['add_contestdetail']) ? $_POST['add_contestdetail'] : '';
include_once('include/classes/tools.class.php');
$exam = new tools();
if ($action != '') {

  $result = $exam->add_slidernew();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=list-slider');
  }
}
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add Slider Detail

    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="page.php?page=list-result">Slider Details</a></li>
      <li class="active">Add Slider Details</li>
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


        <div class="col-md-2">
        </div>
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add Slider Details </h3>
            </div>
            <div class="box-body">

              <div class="form-group col-sm-12 <?= (isset($errors['course_img'])) ? 'has-error' : '' ?>">
                <label for="course_img" class="col-sm-2 control-label">Slider Image</label>
                <div class="col-sm-8">
                  <input id="course_img" name="course_img" type="file" />

                  <span class="help-block"><?= isset($errors['course_img']) ? $errors['course_img'] : '' ?></span>
                </div>
              </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="page.php?page=list-slider" class="btn btn-default">Cancel</a>
              <input type="submit" name="add_contestdetail" class="btn btn-info" value="Add Slider" />
            </div>
          </div>
        </div>




      </div>
    </form>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>