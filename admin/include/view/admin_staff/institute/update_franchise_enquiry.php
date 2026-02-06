<?php

$institute_id = isset($_GET['id']) ? $_GET['id'] : '';

echo 'institute_id' . $institute_id;
die;

$action = isset($_POST['update_franchise_enquiry']) ? $_POST['update_franchise_enquiry'] : '';

include_once('include/classes/institute.class.php');
$institute = new institute();

if ($action != '') {
	$institute_id = isset($_POST['institute_id']) ? $_POST['institute_id'] : '';

	$result = $institute->update_franchise_enquiry($institute_id);
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		if ($_SESSION['user_role'] == 2)
			header('location:page.php?page=listFranchiseEnquiry');
		else
			header('location:page.php?page=index.php');
	}
}
/* get institute details */
$res = $institute->list_franchise_enquiry($institute_id, '');
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
					<h4 class="card-title"> Update Franchise Enquiry</h4>
					<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">
						<div class="box-body">
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
							<input type="hidden" name="institute_id" value="<?= isset($id) ? $id : '' ?>" />


							<div class="row">
								<div class="form-group col-sm-6 <?= (isset($errors['instname'])) ? 'has-error' : '' ?>">
									<input type="text" class=" form-control required" name="instname" placeholder="Institution Name" value="<?= isset($_POST['instname']) ? $_POST['instname'] : $instname ?>" required="required" />
									<span class="input-icon"><i class=" icon-user"></i></span>
									<span class="help-block"><?= (isset($errors['instname'])) ? $errors['instname'] : '' ?></span>
								</div>
								<div class="form-group col-sm-6 <?= (isset($errors['instowner'])) ? 'has-error' : '' ?>">
									<input type="text" class=" form-control required" name="instowner" placeholder="Center Owner Name" value="<?= isset($_POST['instowner']) ? $_POST['instowner'] : $owner_name ?>" required="required" />
									<span class="input-icon"><i class="icon-user"></i></span>
									<span class="help-block"><?= (isset($errors['instowner'])) ? $errors['instowner'] : '' ?></span>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-6 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
									<input type="text" class=" form-control required" name="email" placeholder="Email address" value="<?= isset($_POST['email']) ? $_POST['email'] : $emailid ?>" required="required" />
									<span class="input-icon"><i class=" icon-email"></i></span>
									<span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
								</div>
								<div class="form-group col-sm-6 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
									<input type="text" class=" form-control required" name="mobile" placeholder="Mobile Number" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $mobile_number ?>" required="required" maxlength="10" />
									<span class="input-icon"><i class=" icon-mobile"></i></span>
									<span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="row">
								<div class="form-group col-sm-6 <?= (isset($errors['address'])) ? 'has-error' : '' ?>">
									<textarea type="text" class=" form-control required" name="address1" placeholder="Full Address (Example: Landmark, Road, Builiding/House no. etc.)" required="required"><?= isset($_POST['address']) ? $_POST['address'] : $address ?></textarea>
									<span class="help-block"><?= (isset($errors['address'])) ? $errors['address'] : '' ?></span>
								</div>
								<!-- <div class="form-group col-sm-6 <?= (isset($errors['address2'])) ? 'has-error' : '' ?>">
									<input type="text" class=" form-control required" name="address2" placeholder="Address Line 2" required="required" value="<?= isset($_POST['address2']) ? $_POST['address2'] : '' ?>" />
									<span class="help-block"><?= (isset($errors['address2'])) ? $errors['address2'] : '' ?></span>
								</div> -->

								<div class="form-group col-sm-6 <?= (isset($errors['taluka'])) ? 'has-error' : '' ?>">
									<input type="text" class=" form-control required" name="taluka" placeholder="Taluka Name" required="required" value="<?= isset($_POST['taluka']) ? $_POST['taluka'] : $taluka ?>" />
									<span class="help-block"><?= (isset($errors['taluka'])) ? $errors['taluka'] : '' ?></span>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-6 <?= (isset($errors['postcode'])) ? 'has-error' : '' ?>">
									<input type="text" class=" form-control required" name="postcode" placeholder="Postal Code" value="<?= isset($_POST['postcode']) ? $_POST['postcode'] : $pincode ?>" required="required" />
									<span class="help-block"><?= (isset($errors['postcode'])) ? $errors['postcode'] : '' ?></span>
								</div>
								<div class="form-group col-sm-6 <?= (isset($errors['state'])) ? 'has-error' : '' ?>">

									<select class="form-control select2" name="state" id="state" onchange="getCitiesByState(this.value)">
										<?php
										$state = isset($_POST['state']) ? $_POST['state'] : $state;
										echo $db->MenuItemsDropdown('states_master', 'STATE_ID', 'STATE_NAME', 'STATE_ID,STATE_NAME', $state, ' ORDER BY STATE_NAME ASC'); ?>
									</select>
									<span class="help-block"><?= isset($errors['state']) ? $errors['state'] : '' ?></span>

								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-6 <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
									<input type="text" class=" form-control required" name="city" placeholder="City" value="<?= isset($_POST['city']) ? $_POST['city'] : $city ?>" required="required" />
									<span class="help-block"><?= (isset($errors['city'])) ? $errors['city'] : '' ?></span>
								</div>
								<div class="form-group col-sm-6 <?= (isset($errors['country'])) ? 'has-error' : '' ?>">
									<select class="form-control" name="country_sel" id="country_sel" disabled>
										<?php
										$country = isset($_POST['country']) ? $_POST['country'] : $country;
										echo $db->MenuItemsDropdown('countries_master', 'COUNTRY_ID', 'COUNTRY_NAME', 'COUNTRY_ID,COUNTRY_NAME', 1, ' WHERE COUNTRY_ID=1 ORDER BY COUNTRY_NAME ASC'); ?>
									</select>
									<input type="hidden" name="country" id="country" value="<?= $country ?>" />
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-6 <?= (isset($errors['remark'])) ? 'has-error' : '' ?>">
									<label>Remark </label>
									<textarea class="form-control" id="remark" name="remark" placeholder="Remark" type="text"><?= isset($_POST['address1']) ? $_POST['remark'] : $remark ?></textarea>
									<span class="help-block"><?= (isset($errors['remark'])) ? $errors['remark'] : '' ?></span>
								</div>
							</div>
							<div class="box-footer text-center">
								<input type="submit" name="update_franchise_enquiry" class="btn btn-info" value="Update" />
								<a href="page.php?page=listFranchiseEnquiry" class="btn btn-danger">Cancel</a>
							</div>
						</div>


					</form>
				</div>
			</div>
		</div>
	</div>
</div>