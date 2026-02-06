<?php
$action = isset($_POST['add_support_cat']) ? $_POST['add_support_cat'] : '';
include_once('include/classes/helpsupport.class.php');
$helpsupport = new helpsupport();
if ($action != '') {
  $result = $helpsupport->add_support_cat();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=list-support-cat');
  }
}
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add Support Category

    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="page.php?page=list-support-cat">Help Support</a></li>
      <li class="active">Add Support Category </li>
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


        <div class="col-md-2">
        </div>
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add Support Category</h3>
            </div>
            <div class="box-body">
              <div class="form-group col-sm-12 <?= (isset($errors['supporttype'])) ? 'has-error' : '' ?>">
                <label for="supporttype" class="col-sm-2 control-label">Select Support Type</label>
                <div class="col-sm-4">
                  <select class="form-control" id="supporttype" name="supporttype">
                    <?php
                    $support_type = isset($_POST['supporttype']) ? $_POST['supporttype'] : '';
                    echo $db->MenuItemsDropdown('help_support_type', 'SUPPORT_TYPE_ID', 'SUPPORT_NAME', 'SUPPORT_TYPE_ID,SUPPORT_NAME', $support_type, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                    ?>
                  </select>
                  <span class="help-block"><?= isset($errors['supporttype']) ? $errors['supporttype'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-12 <?= (isset($errors['supportcat'])) ? 'has-error' : '' ?>">
                <label for="supportcat" class="col-sm-2 control-label">Support Category Name</label>
                <div class="col-sm-4">
                  <input class="form-control" id="supportcat" name="supportcat" placeholder="Support Category Name" value="<?= isset($_POST['supportcat']) ? $_POST['supportcat'] : '' ?>" type="text">
                  <span class="help-block"><?= isset($errors['supportcat']) ? $errors['supportcat'] : '' ?></span>
                </div>
              </div>
              <div class="form-group col-sm-12">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : 0;
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
              <a href="page.php?page=list-support-cat" class="btn btn-default">Cancel</a>
              <input type="submit" name="add_support_cat" class="btn btn-info" value="Add Support Category" />
            </div>
          </div>
        </div>
      </div>
    </form>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>