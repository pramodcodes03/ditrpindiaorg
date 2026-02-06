<?php

$id = isset($_POST['id']) ? $_POST['id'] : '';
$action = isset($_POST['edit_contact']) ? $_POST['edit_contact'] : '';

include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $websiteManage->edit_contact($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    //header('location:'.HTTP_HOST.'/website_management/ContactUs');
    header('location:/website_management/ContactUs');
  }
}

$res = $websiteManage->list_contact($id, '');
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
          <h4 class="card-title">Contact Us
            <a href="#" class="btn btn-warning" style="float: right; margin-right:20px;" target="_blank">How To Upload Google Map</a>
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
            <input type="hidden" name="id" value="1" />

            <div class="form-group">
              <label for="exampleInputName1">Email Address </label>
              <input type="text" class="form-control" id="exampleInputName1" name="email_id" value="<?= isset($_POST['email_id']) ? $_POST['email_id'] : $email_id ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">WhatsApp Number (Requires 91) </label>
              <input type="text" class="form-control" id="exampleInputName1" name="contact_number1" value="<?= isset($_POST['contact_number1']) ? $_POST['contact_number1'] : $contact_number1 ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Contact Number2 </label>
              <input type="text" class="form-control" id="exampleInputName1" name="contact_number2" value="<?= isset($_POST['contact_number2']) ? $_POST['contact_number2'] : $contact_number2 ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Address</label>
              <textarea class="form-control" id="exampleTextarea1" rows="4" name="address"><?= isset($_POST['address']) ? $_POST['address'] : $address ?></textarea>
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Location Map </label>
              <textarea class="form-control" id="exampleTextarea1" rows="4" name="map"><?= isset($_POST['map']) ? $_POST['map'] : $map ?></textarea>
            </div>


            <input type="submit" name="edit_contact" class="btn btn-success mr-2" value="Submit">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>