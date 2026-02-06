<?php
$action = isset($_POST['add_advertise']) ? $_POST['add_advertise'] : '';
include_once('include/classes/tools.class.php');
$tools = new tools();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 5) {
  $institute_id = $db->get_parent_id($user_role, $user_id);
  $staff_id = $user_id;
} else {
  $institute_id = $user_id;
  $staff_id = 0;
}

if ($action != '') {
  $result = $tools->add_advertise();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listAdvertisement');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add New Advertise</h4>
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

            <input type="hidden" name="institute_id" value="<?= isset($institute_id) ? $institute_id : '' ?>" />
            <div class="form-group">
              <label for="exampleInputName1">Title</label>
              <input type="text" class="form-control" id="exampleInputName1" name="name" placeholder="name" value="">
            </div>
            <div class="form-group">
              <label for="exampleInputName1">Link</label>
              <input type="text" class="form-control" id="exampleInputName1" name="link" placeholder="link" value="">
            </div>
            <div class="form-group">
              <label>Advertise Image</label>
              <input type="file" name="advertiseimage" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <input type="submit" name="add_advertise" class="btn btn-primary mr-2" value="Submit">
            <a href="page.php?page=listAdvertisement" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>