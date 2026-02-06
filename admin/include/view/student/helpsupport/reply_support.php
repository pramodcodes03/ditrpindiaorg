<?php
$ticket_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['reply_support']) ? $_POST['reply_support'] : '';
include_once('include/classes/helpsupport.class.php');
$helpsupport = new helpsupport();
if ($action != '') {
  $result = $helpsupport->reply_support($ticket_id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listSupport');
  }
}
/* get exam details */
$res = $helpsupport->list_support($ticket_id, '');
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
          <h4 class="card-title">View Ticket</h4>
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
            <?php
            if (isset($success)) {
            ?>
              <div class="row">
                <div class="col-md-12">
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
              <input type="hidden" name="ticket_id" value="<?= $TICKET_ID ?>" />

              <div class="form-group  col-md-8 <?= (isset($errors['description'])) ? 'has-error' : '' ?>">
                <label>Description </label>
                <textarea class="form-control" id="description" name="description" placeholder="Description" type="text" rows="6" disabled="true"><?= isset($_POST['description']) ? $_POST['description'] : $DESCRIPTION ?></textarea>
                <span class="help-block"><?= (isset($errors['description'])) ? $errors['description'] : '' ?></span>
              </div>

              <div class="form-group col-md-4 <?= (isset($errors['supportfiles'])) ? 'has-error' : '' ?>">
                <label>Attach Files</label>
                <?= $helpsupport->get_helpsupport_files($TICKET_ID, true);  ?>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
                <label>Mobile</label>
                <input class="form-control" id="mobile" name="mobile" maxlength="10" placeholder="Mobile" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $MOBILE ?>" type="text" disabled="true">
                <span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
                <label>Email</label>
                <input class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($_POST['email']) ? $_POST['email'] : $EMAIL ?>" type="email" disabled="true">
                <span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
              </div>

              <div class="form-group  col-md-12 <?= (isset($errors['reply'])) ? 'has-error' : '' ?>">
                <label>Admin Reply </label>
                <textarea class="form-control" id="reply" name="reply" placeholder="admin reply" type="text" rows="6" disabled="true"><?= isset($_POST['reply']) ? $_POST['reply'] : $ADMIN_UPDATES ?></textarea>
                <span class="help-block"><?= (isset($errors['reply'])) ? $errors['reply'] : '' ?></span>
              </div>

              <div class="col-md-12 form-group row">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;
                ?>
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?>>
                      Active
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?>>
                      Inactive
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="page.php?page=listSupport" class="btn btn-danger btn1">Cancel</a>
              <!--  <input type="submit" name="reply_support" class="btn btn-info" value="Reply Support" /> -->
            </div>

        </div>
        </form>
      </div>
    </div>
  </div>
</div>