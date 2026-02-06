<?php
include_once('include/controller/admin/employer/update_employer.php');
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="page.php?page=list-employer">AMC</a></li>
      <li class="active">AMC Details</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <form class="form-horizontal form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show')">

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
        <div class="col-md-1">
        </div>

        <div class="col-md-10">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">AMC Details</h3>
              <h4>
                AMC NAME ::<?= isset($EMPLOYER_COMPANY_NAME) ? ': ' . $EMPLOYER_COMPANY_NAME : '' ?> <?= isset($EMPLOYER_NAME) ? ': ' . $EMPLOYER_NAME : '' ?>

              </h4>
            </div>
            <div class="box-body">
              <input type="hidden" name="employer_id" value="<?= isset($EMPLOYER_ID) ? $EMPLOYER_ID : '' ?>" />
              <input type="hidden" name="employer_login_id" value="<?= isset($USER_LOGIN_ID) ? $USER_LOGIN_ID : '' ?>" />
              <div class="form-group <?= (isset($errors['empcode'])) ? 'has-error' : '' ?>">
                <label for="empcode" class="col-sm-3 control-label">AMC Code</label>
                <div class="col-sm-9">
                  <input class="form-control" id="empcode" name="empcode" placeholder="Employer Code" value="<?= isset($_POST['empcode']) ? $_POST['empcode'] : $EMPLOYER_CODE ?>" type="text">
                  <span class="help-block"><?= isset($errors['empcode']) ? $errors['empcode'] : '' ?></span>
                </div>
              </div>
              <div class="form-group <?= (isset($errors['empcmpname'])) ? 'has-error' : '' ?>">
                <label for="empcmpname" class="col-sm-3 control-label">AMC Name</label>
                <div class="col-sm-9">
                  <input class="form-control" id="empcmpname" name="empcmpname" placeholder="Employer name" value="<?= isset($_POST['empcmpname']) ? $_POST['empcmpname'] : $EMPLOYER_COMPANY_NAME ?>" type="text">
                  <span class="help-block"><?= isset($errors['empcmpname']) ? $errors['empcmpname'] : '' ?></span>
                </div>
              </div>
              <div class="form-group">
                <label for="empname" class="col-sm-3 control-label">Contact Person Name</label>
                <div class="col-sm-9">
                  <input class="form-control" id="empname" name="empname" placeholder="Employer name" value="<?= isset($_POST['empname']) ? $_POST['empname'] : $EMPLOYER_NAME ?>" type="text">
                </div>
              </div>
              <div class="form-group">
                <label for="designation" class="col-sm-3 control-label">Designation</label>
                <div class="col-sm-9">

                  <select class="form-control" name="designation" id="designation">
                    <?php

                    $designation = isset($_POST['designation']) ? $_POST['designation'] : $DESIGNATION;
                    echo $db->MenuItemsDropdown('designation_master', 'DESIGNATION_ID', 'DESIGNATION', 'DESIGNATION_ID,DESIGNATION', $designation, ' WHERE ROLE=2 '); ?>
                  </select>
                </div>
              </div>
              <div class="form-group <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
                <label for="email" class="col-sm-3 control-label">Email</label>
                <div class="col-sm-9">
                  <input class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($_POST['email']) ? $_POST['email'] : $EMAIL ?>" type="email" onchange="document.getElementById('uname').value = this.value;">
                  <span class="help-block"><?= isset($errors['email']) ? $errors['email'] : '' ?></span>
                </div>
              </div>
              <div class="form-group <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
                <label for="mobile" class="col-sm-3 control-label">Mobile</label>
                <div class="col-sm-9">
                  <input class="form-control" id="mobile" name="mobile" placeholder="Mobile" value="<?= isset($_POST['address2']) ? $_POST['address2'] : $MOBILE ?>" maxlength="10" type="text">
                  <span class="help-block"><?= isset($errors['mobile']) ? $errors['mobile'] : '' ?></span>
                </div>
              </div>
              <div class="form-group <?= (isset($errors['address1'])) ? 'has-error' : '' ?>">
                <label for="address1" class="col-sm-3 control-label">Full Address</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="address1" name="address1" placeholder="Address Line 1" type="text" value="<?= isset($_POST['address1']) ? $_POST['address1'] : $ADDRESS_LINE1 ?>" />
                  <span class="help-block"><?= (isset($errors['address1'])) ? $errors['address1'] : '' ?></span>
                </div>
              </div>
              <div class="form-group">
                <label for="address2" class="col-sm-3 control-label">Taluka Name</label>
                <div class="col-sm-9">
                  <input class="form-control" id="address2" name="address2" placeholder="Address Line 2" type="text" value="<?= isset($_POST['address2']) ? $_POST['address2'] : $ADDRESS_LINE2 ?>" />
                </div>
              </div>

              <div class="form-group <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
                <label for="state" class="col-sm-3 control-label">State</label>
                <div class="col-sm-9">
                  <select class="form-control select2" name="state" id="state" onchange="getCitiesByState(this.value)">
                    <?php
                    $state = isset($_POST['state']) ? $_POST['state'] : $STATE;
                    echo $db->MenuItemsDropdown('states_master', 'STATE_ID', 'STATE_NAME', 'STATE_ID,STATE_NAME', $state, ' ORDER BY STATE_NAME ASC'); ?>
                  </select>
                  <span class="help-block"><?= isset($errors['state']) ? $errors['state'] : '' ?></span>
                </div>
              </div>
              <div class="form-group <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
                <label for="city" class="col-sm-3 control-label">City</label>
                <div class="col-sm-9">
                  <input class="form-control" id="city" name="city" placeholder="City2" type="text" value="<?= isset($_POST['city']) ? $_POST['city'] : $CITY ?>" />
                </div>
                <!-- <div class="col-sm-9">
                    <select class="form-control select2" name="city" id="city" onchange="getStates(this.value)">
						<?php
            $city = isset($_POST['city']) ? $_POST['city'] : $CITY;
            echo $db->MenuItemsDropdown('city_master', 'CITY_ID', 'CITY_NAME', 'CITY_ID,CITY_NAME', $city, ' ORDER BY CITY_NAME ASC'); ?>
					</select>
					<span class="help-block"><?= isset($errors['city']) ? $errors['city'] : '' ?></span>
                  </div>-->
              </div>
              <div class="form-group">
                <label for="postcode" class="col-sm-3 control-label">Country</label>
                <div class="col-sm-9">
                  <select class="form-control" name="country_sel" id="country_sel" disabled>
                    <?php
                    $country = isset($_POST['country']) ? $_POST['country'] : $COUNTRY;
                    echo $db->MenuItemsDropdown('countries_master', 'COUNTRY_ID', 'COUNTRY_NAME', 'COUNTRY_ID,COUNTRY_NAME', 1, ' WHERE COUNTRY_ID=1 ORDER BY COUNTRY_NAME ASC'); ?>
                  </select>
                  <input type="hidden" name="country" id="country" value="<?= $country ?>" />
                </div>
              </div>
              <div class="form-group">
                <label for="postcode" class="col-sm-3 control-label">Postal Code</label>
                <div class="col-sm-9">
                  <input class="form-control" id="postcode" name="postcode" maxlength="6" placeholder="Postcode" value="<?= isset($_POST['postcode']) ? $_POST['postcode'] : $POSTCODE ?>" type="text">
                </div>
              </div>
              <div class="form-group">
                <label for="empdetails" class="col-sm-3 control-label">Area For AMC</label>
                <div class="col-sm-9">
                  <textarea class="form-control" id="empdetails" name="empdetails" placeholder="Please Mention Area For AMC" type="text"><?= isset($_POST['empdetails']) ? $_POST['empdetails'] : $DETAIL_DESCRIPTION ?></textarea>
                </div>
              </div>

              <div class="form-group">
                <label for="postcode" class="col-sm-3 control-label">Bank Details</label>
                <div class="col-sm-9">
                  <input class="form-control" id="postcode" name="postcode" maxlength="6" placeholder="Bank Details" value="<?= isset($_POST['postcode']) ? $_POST['postcode'] : $BANK_NAME ?>" type="text">
                </div>
              </div>

              <div class="form-group">
                <label for="postcode" class="col-sm-3 control-label">Account Number</label>
                <div class="col-sm-9">
                  <input class="form-control" id="postcode" name="postcode" maxlength="6" placeholder="Account Number" value="<?= isset($_POST['postcode']) ? $_POST['postcode'] : $ACCOUNT_NO ?>" type="text">
                </div>
              </div>

              <div class="form-group">
                <label for="postcode" class="col-sm-3 control-label">IFSC Code</label>
                <div class="col-sm-9">
                  <input class="form-control" id="postcode" name="postcode" maxlength="6" placeholder="IFSC Code" value="<?= isset($_POST['postcode']) ? $_POST['postcode'] : $IFSC ?>" type="text">
                </div>
              </div>

              <div class="form-group">
                <label for="postcode" class="col-sm-3 control-label">Account Holder Name</label>
                <div class="col-sm-9">
                  <input class="form-control" id="postcode" name="postcode" maxlength="6" placeholder="Account Holder Name" value="<?= isset($_POST['postcode']) ? $_POST['postcode'] : $ACCHOLDERNAME ?>" type="text">
                </div>
              </div>

              <div class="form-group">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;
                ?>
                <label for="status" class="col-sm-3 control-label">Status</label>
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
              <a href="page.php?page=list-employer" class="btn btn-default">Cancel</a>
              <!--<input type="submit" name="update_employer" class="btn btn-info" value="Update AMC" />-->
            </div>
          </div>
        </div>
      </div>
</div>
</form>
<!-- /.row -->
</section>
<!-- /.content -->
</div>