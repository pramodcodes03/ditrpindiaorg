<?php
//$student_id = $db->test(isset($_GET['id'])?$_GET['id']:'');
$student_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:'';
$inst_course_id = isset($_REQUEST['inst_course'])?$_REQUEST['inst_course']:'';
?>
<div class="content-wrapper">
<h2 class="card-title">Course Information </h2> 
<?php
	if(isset($_SESSION['msg']))
	{
		$message = isset($_SESSION['msg'])?$_SESSION['msg']:'';
		$msg_flag =$_SESSION['msg_flag'];
	?>
	<div class="row">
	<div class="col-sm-12">
	<div class="alert alert-<?= ($msg_flag==true)?'success':'danger' ?> alert-dismissible" id="messages">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
		<h4><i class="icon fa fa-check"></i> <?= ($msg_flag==true)?'Success':'Error' ?>:</h4>
		<?= ($message!='')?$message:'Sorry! Something went wrong!'; ?>
	</div>
		</div>
		</div>
	<?php
	unset($_SESSION['msg']);
	unset($_SESSION['msg_flag']);
	}
?> 	
	<div class="row">
		<?php
           $courseinfo	= $db->get_inst_course_info($inst_course_id);
           if(!empty($courseinfo))
           {
           
                   extract($courseinfo);
                   $COURSE_AWARD_NAME='';               
                   $COURSE_AWARD_NAME=$db->get_course_award($COURSE_ID);
                   
                   
           ?>
		<div class="col-lg-6 stretch-card">
			<div class="card">
				<div class="card-body">	
					<div class="row">
						<div class="col-md-12 grid-margin grid-margin-md-0 stretch-card">
							<div class="card">
								<div class="card-body text-center">
									<div>
										<img src="../../../../../images/faces/face5.jpg" class="img-lg rounded-circle mb-2" alt="profile image">
										<h4>Maria Johnson</h4>
										<p class="text-muted mb-0">Developer</p>
									</div>
									<p class="mt-2 card-text">
											Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
											Aenean commodo ligula eget dolor. Lorem
									</p>
									<button class="btn btn-info btn-sm mt-3 mb-4">Follow</button>
									<div class="border-top pt-3">
										<div class="row">
											<div class="col-4">
												<h6>5896</h6>
												<p>Post</p>
											</div>
											<div class="col-4">
												<h6>1596</h6>
												<p>Followers</p>
											</div>
											<div class="col-4">
												<h6>7896</h6>
												<p>Likes</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>				
				</div>
			</div>
		</div>
		<?php
           }
           $res = $db->get_aicpe_course_files($COURSE_ID);
           if($res!='')
           {
           ?>
		   <div class="col-lg-6 stretch-card">
				<div class="card">
					<div class="card-body">	
						<div class="row">
							<div class="col-md-12 grid-margin grid-margin-md-0 stretch-card">
								<div class="card">
									<div class="card-body text-center">
										<div>
											<img src="../../../../../images/faces/face5.jpg" class="img-lg rounded-circle mb-2" alt="profile image">
											<h4>Maria Johnson</h4>
											<p class="text-muted mb-0">Developer</p>
										</div>
										<p class="mt-2 card-text">
												Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
												Aenean commodo ligula eget dolor. Lorem
										</p>
										<button class="btn btn-info btn-sm mt-3 mb-4">Follow</button>
										<div class="border-top pt-3">
											<div class="row">
												<div class="col-4">
													<h6>5896</h6>
													<p>Post</p>
												</div>
												<div class="col-4">
													<h6>1596</h6>
													<p>Followers</p>
												</div>
												<div class="col-4">
													<h6>7896</h6>
													<p>Likes</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>				
					</div>
				</div>
			</div>
		<?php			
           }
        ?>
		</div>
</div>