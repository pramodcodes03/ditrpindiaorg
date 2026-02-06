<?php
include_once('database_results.class.php');
include_once('access.class.php');

class account extends access
{
	/* add new institute 
	@param: 
	@return: json
	*/

	public function add_institute_enquiry()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$instname 		= parent::test(isset($_POST['instname']) ? $_POST['instname'] : '');
		$instowner 		= parent::test(isset($_POST['instowner']) ? $_POST['instowner'] : '');

		$email 			= parent::test(isset($_POST['email']) ? $_POST['email'] : '');
		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$address1 		= parent::test(isset($_POST['address1']) ? $_POST['address1'] : '');
		$address2			= parent::test(isset($_POST['address2']) ? $_POST['address2'] : '');
		$state 			= parent::test(isset($_POST['state']) ? $_POST['state'] : '');
		$city 			= parent::test(isset($_POST['city']) ? $_POST['city'] : '');
		$country 			= parent::test(isset($_POST['country']) ? $_POST['country'] : 1);
		$postcode 		= parent::test(isset($_POST['postcode']) ? $_POST['postcode'] : '');

		$location 		= parent::test(isset($_POST['location']) ? $_POST['location'] : '');
		$latitude 		= parent::test(isset($_POST['latitude']) ? $_POST['latitude'] : '');
		$longitude 		= parent::test(isset($_POST['longitude']) ? $_POST['longitude'] : '');


		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		$taluka 	    	= parent::test(isset($_POST['taluka']) ? $_POST['taluka'] : 'NULL');

		// $admin_id 		= $_SESSION['user_id'];
		$role 			= 8; //franchise;
		$created_by  		= $instowner;;
		$created_by_ip  	= parent::get_client_ip();

		/* check validations */
		//new validations
		// if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] == $_SESSION['csrf_token']) {

		// 	$data['success'] = false;
		// 	$data['message']  = 'CSRF token validation failed.';
		// 	return json_encode($data);
		// }

		if ($instowner != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $instowner)) {
				$errors['instowner'] = "Only letters and white space allowed";
			}
		}

		if ($mobile != '') {
			if (strlen($mobile) != 10) {
				$errors['mobile'] = 'Only 10 Digits allowed.';
			}
			$first_no = $mobile[0]; //substr($mobile,1);
			$arr = array('9', '8', '7', '6', '5', '4', '3', '2', '1', '0');
			if (!in_array($first_no, $arr)) {
				$errors['mobile'] = 'Only letters and white space allowed. Mobile number should start with 9 or 8 or 7 only.';
			}
		}
		if ($postcode != '') {
			if (strlen($postcode) != 6)
				$errors['postcode'] = 'Postal code must be in number and 6 digits only.';
		}
		//new validations
		if ($instname == '')
			$errors['instname'] = 'Institute name is required.';

		if ($email == '')
			$errors['email'] = 'Email is required.';
		if ($city == '')
			$errors['city'] = 'City is required.';
		if ($state == '')
			$errors['state'] = 'State is required.';
		if ($mobile == '')
			$errors['mobile'] = 'Mobile number is required.';
		if ($address1 == '')
			$errors['address1'] = 'Address is required.';

		if (!parent::valid_institute_email($email, ''))
			$errors['email'] = 'Sorry! Email is already registered.';

		if (!parent::valid_institute_mobile($mobile, ''))
			$errors['mobile'] = 'Sorry! Mobile Number is already used.';

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "franchise_enquiry";
			$tabFields 	= "(id , instname, owner_name, emailid,mobile_number,address,taluka,pincode,state,city,country,active,delete_flag,created_by,created_at,LOCATION,latitude,longitude)";
			$insertVals	= "(NULL, UPPER('$instname'), UPPER('$instowner'),'$email','$mobile','$address1','$taluka','$postcode','$state','$city','$country','1','0','$created_by',NOW(),'$location','$latitude','$longitude')";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New institute has been added successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}


		return json_encode($data);
	}

	public function add_institute()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data
		$instcode 		= parent::test(isset($_POST['instcode']) ? $_POST['instcode'] : '');
		$instname 		= parent::test(isset($_POST['instname']) ? $_POST['instname'] : '');
		$instowner 		= parent::test(isset($_POST['instowner']) ? $_POST['instowner'] : '');
		$designation 		= parent::test(isset($_POST['designation']) ? $_POST['designation'] : '');
		$dob 				= parent::test(isset($_POST['dob']) ? $_POST['dob'] : '');
		$email 			= $_POST['email'] ?? "";
		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$address1 		= parent::test(isset($_POST['address1']) ? $_POST['address1'] : '');
		$address2			= parent::test(isset($_POST['address2']) ? $_POST['address2'] : '');
		$state 			= parent::test(isset($_POST['state']) ? $_POST['state'] : '');
		$city 			= parent::test(isset($_POST['city']) ? $_POST['city'] : '');
		$country 			= parent::test(isset($_POST['country']) ? $_POST['country'] : 1);
		$postcode 		= parent::test(isset($_POST['postcode']) ? $_POST['postcode'] : '');
		$instdetails 		= parent::test(isset($_POST['instdetails']) ? $_POST['instdetails'] : '');
		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$verify 			= parent::test(isset($_POST['verify']) ? $_POST['verify'] : 0);
		$taluka 	    	= parent::test(isset($_POST['taluka']) ? $_POST['taluka'] : 'NULL');
		$no_of_comp 		= parent::test(isset($_POST['no_of_comp']) ? $_POST['no_of_comp'] : 'NULL');
		$no_of_staff 		= parent::test(isset($_POST['no_of_staff']) ? $_POST['no_of_staff'] : 'NULL');

		$location 		= parent::test(isset($_POST['location']) ? $_POST['location'] : '');
		$latitude 		= parent::test(isset($_POST['latitude']) ? $_POST['latitude'] : '');
		$longitude 		= parent::test(isset($_POST['longitude']) ? $_POST['longitude'] : '');

		$uname 			= $email;
		$pword 			= parent::generate_password();
		$confpword 		= $pword;
		$expirationdate 	= parent::acc_expiry_date('');
		$registrationdate = parent::curr_date('');
		$creditcount 		= 100;
		$democount 		= 50;

		$amc_code 			= parent::test(isset($_POST['amc_code']) ? $_POST['amc_code'] : '');

		/* Files */
		$instlogo 			= isset($_FILES['instlogo']['name']) ? $_FILES['instlogo']['name'] : '';
		$passphoto 		= isset($_FILES['passphoto']['name']) ? $_FILES['passphoto']['name'] : '';
		$photoidproof 		= isset($_FILES['photoidproof']['name']) ? $_FILES['photoidproof']['name'] : '';
		$instregcertificate = isset($_FILES['instregcertificate']['name']) ? $_FILES['instregcertificate']['name'] : '';
		$educationalproof 	= isset($_FILES['educationalproof']['name']) ? $_FILES['educationalproof']['name'] : '';
		$profcourseproof 	= isset($_FILES['profcourseproof']['name']) ? $_FILES['profcourseproof']['name'] : '';
		$instphotos 		= isset($_FILES['instphotos']['name']) ? $_FILES['instphotos']['name'] : '';

		// $admin_id 		= $_SESSION['user_id'];
		$role 			= 8; //franchise;
		$created_by  		= $instowner;;
		$created_by_ip  	= parent::get_client_ip();

		/* check validations */
		//new validations
		// if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		// 	$errors['email'] = "Invalid email format";
		// }


		// if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] == $_SESSION['csrf_token']) {

		// 	$data['success'] = false;
		// 	$data['message']  = 'CSRF token validation failed.';
		// 	return json_encode($data);
		// }

		if ($instowner != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $instowner)) {
				$errors['instowner'] = "Only letters and white space allowed";
			}
		}

		if ($mobile != '') {
			if (strlen($mobile) != 10) {
				$errors['mobile'] = 'Only 10 Digits allowed.';
			}
			$first_no = $mobile[0]; //substr($mobile,1);
			$arr = array('9', '8', '7', '6', '5', '4', '3', '2', '1', '0');
			if (!in_array($first_no, $arr)) {
				$errors['mobile'] = 'Only letters and white space allowed. Mobile number should start with 9 or 8 or 7 only.';
			}
		}
		if ($postcode != '') {
			if (strlen($postcode) != 6)
				$errors['postcode'] = 'Postal code must be in number and 6 digits only.';
		}
		//new validations
		if ($instname == '')
			$errors['instname'] = 'Institute name is required.';
		if ($dob == '')
			$errors['dob'] = 'Date of Birth is required.';
		if ($email == '')
			$errors['email'] = 'Email is required.';
		if ($city == '')
			$errors['city'] = 'City is required.';
		if ($state == '')
			$errors['state'] = 'State is required.';
		if ($mobile == '')
			$errors['mobile'] = 'Mobile number is required.';
		if ($address1 == '')
			$errors['address1'] = 'Address is required.';
		if ($uname == '')
			$errors['uname'] = 'Username is required.';
		if ($pword == '')
			$errors['pword'] = 'Password is required.';
		if ($confpword == '')
			$errors['confpword'] = 'Confirm Password is required.';
		if ($pword != $confpword)
			$errors['confpword'] = 'Confirm password doesnt match!.';

		if ($expirationdate == '')
			$errors['expirationdate'] = 'Please enter account expiration date.';

		$state_code = parent::get_state_code($state);

		$curr_date = date('d-m-Y');

		$code = $this->generate_institute_code();

		$instcode = $state_code . '/' . $code;

		if (!parent::valid_username($uname))
			$errors['uname'] = 'Sorry! Username is already registered.';
		if (!parent::valid_institute_email($email, ''))
			$errors['email'] = 'Sorry! Email is already registered.';

		if (!parent::valid_institute_mobile($mobile, ''))
			$errors['mobile'] = 'Sorry! Mobile Number is already used.';

		if (!parent::validate_institute_code($instcode))
			$errors['instcode'] = 'Sorry! Institute code is already present.';
		if ($registrationdate != '')
			$registrationdate = date('Y-m-d', strtotime($registrationdate));
		if ($expirationdate != '')
			$expirationdate = date('Y-m-d', strtotime($expirationdate));


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			if ($dob != '')
				$dob = @date('Y-m-d', strtotime($dob));
			parent::start_transaction();
			$tableName 	= "institute_details";
			$tabFields 	= "(INSTITUTE_ID, INSTITUTE_CODE, INSTITUTE_NAME, INSTITUTE_OWNER_NAME,DOB,DESIGNATION,ADDRESS_LINE1,ADDRESS_LINE2,MOBILE,EMAIL,CITY,STATE,COUNTRY,TALUKA,POSTCODE,DETAIL_DESCRIPTION,NO_OF_COMPUTERS,NO_OF_STAFF,CREDIT,DEMO_PER,AMC_CODE,ACTIVE,VERIFIED, CREATED_BY, CREATED_ON, CREATED_ON_IP,LOCATION,latitude,longitude)";
			$insertVals	= "(NULL, UPPER('$instcode'), UPPER('$instname'), UPPER('$instowner'),'$dob','$designation','$address1','$address1','$mobile','$email','$city','$state','$country','$taluka','$postcode','$instdetails','$no_of_comp','$no_of_staff','$creditcount','$democount','$amc_code','1','0','$created_by',NOW(),'$created_by_ip','$location','$latitude','$longitude')";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				/* -----Get the last insert ID ----- */
				$last_insert_id = parent::last_id();
				//if verified them change the username to center code

				//QRCODE	
				include('admin/resources/phpqrcode/qrlib.php');
				$text = ATC_CERT_QRURL . 'verify_atc=1&code=' . $instcode;
				$path = 'admin/resources/AtcDetailsQR/' . $last_insert_id . '/';
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

				$tableName2 	= "user_login_master";
				$tabFields2 	= "(USER_LOGIN_ID, USER_ID, USER_NAME, PASS_WORD,USER_ROLE, ACCOUNT_REGISTERED_ON,ACCOUNT_EXPIRED_ON,ACTIVE, CREATED_BY,CREATED_ON,CREATED_ON_IP)";
				$insertVals2	= "(NULL, '$last_insert_id', '$uname', MD5('$confpword'),'$role','$registrationdate','$expirationdate','1','$created_by',NOW(), '$created_by_ip')";
				$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
				$exSql2			= parent::execQuery($insertSql2);

				if ($exSql2) {
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
					$insertValsWA	= "(NULL, '$last_insert_id','8','0.00','1','0','$created_by',NOW())";
					$insertSqlWA		= parent::insertData($tableNameWA, $tabFieldsWA, $insertValsWA);
					$exSqlWA			= parent::execQuery($insertSqlWA);

					$tableNameMarquee	= "marquee_tags";
					$tabFieldsMarquee	= "(id, inst_id, active,delete_flag,created_by, created_at)";
					$insertValsMarquee	= "(NULL, '$last_insert_id','1','0','$created_by',NOW())";
					$insertSqlMarquee		= parent::insertData($tableNameMarquee, $tabFieldsMarquee, $insertValsMarquee);
					$exSqlMarquee			= parent::execQuery($insertSqlMarquee);


					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! New institute has been added successfully!';
					//send email
					// require_once("phpmailer/PHPMailerAutoload.php");
					// require_once("email/config.php");
					// require_once("email/templates/franchise_registration.php");

					//send sms
					// $message = "Your Application for DITRP Franchisee is received.\r\nPlease check your email for login details and upload required documents.\r\nDITRP \r\n".SUPPORT_NO;
					// parent::trigger_sms($message,$mobile);

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


	public function list_institute($institute_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*,DATE_FORMAT(A.VERIFIED_ON, '%d-%m-%Y') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.VERIFIED_ON, '%d-%m-%Y') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.DOB, '%d-%m-%Y') AS DOB_FORMATTED, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%d-%m-%Y ') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%i %p') AS UPDATED_DATE, B.USER_NAME, B.USER_LOGIN_ID ,(SELECT STATE_NAME FROM states_master WHERE STATE_ID=A.STATE) AS STATE_NAME  FROM institute_details A LEFT JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID AND B.USER_ROLE=8 WHERE A.DELETE_FLAG=0 ";

		if ($institute_id != '') {
			$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= ' ORDER BY CREATED_ON ASC';
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function add_employer()
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$empcode 			= parent::test(isset($_POST['empcode']) ? $_POST['empcode'] : '');
		$empcmpname 		= parent::test(isset($_POST['empcmpname']) ? $_POST['empcmpname'] : '');
		$empname 			= parent::test(isset($_POST['empname']) ? $_POST['empname'] : '');
		$designation 		= parent::test(isset($_POST['designation']) ? $_POST['designation'] : '');
		$email 			= parent::test(isset($_POST['email']) ? $_POST['email'] : '');
		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$address1 		= parent::test(isset($_POST['address1']) ? $_POST['address1'] : '');
		$address2			= parent::test(isset($_POST['address2']) ? $_POST['address2'] : '');
		$state 			= parent::test(isset($_POST['state']) ? $_POST['state'] : '');
		$city 			= parent::test(isset($_POST['city']) ? $_POST['city'] : '');
		$country 			= parent::test(isset($_POST['country']) ? $_POST['country'] : 1);
		$postcode 		= parent::test(isset($_POST['postcode']) ? $_POST['postcode'] : '');
		$empdetails 		= parent::test(isset($_POST['empdetails']) ? $_POST['empdetails'] : '');

		$bankname 		    = parent::test(isset($_POST['bankname']) ? $_POST['bankname'] : '');
		$accountnumber 	    = parent::test(isset($_POST['accountnumber']) ? $_POST['accountnumber'] : '');
		$ifsc 		        = parent::test(isset($_POST['ifsc']) ? $_POST['ifsc'] : '');
		$accountholdername 	= parent::test(isset($_POST['accountholdername']) ? $_POST['accountholdername'] : '');



		$status 			= 1; //parent::test(isset($_POST['status'])?$_POST['status']:'');

		//  $admin_id 		= $_SESSION['user_id'];
		$role 			= 3; //employer;
		$created_by  		= $empname; //$_SESSION['user_fullname'];
		$created_by_ip  	= parent::get_client_ip();

		/* check validations */
		if ($empcmpname == '')
			$errors['empcmpname'] = 'Employer Company name is required.';

		if ($empname == '')
			$errors['empname'] = 'Employer name is required.';
		if ($empname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $empname)) {
				$errors['empname'] = "Only letters and white space allowed";
			}
		}

		if ($designation == '')
			$errors['designation'] = 'Employer Designation is required.';

		if ($email == '')
			$errors['email'] = 'Email is required.';
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Invalid email format";
		}
		if ($city == '')
			$errors['city'] = 'City is required.';
		if ($state == '')
			$errors['state'] = 'State is required.';
		if ($mobile == '')
			$errors['mobile'] = 'Mobile number is required.';

		if ($mobile != '') {
			if (strlen($mobile) != 10) {
				$errors['mobile'] = 'Only 10 Digits allowed.';
			}
			$first_no = $mobile[0];
			$arr = array('9', '8', '7', '6', '5', '4', '3', '2', '1', '0');
			if (!in_array($first_no, $arr)) {
				$errors['mobile'] = 'Only letters and white space allowed. Mobile number should start with 9 or 8 or 7 only.' . $first_no;
			}
		}
		if ($postcode == '')
			$errors['postcode'] = 'postal code is required.';
		if ($postcode != '') {
			if (strlen($postcode) != 6) {
				$errors['postcode'] = 'postal code is required only 6 Digits.';
			}
			if ($postcode < 0) {
				$errors['postcode'] = 'Please enter valid postal code .';
			}
		}
		if ($address1 == '')
			$errors['address1'] = 'Address is required.';


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "employer_details";
			$tabFields 	= "(EMPLOYER_ID, EMPLOYER_CODE, EMPLOYER_COMPANY_NAME,EMPLOYER_NAME,DESIGNATION,ADDRESS_LINE1,ADDRESS_LINE2,MOBILE,EMAIL,CITY,STATE,COUNTRY,POSTCODE,DETAIL_DESCRIPTION,BANK_NAME,ACCOUNT_NO,IFSC,ACCHOLDERNAME,ACTIVE, CREATED_BY, CREATED_ON,CREATED_ON_IP)";
			$insertVals	= "(NULL, UPPER('$empcode'), UPPER('$empcmpname'), UPPER('$empname'),'$designation','$address1','$address2','$mobile','$email','$city','$state','$country','$postcode','$empdetails','$bankname','$accountnumber','$ifsc','$accountholdername','$status','$created_by',NOW(), '$created_by_ip')";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New AMC has been register successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the amc.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}


	public function list_added_aicpe_courses_single($inst_course_id)
	{
		$data = '';
		$sql = "SELECT A.*,A.COURSE_FEES as AICPE_EXAM_FEES,get_aicpe_course_award_name(A.COURSE_AWARD) AS COURSE_AWARD_NAME, B.INSTITUTE_COURSE_ID, B.ACTIVE AS STATUS, B.COURSE_FEES AS INSTITUTE_COURSE_FEES FROM  aicpe_courses A LEFT JOIN institute_courses B ON A.COURSE_ID=B.COURSE_ID  WHERE B.DELETE_FLAG=0  AND B.COURSE_TYPE=1 AND B.INSTITUTE_COURSE_ID='$inst_course_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}
	public function student_register()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$institute_id		= DEFAULT_INSTITUTE_ID;
		$staff_id			= DEFAULT_INSTITUTE_STAFF_ID;

		$studcode 		= $this->generate_student_code($institute_id);
		if (!$this->validate_student_code($studcode, ''))
			$studcode 	= $this->generate_student_code($institute_id);

		$fname	 		= strtoupper(parent::test(isset($_POST['fname']) ? $_POST['fname'] : ''));
		$mname	 		= strtoupper(parent::test(isset($_POST['mname']) ? $_POST['mname'] : ''));
		$lname	 		= strtoupper(parent::test(isset($_POST['lname']) ? $_POST['lname'] : ''));

		$course 			= isset($_POST['course']) ? $_POST['course'] : '';
		$coupon_code		= parent::test(isset($_POST['coupon_code']) ? strtoupper($_POST['coupon_code']) : '');
		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$email 			= parent::test(isset($_POST['email']) ? $_POST['email'] : '');
		$dob		 		= parent::test(isset($_POST['dob']) ? $_POST['dob'] : '');
		$gender	 		= parent::test(isset($_POST['gender']) ? $_POST['gender'] : '');
		$adharid	 		= parent::test(isset($_POST['adharid']) ? $_POST['adharid'] : '');
		$qualification	= parent::test(isset($_POST['qualification']) ? $_POST['qualification'] : '');
		$occupation		= parent::test(isset($_POST['occupation']) ? $_POST['occupation'] : '');

		$per_add			= parent::test(isset($_POST['per_add']) ? $_POST['per_add'] : '');
		$state 			= parent::test(isset($_POST['state']) ? $_POST['state'] : '');
		$city 			= parent::test(isset($_POST['city']) ? $_POST['city'] : '');
		$postcode 		= parent::test(isset($_POST['postcode']) ? $_POST['postcode'] : '');
		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : 1);
		$mobile2 			= parent::test(isset($_POST['mobile2']) ? $_POST['mobile2'] : '');

		$adharid			= isset($adharid) ? $adharid : '';
		$qualification	= isset($qualification) ? $qualification : '';
		$occupation		= isset($occupation) ? $occupation : '';
		$interested_course = isset($interested_course) ? $interested_course : '';
		$reason_for_course = parent::test(isset($reason_for_course) ? $reason_for_course : '');
		$daily_invest_time = parent::test(isset($daily_invest_time) ? $daily_invest_time : '');
		$todo_future		= parent::test(isset($todo_future) ? $todo_future : '');
		$how_know_us		= parent::test(isset($how_know_us) ? $how_know_us : '');
		$expectations		= parent::test(isset($expectations) ? $expectations : '');
		$news_letter		= parent::test(isset($news_letter) ? $news_letter : '');
		$enquiry_status	= parent::test(isset($enquiry_status) ? $enquiry_status : '');
		$enquiry_by		= parent::test(isset($enquiry_by) ? $enquiry_by : '');
		$remarks			= parent::test(isset($remarks) ? $remarks : '');

		$uname 			= $studcode;
		$confpword 		= parent::generate_password();
		$role 				= 4;
		$created_by_id  	= DEFAULT_INSTITUTE_ID;
		$created_by  		= $email;

		$created_by_ip  	= parent::get_client_ip();
		$stud_course_id = array();
		/* check validations */
		//required validations 
		$requiredArr = array('dob' => $dob, 'fname' => $fname, 'lname' => $lname, 'mobile' => $mobile, 'email' => $email, 'gender' => $gender, 'per_add' => $per_add, 'course' => $course, 'state' => $state, 'city' => $city, 'postcode' => $postcode);
		if ($dob == '') $errors['dob'] = "Date of birth required!";
		if ($fname == '') $errors['fname'] = "First name is required!";
		if ($lname == '') $errors['lname'] = "Last name required!";
		if ($mobile == '') $errors['mobile'] = "Mobile number required!";
		if ($email == '') $errors['email'] = "Email address required!";
		if ($gender == '') $errors['gender'] = "Gender is required!";
		if ($per_add == '') $errors['per_add'] = "Address is required!";
		if ($course == '') $errors['course'] = "Course is required!";
		if ($state == '') $errors['state'] = "State is required!";
		if ($city == '') $errors['city'] = "City is required!";
		if ($postcode == '') $errors['postcode'] = "Postcode is required!";
		/* $checkRequired = parent::valid_required($requiredArr);
		if(!empty($checkRequired)){
			foreach($checkRequired as $value)
				$errors[$value] = $value.': Required field!';
		}
		 */

		// validate strings
		$stringArr = array('fname' => $fname, 'mname' => $mname, 'lname' => $lname);
		$checkString = parent::valid_string($stringArr);
		if (!empty($checkString)) {
			foreach ($checkString as $value)
				$errors[$value] = 'Only letters and white space allowed!';
		}

		if (!parent::valid_username($uname))
			$errors['uname'] = 'Sorry! Username is already used.' . $uname;



		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Invalid email format";
		}

		if ($mobile != '') {
			$valid_mob = parent::valid_mobile($mobile);
			if ($valid_mob != '') $errors['mobile'] = $valid_mob;
		}

		if ($postcode != '') {
			if (strlen($postcode) != 6)
				$errors['postcode'] = 'Postal code must be in number and 6 digits only.';
			if (!preg_match("/^[a-zA-Z0-9 ]*$/", $postcode)) {
				$errors['postcode'] = "Only letters and white space allowed";
			}
		}
		if ($dob != '')
			$dob = @date('Y-m-d', strtotime($dob));

		// $course_fee =  parent::get_inst_course_fees($course);
		if ($coupon_code != '') {
			$coupon_code_check = json_decode(parent::apply_coupon_code($coupon_code, $course), true);
			$success = isset($coupon_code_check['success']) ? $coupon_code_check['success'] : '';
			if ($success) {
				$amount = isset($coupon_code_check['amount']) ? $coupon_code_check['amount'] : '';
			}
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "student_enquiry";
			$tabFields 	= "(ENQUIRY_ID,INSTITUTE_ID, STAFF_ID, STUDENT_FNAME,STUDENT_MNAME,STUDENT_LNAME, STUDENT_DOB,STUDENT_GENDER,STUDENT_MOBILE,STUDENT_MOBILE2,STUDENT_EMAIL,STUDENT_PER_ADD,STUDENT_STATE,STUDENT_CITY,STUDENT_PINCODE,STUDENT_ADHAR_NUMBER,EDUCATIONAL_QUALIFICATION,OCCUPATION,INSTRESTED_COURSE,REASON_FOR_COURSE,DAILY_INVEST_TIME,LIKE_TODO_FUTURE,HOW_KNOW_US,EXPECTATIONS,REMARK,ENQUIRY_STATUS,NEWS_LETTER,ENQUIRY_BY, CREATED_BY, CREATED_ON, CREATED_ON_IP)";
			$insertVals	= "(NULL, '$institute_id','$staff_id', '$fname','$mname','$lname','$dob','$gender','$mobile','$mobile2','$email','$per_add','$state','$city','$postcode','$adharid','$qualification','$occupation','$interested_course','$reason_for_course','$daily_invest_time','$todo_future','$how_know_us','$expectations','$remarks','$enquiry_status','$news_letter','$created_by_id','$created_by',NOW(),'$created_by_ip')";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				//get enquiry id
				$enquiry_id 		= parent::last_id();
				$tableName2 	= "student_details";
				$tabFields2 	= "(STUDENT_ID,INSTITUTE_ID, STAFF_ID, STUDENT_CODE, STUDENT_FNAME,STUDENT_MNAME,STUDENT_LNAME, STUDENT_DOB,STUDENT_GENDER,STUDENT_MOBILE,STUDENT_MOBILE2,STUDENT_EMAIL,STUDENT_PER_ADD,STUDENT_STATE,STUDENT_CITY,STUDENT_PINCODE,STUDENT_ADHAR_NUMBER,EDUCATIONAL_QUALIFICATION,OCCUPATION,ENQUIRY_ID,ACTIVE, CREATED_BY, CREATED_ON, CREATED_ON_IP)";

				$insertVals2	= "(NULL, '$institute_id','$staff_id','$studcode', '$fname','$mname','$lname','$dob','$gender','$mobile','$mobile2','$email','$per_add','$state','$city','$postcode','$adharid','$qualification','$occupation','$enquiry_id','$status','$created_by',NOW(),'$created_by_ip')";

				$insertSql2	= parent::insertData($tableName2, $tabFields2, $insertVals2);
				$exSql2		= parent::execQuery($insertSql2);
				if ($exSql2) {
					/* -----Get the student ID ----- */
					$student_id 		= parent::last_id();

					// student login details
					$tableName4 	= "user_login_master";
					$tabFields4 	= "(USER_LOGIN_ID, USER_ID, USER_NAME, PASS_WORD,USER_ROLE, ACCOUNT_REGISTERED_ON,ACTIVE, CREATED_BY,CREATED_ON,CREATED_ON_IP)";
					$insertVals4	= "(NULL, '$student_id', '$uname', MD5('$confpword'),'$role',NOW(),'$status','$created_by',NOW(), '$created_by_ip')";
					$insertSql4		= parent::insertData($tableName4, $tabFields4, $insertVals4);
					$exSql4			= parent::execQuery($insertSql4);
					if ($exSql4) {
						$data['username'] = $uname;
						$data['password'] = $confpword;
						$sql = "UPDATE student_enquiry SET REGISTRATION=1, ADMISSION_BY='$created_by_id' WHERE ENQUIRY_ID='$enquiry_id'";
						parent::execQuery($sql);
						parent::commit();
					}

					//send email
					//require_once(ROOT."/include/email/config.php");						
					//require_once(ROOT."/include/email/templates/student_admission_success.php");

					$data['success'] = true;
					$data['message'] = 'Success! Your registration was successfull!';
				}
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the student.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function generate_student_code($inst_id = '')
	{
		$code = '';
		$sql = "SELECT generate_student_code($inst_id) AS STUD_CODE";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$code = $data['STUD_CODE'];
		}
		return $code;
	}
	/* validate student code */
	public function validate_student_code($code, $stud_id = '')
	{
		$sql = "SELECT STUDENT_CODE FROM student_details WHERE STUDENT_CODE='$code'";
		if ($stud_id != '')
			$sql .= " AND STUDENT_ID!='$stud_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			return false;
		}
		return true;
	}

	/* generate institute code */
	public function generate_institute_code()
	{
		$code = '';
		$code = parent::getRandomCode2();
		$sql = "SELECT INSTITUTE_CODE FROM institute_details WHERE INSTITUTE_CODE='$code'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_institute_code();
		}
		return $code;
	}
	/* validate institute code */
	public function validate_institute_code($code, $inst_id = '')
	{
		$sql = "SELECT INSTITUTE_CODE FROM institute_details WHERE INSTITUTE_CODE='$code'";
		if ($inst_id != '')
			$sql .= " AND INSTITUTE_ID!='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			return false;
		}
		return true;
	}
}
