<?php
$institute_id = isset($_GET['id'])?$_GET['id']:1;
/* get institute details */
	$res = $institute->list_institute($institute_id,'');
	if($res!='')
	{
		$srno=1;
		while($data = $res->fetch_assoc())
		{
			extract($data);			
		}
	}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Update Details</h4>          
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">  
			<?php
				if(isset($success))
				{
				?>
				<div class="row">
				<div class="col-sm-12">
				<div class="alert alert-<?= ($success==true)?'success':'danger' ?> alert-dismissible" id="messages">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
					<h4><i class="icon fa fa-check"></i> <?= ($success==true)?'Success':'Error' ?>:</h4>
					<?= isset($message)?$message:'Please correct the errors.'; ?>
					<?php
					if(!empty($errors))
					{
						echo "<ul>";
						foreach($errors as $err)
						{
							echo "<li>$err</li>";
						}
						echo "</ul>";
					}
					?>
				</div>
				</div>
				</div>
				<?php
				}
				?>
				<input type="hidden" name="institute_id" value="<?= isset($INSTITUTE_ID)?$INSTITUTE_ID:'' ?>" />
				<input type="hidden" name="institute_login_id" value="<?= isset($USER_LOGIN_ID)?$USER_LOGIN_ID:'' ?>" />
				<input type="hidden" name="status" value="<?= $ACTIVE ?>" />
				
				<input type="hidden" name="creditcount" value="<?= $CREDIT ?>" />
				<input type="hidden" name="democount" value="<?= $DEMO_PER ?>" />
				<input type="hidden" name="registrationdate" value="<?= $REG_DATE ?>" />
				<input type="hidden" name="expirationdate" value="<?= $EXP_DATE ?>" />
				<input type="hidden"  name="uname" id="uname" value="<?= $USER_NAME ?>" />
				<div class="row">
					<div class="form-group col-sm-4 <?= (isset($errors['instcode']))?'has-error':'' ?>">
						<label for="instcode" class="control-label">Institute Code</label>
						<div class="">
							<input class="form-control" id="instcode" name="instcode" placeholder="Institute Code" value="<?= isset($_POST['instcode'])?$_POST['instcode']:$INSTITUTE_CODE ?>" 	type="text">
							<span class="help-block"><?= isset($errors['instcode'])?$errors['instcode']:'' ?></span>
						</div>
					</div>
			
					<div class="form-group col-sm-4 <?= (isset($errors['instname']))?'has-error':'' ?>">
						<label for="instname" class="control-label">Institute Name</label>
						<div class="">
							<input type="hidden" name="instname" value="<?= $INSTITUTE_NAME ?>" />
							<input class="form-control" id="instname" name="instname" placeholder="Institute name" value="<?= isset($_POST['instname'])?$_POST['instname']:$INSTITUTE_NAME ?>" type="text" =>
							<span class="help-block"><?= isset($errors['instname'])?$errors['instname']:'' ?></span>
						</div>
					</div>

					<div class="form-group col-sm-4 <?= (isset($errors['instowner']))?'has-error':'' ?>">
						<label for="instowner" class="control-label">Owner Name</label>
						<div class="">
							<input class="form-control" id="instowner" name="instowner" placeholder="Institute owner name" value="<?= isset($_POST['instowner'])?$_POST['instowner']:$INSTITUTE_OWNER_NAME ?>" type="text" />
							<span class="help-block"><?= isset($errors['instowner'])?$errors['instowner']:'' ?></span>
						</div>
					</div>
				
                <div class="form-group col-sm-4 <?= (isset($errors['dob']))?'has-error':'' ?>">
					<label for="dob" class="control-label">Date Of Birth</label>
					<div class="">
					  <input class="form-control pull-right" name="dob" value="<?= isset($_POST['dob'])?$_POST['dob']:$DOB ?>"  id="dob" type="date">
					<span class="help-block"><?= (isset($errors['dob']))?$errors['dob']:'' ?></span>
					</div>
				</div>
			
				
				<div class="form-group col-sm-4 <?= (isset($errors['email']))?'has-error':'' ?>">
                  <label for="email" class="control-label">Email</label>
                  <div class="">
                    <input class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($_POST['email'])?$_POST['email']:$EMAIL ?>"  type="email" onchange="document.getElementById('uname').value = this.value;">
					<span class="help-block"><?= isset($errors['email'])?$errors['email']:'' ?></span>
                  </div>
                </div>
                
				<div class="form-group col-sm-4 <?= (isset($errors['mobile']))?'has-error':'' ?>">
                  <label for="mobile" class="control-label">Mobile</label>
                  <div class="">
                       <input type="hidden" name="mobile" value="<?= $MOBILE ?>" />
                    <input class="form-control" id="mobile" name="mobile" maxlength="10" placeholder="Mobile" value="<?= isset($_POST['mobile'])?$_POST['mobile']:$MOBILE ?>" type="text">
					<span class="help-block"><?= isset($errors['mobile'])?$errors['mobile']:'' ?></span>
                  </div>
                </div>

				<div class="form-group col-sm-4">
                  <label for="address2" class="control-label">Address</label>
                  <div class="">
                    <textarea class="form-control" id="address1" name="address1" placeholder="Address" type="text"><?= isset($_POST['address1'])?$_POST['address1']:$ADDRESS_LINE1 ?></textarea> 
                  </div>
                </div>	

                <div class="form-group col-sm-4">
                  <label for="taluka" class="control-label">Taluka</label>
                  <div class="">
                    <input class="form-control" id="taluka" name="taluka" placeholder="Taluka Name" type="text" value="<?= isset($_POST['taluka'])?$_POST['taluka']:$TALUKA ?>" />
                  </div>
                </div>

				<div class="form-group col-sm-4 <?= (isset($errors['city']))?'has-error':'' ?>">
                  <label for="address2" class="control-label">City</label>
                  <div class="">
					<input class="form-control" id="city" name="city" placeholder="City Name" type="text" value="<?= isset($_POST['city'])?$_POST['city']:$CITY ?>" />
					<span class="help-block"><?= isset($errors['city'])?$errors['city']:'' ?></span>
                  </div>
                </div>
		
				<div class="form-group col-sm-4 <?= (isset($errors['state']))?'has-error':'' ?>">
                  <label for="state" class="control-label">State</label>
                  <div class="">
                    <input type="hidden" name="state" value="<?= $STATE ?>" />
                    <select class="form-control" name="state" id="state" onchange="getCitiesByState(this.value)">
						<?php
						$state = isset($_POST['state'])?$_POST['state']:$STATE;
						echo $db->MenuItemsDropdown ('states_master','STATE_ID','STATE_NAME','STATE_ID,STATE_NAME',$state,' ORDER BY STATE_NAME ASC'); ?>
					</select>
					<span class="help-block"><?= isset($errors['state'])?$errors['state']:'' ?></span>
                  </div>
                </div>

				<div class="form-group col-sm-4">
                  <label for="postcode" class="control-label">Postal Code</label>
                  <div class="">
                    <input class="form-control" id="postcode" name="postcode" placeholder="Postcode" value="<?= isset($_POST['postcode'])?$_POST['postcode']:$POSTCODE ?>" maxlength="6" type="text">
                  </div>
                </div>		
				
				<div class="form-group col-sm-4 <?= (isset($errors['uname']))?'has-error':'' ?>">
                  <label for="uname1" class="control-label">Username</label>
                  <div class="">
                    <input class="form-control" id="uname1" name="uname1" placeholder="Username" value="<?= $USER_NAME ?>" disabled type="text" style="text-transform: none;" />
					<span class="help-block"><?= isset($errors['uname'])?$errors['uname']:'' ?></span>
                  </div>
                </div>

                <div class="form-group col-sm-4 <?= (isset($errors['pword']))?'has-error':'' ?>">
                  <label for="pword" class="control-label">New Password</label>
                  <div class="">
                    <input class="form-control" id="pword" name="pword" placeholder="New Password" value="<?= isset($_POST['pword'])?$_POST['pword']:'' ?>" type="password" autocomplete="new-password">
					<span class="help-block"><?= isset($errors['pword'])?$errors['pword']:'' ?></span>
                  </div>
                </div>

				<div class="form-group col-sm-4 <?= (isset($errors['confpword']))?'has-error':'' ?>">
                  <label for="confpword" class="control-label">Confirm New Password</label>
                  <div class="">
                    <input class="form-control" id="confpword" name="confpword" placeholder="Confirm New Password" value="<?= isset($_POST['confpword'])?$_POST['confpword']:'' ?>" type="password" autocomplete="new-password">
					<span class="help-block"><?= isset($errors['confpword'])?$errors['confpword']:'' ?></span>
                  </div>
                </div>
                
                <div class="form-group col-sm-4 <?= (isset($errors['gstno']))?'has-error':'' ?>">
                  <label for="gstno" class="control-label">GST Number</label>
                  <div class="">
                    <input class="form-control" id="gstno" name="gstno" placeholder="GST Number" value="<?= isset($_POST['gstno'])?$_POST['gstno']:$GSTNO ?>" type="text">
					<span class="help-block"><?= isset($errors['gstno'])?$errors['gstno']:'' ?></span>
                  </div>
                </div>
				  
				<div class="form-group col-sm-4 thumbnail <?= (isset($errors['instlogo']))?'has-error':'' ?>">
				  <label for="instlogo" class="control-label">Institute Logo</label>
				  <div class="col-sm-8">
				  <?= $institute->get_institute_docs_all($INSTITUTE_ID, 'logo', true);	?>				
					<input id="instlogo" name="instlogo" type="file">			
				   <p class="help-block"><?= (isset($errors['instlogo']))?$errors['instlogo']:'Logo' ?></p>
				  </div>
				</div>

				<div class="form-group col-sm-4  thumbnail <?= (isset($errors['passphoto']))?'has-error':'' ?>">
				  <label for="passphoto" class="control-label">Owner Passport size Photo</label>
				  <div class="col-sm-8">
				   <?= $institute->get_institute_docs_all($INSTITUTE_ID, 'owner_photo', true);	?>				 
					<input id="passphoto" name="passphoto" type="file">				
				   <p class="help-block"><?= (isset($errors['passphoto']))?$errors['passphoto']:'Photo' ?></p>	
				  </div>
				</div>
				<div class="form-group col-sm-4  thumbnail <?= (isset($errors['instsign']))?'has-error':'' ?>">
		            <label for="instsign" class="control-label">Institute Signatute</label>
		            <div class="col-sm-8">
		              <?= $institute->get_institute_docs_all($INSTITUTE_ID, 'sign', true); ?>
		            <input id="instsign" name="instsign" type="file">
		             <p class="help-block"><?= (isset($errors['instsign']))?$errors['instsign']:'Sign' ?></p>
		            </div>
		         </div>
				</div>
			
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="index.php" class="btn btn-default">Cancel</a>
                <input type="submit" name="update_institute" class="btn btn-info" value="Update Institute" />
              </div>             
          </div> 
        
              
          </form>
      
      </div>
    </div>
  </div>
</div>
		