 <?php
  include(ROOT.'/include/controller/user/add_user.php');
 ?>
 <div id="page-wrapper" >
		<div id="page-inner">
			<div class="row">
				<div class="col-md-12">
				 <h2>Add New Post</h2>  
				<h5 class="bread"><a href="page.php?p=list-posts">Posts</a> <i class="fa fa-angle-double-right"></i> Add Post </h5>				 
				  <?php if(isset($msg)){?>
				  <p class="alert alert-danger"><?= $msg ?></p>
				  <?php } ?>
				</div>
			</div>
			 <!-- /. ROW  -->
			 <hr />
		   <div class="row">
			<div class="col-md-12">
				<!-- Form Elements -->
				<div class="panel panel-default">
					<div class="panel-heading">
						Add New Post 
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
							   <!-- <h3>Basic Form Examples</h3>-->
								<form class="form-horizontal" action="" method="post">
								 <div class="form-group">
									<label for="inputEmail3" class="col-sm-3 control-label">First Name</label>
									<div class="col-sm-8">
									  <input type="text" class="form-control" id="first_name" name="first_name" value="<?= isset($first_name)?$first_name:'' ?>" placeholder="First name" data-validation="alphanumeric">
									</div>
								  </div>								   
								  <div class="form-group">
									<label for="inputEmail3" class="col-sm-3 control-label">Last Name</label>
									<div class="col-sm-8">
									  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last name" value="<?= isset($last_name)?$last_name:'' ?>" data-validation="alphanumeric" >
									</div>
								  </div>
								  <div class="form-group">
									<label for="inputEmail3" class="col-sm-3 control-label">Email <span class="required">*</span></label>
									<div class="col-sm-8">
									  <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($email)?$email:'' ?>" data-validation="email">
									  <?php if(isset($email_err) && !empty($email_err)) echo '<span class="required">'.$email_err.'</span>'; ?>
									</div>
								  </div>
								  <div class="form-group">
									<label for="inputEmail3" class="col-sm-3 control-label">Mobile </label>
									<div class="col-sm-8">
									  <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile" value="<?= isset($mobile)?$mobile:'' ?>" >
									</div>
								  </div>
								   <div class="form-group">
									<label for="inputEmail3" class="col-sm-3 control-label">Description </label>
									<div class="col-sm-8">
									   <textarea class="form-control" id="description" name="description" placeholder="Description"><?= isset($description)?$description:'' ?></textarea>
									</div>
								  </div>
								  <div class="form-group">
									<label for="inputEmail3" class="col-sm-3 control-label">Username <span class="required">*</span></label>
									<div class="col-sm-8">
									  <input type="text" class="form-control" id="uname" name="uname" placeholder="Enter username" value="<?= isset($uname)?$uname:'' ?>" data-validation="length alphanumeric" 
		 data-validation-length="3-12" 
		 data-validation-error-msg="User name has to be an alphanumeric value (3-12 chars)">
										 <?php if(isset($uname_err) && !empty($uname_err)) echo '<span class="required">'.$uname_err.'</span>'; ?>
									</div>
								  </div>
								  <div class="form-group">
									<label for="inputPassword3" class="col-sm-3 control-label">Password <span class="required">*</span></label>
									<div class="col-sm-8">
									  <input class="form-control" type="password" name="pword" id="pword" placeholder="Enter Password" data-validation="length required" data-validation-length="min6" data-validation-error-msg="Password should contain minimum 6 characters" value="<?= isset($pword)?$pword:'' ?>">
									</div>
								  </div>
								  <div class="form-group">
									<label for="inputPassword3" class="col-sm-3 control-label">Repeat Password <span class="required">*</span></label>
									<div class="col-sm-8">
									  <input class="form-control" type="password" id="re_pword" name="re_pword" placeholder="Repeat Password" value="<?= isset($re_pword)?$re_pword:'' ?>" data-validation="length required" data-validation-length="min6" data-validation-error-msg="Password should contain minimum 6 characters">
									  <?php if(isset($pass_err) && !empty($pass_err)) echo '<span class="required">'.$pass_err.'</span>';?>
									</div>
								  </div>
								  <div class="form-group">
									<label for="inputPassword3" class="col-sm-3 control-label">Role <span class="required">*</span></label>
									<div class="col-sm-8">
									  <select class="form-control" id="role" name="role" data-validation="required">
										
										<?php
										$role = isset($role)?$role:'';
										$db->MenuItemsDropdown ("admin_role_master","ADMIN_ROLE_ID","ROLE_NAME","ADMIN_ROLE_ID,ROLE_NAME","$role"," WHERE ADMIN_ROLE_ID!=1 ORDER BY ROLE_NAME ASC");
										?>
									  </select>
									</div>
								  </div>
								  <div class="form-group">
								  <label for="inputPassword3" class="col-sm-3 control-label">Responsibilty <span class="required">*</span></label>
									<div class="col-sm-offset-3">
									<div class="checkbox">
										<label>
										  <input type="checkbox" name="responsibility_all" id="checkAll"  onClick="toggle(this)" <?php if(isset($responsibility_all)) echo "checked='checked'"; ?>> All
										</label>
									  </div>
									<?php 
									
									$responsiblityRes = $db->get_responsibilities();
									while($data = $responsiblityRes->fetch_assoc())
									{
										$resp_id = $data['RESPONSIBILITY_ID'];
										$resp_name = $data['RESPONSIBILITY_NAME'];									
										$checked = "";
										if(isset($responsibility) && is_array($responsibility))
										{
											if(in_array($resp_id, $responsibility)) $checked="checked='checked'";
											else $checked = '';
										}	
										echo '<div class="checkbox">
										<label>
										  <input type="checkbox" name="responsibility[]"  value="'.$resp_id.'" '.$checked.'> '.$resp_name.'
										</label>
									  </div>';
									}
									?>
									</div>

								  </div>
								  <div class="form-group">
									<label for="inputPassword3" class="col-sm-3 control-label">Status <span class="required">*</span></label>
									<div class="col-sm-8">
									  <select class="form-control" id="status" name="status" data-validation="required">
										
										<?php
										$status = isset($status)?$status:'';
										$db->MenuItemsDropdown ("admin_status_master","ADMIN_STATUS_ID","STATUS_NAME","ADMIN_STATUS_ID,STATUS_NAME","$status"," ORDER BY STATUS_NAME ASC");
										?>
									  </select>
									</div>
								  </div>
								 
								  <div class="form-group">
									<div class="col-sm-offset-3 col-sm-8">
									  <input type="submit" name="add_user" id="add_user" value="Add New User" class="btn btn-primary" />
									  <a name="add_user" id="add_user" href="page.php?p=list-users" class="btn btn-warning">Cancel</a>
									</div>
									
								  </div>
								</form>
							</div>
						
						   
						</div>
					</div>
				</div>
				 <!-- End Form Elements -->
			</div>
		</div>
			<!-- /. ROW  -->		   
	</div>
		 <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->
