<?php

$action = isset($_POST['update_images']) ? $_POST['update_images'] : '';

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
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $tools->edit_backgroundimages($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=manageBackground');
  }
}

$res = $tools->list_backgroundimages('', $institute_id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    extract($data);


    $certificate_image    = BACKGROUND_IMAGE_PATH . '/' . $institute_id . '/' . $certificate_image;
    $marksheet_image      = BACKGROUND_IMAGE_PATH . '/' . $institute_id . '/' . $marksheet_image;

    if ($admissionform_image != '') {
      $admissionform_image  = BACKGROUND_IMAGE_PATH . '/' . $institute_id . '/' . $admissionform_image;
    } else {
      $admissionform_image  = "resources/default/admisionform.jpg";
    }

    if ($idcard_image != '') {
      $idcard_image         = BACKGROUND_IMAGE_PATH . '/' . $institute_id . '/' . $idcard_image;
    } else {
      $idcard_image  = "resources/default/idcard.jpg";
    }

    if ($hallticket_image != '') {
      $hallticket_image     = BACKGROUND_IMAGE_PATH . '/' . $institute_id . '/' . $hallticket_image;
    } else {
      $hallticket_image  = "resources/default/hall.jpg";
    }

    if ($feesreceipt_image != '') {
      $feesreceipt_image    = BACKGROUND_IMAGE_PATH . '/' . $institute_id . '/' . $feesreceipt_image;
    } else {
      $feesreceipt_image  = "resources/default/fees.jpg";
    }

    if ($teacherid_image != '') {
      $teacherid_image    = BACKGROUND_IMAGE_PATH . '/' . $institute_id . '/' . $teacherid_image;
    } else {
      $teacherid_image  = "resources/default/teacherid.jpg";
    }

    if ($birthdayimage != '') {
      $birthdayimage    = BACKGROUND_IMAGE_PATH . '/' . $institute_id . '/' . $birthdayimage;
    } else {
      $birthdayimage  = "resources/default/birthday.jpg";
    }
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Manage Background Images
            <a href="https://drive.google.com/file/d/1yFWPEa1eGu5kMFbBkYBOmXTdXptAm6Vu/view?usp=share_link" class="btn btn-primary" style="float: right" target="_blank">Download Sample Certificates</a>
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
            <input type="hidden" name="inst_id" value="<?= isset($institute_id) ? $institute_id : '' ?>" />
            <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>" />

            <div class="form-group">
              <label>Admission Form Image</label>
              <input type="file" name="admissionform_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <?php
            if ($admissionform_image != '') {
            ?>
              <img src="<?= $admissionform_image ?>" style="width:150px; height:100%; border-radius:0;" />
              <a href="page.php?page=previewAdmissionForm" target="_blank" class="btn btn-primary btn1">Preview</a>
              <br /><br />
            <?php
            }
            ?>
            <div class="form-group">
              <label>ID Card Image</label>
              <input type="file" name="idcard_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <?php
            if ($idcard_image != '') {
            ?>
              <img src="<?= $idcard_image ?>" style="width:150px; height:100%; border-radius:0;" />
              <a href="page.php?page=previewIdcard" target="_blank" class="btn btn-primary btn1">Preview</a>
              <br /><br />
            <?php
            }
            ?>

            <div class="form-group">
              <label>Hall Ticket Image</label>
              <input type="file" name="hallticket_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <?php
            if ($hallticket_image != '') {
            ?>
              <img src="<?= $hallticket_image ?>" style="width:150px; height:100%; border-radius:0;" />
              <a href="page.php?page=previewHallTicket" target="_blank" class="btn btn-primary btn1">Preview</a>
              <br /><br />
            <?php
            }
            ?>

            <div class="form-group">
              <label>Fees Receipt Image</label>
              <input type="file" name="feesreceipt_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <?php
            if ($feesreceipt_image != '') {
            ?>
              <img src="<?= $feesreceipt_image ?>" style="width:150px; height:100%; border-radius:0;" />
              <a href="page.php?page=previewFeesReceipt" target="_blank" class="btn btn-primary btn1">Preview</a>
              <br /><br />
            <?php
            }
            ?>

            <div class="form-group">
              <label>Teacher IDCard Image</label>
              <input type="file" name="teacherid_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <?php
            if ($teacherid_image != '') {
            ?>
              <img src="<?= $teacherid_image ?>" style="width:150px; height:100%; border-radius:0;" />
              <!--<a href="page.php?page=previewTeacherIDCard" target="_blank" class="btn btn-primary btn1">Preview</a>-->
              <br /><br />
            <?php
            }
            ?>

            <div class="form-group">
              <label>Birthday Image</label>
              <input type="file" name="birthday_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <?php
            if ($birthdayimage != '') {
            ?>
              <img src="<?= $birthdayimage ?>" style="width:150px; height:100%; border-radius:0;" />
              <!--<a href="page.php?page=previewTeacherIDCard" target="_blank" class="btn btn-primary btn1">Preview</a>-->
              <br /><br />
            <?php
            }
            ?>

            <input type="submit" name="update_images" class="btn btn-success mr-2" value="Submit">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>