  <?php
	$amc_id = isset($_GET['id']) ? $_GET['id'] : '';
	include_once('include/classes/amc.class.php');
	$amc = new amc();
	$action = isset($_POST['update_amc']) ? $_POST['update_amc'] : '';
	if ($action != '') {
		$result	= $amc->update_amc($amc_id);
		$result = json_decode($result, true);
		$success = isset($result['success']) ? $result['success'] : '';
		$message = isset($result['message']) ? $result['message'] : '';
		$errors = isset($result['errors']) ? $result['errors'] : '';
		if ($success == true) {
			$_SESSION['msg'] = $message;
			$_SESSION['msg_flag'] = $success;

			header('location:page.php?page=list-amc');
		}
	}
	$res = $amc->list_amc($amc_id, '');
	if ($res != '') {
		$srno = 1;
		while ($data = $res->fetch_assoc()) {
			//print_r($data);exit();
			$AMC_ID 		= $data['AMC_ID'];
			//$USER_LOGIN_ID 		= $data['USER_LOGIN_ID'];
			$AMC_CODE 	= $data['AMC_CODE'];
			$AMC_COMPANY_NAME 	= $data['AMC_COMPANY_NAME'];
			$AMC_NAME = $data['AMC_NAME'];
			$EMAIL 				= $data['EMAIL'];
			$MOBILE 			= $data['MOBILE'];
			$DESIGNATION 			= $data['DESIGNATION'];
			$ADDRESS_LINE1 			= $data['ADDRESS_LINE1'];
			$ADDRESS_LINE2 			= $data['ADDRESS_LINE2'];
			$CITY 			= $data['CITY'];
			$STATE 			= $data['STATE'];
			$COUNTRY 			= $data['COUNTRY'];
			$POSTCODE 			= $data['POSTCODE'];
			$DETAIL_DESCRIPTION	 			= $data['DETAIL_DESCRIPTION'];
			$BANK_NAME	 			= $data['BANK_NAME'];
			$ACCOUNT_NO	 			= $data['ACCOUNT_NO'];
			$IFSC_CODE	 			= $data['IFSC_CODE'];
			$ACCOUNT_HOLDER_NAME	= $data['ACCOUNT_HOLDER_NAME'];
		}
	}

	?>
  <div class="content-wrapper">
  	<!-- Content Header (Page header) -->
  	<section class="content-header">
  		<h1>
  			Update AMC Details

  		</h1>
  		<ol class="breadcrumb">
  			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
  			<li><a href="page.php?page=list-amc">AMC</a></li>
  			<li class="active">Update AMC</li>
  		</ol>
  	</section>
  	<section class="content">
  		<form action="" method="post" enctype="multipart/form-data">
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
  				<div class="col-sm-12">
  					<div class="box box-primary">
  						<div class="box-header with-border">
  							<h3 class="box-title">Update AMC Details</h3>
  						</div>
  						<div class="box-body">
  							<input id="status" name="status" value="1" type="hidden" />
  							<input id="verify" name="verify" value="0" type="hidden" />
  							<input id="empcode" name="empcode" value="" type="hidden" />
  							<input id="empcode" name="amc_id" value="<?= isset($_POST['amc_id']) ? $_POST['amc_id'] : $AMC_ID ?>" type="hidden" />

  							<div class="form-group col-sm-12 <?= (isset($errors['empcmpname'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Institution/Academy/Center/Organisation:</label>
  								<input type="text" class="form-control required" name="empcmpname" placeholder="Institution/Academy/Center/Organisation" value="<?= isset($_POST['empcmpname']) ? $_POST['empcmpname'] : $AMC_COMPANY_NAME ?>" required="required" />
  								<span class="input-icon"><i class="icon-user"></i></span>
  								<span class="help-block"><?= (isset($errors['empcmpname'])) ? $errors['empcmpname'] : '' ?></span>
  							</div>
  							<div class="clearfix"></div>

  							<div class="form-group col-sm-6  <?= (isset($errors['empname'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Contact Person Name:</label>
  								<input type="text" class="form-control required" name="empname" placeholder="Contact person name" value="<?= isset($_POST['empname']) ? $_POST['empname'] : $AMC_NAME ?>" required="required" />
  								<span class="input-icon"><i class="icon-user"></i></span>
  								<span class="help-block"><?= (isset($errors['empname'])) ? $errors['empname'] : '' ?></span>
  							</div>
  							<div class="form-group col-sm-6  <?= (isset($errors['designation'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Designation:</label>
  								<select class="form-control required" name="designation" id="designation" required="required">
  									<option>--Select Designation---</option>
  									<?php
										$designation = isset($_POST['designation']) ? $_POST['designation'] : $DESIGNATION;
										echo $db->MenuItemsDropdown('designation_master', 'DESIGNATION_ID', 'DESIGNATION', 'DESIGNATION_ID,DESIGNATION', $designation, ' ORDER BY DESIGNATION ASC');
										?>
  								</select>
  								<span class="help-block"><?= (isset($errors['designation'])) ? $errors['designation'] : '' ?></span>
  							</div>
  							<div class="clearfix"></div>

  							<div class="form-group col-sm-6 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Email:</label>
  								<input type="text" class=" form-control required" name="email" placeholder="Email address" value="<?= isset($_POST['email']) ? $_POST['email'] : $EMAIL ?>" required="required" />
  								<span class="input-icon"><i class=" icon-email"></i></span>
  								<span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
  							</div>

  							<div class="form-group col-sm-6 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Mobile:</label>
  								<input type="text" class=" form-control required" name="mobile" placeholder="Mobile Number" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $MOBILE ?>" required="required" />
  								<span class="input-icon"><i class=" icon-mobile"></i></span>
  								<span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
  							</div>
  							<div class="clearfix"></div>

  							<div class="form-group col-sm-6 <?= (isset($errors['address1'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Full Address:</label>
  								<textarea type="text" class=" form-control required" name="address1" placeholder="Full Address (Example: Landmark, Road, Builiding/House no. etc.)" required="required"><?= isset($_POST['address1']) ? $_POST['address1'] : $ADDRESS_LINE1; ?></textarea>
  								<span class="help-block"><?= (isset($errors['address1'])) ? $errors['address1'] : '' ?></span>
  							</div>
  							<div class="form-group col-sm-6 <?= (isset($errors['address2'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Taluka Name:</label>
  								<input type="text" class=" form-control required" name="address2" placeholder="Taluka Name" required="required" value="<?= isset($_POST['address2']) ? $_POST['address2'] : $ADDRESS_LINE2; ?>" />
  								<span class="help-block"><?= (isset($errors['address2'])) ? $errors['address2'] : '' ?></span>
  							</div>
  							<div class="clearfix"></div>

  							<div class="form-group col-sm-6 <?= (isset($errors['state'])) ? 'has-error' : '' ?>">
  								<label class="control-label">State:</label>
  								<select class=" form-control required" name="state" id="state" onchange="getCitiesByState(this.value)" required="required">
  									<option value="">--Select State---</option>
  									<?php
										$state = isset($_POST['state']) ? $_POST['state'] : $STATE;
										echo $db->MenuItemsDropdown('states_master', 'STATE_ID', 'STATE_NAME', 'STATE_ID,STATE_NAME', $state, ' ORDER BY STATE_NAME ASC'); ?>
  								</select>
  								<span class="help-block"><?= (isset($errors['state'])) ? $errors['state'] : '' ?></span>
  							</div>
  							<div class="form-group col-sm-6 <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
  								<label class="control-label">City:</label>
  								<select class=" form-control required" name="city" id="city">
  									<option value="">-- Select Taluka | District ---</option>
  									<?php
										$city = isset($_POST['city']) ? $_POST['city'] : $CITY;
										echo $db->MenuItemsDropdown('city_master', 'CITY_ID', 'CITY_NAME', 'CITY_ID,CITY_NAME', $city, ' ORDER BY CITY_NAME ASC'); ?>
  								</select>
  								<span class="help-block"><?= (isset($errors['city'])) ? $errors['city'] : '' ?></span>
  							</div>
  							<div class="clearfix"></div>

  							<div class="form-group col-sm-6 <?= (isset($errors['country'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Country:</label>
  								<select class="form-control" name="country_sel" id="country_sel">
  									<?php
										$country = isset($_POST['country']) ? $_POST['country'] : 1;
										echo $db->MenuItemsDropdown('countries_master', 'COUNTRY_ID', 'COUNTRY_NAME', 'COUNTRY_ID,COUNTRY_NAME', $country, ' WHERE COUNTRY_ID=1'); ?>
  								</select>
  								<span class="help-block"><?= (isset($errors['country'])) ? $errors['country'] : '' ?></span>
  							</div>
  							<div class="form-group col-sm-6 <?= (isset($errors['postcode'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Postal Code:</label>
  								<input type="text" class=" form-control required" name="postcode" placeholder="Postal Code" value="<?= isset($_POST['postcode']) ? $_POST['postcode'] : $POSTCODE ?>" required="required" />
  								<span class="help-block"><?= (isset($errors['postcode'])) ? $errors['postcode'] : '' ?></span>
  							</div>
  							<div class="clearfix"></div>

  							<div class="form-group col-sm-12 <?= (isset($errors['empdetails'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Please Mention Area For AMC:</label>
  								<textarea class="form-control required" id="empdetails" name="empdetails" placeholder="Please Mention Area For AMC" required="required"><?= isset($_POST['empdetails']) ? $_POST['empdetails'] : $DETAIL_DESCRIPTION; ?></textarea>
  								<span class="help-block"><?= (isset($errors['empdetails'])) ? $errors['empdetails'] : '' ?></span>
  							</div>
  							<div class="clearfix"></div>

  							<h4> Bank Details : </h4> <br />

  							<div class="form-group col-sm-6 <?= (isset($errors['bankname'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Bank Name:</label>
  								<input type="text" class=" form-control required" name="bankname" placeholder="Bank Name" value="<?= isset($_POST['bankname']) ? $_POST['bankname'] : $BANK_NAME ?>" required="required" />
  								<span class="help-block"><?= (isset($errors['bankname'])) ? $errors['bankname'] : '' ?></span>
  							</div>
  							<div class="form-group col-sm-6 <?= (isset($errors['accountnumber'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Account Number:</label>
  								<input type="text" class=" form-control required" name="accountnumber" placeholder="Account Number" value="<?= isset($_POST['accountnumber']) ? $_POST['accountnumber'] : $ACCOUNT_NO ?>" required="required" />
  								<span class="help-block"><?= (isset($errors['accountnumber'])) ? $errors['accountnumber'] : '' ?></span>
  							</div>
  							<div class="clearfix"></div>

  							<div class="form-group col-sm-6 <?= (isset($errors['ifsc'])) ? 'has-error' : '' ?>">
  								<label class="control-label">IFSC Code:</label>
  								<input type="text" class="form-control required" name="ifsc" placeholder="IFSC Code" value="<?= isset($_POST['ifsc']) ? $_POST['ifsc'] : $IFSC_CODE ?>" required="required" />
  								<span class="help-block"><?= (isset($errors['ifsc'])) ? $errors['ifsc'] : '' ?></span>
  							</div>
  							<div class="form-group col-sm-6 <?= (isset($errors['accountholdername'])) ? 'has-error' : '' ?>">
  								<label class="control-label">Acc. Holder Name:</label>
  								<input type="text" class=" form-control required" name="accountholdername" placeholder="Account Holder Name" value="<?= isset($_POST['accountholdername']) ? $_POST['accountholdername'] : $ACCOUNT_HOLDER_NAME ?>" required="required" />
  								<span class="help-block"><?= (isset($errors['accountholdername'])) ? $errors['accountholdername'] : '' ?></span>
  							</div>
  							<div class="clearfix"></div>

  							<div class="text-center">
  								<input type="submit" name="update_amc" class="btn btn-primary" value="UPDATE" />
  								<a href="page.php?page=list-amc" class="btn btn-primary">Cancel</a>
  							</div>
  						</div>
  					</div>
  				</div>
  			</div>
  		</form>
  	</section>
  </div>