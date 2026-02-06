<?php
include_once('database_results.class.php');
include_once('access.class.php');

class institute extends access
{
	public function helpSupport_progress()
	{
		$total = 0;
		$data = NULL;
		$sql = "SELECT COUNT(`TICKET_ID`) AS total FROM `help_support` WHERE `CURRENT_STATUS`='1' ";
		//	$sql .= ' ORDER BY INSTITUTE_ID DESC';
		//	echo $sql;
		$res = parent::execQuery($sql);
		$data = $res->fetch_assoc();
		$total = $data['total'];
		return $total;
	}
	public function helpSupport_closed()
	{
		$total = 0;
		$data = NULL;
		$sql = "SELECT COUNT(`TICKET_ID`) AS total FROM `help_support` WHERE `CURRENT_STATUS`='2' ";
		//	$sql .= ' ORDER BY INSTITUTE_ID DESC';
		//	echo $sql;
		$res = parent::execQuery($sql);
		$data = $res->fetch_assoc();
		$total = $data['total'];
		return $total;
	}

	public function helpSupport_total()
	{
		$total = 0;
		$data = NULL;
		$sql = "SELECT COUNT(`TICKET_ID`) AS total FROM `help_support` WHERE `ACTIVE`='1' AND DELETE_FLAG='0'";
		//	$sql .= ' ORDER BY INSTITUTE_ID DESC';
		//	echo $sql;
		$res = parent::execQuery($sql);
		$data = $res->fetch_assoc();
		$total = $data['total'];
		return $total;
	}



	public function helpSupport()
	{
		$total = 0;
		$data = NULL;
		$sql = "SELECT COUNT(`TICKET_ID`) AS total FROM `help_support` WHERE `USER_ROLE`='7' ";
		$sql .= ' ORDER BY INSTITUTE_ID DESC';
		//	echo $sql;
		$res = parent::execQuery($sql);
		$data = $res->fetch_assoc();
		$total = $data['total'];
		return $total;
	}


	public function comission_paid1_count($userid)
	{
		$total = 0;
		$data = NULL;
		$sql = "SELECT IF(onlinetotal IS NOT NULL,onlinetotal,0) + IF(offlinetotal IS NOT NULL,offlinetotal,0) AS total FROM( SELECT getamctotalcommision($userid,1,'ONLINE') AS onlinetotal, getamctotalcommision($userid,1,'OFFLINE') AS offlinetotal) as tab";
		echo $sql;
		$res = parent::execQuery($sql);
		$data = $res->fetch_assoc();
		$total = $data['total'];
		return $total;
	}

	public function comission_unpaid_count($userid)
	{
		$total = 0;
		$data = NULL;
		$sql = "SELECT IF(onlinetotal IS NOT NULL,onlinetotal,0) + IF(offlinetotal IS NOT NULL,offlinetotal,0) AS total FROM( SELECT getamctotalcommision($userid,0,'ONLINE') AS onlinetotal, getamctotalcommision($userid,0,'OFFLINE') AS offlinetotal) as tab";
		//	echo $sql;
		$res = parent::execQuery($sql);
		$data = $res->fetch_assoc();
		$total = $data['total'];
		return $total;
	}

	public function comission_paid_count($userid)
	{
		$total = 0;
		$data = NULL;
		$sql = "SELECT SUM(`AMC_COMISSION`) AS total FROM `amc_payment` WHERE `AMC_ID`='$userid' ";
		$sql .= ' ORDER BY INSTITUTE_ID DESC';
		//	echo $sql;
		$res = parent::execQuery($sql);
		$data = $res->fetch_assoc();
		$total = $data['total'];
		return $total;
	}

	public function list_Assign_count($userid)
	{
		$total = 0;
		$data = NULL;
		$sql = "SELECT COUNT(INSTITUTE_ID) as total FROM amc_assign WHERE AMC_ID=$userid AND DELETE_FLAG!=1 ";
		$sql .= ' ORDER BY INSTITUTE_ID DESC';
		//echo $sql;
		$res = parent::execQuery($sql);
		$data = $res->fetch_assoc();
		$total = $data['total'];
		return $total;
	}
	/* add new staff in institute 
	@param: 
	@return: json
	*/
	public function add_institute()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		//print_r($_POST); exit();
		$instname 		= parent::test(isset($_POST['instname']) ? $_POST['instname'] : NULL);
		$instowner 		= parent::test(isset($_POST['instowner']) ? $_POST['instowner'] : NULL);
		$designation 		= parent::test(isset($_POST['designation']) ? $_POST['designation'] : NULL);
		$dob 				= parent::test(isset($_POST['dob']) ? $_POST['dob'] : NULL);
		$email 			= parent::test(isset($_POST['email']) ? $_POST['email'] : NULL);
		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : NULL);
		$address1 		= parent::test(isset($_POST['address1']) ? $_POST['address1'] : NULL);
		//$address2		= parent::test(isset($_POST['address2'])?$_POST['address2']:'');
		$state 			= parent::test(isset($_POST['state']) ? $_POST['state'] : NULL);
		$city 			= parent::test(isset($_POST['city']) ? $_POST['city'] : NULL);
		$taluka 			= parent::test(isset($_POST['taluka']) ? $_POST['taluka'] : NULL);

		$country 			= parent::test(isset($_POST['country']) ? $_POST['country'] : 1);
		$postcode 		= parent::test(isset($_POST['postcode']) ? $_POST['postcode'] : NULL);
		$instdetails 		= parent::test(isset($_POST['instdetails']) ? $_POST['instdetails'] : NULL);
		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : NULL);
		$verify 			= parent::test(isset($_POST['verify']) ? $_POST['verify'] : 0);

		$plan 			= parent::test(isset($_POST['plan']) ? $_POST['plan'] : NULL);

		$uname 			= parent::test(isset($_POST['uname']) ? $_POST['uname'] : NULL);
		$pword 			= parent::test(isset($_POST['pword']) ? $_POST['pword'] : NULL);
		$confpword 		= parent::test(isset($_POST['confpword']) ? $_POST['confpword'] : NULL);
		$expirationdate 	= parent::test(isset($_POST['expirationdate']) ? $_POST['expirationdate'] : NULL);
		$registrationdate    = parent::test(isset($_POST['registrationdate']) ? $_POST['registrationdate'] : NULL);

		$masterpassword    = parent::test(isset($_POST['masterpassword']) ? $_POST['masterpassword'] : NULL);

		$gstno    = parent::test(isset($_POST['gstno']) ? $_POST['gstno'] : NULL);

		$prime_member    = parent::test(isset($_POST['prime_member']) ? $_POST['prime_member'] : NULL);
		$prime_memberdate    = parent::test(isset($_POST['prime_memberdate']) ? $_POST['prime_memberdate'] : NULL);

		$prime_admission    = parent::test(isset($_POST['prime_admission']) ? $_POST['prime_admission'] : NULL);

		/*$creditcount 		= parent::test(isset($_POST['creditcount'])?$_POST['creditcount']:'');
		  $democount 		= parent::test(isset($_POST['democount'])?$_POST['democount']:'');*/

		$no_of_comp 		= parent::test(isset($_POST['no_of_comp']) ? $_POST['no_of_comp'] : 'NULL');
		$no_of_staff 		= parent::test(isset($_POST['no_of_staff']) ? $_POST['no_of_staff'] : 'NULL');

		/* Files */
		$instlogo 			= isset($_FILES['instlogo']['name']) ? $_FILES['instlogo']['name'] : NULL;
		$passphoto 		= isset($_FILES['passphoto']['name']) ? $_FILES['passphoto']['name'] : NULL;
		$photoidproof 		= isset($_FILES['photoidproof']['name']) ? $_FILES['photoidproof']['name'] : NULL;
		$instregcertificate    = isset($_FILES['instregcertificate']['name']) ? $_FILES['instregcertificate']['name'] : NULL;
		$educationalproof 	= isset($_FILES['educationalproof']['name']) ? $_FILES['educationalproof']['name'] : NULL;
		$profcourseproof 	= isset($_FILES['profcourseproof']['name']) ? $_FILES['profcourseproof']['name'] : NULL;
		$instphotos 		= isset($_FILES['instphotos']['name']) ? $_FILES['instphotos']['name'] : NULL;

		$instsign 			= isset($_FILES['instsign']['name']) ? $_FILES['instsign']['name'] : NULL;
		$inststamp 		= isset($_FILES['inststamp']['name']) ? $_FILES['inststamp']['name'] : NULL;


		$admin_id 		= $_SESSION['user_id'];
		$role 		= 8; //institute staff;
		$created_by  		= $_SESSION['user_fullname'];
		$created_by_ip  		= $_SESSION['ip_address'];

		/* check validations */
		//new validations
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Invalid email format";
		}
		/*if($instname!='')
			{
				if (!preg_match("/^[a-zA-Z0-9]*$/",$instname)) {
				$errors['instname'] = "Only letters and white space allowed";}
			}*/
		if ($instowner != NULL) {
			if (!preg_match("/^[a-zA-Z ]*$/", $instowner)) {
				$errors['instowner'] = "Only letters and white space allowed";
			}
		}
		/*if($designation!='')
			{
				if (!preg_match("/^[a-zA-Z ]*$/",$designation)) {
				$errors['designation'] = "Only letters and white space allowed";}
			}
			*/
		if ($mobile != NULL) {
			if (strlen($mobile) != 10) {
				$errors['mobile'] = 'Only 10 Digits allowed.';
			}
			$first_no = $mobile[0]; //substr($mobile,1);
			/*	$arr = array('9','8','7');
				if(!in_array($first_no,$arr))
				{
					$errors['mobile'] = 'Only letters and white space allowed. Mobile number should start with 9 or 8 or 7 only.';
				}*/
		}
		if ($postcode != NULL) {
			if (strlen($postcode) != 6)
				$errors['postcode'] = 'Postal code must be in number and 6 digits only.';
		}
		//new validations
		if ($instname == NULL)
			$errors['instname'] = 'Institute name is required.';
		if ($email == NULL)
			$errors['email'] = 'Email is required.';
		if ($city == NULL)
			$errors['city'] = 'City is required.';
		if ($state == NULL)
			$errors['state'] = 'State is required.';
		if ($mobile == NULL)
			$errors['mobile'] = 'Mobile number is required.';
		if ($address1 == NULL)
			$errors['address1'] = 'Address is required.';
		if ($uname == NULL)
			$errors['uname'] = 'Username is required.';
		if ($pword == NULL)
			$errors['pword'] = 'Password is required.';
		if ($confpword == NULL)
			$errors['confpword'] = 'Confirm Password is required.';
		if ($pword != $confpword)
			$errors['confpword'] = 'Confirm password doesnt match!.';

		if ($expirationdate == NULL)
			$errors['expirationdate'] = 'Expire Date Is required';

		if ($registrationdate == NULL)
			$errors['registrationdate'] = 'Registration Date Is required';

		// 			if($masterpassword ==NULL){
		// 				$errors['masterpassword'] = 'Master password is required.';
		// 			}

		// 			if($masterpassword != 'Amzad@#$HDIPassword'){
		// 				$errors['masterpassword'] = 'Enter Correct Password.';
		// 			}

		if ($plan == NULL)
			$errors['plan'] = 'Please Select Plan.';

		$state_code = parent::get_state_code($state);

		$curr_date = date('d-m-Y');

		$code = $this->generate_institute_code();

		$instcode = $state_code . '/' . $code;

		/*if($creditcount=='')
			$errors['creditcount'] = 'Please enter credit amount.';
		 // if($democount=='')
			//$errors['democount'] = 'Please enter Demo count to allow per students.';*/

		//  if($expirationdate=='')
		//$errors['expirationdate'] = 'Please enter account expiration date.';

		if (!parent::valid_username($uname))
			$errors['uname'] = 'Sorry! Username is already used.';
		if (!parent::valid_institute_email($email, NULL))
			$errors['email'] = 'Sorry! Email is already used.';

		if (!parent::valid_institute_mobile($mobile, NULL))
			$errors['mobile'] = 'Sorry! Mobile Number is already used.';

		if (!$this->validate_institute_code($instcode))
			$errors['instcode'] = 'Sorry! Institute code is already present.';
		if ($registrationdate != NULL)
			$registrationdate = date('Y-m-d', strtotime($registrationdate));
		if ($expirationdate != NULL)
			$expirationdate = date('Y-m-d', strtotime($expirationdate));

		/* if($creditcount!='' && !ctype_digit($creditcount))
			$errors['creditcount'] = 'Please enter valid credit amount. Should be positive integer only.';
		 if($democount!='' && !ctype_digit($democount))
			$errors['democount'] = 'Please enter valid demo count. Should be positive integer only.';*/

		/* files validations */
		//   if($instlogo=='')		{	$errors['instlogo'] 				= 'Please upload institute logo.';}
		//   if($passphoto=='')		{	$errors['passphoto'] 			= 'Please upload owner photo.';}
		//   if($photoidproof=='')		{ 	$errors['photoidproof'] 		= 'Please upload Photo ID proof.';}
		//   if($instregcertificate==''){ 	$errors['instregcertificate'] 	= 'Please upload Institute Certificate'; }
		//   if($educationalproof=='')	{  	$errors['educationalproof'] 	= 'Please upload educational certificates.'; } 
		//   if($profcourseproof=='')	{	$errors['profcourseproof'] 		= 'Please upload any other professional courses certificates.';}
		/*if($instphotos=='')		{	$errors['instphotos'] 			= 'Please upload institute photos.'; }*/

		//if($instsign=='')	{	$errors['instsign'] 		= 'Please upload institute signature.';}
		// if($inststamp=='')	{	$errors['inststamp'] 		= 'Please upload institute stamp.';}

		if ($instlogo != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($instlogo, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['instlogo'] = 'Invalid file format! Please select valid file.';
			}
		}
		if ($passphoto != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($passphoto, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['passphoto'] = 'Invalid file format! Please select valid file.';
			}
		}
		if ($photoidproof != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($photoidproof, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['photoidproof'] = 'Invalid file format! Please select valid file.';
			}
		}
		if ($instregcertificate != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($instregcertificate, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['instregcertificate'] = 'Invalid file format! Please select valid file.';
			}
		}
		if ($educationalproof != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($educationalproof, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['educationalproof'] = 'Invalid file format! Please select valid file.';
			}
		}
		if ($profcourseproof != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($profcourseproof, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['profcourseproof'] = 'Invalid file format! Please select valid file.';
			}
		}

		if ($instsign != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($instsign, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['instsign'] = 'Invalid file format! Please select valid file.';
			}
		}

		/*		  if($inststamp!='')
		  {
				$allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
				$extension = pathinfo($inststamp, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['inststamp'] = 'Invalid file format! Please select valid file.';
				}
		  }*/

		//$errors=array();
		if (!empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			if ($dob != NULL)
				$dob = @date('Y-m-d', strtotime($dob));
			parent::start_transaction();

			$tableName 	= "institute_details";
			$tabFields 	= "(INSTITUTE_ID, INSTITUTE_CODE, INSTITUTE_NAME, INSTITUTE_OWNER_NAME,DOB,DESIGNATION,ADDRESS_LINE1,MOBILE,EMAIL,CITY,STATE,COUNTRY,TALUKA,POSTCODE,NO_OF_COMPUTERS,NO_OF_STAFF,DETAIL_DESCRIPTION,PLAN_ID,GSTNO,ACTIVE,VERIFIED, CREATED_BY, CREATED_ON, CREATED_ON_IP,DEMO_PER,PRIMEMEMBER,PRIMEMEMBER_DATE,NUMBER_OF_ADMISSION";
			$insertVals	= "(NULL, UPPER('$instcode'), UPPER('$instname'), '$instowner','$dob','$designation','$address1','$mobile','$email','$city','$state','$country','$taluka','$postcode',' $no_of_comp','$no_of_staff','$instdetails','$plan',UPPER('$gstno'),'$status','$verify','$created_by',NOW(),'$created_by_ip','50','$prime_member','$prime_memberdate','$prime_admission'";

			if ($verify == 1) {
				$tabFields .= ", VERIFIED_ON";
				$insertVals .= ", NOW()";
			}
			$tabFields .= ")";
			$insertVals .= ")";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				/* -----Get the last insert ID ----- */
				$last_insert_id = parent::last_id();
				//if verified them change the username to center code
				//QRCODE	
				include('resources/phpqrcode/qrlib.php');
				$text = ATC_CERT_QRURL . 'verify_atc=1&code=' . $instcode;
				$path = 'resources/AtcDetailsQR/' . $last_insert_id . '/';
				if (!file_exists($path)) {
					@mkdir($path, 0777, true);
				}
				$file = $path . uniqid() . ".png";
				$ecc = 'L';
				$pixel_Size = 100;
				$frame_Size = 100;
				QRcode::png($text, $file, $ecc, $pixel_Size, $frame_size);

				$sqlQR = "UPDATE institute_details SET QRFILE = '$file' WHERE INSTITUTE_ID='$last_insert_id'";
				$exSqlQR = parent::execQuery($sqlQR);
				////////////////////////////////////////////////////////////

				if ($verify == 1)
					$uname = $email;
				$tableName2 	= "user_login_master";
				$tabFields2 	= "(USER_LOGIN_ID, USER_ID, USER_NAME, PASS_WORD,USER_ROLE, ACCOUNT_REGISTERED_ON,ACCOUNT_EXPIRED_ON,ACTIVE, CREATED_BY,CREATED_ON,CREATED_ON_IP)";
				$insertVals2	= "(NULL, '$last_insert_id', '$uname', MD5('$confpword'),'$role','$registrationdate','$expirationdate','$status','$created_by',NOW(), '$created_by_ip')";
				$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
				$exSql2			= parent::execQuery($insertSql2);

				$tableNameRA	= "referral_amount";
				$tabFieldsRA 	= "(id, inst_id, amount, active,delete_flag,created_by, created_at)";
				$insertValsRA	= "(NULL, '$last_insert_id', '0', '1','0','$created_by',NOW())";
				$insertSqlRA		= parent::insertData($tableNameRA, $tabFieldsRA, $insertValsRA);
				$exSqlRA			= parent::execQuery($insertSqlRA);

				$tableNameBI	= "background_images";
				$tabFieldsBI 	= "(id, inst_id, active,delete_flag,created_by, created_at)";
				$insertValsBI	= "(NULL, '$last_insert_id','1','0','$created_by',NOW())";
				$insertSqlBI		= parent::insertData($tableNameBI, $tabFieldsBI, $insertValsBI);
				$exSqlBI			= parent::execQuery($insertSqlBI);

				$tableNameWA	= "wallet";
				$tabFieldsWA 	= "(WALLET_ID, USER_ID, USER_ROLE,TOTAL_BALANCE,ACTIVE, DELETE_FLAG,CREATED_BY,CREATED_ON)";
				$insertValsWA	= "(NULL, '$last_insert_id','$role','0.00','1','0','$created_by',NOW())";
				$insertSqlWA		= parent::insertData($tableNameWA, $tabFieldsWA, $insertValsWA);
				$exSqlWA			= parent::execQuery($insertSqlWA);

				$tableNameMarquee	= "marquee_tags";
				$tabFieldsMarquee	= "(id, inst_id, active,delete_flag,created_by, created_at)";
				$insertValsMarquee	= "(NULL, '$last_insert_id','1','0','$created_by',NOW())";
				$insertSqlMarquee		= parent::insertData($tableNameMarquee, $tabFieldsMarquee, $insertValsMarquee);
				$exSqlMarquee			= parent::execQuery($insertSqlMarquee);


				if ($exSql2) {
					$courseImgPathDir 		= 	INSTITUTE_DOCUMENTS_PATH . '/' . $last_insert_id . '/';

					//$bucket_directory = 'institute/docs/'.$last_insert_id.'/'; 

					$tableName3 			= "institute_files";
					/* upload files */
					if ($instlogo != NULL) {
						$ext 			= pathinfo($_FILES["instlogo"]["name"], PATHINFO_EXTENSION);
						$file_name 		= INST_LOGO . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_LOGO . "','1',0,'$created_by',NOW(), '$created_by_ip')";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["instlogo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["instlogo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					if ($passphoto != NULL) {
						$ext 			= pathinfo($_FILES["passphoto"]["name"], PATHINFO_EXTENSION);
						$file_name 		= INST_OWNER_PHOTO . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_OWNER_PHOTO . "','1',0,'$created_by',NOW(), '$created_by_ip')";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["passphoto"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["passphoto"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					if ($photoidproof != NULL) {
						$ext 			= pathinfo($_FILES["photoidproof"]["name"], PATHINFO_EXTENSION);
						$file_name 		= INST_PHOTO_PROOF . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON, CREATED_ON_IP)";
						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_PHOTO_PROOF . "','1',0,'$created_by',NOW(), '$created_by_ip')";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["photoidproof"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["photoidproof"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					if ($instregcertificate != NULL) {
						$ext 			= pathinfo($_FILES["instregcertificate"]["name"], PATHINFO_EXTENSION);
						$file_name 		= INST_REG_CERTIFICATE . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_REG_CERTIFICATE . "','1',0,'$created_by',NOW(),'$created_by_ip')";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["instregcertificate"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["instregcertificate"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					if ($educationalproof != NULL) {
						$ext 			= pathinfo($_FILES["educationalproof"]["name"], PATHINFO_EXTENSION);
						$file_name 		= INST_EDU_DOCS . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_EDU_DOCS . "','1',0,'$created_by',NOW(), '$created_by_ip')";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["educationalproof"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["educationalproof"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					if ($profcourseproof != NULL) {
						$ext 			= pathinfo($_FILES["profcourseproof"]["name"], PATHINFO_EXTENSION);
						$file_name 		= INST_PROF_COURSE_DOCS . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON, CREATED_ON_IP)";
						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_PROF_COURSE_DOCS . "','1',0,'$created_by',NOW(),'$created_by_ip')";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["profcourseproof"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["profcourseproof"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					if ($instphotos != NULL) {
						while (list($key, $value) = each($_FILES["instphotos"]["name"])) {
							$cover_image		= $_FILES["instphotos"]["name"][$key];
							//if product record is not blank
							if ($cover_image != NULL) {
								$ext 			= pathinfo($_FILES["instphotos"]["name"][$key], PATHINFO_EXTENSION);
								$file_name 		= INST_OTHER_PHOTOS . '_' . mt_rand(0, 123456789) . '.' . $ext;
								$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
								$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_OTHER_PHOTOS . "','1',0,'$created_by',NOW(), '$created_by_ip')";
								$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
								$exec3   		= parent::execQuery($insertSql3);

								$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
								$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
								$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
								@mkdir($courseImgPathDir, 0777, true);
								//@mkdir($courseImgThumbPathDir,0777,true);								
								parent::create_thumb_img($_FILES["instphotos"]["tmp_name"][$key], $courseImgPathFile,  $ext, 800, 750);
								//parent::create_thumb_img($_FILES["instphotos"]["tmp_name"][$key], $courseImgThumbPathFile,  $ext, 300, 280);		
							}
						}
					}

					if ($instsign != NULL) {
						$ext 			= pathinfo($_FILES["instsign"]["name"], PATHINFO_EXTENSION);
						$file_name 		= INST_SIGN . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_SIGN . "','1',0,'$created_by',NOW(), '$created_by_ip')";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["instsign"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["instlogo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					if ($inststamp != NULL) {
						$ext 			= pathinfo($_FILES["inststamp"]["name"], PATHINFO_EXTENSION);
						$file_name 		= INST_STAMP . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_STAMP . "','1',0,'$created_by',NOW(), '$created_by_ip')";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["inststamp"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["instlogo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! New institute has been added successfully!';
					//send email
					//require_once("include/email/config.php");
					//require_once("include/email/templates/franchise_registration.php");

					//send sms
					//$message = "Your Application for DITRP Franchisee is received.\r\nPlease check your email for login details and upload required documents.\r\nDITRP \r\n".SUPPORT_NO;
					//parent::trigger_sms($message,$mobile);
				} else {
					parent::rollback();
					$data['message'] = 'Sorry! Something went wrong! Could not add the user.';
					$data['success'] = false;
					$data['errors']  = $errors;
				}
			}
		}
		return json_encode($data);
	}

	/* update institute 
	@param: 
	@return: json
	*/
	public function update_institute($inst_id)
	{
		//print_r($_POST); exit();
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data 

		$institute_login_id = parent::test(isset($_POST['institute_login_id']) ? $_POST['institute_login_id'] : NULL);
		$institute_id 	= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : NULL);
		$instcode 		= parent::test(isset($_POST['instcode']) ? $_POST['instcode'] : NULL);
		$instname 		= parent::test(isset($_POST['instname']) ? $_POST['instname'] : NULL);
		$instowner 		= parent::test(isset($_POST['instowner']) ? $_POST['instowner'] : NULL);
		$designation 		= parent::test(isset($_POST['designation']) ? $_POST['designation'] : NULL);
		$email 			= parent::test(isset($_POST['email']) ? $_POST['email'] : NULL);
		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : NULL);
		$address1 		= parent::test(isset($_POST['address1']) ? $_POST['address1'] : NULL);
		//$address2			= parent::test(isset($_POST['address2'])?$_POST['address2']:'');
		$state 			= parent::test(isset($_POST['state']) ? $_POST['state'] : NULL);
		$city 			= parent::test(isset($_POST['city']) ? $_POST['city'] : NULL);
		$country 			= parent::test(isset($_POST['country']) ? $_POST['country'] : 1);
		$postcode 		= parent::test(isset($_POST['postcode']) ? $_POST['postcode'] : NULL);
		$instdetails 		= parent::test(isset($_POST['instdetails']) ? $_POST['instdetails'] : NULL);
		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : NULL);
		$verify 			= parent::test(isset($_POST['verify']) ? $_POST['verify'] : NULL);
		$dob 		    	= parent::test(isset($_POST['dob']) ? $_POST['dob'] : NULL);
		$taluka 		    = parent::test(isset($_POST['taluka']) ? $_POST['taluka'] : NULL);

		$plan 			= parent::test(isset($_POST['plan']) ? $_POST['plan'] : NULL);

		$uname 			= parent::test(isset($_POST['uname']) ? $_POST['uname'] : NULL);
		$pword 			= parent::test(isset($_POST['pword']) ? $_POST['pword'] : NULL);
		$confpword 		= parent::test(isset($_POST['confpword']) ? $_POST['confpword'] : NULL);
		$expirationdate 	= parent::test(isset($_POST['expirationdate']) &&  parent::validateDate($_POST['expirationdate']) ? $_POST['expirationdate'] : NULL);
		$registrationdate = parent::test(isset($_POST['registrationdate'])  &&  parent::validateDate($_POST['registrationdate']) ? $_POST['registrationdate'] : NULL);
		$verifydate 		= parent::test(isset($_POST['verifydate']) &&  parent::validateDate($_POST['verifydate']) ? $_POST['verifydate'] : NULL);
		$no_of_comp 		= parent::test(isset($_POST['no_of_comp']) ? $_POST['no_of_comp'] : 'NULL');
		$no_of_staff 		= parent::test(isset($_POST['no_of_staff']) ? $_POST['no_of_staff'] : 'NULL');
		$creditcount 		= parent::test(isset($_POST['creditcount']) ? $_POST['creditcount'] : NULL);
		$democount 		= parent::test(isset($_POST['democount']) ? $_POST['democount'] : NULL);

		$gstno			= parent::test(isset($_POST['gstno']) ? $_POST['gstno'] : NULL);
		$supportpin		= parent::test(isset($_POST['supportpin']) ? $_POST['supportpin'] : NULL);

		$prime_member		= parent::test(isset($_POST['prime_member']) ? $_POST['prime_member'] : NULL);
		$prime_memberdate    = parent::test(isset($_POST['prime_memberdate']) ? $_POST['prime_memberdate'] : NULL);
		$prime_admission    = parent::test(isset($_POST['prime_admission']) ? $_POST['prime_admission'] : NULL);

		$remark		= parent::test(isset($_POST['remark']) ? $_POST['remark'] : NULL);


		$contact2		= parent::test(isset($_POST['contact2']) ? $_POST['contact2'] : NULL);
		$website		= parent::test(isset($_POST['website']) ? $_POST['website'] : NULL);
		$lastdate		= parent::test(isset($_POST['lastdate']) ? $_POST['lastdate'] : NULL);

		$lastdate = isset($_POST['lastdate']) && parent::validateDate($_POST['lastdate'])
			? parent::test($_POST['lastdate'])
			: NULL;


		// echo $lastdate;
		// die;
		$package_festival		= parent::test(isset($_POST['package_festival']) ? $_POST['package_festival'] : 0);

		$location		= parent::test(isset($_POST['location']) ? $_POST['location'] : NULL);

		$latitude		= parent::test(isset($_POST['latitude']) ? $_POST['latitude'] : NULL);
		$longitude		= parent::test(isset($_POST['longitude']) ? $_POST['longitude'] : NULL);

		$video_date		= parent::test(isset($_POST['video_date']) ? $_POST['video_date'] : NULL);
		$video_plan		= parent::test(isset($_POST['video_plan']) ? $_POST['video_plan'] : 0);


		/* Files */
		$instlogo 			= isset($_FILES['instlogo']['name']) ? $_FILES['instlogo']['name'] : NULL;
		$passphoto 		= isset($_FILES['passphoto']['name']) ? $_FILES['passphoto']['name'] : NULL;
		$photoidproof 		= isset($_FILES['photoidproof']['name']) ? $_FILES['photoidproof']['name'] : NULL;
		$instregcertificate = isset($_FILES['instregcertificate']['name']) ? $_FILES['instregcertificate']['name'] : NULL;
		$educationalproof 	= isset($_FILES['educationalproof']['name']) ? $_FILES['educationalproof']['name'] : NULL;
		$profcourseproof 	= isset($_FILES['profcourseproof']['name']) ? $_FILES['profcourseproof']['name'] : NULL;
		$instphotos 		= isset($_FILES['instphotos']['name']) ? $_FILES['instphotos']['name'] : NULL;

		$instsign 			= isset($_FILES['instsign']['name']) ? $_FILES['instsign']['name'] : NULL;
		$inststamp 		= isset($_FILES['inststamp']['name']) ? $_FILES['inststamp']['name'] : NULL;


		$admin_id 		= $_SESSION['user_id'];
		$role 			= 2; //institute staff;
		$updated_by  		= $_SESSION['user_fullname'];
		$created_by_ip  		= $_SESSION['ip_address'];
		$user_role  		= $_SESSION['user_role'];

		$masterpassword 		= parent::test(isset($_POST['masterpassword']) ? $_POST['masterpassword'] : NULL);

		/* check validations */
		if ($instname == NULL)
			$errors['instname'] = 'Institute name is required.';
		if ($email == NULL)
			$errors['email'] = 'Email is required.';
		if ($city == NULL)
			$errors['city'] = 'City is required.';
		if ($state == NULL)
			$errors['state'] = 'State is required.';
		if ($mobile == NULL)
			$errors['mobile'] = 'Mobile number is required.';
		if ($address1 == NULL)
			$errors['address1'] = 'Address is required.';

		if ($uname == NULL)
			$errors['uname'] = 'Username is required.';



		if ($pword != NULL && $confpword == NULL)
			$errors['confpword'] = 'Confirm Password is required.';

		if ($uname != NULL && $pword != NULL && $pword != $confpword)
			$errors['confpword'] = 'Confirm password doesnt match!.';

		if ($instowner != NULL && !preg_match("/^[a-zA-Z ]*$/", $instowner)) {
			$errors['instowner'] = "Only letters and white space allowed";
		}

		if ($email != NULL && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Invalid email format";
		}
		if ($mobile != NULL) {
			if (strlen($mobile) != 10) {
				$errors['mobile'] = 'Only 10 Digits allowed.';
			}
			$first_no = $mobile[0];
			/*$arr = array('9','8','7');
				if(!in_array($first_no,$arr))
				{
					$errors['mobile'] = 'Only letters and white space allowed. Mobile number should start with 9 or 8 or 7 only.'.$first_no;
				}*/
		}

		if (!parent::valid_username_onupdate($uname, $institute_login_id))
			$errors['uname'] = 'Sorry! Username is already used.';

		if (!parent::valid_institute_email($email, $institute_id))
			$errors['email'] = 'Sorry! Email is already used.';
		//  if(!parent::valid_institute_mobile($mobile,$institute_id))
		// 			$errors['mobile'] = 'Sorry! Mobile Number is already used.';

		if (!$this->validate_institute_code($instcode, $institute_id))
			$errors['instcode'] = 'Sorry! Institute code is already present.';
		if ($registrationdate != NULL)
			$registrationdate = date('Y-m-d', strtotime($registrationdate));
		if ($expirationdate != NULL)
			$expirationdate = date('Y-m-d', strtotime($expirationdate));


		if (!$verifydate)
			$verifydate = date('Y-m-d', strtotime($verifydate));

		/* files validations */

		if ($instlogo != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($instlogo, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['instlogo'] = 'Invalid file format! Please select valid file.';
			}
		}
		if ($passphoto != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($passphoto, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['passphoto'] = 'Invalid file format! Please select valid file.';
			}
		}

		if ($instsign != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($instsign, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['instsign'] = 'Invalid file format! Please select valid file.';
			}
		}
		if ($user_role != 2) {
			if ($plan == NULL)
				$errors['plan'] = 'Please Select Plan.';

			//         	if($masterpassword ==NULL){
			// 				$errors['masterpassword'] = 'Master password is required.';
			// 			}

			// 			if($masterpassword != 'Amzad@#$HDIPassword'){
			// 				$errors['masterpassword'] = 'Enter Correct Password.';
			// 			}
		}


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			if ($dob != NULL)
				$dob = @date('Y-m-d', strtotime($dob));
			parent::start_transaction();
			$tableName 	= "institute_details";
			$setValues 	= "INSTITUTE_CODE='$instcode', INSTITUTE_NAME=UPPER('$instname'), INSTITUTE_OWNER_NAME='$instowner', DOB='$dob', DESIGNATION='$designation',ADDRESS_LINE1='$address1', MOBILE='$mobile',EMAIL='$email',CITY='$city',STATE='$state',TALUKA='$taluka', POSTCODE='$postcode', DETAIL_DESCRIPTION='$instdetails',NO_OF_COMPUTERS='$no_of_comp',NO_OF_STAFF='$no_of_staff',PLAN_ID='$plan',GSTNO=UPPER('$gstno'),ACTIVE='$status', VERIFIED='$verify',VERIFIED_ON='$verifydate',UPDATED_BY='$updated_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip',SUPPORT_PIN='$supportpin',REMARK='$remark', CONTACT_NUMBER2='$contact2', WEBSITE='$website', FESTIVAL_PACKAGE='$package_festival' ,FESTIVAL_LAST_DATE=" . (empty($lastdate) ? "NULL" : "'$lastdate'") . ",LOCATION='$location',latitude='$latitude',longitude='$longitude',video_plan='$video_plan',video_date=" . (empty($video_date) ? "NULL" : "'$video_date'") . ",PRIMEMEMBER='$prime_member', PRIMEMEMBER_DATE=" . (empty($prime_memberdate) ? "NULL" : "'$prime_memberdate'") . ",NUMBER_OF_ADMISSION='$prime_admission'";
			$whereClause = " WHERE INSTITUTE_ID='$institute_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);

			// echo $updateSql;
			// die;

			$exSql	= parent::execQuery($updateSql);
			// if verified then change the username to center code
			//if($verify==1)
			//$uname = $instcode;
			$tableName2 	= "user_login_master";
			$setValues2 	= "USER_NAME='$uname', ACTIVE='$status', UPDATED_BY='$updated_by', UPDATED_ON=NOW(),UPDATED_ON_IP='$created_by_ip'";

			if (!empty($_POST['confpword'])) {
				if ($confpword == $pword)
					$setValues2 .= " , PASS_WORD= MD5('$confpword'), PASSWORD_CHANGE_DATE=NOW(), UPDATED_ON_IP='$created_by_ip'";
			}
			$whereClause2	= " WHERE USER_LOGIN_ID='$institute_login_id' AND USER_ID='$institute_id'";
			$updateSql2 = parent::updateData($tableName2, $setValues2, $whereClause2);
			$exSql2			= parent::execQuery($updateSql2);

			if ($plan != NULL) {
				$sqlPlan = "SELECT COURSE_ID,MULTI_SUB_COURSE_ID,TYPING_COURSE_ID,PLAN_ID FROM institute_courses WHERE INSTITUTE_ID ='$institute_id'";
				$exSqlPlan	= parent::execQuery($sqlPlan);
				if ($exSqlPlan && $exSqlPlan->num_rows > 0) {
					while ($dataPlan = $exSqlPlan->fetch_assoc()) {
						$COURSE_ID1              = $dataPlan['COURSE_ID'];
						$MULTI_SUB_COURSE_ID1    = $dataPlan['MULTI_SUB_COURSE_ID'];
						$TYPING_COURSE_ID1       = $dataPlan['TYPING_COURSE_ID'];
						$PLAN_ID1                = $dataPlan['PLAN_ID'];

						if ($plan != $PLAN_ID1) {
							if ($COURSE_ID1 != NULL && !empty($COURSE_ID1) && $COURSE_ID1 != 0) {
								$sqlPlanUpdate = "UPDATE institute_courses SET PLAN_ID= '$plan', PLAN_FEES = (SELECT COURSE_FEES FROM course_plan_fees WHERE COURSE_ID = $COURSE_ID1 AND PLAN_ID = $plan),EXAM_FEES =(SELECT COURSE_FEES FROM course_plan_fees WHERE COURSE_ID = $COURSE_ID1 AND PLAN_ID = $plan) WHERE INSTITUTE_ID='$institute_id' AND COURSE_ID='$COURSE_ID1'";
								$exSqlPlanUpdate = parent::execQuery($sqlPlanUpdate);
							}

							if ($MULTI_SUB_COURSE_ID1 != NULL && !empty($MULTI_SUB_COURSE_ID1) && $MULTI_SUB_COURSE_ID1 != 0) {
								$sqlPlanUpdate = "UPDATE institute_courses SET PLAN_ID= '$plan', PLAN_FEES = (SELECT COURSE_FEES FROM multi_sub_course_plan_fees WHERE MULTI_SUB_COURSE_ID = $MULTI_SUB_COURSE_ID1 AND PLAN_ID = $plan),EXAM_FEES=(SELECT COURSE_FEES FROM multi_sub_course_plan_fees WHERE MULTI_SUB_COURSE_ID = $MULTI_SUB_COURSE_ID1 AND PLAN_ID = $plan) WHERE INSTITUTE_ID='$institute_id' AND MULTI_SUB_COURSE_ID='$MULTI_SUB_COURSE_ID1'";
								$exSqlPlanUpdate = parent::execQuery($sqlPlanUpdate);
							}

							if ($TYPING_COURSE_ID1 != NULL && !empty($TYPING_COURSE_ID1) && $TYPING_COURSE_ID1 != 0) {
								$sqlPlanUpdate = "UPDATE institute_courses SET PLAN_ID= '$plan', PLAN_FEES = (SELECT COURSE_FEES FROM course_typing_plan_fees WHERE TYPING_COURSE_ID = $TYPING_COURSE_ID1 AND PLAN_ID = $plan),EXAM_FEES=(SELECT COURSE_FEES FROM course_typing_plan_fees WHERE TYPING_COURSE_ID = $TYPING_COURSE_ID1 AND PLAN_ID = $plan) WHERE INSTITUTE_ID='$institute_id' AND TYPING_COURSE_ID='$TYPING_COURSE_ID1'";
								$exSqlPlanUpdate = parent::execQuery($sqlPlanUpdate);
							}
						}
					}
				}
			}

			$courseImgPathDir 		= INSTITUTE_DOCUMENTS_PATH . '/' . $institute_id . '/';

			$tableName3 			= "institute_files";
			/* upload files */
			if ($instlogo != NULL) {
				$ext 			= pathinfo($_FILES["instlogo"]["name"], PATHINFO_EXTENSION);
				$file_name 		= INST_LOGO . '_' . mt_rand(0, 123456789) . '.' . $ext;

				$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
				$insertVals3	= "(NULL, '$institute_id', '$file_name','" . INST_LOGO . "','1',0,'$updated_by',NOW(),'$created_by_ip')";
				$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
				$exec3   		= parent::execQuery($insertSql3);

				$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
				$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
				$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
				@mkdir($courseImgPathDir, 0777, true);
				//@mkdir($courseImgThumbPathDir,0777,true);								
				parent::create_thumb_img($_FILES["instlogo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
				//parent::create_thumb_img($_FILES["instlogo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
			}
			if ($passphoto != NULL) {
				$ext 			= pathinfo($_FILES["passphoto"]["name"], PATHINFO_EXTENSION);
				$file_name 		= INST_OWNER_PHOTO . '_' . mt_rand(0, 123456789) . '.' . $ext;
				$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
				$insertVals3	= "(NULL, '$institute_id', '$file_name','" . INST_OWNER_PHOTO . "','1',0,'$updated_by',NOW(),'$created_by_ip')";
				$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
				$exec3   		= parent::execQuery($insertSql3);

				$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
				$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
				$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
				@mkdir($courseImgPathDir, 0777, true);
				//@mkdir($courseImgThumbPathDir,0777,true);								
				parent::create_thumb_img($_FILES["passphoto"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
				//parent::create_thumb_img($_FILES["passphoto"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
			}

			if ($instsign != NULL) {
				$ext 			= pathinfo($_FILES["instsign"]["name"], PATHINFO_EXTENSION);
				$file_name 		= INST_SIGN . '_' . mt_rand(0, 123456789) . '.' . $ext;
				$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
				$insertVals3	= "(NULL, '$institute_id', '$file_name','" . INST_SIGN . "','1',0,'$updated_by',NOW(), '$created_by_ip')";
				$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
				$exec3   		= parent::execQuery($insertSql3);

				$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
				$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
				$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
				@mkdir($courseImgPathDir, 0777, true);
				//@mkdir($courseImgThumbPathDir,0777,true);								
				parent::create_thumb_img($_FILES["instsign"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
				//parent::create_thumb_img($_FILES["instlogo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
			}

			//require_once(ROOT."/include/email/config.php");
			//require_once(ROOT."/include/email/templates/doc_upload_admin_to_franchise.php");
			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! Institute has been updated successfully!';
		}
		return json_encode($data);
	}
	public function update_institute_password()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data 

		$institute_login_id = parent::test(isset($_POST['institute_login_id']) ? $_POST['institute_login_id'] : NULL);
		$institute_id 	= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : NULL);
		$verify 			= parent::test(isset($_POST['verify']) ? $_POST['verify'] : NULL);
		$uname 			= parent::test(isset($_POST['uname']) ? $_POST['uname'] : NULL);
		$pword 			= parent::test(isset($_POST['pword']) ? $_POST['pword'] : NULL);
		$confpword 		= parent::test(isset($_POST['confpword']) ? $_POST['confpword'] : NULL);
		$admin_id 		= $_SESSION['user_id'];
		$role 			= 2; //institute staff;
		$updated_by  		= $_SESSION['user_fullname'];
		$created_by_ip  		= $_SESSION['ip_address'];
		$user_role  		= $_SESSION['user_role'];
		/* check validations */
		if ($pword != NULL && $confpword == NULL)
			$errors['confpword'] = 'Confirm Password is required.';

		if ($pword != NULL && $pword != $confpword)
			$errors['confpword'] = 'Confirm password doesnt match!.';
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			if ($confpword != NULL) {
				$tableName 	= "user_login_master";
				$setValues 	= "PASS_WORD= MD5('$confpword'), PASSWORD_CHANGE_DATE=NOW(), UPDATED_ON_IP='$created_by_ip'";
				$whereClause = "  WHERE USER_LOGIN_ID='$institute_login_id' AND USER_ID='$institute_id'";
				$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
				$exSql		= parent::execQuery($updateSql);
			}
			$data['success'] = true;
			$data['message'] = 'Success! Institute has been updated successfully!';
		}
		return json_encode($data);
	}
	public function list_institute($institute_id = NULL, $condition = NULL, $user_role = 8, $search = NULL)
	{

		$data = NULL;
		$sql = "SELECT A.*,DATE_FORMAT(A.VERIFIED_ON, '%Y-%m-%d') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.VERIFIED_ON, '%Y-%m-%d') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.DOB, '%Y-%m-%d') AS DOB_FORMATTED, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%Y-%m-%d') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%Y-%m-%d') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%i %p') AS UPDATED_DATE, B.USER_NAME, B.PASS_WORD ,B.USER_LOGIN_ID,(SELECT STATE_NAME FROM states_master WHERE STATE_ID=A.STATE) AS STATE_NAME  FROM institute_details A LEFT JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID WHERE A.DELETE_FLAG=0";
		if ($institute_id != NULL) {
			$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
		}
		if ($user_role != NULL) {
			$sql .= " AND B.USER_ROLE='$user_role' ";
		}
		if ($search != NULL) {
			$sql .= " AND (A.INSTITUTE_NAME LIKE '%$search%' 
						OR A.CITY LIKE '%$search%' 
						OR A.INSTITUTE_CODE LIKE '%$search%' 
						OR A.MOBILE LIKE '%$search%' 
						OR A.POSTCODE LIKE '%$search%' 
						OR A.AMC_CODE LIKE '%$search%' 
						OR A.EMAIL LIKE '%$search%' 
						OR B.USER_NAME LIKE '%$search%' 
						OR (SELECT STATE_NAME FROM states_master WHERE STATE_ID=A.STATE) LIKE '%$search%')";
		}
		if ($condition != NULL) {
			$sql .= " $condition ";
		}
		// $sql .= ' ORDER BY INSTITUTE_ID DESC';
		// echo $sql;
		// exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function get_role($user_id)
	{
		$sql = "SELECT USER_ROLE FROM user_login_master WHERE USER_ID='$user_id' LIMIT 1";
		$res = parent::execQuery($sql);
		// echo $sql;
		// exit();
		// Return null if no rows are found
		if ($res && $res->num_rows > 0) {
			return $res; // Return the result set for further processing
		}
		return null;
	}

	public function list_institute_festival($institute_id = NULL, $condition = NULL)
	{
		$data = NULL;
		$sql = "SELECT A.*,(SELECT CITY_NAME FROM city_master WHERE CITY_ID=A.CITY) AS CITY_NAME,(SELECT STATE_NAME FROM states_master WHERE STATE_ID=A.STATE) AS STATE_NAME FROM institute_details A WHERE A.DELETE_FLAG=0 ";

		if ($institute_id != NULL) {
			$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
		}
		if ($condition != NULL) {
			$sql .= " $condition ";
		}
		//	$sql .= ' ORDER BY CREATED_ON DESC';
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}
	public function get_institute_docs($institute_id = NULL, $condition = NULL)
	{
		$data = NULL;
		$sql = "SELECT * FROM institute_files WHERE 1";
		if ($institute_id != NULL)
			$sql .= " AND INSTITUTE_ID='$institute_id'";
		if ($condition != NULL)
			$sql .= " $condition";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}
	public function get_institute_docs_single($institute_id = NULL, $file_label = NULL)
	{
		$img = NULL;
		$data = array();
		$target = NULL;

		$sql = "SELECT * FROM institute_files WHERE 1";
		if ($institute_id != NULL)
			$sql .= " AND INSTITUTE_ID='$institute_id'";
		if ($file_label != NULL)
			$sql .= " AND FILE_LABEL='$file_label'";
		$sql .= ' ORDER BY FILE_ID ';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$rec = $res->fetch_assoc();
			$FILE_ID = $rec['FILE_ID'];
			$FILE_NAME = $rec['FILE_NAME'];
			$INSTITUTE_ID = $rec['INSTITUTE_ID'];

			$filePath = '../uploads/default_user.png';
			$fileLink = '../uploads/default_user.png';

			if ($FILE_NAME != NULL) {
				/*$filePath = INSTITUTE_DOCUMENTS_PATH.'/'.$INSTITUTE_ID.'/thumb/'.$FILE_NAME;
				$fileLink = INSTITUTE_DOCUMENTS_PATH.'/'.$INSTITUTE_ID.'/'.$FILE_NAME;*/

				$filePath = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/' . $FILE_NAME;
				$fileLink = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/' . $FILE_NAME;

				$dummy = '/default_user.png';


				$img .=  '<img src="' . $filePath . '" class="img img-responsive" style="height:50px; width:50px" />';
			} else {
				$img .=  '<img src="<?= $dummy ?>" class="img img-responsive" style="height:50px; width:50px" />';
			}
		}

		return $img;
	}

	public function get_institute_docs_all($institute_id = NULL, $file_label = NULL, $display = true)
	{
		$img = NULL;
		$data = array();
		$target = NULL;

		$sql = "SELECT FILE_ID,FILE_NAME,FILE_LABEL,INSTITUTE_ID FROM institute_files WHERE 1";
		if ($institute_id != NULL)
			$sql .= " AND INSTITUTE_ID='$institute_id'";
		if ($file_label != NULL)
			$sql .= " AND FILE_LABEL='$file_label'";
		$sql .= ' ORDER BY FILE_ID ';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($rec = $res->fetch_assoc()) {
				$FILE_ID 		= $rec['FILE_ID'];
				$FILE_NAME 		= $rec['FILE_NAME'];
				$INSTITUTE_ID 	= $rec['INSTITUTE_ID'];
				if ($FILE_NAME != NULL) {
					if (!$display) {
						$data1 = array("file_id" => $FILE_ID, "file_name" => $FILE_NAME, "institute_id" => $INSTITUTE_ID);
						array_push($data, $data1);
					} else {
						$filePath = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/' . $FILE_NAME;
						$fileLink = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/' . $FILE_NAME;
						$isVerified = parent::check_institute_verified($INSTITUTE_ID);
						$deleteBtn = NULL;
						if (!$isVerified)
							$deleteBtn = '<a href="javascript:void(0)" title= "Delete File" onclick="deleteInstitueFile(' . $FILE_ID . ',' . $INSTITUTE_ID . ')" class="btn btn-danger table-btn"><i class="mdi mdi-delete"></i></a>
												&nbsp;&nbsp;&nbsp;';
						$img .=  '<div id="file-area' . $FILE_ID . '">
											
												<img src="' . $filePath . '" class="img img-responsive" style="width:100px" />
												<br/>
													<a href="javascript:void(0)" title= "Delete File" onclick="deleteInstitueFile(' . $FILE_ID . ',' . $INSTITUTE_ID . ')" class="btn btn-danger table-btn"><i class="mdi mdi-delete"></i></a>
												
												<a class="btn btn-primary table-btn" href="' . $fileLink . '" target="_blank" title="View File"><i class="mdi mdi-eye"></i></a>
												<a href="' . $fileLink . '" target="_blank">
												</a>
											</div>';
					}
				}
			}
		}
		if (!$display)
			return $data;
		else return $img;
	}
	/* generate institute code */
	public function generate_institute_code()
	{
		$code = NULL;
		$code = parent::getRandomCode2();
		$sql = "SELECT INSTITUTE_CODE FROM institute_details WHERE INSTITUTE_CODE='$code'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_institute_code();
		}
		return $code;
	}
	/* validate institute code */
	public function validate_institute_code($code, $inst_id = NULL)
	{
		$sql = "SELECT INSTITUTE_CODE FROM institute_details WHERE INSTITUTE_CODE='$code'";
		if ($inst_id != NULL)
			$sql .= " AND INSTITUTE_ID!='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			return false;
		}
		return true;
	}
	/* validate institute code */
	public function delete_institue_file($file_id, $inst_id = NULL)
	{
		$sql = "DELETE FROM institute_files WHERE FILE_ID='$file_id'";
		if ($inst_id != NULL)
			$sql .= " AND INSTITUTE_ID='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	/* change institute name website visibility flag */
	public function changeVisiblityFlag($inst_id, $flag)
	{
		$sql = "UPDATE institute_details SET DISPLAY_ON_WEBSITE='$flag' WHERE INSTITUTE_ID='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			return true;
		}
		return false;
	}
	/* change institute name website visibility flag */
	public function changeStatusFlag($inst_id, $flag)
	{
		$sql = "UPDATE institute_details SET ACTIVE='$flag' WHERE INSTITUTE_ID='$inst_id'";
		$sql2 = "UPDATE user_login_master SET ACTIVE='$flag' WHERE USER_ID='$inst_id' AND USER_ROLE=2";
		$res = parent::execQuery($sql);
		$res2 = parent::execQuery($sql2);
		if ($res) {
			return true;
		}
		return false;
	}

	public function changeStatusFlagWebsite($inst_id, $flag)
	{
		$sql = "UPDATE institute_details SET SHOW_ON_WEBSITE='$flag' WHERE INSTITUTE_ID='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			return true;
		}
		return false;
	}

	/* change institute name website visibility flag */
	public function changeVerifyFlag($inst_id, $flag)
	{
		$date = @date('Y-m-d H:i:s');
		$sql = "UPDATE institute_details SET VERIFIED='$flag',VERIFIED_ON='$date' WHERE INSTITUTE_ID='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			// if($flag==1)
			// {
			// 	//send email			
			// 	require_once("../email/config.php");
			// 	require_once("../email/templates/franchise_registration_approved.php");
			// 	//send SMS
			// 	$mobile = parent::get_user_mobile($inst_id,2);
			// 	$message = "Congratulations!!!\r\nYour application for DITRP Authorisation is approved.\r\nPlease check your email.\r\nDITRP \r\n".SUPPORT_NO;
			// 	parent::trigger_sms($message,$mobile);
			// }
			return true;
		}
		return false;
	}
	/* change institute name website visibility flag */
	public function deleteInstitue($inst_id)
	{
		$sql = "UPDATE institute_details SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE INSTITUTE_ID='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			$sql = "UPDATE user_login_master SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE USER_ID='$inst_id' AND USER_ROLE=2";
			$res = parent::execQuery($sql);
			return true;
		}
		return false;
	}
	/* ------------------INSTITUTE LOGIN Section----------------------------------------- */
	//list institute staff
	public function list_institute_staff($staff_id = NULL, $institute_id = NULL, $cond = NULL)
	{
		$data = NULL;
		$sql = "SELECT A.*,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME ,get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,  DATE_FORMAT(A.STAFF_DOB, '%d-%m-%Y') AS STAFF_DOB_FORMATED, DATE_FORMAT(A.STAFF_DOJ, '%d-%m-%Y') AS STAFF_DOJ_FORMATED, B.USER_NAME, B.USER_LOGIN_ID, (SELECT C.CITY_NAME FROM city_master C WHERE C.CITY_ID=A.STAFF_CITY) AS STAFF_CITY_NAME,(SELECT D.STATE_NAME FROM states_master D WHERE D.STATE_ID=A.STAFF_STATE) AS STAFF_STATE_NAME  FROM institute_staff_details A LEFT JOIN user_login_master B ON A.STAFF_ID=B.USER_ID AND B.USER_ROLE=5 WHERE A.DELETE_FLAG=0 ";
		if ($staff_id != NULL) {
			$sql .= " AND A.STAFF_ID='$staff_id' ";
		}
		if ($institute_id != NULL) {
			$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
		}
		if ($cond != NULL) {
			$sql .= $cond;
		}
		$sql .= 'ORDER BY CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	/* add new staff in institute 
	@param: 
	@return: json
	*/
	public function add_institute_staff()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$action 		= isset($_POST['add_user']) ? $_POST['add_user'] : NULL;
		$fullname 	= parent::test(isset($_POST['fullname']) ? $_POST['fullname'] : NULL);
		$email 		= parent::test(isset($_POST['email']) ? $_POST['email'] : NULL);
		$mobile 		= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : NULL);
		$dob 			= parent::test(isset($_POST['dob']) ? $_POST['dob'] : NULL);
		$doj 			= parent::test(isset($_POST['doj']) ? $_POST['doj'] : NULL);
		$gender 		= parent::test(isset($_POST['gender']) ? $_POST['gender'] : NULL);
		$temp_add		= parent::test(isset($_POST['temp_add']) ? $_POST['temp_add'] : NULL);
		$per_add 		= parent::test(isset($_POST['per_add']) ? $_POST['per_add'] : NULL);
		$state 		= parent::test(isset($_POST['state']) ? $_POST['state'] : NULL);
		$city 		= parent::test(isset($_POST['city']) ? $_POST['city'] : NULL);
		$pincode 		= parent::test(isset($_POST['pincode']) ? $_POST['pincode'] : NULL);
		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : NULL);
		$designation 	= isset($_POST['designation']) ? $_POST['designation'] : NULL;
		$qualification = isset($_POST['qualification']) ? $_POST['qualification'] : NULL;
		$uname 		= isset($_POST['uname']) ? $_POST['uname'] : NULL;
		$pword 		= isset($_POST['pword']) ? $_POST['pword'] : NULL;
		$confpword 	= isset($_POST['confpword']) ? $_POST['confpword'] : NULL;
		$status 		= isset($_POST['status']) ? $_POST['status'] : NULL;
		$responsibilities	= isset($_POST['responsibilities']) ? $_POST['responsibilities'] : NULL;
		//incentive details
		$incentive_mode 		= isset($_POST['incentive_mode']) ? $_POST['incentive_mode'] : 'amount';
		$incentive_value 		= isset($_POST['incentive_value']) ? $_POST['incentive_value'] : 0;

		$staff_photo = isset($_FILES['staff_photo']['name']) ? $_FILES['staff_photo']['name'] : NULL;
		$staff_photo_id = isset($_FILES['staff_photo_id']['name']) ? $_FILES['staff_photo_id']['name'] : NULL;
		$institute_id = $_SESSION['user_id'];
		$role 		= 5; //institute staff;
		$created_by  	= $_SESSION['user_name'];

		/* check validations */
		if ($dob != NULL && $dob != '01-01-1970')
			$dob = date("Y-m-d", strtotime($dob));
		if ($doj != NULL && $doj != '01-01-1970')
			$doj = date("Y-m-d", strtotime($doj));
		if ($responsibilities == NULL || empty($responsibilities))
			$errors['responsibilities'] = 'Responsibilities is required!';
		if ($fullname == NULL)
			$errors['fullname'] = 'Fullname is required.';
		if ($fullname != NULL) {
			if (!preg_match("/^[a-zA-Z ]*$/", $fullname)) {
				$errors['fullname'] = "Only letters and white space allowed";
			}
		}
		if ($email == NULL)
			$errors['email'] = 'Email is required.';
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Invalid email format";
		}
		if ($mobile == NULL)
			$errors['mobile'] = 'Mobile number is required.';
		if ($mobile != NULL) {
			if (strlen($mobile) != 10)
				$errors['mobile'] = ' Please enter valid numbers,Only 10 Digits allowed.';
			if ($mobile <= 0)
				$errors['mobile'] = 'Please enter valid numbers.';
		}
		if ($temp_add != NULL) {
			if (preg_match("/[\'^�$%&*()}{@#~?><>,|=_+�-]/", $temp_add)) {
				$errors['temp_add'] = "Only letters and white space allowed. No special characters.";
			}
		}
		if ($per_add != NULL) {
			if (preg_match("/[\'^�$%&*()}{@#~?><>,|=_+-]/", $per_add)) {
				$errors['per_add'] = "Only letters and white space allowed. No special characters.";
			}
		}
		if ($pincode != NULL) {
			if (strlen($pincode) != 6)
				$errors['pincode'] = 'Postal code must be in number and 6 digits only.';
		}
		if ($designation != NULL) {
			if (!preg_match("/^[a-zA-Z ]*$/", $designation)) {
				$errors['designation'] = "Only letters and white space allowed";
			}
		}
		if ($qualification != NULL) {
			if (!preg_match("/^[a-zA-Z ]*$/", $qualification)) {
				$errors['qualification'] = "Only letters and white space allowed";
			}
		}
		if ($uname == NULL)
			$errors['uname'] = 'Username is required.';
		if ($pword == NULL)
			$errors['pword'] = 'Password is required.';
		if ($confpword == NULL)
			$errors['confpword'] = 'Confirm Password is required.';
		if ($pword != $confpword)
			$errors['confpword'] = 'Confirm password doesnt match!.';
		if (!parent::valid_username($uname))
			$errors['uname'] = 'Sorry! Username is already used.';
		if (!parent::valid_institute_staff_email($email, NULL))
			$errors['email'] = 'Sorry! Email is already used.';
		if ($staff_photo != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG');
			$extension = pathinfo($staff_photo, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['staff_photo'] = 'Please select valid image format file. Only jpg, gif, png or jpeg files.';
			}
		}
		if ($staff_photo_id != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG');
			$extension = pathinfo($staff_photo_id, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['staff_photo_id'] = 'Please select valid image format file. Only jpg, gif, png or jpeg files.';
			}
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
		} else {
			parent::start_transaction();
			$responsibilities = json_encode($responsibilities);
			$tableName 	= "institute_staff_details";
			$tabFields 	= "(STAFF_ID, INSTITUTE_ID, STAFF_FULLNAME, STAFF_GENDER,STAFF_DOB,STAFF_EMAIL,STAFF_MOBILE,STAFF_TEMP_ADDRESS,STAFF_PER_ADDRESS,STAFF_CITY,STAFF_STATE,STAFF_PINCODE,STAFF_EDUCATION,STAFF_DESIGNATION,STAFF_RESPONSIBILITIES,INCENTIVE_IN,INCENTIVE_VALUE,STAFF_DOJ,ACTIVE, CREATED_BY, CREATED_ON,CREATED_ON_IP)";
			$insertVals	= "(NULL, '$institute_id', UPPER('$fullname'), '$gender','$dob','$email','$mobile','$temp_add','$per_add','$city','$state','$pincode',UPPER('$qualification'),'$designation','$responsibilities','$incentive_mode','$incentive_value','$doj','$status','$created_by',NOW(), '" . $_SESSION['ip_address'] . "')";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				/* -----Get the last insert ID ----- */
				$last_insert_id = parent::last_id();
				if ($staff_photo != NULL) {
					$ext = pathinfo($_FILES["staff_photo"]["name"], PATHINFO_EXTENSION);
					$file_name = time() . NULL . mt_rand(0, 123456789) . '.' . $ext;

					$updProspSql 	= "UPDATE institute_staff_details SET STAFF_PHOTO='$file_name' WHERE  STAFF_ID='$last_insert_id'";
					$exec   	= parent::execQuery($updProspSql);

					$courseImgPathDir 		= 	INSTITUTE_STAFF_PHOTO_PATH . '/' . $last_insert_id . '/';

					//$bucket_directory = 'institute/staff/'.$last_insert_id.'/';

					/*$s3_obj = new S3Class();
                            $activityContent = $_FILES['staff_photo']['name'];
                            $fileTempName = $_FILES['staff_photo']['tmp_name'];
                            $new_width = 800;
                            $new_height = 750;
                            $image_p = imagecreatetruecolor($new_width, $new_height);
                            $image = imagecreatefromstring(file_get_contents($fileTempName));
                            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));
                            
                            $newFielName = tempnam(null,null); // take a llok at the tempnam and adjust parameters if needed
                            imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()
                            
                            $response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory.''.$file_name , S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["staff_photo"]["type"]));*/

					//var_dump($response);
					//exit();

					$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					//@mkdir($courseImgThumbPathDir,0777,true);

					parent::create_thumb_img($_FILES["staff_photo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					//parent::create_thumb_img($_FILES["staff_photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280) ;	

				}
				if ($staff_photo_id != NULL) {
					$ext = pathinfo($_FILES["staff_photo_id"]["name"], PATHINFO_EXTENSION);
					$file_name = time() . NULL . mt_rand(0, 123456789) . '.' . $ext;

					$updProspSql 	= "UPDATE institute_staff_details SET STAFF_PHOTO_ID='$file_name' WHERE  STAFF_ID='$last_insert_id'";
					$exec   	= parent::execQuery($updProspSql);

					/*$bucket_directory = 'institute/staff/'.$last_insert_id.'/';
							
							$s3_obj = new S3Class();
                            $activityContent = $_FILES['staff_photo_id']['name'];
                            $fileTempName = $_FILES['staff_photo_id']['tmp_name'];
                            $new_width = 800;
                            $new_height = 750;
                            $image_p = imagecreatetruecolor($new_width, $new_height);
                            $image = imagecreatefromstring(file_get_contents($fileTempName));
                            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));
                            
                            $newFielName = tempnam(null,null); // take a llok at the tempnam and adjust parameters if needed
                            imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()
                            
                            $response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory.''.$file_name , S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["staff_photo_id"]["type"]));*/

					//var_dump($response);
					//exit();

					$courseImgPathDir 		= 	INSTITUTE_STAFF_PHOTO_PATH . '/' . $last_insert_id . '/';
					$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					//@mkdir($courseImgThumbPathDir,0777,true);

					parent::create_thumb_img($_FILES["staff_photo_id"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					//parent::create_thumb_img($_FILES["staff_photo_id"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280) ;	

				}
				$tableName2 	= "user_login_master";
				$tabFields2 	= "(USER_LOGIN_ID, USER_ID, USER_NAME, PASS_WORD,USER_ROLE, ACTIVE, CREATED_BY,CREATED_ON)";
				$insertVals2	= "(NULL, '$last_insert_id', '$uname', MD5('$confpword'),'$role','$status','$created_by',NOW())";
				$insertSql2	= parent::insertData($tableName2, $tabFields2, $insertVals2);
				$exSql2		= parent::execQuery($insertSql2);
				if ($exSql2) {
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! New staff member has been added successfully!';
				} else {
					parent::rollback();
					$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
					$data['success'] = false;
					$data['errors']  = $errors;
				}
			}
		}
		return json_encode($data);
	}
	/* update staff in institute 
	@param: 
	@return: json
	*/
	public function update_institute_staff()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$action 		= isset($_POST['update_staff']) ? $_POST['update_staff'] : NULL;
		$login_id 	= parent::test(isset($_POST['login_id']) ? $_POST['login_id'] : NULL);
		$staff_id 	= parent::test(isset($_POST['staff_id']) ? $_POST['staff_id'] : NULL);
		$institute_id	= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : NULL);
		$fullname 	= parent::test(isset($_POST['fullname']) ? $_POST['fullname'] : NULL);
		$email 		= parent::test(isset($_POST['email']) ? $_POST['email'] : NULL);
		$mobile 		= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : NULL);
		$dob 			= parent::test(isset($_POST['dob']) ? $_POST['dob'] : NULL);
		$doj 			= parent::test(isset($_POST['doj']) ? $_POST['doj'] : NULL);
		$gender 		= parent::test(isset($_POST['gender']) ? $_POST['gender'] : NULL);
		$temp_add		= parent::test(isset($_POST['temp_add']) ? $_POST['temp_add'] : NULL);
		$per_add 		= parent::test(isset($_POST['per_add']) ? $_POST['per_add'] : NULL);
		$state 		= parent::test(isset($_POST['state']) ? $_POST['state'] : NULL);
		$city 		= parent::test(isset($_POST['city']) ? $_POST['city'] : NULL);
		$pincode 		= parent::test(isset($_POST['pincode']) ? $_POST['pincode'] : NULL);
		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : NULL);
		$designation 	= isset($_POST['designation']) ? $_POST['designation'] : NULL;
		$qualification = isset($_POST['qualification']) ? $_POST['qualification'] : NULL;
		$uname 		= isset($_POST['uname']) ? $_POST['uname'] : NULL;
		$pword 		= isset($_POST['pword']) ? $_POST['pword'] : NULL;
		$confpword 	= isset($_POST['confpword']) ? $_POST['confpword'] : NULL;
		$status 		= isset($_POST['status']) ? $_POST['status'] : NULL;
		$responsibilities	= isset($_POST['responsibilities']) ? $_POST['responsibilities'] : NULL;
		//incentive details
		$incentive_mode 		= isset($_POST['incentive_mode']) ? $_POST['incentive_mode'] : 'amount';
		$incentive_value 		= isset($_POST['incentive_value']) ? $_POST['incentive_value'] : 0;

		$staff_photo = isset($_FILES['staff_photo']['name']) ? $_FILES['staff_photo']['name'] : NULL;
		$staff_photo_id = isset($_FILES['staff_photo_id']['name']) ? $_FILES['staff_photo_id']['name'] : NULL;
		$institute_id = $_SESSION['user_id'];
		$role 		= 5; //institute staff;
		$updated_by  	= $_SESSION['user_name'];

		/* check validations */
		if ($dob != NULL && $dob != '01-01-1970')
			$dob = date("Y-m-d", strtotime($dob));
		if ($doj != NULL && $doj != '01-01-1970')
			$doj = date("Y-m-d", strtotime($doj));
		if ($fullname == NULL)
			$errors['fullname'] = 'Fullname is required.';
		if ($gender == NULL)
			$errors['gender'] = 'Gender is required.';
		if ($fullname != NULL) {
			if (!preg_match("/^[a-zA-Z ]*$/", $fullname)) {
				$errors['fullname'] = "Only letters and white space allowed";
			}
		}
		if ($temp_add != NULL) {
			if (preg_match("/[\'^$%&*()}{@#~?><>,|=_+-]/", $temp_add)) {
				$errors['temp_add'] = "Only letters and white space allowed. No special characters.";
			}
		}
		if ($per_add != NULL) {
			if (preg_match("/[\'^$%&*()}{@#~?><>,|=_+-]/", $per_add)) {
				$errors['per_add'] = "Only letters and white space allowed. No special characters.";
			}
		}
		if ($designation != NULL) {
			if (!preg_match("/^[a-zA-Z]*$/", $designation)) {
				$errors['designation'] = "Only letters and white space allowed";
			}
		}
		if ($qualification != NULL) {
			if (!preg_match("/^[a-zA-Z]*$/", $qualification)) {
				$errors['qualification'] = "Only letters and white space allowed";
			}
		}
		if ($email == NULL)
			$errors['email'] = 'Email is required.';
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Invalid email format";
		}
		if ($mobile == NULL)
			$errors['mobile'] = 'Mobile number is required.';
		if ($mobile != NULL) {
			if (strlen($mobile) != 10) {
				$errors['mobile'] = 'Only 10 Digits allowed.';
			}
			$first_no = $mobile[0]; //substr($mobile,1);
			$arr = array('9', '8', '7', '6', '5', '4', '3', '2', '1', '0');
			if (!in_array($first_no, $arr)) {
				$errors['mobile'] = 'Only numbers allowed. Mobile number should start with 9 or 8 or 7 only.';
			}
		}
		if ($pincode != NULL) {
			if (strlen($pincode) != 6)
				$errors['pincode'] = 'Postal code must be in number and 6 digits only.';
		}
		if ($uname == NULL)
			$errors['uname'] = 'Username is required.';
		/*		 
		 if ($pword=='')
			$errors['pword'] = 'Password is required.';
		  if ($confpword=='')
		     $errors['confpword'] = 'Confirm Password is required.';
		 */
		if ($pword != $confpword)
			$errors['confpword'] = 'Confirm password doesnt match!.';
		if (!parent::valid_username_onupdate($uname, $login_id))
			$errors['uname'] = 'Sorry! Username is already used.';
		if (!parent::valid_institute_staff_email($email, $staff_id))
			$errors['email'] = 'Sorry! Email is already used.';

		if ($staff_photo != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG');
			$extension = pathinfo($staff_photo, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['staff_photo'] = 'Please select only JPG format file';
			}
		}
		if ($staff_photo_id != NULL) {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG');
			$extension = pathinfo($staff_photo_id, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['staff_photo_id'] = 'Please select only JPG format file';
			}
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$responsibilities = json_encode($responsibilities);
			$tableName 	= "institute_staff_details";
			$setValues 	= "STAFF_FULLNAME=UPPER('$fullname'), STAFF_GENDER='$gender', STAFF_DOB='$dob',STAFF_EMAIL='$email',STAFF_MOBILE='$mobile', STAFF_TEMP_ADDRESS='$temp_add',STAFF_PER_ADDRESS='$per_add',STAFF_CITY='$city',STAFF_STATE='$state', STAFF_PINCODE='$pincode', STAFF_EDUCATION='$qualification',STAFF_DESIGNATION='$designation',STAFF_RESPONSIBILITIES='$responsibilities',STAFF_DOJ='$doj',INCENTIVE_IN='$incentive_mode',INCENTIVE_VALUE='$incentive_value', ACTIVE='$status', UPDATED_BY='$updated_by', UPDATED_ON=NOW(), UPDATED_ON_IP='" . $_SESSION['ip_address'] . "'";
			$whereClause = " WHERE STAFF_ID='$staff_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);

			$exSql		= parent::execQuery($updateSql);
			if (!empty($_POST['uname'])) {
				/* -----Get the last insert ID ----- */
				$last_insert_id = parent::last_id();
				$tableName2 	= "user_login_master";
				$setValues2 	= "USER_NAME='$uname', ACTIVE='$status', UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
				if (!empty($_POST['confpword'])) {
					if ($confpword == $pword)
						$setValues2 .= " , PASS_WORD= MD5('$confpword'), PASSWORD_CHANGE_DATE=NOW()";
				}
				$whereClause2	= " WHERE USER_LOGIN_ID='$login_id' AND USER_ID='$staff_id'";
				$updateSql2		= parent::updateData($tableName2, $setValues2, $whereClause2);
				$exSql2			= parent::execQuery($updateSql2);
				if ($exSql2) {
					if ($staff_photo != NULL) {
						$ext = pathinfo($_FILES["staff_photo"]["name"], PATHINFO_EXTENSION);
						$file_name = time() . NULL . mt_rand(0, 123456789) . '.' . $ext;

						$updProspSql 	= "UPDATE institute_staff_details SET STAFF_PHOTO='$file_name' WHERE  STAFF_ID='$staff_id'";
						$exec   	= parent::execQuery($updProspSql);

						/*$bucket_directory = 'institute/staff/'.$staff_id.'/';
                                                        
                                $s3_obj = new S3Class();
                                $activityContent = $_FILES['staff_photo']['name'];
                                $fileTempName = $_FILES['staff_photo']['tmp_name'];
                                $new_width = 800;
                                $new_height = 750;
                                $image_p = imagecreatetruecolor($new_width, $new_height);
                                $image = imagecreatefromstring(file_get_contents($fileTempName));
                                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));
                                
                                $newFielName = tempnam(null,null); // take a llok at the tempnam and adjust parameters if needed
                                imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()
                                
                                $response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory.''.$file_name , S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["staff_photo"]["type"]));*/

						//var_dump($response);
						//exit();


						$courseImgPathDir 		= 	INSTITUTE_STAFF_PHOTO_PATH . '/' . $staff_id . '/';
						$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);

						parent::create_thumb_img($_FILES["staff_photo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["staff_photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280) ;	

					}
					if ($staff_photo_id != NULL) {
						$ext = pathinfo($_FILES["staff_photo_id"]["name"], PATHINFO_EXTENSION);
						$file_name = time() . NULL . mt_rand(0, 123456789) . '.' . $ext;

						$updProspSql 	= "UPDATE institute_staff_details SET STAFF_PHOTO_ID='$file_name' WHERE  STAFF_ID='$staff_id'";
						$exec   	= parent::execQuery($updProspSql);

						/*$bucket_directory = 'institute/staff/'.$staff_id.'/';
                                                        
                                $s3_obj = new S3Class();
                                $activityContent = $_FILES['staff_photo_id']['name'];
                                $fileTempName = $_FILES['staff_photo_id']['tmp_name'];
                                $new_width = 800;
                                $new_height = 750;
                                $image_p = imagecreatetruecolor($new_width, $new_height);
                                $image = imagecreatefromstring(file_get_contents($fileTempName));
                                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));
                                
                                $newFielName = tempnam(null,null); // take a llok at the tempnam and adjust parameters if needed
                                imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()
                                
                                $response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory.''.$file_name , S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["staff_photo_id"]["type"]));*/

						//var_dump($response);
						//exit();

						$courseImgPathDir 		= 	INSTITUTE_STAFF_PHOTO_PATH . '/' . $staff_id . '/';
						$courseImgPathFile 		= 	$courseImgPathDir . NULL . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . NULL . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);

						parent::create_thumb_img($_FILES["staff_photo_id"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["staff_photo_id"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280) ;	

					}
					parent::commit();


					$data['success'] = true;
					$data['message'] = 'Success! Staff member has been updated successfully!';
				} else {
					parent::rollback();
					$errors['message'] = 'Sorry! Something went wrong! Could not update the user.';
					$data['success'] = false;
					$data['errors']  = $errors;
				}
			}
		}
		return json_encode($data);
	}
	/* change institute staff status */
	public function change_inst_staff_status($staff_id, $flag)
	{
		$sql = "UPDATE institute_staff_details SET ACTIVE='$flag', UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE STAFF_ID='$staff_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			$sql = "UPDATE user_login_master SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE USER_ID='$staff_id' AND USER_ROLE=5";
			$res = parent::execQuery($sql);
			return true;
		}
		return false;
	}
	/* change institute staff status */
	public function delete_inst_staff($staff_id)
	{
		$sql = "UPDATE institute_staff_details SET ACTIVE='0',DELETE_FLAG=1, UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE STAFF_ID='$staff_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			$sql = "UPDATE user_login_master SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE USER_ID='$staff_id' AND USER_ROLE=5";
			$res = parent::execQuery($sql);
			return true;
		}
		return false;
	}

	//student payments
	//add student payments
	// register new course for existing student
	public function add_student_payment()
	{
		$student_id 	= parent::test(isset($_POST['student_id']) ? $_POST['student_id'] : NULL);
		$staff_id 		= parent::test(isset($_POST['staff_id']) ? $_POST['staff_id'] : NULL);
		$institute_id 	= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : NULL);
		$course	 		= parent::test(isset($_POST['course']) ? $_POST['course'] : NULL);
		$amountpaid	 	= parent::test(isset($_POST['amountpaid']) ? $_POST['amountpaid'] : NULL);
		$paymentnote	= parent::test(isset($_POST['paymentnote']) ? $_POST['paymentnote'] : NULL);
		$paymentmode	= parent::test(isset($_POST['paymentmode']) ? $_POST['paymentmode'] : NULL);

		$amountbalance	= parent::test(isset($_POST['amountbalance']) ? $_POST['amountbalance'] : NULL);

		$fees_date	= parent::test(isset($_POST['fees_date']) ? $_POST['fees_date'] : NULL);

		$errors 		= array();  // array to hold validation errors
		$data 			= array();

		$created_by_id = $_SESSION['user_id'];

		if ($fees_date != NULL) {
			$fees_date = @date('Y-m-d', strtotime($fees_date));
		}

		if ($amountpaid < 0) {
			$errors['amountpaid'] = 'Invalid amount! Please enter the amount greater than zero.';
		}

		$requiredArr = array('course' => $course, 'student_id' => $student_id, 'amountpaid' => $amountpaid, 'paymentmode' => $paymentmode);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}
		if ($amountpaid != NULL && !parent::valid_decimal($amountpaid))
			$errors['amountpaid'] = 'Please enter valid amount. Should be positive integer only.';
		if ($amountpaid == 0 && $amountbalance == 0) {
			$errors['amountpaid'] = 'Course Fees Already Paid.';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			$receipt_no = date('d-m-Y') . '/' . $this->generate_student_receipt_no() . $student_id;
			parent::start_transaction();
			$tableName4 	= "student_payments";
			$tabFields4 	= "(PAYMENT_ID,RECIEPT_NO, STUDENT_ID,INSTITUTE_ID, INSTITUTE_COURSE_ID,STUD_COURSE_DETAIL_ID,COURSE_FEES,TOTAL_COURSE_FEES, FEES_PAID,FEES_BALANCE,FEES_PAID_DATE,FEES_PAYMENT_MODE,PAYMENT_NOTE,ACTIVE,CREATED_BY,CREATED_ON)";
			$insertVals4	= "(NULL, '$receipt_no','$student_id','$institute_id', '$course', (SELECT A.STUD_COURSE_DETAIL_ID FROM student_course_details A WHERE A.STUDENT_ID='$student_id' AND A.INSTITUTE_COURSE_ID='$course' LIMIT 0,1),get_stud_course_total_fees($student_id,$course),(SELECT B.TOTAL_COURSE_FEES FROM student_course_details B WHERE B.STUDENT_ID='$student_id' AND B.INSTITUTE_COURSE_ID='$course' LIMIT 0,1),'$amountpaid',student_calculate_balance_fees($student_id,$course, $amountpaid),'$fees_date','$paymentmode','$paymentnote','1','" . $_SESSION['user_fullname'] . "',NOW())";
			$insertSql4		= parent::insertData($tableName4, $tabFields4, $insertVals4);
			$exSql4			= parent::execQuery($insertSql4);
			$last_insert_id = parent::last_id();
			if ($exSql4) {
				//institute wallet
				$tableName91 	= " wallet";
				$setValuesInst 	= "TOTAL_BALANCE = TOTAL_BALANCE + $amountpaid, UPDATED_BY='$created_by', UPDATED_ON=NOW()";
				$whereClauseInst 	= "WHERE USER_ID='$institute_id' AND USER_ROLE = 2";
				echo $updSqlInst = parent::updateData($tableName91, $setValuesInst, $whereClauseInst);
				$exSqlInst 		= parent::execQuery($updSqlInst);

				if ($exSqlInst) {

					$trans_typeInst = 'CREDIT';
					$tableNameInst 	= "offline_payments";
					$tabFieldsInst	= "(PAYMENT_ID, TRANSACTION_TYPE,USER_ID,PAYMENT_AMOUNT,PAYMENT_REMARK,ACTIVE,CREATED_BY, CREATED_ON,STUDENT_ID)";
					$insertValsInst	= "(NULL,'$trans_typeInst','$institute_id','$amountpaid','Student Fees','1','$created_by',NOW(),'$student_id')";
					$insertSqlInst	= parent::insertData($tableNameInst, $tabFieldsInst, $insertValsInst);
					$exSqlInstPayment		= parent::execQuery($insertSqlInst);
				}

				//QRCODE
				include('resources/phpqrcode/qrlib.php');
				$text = STUDENT_PAYMENT_QRURL . 'verify_student=1&code=' . $receipt_no;
				$path = 'resources/studentFeesQR/' . $student_id . '/';
				if (!file_exists($path)) {
					@mkdir($path, 0777, true);
				}
				$file = $path . uniqid() . ".png";
				$ecc = 'L';
				$pixel_Size = 100;
				$frame_Size = 100;
				QRcode::png($text, $file, $ecc, $pixel_Size, $frame_size);
				////////////////////////////////////////////////////////////

				$sql1 = "UPDATE student_payments SET QRFILE = '$file' WHERE PAYMENT_ID='$last_insert_id'";
				$exSql1		=  parent::execQuery($sql1);


				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New payment has been added successfully!';
			}
		}
		return json_encode($data);
	}
	/* generate student receipt number */
	public function generate_student_receipt_no()
	{
		$code = NULL;
		$code = parent::getRandomCode3();
		$sql = "SELECT RECIEPT_NO FROM student_payments WHERE RECIEPT_NO ='$code'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_student_receipt_no();
		}
		return $code;
	}
	//update student payment
	public function update_student_payment()
	{
		$payment_id 	= parent::test(isset($_POST['payment_id']) ? $_POST['payment_id'] : NULL);
		$student_id 	= parent::test(isset($_POST['student_id']) ? $_POST['student_id'] : NULL);
		$staff_id 		= parent::test(isset($_POST['staff_id']) ? $_POST['staff_id'] : NULL);
		$institute_id 	= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : NULL);
		$course	 		= parent::test(isset($_POST['course']) ? $_POST['course'] : NULL);
		$amountpaid	 	= parent::test(isset($_POST['amountpaid']) ? $_POST['amountpaid'] : NULL);
		$amountbalance	 	= parent::test(isset($_POST['amountbalance']) ? $_POST['amountbalance'] : NULL);
		$paymentmode	= parent::test(isset($_POST['paymentmode']) ? $_POST['paymentmode'] : NULL);
		$paymentnote	= parent::test(isset($_POST['paymentnote']) ? $_POST['paymentnote'] : NULL);

		$fees_date	= parent::test(isset($_POST['fees_date']) ? $_POST['fees_date'] : NULL);

		if ($fees_date != NULL) {
			$fees_date = @date('Y-m-d', strtotime($fees_date));
		}


		$errors = array();  // array to hold validation errors
		$data = array();
		$updated_by = $_SESSION['user_fullname'];
		$updated_by_ip = $_SESSION['ip_address'];
		// 		if($amountpaid<=0)
		// 		{
		// 			$errors['amountpaid'] = 'Invalid amount! Please enter the amount greater than zero.';
		// 		}

		$requiredArr = array('course' => $course, 'student_id' => $student_id, 'amountpaid' => $amountpaid, 'paymentmode' => $paymentmode);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}
		if ($amountpaid != NULL && !parent::valid_decimal($amountpaid))
			$errors['amountpaid'] = 'Please enter valid amount. Should be positive integer only.';
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName	= "student_payments";
			$setValues 	= "STUDENT_ID='$student_id', 
									INSTITUTE_COURSE_ID='$course', 
									STUD_COURSE_DETAIL_ID=(SELECT A.STUD_COURSE_DETAIL_ID FROM student_course_details A WHERE A.STUDENT_ID='$student_id' AND A.INSTITUTE_COURSE_ID='$course'),
									COURSE_FEES=get_institute_course_fee_for_student($student_id, $course),										
									TOTAL_COURSE_FEES = get_stud_course_total_fees($student_id,$course),
									FEES_PAID = $amountpaid, 	
									FEES_BALANCE = $amountbalance,
									FEES_PAID_DATE='$fees_date',
									FEES_PAYMENT_MODE='$paymentmode',
									PAYMENT_NOTE='$paymentnote',
									ACTIVE=1,
									FEES_PAID_DATE=NOW(),
									CREATED_ON=NOW(),
									UPDATED_BY='$updated_by',
									UPDATED_ON=NOW()";
			$whereClause 	= " WHERE PAYMENT_ID='$payment_id'";

			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql4			= parent::execQuery($updateSql);
			if ($exSql4) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New payment has been added successfully!';
			}
		}
		return json_encode($data);
	}
	public function list_student_payments($payment_id = NULL, $student_id = NULL, $inst_id = NULL, $staff_id = NULL, $cond = NULL)
	{
		$res = NULL;

		$sql = "SELECT A.*,
				student_calculate_balance_fees2(A.STUD_COURSE_DETAIL_ID,0) AS FEES_BALANCE,
				get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME, 
				get_institute_staff_name(A.STAFF_ID) AS INSTITUTE_STAFF_NAME,
				DATE_FORMAT(A.FEES_PAID_DATE, '%d-%m-%Y %h:%m %p') as FEES_PAID_ON,
				get_student_name(A.STUDENT_ID) AS STUDENT_NAME,
				get_stud_course_fee_total_paid(A.STUDENT_ID,A.INSTITUTE_COURSE_ID) AS TOTAL_FEES_PAID				
				FROM student_payments A 
				WHERE A.DELETE_FLAG=0";

		if ($payment_id != NULL)
			$sql .= " AND A.PAYMENT_ID=$payment_id";
		if ($student_id != NULL)
			$sql .= " AND A.STUDENT_ID=$student_id";
		if ($inst_id != NULL)
			$sql .= " AND A.INSTITUTE_ID=$inst_id";
		//if($staff_id!='')
		//	$sql .= " AND A.STAFF_ID=$staff_id";
		if ($cond != NULL)
			$sql .= " $cond";

		$sql .=	" ORDER BY A.PAYMENT_ID DESC";
		$exec = parent::execQuery($sql);
		if ($exec && $exec->num_rows > 0)
			$res = $exec;
		return $res;
	}
	public function list_student_payments_upd($payment_id = NULL, $student_id = NULL, $inst_id = NULL, $staff_id = NULL, $cond = NULL)
	{
		$res = NULL;
		//	student_calculate_balance_fees2(A.STUD_COURSE_DETAIL_ID,0) AS FEES_BALANCE,
		//get_stud_course_fee_total_paid2(A.STUD_COURSE_DETAIL_ID) AS TOTAL_FEES_PAID		
		$sql = "SELECT A.*,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME, 
				get_institute_staff_name(A.STAFF_ID) AS INSTITUTE_STAFF_NAME,
				get_student_name(A.STUDENT_ID) AS STUDENT_NAME, SUM(FEES_PAID) as FEES_PAID
				FROM student_payments A 
				WHERE A.DELETE_FLAG=0";

		if ($payment_id != NULL)
			$sql .= " AND A.PAYMENT_ID=$payment_id";
		if ($student_id != NULL)
			$sql .= " AND A.STUDENT_ID=$student_id";
		if ($inst_id != NULL)
			$sql .= " AND A.INSTITUTE_ID=$inst_id";
		//if($staff_id!='')
		//	$sql .= " AND A.STAFF_ID=$staff_id";
		if ($cond != NULL)
			$sql .= " $cond";

		$sql .=	" GROUP BY A.STUDENT_ID, A.STUD_COURSE_DETAIL_ID ORDER BY A.PAYMENT_ID DESC";
		// echo $sql;
		$exec = parent::execQuery($sql);
		if ($exec && $exec->num_rows > 0)
			$res = $exec;
		return $res;
	}

	public function list_student_payments_upd_history($payment_id = NULL, $student_id = NULL, $inst_id = NULL, $staff_id = NULL, $cond = NULL)
	{
		$res = NULL;
		//	student_calculate_balance_fees2(A.STUD_COURSE_DETAIL_ID,0) AS FEES_BALANCE,
		//get_stud_course_fee_total_paid2(A.STUD_COURSE_DETAIL_ID) AS TOTAL_FEES_PAID		
		$sql = "SELECT A.*,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME, 
				get_institute_staff_name(A.STAFF_ID) AS INSTITUTE_STAFF_NAME,
				DATE_FORMAT(A.FEES_PAID_DATE, '%d-%m-%Y %h:%m %p') as FEES_PAID_ON,
				get_student_name(A.STUDENT_ID) AS STUDENT_NAME
				FROM student_payments A 
				WHERE A.DELETE_FLAG=0";

		if ($payment_id != NULL)
			$sql .= " AND A.PAYMENT_ID=$payment_id";
		if ($student_id != NULL)
			$sql .= " AND A.STUDENT_ID=$student_id";
		if ($inst_id != NULL)
			$sql .= " AND A.INSTITUTE_ID=$inst_id";
		//if($staff_id!='')
		//	$sql .= " AND A.STAFF_ID=$staff_id";
		if ($cond != NULL)
			$sql .= " $cond";

		$sql .=	" ORDER BY A.PAYMENT_ID DESC";
		// echo $sql;
		$exec = parent::execQuery($sql);
		if ($exec && $exec->num_rows > 0)
			$res = $exec;
		return $res;
	}

	public function view_student_payments_upd($payment_id = NULL, $student_id = NULL, $inst_id = NULL, $staff_id = NULL, $cond = NULL)
	{
		$res = NULL;
		//	student_calculate_balance_fees2(A.STUD_COURSE_DETAIL_ID,0) AS FEES_BALANCE,
		//get_stud_course_fee_total_paid2(A.STUD_COURSE_DETAIL_ID) AS TOTAL_FEES_PAID		
		$sql = "SELECT A.*,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME, 
				get_institute_staff_name(A.STAFF_ID) AS INSTITUTE_STAFF_NAME,
				DATE_FORMAT(A.FEES_PAID_DATE, '%d-%m-%Y %h:%m %p') as FEES_PAID_ON,
				get_student_name(A.STUDENT_ID) AS STUDENT_NAME
				FROM student_payments A 
				WHERE A.DELETE_FLAG=0";

		if ($payment_id != NULL)
			$sql .= " AND A.PAYMENT_ID=$payment_id";
		if ($student_id != NULL)
			$sql .= " AND A.STUDENT_ID=$student_id";
		if ($inst_id != NULL)
			$sql .= " AND A.INSTITUTE_ID=$inst_id";
		//if($staff_id!='')
		//	$sql .= " AND A.STAFF_ID=$staff_id";
		if ($cond != NULL)
			$sql .= " $cond";

		$sql .=	" ORDER BY A.PAYMENT_ID ASC";
		//echo $sql;
		$exec = parent::execQuery($sql);
		if ($exec && $exec->num_rows > 0)
			$res = $exec;
		return $res;
	}

	public function get_stud_course_payment_total($student_id = NULL, $course_id = NULL, $paid_amt = 0)
	{
		$res = NULL;
		//student_calculate_balance_fees2(A.STUD_COURSE_DETAIL_ID,$paid_amt) AS FEES_BALANCE,	
		//A.TOTAL_COURSE_FEES, SUM(A.FEES_PAID) AS FEES_PAID, 

		$sql = "SELECT A.*, A.INSTITUTE_COURSE_ID, get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME, 
				get_institute_staff_name(A.STAFF_ID) AS INSTITUTE_STAFF_NAME,
				DATE_FORMAT(A.FEES_PAID_DATE, '%d-%m-%Y') as FEES_PAID_ON,
				get_student_name(A.STUDENT_ID) AS STUDENT_NAME
				FROM student_payments A 
				WHERE DELETE_FLAG=0";
		if ($course_id != NULL)
			$sql .= " AND A.INSTITUTE_COURSE_ID=$course_id";
		if ($student_id != NULL)
			$sql .= " AND A.STUDENT_ID=$student_id";
		//	$sql .=	" GROUP BY A.INSTITUTE_COURSE_ID";
		$sql .=	" ORDER BY A.PAYMENT_ID DESC";
		//echo $sql; exit();
		$exec = parent::execQuery($sql);
		if ($exec && $exec->num_rows > 0)
			$res = $exec;
		return $res;
	}
	/* change institute staff status */
	public function delete_student_payment($payment_id)
	{
		$sql = "UPDATE student_payments SET ACTIVE='0',DELETE_FLAG=1, UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE PAYMENT_ID='$payment_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}
	public function get_stud_course_fee_detail($stud_id, $course_id, $amtpaid = 0)
	{
		$balance = array();
		$sql = "SELECT STUD_COURSE_DETAIL_ID,TOTAL_COURSE_FEES FROM student_course_details WHERE STUDENT_ID='$stud_id' AND INSTITUTE_COURSE_ID='$course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$sql2 = "SELECT (" . $data['TOTAL_COURSE_FEES'] . " - SUM(B.FEES_PAID)) AS TOTAL_BALANCE_FEES FROM student_payments B WHERE B.STUD_COURSE_DETAIL_ID='" . $data['STUD_COURSE_DETAIL_ID'] . "' AND B.DELETE_FLAG=0";
			$res2 = parent::execQuery($sql2);
			if ($res2 && $res2->num_rows > 0) {
				$data3 = $res2->fetch_assoc();
				$balance['total_course_fees'] = $data['TOTAL_COURSE_FEES'];
				$balance['fees_balance'] = $data3['TOTAL_BALANCE_FEES'];
			}
		}
		return $balance;
	}
	public function get_stud_course_fees($stuId, $courseId)
	{
		$course_fee = array();
		$sql = "SELECT TOTAL_COURSE_FEES FROM student_course_details WHERE STUDENT_ID='$stud_id' AND INSTITUTE_COURSE_ID='$course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$course_fee['total_course_fees'] = $data['TOTAL_COURSE_FEES'];
			$course_fee['fees_balance'] = $data['FEES_BALANCE'];
		}
		return $balance;
	}
	//get total payment recieved
	public function total_payments($inst_id = NULL, $cond = NULL)
	{
		$res = array();;
		$sql = "SELECT SUM(TOTAL_COURSE_FEES) AS ALL_COURSE_FEES, SUM(FEES_PAID) AS TOTAL_FEES_PAID, SUM(FEES_BALANCE) AS TOTAL_FEES_BALANCE FROM student_payments WHERE DELETE_FLAG=0";
		if ($inst_id != NULL) {
			$sql .= " AND INSTITUTE_ID='$inst_id'";
		}
		if ($cond != NULL) {
			$sql .= $cond;
		}
		$exc = parent::execQuery($sql);
		if ($exc && $exc->num_rows > 0) {
			while ($data = $exc->fetch_assoc()) {
				$res['ALL_COURSE_FEES'] = $data['ALL_COURSE_FEES'];
				$res['TOTAL_FEES_PAID'] = $data['TOTAL_FEES_PAID'];
				$res['TOTAL_FEES_BALANCE'] = $data['TOTAL_FEES_BALANCE'];
			}
		}
		return $res;
	}
	public function credit_details($inst_id)
	{
		$res = array();
		$sql = "SELECT CREDIT, CREDIT_USED FROM institute_details WHERE INSTITUTE_ID='$inst_id'";
		$exc = parent::execQuery($sql);
		if ($exc && $exc->num_rows > 0) {
			$data 		 		= $exc->fetch_assoc();
			$res['CREDIT'] 	 	= isset($data['CREDIT']) ? $data['CREDIT'] : 0;
			$res['CREDIT_USED'] = isset($data['CREDIT_USED']) ? $data['CREDIT_USED'] : 0;
		}
		return $res;
	}
	//Reports
	//staff incentives
	//list institute staff
	public function get_total_enq($inst_id, $staff_id)
	{
		$total = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM student_enquiry WHERE REGISTRATION=1 AND ENQUIRY_BY=$staff_id AND INSTITUTE_ID='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$total = $data['TOTAL'];
		}
		return $total;
	}
	public function getTotalWallet_amc($INSTITUTE_ID)
	{
		$data = 0;
		$sql = "SELECT SUM(TOTAL_BALANCE) AS TOTAL FROM wallet WHERE USER_ID='$INSTITUTE_ID' AND  DELETE_FLAG=0 ";

		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}

	public function get_total_admissions_amc($inst_id)
	{
		$total = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM student_enquiry WHERE REGISTRATION=1 AND INSTITUTE_ID='$inst_id'";
		//	echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$total = $data['TOTAL'];
		}
		return $total;
	}


	public function get_total_admissions($inst_id, $staff_id = NULL)
	{
		$total = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM student_enquiry WHERE REGISTRATION=1 AND ADMISSION_BY=$staff_id AND INSTITUTE_ID='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$total = $data['TOTAL'];
		}
		return $total;
	}
	public function get_total_incentive_on_admission($inst_id, $staff_id)
	{
		$result = array();
		$sql = "SELECT COUNT(A.STAFF_ID) AS TOTAL_COUNT,SUM(A.TOTAL_COURSE_FEES) AS TOTAL_FEES, B.INCENTIVE_IN, B.INCENTIVE_VALUE FROM student_course_details A  LEFT JOIN institute_staff_details B ON A.STAFF_ID=B.STAFF_ID WHERE A.STAFF_ID=$staff_id AND A.INSTITUTE_ID='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$TOTAL_COUNT = $data['TOTAL_COUNT'];
			$TOTAL_FEES = $data['TOTAL_FEES'];
			$INCENTIVE_IN = $data['INCENTIVE_IN'];
			$INCENTIVE_VALUE = $data['INCENTIVE_VALUE'];
			if ($INCENTIVE_IN == 'amount')
				$result['total_incentive'] = $INCENTIVE_VALUE * $TOTAL_COUNT;
			elseif ($INCENTIVE_IN = 'percentage')
				$result['total_incentive'] = ($TOTAL_FEES * $INCENTIVE_VALUE) / 100;

			$result['total_fees'] = $TOTAL_FEES;
		}
		return $result;
	}
	//list institute staff incentive
	public function list_institute_staff_incentive($staff_id = NULL, $institute_id = NULL, $cond = NULL)
	{
		$data = NULL;
		$sql = "SELECT A.*, DATE_FORMAT(A.STAFF_DOB, '%d-%m-%Y') AS STAFF_DOB_FORMATED, DATE_FORMAT(A.STAFF_DOJ, '%d-%m-%Y') AS STAFF_DOJ_FORMATED, B.USER_NAME, B.USER_LOGIN_ID FROM institute_staff_details A LEFT JOIN user_login_master B ON A.STAFF_ID=B.USER_ID AND B.USER_ROLE=5 WHERE A.DELETE_FLAG=0 ";

		//$sql = "SELECT A.* FROM student_enquiry A LEFT JOIN institute_staff_details B ON A."
		if ($staff_id != NULL) {
			$sql .= " AND A.STAFF_ID='$staff_id' ";
		}
		if ($institute_id != NULL) {
			$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
		}
		if ($cond != NULL) {
			$sql .= " $cond ";
		}
		$sql .= 'ORDER BY CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	public function get_inst_certificate($inst_id)
	{
		$result = "";
		$sql = "SELECT CERTIFICATE_FILE FROM institute_details WHERE INSTITUTE_ID='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['CERTIFICATE_FILE'];
		}
		return $result;
	}
	public function total_institute_payments($inst_id = NULL, $cond = NULL)
	{
		$res = array();
		$sql = "SELECT SUM(PAYMENT_AMOUNT) AS TOTAL_EXAM_FEES FROM offline_payments WHERE DELETE_FLAG=0 AND PAYMENT_REMARK='admission_confirmed'";
		if ($inst_id != NULL) {
			$sql .= " AND USER_ID='$inst_id' AND USER_ROLE=2";
		}
		if ($cond != NULL) {
			$sql .= $cond;
		}
		//echo $sql;
		$exc = parent::execQuery($sql);
		if ($exc && $exc->num_rows > 0) {
			while ($data = $exc->fetch_assoc()) {
				$res['TOTAL_EXAM_FEES'] = $data['TOTAL_EXAM_FEES'];
			}
		}
		return $res;
	}

	//AMC FUNCTION COMMISSION
	public function total_commission($userid)
	{
		$total = 0;
		$data = NULL;
		$sql = "SELECT (getamctotalcommision($userid,1,'OFFLINE')+getamctotalcommision($userid,0,'OFFLINE')+getamctotalcommision($userid,1,'ONLINE')+getamctotalcommision($userid,0,'ONLINE')) AS total";
		//	echo $sql;
		$res = parent::execQuery($sql);
		$data = $res->fetch_assoc();
		$total = $data['total'];
		return $total;
	}

	//Batches Section

	public function add_batch()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data
		extract($_POST);

		//$course_id	 	= parent::test(isset($course_id)?$course_id:'');		  
		$batch_name	 	= parent::test(isset($batch_name) ? $batch_name : NULL);
		$inst_id	 	= parent::test(isset($inst_id) ? $inst_id : NULL);
		// $end_date	 	= parent::test(isset($end_date)?$end_date:'');		  
		$timing	 		= parent::test(isset($timing) ? $timing : NULL);
		$numberofstudent	 		= parent::test(isset($numberofstudent) ? $numberofstudent : NULL);

		$role 			= $_SESSION['user_role'];
		$created_by  		= $_SESSION['user_fullname'];


		/* check validations */
		$requiredArr = array('batch_name' => $batch_name);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}

		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "course_batches";
			$tabFields 	= "(id ,inst_id,batch_name,timing,numberofstudent, active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL,'$inst_id', '$batch_name','$timing','$numberofstudent','1','0','$created_by',NOW())";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New batch has been added successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the batch.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function update_batch()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data
		extract($_POST);

		$id	 			= parent::test(isset($id) ? $id : NULL);
		//$course_id	 	= parent::test(isset($course_id)?$course_id:'');		  
		$batch_name	 	= parent::test(isset($batch_name) ? $batch_name : NULL);
		// $start_date	 	= parent::test(isset($start_date)?$start_date:'');		  
		// $end_date	 	= parent::test(isset($end_date)?$end_date:'');		  
		$timing	 		= parent::test(isset($timing) ? $timing : NULL);
		$numberofstudent	 		= parent::test(isset($numberofstudent) ? $numberofstudent : NULL);

		$role 			= $_SESSION['user_role'];
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */
		$requiredArr = array('batch_name' => $batch_name);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}

		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "course_batches";
			$setValues 	= "batch_name='$batch_name',timing='$timing',numberofstudent='$numberofstudent',updated_by='$updated_by', updated_at=NOW()";

			$whereClause = " WHERE id ='$id '";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);
			if ($exSql && parent::rows_affected() > 0) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Batch has been updated successfully!';
			} else {

				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not update the Batch.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	//list student from institute
	public function list_batch($id = NULL, $inst_id = NULL, $cond = NULL)
	{
		$data = NULL;
		$sql = "SELECT A.* FROM course_batches A WHERE A.delete_flag=0 ";
		if ($id != NULL) {
			$sql .= " AND A.id ='$id' ";
		}
		if ($inst_id != NULL) {
			$sql .= " AND A.inst_id ='$inst_id' ";
		}
		if ($cond != NULL) {
			$sql .= $cond;
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//delete student enquiry
	/* validate institute code */
	public function delete_batch($id = NULL)
	{
		$sql = "UPDATE course_batches SET delete_flag=1 WHERE id ='$id '";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}

	//list franchise enquiry
	public function list_franchise_enquiry($institute_id = NULL, $condition = NULL)
	{
		$data = NULL;
		$sql = "SELECT A.* ,(SELECT STATE_NAME FROM states_master WHERE STATE_ID=A.state) AS STATE_NAME FROM franchise_enquiry A WHERE A.delete_flag=0 ";

		if ($institute_id != NULL) {
			$sql .= " AND A.id='$institute_id' ";
		}
		if ($condition != NULL) {
			$sql .= " $condition ";
		}
		$sql .= ' ORDER BY created_at DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function update_franchise_enquiry($inst_id)
	{
		//print_r($_POST); exit();
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data 


		$institute_id 	= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : NULL);

		$instname 		= parent::test(isset($_POST['instname']) ? $_POST['instname'] : NULL);
		$instowner 		= parent::test(isset($_POST['instowner']) ? $_POST['instowner'] : NULL);

		$email 			= parent::test(isset($_POST['email']) ? $_POST['email'] : NULL);
		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : NULL);
		$address1 		= parent::test(isset($_POST['address1']) ? $_POST['address1'] : NULL);

		$state 			= parent::test(isset($_POST['state']) ? $_POST['state'] : NULL);
		$city 			= parent::test(isset($_POST['city']) ? $_POST['city'] : NULL);
		$country 			= parent::test(isset($_POST['country']) ? $_POST['country'] : 1);
		$postcode 		= parent::test(isset($_POST['postcode']) ? $_POST['postcode'] : NULL);

		$taluka 		    = parent::test(isset($_POST['taluka']) ? $_POST['taluka'] : NULL);
		$remark 		    = parent::test(isset($_POST['remark']) ? $_POST['remark'] : NULL);

		$admin_id 		= $_SESSION['user_id'];
		$role 			= 2; //institute staff;
		$updated_by  		= $_SESSION['user_fullname'];

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			$tableName 	= "franchise_enquiry";
			$setValues 	= "instname=UPPER('$instname'), owner_name='$instowner',address	='$address1', mobile_number='$mobile',emailid='$email',city='$city',state='$state',taluka='$taluka', pincode='$postcode',updated_by='$updated_by', updated_at=NOW(),remark='$remark'";
			$whereClause = " WHERE id ='$institute_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);

			$exSql	= parent::execQuery($updateSql);


			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! Franchise Enquiry has been updated successfully!';
		}
		return json_encode($data);
	}
	/* change institute name website visibility flag */
	public function delete_franchise_enquiry($inst_id)
	{
		echo $sql = "UPDATE franchise_enquiry SET delete_flag ='1', active = '0' WHERE id ='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			return true;
		}
		return false;
	}

	//list franchise enquiry
	public function list_services_enquiry($institute_id = NULL, $condition = NULL)
	{
		$data = NULL;
		$sql = "SELECT A.* ,(SELECT STATE_NAME FROM states_master WHERE STATE_ID=A.state) AS STATE_NAME,(SELECT name FROM our_services WHERE id =A.services_id) AS SERVICE_NAME FROM services_enquiry A WHERE A.delete_flag=0 ";

		if ($institute_id != NULL) {
			$sql .= " AND A.id='$institute_id' ";
		}
		if ($condition != NULL) {
			$sql .= " $condition ";
		}
		$sql .= ' ORDER BY created_at DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function list_institute_birthday($condition = NULL)
	{
		$data = NULL;
		$sql = "SELECT A.*,DATE_FORMAT(A.DOB, '%d-%m-%Y') AS DOB,DAY(A.DOB) AS DOB_DAY, MONTH(A.DOB) AS DOB_MONTH FROM institute_details A WHERE A.DELETE_FLAG=0 ";

		if ($condition != NULL) {
			$sql .= " $condition ";
		}
		$sql .= ' ORDER BY CREATED_ON DESC';
		//echo $sql; 
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
}
