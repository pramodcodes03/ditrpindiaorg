<?php
$id = $db->test(isset($_GET['id']) ? $_GET['id'] : '');
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 5) {
  $institute_id = $db->get_parent_id($user_role, $user_id);
  $staff_id = $user_id;
} else {
  $institute_id = $user_id;
  $staff_id = 0;
}

$action = isset($_POST['update_old_certificate']) ? $_POST['update_old_certificate'] : '';
include_once('include/classes/tools.class.php');
$tools = new tools();
if ($action != '') {
  $result = $tools->update_old_certificate();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = isset($result['message']) ? $result['message'] : '';
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=oldCertificate');
  }
}
$res = $tools->list_oldcertificates($id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    extract($data);
    //print_r($data); exit();
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Edit Old Certficate
          </h4>
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
            <input type="hidden" class="form-control" name="id" placeholder="id" value="<?= $id ?>">
            <div class="row">

              <div class="col-md-6 form-group">
                <label for="cert_number">Certificate Number</label>
                <input type="text" class="form-control" id="cert_number" name="cert_number" placeholder="cert_number" value="<?= isset($_POST['cert_number']) ? $_POST['cert_number'] : $cert_number ?>">
                <span class="help-block"><?= isset($errors['cert_number']) ? $errors['cert_number'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label for="cert_date">Certificate Date</label>
                <input type="text" class="form-control" id="cert_date" name="cert_date" placeholder="cert_date" value="<?= isset($_POST['cert_date']) ? $_POST['cert_date'] : $cert_date ?>">
                <span class="help-block"><?= isset($errors['cert_date']) ? $errors['cert_date'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label for="name">Name Of Student</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="name" value="<?= isset($_POST['name']) ? $_POST['name'] : $name ?>">
                <span class="help-block"><?= isset($errors['name']) ? $errors['name'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label for="course_name">Course Name</label>
                <input type="text" class="form-control" id="course_name" name="course_name" placeholder="course_name" value="<?= isset($_POST['course_name']) ? $_POST['course_name'] : $course_name ?>">
                <span class="help-block"><?= isset($errors['course_name']) ? $errors['course_name'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label for="course_duration">Course Duration</label>
                <input type="text" class="form-control" id="course_duration" name="course_duration" placeholder="course_duration" value="<?= isset($_POST['course_duration']) ? $_POST['course_duration'] : $course_duration ?>">
                <span class="help-block"><?= isset($errors['course_duration']) ? $errors['course_duration'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label for="marks">Marks</label>
                <input type="text" class="form-control" id="marks" name="marks" placeholder="marks" value="<?= isset($_POST['marks']) ? $_POST['marks'] : $marks ?>">
                <span class="help-block"><?= isset($errors['marks']) ? $errors['marks'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label for="grade">Grade</label>
                <input type="text" class="form-control" id="grade" name="grade" placeholder="grade" value="<?= isset($_POST['grade']) ? $_POST['grade'] : $grade ?>">
                <span class="help-block"><?= isset($errors['grade']) ? $errors['grade'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label for="institute_name">Institute Name</label>
                <input type="text" class="form-control" id="institute_name" name="institute_name" placeholder="institute_name" value="<?= isset($_POST['institute_name']) ? $_POST['institute_name'] : $institute_name ?>">
                <span class="help-block"><?= isset($errors['institute_name']) ? $errors['institute_name'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label for="institute_address">Institute Address</label>
                <input type="text" class="form-control" id="institute_address" name="institute_address" placeholder="institute_address" value="<?= isset($_POST['institute_address']) ? $_POST['institute_address'] : $institute_address ?>">
                <span class="help-block"><?= isset($errors['institute_address']) ? $errors['institute_address'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="email" value="<?= isset($_POST['email']) ? $_POST['email'] : $email ?>">
                <span class="help-block"><?= isset($errors['email']) ? $errors['email'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="contact_number" value="<?= isset($_POST['contact_number']) ? $_POST['contact_number'] : $contact_number ?>">
                <span class="help-block"><?= isset($errors['contact_number']) ? $errors['contact_number'] : '' ?></span>
              </div>

            </div>
            <div class="clearfix" style="clear:both"></div>

            <input type="submit" name="update_old_certificate" class="btn btn-primary mr-2" value="Submit">
            <a href="page.php?page=oldCertificate" class="btn btn-danger mr-2" title="Cancel">Cancel</a>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>