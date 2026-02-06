 <?php


    include(ROOT.'/include/controller/user/update_user.php');
 ?>
 <div id="page-wrapper" >
		<div id="page-inner">
			<div class="row">
				<div class="col-md-12">
				 <h2>Update User</h2>   
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
						Update User 
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
							   <!-- <h3>Basic Form Examples</h3>-->
								<form class="form-horizontal" action="" method="post">
								
								<input type="hidden" value="<?= isset($admin_detail_id)?$admin_detail_id:''; ?>" name="user_id" />
								
								 <div class="form-group">
									<label for="inputEmail3" class="col-sm-3 control-label">First Name</label>
									<div class="col-sm-8">
									  <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $first_name ?>" placeholder="First name" data-validation="alphanumeric">
									</div>
								  </div>								   
								  <div class="form-group">
									<label for="inputEmail3" class="col-sm-3 control-label">Last Name</label>
									<div class="col-sm-8">
									  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last name" value="<?= $last_name ?>" data-validation="alphanumeric" >
									</div>
								  </div>
								  <div class="form-group">
									<label for="inputEmail3" class="col-sm-3 control-label">Email <span class="required">*</span></label>
									<div class="col-sm-8">
									  <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($email)?$email:'' ?>" data-validation="email">
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
									<label for="inputPassword3" class="col-sm-3 control-label">Role <span class="required">*</span></label>
									<div class="col-sm-8">
									  <select class="form-control" id="role" name="role" data-validation="required">										
										<?php										
										$db->MenuItemsDropdown ("admin_role_master","ADMIN_ROLE_ID","ROLE_NAME","ADMIN_ROLE_ID,ROLE_NAME","$role"," ORDER BY ROLE_NAME ASC");
										?>
									  </select>
									</div>
								  </div>
								    <div class="form-group">
								  <label for="inputPassword3" class="col-sm-3 control-label">Responsibilty <span class="required">*</span></label>
									<div class="col-sm-offset-3">
									<div class="checkbox">
										<label>
										  <input type="checkbox" name="responsibility_all" id="checkAll"  onClick="toggle(this)"> All
										</label>
									  </div>
									<?php 
									
									$responsiblityRes = $db->get_responsibilities();
									while($data = $responsiblityRes->fetch_assoc())
									{
										$resp_id = $data['RESPONSIBILITY_ID'];
										$resp_name = $data['RESPONSIBILITY_NAME'];									
										$user_resp = $user->get_user_responsibilities($id);
										$checked = '';
										
										if(in_array($resp_id, $user_resp))
										{
											/*echo '<div class="checkbox">
													<label>
													  <input type="checkbox" name="responsibility[]" checked="checked"  value="'.$resp_id.'"> '.$resp_name.'
													</label>
												  </div>';*/
												  $checked = "checked='checked'";
										}else{
											$checked = '';
											/*echo '<div class="checkbox">
												<label>
												  <input type="checkbox" name="responsibility[]"  value="'.$resp_id.'"> '.$resp_name.'
												</label>
											  </div>';*/
											  
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
										
										$db->MenuItemsDropdown ("admin_status_master","ADMIN_STATUS_ID","STATUS_NAME","ADMIN_STATUS_ID,STATUS_NAME","$status"," ORDER BY STATUS_NAME ASC");
										?>
									  </select>
									</div>
								  </div>
								 
								  <div class="form-group">
									<div class="col-sm-offset-3 col-sm-8">
									  <input type="submit" name="update_user" id="update_user" value="Update User" class="btn btn-primary" />
									  <a href="page.php?p=list-users" class="btn btn-warning">Cancel</a>
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
