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
    header('location:page.php?page=list-support');
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
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Reply Help Support

    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Help Support</a></li>
      <li class="active"> Reply Help Support</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <form class="form-horizontal form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');">

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
              if (isset($errors) && !empty($errors)) {
                echo '<ul>';
                foreach ($errors as $key => $value) {
                  echo '<li>' . $value . '</li>';
                }
                echo '</ul>';
              }
              ?>
            </div>
          </div>
        </div>
      <?php
      }
      ?>

      <div class="row">
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> Reply Help Support</h3>
            </div>
            <div class="box-body">
              <input type="hidden" name="ticket_id" value="<?= $TICKET_ID ?>" />
              <div class="form-group col-md-6 <?= (isset($errors['supporttype'])) ? 'has-error' : '' ?>">
                <label for="supporttype" class="col-sm-4 control-label">Select Support Type</label>
                <div class="col-sm-6">
                  <select class="form-control" name="supporttype" id="supporttype" disabled="true">
                    <?php
                    $supporttype = isset($_POST['supporttype']) ? $_POST['supporttype'] : '';
                    echo $db->MenuItemsDropdown('help_support_type', 'SUPPORT_TYPE_ID', 'SUPPORT_NAME', 'SUPPORT_TYPE_ID,SUPPORT_NAME', $SUPPORT_TYPE_ID, ' WHERE ACTIVE=1 AND DELETE_FLAG=0'); ?>
                  </select>
                  <span class="help-block"><?= (isset($errors['supporttype'])) ? $errors['supporttype'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['supportcat'])) ? 'has-error' : '' ?>">
                <label for="supportcat" class="col-sm-4 control-label">Select Support Category</label>
                <div class="col-sm-6">
                  <select class="form-control" name="supportcat" id="supportcat" disabled="true">
                    <?php
                    $supportcat = isset($_POST['supportcat']) ? $_POST['supportcat'] : '';
                    echo $db->MenuItemsDropdown('help_support_category', 'SUPPORT_CAT_ID', 'CATEGORY_NAME', 'SUPPORT_CAT_ID,CATEGORY_NAME', $SUPPORT_CAT_ID, ' WHERE ACTIVE=1 AND DELETE_FLAG=0'); ?>
                  </select>
                  <span class="help-block"><?= (isset($errors['supportcat'])) ? $errors['supportcat'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['authorname'])) ? 'has-error' : '' ?>">
                <label for="authorname" class="col-sm-4 control-label">Author Name</label>
                <div class="col-sm-6">
                  <input class="form-control" id="authorname" name="authorname" placeholder="Author Name" value="<?= isset($_POST['authorname']) ? $_POST['authorname'] : $AUTHOR_NAME ?>" type="text" disabled="true">
                  <span class="help-block"><?= (isset($errors['authorname'])) ? $errors['authorname'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
                <label for="mobile" class="col-sm-4 control-label">Mobile</label>
                <div class="col-sm-6">
                  <input class="form-control" id="mobile" name="mobile" maxlength="10" placeholder="Mobile" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $MOBILE ?>" type="text" disabled="true">
                  <span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['altmobile'])) ? 'has-error' : '' ?>">
                <label for="altmobile" class="col-sm-4 control-label">Alternate Mobile</label>
                <div class="col-sm-6">
                  <input class="form-control" id="altmobile" name="altmobile" maxlength="10" placeholder="Alternate Mobile" value="<?= isset($_POST['altmobile']) ? $_POST['altmobile'] : $ALT_MOBILE ?>" type="text" disabled="true">
                  <span class="help-block"><?= (isset($errors['altmobile'])) ? $errors['altmobile'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
                <label for="email" class="col-sm-4 control-label">Email</label>
                <div class="col-sm-6">
                  <input class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($_POST['email']) ? $_POST['email'] : $EMAIL ?>" type="email" onchange="document.getElementById('uname').value = this.value;" disabled="true">
                  <span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['altemail'])) ? 'has-error' : '' ?>">
                <label for="altemail" class="col-sm-4 control-label">Alternate Email</label>
                <div class="col-sm-6">
                  <input class="form-control" id="altemail" name="altemail" placeholder="altEmail" value="<?= isset($_POST['altemail']) ? $_POST['altemail'] : $ALT_EMAIL ?>" type="altemail" onchange="document.getElementById('uname').value = this.value;" disabled="true">
                  <span class="help-block"><?= (isset($errors['altemail'])) ? $errors['altemail'] : '' ?></span>
                </div>
              </div>

              <div class="form-group  col-md-12 <?= (isset($errors['description'])) ? 'has-error' : '' ?>">
                <label for="description" class="col-sm-2 control-label">Description </label>
                <div class="col-sm-5">
                  <textarea class="form-control" id="description" name="description" placeholder="Description" type="text" rows="6" disabled="true"><?= isset($_POST['description']) ? $_POST['description'] : $DESCRIPTION ?></textarea>
                  <span class="help-block"><?= (isset($errors['description'])) ? $errors['description'] : '' ?></span>
                </div>
              </div>

              <div class="form-group  col-md-12 <?= (isset($errors['reply'])) ? 'has-error' : '' ?>">
                <label for="reply" class="col-sm-2 control-label">Admin Reply </label>
                <div class="col-sm-8">
                  <textarea class="form-control" id="reply" name="reply" placeholder="admin reply" type="text" rows="6" disabled="true"><?= isset($_POST['reply']) ? $_POST['reply'] : $ADMIN_UPDATES ?></textarea>
                  <span class="help-block"><?= (isset($errors['reply'])) ? $errors['reply'] : '' ?></span>
                </div>
              </div>

              <div class="form-group <?= (isset($errors['supportfiles'])) ? 'has-error' : '' ?>">
                <label for="supportfiles" class="col-sm-2 control-label">Attach Files</label>
                <div class="col-sm-8">
                  <?= $helpsupport->get_helpsupport_files($TICKET_ID, true);  ?>
                </div>
              </div>


              <div class="form-group">
                <label for="status" class="col-sm-3 control-label">Status</label>
                <div class="radio">
                  <label>
                    <input name="status" id="optionsRadios1" value="1" <?= ($ACTIVE == 1) ? "checked=''" : ''  ?> type="radio">
                    Active
                  </label>
                  <label>
                    <input name="status" id="optionsRadios2" value="0" <?= ($ACTIVE == 0) ? "checked=''" : ''  ?> type="radio">
                    Inactive
                  </label>
                </div>
              </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="page.php?page=list-support" class="btn btn-default">Cancel</a>
              <!--  <input type="submit" name="reply_support" class="btn btn-info" value="Reply Support" /> -->
            </div>
          </div>
        </div>
      </div>
    </form>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>