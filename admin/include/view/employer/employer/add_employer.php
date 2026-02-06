<?php
include_once('include/controller/admin/employer/add_employer.php');
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Add New Employer

		</h1>
		<ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Employer</a></li>
			<li class="active">Add New Employer</li>
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


				<div class="col-md-7">
					<!-- general form elements -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Add New Employer Details</h3>
						</div>
						<div class="box-body">
							<div class="form-group <?= (isset($errors['empcode'])) ? 'has-error' : '' ?>">
								<label for="empcode" class="col-sm-3 control-label">Employer Code</label>
								<div class="col-sm-9">
									<input class="form-control" id="empcode" name="empcode" placeholder="Employer Code" value="<?= isset($_POST['empcode']) ? $_POST['empcode'] : $employer->generate_employer_code() ?>" type="text">
									<span class="help-block"><?= (isset($errors['empcode'])) ? $errors['empcode'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['empcmpname'])) ? 'has-error' : '' ?>">
								<label for="empcmpname" class="col-sm-3 control-label">Company Name</label>
								<div class="col-sm-9">
									<input class="form-control" id="empcmpname" name="empcmpname" placeholder="Company  name" value="<?= isset($_POST['empcmpname']) ? $_POST['empcmpname'] : '' ?>" type="text">
									<span class="help-block"><?= (isset($errors['empcmpname'])) ? $errors['empcmpname'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['empname'])) ? 'has-error' : '' ?>">
								<label for="empname" class="col-sm-3 control-label">Employer Name</label>
								<div class="col-sm-9">
									<input class="form-control" id="empname" name="empname" placeholder="Employer name" value="<?= isset($_POST['empname']) ? $_POST['empname'] : '' ?>" type="text">
									<span class="help-block"><?= (isset($errors['empname'])) ? $errors['empname'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['designation'])) ? 'has-error' : '' ?>">
								<label for="designation" class="col-sm-3 control-label">Designation</label>
								<div class="col-sm-9">
									<select class="form-control" name="designation" id="designation">
										<?php
										$designation = isset($_POST['designation']) ? $_POST['designation'] : '';
										echo $db->MenuItemsDropdown('designation_master', 'DESIGNATION_ID', 'DESIGNATION', 'DESIGNATION_ID,DESIGNATION', $designation, ' WHERE ROLE=2 '); ?>
									</select>
									<span class="help-block"><?= (isset($errors['designation'])) ? $errors['designation'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
								<label for="email" class="col-sm-3 control-label">Email</label>
								<div class="col-sm-9">
									<input class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" type="email" onchange="document.getElementById('uname').value = this.value;">
									<span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
								<label for="mobile" class="col-sm-3 control-label">Mobile</label>
								<div class="col-sm-9">
									<input class="form-control" id="mobile" name="mobile" placeholder="Mobile" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : '' ?>" type="text" maxlength="10">
									<span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['address1'])) ? 'has-error' : '' ?>">
								<label for="address1" class="col-sm-3 control-label">Address Line 1</label>
								<div class="col-sm-9">
									<input class="form-control" id="address1" name="address1" placeholder="Address Line 1" type="text" maxlength="100" value="<?= isset($_POST['address1']) ? $_POST['address1'] : '' ?>" />
									<span class="help-block"><?= (isset($errors['address1'])) ? $errors['address1'] : '' ?></span>
								</div>
							</div>
							<div class="form-group">
								<label for="address2" class="col-sm-3 control-label">Address Line 2</label>
								<div class="col-sm-9">
									<input class="form-control" maxlength="100" id="address2" name="address2" placeholder="Address Line 2" type="text" value="<?= isset($_POST['address2']) ? $_POST['address2'] : '' ?>" />
								</div>
							</div>
							<div class="form-group <?= (isset($errors['state'])) ? 'has-error' : '' ?>">
								<label for="state" class="col-sm-3 control-label">State</label>
								<div class="col-sm-9">
									<select class="form-control select2" name="state" id="state" onchange="getCitiesByState(this.value)">
										<?php
										$state = isset($_POST['state']) ? $_POST['state'] : '';
										echo $db->MenuItemsDropdown('states_master', 'STATE_ID', 'STATE_NAME', 'STATE_ID,STATE_NAME', $state, ' ORDER BY STATE_NAME ASC'); ?>
									</select>
									<span class="help-block"><?= (isset($errors['state'])) ? $errors['state'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
								<label for="address2" class="col-sm-3 control-label">City</label>
								<div class="col-sm-9">
									<select class="form-control select2" name="city" id="city">
										<?php
										$city = isset($_POST['city']) ? $_POST['city'] : '';
										echo $db->MenuItemsDropdown('city_master', 'CITY_ID', 'CITY_NAME', 'CITY_ID,CITY_NAME', $city, ' ORDER BY CITY_NAME ASC'); ?>
									</select>
									<span class="help-block"><?= (isset($errors['city'])) ? $errors['city'] : '' ?></span>
								</div>
							</div>

							<div class="form-group">
								<label for="postcode" class="col-sm-3 control-label">Country</label>
								<div class="col-sm-9">
									<select class="form-control" name="country_sel" id="country_sel" disabled>
										<?php
										$country = isset($_POST['country']) ? $_POST['country'] : 1;
										echo $db->MenuItemsDropdown('countries_master', 'COUNTRY_ID', 'COUNTRY_NAME', 'COUNTRY_ID,COUNTRY_NAME', $country, ' WHERE COUNTRY_ID=1 ORDER BY COUNTRY_NAME ASC'); ?>
									</select>
									<input type="hidden" name="country" id="country" value="1" />
								</div>
							</div>
							<div class="form-group <?= (isset($errors['postcode'])) ? 'has-error' : '' ?>">
								<label for="postcode" class="col-sm-3 control-label">Postal Code</label>
								<div class="col-sm-9">
									<input class="form-control" maxlength="6" id="postcode" name="postcode" placeholder="Postcode" value="<?= isset($_POST['postcode']) ? $_POST['postcode'] : '' ?>" type="text">
									<span class="help-block"><?= (isset($errors['postcode'])) ? $errors['postcode'] : '' ?></span>
								</div>

							</div>
							<div class="form-group">
								<label for="empdetails" class="col-sm-3 control-label">Details about Employer</label>
								<div class="col-sm-9">
									<textarea class="form-control" maxlength="100" id="empdetails" name="empdetails" placeholder="Please provide details about Staff,Infrastructure, Current business, and Reason of joining DITRP" type="text"><?= isset($_POST['empdetails']) ? $_POST['empdetails'] : '' ?></textarea>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['uname'])) ? 'has-error' : '' ?>">
								<label for="uname" class="col-sm-3 control-label">Username</label>
								<div class="col-sm-9">
									<input class="form-control" id="uname" name="uname" placeholder="Username" value="<?= isset($_POST['uname']) ? $_POST['uname'] : '' ?>" type="email" readonly>
									<span class="help-block"><?= (isset($errors['uname'])) ? $errors['uname'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['pword'])) ? 'has-error' : '' ?>">
								<label for="pword" class="col-sm-3 control-label">Password</label>
								<div class="col-sm-9">
									<input class="form-control" id="pword" name="pword" placeholder="Password" value="<?= isset($_POST['pword']) ? $_POST['pword'] : '' ?>" type="password">
									<span class="help-block"><?= (isset($errors['pword'])) ? $errors['pword'] : '' ?></span>
								</div>
							</div>
							<div class="form-group  <?= (isset($errors['confpword'])) ? 'has-error' : '' ?>">
								<label for="confpword" class="col-sm-3 control-label">Confirm Password</label>
								<div class="col-sm-9">
									<input class="form-control" id="confpword" name="confpword" placeholder="Confirm Password" value="<?= isset($_POST['confpword']) ? $_POST['confpword'] : '' ?>" type="password">
									<span class="help-block"><?= (isset($errors['confpword'])) ? $errors['confpword'] : '' ?></span>
								</div>
							</div>
							<div class="form-group">
								<?php
								$status = isset($_POST['status']) ? $_POST['status'] : 0;
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
							<div class="form-group">
								<?php
								$verify = isset($_POST['verify']) ? $_POST['verify'] : 0;
								?>
								<label for="verify" class="col-sm-3 control-label">Verify</label>
								<div class="radio">
									<label>
										<input name="verify" id="optionsRadios3" value="1" <?= ($verify == 1) ? "checked=''" : ''  ?> type="radio">
										Yes
									</label>
									<label>
										<input name="verify" id="optionsRadios4" value="0" <?= ($verify == 0) ? "checked=''" : ''  ?> type="radio">
										No
									</label>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer text-center">
							<a href="list-employer" class="btn btn-default">Cancel</a>
							<input type="submit" name="add_employer" class="btn btn-info" value="Add Employer" />
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">For DITRP Use</h3>
							</div>
							<div class="box-body">
								<div class="form-group <?= (isset($errors['creditcount'])) ? 'has-error' : '' ?>">
									<label for="creditcount" class="col-sm-3 control-label">Credit</label>
									<div class="col-sm-9">
										<input class="form-control" id="creditcount" name="creditcount" value="<?= isset($_POST['creditcount']) ? $_POST['creditcount'] : '100' ?>" placeholder="Credit" type="number">
										<span class="help-block"><?= (isset($errors['creditcount'])) ? $errors['creditcount'] : '' ?></span>
									</div>
								</div>
								<div class="form-group <?= (isset($errors['democount'])) ? 'has-error' : '' ?>">
									<label for="democount" class="col-sm-3 control-label">Demo</label>
									<div class="col-sm-9">
										<input class="form-control" id="democount" name="democount" value="<?= isset($_POST['democount']) ? $_POST['democount'] : '10' ?>" placeholder="Demo per student" type="number">
										<span class="help-block"><?= (isset($errors['democount'])) ? $errors['democount'] : '' ?></span>
									</div>
								</div>
								<div class="form-group">
									<label for="registrationdate" class="col-sm-3 control-label">Register Date</label>
									<div class="col-sm-9">
										<div class="input-group date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input class="form-control pull-right" value="<?= isset($_POST['registrationdate']) ? $_POST['registrationdate'] : $access->curr_date(); ?>" id="registrationdate" type="text" name="registrationdate" onchange="setAccExpDate(this.value)">
										</div>
									</div>
								</div>
								<div class="form-group <?= (isset($errors['expirationdate'])) ? 'has-error' : '' ?>">
									<label for="expirationdate" class="col-sm-3 control-label">Expire Date</label>
									<div class="col-sm-9">
										<div class="input-group date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input class="form-control pull-right" value="<?= isset($_POST['expirationdate']) ? $_POST['expirationdate'] : $access->acc_expiry_date(); ?>" id="expirationdate" type="text" name="expirationdate">

										</div>
										<span class="help-block"><?= (isset($errors['expirationdate'])) ? $errors['expirationdate'] : '' ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<!-- general form elements -->
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Upload Employer Documents</h3>
							</div>
							<div class="box-body">
								<div class="form-group <?= (isset($errors['emplogo'])) ? 'has-error' : '' ?>">
									<label for="emplogo" class="col-sm-4 control-label">Employer Logo</label>
									<div class="col-sm-8">
										<input id="emplogo" name="emplogo" type="file">
										<p class="help-block"><?= (isset($errors['emplogo'])) ? $errors['emplogo'] : 'Logo' ?></p>
									</div>
								</div>
								<div class="form-group  <?= (isset($errors['emppassphoto'])) ? 'has-error' : '' ?>">
									<label for="emppassphoto" class="col-sm-4 control-label">Employer Passport Photo</label>
									<div class="col-sm-8">
										<input id="emppassphoto" name="emppassphoto" type="file">
										<p class="help-block"><?= (isset($errors['emppassphoto'])) ? $errors['emppassphoto'] : 'Photo' ?></p>

									</div>
								</div>
								<div class="form-group <?= (isset($errors['empphotoidproof'])) ? 'has-error' : '' ?>">
									<label for="empphotoidproof" class="col-sm-4 control-label">Photo ID Proof</label>
									<div class="col-sm-8">
										<input id="empphotoidproof" name="empphotoidproof" type="file">
										<p class="help-block"><?= (isset($errors['empphotoidproof'])) ? $errors['empphotoidproof'] : 'Adhaar Card, Voter Id, Driving Licence, Bank Passbook with Photo, etc.' ?></p>
									</div>
								</div>
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