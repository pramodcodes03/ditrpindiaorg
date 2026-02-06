<?php
$action = isset($_POST['add_contestdetail']) ? $_POST['add_contestdetail'] : '';
include_once('include/classes/tools.class.php');
$exam = new tools();
if ($action != '') {

  $result = $exam->add_video();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=list-video');
  }
}
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add Video Details

    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="page.php?page=list-result">Video Details</a></li>
      <li class="active">Add Video Details</li>
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
              <h3 class="box-title">Add Video Details</h3>
            </div>
            <div class="box-body">


              <div class="form-group col-sm-12 <?= (isset($errors['contestname'])) ? 'has-error' : '' ?>">
                <label for="contestname" class="col-sm-4 control-label">https://www.youtube.com/watch?v=</label>
                <div class="col-sm-6">
                  <input class="form-control" id="contestname" name="contestname" placeholder="Type Video ID After v=" value="<?= isset($_POST['contestname']) ? $_POST['contestname'] : '' ?>" type="text">
                  <span class="help-block"><?= isset($errors['contestname']) ? $errors['contestname'] : '' ?></span>
                </div>
              </div>

              <!--<div class="form-group col-sm-12 <?= (isset($errors['course_img'])) ? 'has-error' : '' ?>">-->
              <!--    <label for="course_img" class="col-sm-2 control-label">Result Image</label>-->
              <!--    <div class="col-sm-8">-->
              <!--        <input id="course_img" name="course_img" type="file" />-->

              <!--        <span class="help-block"><?= isset($errors['course_img']) ? $errors['course_img'] : '' ?></span>-->
              <!--    </div>-->
              <!--</div>-->

              <div class="form-group col-sm-12">

                <label for="status" class="col-sm-4 control-label">Status</label>
                <div class="radio col-sm-6">
                  <label>
                    <input name="status" id="optionsRadios1" value="1" type="radio"> Active
                  </label>
                  <label>
                    <input name="status" id="optionsRadios2" value="0" type="radio"> Inactive
                  </label>
                </div>
              </div>



            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="page.php?page=list-result" class="btn btn-default">Cancel</a>
              <input type="submit" name="add_contestdetail" class="btn btn-info" value="Add Video" />
            </div>
          </div>
        </div>




      </div>
    </form>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>