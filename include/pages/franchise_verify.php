<?php
$data=array();
$action= isset($_POST['find_institute'])?$_POST['find_institute']:'';
if($action!='')
{

   $institute_code =$db->test(isset($_POST['code'])?strtoupper($_POST['code']):'');
	$result	=$db->find_institute($institute_code);
	$result = json_decode($result, true);
	$success= isset($result['success'])?$result['success']:'';
	$message= isset($result['message'])?$result['message']:'';
	$errors = isset($result['errors'])?$result['errors']:'';
	$data = isset($result['data'])?$result['data']:'';
	if($success==true)
	{
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
			
		
	}
	
	
}

?>
         
         
         
         
	<?php
	$res = $websiteManage->list_headimages('', '');           
	if($res!='')
	{
		while($data1 = $res->fetch_assoc())
		{
			extract($data1);
			$image = 'resources/default_images/about_default.jpg';
			if($verification!='')
				$image     = BANNERS_PATH.'/'.$id.'/'.$verification;
?>
<div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
	<div class="breadcrumbs-inner">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<h1 class="page-title">Franchise Verification</h1>
					<ul>
						<li>
							<a class="active" href="index.php">Home</a>
						</li>
						<li>Verification</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	}
}
?>
<div id="rs-events" class="rs-events sec-spacer">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<form action="" method="post" enctype="multipart/form-data">
					<div class="row">
						<h2 class="title-default-left title-bar-high mt-50">Franchise Verification (ATC Verification)</h2>	
								
						<?php					
						if(isset($success))
						{
						?>
						<div class="row">
						<div class="col-sm-12">
						<div class="alert alert-<?= ($success==true)?'success':'danger' ?> alert-dismissible" id="messages">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
							
							<?= isset($message)?$message:'Please correct the errors.'; ?>
							<?php
							if(isset($errors) && !empty($errors)){
								echo '<ul>';
								foreach($errors as $err)
								{
									echo '<li>'.$err.'</li>';
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

						<div class="col-md-12 col-sm-8">
							<div id="login">
							<div class="form-group">
			
								<input name="code" type="text" value="<?= isset($_POST['code'])?$_POST['code']:'' ?>" style="    padding: 10px; font-size: 20px;">
								</div>

							<div class="">
							<input type="submit" name="find_institute" class="btn btn-primary" value="Verify" /> 
							<a href="<?= HTTP_HOST ?>" class="btn btn-danger">Cancel</a>

							</div>
									<br><br>
							</div>
						</div>

						
					</div>
				</form>
			</div>
		</div>
		<?php
		if(!empty($data))
		{
			
			$INSTITUTE_ID 		= $data['INSTITUTE_ID'];					
			$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];
			$INSTITUTE_NAME 	= $data['INSTITUTE_NAME'];
			$INSTITUTE_OWNER_NAME= $data['INSTITUTE_OWNER_NAME'];
			$EMAIL 				= $data['EMAIL'];
			$ADDRESS_LINE1 		= $data['ADDRESS_LINE1'];
			$ADDRESS_LINE2 		= $data['ADDRESS_LINE2'];
			$MOBILE 			= $data['MOBILE'];
			$CITY			= $data['CITY'];	
			$STATE_NAME 			= $data['STATE_NAME'];
			//$DISPLAY_ON_WEBSITE = $data1['DISPLAY_ON_WEBSITE'];
			
			
			$logo =$db->get_institute_docs_single($INSTITUTE_ID, 'logo');
			$logopath = $logo;
			$certverData =$INSTITUTE_ID.':'.$INSTITUTE_CODE.':'.$EMAIL;
			
			$owner_photo = $db->get_institute_docs_single($INSTITUTE_ID, 'owner_photo');
			$owner_photo_path = $owner_photo;
		?>
		<div class="row">
			<div class="col-sm-12 fverify">
			<h4 class="title-default-left title-bar-high">Franchise Details (ATC Details)</h4>
	    	
					<div class="table-responsive">
						<table class="table table-bordered table-responsive">
															
						<tr>
							<td>INSTITUTE LOGO</td>
							<td>
								<img src="<?= $logopath ?>" class="img img-responsive img-rounded" style="height: 100px;">
							</td>
						</tr>
						<tr>
							<td>OWNER PHOTO</td>
							<td>
								<img src="<?= $owner_photo_path ?>" class="img img-responsive img-rounded" style="height: 100px;">
							</td>
						</tr>
						<tr>
							<td>INSTITUTE CODE</td>
							<td><?= $INSTITUTE_CODE ?></td>				
						</tr>
						<tr>
							<td>INSTITUTE NAME</td>
							<td><?= $INSTITUTE_NAME ?></td>				
						</tr>
						<tr>
							<td>INSTITUTE OWNER NAME</td>
							<td><?= $INSTITUTE_OWNER_NAME ?></td>				
						</tr>
						<tr>
							<td>INSTITUTE EMAIL</td>
							<td><?= $EMAIL ?></td>				
						</tr>
						<tr>
							<td>INSTITUTE CONTACT NUMBER</td>
							<td><?= $MOBILE ?></td>				
						</tr>
						<tr>
							<td>INSTITUTE ADDRESS</td>
							<td><?= $ADDRESS_LINE1.' ,'.$CITY.' , '.$STATE_NAME ?></td>				
						</tr>
						<!-- <tr>
							<th>INSTITUTE VERIFIED CERTIFICATE</th>
							<td>
								<form method="post" id="certVerifyForm" action="franchise_certificate_verify.php">
									<input type="hidden" name='code' value="<?= base64_encode($INSTITUTE_CODE)?>">
									<input type="submit" name="verify_certificate" value="View Cerificate" class="default-big-btn" />
								</form>
							</td>				
						</tr> -->
					</table>
					</div>
				</div>
			</div>
		</div>
		
<?php
}
?>

<script type="text/javascript">
	var myForm = document.getElementById('certVerifyForm');
	myForm.onsubmit = function() {
		var w = window.open('about:blank','Popup_Window','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=650,height=800,left = 312,top = 30');
		this.target = 'Popup_Window';
	};
</script>