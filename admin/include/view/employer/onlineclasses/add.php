<?php
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 3) {
  $institute_id = $db->get_parent_id($user_role, $user_id);
  $staff_id = $user_id;
} else {
  $institute_id = $user_id;
  $staff_id = 0;
}
$action = isset($_POST['add_onlineclasses_details']) ? $_POST['add_onlineclasses_details'] : '';
include_once('include/classes/tools.class.php');
$tools = new tools();
if ($action != '') {
  $result = $tools->add_onlineclasses_details();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listOnlineClasses');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add Online Classes</h4>
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
            <input type="hidden" class="form-control" id="institute_id" name="institute_id" value="<?= $institute_id ?>">
            <div class="form-group <?= (isset($errors['course_id'])) ? 'has-error' : '' ?>">
              <label for="course_id">Course of interest <span class="asterisk">*</span></label>
              <?php $course_id  = isset($_POST['course_id']) ? $_POST['course_id'] : ''; ?>
              <select class="form-control select2" name="course_id" data-placeholder="Select a Course" id="coursename" required>
                <option name="" value="">Select a Course</option>
                <?php
                $sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.COURSE_TYPE FROM institute_courses A WHERE  A.INSTITUTE_ID='$institute_id' AND A.DELETE_FLAG=0 AND A.ACTIVE=1";
                //echo $sql;
                $ex = $db->execQuery($sql);
                if ($ex && $ex->num_rows > 0) {
                  while ($data = $ex->fetch_assoc()) {
                    $INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
                    $COURSE_ID        = $data['COURSE_ID'];
                    $MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];

                    if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
                      $course        = $db->get_course_detail($COURSE_ID);
                      $course_name      = $course['COURSE_NAME_MODIFY'];
                    }

                    if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
                      $course        = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
                      $course_name      = $course['COURSE_NAME_MODIFY'];
                    }

                    $selected = (is_array($course_id) && in_array($INSTITUTE_COURSE_ID, $course_id)) ? 'selected="selected"' : '';

                    echo '<option value="' . $INSTITUTE_COURSE_ID . '" ' . $selected . '>' . $course_name . '</option>';
                  }
                }
                ?>
              </select>
              <span class="help-block"><?= (isset($errors['course_id'])) ? $errors['course_id'] : '' ?></span>
            </div>
            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control" id="title" name="title" placeholder="title" value="">
            </div>

            <div class="form-group">
              <label>Link</label>
              <input type="text" class="form-control" id="link" name="link" placeholder="link" value="">
            </div>

            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control" id="description" rows="4" name="description"></textarea>
            </div>

            <div class="form-group">
              <label>Expiry Date</label>
              <input type="date" class="form-control" id="expirydate" name="expirydate" placeholder="Expiry Date" value="">
            </div>

            <input type="submit" name="add_onlineclasses_details" class="btn btn-primary mr-2" value="Submit">
            <a href="page.php?page=listOnlineClasses" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>