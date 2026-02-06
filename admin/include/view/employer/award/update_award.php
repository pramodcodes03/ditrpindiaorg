<?php
$id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_award']) ? $_POST['update_award'] : '';
include_once('include/classes/course.class.php');
$course = new course();
if ($action != '') {
  $result = $course->update_award($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listAwardCategories');
  }
}
/* get exam details */
$res = $course->list_award($id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {

    extract($data);
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Update Award Categories</h4>
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $AWARD_ID ?>" />
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
              <div class="col-md-6 form-group">
                <label for="award_name">Award Categories Name</label>
                <input type="text" class="form-control" id="award_name" name="award_name" placeholder="Award Categories Name" value="<?= isset($_POST['award_name']) ? $_POST['award_name'] : $AWARD ?>">
              </div>
            </div>

            <input type="submit" name="update_award" class="btn btn-primary mr-2" value="Submit">
            <a href="page.php?page=listAwardCategories" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>