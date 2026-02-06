<?php
$exam_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_exam']) ? $_POST['update_exam'] : '';
include_once('include/classes/tools.class.php');
$exam = new tools();
if ($action != '') {
  $result = $exam->update_video($exam_id);
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
/* get exam details */
$res = $exam->list_video($exam_id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {

    extract($data);
  }
}
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Update Video Details

    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="page.php?page=list-exams">Video</a></li>
      <li class="active">Update Video Details</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

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
              <h3 class="box-title">Update Video Details</h3>
            </div>
            <div class="box-body">
              <input type="hidden" name="exam_id" value="<?= $CONTEST_ID ?>" />


              <div class="form-group col-sm-12 <?= (isset($errors['contestname'])) ? 'has-error' : '' ?>">
                <label for="contestname" class="col-sm-2 control-label">https://www.youtube.com/watch?v=
                </label>
                <div class="col-sm-8">
                  <input class="form-control" id="contestname" name="contestname" placeholder="Type Video ID After v=" value="<?= isset($_POST['contestname']) ? $_POST['contestname'] : $RESULT_STATE ?>" type="text">
                  <span class="help-block"><?= isset($errors['contestname']) ? $errors['contestname'] : '' ?></span>
                </div>
              </div>







              <div class="form-group col-sm-12">
                <?php $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;  ?>
                <label for="status" class="col-sm-2 control-label">Status</label>
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
              <a href="page.php?page=list-result" class="btn btn-default">Cancel</a>
              <input type="submit" name="update_exam" class="btn btn-info" value="Update Video" />
            </div>
          </div>
        </div>




      </div>
    </form>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>