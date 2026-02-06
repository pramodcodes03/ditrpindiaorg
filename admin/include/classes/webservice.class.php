<?php
include_once('database_results.class.php');
include_once('access.class.php');

//@error_reporting(1);
class webservice extends access
{

	public function defaultReqErr()
	{
		$data['success'] = false;
		$data['errors']  = array('request' => 'invalid');
		$data['message']  = 'Invalid Request!';
		return json_encode($data);
	}
	public function wcheck($username, $password)
	{
		//parent::wset_mysql_charset();
		$info = NULL;
		$username = parent::test($username);
		$password = parent::test($password);
		$password_enc = md5($password);

		$tablename 	= ' user_login_master a ';
		$tabFields 	= " a.USER_LOGIN_ID,a.USER_ID, a.USER_NAME, a.USER_ROLE ";
		$whereClause   = " where a.USER_NAME ='" . $username . "' and a.PASS_WORD ='" . $password_enc . "' AND a.ACTIVE=1 AND a.DELETE_FLAG=0 AND a.USER_ROLE=4 ";

		$selQue 	    = parent::selectData($tabFields, $tablename, $whereClause);
		$selectAccess 	= parent::execQuery($selQue);
		$info = $selectAccess->fetch_assoc();

		return $info;
	}
	public function wuser_login($uname, $pword, $deviceid)
	{

		$errors = array();
		$data = array();
		if ($uname == '')
			$errors['uname'] = 'Username is required.';
		if ($pword == '')
			$errors['pword'] = 'Password is required.';
		if (!empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			//$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			$info 	=	$this->wcheck($uname, $pword);

			if ($info != NULL) {
				$USER_LOGIN_ID 	= $info['USER_LOGIN_ID'];
				$STUDENT_ID 	= $info['USER_ID'];
				//update last login device ID
				$sql = "UPDATE user_login_master  SET LAST_LOGIN_IP='$deviceid', USER_LOGIN_STATUS = '1' WHERE USER_LOGIN_ID='$USER_LOGIN_ID'";
				parent::execQuery($sql);

				$dataR = array();
				$sql1 = "SELECT A.*,B.FILE_NAME FROM student_details A LEFT JOIN student_files B ON A.STUDENT_ID  = B.STUDENT_ID WHERE A.DELETE_FLAG=0 AND A.STUDENT_ID = '$STUDENT_ID' AND B.FILE_LABEL = 'photo' ORDER BY STUDENT_ID DESC";
				$res = parent::execQuery($sql1);
				if ($res && $res->num_rows > 0) {
					while ($dataR = $res->fetch_assoc()) {
						$data['STUDENT_ID'] = $dataR['STUDENT_ID'];
						$data['INSTITUTE_ID'] = $dataR['INSTITUTE_ID'];
						$data['STUDENT_CODE'] = $dataR['STUDENT_CODE'];
						$data['ABBREVIATION'] = $dataR['ABBREVIATION'];
						$data['STUDENT_FNAME'] = $dataR['STUDENT_FNAME'];

						$data['STUDENT_MNAME'] = $dataR['STUDENT_MNAME'];
						$data['STUDENT_LNAME'] = $dataR['STUDENT_LNAME'];
						$data['STUDENT_MOTHERNAME'] = $dataR['STUDENT_MOTHERNAME'];

						$data['STUDENT_MOBILE'] = $dataR['STUDENT_MOBILE'];
						$data['STUDENT_EMAIL'] = $dataR['STUDENT_EMAIL'];
						$data['INSTITUTE_ID'] = $dataR['INSTITUTE_ID'];
						$data['PROFILE_PHOTO'] = $dataR['FILE_NAME'];
						$data['PROFILE_PHOTO_PATH'] = HTTP_HOST . 'uploads/student/' . $dataR['STUDENT_ID'] . '/' . $dataR['FILE_NAME'];
					}
				}

				$data['success'] = true;
				$data['message'] = 'Success! User logged in successfully!';
			} else {
				$data['message'] = 'Sorry! Login credentials does not matched.';
				$data['success'] = false;
				//$data['errors']  = $errors;
			}
		}

		return $data;
	}

	public function wuser_logout($userid)
	{
		$errors = array();
		$data = array();

		$sql = "UPDATE user_login_master  SET USER_LOGIN_STATUS = '0' WHERE USER_ID='$userid' AND USER_ROLE = '4'";
		$res =	parent::execQuery($sql);

		if ($res) {
			$data['success'] = true;
			$data['message'] = 'Success! User logout successfully!';
		} else {
			$data['message'] = 'Sorry! something wrong.';
			$data['success'] = false;
			//$data['errors']  = $errors;
		}
		return $data;
	}

	//list student courses 
	public function wgetStudentCourseDetails($student_id)
	{
		$courseData = array();
		$examData = array();
		$sql = "SELECT  B.COURSE_ID FROM student_course_details A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID  WHERE A.DELETE_FLAG=0 AND A.STUDENT_ID='$student_id' ORDER BY A.CREATED_ON DESC";

		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {

			while ($dataR = $res->fetch_assoc()) {
				$COURSE_ID = $dataR['COURSE_ID'];
				$courseData[] = $this->wgetCourseDetails($COURSE_ID);
			}
		}
		return $courseData;
	}
	public function wgetCourseDetails($courseId)
	{
		$dataR = array();
		$sql = "SELECT COURSE_ID,COURSE_CODE,COURSE_AWARD,COURSE_DURATION,COURSE_NAME,COURSE_DETAILS,COURSE_ELIGIBILITY,COURSE_FEES  FROM courses WHERE COURSE_ID='$courseId'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				extract($data);
				$dataR['COURSE_ID'] = $COURSE_ID;
				$dataR['COURSE_NAME'] = $COURSE_NAME;
				$dataR['COURSE_CODE'] = $COURSE_CODE;
				$dataR['COURSE_AWARD'] = $COURSE_AWARD;
				//  $dataR['COURSE_DETAILS'] = $COURSE_DETAILS;
				$dataR['COURSE_DURATION'] = $COURSE_DURATION;
				$dataR['QUESTION_BANK'] = $this->wgetExamDetails($courseId);
			}
		}
		return $dataR;
	}
	public function wgetExamDetails($courseId)
	{
		$examDetail = array();
		$sql = "SELECT AICPE_COURSE_ID,TOTAL_QUESTIONS FROM exam_structure WHERE AICPE_COURSE_ID='$courseId'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data =  $res->fetch_assoc();
			$TOTAL_QUESTIONS = $data['TOTAL_QUESTIONS'] * 4;
			$COURSE_ID = $data['AICPE_COURSE_ID'];
			$sql1 = "SELECT QUESTION_ID,AICPE_COURSE_ID AS COURSE_ID, QUESTION,IMAGE,OPTION_A,OPTION_B,OPTION_C,OPTION_D,CORRECT_ANS FROM exam_question_bank WHERE AICPE_COURSE_ID='$COURSE_ID' ORDER BY RAND()  LIMIT 0, $TOTAL_QUESTIONS";
			$res1 = parent::execQuery($sql1);
			if ($res1 && $res1->num_rows > 0) {
				while ($dataR = $res1->fetch_assoc()) {
					$examDetail[] = $dataR;
				}
			}
		}
		return $examDetail;
	}

	public function wgetStudentDetails($studentid)
	{

		$dataR = array();
		$sql = "SELECT A.STUDENT_ID,A.INSTITUTE_ID, A.STUDENT_CODE,A.STUDENT_FNAME,A.STUDENT_MNAME,A.STUDENT_LNAME, DATE_FORMAT(A.STUDENT_DOB,'%d-%m-%Y') AS STUDENT_DOB,A.STUDENT_GENDER, A.STUDENT_MOBILE, A.STUDENT_EMAIL, A.STUDENT_TEMP_ADD AS STUDENT_ADDRESS, B.INSTITUTE_CODE, B.INSTITUTE_NAME, B.ADDRESS_LINE1 AS INSTITUTE_ADDRESS, get_institute_city(B.INSTITUTE_ID) AS INSTITUTE_CITY, get_institute_state(B.INSTITUTE_ID) AS INSTITUTE_STATE, B.POSTCODE AS INSTITUTE_POSTCODE, B.EMAIL AS INSTITUTE_EMAIL, B.MOBILE AS INSTITUTE_MOBILE  FROM student_details A INNER JOIN institute_details B ON A.INSTITUTE_ID=B.INSTITUTE_ID WHERE A.STUDENT_ID='$studentid'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['STUDENT_ID'] = $data['STUDENT_ID'];
				$dataR['INSTITUTE_ID'] = $data['INSTITUTE_ID'];
				$dataR['INSTITUTE_NAME'] = $data['INSTITUTE_NAME'];
				$dataR['STUDENT_CODE'] = $data['STUDENT_CODE'];
				$dataR['STUDENT_FNAME'] = $data['STUDENT_FNAME'];
				$dataR['STUDENT_MNAME'] = $data['STUDENT_MNAME'];
				$dataR['STUDENT_LNAME'] = $data['STUDENT_LNAME'];
				$dataR['STUDENT_DOB'] = $data['STUDENT_DOB'];
				$dataR['STUDENT_GENDER'] = $data['STUDENT_GENDER'];
				$dataR['STUDENT_MOBILE'] = $data['STUDENT_MOBILE'];
				$dataR['STUDENT_EMAIL'] = $data['STUDENT_EMAIL'];
				$dataR['STUDENT_ADDRESS'] = $data['STUDENT_ADDRESS'];

				$dataR['INSTITUTE_CODE'] = $data['INSTITUTE_CODE'];
				$dataR['INSTITUTE_ADDRESS'] = $data['INSTITUTE_ADDRESS'];
				$dataR['INSTITUTE_CITY'] = $data['INSTITUTE_CITY'];
				$dataR['INSTITUTE_STATE'] = $data['INSTITUTE_STATE'];
				$dataR['INSTITUTE_POSTCODE'] = $data['INSTITUTE_POSTCODE'];
				$dataR['INSTITUTE_EMAIL'] = $data['INSTITUTE_EMAIL'];
				$dataR['INSTITUTE_MOBILE'] = $data['INSTITUTE_MOBILE'];
			}
		}
		return $dataR;
	}
	public function wcheck_exam_status($student_id, $institute_id)
	{
		parent::wset_mysql_charset();
		$info = NULL;
		$student_id = parent::test($student_id);
		$institute_id = parent::test($institute_id);


		$tablename 	= ' student_course_details a ';
		$tabFields 	= " a.EXAM_STATUS";
		$whereClause   = " where a.STUDENT_ID ='" . $student_id . "' and a.INSTITUTE_ID ='" . $institute_id . "' LIMIT 0,1";

		$selQue 	= parent::selectData($tabFields, $tablename, $whereClause);
		$selectAccess 	= parent::execQuery($selQue);
		$info = $selectAccess->fetch_assoc();
		$status = isset($info['EXAM_STATUS']) ? $info['EXAM_STATUS'] : '';
		//$status = ($status==3)?'Appeared':'';
		return $status;
	}
	//get assign pincode institute id

	public function getInstituteid($pincode = NULL)
	{
		//$instid = '';		

		$sql = "SELECT inst_id FROM assign_pincode_institude  WHERE delete_flag=0 AND pincode = '$pincode' ORDER BY id DESC";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {

			while ($dataR = $res->fetch_assoc()) {
				$instid = $dataR['inst_id'];
			}
		}
		return $inst_id;
	}

	//add student
	public function add_student($name, $fathername, $mothername, $surname, $mobile, $email, $dob, $gender, $conf_pass, $aadhar, $address, $state, $city, $pincode, $photo)
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$institute_id = '';
		$name 		        = 	parent::test($name);
		$fathername 		= 	parent::test($fathername);
		$mothername 		= 	parent::test($mothername);
		$surname 		    = 	parent::test($surname);
		$mobile 	=	parent::test($mobile);
		$email 	= 	parent::test($email);
		$dob 	    = 	parent::test($dob);
		$gender 	= 	parent::test($gender);

		$conf_pass = 	parent::test($conf_pass);
		$aadhar 	= 	parent::test($aadhar);
		$address 	= 	parent::test($address);
		$state 	= 	parent::test($state);
		$city 		= 	parent::test($city);
		$pincode 	= 	parent::test($pincode);

		$photo 	= 	$photo;

		$role 				= 4; //student login role		
		$created_by  		= $name;

		$studcode 		= $this->generate_password();

		$sql1 = "SELECT inst_id FROM assign_pincode_institude  WHERE delete_flag=0 AND pincode = '$pincode' ORDER BY id DESC";
		$res1 = parent::execQuery($sql1);

		$dataR1 = $res1->fetch_assoc();
		$institute_id = $dataR1['inst_id'];
		if (!empty($institute_id)) {
			$institute_id = $institute_id;
		} else {
			$institute_id = 1;
		}

		if ($dob != '')
			$dob = @date('Y-m-d', strtotime($dob));

		if (!parent::valid_username($email))
			$errors['email'] = 'Sorry! Username is already used.';


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
		//working validation error showing in application
		if ($photo == '') {
			$data['success'] = false;
			$data['message']  = 'Please upload photo.';
			return $data;
		}

		if (!empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "student_details";
			$tabFields 	= "(STUDENT_ID,INSTITUTE_ID,STUDENT_FNAME,STUDENT_CODE,STUDENT_DOB,STUDENT_GENDER,STUDENT_MOBILE,STUDENT_EMAIL,STUDENT_PER_ADD,STUDENT_STATE,STUDENT_CITY,STUDENT_PINCODE,STUDENT_ADHAR_NUMBER,ACTIVE, CREATED_BY, CREATED_ON,FATHER_NAME,MOTHER_NAME,SURNAME)";

			$insertVals	= "(NULL,'$institute_id','$name','$studcode','$dob','$gender','$mobile','$email','$address','$state','$city','$pincode','$aadhar','1','$created_by',NOW(),'$fathername','$mothername','$surname')";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* -----Get the last insert ID ----- */
				$student_id = parent::last_id();

				// student login details
				$tableName2 	= "user_login_master";
				$tabFields2 	= "(USER_LOGIN_ID, USER_ID, USER_NAME, PASS_WORD,USER_ROLE, ACCOUNT_REGISTERED_ON,ACTIVE, CREATED_BY,CREATED_ON)";
				$insertVals2	= "(NULL, '$student_id', '$email', MD5('$conf_pass'),'$conf_pass','$role',NOW(),'1','$created_by',NOW())";
				$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
				$exSql2			= parent::execQuery($insertSql2);
				if ($exSql2) {
					$tableName5 	= "wallet";
					$tabFields5		= "(WALLET_ID, USER_ID, USER_ROLE, TOTAL_BALANCE,ACTIVE,DELETE_FLAG, CREATED_BY,CREATED_ON)";
					$insertVals5	= "(NULL, '$student_id', '$role', '0','1','0','$created_by',NOW())";
					$insertSql5		= parent::insertData($tableName5, $tabFields5, $insertVals5);
					$exSql5			= parent::execQuery($insertSql5);

					if ($exSql5) {
						$decodedImage = base64_decode("$photo");
						$image_file_name = 'student' . $this->getRandomCode(15) . $student_id;

						$path = '/home4/kzqhujmy/public_html/ditrpselfstudy/uploads/student/photo/' . $image_file_name . '.jpg';
						$return = file_put_contents($path, $decodedImage);

						$student_Image = $image_file_name . '.jpg';

						if ($return != '') {
							$tableName6 	= "student_details";
							$setValues6 	= "STUDENT_IMAGE='$student_Image'";
							$whereClause6 = " WHERE STUDENT_ID='$student_id'";
							$updateSql6	= parent::updateData($tableName6, $setValues6, $whereClause6);
							$exSql6		= parent::execQuery($updateSql6);
						}
					}

					parent::execQuery($sql);
					parent::commit();
				}

				$dataR = array();

				$sql = "SELECT *, CONCAT('https://ditrpself-study.com/uploads/student/photo/',STUDENT_IMAGE) AS STUDENT_IMAGE FROM student_details  WHERE DELETE_FLAG=0 AND STUDENT_ID = '$student_id' ORDER BY STUDENT_ID DESC";
				$res = parent::execQuery($sql);
				if ($res && $res->num_rows > 0) {
					while ($dataR = $res->fetch_assoc()) {
						$data['STUDENT_ID'] = $dataR['STUDENT_ID'];
						$data['STUDENT_CODE'] = $dataR['STUDENT_CODE'];
						$data['STUDENT_FNAME'] = $dataR['STUDENT_FNAME'];
						$data['STUDENT_MOBILE'] = $dataR['STUDENT_MOBILE'];
						$data['STUDENT_EMAIL'] = $dataR['STUDENT_EMAIL'];
						$data['STUDENT_PER_ADD'] = $dataR['STUDENT_PER_ADD'];
						$data['STUDENT_STATE'] = $dataR['STUDENT_STATE'];
						$data['STUDENT_CITY'] = $dataR['STUDENT_CITY'];
						$data['STUDENT_PINCODE'] = $dataR['STUDENT_PINCODE'];
						$data['STUDENT_ADHAR_NUMBER'] = $dataR['STUDENT_ADHAR_NUMBER'];
						$data['STUDENT_DOB'] = $dataR['STUDENT_DOB'];
						$data['STUDENT_GENDER'] = $dataR['STUDENT_GENDER'];
						$data['FATHER_NAME'] = $dataR['FATHER_NAME'];
						$data['MOTHER_NAME'] = $dataR['MOTHER_NAME'];
						$data['SURNAME'] = $dataR['SURNAME'];
						$data['INSTITUTE_ID'] = $dataR['INSTITUTE_ID'];
						$data['STUDENT_IMAGE'] = $dataR['STUDENT_IMAGE'];
						$data['VOLUNTEER'] = $dataR['VOLUNTEER'];
					}
				}



				$data['success'] = true;
				$data['message'] = 'Congratulations! You Are Successfully Registered With DITRP SELF-STUDY.';
			} else {
				//	parent::rollback();
				$data['message'] = 'Sorry! Something went wrong! Registration Failed.';
				$data['success'] = false;
				//$data['errors']  = $errors;

			}
		}
		return $data;
	}

	public function editProfile($userid, $name, $fathername, $mothername, $surname, $mobile, $email, $dob, $gender, $aadhar, $photo, $address)
	{
		$errors = array();  // array to hold validation errors
		$data = array();

		// array to pass back data

		$userid 	        = 	parent::test($userid);
		$name 		        = 	parent::test($name);
		$fathername 		= 	parent::test($fathername);
		$mothername 		= 	parent::test($mothername);
		$surname 		    = 	parent::test($surname);
		$mobile        	=	parent::test($mobile);
		$email         	= 	parent::test($email);
		$dob 	            = 	parent::test($dob);
		$gender 	        = 	parent::test($gender);
		$aadhar        	= 	parent::test($aadhar);
		$address 	        = 	parent::test($address);
		$photo 	= 	$photo;

		// $conf_pass = 	parent::test($conf_pass);
		// 		 $state 	        = 	parent::test($state);
		// 		 $city 		        = 	parent::test($city);
		// 		 $pincode 	        = 	parent::test($pincode);

		//required validations 
		$requiredArr = array('name' => $name, 'mobile' => $mobile, 'email' => $email, 'aadhar' => $aadhar);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}

		// 		  if(!parent::valid_username($email))
		// 			$errors['email'] = 'Sorry! Username is already used.';


		// 			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		// 					$errors['email'] = "Invalid email format";
		// 				}

		// 			if($mobile!=''){
		// 				$valid_mob = parent::valid_mobile($mobile);
		// 				if($valid_mob!='') $errors['mobile'] = $valid_mob;					
		// 			}


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();

			$tableName 	= "student_details";
			$setValues 	= "STUDENT_FNAME='$name', STUDENT_MOBILE='$mobile', STUDENT_EMAIL='$email',STUDENT_DOB='$dob',STUDENT_GENDER='$gender',STUDENT_ADHAR_NUMBER='$aadhar', ACTIVE='1', UPDATED_BY='$name', UPDATED_ON=NOW(),FATHER_NAME='$fathername',MOTHER_NAME='$mothername',SURNAME='$surname',STUDENT_PER_ADD = '$address'";
			$whereClause = " WHERE STUDENT_ID='$userid'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				if ($photo != '') {
					$decodedImage = base64_decode("$photo");
					$image_file_name = 'student' . $this->getRandomCode(15) . $userid;

					$path = '/home4/kzqhujmy/public_html/ditrpselfstudy/uploads/student/photo/' . $image_file_name . '.jpg';
					$return = file_put_contents($path, $decodedImage);

					$student_Image = $image_file_name . '.jpg';

					if ($return != '') {
						$tableName6 	= "student_details";
						$setValues6 	= "STUDENT_IMAGE='$student_Image'";
						$whereClause6 = " WHERE STUDENT_ID='$userid'";
						$updateSql6	= parent::updateData($tableName6, $setValues6, $whereClause6);
						$exSql6		= parent::execQuery($updateSql6);
					}
				}

				$dataR = array();

				$sql = "SELECT *, CONCAT('https://ditrpself-study.com/uploads/student/photo/',STUDENT_IMAGE) AS STUDENT_IMAGE FROM student_details  WHERE DELETE_FLAG=0 AND STUDENT_ID = '$userid' ORDER BY STUDENT_ID DESC";
				$res = parent::execQuery($sql);
				if ($res && $res->num_rows > 0) {
					while ($dataR = $res->fetch_assoc()) {
						$data['STUDENT_ID'] = $dataR['STUDENT_ID'];
						$data['STUDENT_CODE'] = $dataR['STUDENT_CODE'];
						$data['STUDENT_FNAME'] = $dataR['STUDENT_FNAME'];
						$data['STUDENT_MOBILE'] = $dataR['STUDENT_MOBILE'];
						$data['STUDENT_EMAIL'] = $dataR['STUDENT_EMAIL'];
						$data['STUDENT_PER_ADD'] = $dataR['STUDENT_PER_ADD'];
						$data['STUDENT_STATE'] = $dataR['STUDENT_STATE'];
						$data['STUDENT_CITY'] = $dataR['STUDENT_CITY'];
						$data['STUDENT_PINCODE'] = $dataR['STUDENT_PINCODE'];
						$data['STUDENT_ADHAR_NUMBER'] = $dataR['STUDENT_ADHAR_NUMBER'];
						$data['STUDENT_DOB'] = $dataR['STUDENT_DOB'];
						$data['STUDENT_GENDER'] = $dataR['STUDENT_GENDER'];
						$data['FATHER_NAME'] = $dataR['FATHER_NAME'];
						$data['MOTHER_NAME'] = $dataR['MOTHER_NAME'];
						$data['SURNAME'] = $dataR['SURNAME'];
						$data['INSTITUTE_ID'] = $dataR['INSTITUTE_ID'];
						$data['STUDENT_IMAGE'] = $dataR['STUDENT_IMAGE'];
						$data['VOLUNTEER'] = $dataR['VOLUNTEER'];
					}
				}
				parent::execQuery($sql);
				parent::commit();
				//send email
				//require_once(ROOT."/include/email/config.php");						
				//require_once(ROOT."/include/email/templates/student_admission_success.php");
				/**require_once("../email/config.php");				
    						require_once("../email/templates/student_admission_success.php");*/
				$data['success'] = true;
				$data['message'] = 'Success! Your Profile Updated Successfully!';
			} else {
				//	parent::rollback();
				$data['message'] = 'Sorry! Something went wrong! Profile Updation Failed.';
				$data['success'] = false;
				//$data['errors']  = $errors;

			}
		}
		return $data;
	}

	public function changePassword($user_id, $curr_password, $new_password, $confirm_password)
	{
		$errors = array();  // array to hold validation errors
		$data = array();

		// array to pass back data

		$user_id 	            = 	parent::test($user_id);
		$curr_password 		= 	parent::test($curr_password);
		$new_password 		    = 	parent::test($new_password);
		$confirm_password 		= 	parent::test($confirm_password);

		if ($new_password == $confirm_password) {

			$password = $confirm_password;
		}


		//required validations 
		$requiredArr = array('user_id' => $user_id, 'curr_password' => $curr_password, 'new_password' => $new_password, 'confirm_password' => $confirm_password);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();

			$tableName 	= "user_login_master";
			$setValues 	= "PASS_WORD=MD5('$password'), PASSWORD_CHANGE_DATE = NOW()";
			$whereClause = " WHERE USER_ID ='$user_id' AND USER_ROLE = '4'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);

			//	error_log(print_r($updateSql, TRUE)); exit();

			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {

				//	parent::execQuery($sql);
				parent::commit();

				$data['success'] = true;
				$data['message'] = 'Success! Your Password Updated Successfully!';
			} else {
				//	parent::rollback();
				$data['message'] = 'Sorry! Something went wrong! Profile Updation Failed.';
				$data['success'] = false;
				//$data['errors']  = $errors;

			}
		}
		return $data;
	}

	public function getAllCourses($student_id)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();

		$inst_id = $this->get_student_institute_id($student_id);
		$sql = "SELECT A.* FROM institute_courses A WHERE A.DELETE_FLAG=0 AND A.ACTIVE=1 AND INSTITUTE_ID ='$inst_id'";
		$res = parent::execQuery($sql);

		if (($res && $res->num_rows > 0)) {
			while ($data = $res->fetch_assoc()) {
				$isCoursePurchase = $this->checkIsCoursePurchase($student_id, $data['INSTITUTE_COURSE_ID']);
				if ($isCoursePurchase == '') {
					$COURSE_ID = $data['COURSE_ID'];
					$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
					$TYPING_COURSE_ID = $data['TYPING_COURSE_ID'];

					if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
						$course_data = $this->get_course_detail($COURSE_ID);
						$dataR['inst_course_id'] = $data['INSTITUTE_COURSE_ID'];
						$dataR['course_name'] 	= $course_data['COURSE_NAME_MODIFY'];
						$dataR['course_id'] 	= $course_data['COURSE_ID'];

						$dataR['course_code']	    = $course_data['COURSE_CODE'];
						$dataR['course_duration'] 	= $course_data['COURSE_DURATION'];
						$dataR['course_details'] 	= $course_data['COURSE_DETAILS'];
						$dataR['course_eligibility']	= $course_data['COURSE_ELIGIBILITY'];
						$dataR['course_fees']	    = $course_data['COURSE_FEES'];
						$dataR['course_mrp']	        = $course_data['COURSE_MRP'];
						$dataR['course_minamount'] 	= $course_data['MINIMUM_AMOUNT'];
						$dataR['course_image']	    = $course_data['COURSE_IMAGE'];

						$dataR['path'] = HTTP_HOST . 'uploads/course/material/' . $COURSE_ID . '/' . $dataR['course_image'];
					}

					if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
						$course_data = $this->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
						$dataR['inst_course_id'] = $data['INSTITUTE_COURSE_ID'];
						$dataR['course_name'] 		= $course_data['COURSE_NAME_MODIFY'];
						$dataR['course_id']			= $course_data['MULTI_SUB_COURSE_ID'];
						$dataR['course_code']	    = $course_data['MULTI_SUB_COURSE_CODE'];
						$dataR['course_duration'] 	= $course_data['MULTI_SUB_COURSE_DURATION'];
						$dataR['course_details'] 	= $course_data['MULTI_SUB_COURSE_DETAILS'];
						$dataR['course_eligibility']	= $course_data['MULTI_SUB_COURSE_ELIGIBILITY'];
						$dataR['course_fees']	    = $course_data['MULTI_SUB_COURSE_FEES'];
						$dataR['course_mrp']	        = $course_data['MULTI_SUB_COURSE_MRP'];
						$dataR['course_minamount'] 	= $course_data['MULTI_SUB_MINIMUM_AMOUNT'];
						$dataR['course_image']	    = $course_data['MULTI_SUB_COURSE_IMAGE'];

						$dataR['path'] = HTTP_HOST . 'uploads/course_with_sub/material/' . $MULTI_SUB_COURSE_ID . '/' . $dataR['course_image'];
					}

					if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
						$course_data            = $this->get_course_detail_typing($TYPING_COURSE_ID);
						$dataR['inst_course_id']    = $data['INSTITUTE_COURSE_ID'];
						$dataR['course_name'] 		= $course_data['COURSE_NAME_MODIFY'];
						$dataR['course_id']			= $course_data['TYPING_COURSE_ID'];
						$dataR['course_code']	    = $course_data['TYPING_COURSE_CODE'];
						$dataR['course_duration'] 	= $course_data['TYPING_COURSE_DURATION'];
						$dataR['course_details'] 	= $course_data['TYPING_COURSE_DETAILS'];
						$dataR['course_eligibility']	= $course_data['TYPING_COURSE_ELIGIBILITY'];
						$dataR['course_fees']	    = $course_data['TYPING_COURSE_FEES'];
						$dataR['course_mrp']	        = $course_data['TYPING_COURSE_MRP'];
						$dataR['course_minamount'] 	= $course_data['TYPING_MINIMUM_AMOUNT'];
						$dataR['course_image']	    = $course_data['TYPING_COURSE_IMAGE'];

						$dataR['path'] = HTTP_HOST . 'uploads/course_typing/material/' . $TYPING_COURSE_ID . '/' . $dataR['course_image'];
					}
					$dataP[] = $dataR;
				}
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Course Is Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}


	public function getCoursesById($inst_courseid, $userid, $inst_id)
	{
		$inst_courseid = parent::test($inst_courseid);

		$userid = parent::test($userid);
		$inst_id = parent::test($inst_id);

		$dataR = array();
		$dataS = array();
		$dataP = array();

		$sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.TYPING_COURSE_ID,A.COURSE_FEES,A.EXAM_FEES,A.MINIMUM_FEES FROM institute_courses A WHERE A.INSTITUTE_COURSE_ID = '$inst_courseid' AND A.DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$COURSE_ID = $data['COURSE_ID'];
				$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
				$TYPING_COURSE_ID  = $data['TYPING_COURSE_ID'];

				$COURSE_FEES  = $data['COURSE_FEES'];
				$MINIMUM_FEES  = $data['MINIMUM_FEES'];


				if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
					$course_data                  = $this->get_course_detail($COURSE_ID);
					$dataR['course_name'] 	    = $course_data['COURSE_NAME_MODIFY'];
					$dataR['course_id'] 	        = $course_data['COURSE_ID'];

					$dataR['course_code']	        = $course_data['COURSE_CODE'];
					$dataR['course_duration'] 	= $course_data['COURSE_DURATION'];
					$dataR['course_details'] 	    = html_entity_decode($course_data['COURSE_DETAILS']);
					$dataR['course_eligibility']	= html_entity_decode($course_data['COURSE_ELIGIBILITY']);
					$dataR['course_fees']	        = $COURSE_FEES;
					$dataR['course_mrp']	        = $course_data['COURSE_MRP'];
					$dataR['course_minamount'] 	= $MINIMUM_FEES;
					$dataR['course_image']	    = $course_data['COURSE_IMAGE'];

					$dataR['inst_courseid']	    = $inst_courseid;

					$checkCertPrintAvilability = $this->getCertPrintAvailablity($course_data['COURSE_ID'], $userid, $inst_id);

					if ($checkCertPrintAvilability == '1') {
						$dataR['exam_button_status']	= "1";
						$dataR['CERTIFICATE'] = "1";
					} else {
						$dataR['exam_button_status'] = "0";
						$dataR['CERTIFICATE'] = "0";
					}
					$examStatus = "";
					$examStatus = $this->get_stud_exam_status($userid, $inst_courseid);

					if ($examStatus == '3') {
						$dataR['exam_button_status']	= "1";
						$dataR['CERTIFICATE'] = "0";
					}

					$dataR['path'] = HTTP_HOST . 'uploads/course/material/' . $COURSE_ID . '/' . $dataR['course_image'];

					$coursePDF = $this->get_course_pdf($COURSE_ID);
					$dataR['pdf'] = $coursePDF['result'];
					$courseVideo = $this->get_course_video($COURSE_ID);
					$dataR['video'] = $courseVideo['result'];
				}

				if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
					$course_data                  = $this->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
					$dataR['course_name'] 	    = $course_data['COURSE_NAME_MODIFY'];
					$dataR['course_id']	        = $course_data['MULTI_SUB_COURSE_ID'];
					$dataR['course_code']	        = $course_data['MULTI_SUB_COURSE_CODE'];
					$dataR['course_duration'] 	= $course_data['MULTI_SUB_COURSE_DURATION'];
					$dataR['course_details'] 	    = html_entity_decode($course_data['MULTI_SUB_COURSE_DETAILS']);
					$dataR['course_eligibility']	= html_entity_decode($course_data['MULTI_SUB_COURSE_ELIGIBILITY']);
					$dataR['course_fees']	        = $COURSE_FEES;
					$dataR['course_mrp']	        = $course_data['MULTI_SUB_COURSE_MRP'];
					$dataR['course_minamount'] 	= $MINIMUM_FEES;
					$dataR['course_image']	    = $course_data['MULTI_SUB_COURSE_IMAGE'];

					$dataR['inst_courseid']	    = $inst_courseid;

					$dataR['exam_button_status']	= "1";

					$dataR['path'] = HTTP_HOST . 'uploads/course_with_sub/material/' . $MULTI_SUB_COURSE_ID . '/' . $dataR['course_image'];

					$coursePDF = $this->get_course_pdf_multi($MULTI_SUB_COURSE_ID);
					$dataR['pdf'] = $coursePDF['result'];
					$courseVideo = $this->get_course_video_multi($MULTI_SUB_COURSE_ID);
					$dataR['video'] = $courseVideo['result'];
				}

				if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
					$course_data                  = $this->get_course_detail_typing($TYPING_COURSE_ID);
					$dataR['course_name'] 	    = $course_data['COURSE_NAME_MODIFY'];
					$dataR['course_id']	        = $course_data['TYPING_COURSE_ID'];
					$dataR['course_code']	        = $course_data['TYPING_COURSE_CODE'];
					$dataR['course_duration'] 	= $course_data['TYPING_COURSE_DURATION'];
					$dataR['course_details'] 	    = html_entity_decode($course_data['TYPING_COURSE_DETAILS']);
					$dataR['course_eligibility']	= html_entity_decode($course_data['TYPING_COURSE_ELIGIBILITY']);
					$dataR['course_fees']	        = $COURSE_FEES;
					$dataR['course_mrp']	        = $course_data['TYPING_COURSE_MRP'];
					$dataR['course_minamount'] 	= $MINIMUM_FEES;
					$dataR['course_image']	    = $course_data['TYPING_COURSE_IMAGE'];

					$dataR['inst_courseid']	    = $inst_courseid;

					$dataR['exam_button_status']	= "1";

					$dataR['path'] = HTTP_HOST . 'uploads/course_typing/material/' . $TYPING_COURSE_ID . '/' . $dataR['course_image'];
				}

				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Course Is Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	//single course pdf
	public function get_course_pdf($courseid)
	{
		$courseid = parent::test($courseid);
		$dataQ = array();
		$dataR = array();
		$dataS = array();

		$sql = "SELECT FILE_ID,COURSE_ID, CONCAT('uploads/course/material/',COURSE_ID,'/',FILE_NAME) AS FILE_NAME, FILE_LABEL FROM courses_files WHERE COURSE_ID = '$courseid' AND DELETE_FLAG	= 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataQ['FILE_LABEL']	= $data['FILE_LABEL'];
				$dataQ['FILE_NAME']		= HTTP_HOST . $data['FILE_NAME'];
				$dataR[] = $dataQ;
			}
			$dataS['result'] = $dataR;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No PDF Is Available For This Course!!!';
			$dataS['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataS;
	}

	//single course videos
	public function get_course_video($courseid)
	{
		$courseid = parent::test($courseid);

		$dataR = array();
		$dataS = array();
		$sql = "SELECT id ,course_id, video_link, title FROM course_videos WHERE course_id = '$courseid' AND delete_flag = 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR[] = $data;
			}
			$dataS['result'] = $dataR;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Videos Is Available For This Course!!!';
			$dataS['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataS;
	}

	//multile course pdf
	public function get_course_pdf_multi($courseid)
	{
		$courseid = parent::test($courseid);
		$dataQ = array();
		$dataR = array();
		$dataS = array();

		$sql = "SELECT FILE_ID,MULTI_SUB_COURSE_ID, CONCAT(HTTP_HOST.'uploads/course_with_sub/material/',MULTI_SUB_COURSE_ID,'/',FILE_NAME) AS FILE_NAME, FILE_LABEL FROM multi_sub_courses_files WHERE MULTI_SUB_COURSE_ID = '$courseid' AND DELETE_FLAG	= 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataQ['FILE_LABEL']	= $data['FILE_LABEL'];
				$dataQ['FILE_NAME']		= HTTP_HOST . $data['FILE_NAME'];
				$dataR[] = $dataQ;
			}
			$dataS['result'] = $dataR;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No PDF Is Available For This Course!!!';
			$dataS['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataS;
	}

	//single course videos
	public function get_course_video_multi($courseid)
	{
		$courseid = parent::test($courseid);

		$dataR = array();
		$dataS = array();
		$sql = "SELECT id ,course_id, video_link, title FROM multi_sub_course_videos WHERE course_id = '$courseid' AND delete_flag	= 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR[] = $data;
			}
			$dataS['result'] = $dataR;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Videos Is Available For This Course!!!';
			$dataS['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataS;
	}

	public function getCoursesPDF($courseid)
	{
		$courseid = parent::test($courseid);

		$dataR = array();
		$dataS = array();

		$sql = "SELECT FILE_ID,COURSE_ID, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',FILE_NAME) AS FILE_NAME, FILE_LABEL FROM aicpe_courses_files WHERE COURSE_ID = '$courseid' AND DELETE_FLAG	= 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR[] = $data;
			}
			$dataS['result'] = $dataR;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No PDF Is Available For This Course!!!';
			$dataS['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataS;
	}

	public function getCoursesVideos($courseid)
	{
		$courseid = parent::test($courseid);

		$dataR = array();
		$dataS = array();
		$sql = "SELECT FILE_ID,COURSE_ID, FILE_LABEL, VIDEO_LINK FROM aicpe_course_video WHERE COURSE_ID = '$courseid' AND DELETE_FLAG	= 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR[] = $data;
			}
			$dataS['result'] = $dataR;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Videos Is Available For This Course!!!';
			$dataS['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataS;
	}

	public function getWallet($userid)
	{
		$userid 	= 	parent::test($userid);

		$dataR = array();
		$dataS = array();
		$dataP = array();
		$sql = "SELECT * FROM wallet WHERE USER_ID = '$userid' AND USER_ROLE = 4 AND DELETE_FLAG = 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['WALLET_ID'] = $data['WALLET_ID'];
				$dataR['USER_ID'] = $data['USER_ID'];
				$dataR['USER_ROLE'] = $data['USER_ROLE'];
				$dataR['TOTAL_BALANCE'] = $data['TOTAL_BALANCE'];
				$dataS = $dataR;
			}
			//	$dataS['result'] = $dataP;
			//$dataS['success'] = true;
		} else {
			$dataS['message'] = 'Wallet Is Not Available For This User.';
			$dataS['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataS;
	}

	public function getInstituteDetails($userid)
	{
		$userid 	= 	parent::test($userid);

		$dataR = array();
		$dataS = array();
		$dataP = array();

		$sql = "SELECT B.INSTITUTE_CODE, B.INSTITUTE_NAME, B.INSTITUTE_OWNER_NAME, B.ADDRESS_LINE1, B.ADDRESS_LINE2, B.MOBILE, B.EMAIL,B.POSTCODE,(SELECT STATE_NAME FROM states_master WHERE STATE_ID=B.STATE) AS STATE_NAME,B.CITY  FROM student_details A LEFT JOIN institute_details B ON A.INSTITUTE_ID = B.INSTITUTE_ID WHERE A.STUDENT_ID = '$userid' AND A.DELETE_FLAG = 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['INSTITUTE_CODE']        = $data['INSTITUTE_CODE'];
				$dataR['INSTITUTE_NAME']        = $data['INSTITUTE_NAME'];
				$dataR['INSTITUTE_OWNER_NAME']  = $data['INSTITUTE_OWNER_NAME'];
				$dataR['ADDRESS_LINE1']         = $data['ADDRESS_LINE1'];
				$dataR['MOBILE'] 	            = $data['MOBILE'];
				$dataR['EMAIL'] 	            = $data['EMAIL'];
				$dataR['CITY_NAME'] 		    = $data['CITY'];
				$dataR['STATE_NAME']        	= $data['STATE_NAME'];
				$dataR['POSTCODE']        	= $data['POSTCODE'];
				$dataR['address']        	= $data['ADDRESS_LINE1'] . ' ' . $data['CITY'] . ' ' . $data['STATE_NAME'] . ' - ' . $data['POSTCODE'];

				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Institute For This User.';
			$dataS['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataS;
	}

	public function getStateList()
	{

		$dataR = array();
		$sql = "SELECT STATE_ID, STATE_NAME FROM states_master WHERE 1";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR[] = $data;
			}
		}
		return $dataR;
	}

	public function getCityList($stateid)
	{
		$stateid 	= 	parent::test($stateid);

		$dataR = array();
		$sql = "SELECT CITY_ID, CITY_NAME FROM city_master WHERE STATE_ID = '$stateid'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR[] = $data;
			}
		}
		return $dataR;
	}

	public function getProfile($userid)
	{
		$userid 	= 	parent::test($userid);

		$dataR = array();
		$sql = "SELECT A.*,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME, (SELECT STATE_NAME FROM states_master WHERE STATE_ID=A.STUDENT_STATE) AS STUDENT_STATE, B.FILE_NAME FROM student_details A LEFT JOIN student_files B ON A.STUDENT_ID  = B.STUDENT_ID WHERE A.STUDENT_ID = '$userid' AND B.FILE_LABEL = 'photo' AND A.DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['STUDENT_ID'] = $data['STUDENT_ID'];
				$dataR['STUDENT_CODE'] = $data['STUDENT_CODE'];
				$dataR['STUDENT_FNAME'] = $data['STUDENT_FNAME'];
				$dataR['STUDENT_MOBILE'] = $data['STUDENT_MOBILE'];
				$dataR['STUDENT_EMAIL'] = $data['STUDENT_EMAIL'];
				$dataR['STUDENT_PER_ADD'] = $data['STUDENT_PER_ADD'];
				$dataR['STUDENT_STATE'] = $data['STUDENT_STATE'];
				$dataR['STUDENT_CITY'] = $data['STUDENT_CITY'];
				$dataR['STUDENT_PINCODE'] = $data['STUDENT_PINCODE'];
				$dataR['STUDENT_ADHAR_NUMBER'] = $data['STUDENT_ADHAR_NUMBER'];
				$dataR['STUDENT_DOB'] = $data['STUDENT_DOB'];
				$dataR['STUDENT_GENDER'] = $data['STUDENT_GENDER'];
				$dataR['FATHER_NAME'] = $data['STUDENT_MNAME'];
				$dataR['MOTHER_NAME'] = $data['STUDENT_MOTHERNAME'];
				$dataR['SURNAME'] = $data['STUDENT_LNAME'];
				$dataR['INSTITUTE_ID'] = $data['INSTITUTE_ID'];
				$dataR['INSTITUTE_NAME'] = $data['INSTITUTE_NAME'];

				$dataR['PROFILE_PHOTO'] = $data['FILE_NAME'];
				$dataR['PROFILE_PHOTO_PATH'] = HTTP_HOST . 'uploads/student/' . $data['STUDENT_ID'] . '/' . $data['FILE_NAME'];

				$dataR['resume_link'] = HTTP_HOST . "studentResume.php?id=" . $userid;
			}
		}
		return $dataR;
	}



	public function getSlider()
	{
		//error_log($_POST);
		if (!empty($_POST)) {
			$userid     = $_POST['USER_ID'];
			$playerid   = $_POST['PLAYER_ID'];

			$sql1 = "UPDATE user_login_master SET PLAYER_ID = '$playerid' WHERE USER_ID = '$userid' AND USER_ROLE = '4' ";
			$res1 = parent::execQuery($sql1);
		}

		$dataR1 = array();
		$sqlupdatetype = "SELECT type from update_type WHERE 1";
		$res2 = parent::execQuery($sqlupdatetype);
		$dataR1 = $res2->fetch_assoc();

		$updatetype = $dataR1['type'];
		if ($updatetype == 1) {
			$updatetype = TRUE;
		} else {
			$updatetype = FALSE;
		}


		$dataR = array();
		$dataS = array();
		$dataQ = array();
		$dataT = array();

		$sql = "SELECT SLIDER_ID, CONCAT('https://ditrpself-study.com/uploads/slidernew/',SLIDER_ID,'/',SLIDER_IMG) AS SLIDER_IMG, COURSE_ID, URL  FROM slider WHERE DELETE_FLAG = 0 ";
		$res = parent::execQuery($sql);

		$sql_section = "SELECT SECTION_ID, SECTION_NAME, CONCAT('https://ditrpself-study.com/uploads/coursesection/',SECTION_ID,'/',SECTION_IMG) AS SECTION_IMG FROM course_sections WHERE DELETE_FLAG = 0 ";
		$res_section = parent::execQuery($sql_section);


		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR[] = $data;
			}
		}

		if ($res_section && $res_section->num_rows > 0) {
			while ($data_section = $res_section->fetch_assoc()) {
				$dataQ[] = $data_section;
			}
		}
		$dataS['result'] = $dataR;
		$dataS['update_type'] = $updatetype;
		$dataS['course_section'] = $dataQ;


		return $dataS;
	}

	public function buyCourseOnline()
	{
		$errors = array();  // array to hold validation errors
		$data = array();  // array to pass back data
		$data1 = array();

		// error_log(print_r($_GET , TRUE)); 

		$course_id         = $_GET['course_id'];
		$user_id           = $_GET['user_id'];
		$amount            = $_GET['amount'];
		$referal_code        = $_GET['referal_code'];

		$sql6 = "SELECT STUD_COURSE_DETAIL_ID from student_course_details WHERE STUDENT_ID = '$user_id' AND  INSTITUTE_COURSE_ID = '$course_id' ORDER BY STUD_COURSE_DETAIL_ID ASC";
		$res6 = parent::execQuery($sql6);

		if ($res6->num_rows == 0) {


			if ($_GET['course_id'] && $_GET['course_id'] != "" && $_GET['user_id'] && $_GET['user_id'] != "" && $_GET['amount'] && $_GET['amount'] != "") {

				$sql = "SELECT STUDENT_ID,STUDENT_CODE, INSTITUTE_ID, STUDENT_FNAME, STUDENT_EMAIL,STUDENT_MOBILE from student_details WHERE STUDENT_ID = '$user_id' AND  DELETE_FLAG = '0' ORDER BY STUDENT_ID ASC";
				$res = parent::execQuery($sql);
				$data = $res->fetch_assoc();

				$STUDENT_FNAME = $data['STUDENT_FNAME'];
				$STUDENT_EMAIL = $data['STUDENT_EMAIL'];
				$STUDENT_MOBILE = $data['STUDENT_MOBILE'];
				$INSTITUTE_ID = $data['INSTITUTE_ID'];
				$STUDENT_CODE = $data['STUDENT_CODE'];

				if ($referal_code != '') {
					$sql5 = "SELECT STUDENT_ID, STUDENT_CODE  from student_details WHERE STUDENT_CODE = '$referal_code' AND  DELETE_FLAG = '0' ORDER BY STUDENT_ID ASC";
					$res5 = parent::execQuery($sql5);
					$data5 = $res5->fetch_assoc();
					$REFERAL_ID = $data5['STUDENT_ID'];
					$REFERAL_CODE = $data5['STUDENT_CODE'];

					if ($REFERAL_ID != $user_id && $referal_code == $REFERAL_CODE) {
						$referal_code = $referal_code;
					} else {
						$data1['message'] = "You Can Not Use Your Code As Referal Code Or Your Enter Code Is Invalid. Please Refer Code To Your Friends To Get Benifits.";
					}
				}

				$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);


				$surl = SUCCESS_URL;
				$furl = FAILURE_URL;
				$curl = FAILURE_URL;

				$key  = MERCHANT_KEY;

				$PAYU_BASE_URL = PAYU_BASE_URL;
				$SALT          = SALT;

				$service_provider = 'payu_paisa';

				$_GET['key']            = $key;
				$_GET['txnid']          = $txnid;
				$_GET['amount']         = $amount;
				$_GET['productinfo']    = "Course Purchase";

				$_GET['firstname']      = $STUDENT_FNAME;
				$_GET['email']          = $STUDENT_EMAIL;
				$_GET['phone']          = $STUDENT_MOBILE;

				$_GET['courseid']        = $course_id;
				$_GET['userid']          = $user_id;

				$_GET['user_role']          = '4';
				$_GET['referal_code']       = $referal_code;


				$_GET['surl']      = $surl;
				$_GET['furl']      = $furl;
				$_GET['curl']      = $curl;

				$_GET['service_provider']     = $service_provider;

				$posted = array();
				if (!empty($_GET)) {
					//print_r($_GET);
					foreach ($_GET as $key => $value) {
						$posted[$key] = $value;
					}
				}

				//print_r($posted); exit();
				$posted['hash'] = $hash;
				// Hash Sequence
				$hashSequence = "key|txnid|amount|productinfo|firstname|email|phone|courseid|userid|user_role|referal_code|udf5|udf6|udf7|udf8|udf9|udf10";
				if (empty($posted['hash']) && sizeof($posted) > 0) {
					if (
						empty($posted['key'])
						|| empty($posted['txnid'])
						|| empty($posted['amount'])
						|| empty($posted['firstname'])
						|| empty($posted['email'])
						|| empty($posted['phone'])
						|| empty($posted['productinfo'])
						|| empty($posted['surl'])
						|| empty($posted['furl'])
						|| empty($posted['service_provider'])
						|| empty($posted['userid'])
						|| empty($posted['user_role'])
						|| empty($posted['courseid'])
						|| empty($posted['referal_code'])
					) {
						$formError = 1;
					} else {
						$hashVarsSeq = explode('|', $hashSequence);
						$hash_string = '';
						foreach ($hashVarsSeq as $hash_var) {
							$hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
							$hash_string .= '|';
						}

						$hash_string .= $SALT;

						//print_r($hash_string); exit();

						$hash = strtolower(hash('sha512', $hash_string));
						$action = $PAYU_BASE_URL . '/_payment';
					}
				} elseif (!empty($posted['hash'])) {

					$hash = $posted['hash'];

					$action = $PAYU_BASE_URL . '/_payment';
				}
			} else {
				$data1['response'] = "n";
				$data1['success'] = false;
				$data1['error'] = true;
				$data1['message'] = "All field required";
			}
		} else {
			$data1['response'] = "n";
			$data1['success'] = false;
			$data1['message'] = "You already purchase this course.";
		}
		return $data1;
	}

	public function CoursePurchaseList($userid)
	{
		$userid = parent::test($userid);

		$dataR = array();
		$dataS = array();
		$dataP = array();
		$sql = "SELECT A.*,get_institute_demo_count(A.INSTITUTE_ID) AS INSTITUTE_DEMO_COUNT, get_student_name(A.STUDENT_ID) AS STUDENT_NAME,get_student_code(A.STUDENT_ID) AS STUDENT_CODE , (SELECT C.EXAM_STATUS FROM exam_status_master C WHERE C.EXAM_STATUS_ID=A.EXAM_STATUS) AS EXAM_STATUS_NAME, (SELECT D.EXAM_TYPE FROM exam_types_master D WHERE D.EXAM_TYPE_ID=A.EXAM_TYPE) AS EXAM_TYPE_NAME, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON,'%d %M %Y') AS ACCOUNT_REGISTERED_DATE FROM student_course_details A LEFT JOIN user_login_master B ON A.STUDENT_ID=B.USER_ID  WHERE A.DELETE_FLAG=0 AND B.USER_ROLE=4 AND  A.STUDENT_ID = '$userid' ORDER BY  A.STUD_COURSE_DETAIL_ID DESC";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$i = 0;
			while ($data = $res->fetch_assoc()) {

				if ($i % 2 == 0) {
					$dataR['BCK_IMAGE'] = HTTP_HOST . 'resources/list1.png';
				}
				if ($i % 2 == 1) {
					$dataR['BCK_IMAGE'] = HTTP_HOST . 'resources/list2.png';
				}
				if ($i % 3 == 0) {
					$dataR['BCK_IMAGE'] = HTTP_HOST . 'resources/list3.png';
				}
				if ($i % 3 == 1) {
					$dataR['BCK_IMAGE'] = HTTP_HOST . 'resources/list4.png';
				}

				$exam_button_status = "0";
				if ($data['EXAM_STATUS'] == '3') {
					$exam_button_status = "1";
				}
				$COURSE_INFO = $this->get_inst_course_info($data['INSTITUTE_COURSE_ID']);
				$COURSE_ID = $MULTI_SUB_COURSE_ID = $TYPING_COURSE_ID = 0;
				if ($COURSE_INFO['COURSE_ID'] != '' && !empty($COURSE_INFO['COURSE_ID']) && $COURSE_INFO['COURSE_ID'] != '0') {
					$checkCertPrintAvilability = $this->getCertPrintAvailablity($COURSE_INFO['COURSE_ID'], $userid, $data['INSTITUTE_ID']);
					$COURSE_ID = $COURSE_INFO['COURSE_ID'];
					if ($checkCertPrintAvilability == '1') {
						$exam_button_status = "1";
					}
				}
				if ($COURSE_INFO['MULTI_SUB_COURSE_ID'] != '' && !empty($COURSE_INFO['MULTI_SUB_COURSE_ID']) && $COURSE_INFO['MULTI_SUB_COURSE_ID'] != '0') {
					$checkCertPrintAvilability = $this->getCertPrintAvailablityMulti($COURSE_INFO['MULTI_SUB_COURSE_ID'], $userid, $data['INSTITUTE_ID']);
					$MULTI_SUB_COURSE_ID = $COURSE_INFO['MULTI_SUB_COURSE_ID'];
					$exam_button_status = "1";
				}
				if ($COURSE_INFO['TYPING_COURSE_ID'] != '' && !empty($COURSE_INFO['TYPING_COURSE_ID']) && $COURSE_INFO['TYPING_COURSE_ID'] != '0') {
					$checkCertPrintAvilability = $this->getCertPrintAvailablityTyping($COURSE_INFO['TYPING_COURSE_ID'], $userid, $data['INSTITUTE_ID']);
					$TYPING_COURSE_ID = $COURSE_INFO['TYPING_COURSE_ID'];
					$exam_button_status = "1";
				}

				$dataR['STUD_COURSE_DETAIL_ID'] = $data['STUD_COURSE_DETAIL_ID'];
				$dataR['INSTITUTE_COURSE_ID'] = $data['INSTITUTE_COURSE_ID'];
				$dataR['STUDENT_ID'] = $data['STUDENT_ID'];
				$dataR['COURSE_NAME'] =  $this->get_inst_course_name($data['INSTITUTE_COURSE_ID']);
				$dataR['COURSE_FEES'] = $data['COURSE_FEES'];
				$dataR['EXAM_STATUS'] = $data['EXAM_STATUS_NAME'];
				$dataR['JOINED_ON'] = $data['ACCOUNT_REGISTERED_DATE'];

				$dataR['EXAM_STATUS_ID'] = $data['EXAM_STATUS'];
				$dataR['EXAM_TYPE'] = $data['EXAM_TYPE'];

				$dataR['COURSE_ID'] = "$COURSE_ID";
				$dataR['MULTI_SUB_COURSE_ID'] = "$MULTI_SUB_COURSE_ID";
				$dataR['TYPING_COURSE_ID'] = "$TYPING_COURSE_ID";
				$inst_id = $data['INSTITUTE_ID'];

				$dataR['INSTITUTE_ID'] = "$inst_id";

				if ($checkCertPrintAvilability == '1') {
					$dataR['CERTIFICATE'] = 1;
					$dataR['CERTIFICATE_PATH'] =  HTTP_HOST . "studentCertificate.php?user_id=" . $data['STUDENT_ID'] . "&course=" . $COURSE_ID . "&course_multi_sub=" . $MULTI_SUB_COURSE_ID . "&course_typing=" . $TYPING_COURSE_ID . "&inst_id=" . $inst_id;
					$dataR['MARKSHEET_PATH']   =  HTTP_HOST . "studentMarksheet.php?user_id=" . $data['STUDENT_ID'] . "&course=" . $COURSE_ID . "&course_multi_sub=" . $MULTI_SUB_COURSE_ID . "&course_typing=" . $TYPING_COURSE_ID . "&inst_id=" . $inst_id;
				} else {
					$dataR['CERTIFICATE'] = 0;
					$dataR['CERTIFICATE_PATH'] = "";
					$dataR['MARKSHEET_PATH'] = "";
				}

				$dataR['exam_button_status'] = $exam_button_status;


				$dataR['ADMISSION_FORM'] =  HTTP_HOST . "studentAdmissionForm.php?id=" . $data['STUDENT_ID'] . "&courseid=" . $data['INSTITUTE_COURSE_ID'];
				$dataR['ID_CARD'] =  HTTP_HOST . "studentAdmissionIDCard.php?id=" . $data['STUDENT_ID'] . "&courseid=" . $data['INSTITUTE_COURSE_ID'];

				$dataP[] = $dataR;

				$i++;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'You Do Not Purchase Any Course Till Now.';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	//demo exam section

	public function DemoExamList($inst_course_id, $userid)
	{
		$inst_course_id = parent::test($inst_course_id);
		$userid = parent::test($userid);
		$dataR = array();
		$dataS = array();
		$sql = "SELECT A.*, B.COURSE_ID,B.EXAM_ID, B.EXAM_TITLE, B.TOTAL_MARKS, B.TOTAL_QUESTIONS, B.MARKS_PER_QUE, B.PASSING_MARKS, B.EXAM_TIME  FROM student_course_details A LEFT JOIN institute_courses C ON A.INSTITUTE_COURSE_ID = C.INSTITUTE_COURSE_ID LEFT JOIN exam_structure B ON C.COURSE_ID = B.COURSE_ID WHERE A.STUDENT_ID = '$userid' AND A.INSTITUTE_COURSE_ID = '$inst_course_id' AND A.DELETE_FLAG	= 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$DEMO_REMAINING = 50 - $data['DEMO_COUNT'];
				//DEMO_ATTEMPT

				$dataR['STUD_COURSE_DETAIL_ID'] = $data['STUD_COURSE_DETAIL_ID'];
				$dataR['COURSE_ID'] = $data['COURSE_ID'];
				$dataR['STUDENT_ID'] = $data['STUDENT_ID'];
				$dataR['COURSE_FEES'] = $data['COURSE_FEES'];
				$dataR['INSTITUTE_COURSE_ID'] = $data['INSTITUTE_COURSE_ID'];
				$dataR['DEMO_COUNT'] = "50";
				$dataR['DEMO_ATTEMPT'] = $data['DEMO_COUNT'];
				$dataR['DEMO_REMAINING'] = "$DEMO_REMAINING";

				$dataR['EXAM_ATTEMPT'] = $data['EXAM_ATTEMPT'];
				$dataR['EXAM_ID'] = $data['EXAM_ID'];
				$dataR['EXAM_TITLE'] = $data['EXAM_TITLE'];
				$dataR['TOTAL_MARKS'] = $data['TOTAL_MARKS'];
				$dataR['TOTAL_QUESTIONS'] = $data['TOTAL_QUESTIONS'];
				$dataR['MARKS_PER_QUE'] = $data['MARKS_PER_QUE'];
				$dataR['PASSING_MARKS'] = $data['PASSING_MARKS'];
				$dataR['EXAM_TIME'] = $data['EXAM_TIME'];
				$dataS = $dataR;
			}
		} else {
			$dataS['message'] = 'You Attempted all Demo Exam.';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	public function StartDemoExam($courseid, $userid, $langid)
	{
		$courseid    = parent::test($courseid);
		$userid     = parent::test($userid);
		$langid     = parent::test($langid);

		$sessionid = $this->generate_exam_sessioncode();
		$courseid  =  $this->get_course_id($courseid);

		$dataR = array();
		$dataS = array();

		$sql = "SELECT * FROM exam_structure WHERE COURSE_ID = '$courseid' AND DELETE_FLAG	= 0 ";
		$res = parent::execQuery($sql);

		$data = $res->fetch_assoc();

		$TOTAL_QUESTIONS = $data['TOTAL_QUESTIONS'];
		$EXAM_ID         = $data['EXAM_ID'];

		$sql1 = "SELECT * FROM exam_question_bank WHERE COURSE_ID = '$courseid' AND ACTIVE = 1 AND DELETE_FLAG = 0 AND LANG_ID = $langid order by RAND() limit $TOTAL_QUESTIONS";
		$res1 = parent::execQuery($sql1);

		if ($res1 && $res1->num_rows > 0) {
			while ($data1 = $res1->fetch_assoc()) {
				// for($i=0 ; $i < count($res1);$i++){

				$QUESTION_ID = $data1['QUESTION_ID'];
				$QUESTION    = $data1['QUESTION'];
				$IMAGE = $data1['IMAGE'];
				$OPTION_A = $data1['OPTION_A'];
				$OPTION_B = $data1['OPTION_B'];
				$OPTION_C = $data1['OPTION_C'];
				$OPTION_D = $data1['OPTION_D'];
				$CORRECT_ANS = $data1['CORRECT_ANS'];

				$tableName2 	= "p_exam_attempt";
				$tabFields2 	= "(id, exam_id, student_id, institute_id,question_id,question, image,option_a, option_b,option_c,option_d,correct_ans,session_id,LANG_ID)";
				$insertVals2	= "(NULL, '$EXAM_ID', '$userid',NULL,'$QUESTION_ID','$QUESTION','$IMAGE','$OPTION_A','$OPTION_B','$OPTION_C','$OPTION_D','$CORRECT_ANS','$sessionid','$langid')";
				$insertSql2	    = parent::insertData($tableName2, $tabFields2, $insertVals2);
				$exSql2			= parent::execQuery($insertSql2);


				// }
			}
		}
		parent::execQuery($sql1);
		parent::commit();
		if ($exSql2) {
			$data3['success'] = true;
			$data3['message'] = 'Success! Demo Exam Paper Sets.';
			$data3['session_id'] = $sessionid;
			$data3['exam_id'] = $EXAM_ID;
		} else {
			$data3['success'] = false;
			$data3['message'] = 'Demo Exam Paper Not Sets!!!';
		}

		return $data3;
	}

	public function GetDemoExam($courseid, $userid, $sessionid)
	{
		$courseid = parent::test($courseid);
		$userid = parent::test($userid);
		$sessionid = parent::test($sessionid);


		$dataR = array();
		$dataS = array();
		$sql = "SELECT * from p_exam_attempt WHERE student_id = '$userid' AND  session_id = '$sessionid' ORDER BY id ASC";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$srno = 1;

			while ($data = $res->fetch_assoc()) {
				$dataR['srno'] = $srno;

				$dataR['id'] = $data['id'];
				$dataR['exam_id'] = $data['exam_id'];
				$dataR['session_id'] = $data['session_id'];
				$dataR['student_id'] = $data['student_id'];
				$dataR['question_id'] = $data['question_id'];
				$dataR['question'] = $data['question'];
				// $dataR['image'] =$data['image'];

				$dataR['option_a'] = $data['option_a'];
				$dataR['option_b'] = $data['option_b'];
				$dataR['option_c'] = $data['option_c'];
				$dataR['option_d'] = $data['option_d'];
				$dataR['correct_ans'] = $data['correct_ans'];

				$dataS[] = $dataR;
				$srno++;
			}
		}
		return $dataS;
	}

	public function SaveDemoExam()
	{
		$Question_Array = array();
		$userid     = $_POST['userid'];
		$session_id  = $_POST['session_id'];
		$exam_id  = $_POST['exam_id'];
		$inst_course_id  = $_POST['inst_course_id'];

		$option_a_chk = '';
		$option_b_chk = '';
		$option_c_chk = '';
		$option_d_chk = '';
		$answer_status = 0;
		//print_r($_POST);

		//$data = '[{"qid": 167, "answer": "option_", "correct_ans": "option_a"}, {"qid": 74, "answer": "option_a", "correct_ans": "option_a"}]';

		//$array = json_decode($data, true);

		// print_r($array); exit();

		$Question_Array = $_POST['Question_Array'];
		//print_r($Question_Array);

		$Question_Array = json_decode($Question_Array, true);
		//$Question_Array = explode("},",$Question_Array);
		//print_r($Question_Array);

		//$Question_Array = json_decode($Question_Array);

		//print_r($Question_Array);
		//exit();
		$corrCount = 0;

		foreach ($Question_Array as $key => $value) {

			$value = (array) $value;
			$q_id             = $value['qid'];
			$answer           = $value['answer'];
			$correct_ans      = $value['correct_ans'];
			$answer_status = 0;

			if ($answer == 'option_a') {
				$option_a_chk = '1';
				$option_b_chk = '0';
				$option_c_chk = '0';
				$option_d_chk = '0';

				if ($correct_ans == $answer) {
					$answer_status = 1;
					$corrCount++;
				} else {
					$answer_status = 0;
				}
			} else if ($answer == 'option_b') {
				$option_a_chk = '0';
				$option_b_chk = '1';
				$option_c_chk = '0';
				$option_d_chk = '0';

				if ($correct_ans == $answer) {
					$answer_status = 1;
					$corrCount++;
				} else {
					$answer_status = 0;
				}
			} else if ($answer == 'option_c') {
				$option_a_chk = '0';
				$option_b_chk = '0';
				$option_c_chk = '1';
				$option_d_chk = '0';

				if ($correct_ans == $answer) {
					$answer_status = 1;
					$corrCount++;
				} else {
					$answer_status = 0;
				}
			} else if ($answer == 'option_d') {
				$option_a_chk = '0';
				$option_b_chk = '0';
				$option_c_chk = '0';
				$option_d_chk = '1';

				if ($correct_ans == $answer) {
					$answer_status = 1;
					$corrCount++;
				} else {
					$answer_status = 0;
				}
			} else {
				$option_a_chk = '0';
				$option_b_chk = '0';
				$option_c_chk = '0';
				$option_d_chk = '0';
				$answer_status = 0;
			}
			$tableName 	= "p_exam_attempt";
			$setValues 	= "answer_status='$answer_status', option_a_chk='$option_a_chk', option_b_chk='$option_b_chk',option_c_chk='$option_c_chk',option_d_chk='$option_d_chk'";
			$whereClause = " WHERE question_id='$q_id' AND session_id = '$session_id' AND student_id = '$userid' ";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql	= parent::execQuery($updateSql);
		}

		$correct_answer = $corrCount;

		$sql = "SELECT * from exam_structure WHERE EXAM_ID = '$exam_id'";
		$res = parent::execQuery($sql);
		$data = $res->fetch_assoc();

		$EXAM_TITLE         = $data['EXAM_TITLE'];
		$TOTAL_MARKS        = $data['TOTAL_MARKS'];
		$TOTAL_QUESTIONS    = $data['TOTAL_QUESTIONS'];
		$MARKS_PER_QUE      = $data['MARKS_PER_QUE'];
		$PASSING_MARKS      = $data['PASSING_MARKS'];
		$COURSE_ID      	= $data['COURSE_ID'];

		$INCORRECT_ANSWER   = $TOTAL_QUESTIONS - $correct_answer;

		$MARKS_OBTAINED     = $MARKS_PER_QUE * $correct_answer;
		$markPer = '';
		$grade = '';
		$result_status = '';

		if ($MARKS_OBTAINED > 0) {
			$markPer = (($MARKS_OBTAINED * 100) / $data['TOTAL_MARKS']);
		}

		if ($markPer >= 85) {
			$grade = "A+ : Excellent";
			$result_status = 'Passed';
		} elseif ($markPer >= 70 && $markPer < 85) {
			$grade = "A : Very Good";
			$result_status = 'Passed';
		} elseif ($markPer >= 55 && $markPer < 70) {
			$grade = "B : Good";
			$result_status = 'Passed';
		} elseif ($markPer >= 40 && $markPer < 55) {
			$grade = "C : Average";
			$result_status = 'Passed';
		} else {
			$grade = "";
			$result_status = 'Failed';
		}
		$DEMO_COUNT = "";
		$DEMO_ATTEMPT1 = "";
		$sql7 = "SELECT DEMO_COUNT,DEMO_ATTEMPT from student_course_details WHERE STUDENT_ID = '$userid' AND INSTITUTE_COURSE_ID = '$inst_course_id'";
		$res7 = parent::execQuery($sql7);
		$data7 = $res7->fetch_assoc();

		$DEMO_COUNT        = $data7['DEMO_COUNT'];
		$DEMO_ATTEMPT1     = $data7['DEMO_ATTEMPT'];

		$tableName2 	= "p_exam_result";
		$tabFields2 	= "(EXAM_RESULT_ID,STUDENT_ID, EXAM_ID,EXAM_TITLE, EXAM_ATTEMPT,EXAM_TOTAL_QUE,EXAM_TOTAL_MARKS,EXAM_MARKS_PER_QUE,EXAM_PASSING_MARKS,CORRECT_ANSWER,INCORRECT_ANSWER, MARKS_OBTAINED,RESULT_STATUS,CREATED_ON,SESSION_ID)";
		$insertVals2	= "(NULL,'$userid','$exam_id','$EXAM_TITLE','1','$TOTAL_QUESTIONS','$TOTAL_MARKS','$MARKS_PER_QUE','$PASSING_MARKS','$correct_answer','$INCORRECT_ANSWER','$MARKS_OBTAINED','$result_status',NOW(),'$session_id')";

		$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
		$exSql2			= parent::execQuery($insertSql2);

		if ($result_status != "") {

			$DEMO_ATTEMPT = $DEMO_ATTEMPT1 + 1;
			$DEMO_COUNT   = $DEMO_COUNT + 1;

			$tableName6 	= "student_course_details";
			$setValues6 	= "DEMO_ATTEMPT='$DEMO_ATTEMPT',DEMO_COUNT='$DEMO_COUNT'";
			$whereClause6 = " WHERE STUDENT_ID='$userid' AND INSTITUTE_COURSE_ID = '$inst_course_id'";
			$updateSql6	= parent::updateData($tableName6, $setValues6, $whereClause6);

			$exSql6	= parent::execQuery($updateSql6);
		}
		parent::commit();
		if ($exSql2) {
			$data1['RESULT_STATUS'] = $result_status;
			$data1['EXAM_ID']       = $exam_id;
			$data1['STUDENT_ID']    = $userid;

			$data1['EXAM_TITLE']        = $EXAM_TITLE;
			$data1['TOTAL_MARKS']       = $TOTAL_MARKS;
			$data1['TOTAL_QUESTIONS']   = $TOTAL_QUESTIONS;
			$data1['CORRECT_ANSWER']    = "$correct_answer";
			$data1['INCORRECT_ANSWER']  = "$INCORRECT_ANSWER";
			$data1['MARKS_OBTAINED']    = "$MARKS_OBTAINED";


			$data1['success'] = true;
			$data1['message'] = 'Success! ! You are Successfully attempted exam.';
		}

		return $data1;
	}

	//final exam section

	public function FinalExamList($courseid, $userid)
	{
		$courseid = parent::test($courseid);
		$userid = parent::test($userid);

		$dataR = array();
		$dataS = array();
		$sql = "SELECT A.*, B.EXAM_ID, B.EXAM_TITLE, B.TOTAL_MARKS, B.TOTAL_QUESTIONS, B.MARKS_PER_QUE, B.PASSING_MARKS, B.EXAM_TIME  FROM student_course_details A LEFT JOIN exam_structure B ON A.INSTITUTE_COURSE_ID = B.COURSE_ID WHERE A.STUDENT_ID = '$userid' AND A.INSTITUTE_COURSE_ID = '$courseid' AND A.DELETE_FLAG	= 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['STUD_COURSE_DETAIL_ID'] = $data['STUD_COURSE_DETAIL_ID'];
				$dataR['COURSE_ID'] = $data['INSTITUTE_COURSE_ID'];
				$dataR['STUDENT_ID'] = $data['STUDENT_ID'];
				$dataR['COURSE_FEES'] = $data['COURSE_FEES'];
				$dataR['DEMO_COUNT'] = $data['DEMO_COUNT'];
				$dataR['DEMO_ATTEMPT'] = $data['DEMO_ATTEMPT'];

				$dataR['EXAM_ATTEMPT'] = $data['EXAM_ATTEMPT'];

				$dataR['EXAM_ID'] = $data['EXAM_ID'];
				$dataR['EXAM_TITLE'] = $data['EXAM_TITLE'];
				$dataR['TOTAL_MARKS'] = $data['TOTAL_MARKS'];
				$dataR['TOTAL_QUESTIONS'] = $data['TOTAL_QUESTIONS'];
				$dataR['MARKS_PER_QUE'] = $data['MARKS_PER_QUE'];
				$dataR['PASSING_MARKS'] = $data['PASSING_MARKS'];
				$dataR['EXAM_TIME'] = $data['EXAM_TIME'];
				$dataR['FINAL_EXAM_STATUS'] = $data['FINAL_EXAM_STATUS'];
				$dataR['SHOW_FINALEXAM_BUTTON'] = $data['SHOW_FINALEXAM_BUTTON'];


				$dataS[] = $dataR;
			}
		}
		return $dataS;
	}

	public function StartFinalExam($courseid, $userid, $langid, $inst_course_id)
	{
		$courseid    = parent::test($courseid);
		$userid     = parent::test($userid);
		$langid     = parent::test($langid);
		$inst_course_id     = parent::test($inst_course_id);

		$sessionid = $this->generate_exam_sessioncode();

		$dataR = array();
		$dataS = array();

		$sql = "SELECT * FROM exam_structure WHERE COURSE_ID = '$courseid' AND DELETE_FLAG	= 0 ";
		$res = parent::execQuery($sql);

		$data = $res->fetch_assoc();

		$TOTAL_QUESTIONS = $data['TOTAL_QUESTIONS'];
		$EXAM_ID         = $data['EXAM_ID'];

		$sql1 = "SELECT * FROM exam_question_bank WHERE COURSE_ID = '$courseid' AND ACTIVE = 1 AND DELETE_FLAG = 0 AND LANG_ID = $langid order by RAND() limit $TOTAL_QUESTIONS";
		$res1 = parent::execQuery($sql1);

		if ($res1 && $res1->num_rows > 0) {
			while ($data1 = $res1->fetch_assoc()) {
				//for($i=0 ; $i < $res1->num_rows;$i++){

				$QUESTION_ID = $data1['QUESTION_ID'];
				$QUESTION    = $data1['QUESTION'];
				$IMAGE = $data1['IMAGE'];
				$OPTION_A = $data1['OPTION_A'];
				$OPTION_B = $data1['OPTION_B'];
				$OPTION_C = $data1['OPTION_C'];
				$OPTION_D = $data1['OPTION_D'];
				$CORRECT_ANS = $data1['CORRECT_ANS'];

				$tableName2 	= "exam_attempt";
				$tabFields2 	= "(id, exam_id, student_id, institute_id,question_id,question, image,option_a, option_b,option_c,option_d,correct_ans,session_id,LANG_ID)";
				$insertVals2	= "(NULL, '$EXAM_ID', '$userid',NULL,'$QUESTION_ID','$QUESTION','$IMAGE','$OPTION_A','$OPTION_B','$OPTION_C','$OPTION_D','$CORRECT_ANS','$sessionid','$langid')";
				$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
				$exSql2			= parent::execQuery($insertSql2);


				///}
			}
			$otp = $this->generate_exam_code();

			$tableName3 	= "student_course_details";
			$setValues3 	= "EXAM_SECRETE_CODE='$otp',EXAM_SECRETE_CODE_DATE = NOW()";
			$whereClause3 = " WHERE STUDENT_ID ='$userid' AND INSTITUTE_COURSE_ID ='$inst_course_id' ";
			$updateSql3	= parent::updateData($tableName3, $setValues3, $whereClause3);
			$exSql3	= parent::execQuery($updateSql3);
		}


		parent::execQuery($sql1);
		parent::commit();

		if ($exSql2) {
			$data3['success'] = true;
			$data3['message'] = 'Success! Final Exam Paper Sets.';
			$data3['session_id'] = $sessionid;
			$data3['exam_id'] = $EXAM_ID;
		} else {
			$data3['success'] = false;
			$data3['message'] = 'Final Exam Paper Not Sets!!!';
		}

		return $data3;
	}

	public function GetFinalExam($userid, $sessionid)
	{
		//$courseid = parent::test($courseid);
		$userid = parent::test($userid);
		$sessionid = parent::test($sessionid);


		$dataR = array();
		$dataS = array();
		$sql = "SELECT * from exam_attempt WHERE student_id = '$userid' AND  session_id = '$sessionid' ORDER BY id ASC";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$srno = 1;

			while ($data = $res->fetch_assoc()) {
				$dataR['srno'] = $srno;

				$dataR['id'] = $data['id'];
				$dataR['exam_id'] = $data['exam_id'];
				$dataR['session_id'] = $data['session_id'];
				$dataR['student_id'] = $data['student_id'];
				$dataR['question_id'] = $data['question_id'];
				$dataR['question'] = $data['question'];
				// $dataR['image'] =$data['image'];

				$dataR['option_a'] = $data['option_a'];
				$dataR['option_b'] = $data['option_b'];
				$dataR['option_c'] = $data['option_c'];
				$dataR['option_d'] = $data['option_d'];
				$dataR['correct_ans'] = $data['correct_ans'];

				$dataS[] = $dataR;
				$srno++;
			}
		}
		return $dataS;
	}

	public function SaveFinalExam()
	{
		$Question_Array = array();
		$userid     = $_POST['userid'];
		$session_id  = $_POST['session_id'];
		$exam_id  = $_POST['exam_id'];
		$inst_course_id  = $_POST['inst_course_id'];

		$option_a_chk = '';
		$option_b_chk = '';
		$option_c_chk = '';
		$option_d_chk = '';
		$answer_status = 0;


		$Question_Array = $_POST['Question_Array'];
		//print_r($Question_Array);

		$Question_Array = json_decode($Question_Array, true);
		//print_r($Question_Array); exit();
		$corrCount = 0;

		foreach ($Question_Array as $key => $value) {

			$value = (array) $value;
			$q_id             = $value['qid'];
			$answer           = $value['answer'];
			$correct_ans      = $value['correct_ans'];

			if ($answer == 'option_a') {
				$option_a_chk = '1';
				$option_b_chk = '0';
				$option_c_chk = '0';
				$option_d_chk = '0';

				if ($correct_ans == $answer) {
					$answer_status = 1;
					$corrCount++;
				} else {
					$answer_status = 0;
				}
			} elseif ($answer == 'option_b') {
				$option_a_chk = '0';
				$option_b_chk = '1';
				$option_c_chk = '0';
				$option_d_chk = '0';

				if ($correct_ans == $answer) {
					$answer_status = 1;
					$corrCount++;
				} else {
					$answer_status = 0;
				}
			} elseif ($answer == 'option_c') {
				$option_a_chk = '0';
				$option_b_chk = '0';
				$option_c_chk = '1';
				$option_d_chk = '0';

				if ($correct_ans == $answer) {
					$answer_status = 1;
					$corrCount++;
				} else {
					$answer_status = 0;
				}
			} elseif ($answer == 'option_d') {
				$option_a_chk = '0';
				$option_b_chk = '0';
				$option_c_chk = '0';
				$option_d_chk = '1';

				if ($correct_ans == $answer) {
					$answer_status = 1;
					$corrCount++;
				} else {
					$answer_status = 0;
				}
			} else {
				$option_a_chk = '0';
				$option_b_chk = '0';
				$option_c_chk = '0';
				$option_d_chk = '0';
				$answer_status = 0;
			}
			$tableName 	= "exam_attempt";
			$setValues 	= "answer_status='$answer_status', option_a_chk='$option_a_chk', option_b_chk='$option_b_chk',option_c_chk='$option_c_chk',option_d_chk='$option_d_chk'";
			$whereClause = " WHERE question_id='$q_id' AND session_id = '$session_id' AND student_id = '$userid' ";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql	= parent::execQuery($updateSql);
		}

		$correct_answer = $corrCount;

		$sql = "SELECT * from exam_structure WHERE EXAM_ID = '$exam_id'";
		$res = parent::execQuery($sql);
		$data = $res->fetch_assoc();

		$EXAM_TITLE         = $data['EXAM_TITLE'];
		$TOTAL_MARKS        = $data['TOTAL_MARKS'];
		$TOTAL_QUESTIONS    = $data['TOTAL_QUESTIONS'];
		$MARKS_PER_QUE      = $data['MARKS_PER_QUE'];
		$PASSING_MARKS      = $data['PASSING_MARKS'];
		$COURSE_ID      = $data['COURSE_ID'];


		$INCORRECT_ANSWER   = $TOTAL_QUESTIONS - $correct_answer;

		$MARKS_OBTAINED     = $MARKS_PER_QUE * $correct_answer;


		$RESULT_STATUS = "Result Pending";

		$MARKS_PER = $MARKS_OBTAINED * 100 / $TOTAL_MARKS;
		$GRADE = '';
		if ($MARKS_PER >= 85 && $MARKS_PER <= 100) {
			$GRADE = "A+";
		} elseif ($MARKS_PER >= 70 && $MARKS_PER <= 84) {
			$GRADE = "A";
		} elseif ($MARKS_PER >= 55 && $MARKS_PER <= 69) {
			$GRADE = "B";
		} elseif ($MARKS_PER >= 40 && $MARKS_PER <= 54) {
			$GRADE = "C";
		} else {
			$GRADE = "D";
		}

		$inst_id = $this->get_student_institute_id($userid);

		$tableName2 	= "exam_result";
		$tabFields2 	= "(EXAM_RESULT_ID,STUDENT_ID,INSTITUTE_ID, EXAM_ID,INSTITUTE_COURSE_ID ,EXAM_TITLE, EXAM_ATTEMPT,EXAM_TOTAL_QUE,EXAM_TOTAL_MARKS,EXAM_MARKS_PER_QUE,EXAM_PASSING_MARKS,CORRECT_ANSWER,INCORRECT_ANSWER, MARKS_OBTAINED,RESULT_STATUS,CREATED_ON,SESSION_ID,GRADE,MARKS_PER,EXAM_TYPE)";
		$insertVals2	= "(NULL,'$userid','$inst_id','$exam_id','$inst_course_id','$EXAM_TITLE','1','$TOTAL_QUESTIONS','$TOTAL_MARKS','$MARKS_PER_QUE','$PASSING_MARKS','$correct_answer','$INCORRECT_ANSWER','$MARKS_OBTAINED','$RESULT_STATUS',NOW(),'$session_id','$GRADE','$MARKS_PER','1')";
		$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
		$exSql2			= parent::execQuery($insertSql2);

		if ($RESULT_STATUS == "Result Pending") {
			$tableName6 	= "student_course_details";
			$setValues6 	= "EXAM_STATUS= 3 ";
			$whereClause6 = " WHERE STUDENT_ID='$userid' AND INSTITUTE_COURSE_ID = '$inst_course_id'";
			$updateSql6	= parent::updateData($tableName6, $setValues6, $whereClause6);
			$exSql6	= parent::execQuery($updateSql6);
		} else {
			$tableName6 	= "student_course_details";
			$setValues6 	= "EXAM_STATUS= 2 ";
			$whereClause6 = " WHERE STUDENT_ID='$userid' AND INSTITUTE_COURSE_ID = '$inst_course_id'";
			$updateSql6	= parent::updateData($tableName6, $setValues6, $whereClause6);
			$exSql6	= parent::execQuery($updateSql6);
		}
		parent::commit();

		if ($exSql2) {

			$data1['RESULT_STATUS'] = $RESULT_STATUS;
			$data1['EXAM_ID']       = $exam_id;
			$data1['STUDENT_ID']    = $userid;

			$data1['EXAM_TITLE']    = $EXAM_TITLE;
			$data1['TOTAL_MARKS']    = $TOTAL_MARKS;
			$data1['TOTAL_QUESTIONS']    = $TOTAL_QUESTIONS;
			$data1['CORRECT_ANSWER']    = "$correct_answer";
			$data1['INCORRECT_ANSWER']    = "$INCORRECT_ANSWER";
			$data1['MARKS_OBTAINED']    = "$MARKS_OBTAINED";


			$data1['MARKS_PER']    = "$MARKS_PER";
			$data1['GRADE']    = "$GRADE";

			$data1['INSTITUTE_COURSE_ID']    = "$inst_course_id";

			$data1['success'] = true;
			$data1['message'] = 'Success! ! You are Successfully attempted exam.';
		}

		return $data1;
	}

	//course buy from wallet amount

	public function buyCourseWallet()
	{

		$errors = array();  // array to hold validation errors
		$datafinal = array();

		$course_id    = $_POST['course_id'];
		$user_id      = $_POST['user_id'];
		$amount       = $_POST['amount'];

		$sql6 = "SELECT STUD_COURSE_DETAIL_ID from student_course_details WHERE STUDENT_ID = '$user_id' AND  INSTITUTE_COURSE_ID = '$course_id' ORDER BY STUD_COURSE_DETAIL_ID ASC";
		$res6 = parent::execQuery($sql6);
		if ($res6->num_rows == 0) {

			$sql = "SELECT STUDENT_ID, INSTITUTE_ID, STUDENT_FNAME, STUDENT_EMAIL,STUDENT_MOBILE from student_details WHERE STUDENT_ID = '$user_id' AND  DELETE_FLAG = '0' ORDER BY STUDENT_ID ASC";
			$res = parent::execQuery($sql);
			$data = $res->fetch_assoc();

			$STUDENT_FNAME = $data['STUDENT_FNAME'];
			$STUDENT_EMAIL = $data['STUDENT_EMAIL'];
			$STUDENT_MOBILE = $data['STUDENT_MOBILE'];
			$INSTITUTE_ID = $data['INSTITUTE_ID'];

			$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);

			$sql1 = "SELECT WALLET_ID, TOTAL_BALANCE from wallet WHERE USER_ID = '$user_id' AND  USER_ROLE = '4' AND DELETE_FLAG = '0' ORDER BY WALLET_ID ASC";
			$res1 = parent::execQuery($sql1);
			$data1 = $res1->fetch_assoc();

			$WALLET_ID      = $data1['WALLET_ID'];
			$TOTAL_BALANCE  = $data1['TOTAL_BALANCE'];

			if (empty($course_id))
				$errors['course_id'] = 'Course Is Required';

			if (empty($user_id))
				$errors['user_id'] = 'UserId Is Required';

			if (empty($amount))
				$errors['amount'] = 'Course Amount Is Required';

			if ($TOTAL_BALANCE < $amount)
				$errors['amount'] = 'Sorry! You Do Not Sufficient Amount To Purchase This Course. Try To Purchase By Online Payment.';

			$WalletAmount = $TOTAL_BALANCE - $amount;

			if (! empty($errors)) {
				// if there are items in our errors array, return those errors
				$datafinal['success'] = false;
				$datafinal['errors']  = $errors;
				$datafinal['message']  = 'Please correct all the errors.';
			} else {
				parent::start_transaction();
				$tableName 	= "offline_payments";
				$tabFields 	= "(PAYMENT_ID,TRANSACTION_NO,TRANSACTION_TYPE,USER_ID,USER_ROLE,USER_FULLNAME,USER_EMAIL,USER_MOBILE,PAYMENT_AMOUNT,PAYMENT_MODE,PAYMENT_DATE,PAYMENT_STATUS,WALLET_ID,ACTIVE,DELETE_FLAG, CREATED_BY, CREATED_ON,COURSE_ID)";

				$insertVals	= "(NULL,'$txnid','DEBIT','$user_id','4','$STUDENT_FNAME','$STUDENT_EMAIL','$STUDENT_MOBILE','$amount','Wallet Amount',NOW(),'Success','$WALLET_ID','1','0','$STUDENT_FNAME',NOW(),'$course_id')";

				$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
				$exSql		= parent::execQuery($insertSql);

				if ($exSql) {
					$payment_id = parent::last_id();

					$tableName2 	= "student_course_details";
					$tabFields2 	= "(STUD_COURSE_DETAIL_ID, STUDENT_ID, INSTITUTE_ID, INSTITUTE_COURSE_ID,COURSE_FEES,OFFLINE_PAYMENT_ID, DEMO_COUNT,DEMO_ATTEMPT,ACTIVE,DELETE_FLAG, CREATED_BY,CREATED_ON)";
					$insertVals2	= "(NULL, '$user_id', '$INSTITUTE_ID', '$course_id','$amount','$payment_id','30','0','1','0','$STUDENT_FNAME',NOW())";
					$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
					$exSql2			= parent::execQuery($insertSql2);

					if ($exSql2) {
						$sql = "UPDATE wallet  SET TOTAL_BALANCE='$WalletAmount' WHERE WALLET_ID='$WALLET_ID' AND USER_ID = '$user_id' AND USER_ROLE = '4'";
						parent::execQuery($sql);
						parent::commit();
					}
					$datafinal['success'] = true;
					$datafinal['message'] = 'Success! You Successfully Buy Course By Wallet Amount!';
				} else {
					//	parent::rollback();
					$datafinal['message'] = 'Sorry! Something went wrong! Please Buy Course By Online Payment.';
					$datafinal['success'] = false;
					//	$datafinal['errors']  = $errors;

				}
			}
		} else {
			$datafinal['success'] = false;
			$datafinal['message'] = "You already purchase this course.";
		}
		return $datafinal;
	}

	public function CourseEnquiryByStudent()
	{

		//error_log(print_r($_POST, TRUE)); exit();
		$errors = array();  // array to hold validation errors
		$datafinal = array();

		$institute_id = '';

		$user_id        = $_POST['user_id'];
		$name           = $_POST['name'];
		$mobile         = $_POST['mobile'];
		$email          = $_POST['email'];
		$pincode        = $_POST['pincode'];
		$course_id      = $_POST['course_id'];
		$message        = $_POST['message'];

		$sql1 = "SELECT inst_id FROM assign_pincode_institude  WHERE delete_flag=0 AND pincode = '$pincode' ORDER BY id DESC";
		$res1 = parent::execQuery($sql1);

		$dataR1 = $res1->fetch_assoc();
		$institute_id = $dataR1['inst_id'];
		if (!empty($institute_id)) {
			$institute_id = $institute_id;
		} else {
			$institute_id = 1;
		}

		if (empty($course_id))
			$errors['course_id'] = 'Course Is Required';

		if (empty($pincode))
			$errors['pincode'] = 'Pincode Is Required';

		if (empty($name))
			$errors['name'] = 'Student Name Is Required';

		if (empty($mobile))
			$errors['mobile'] = 'Mobile Number Is Required';

		if ($pincode != '') {
			if (strlen($pincode) != 6)
				$errors['pincode'] = 'Postal code must be in number and 6 digits only.';
		}

		if ($mobile != '') {
			if (strlen($mobile) != 10)
				$errors['mobile'] = ' Please enter valid numbers,Only 10 Digits allowed.';
			if ($mobile <= 0)
				$errors['mobile'] = 'Please enter valid numbers.';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$datafinal['success'] = false;
			$datafinal['errors']  = $errors;
			$datafinal['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "student_enquiry";
			$tabFields 	= "(ENQUIRY_ID,INSTITUTE_ID,STUDENT_FNAME,STUDENT_DOB,STUDENT_GENDER,STUDENT_MOBILE,STUDENT_EMAIL,STUDENT_PER_ADD,STUDENT_STATE,STUDENT_CITY,STUDENT_PINCODE,STUDENT_ID,COURSE_ID,DELETE_FLAG, CREATED_BY, CREATED_ON,MESSAGE)";

			$insertVals	= "(NULL,'$institute_id','$name','','','$mobile','$email','','','','$pincode','$user_id','$course_id','0','$name',NOW(),'$message')";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				parent::commit();
				$datafinal['success'] = true;
				$datafinal['message'] = 'Success! Your Course Enquiry Is Successfully Registered. Our Team Will Contact You Soon!';
			} else {
				//	parent::rollback();
				$datafinal['message'] = 'Sorry! Something went wrong!';
				$datafinal['success'] = false;
				//$datafinal['errors']  = $errors;

			}
		}

		return $datafinal;
	}

	public function WalletTrasactionList($userid)
	{
		$userid 	= 	parent::test($userid);

		$dataR = array();

		$dataS = array();

		$dataP = array();

		$sql = "SELECT A.*, B.COURSE_NAME, B.COURSE_CODE FROM offline_payments A LEFT JOIN courses B ON A.COURSE_ID = B.COURSE_ID WHERE A.USER_ID = '$userid' AND A.USER_ROLE = 4 AND A.DELETE_FLAG = 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['TRANSACTION_TYPE'] = $data['TRANSACTION_TYPE'];
				$dataR['TRANSACTION_NO'] = $data['TRANSACTION_NO'];
				$dataR['PAYMENT_AMOUNT'] = $data['PAYMENT_AMOUNT'];
				$dataR['PAYMENT_DATE'] = $data['PAYMENT_DATE'];
				$dataR['PAYMENT_STATUS'] = $data['PAYMENT_STATUS'];
				$dataR['COURSE_ID'] = $data['COURSE_ID'];
				$dataR['COURSE_NAME'] = $data['COURSE_NAME'];
				$dataR['COURSE_CODE'] = $data['COURSE_CODE'];
				$dataS[] = $dataR;
				//$dataS['success'] = true;

			}
			$dataP['result'] = $dataS;
			$dataP['success'] = true;
		} else {

			$dataP['success'] = false;
			$dataP['message']  = 'No Wallet Transactions Available.';
		}
		return $dataP;
	}

	public function GetFinalExamResult($userid)
	{
		$userid 	= 	parent::test($userid);
		$examid 	= 	parent::test($examid);

		$dataR = array();
		$dataS = array();
		$dataT = array();

		$sql = "SELECT * FROM exam_result WHERE STUDENT_ID = '$userid' AND DELETE_FLAG = 0 ORDER BY EXAM_RESULT_ID DESC";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['EXAM_RESULT_ID'] = $data['EXAM_RESULT_ID'];
				$dataR['EXAM_ID'] = $data['EXAM_ID'];
				$dataR['EXAM_TITLE'] = $data['EXAM_TITLE'];
				$dataR['EXAM_TOTAL_QUE'] = $data['EXAM_TOTAL_QUE'];
				$dataR['EXAM_TOTAL_MARKS'] = $data['EXAM_TOTAL_MARKS'];

				$dataR['EXAM_MARKS_PER_QUE'] = $data['EXAM_MARKS_PER_QUE'];
				$dataR['EXAM_PASSING_MARKS'] = $data['EXAM_PASSING_MARKS'];
				$dataR['CORRECT_ANSWER'] = $data['CORRECT_ANSWER'];
				$dataR['INCORRECT_ANSWER'] = $data['INCORRECT_ANSWER'];
				$dataR['MARKS_OBTAINED'] = $data['MARKS_OBTAINED'];

				$dataR['MARKS_PER'] = $data['MARKS_PER'];
				$dataR['RESULT_STATUS'] = $data['RESULT_STATUS'];
				$dataR['GRADE'] = $data['GRADE'];
				//$dataR['APPLY_FOR_CERTIFICATE'] = $data['APPLY_FOR_CERTIFICATE'];
				//	$dataR['FINAL_EXAM_STATUS'] = $data['FINAL_EXAM_STATUS'];

				//$dataR['LANG_ID'] = $data['LANG_ID'];

				//	$dataR['ORDER_FOR_CERTIFICATE'] = $data['ORDER_FOR_CERTIFICATE'];
				//	$dataR['CERTIFICATE_APPLY_ID'] = $data['CERTIFICATE_APPLY_ID'];
				$dataR['DATE'] = date("d-m-Y", strtotime($data['CREATED_ON']));
				$dataR['EXAM_TYPE'] = "";
				if ($data['EXAM_TYPE'] == '1') {
					$dataR['EXAM_TYPE'] = "ONLINE";
				} else if ($data['EXAM_TYPE'] == '3') {
					$dataR['EXAM_TYPE'] = "OFFLINE";
				} else {
					$dataR['EXAM_TYPE'] = "";
				}

				$dataS[] = $dataR;
			}
			$dataT['success'] = true;
			$dataT['result'] = $dataS;
		} else {
			$dataT['message'] = 'No Exam Appeared.';
			$dataT['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataT;
	}

	public function ResetFinalExam($userid, $courseid)
	{
		$userid 	= 	parent::test($userid);
		$courseid 	= 	parent::test($courseid);

		$dataR = array();

		$sql = "UPDATE student_course_details SET FINAL_EXAM_STATUS = '0' WHERE STUDENT_ID = '$userid' AND INSTITUTE_COURSE_ID = '$courseid' AND DELETE_FLAG = 0 ";
		$res = parent::execQuery($sql);

		if ($res) {
			$dataR['message'] = 'Success! Exam Resets Successfully.';
			$dataR['success'] = true;
		} else {
			$dataR['message'] = 'Failed! Exam Resets Failed.';
			$dataR['success'] = false;
		}
		return $dataR;
	}

	//All Courses list after login
	public function getAllCoursesAfterLogin($userid, $section_id)
	{
		$userid 	= 	parent::test($userid);
		$section_id 	= 	parent::test($section_id);

		if ($section_id != '') {
			$cond = " AND SECTION_ID = '$section_id'";
		}
		$dataA = array();
		$dataB = '';

		$dataR = array();
		$dataS = array();
		$dataP = array();
		$dataC = array();
		$dataD = array();

		$dataE = array();
		$dataF = array();

		$sql = "SELECT A.INSTITUTE_COURSE_ID FROM student_course_details A WHERE A.STUDENT_ID = '$userid' AND A.DELETE_FLAG	= 0 ";
		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataA[] = $data['INSTITUTE_COURSE_ID'];
			}
		}
		if (!empty($dataA)) {
			$dataB = implode(',', $dataA);
		} else {
			$dataB = 0;
		}

		$sql1 = "SELECT A.COURSE_ID, A.COURSE_CODE,(SELECT AWARD FROM aicpe_course_awards WHERE AWARD_ID = A.COURSE_AWARD) AS COURSE_AWARD, A.COURSE_DURATION, A.COURSE_NAME, A.COURSE_DETAILS, A.COURSE_ELIGIBILITY, A.COURSE_FEES, CONCAT('https://ditrpself-study.com/uploads/course/material/',A.COURSE_ID,'/',A.COURSE_IMAGE) AS COURSE_IMAGE, SUBJECT, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',CERT_IMG) AS CERTIFIACTE_IMAGE, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',MARKSHEET_IMG) AS MARKSHEET_IMAGE, COURSE_MRP, FREE_COURSE_STATUS FROM aicpe_courses A WHERE  A.COURSE_ID NOT IN ($dataB) AND A.DELETE_FLAG = 0 AND  FREE_COURSE_STATUS = 0  $cond";
		//exit();
		$res1 = parent::execQuery($sql1);

		$sql2 = "SELECT SLIDER_ID,CONCAT('https://ditrpself-study.com/uploads/coursebanners/',SLIDER_ID,'/',SLIDER_IMG) AS SLIDER_IMG, COURSE_ID, URL FROM course_banner WHERE DELETE_FLAG = 0 ";
		$res2 = parent::execQuery($sql2);

		$sql3 = "SELECT A.COURSE_ID, A.COURSE_CODE,(SELECT AWARD FROM aicpe_course_awards WHERE AWARD_ID = A.COURSE_AWARD) AS COURSE_AWARD, A.COURSE_DURATION, A.COURSE_NAME, A.COURSE_DETAILS, A.COURSE_ELIGIBILITY, A.COURSE_FEES, CONCAT('https://ditrpself-study.com/uploads/course/material/',A.COURSE_ID,'/',A.COURSE_IMAGE) AS COURSE_IMAGE, SUBJECT, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',CERT_IMG) AS CERTIFIACTE_IMAGE, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',MARKSHEET_IMG) AS MARKSHEET_IMAGE, COURSE_MRP, FREE_COURSE_STATUS FROM aicpe_courses A WHERE  A.COURSE_ID NOT IN ($dataB) AND A.DELETE_FLAG = 0 AND FREE_COURSE_STATUS = 1  $cond";
		//exit();
		$res3 = parent::execQuery($sql3);




		if (($res1 && $res1->num_rows > 0) || ($res2 && $res2->num_rows > 0)) {
			while ($data1 = $res1->fetch_assoc()) {
				$dataR['COURSE_ID'] = $data1['COURSE_ID'];
				$dataR['COURSE_CODE'] = $data1['COURSE_CODE'];
				$dataR['COURSE_AWARD'] = $data1['COURSE_AWARD'];
				$dataR['COURSE_DURATION'] = $data1['COURSE_DURATION'];
				$dataR['COURSE_NAME'] = $data1['COURSE_NAME'];
				$dataR['COURSE_DETAILS'] = html_entity_decode($data1['COURSE_DETAILS']);
				$dataR['COURSE_ELIGIBILITY'] = html_entity_decode($data1['COURSE_ELIGIBILITY']);
				$dataR['COURSE_FEES'] = $data1['COURSE_FEES'];
				$dataR['COURSE_IMAGE'] = $data1['COURSE_IMAGE'];

				$dataR['SUBJECT'] = html_entity_decode($data['SUBJECT']);
				$dataR['CERTIFIACTE_IMAGE'] = $data['CERTIFIACTE_IMAGE'];
				$dataR['MARKSHEET_IMAGE'] = $data['MARKSHEET_IMAGE'];
				$dataR['COURSE_MRP'] = $data['COURSE_MRP'];
				$dataR['FREE_COURSE_STATUS'] = $data['FREE_COURSE_STATUS'];

				$dataP[] = $dataR;
			}

			while ($data2 = $res2->fetch_assoc()) {
				$dataD['SLIDER_ID'] = $data2['SLIDER_ID'];
				$dataD['SLIDER_IMG'] = $data2['SLIDER_IMG'];
				$dataD['COURSE_ID'] = $data2['COURSE_ID'];
				$dataD['URL'] = $data2['URL'];

				$dataC[] = $dataD;
			}

			while ($data3 = $res3->fetch_assoc()) {
				$dataE['COURSE_ID'] = $data3['COURSE_ID'];
				$dataE['COURSE_CODE'] = $data3['COURSE_CODE'];
				$dataE['COURSE_AWARD'] = $data3['COURSE_AWARD'];
				$dataE['COURSE_DURATION'] = $data3['COURSE_DURATION'];
				$dataE['COURSE_NAME'] = $data3['COURSE_NAME'];
				$dataE['COURSE_DETAILS'] = html_entity_decode($data3['COURSE_DETAILS']);
				$dataE['COURSE_ELIGIBILITY'] = html_entity_decode($data3['COURSE_ELIGIBILITY']);
				$dataE['COURSE_FEES'] = $data3['COURSE_FEES'];
				$dataE['COURSE_IMAGE'] = $data3['COURSE_IMAGE'];

				$dataE['SUBJECT'] = html_entity_decode($data3['SUBJECT']);
				$dataE['CERTIFIACTE_IMAGE'] = $data3['CERTIFIACTE_IMAGE'];
				$dataE['MARKSHEET_IMAGE'] = $data3['MARKSHEET_IMAGE'];
				$dataE['COURSE_MRP'] = $data3['COURSE_MRP'];
				$dataE['FREE_COURSE_STATUS'] = $data3['FREE_COURSE_STATUS'];

				$dataF[] = $dataE;
			}

			$dataS['result'] = $dataP;
			$dataS['course_banner'] = $dataC;
			$dataS['free_course'] = $dataF;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Course Is Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	public function ApproveFinalCertificate($userid, $courseid, $examresultid)
	{

		$errors = array();  // array to hold validation errors
		$datafinal = array();

		$userid 	= 	parent::test($userid);
		$courseid 	= 	parent::test($courseid);
		$examresultid 	= 	parent::test($examresultid);

		$sql = "SELECT * from exam_result WHERE EXAM_RESULT_ID = '$examresultid' AND  STUDENT_ID = '$userid' AND DELETE_FLAG = '0' ORDER BY EXAM_RESULT_ID ASC";
		$res = parent::execQuery($sql);
		$data = $res->fetch_assoc();

		$INSTITUTE_ID = $data['INSTITUTE_ID'];
		$EXAM_ID = $data['EXAM_ID'];
		$EXAM_TITLE = $data['EXAM_TITLE'];
		$GRADE = $data['GRADE'];
		$MARKS_PER = $data['MARKS_PER'];
		$RESULT_STATUS = $data['RESULT_STATUS'];

		$MARKS_OBTAINED = $data['MARKS_OBTAINED'];
		$EXAM_TOTAL_MARKS = $data['EXAM_TOTAL_MARKS'];
		$EXAM_PASSING_MARKS = $data['EXAM_PASSING_MARKS'];

		$tableName 	= "certificate_requests";
		$tabFields 	= "(CERTIFICATE_REQUEST_ID,EXAM_RESULT_ID,STUDENT_ID,INSTITUTE_ID,AICPE_COURSE_ID,EXAM_TITLE,GRADE,MARKS_PER,RESULT_STATUS,ACTIVE,DELETE_FLAG,CREATED_BY, CREATED_ON,MARKS_OBTAINED,EXAM_TOTAL_MARKS,EXAM_PASSING_MARKS)";

		$insertVals	= "(NULL,'$examresultid','$userid','$INSTITUTE_ID','$courseid','$EXAM_TITLE','$GRADE','$MARKS_PER','$RESULT_STATUS','1','0','',NOW(),'$MARKS_OBTAINED','$EXAM_TOTAL_MARKS','$EXAM_PASSING_MARKS')";

		$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
		$exSql		= parent::execQuery($insertSql);

		$CERTIFICATE_REQUEST_ID = parent::last_id();

		if ($exSql) {
			$tableName6 	= "exam_result";
			$setValues6 	= "APPLY_FOR_CERTIFICATE='1',CERTIFICATE_APPLY_ID ='$CERTIFICATE_REQUEST_ID' ";
			$whereClause6 = " WHERE EXAM_RESULT_ID='$examresultid'";
			$updateSql6	= parent::updateData($tableName6, $setValues6, $whereClause6);
			$exSql6	= parent::execQuery($updateSql6);

			parent::commit();
			$datafinal['success'] = true;
			$datafinal['message'] = 'Success! You Successfully Apply For Certificate And Marksheet!';
		} else {
			//	parent::rollback();
			$datafinal['message'] = 'Sorry! Something went wrong!';
			$datafinal['success'] = false;
		}
		return $datafinal;
	}

	public function OrderFinalCertificate($userid, $courseid, $examresultid, $cert_apply_id)
	{

		$errors = array();  // array to hold validation errors
		$datafinal = array();

		$userid 	= 	parent::test($userid);
		$courseid 	= 	parent::test($courseid);
		$examresultid 	= 	parent::test($examresultid);
		$cert_apply_id 	= 	parent::test($cert_apply_id);

		$tableName 	= "certificates_details";
		$setValues 	= "ORDER_FOR_CERTIFICATE='1' ";
		$whereClause = " WHERE CERTIFICATE_REQUEST_ID='$cert_apply_id'";
		$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
		$exSql	= parent::execQuery($updateSql);

		if ($exSql) {
			$tableName6 	= "exam_result";
			$setValues6 	= "ORDER_FOR_CERTIFICATE='1'";
			$whereClause6 = " WHERE EXAM_RESULT_ID='$examresultid'";
			$updateSql6	= parent::updateData($tableName6, $setValues6, $whereClause6);
			$exSql6	= parent::execQuery($updateSql6);

			parent::commit();
			$datafinal['success'] = true;
			$datafinal['message'] = 'Success! You Successfully Order For Certificate And Marksheet!';
		} else {
			//	parent::rollback();
			$datafinal['message'] = 'Sorry! Something went wrong!';
			$datafinal['success'] = false;
		}

		return $datafinal;
	}

	public function Notification()
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();
		$sql = "SELECT NOTIFICATION_ID, NOTIFICATION_NAME,NOTIFICATION_DESC,CONCAT('https://ditrpself-study.com/uploads/notification/',NOTIFICATION_ID,'/',NOTIFICATION_IMG) AS NOTIFICATION_IMG FROM notifications WHERE DELETE_FLAG = 0 ";
		$res = parent::execQuery($sql);


		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['NOTIFICATION_ID'] = $data['NOTIFICATION_ID'];
				$dataR['NOTIFICATION_NAME'] = $data['NOTIFICATION_NAME'];
				$dataR['NOTIFICATION_DESC'] = html_entity_decode($data['NOTIFICATION_DESC']);
				$dataR['NOTIFICATION_IMG'] = $data['NOTIFICATION_IMG'];

				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Notifications Is Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	public function OurAchivers($state)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();
		$sql = "SELECT NOTIFICATION_ID, NOTIFICATION_NAME,STATE,CITY,CONCAT('https://ditrpself-study.com/uploads/ourachivers/',NOTIFICATION_ID,'/',NOTIFICATION_IMG) AS NOTIFICATION_IMG FROM ourachivers WHERE DELETE_FLAG = 0 AND STATE LIKE '$state%'";
		$res = parent::execQuery($sql);


		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['ACHIVER_ID'] = $data['NOTIFICATION_ID'];
				$dataR['ACHIVER_NAME'] = $data['NOTIFICATION_NAME'];
				$dataR['STATE'] = $data['STATE'];
				$dataR['CITY'] = $data['CITY'];

				$dataR['NOTIFICATION_IMG'] = $data['NOTIFICATION_IMG'];

				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Achiver Is Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	public function CourseTestimonials($courseid)
	{
		$courseid = parent::test($courseid);

		$dataR = array();
		$dataS = array();
		$sql = "SELECT FILE_ID,COURSE_ID, FILE_LABEL, VIDEO_LINK FROM aicpe_course_testimonial WHERE COURSE_ID = '$courseid' AND DELETE_FLAG	= 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR[] = $data;
			}
			$dataS['result'] = $dataR;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Testimonial Is Available For This Course!!!';
			$dataS['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataS;
	}
	//exam instruction
	public function GetExamInstruction($courseid, $userid)
	{
		$courseid   = parent::test($courseid);
		$userid     = parent::test($userid);

		$dataR = array();
		$dataS = array();
		$sql = "SELECT A.*,get_stud_photo(C.STUDENT_ID) AS STUD_PHOTO, C.STUDENT_CODE, C.STUDENT_FNAME,C.STUDENT_DOB,C.STUDENT_GENDER,C.STUDENT_MOBILE,get_institute_name(C.INSTITUTE_ID) AS INSTITUTE_NAME  FROM student_course_details A LEFT JOIN student_details C ON C.STUDENT_ID = A.STUDENT_ID WHERE A.STUDENT_ID = '$userid' AND A.DELETE_FLAG	= 0 ";

		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {

				$dataR['STUDENT_CODE']   = $data['STUDENT_CODE'];
				$dataR['STUDENT_FNAME']  = $data['STUDENT_FNAME'];
				$dataR['STUDENT_DOB']    = $data['STUDENT_DOB'];
				$dataR['STUDENT_GENDER'] = $data['STUDENT_GENDER'];
				$dataR['STUDENT_MOBILE'] = $data['STUDENT_MOBILE'];
				$dataR['INSTITUTE_NAME'] = $data['INSTITUTE_NAME'];

				$dataR['INSTITUTE_COURSE_ID'] = $this->get_inst_course_id($courseid, $data['INSTITUTE_ID']);

				$dataR['STUD_PHOTO']    = HTTP_HOST . 'uploads/student/' . $data['STUDENT_ID'] . '/' . $data['STUD_PHOTO'];

				$sql1 = "SELECT B.EXAM_ID, B.EXAM_TITLE, B.TOTAL_MARKS, B.TOTAL_QUESTIONS, B.MARKS_PER_QUE, B.PASSING_MARKS, B.EXAM_TIME FROM exam_structure B WHERE B.COURSE_ID = '$courseid' AND B.DELETE_FLAG = 0 ";
				$res1 = parent::execQuery($sql1);
				if ($res1 && $res1->num_rows > 0) {
					while ($data1 = $res1->fetch_assoc()) {
						$dataR['EXAM_TITLE']        = $data1['EXAM_TITLE'];
						$dataR['TOTAL_MARKS']       = $data1['TOTAL_MARKS'];
						$dataR['TOTAL_QUESTIONS']   = $data1['TOTAL_QUESTIONS'];
						$dataR['MARKS_PER_QUE']     = $data1['MARKS_PER_QUE'];
						$dataR['PASSING_MARKS']     = $data1['PASSING_MARKS'];
						$dataR['EXAM_TIME']         = $data1['EXAM_TIME'];
					}
				}

				//  $dataS[] = $dataR;

			}
		} else {
			$dataR['message'] = 'Exam Section Not Activate For This Student';
			$dataR['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataR;
	}

	//show certificate
	public function ShowCertificate($courseid, $userid, $cert_apply_id)
	{
		$courseid   = parent::test($courseid);
		$userid     = parent::test($userid);
		$cert_apply_id     = parent::test($cert_apply_id);

		$link = HTTP_HOST . "student-certificate&checkstud=$userid&certreq=$cert_apply_id&course=$courseid";
		$dataS = array();
		if ($link !== '') {
			$dataS['CERTIFICATE_FILE']   = $link;
		} else {
			$dataS['message'] = 'No Certificate Generated';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	public function ShowMarksheet($courseid, $userid, $cert_apply_id)
	{
		$courseid   = parent::test($courseid);
		$userid     = parent::test($userid);
		$cert_apply_id     = parent::test($cert_apply_id);

		$link = HTTP_HOST . "student-marksheet&checkstud=$userid&certreq=$cert_apply_id&course=$courseid";
		$dataS = array();
		if ($link !== '') {
			$dataS['MARKSHEET_FILE']   = $link;
		} else {
			$dataS['message'] = 'No Certificate Generated';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	//online payment history
	public function OnlineTrasactionList($userid)
	{
		$userid 	= 	parent::test($userid);

		$dataR = array();

		$dataS = array();

		$dataP = array();

		$sql = "SELECT A.*, B.COURSE_NAME, B.COURSE_CODE FROM online_payments A LEFT JOIN aicpe_courses B ON A.COURSE_ID = B.COURSE_ID WHERE A.USER_ID = '$userid' AND A.USER_ROLE = 4 AND A.DELETE_FLAG = 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['PAYMENT_ID']        = $data['PAYMENT_ID'];
				$dataR['TRANSACTION_TYPE']  = $data['TRANSACTION_TYPE'];
				$dataR['TRANSACTION_NO']    = $data['TRANSACTION_ID'];
				$dataR['PAYMENT_AMOUNT']    = $data['PAYMENT_AMOUNT'];
				$dataR['PAYMENT_MODE']      = $data['PAYMENT_MODE'];
				$dataR['PAYMENT_DATE']      = $data['PAYMENT_DATE'];
				$dataR['PAYMENT_STATUS']    = $data['PAYMENT_STATUS'];

				$dataR['REFERAL_CODE']    = $data['REFERAL_CODE'];
				$dataR['COURSE_ID']       = $data['COURSE_ID'];
				$dataR['COURSE_NAME']     = $data['COURSE_NAME'];
				$dataR['COURSE_CODE']     = $data['COURSE_CODE'];
				$dataS[] = $dataR;
				//$dataS['success'] = true;

			}
			$dataP['result'] = $dataS;
			$dataP['success'] = true;
		} else {

			$dataP['success'] = false;
			$dataP['message']  = 'No Online Transaction Available.';
		}
		return $dataP;
	}

	///search 
	public function getAllCoursesSearch($keyword)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();

		$dataA = array();
		$dataB = array();

		$dataE = array();
		$dataF = array();

		$sql = "SELECT COURSE_ID, COURSE_CODE,(SELECT AWARD FROM aicpe_course_awards WHERE AWARD_ID = COURSE_AWARD) AS COURSE_AWARD, COURSE_DURATION, COURSE_NAME, COURSE_DETAILS, COURSE_ELIGIBILITY, COURSE_FEES, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',COURSE_IMAGE) AS COURSE_IMAGE, SUBJECT, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',CERT_IMG) AS CERTIFIACTE_IMAGE, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',MARKSHEET_IMG) AS MARKSHEET_IMAGE,COURSE_MRP, FREE_COURSE_STATUS FROM aicpe_courses WHERE COURSE_NAME LIKE '$keyword%' AND DELETE_FLAG = 0 AND  FREE_COURSE_STATUS = 0";
		$res = parent::execQuery($sql);

		$sql1 = "SELECT SLIDER_ID,CONCAT('https://ditrpself-study.com/uploads/coursebanners/',SLIDER_ID,'/',SLIDER_IMG) AS SLIDER_IMG, COURSE_ID, URL FROM course_banner WHERE DELETE_FLAG = 0 ";
		$res1 = parent::execQuery($sql1);

		$sql2 = "SELECT COURSE_ID, COURSE_CODE,(SELECT AWARD FROM aicpe_course_awards WHERE AWARD_ID = COURSE_AWARD) AS COURSE_AWARD, COURSE_DURATION, COURSE_NAME, COURSE_DETAILS, COURSE_ELIGIBILITY, COURSE_FEES, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',COURSE_IMAGE) AS COURSE_IMAGE, SUBJECT, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',CERT_IMG) AS CERTIFIACTE_IMAGE, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',MARKSHEET_IMG) AS MARKSHEET_IMAGE,COURSE_MRP, FREE_COURSE_STATUS FROM aicpe_courses WHERE COURSE_NAME LIKE '$keyword%' AND DELETE_FLAG = 0 AND FREE_COURSE_STATUS = 1 ";
		$res2 = parent::execQuery($sql2);



		if (($res && $res->num_rows > 0) || ($res1 && $res1->num_rows > 0)) {
			while ($data = $res->fetch_assoc()) {
				$dataR['COURSE_ID'] = $data['COURSE_ID'];
				$dataR['COURSE_CODE'] = $data['COURSE_CODE'];
				$dataR['COURSE_AWARD'] = $data['COURSE_AWARD'];
				$dataR['COURSE_DURATION'] = $data['COURSE_DURATION'];
				$dataR['COURSE_NAME'] = $data['COURSE_NAME'];
				$dataR['COURSE_DETAILS'] = html_entity_decode($data['COURSE_DETAILS']);
				$dataR['COURSE_ELIGIBILITY'] = html_entity_decode($data['COURSE_ELIGIBILITY']);
				$dataR['COURSE_FEES'] = $data['COURSE_FEES'];
				$dataR['COURSE_IMAGE'] = $data['COURSE_IMAGE'];

				$dataR['SUBJECT'] = html_entity_decode($data['SUBJECT']);
				$dataR['CERTIFIACTE_IMAGE'] = $data['CERTIFIACTE_IMAGE'];
				$dataR['MARKSHEET_IMAGE'] = $data['MARKSHEET_IMAGE'];
				$dataR['COURSE_MRP'] = $data['COURSE_MRP'];
				$dataR['FREE_COURSE_STATUS'] = $data['FREE_COURSE_STATUS'];

				$dataP[] = $dataR;
			}

			while ($data1 = $res1->fetch_assoc()) {
				$dataA['SLIDER_ID'] = $data1['SLIDER_ID'];
				$dataA['SLIDER_IMG'] = $data1['SLIDER_IMG'];
				$dataA['COURSE_ID'] = $data1['COURSE_ID'];
				$dataA['URL'] = $data1['URL'];

				$dataB[] = $dataA;
			}

			while ($data2 = $res2->fetch_assoc()) {
				$dataE['COURSE_ID'] = $data2['COURSE_ID'];
				$dataE['COURSE_CODE'] = $data2['COURSE_CODE'];
				$dataE['COURSE_AWARD'] = $data2['COURSE_AWARD'];
				$dataE['COURSE_DURATION'] = $data2['COURSE_DURATION'];
				$dataE['COURSE_NAME'] = $data2['COURSE_NAME'];
				$dataE['COURSE_DETAILS'] = html_entity_decode($data2['COURSE_DETAILS']);
				$dataE['COURSE_ELIGIBILITY'] = html_entity_decode($data2['COURSE_ELIGIBILITY']);
				$dataE['COURSE_FEES'] = $data2['COURSE_FEES'];
				$dataE['COURSE_IMAGE'] = $data2['COURSE_IMAGE'];

				$dataE['SUBJECT'] = html_entity_decode($data2['SUBJECT']);
				$dataE['CERTIFIACTE_IMAGE'] = $data2['CERTIFIACTE_IMAGE'];
				$dataE['MARKSHEET_IMAGE'] = $data2['MARKSHEET_IMAGE'];
				$dataE['COURSE_MRP'] = $data2['COURSE_MRP'];
				$dataE['FREE_COURSE_STATUS'] = $data2['FREE_COURSE_STATUS'];

				$dataF[] = $dataE;
			}

			$dataS['result'] = $dataP;
			$dataS['course_banner'] = $dataB;
			$dataS['free_course'] = $dataF;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Course Is Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	public function getAllCoursesAfterLoginSearch($userid, $keyword)
	{
		$userid 	= 	parent::test($userid);

		$dataA = array();
		$dataB = '';

		$dataR = array();
		$dataS = array();
		$dataP = array();
		$dataC = array();
		$dataD = array();

		$dataE = array();
		$dataF = array();

		$sql = "SELECT A.INSTITUTE_COURSE_ID FROM student_course_details A WHERE A.STUDENT_ID = '$userid' AND A.DELETE_FLAG	= 0 ";
		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataA[] = $data['INSTITUTE_COURSE_ID'];
			}
		}
		if (!empty($dataA)) {
			$dataB = implode(',', $dataA);
		} else {
			$dataB = 0;
		}

		$sql1 = "SELECT A.COURSE_ID, A.COURSE_CODE,(SELECT AWARD FROM aicpe_course_awards WHERE AWARD_ID = A.COURSE_AWARD) AS COURSE_AWARD, A.COURSE_DURATION, A.COURSE_NAME, A.COURSE_DETAILS, A.COURSE_ELIGIBILITY, A.COURSE_FEES, CONCAT('https://ditrpself-study.com/uploads/course/material/',A.COURSE_ID,'/',A.COURSE_IMAGE) AS COURSE_IMAGE, SUBJECT, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',CERT_IMG) AS CERTIFIACTE_IMAGE, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',MARKSHEET_IMG) AS MARKSHEET_IMAGE,COURSE_MRP, FREE_COURSE_STATUS FROM aicpe_courses A WHERE  A.COURSE_ID NOT IN ($dataB) AND COURSE_NAME LIKE '$keyword%' AND A.DELETE_FLAG = 0 AND  FREE_COURSE_STATUS = 0";
		//exit();
		$res1 = parent::execQuery($sql1);

		$sql2 = "SELECT SLIDER_ID,CONCAT('https://ditrpself-study.com/uploads/coursebanners/',SLIDER_ID,'/',SLIDER_IMG) AS SLIDER_IMG, COURSE_ID, URL FROM course_banner WHERE DELETE_FLAG = 0 ";
		$res2 = parent::execQuery($sql2);

		$sql3 = "SELECT A.COURSE_ID, A.COURSE_CODE,(SELECT AWARD FROM aicpe_course_awards WHERE AWARD_ID = A.COURSE_AWARD) AS COURSE_AWARD, A.COURSE_DURATION, A.COURSE_NAME, A.COURSE_DETAILS, A.COURSE_ELIGIBILITY, A.COURSE_FEES, CONCAT('https://ditrpself-study.com/uploads/course/material/',A.COURSE_ID,'/',A.COURSE_IMAGE) AS COURSE_IMAGE, SUBJECT, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',CERT_IMG) AS CERTIFIACTE_IMAGE, CONCAT('https://ditrpself-study.com/uploads/course/material/',COURSE_ID,'/',MARKSHEET_IMG) AS MARKSHEET_IMAGE,COURSE_MRP, FREE_COURSE_STATUS FROM aicpe_courses A WHERE  A.COURSE_ID NOT IN ($dataB) AND COURSE_NAME LIKE '$keyword%' AND A.DELETE_FLAG = 0 AND  FREE_COURSE_STATUS = 1";
		//exit();
		$res3 = parent::execQuery($sql3);




		if (($res1 && $res1->num_rows > 0) || ($res2 && $res2->num_rows > 0)) {
			while ($data1 = $res1->fetch_assoc()) {
				$dataR['COURSE_ID'] = $data1['COURSE_ID'];
				$dataR['COURSE_CODE'] = $data1['COURSE_CODE'];
				$dataR['COURSE_AWARD'] = $data1['COURSE_AWARD'];
				$dataR['COURSE_DURATION'] = $data1['COURSE_DURATION'];
				$dataR['COURSE_NAME'] = $data1['COURSE_NAME'];
				$dataR['COURSE_DETAILS'] = html_entity_decode($data1['COURSE_DETAILS']);
				$dataR['COURSE_ELIGIBILITY'] = html_entity_decode($data1['COURSE_ELIGIBILITY']);
				$dataR['COURSE_FEES'] = $data1['COURSE_FEES'];
				$dataR['COURSE_IMAGE'] = $data1['COURSE_IMAGE'];

				$dataR['SUBJECT'] = html_entity_decode($data['SUBJECT']);
				$dataR['CERTIFIACTE_IMAGE'] = $data['CERTIFIACTE_IMAGE'];
				$dataR['MARKSHEET_IMAGE'] = $data['MARKSHEET_IMAGE'];

				$dataR['COURSE_MRP'] = $data['COURSE_MRP'];
				$dataR['FREE_COURSE_STATUS'] = $data['FREE_COURSE_STATUS'];

				$dataP[] = $dataR;
			}

			while ($data2 = $res2->fetch_assoc()) {
				$dataD['SLIDER_ID'] = $data2['SLIDER_ID'];
				$dataD['SLIDER_IMG'] = $data2['SLIDER_IMG'];
				$dataD['COURSE_ID'] = $data2['COURSE_ID'];
				$dataD['URL'] = $data2['URL'];

				$dataC[] = $dataD;
			}

			while ($data3 = $res3->fetch_assoc()) {
				$dataE['COURSE_ID'] = $data3['COURSE_ID'];
				$dataE['COURSE_CODE'] = $data3['COURSE_CODE'];
				$dataE['COURSE_AWARD'] = $data3['COURSE_AWARD'];
				$dataE['COURSE_DURATION'] = $data3['COURSE_DURATION'];
				$dataE['COURSE_NAME'] = $data3['COURSE_NAME'];
				$dataE['COURSE_DETAILS'] = html_entity_decode($data3['COURSE_DETAILS']);
				$dataE['COURSE_ELIGIBILITY'] = html_entity_decode($data3['COURSE_ELIGIBILITY']);
				$dataE['COURSE_FEES'] = $data3['COURSE_FEES'];
				$dataE['COURSE_IMAGE'] = $data3['COURSE_IMAGE'];

				$dataE['SUBJECT'] = html_entity_decode($data3['SUBJECT']);
				$dataE['CERTIFIACTE_IMAGE'] = $data3['CERTIFIACTE_IMAGE'];
				$dataE['MARKSHEET_IMAGE'] = $data3['MARKSHEET_IMAGE'];

				$dataE['COURSE_MRP'] = $data3['COURSE_MRP'];

				$dataE['FREE_COURSE_STATUS'] = $data3['FREE_COURSE_STATUS'];

				$dataF[] = $dataE;
			}

			$dataS['result'] = $dataP;
			$dataS['course_banner'] = $dataC;
			$dataS['free_course'] = $dataF;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Course Is Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	//become a volunteer
	public function becomeaVolunteer($userid)
	{
		$userid 	= 	parent::test($userid);
		$dataR = array();

		$sql = "UPDATE student_details SET VOLUNTEER = '2' WHERE STUDENT_ID = '$userid' AND DELETE_FLAG = 0 ";
		$res = parent::execQuery($sql);

		if ($res) {
			$dataR['message'] = 'Success! Your Volunteer Request Successfully Send To DITRP. Our Team Will Contact You Soon.';
			$dataR['success'] = true;
		} else {
			$dataR['message'] = 'Failed! Volunteer Request Failed. Please Try Again';
			$dataR['success'] = false;
		}
		return $dataR;
	}

	//apply coupon
	public function applyCoupon($couponcode)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();
		$dataT = array();
		$sql = "SELECT COUPON_ID, COUPON_NAME,DISCOUNT_PRICE FROM discount_coupons WHERE DELETE_FLAG = 0 AND COUPON_NAME = '$couponcode' ";
		$res = parent::execQuery($sql);


		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['COUPON_ID'] = $data['COUPON_ID'];
				$dataR['COUPON_NAME'] = $data['COUPON_NAME'];
				$dataR['DISCOUNT_PRICE'] = $data['DISCOUNT_PRICE'];

				$dataP[] = $dataR;
			}
			$dataS = $dataR;
			$dataS['success'] = true;
			$dataS['message'] = 'Coupon Code Applied Successfully.';
		} else {
			$dataS['message'] = 'Your Code Is Not Valid. Please Enter Proper Code To Get Discount.';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	public function getParcelDetails($courseid, $userid, $cert_apply_id)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();

		$sql = "SELECT DISPATCH_STATUS,RECIEPT_NO,DISPATCH_DATE, DISPATCH_MODE, COMPANY_NAME, COURIER_LINK FROM certificates_details WHERE DELETE_FLAG = 0 AND CERTIFICATE_REQUEST_ID = '$cert_apply_id' AND AICPE_COURSE_ID = '$courseid' AND STUDENT_ID = '$userid' ";
		$res = parent::execQuery($sql);


		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['DISPATCH_STATUS'] = $data['DISPATCH_STATUS'];
				$dataR['RECIEPT_NO'] = $data['RECIEPT_NO'];
				$dataR['DISPATCH_DATE'] = $data['DISPATCH_DATE'];
				$dataR['DISPATCH_MODE'] = $data['DISPATCH_MODE'];
				$dataR['COMPANY_NAME'] = $data['COMPANY_NAME'];
				$dataR['COURIER_LINK'] = $data['COURIER_LINK'];

				$dataP[] = $dataR;
			}
			//$dataS['result'] = $dataP;
			$dataS = $dataR;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'Parcel Is Not Dispatch Till Now.';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	public function checkVolunteerStatus($userid)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();

		$sql = "SELECT VOLUNTEER FROM student_details WHERE DELETE_FLAG = 0 AND STUDENT_ID = '$userid' ";
		$res = parent::execQuery($sql);

		$link = "https://ditrpself-study.com/volunteer-certificate&studentid=$userid";

		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['VOLUNTEER'] = $data['VOLUNTEER'];
				if ($dataR['VOLUNTEER'] == 1) {
					$dataR['CERTIFICATE_LINK'] = $link;
				}

				$dataP[] = $dataR;
			}
			//$dataS['result'] = $dataP;
			$dataS = $dataR;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'Something is wrong.';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	//new apis
	public function getOnlineClasses($institute_id, $inst_course_id)
	{
		$dataR = array();
		$dataS = array();
		$dataP = array();
		$sql = "SELECT * FROM online_classes WHERE delete_flag = 0 AND inst_id =  '$institute_id' AND course_id IN ($inst_course_id) AND NOW() <= expirydate ORDER BY id DESC";
		$res = parent::execQuery($sql);


		if ($res && $res->num_rows > 0) {
			$i = 0;
			while ($data = $res->fetch_assoc()) {
				$dataR['id'] = $data['id'];
				$dataR['inst_id'] = $data['inst_id'];
				$dataR['title'] = $data['title'];
				$dataR['link'] = $data['link'];
				$dataR['course_id'] = $data['course_id'];
				$course_name = parent::get_inst_course_name($data['course_id']);
				$dataR['course_name'] = $course_name;
				$dataR['description'] = $data['description'];

				if ($i % 2 == 0) {
					$dataR['BCK_IMAGE'] = HTTP_HOST . 'resources/list1.png';
				}
				if ($i % 2 == 1) {
					$dataR['BCK_IMAGE'] = HTTP_HOST . 'resources/list2.png';
				}
				if ($i % 3 == 0) {
					$dataR['BCK_IMAGE'] = HTTP_HOST . 'resources/list3.png';
				}
				if ($i % 3 == 1) {
					$dataR['BCK_IMAGE'] = HTTP_HOST . 'resources/list4.png';
				}
				$i++;
				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Online Classes Links Are Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}
	public function getStudentPhotos($userid)
	{
		$userid 	= 	parent::test($userid);

		$dataR = array();

		$dataS = array();

		$dataP = array();

		$sql = "SELECT * FROM student_files WHERE STUDENT_ID = '$userid' AND DELETE_FLAG = 0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['FILE_ID'] = $data['FILE_ID'];
				$dataR['STUDENT_ID'] = $data['STUDENT_ID'];
				$dataR['FILE_NAME'] = $data['FILE_NAME'];
				$dataR['FILE_MIME'] = $data['FILE_MIME'];
				$dataR['FILE_LABEL'] = $data['FILE_LABEL'];
				$dataR['FILE_CATEGORY'] = $data['FILE_CATEGORY'];
				$dataR['FILE_DESC'] = $data['FILE_DESC'];
				$dataS[] = $dataR;
				//$dataS['success'] = true;

			}
			$dataP['result'] = $dataS;
			$dataP['success'] = true;
		} else {

			$dataP['success'] = false;
			$dataP['message']  = 'No Wallet Transactions Available.';
		}
		return $dataP;
	}

	//Institute Logo And Title
	public function getInstituteLogo($inst_id)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();
		$sql = "SELECT * FROM logo_management WHERE delete_flag = 0";
		$res = parent::execQuery($sql);

		$inst_name = $this->get_institute_name($inst_id);

		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['id'] = $data['id'];
				$dataR['name'] = $inst_name;
				$dataR['image'] = $data['image'];
				$dataR['path'] = HTTP_HOST . 'uploads/logo/' . $data['id'] . '/' . $data['image'];
				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Data Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	//advertise 
	public function getAdvertiseList($institute_id)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();
		$sql = "SELECT * FROM ims_advertise_popup WHERE delete_flag = 0 AND inst_id = $institute_id AND active = 1 ORDER BY id DESC";
		$res = parent::execQuery($sql);


		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['id'] = $data['id'];
				$dataR['name'] = $data['name'];
				$dataR['link'] = $data['link'];
				$dataR['image'] = $data['image'];
				$dataR['path'] = HTTP_HOST . 'uploads/ims_advertise/' . $data['id'] . '/' . $data['image'];
				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Data Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	//Today's Birthday list
	public function getTodaysBirthday($institute_id)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();
		$month	 	= isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
		$day	 	= isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d');
		$sql = "SELECT A.*,DATE_FORMAT(A.STUDENT_DOB, '%d-%m-%Y') AS DOB_FORMATTED,DAY(A.STUDENT_DOB) AS DOB_DAY, MONTH(A.STUDENT_DOB) AS DOB_MONTH, B.USER_NAME, B.USER_LOGIN_ID,get_stud_photo(A.STUDENT_ID) as STUDENT_PHOTO FROM student_details A LEFT JOIN user_login_master B ON A.STUDENT_ID =B.USER_ID AND B.USER_ROLE=4 WHERE A.DELETE_FLAG=0 AND MONTH(A.STUDENT_DOB) = '$month' AND DAY(A.STUDENT_DOB) = '$day' AND A.INSTITUTE_ID = '$institute_id'";
		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['STUDENT_ID'] = $data['STUDENT_ID'];
				$dataR['STUDENT_FNAME'] = $data['STUDENT_FNAME'];
				$dataR['STUDENT_MNAME'] = $data['STUDENT_MNAME'];
				$dataR['STUDENT_LNAME'] = $data['STUDENT_LNAME'];

				$dataR['STUDENT_MOBILE'] = $data['STUDENT_MOBILE'];
				$dataR['DOB_FORMATTED'] = $data['DOB_FORMATTED'];

				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No One Have A Birthday Today';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	//This Month Birthday list
	public function getBirthdayThisMonth($institute_id)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();
		$month	 	= isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');

		$sql = "SELECT A.*,DATE_FORMAT(A.STUDENT_DOB, '%d-%m-%Y') AS DOB_FORMATTED,DAY(A.STUDENT_DOB) AS DOB_DAY, MONTH(A.STUDENT_DOB) AS DOB_MONTH, B.USER_NAME, B.USER_LOGIN_ID,get_stud_photo(A.STUDENT_ID) as STUDENT_PHOTO FROM student_details A LEFT JOIN user_login_master B ON A.STUDENT_ID =B.USER_ID AND B.USER_ROLE=4 WHERE A.DELETE_FLAG=0 AND MONTH(A.STUDENT_DOB) = '$month' AND A.INSTITUTE_ID = '$institute_id'";
		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['STUDENT_ID'] = $data['STUDENT_ID'];
				$dataR['STUDENT_FNAME'] = $data['STUDENT_FNAME'];
				$dataR['STUDENT_MNAME'] = $data['STUDENT_MNAME'];
				$dataR['STUDENT_LNAME'] = $data['STUDENT_LNAME'];

				$dataR['STUDENT_MOBILE'] = $data['STUDENT_MOBILE'];
				$dataR['DOB_FORMATTED'] = $data['DOB_FORMATTED'];

				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No One Have A Birthday This Month';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	public function getStudentBalanceFees($userid, $course_id)
	{
		$userid 	= 	parent::test($userid);
		$course_id 	= 	parent::test($course_id);
		$dataR = array();
		$dataS = array();
		$dataP = array();

		$sql = "SELECT STUD_COURSE_DETAIL_ID,TOTAL_COURSE_FEES FROM student_course_details WHERE STUDENT_ID='$userid' AND INSTITUTE_COURSE_ID='$course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$sql2 = "SELECT (" . $data['TOTAL_COURSE_FEES'] . " - SUM(B.FEES_PAID)) AS TOTAL_BALANCE_FEES FROM student_payments B WHERE B.STUD_COURSE_DETAIL_ID='" . $data['STUD_COURSE_DETAIL_ID'] . "' AND B.DELETE_FLAG=0";
			$res2 = parent::execQuery($sql2);
			if ($res2 && $res2->num_rows > 0) {
				$data3 = $res2->fetch_assoc();
				$dataR['TOTAL_COURSE_FEES'] = $data['TOTAL_COURSE_FEES'];
				$dataR['FEES_BALANCE'] = $data3['TOTAL_BALANCE_FEES'];
				$dataS[] = $dataR;
				//$dataS['success'] = true;
			}
			$dataP['result'] = $dataS;
			$dataP['success'] = true;
		} else {

			$dataP['success'] = false;
			$dataP['message']  = 'No Transactions Available.';
		}
		return $dataP;
	}
	//home page api
	public function homePageApi($userid)
	{
		$userid 	= 	parent::test($userid);
		$course_id 	= 	parent::test($course_id);
		$dataR = array();
		$dataS = array();
		$dataP = array();

		$dataA = array();
		$dataB = array();
		$dataC = array();
		$dataD = array();
		$dataE = array();
		$dataF = array();
		$dataG = array();
		$dataH = array();

		$dataI = array();
		$dataJ = array();

		$dataMQQ = array();
		$dataMQ = array();
		$stud_name = $this->get_stud_name1($userid);
		$month      = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
		$day        = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d');

		$sql = "SELECT STUD_COURSE_DETAIL_ID,TOTAL_COURSE_FEES FROM student_course_details WHERE STUDENT_ID='$userid' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);

		$sql3 = "SELECT A.*,DATE_FORMAT(A.STUDENT_DOB, '%d-%m-%Y') AS DOB_FORMATTED,DAY(A.STUDENT_DOB) AS DOB_DAY, MONTH(A.STUDENT_DOB) AS DOB_MONTH, B.USER_NAME, B.USER_LOGIN_ID ,get_stud_photo(A.STUDENT_ID) as STUDENT_PHOTO FROM student_details A LEFT JOIN user_login_master B ON A.STUDENT_ID =B.USER_ID AND B.USER_ROLE=4 WHERE A.DELETE_FLAG=0 AND MONTH(A.STUDENT_DOB) = '$month' AND DAY(A.STUDENT_DOB) = '$day'";
		$res3 = parent::execQuery($sql3);


		$sql5 = "SELECT * FROM wallet WHERE USER_ID = '$userid' AND USER_ROLE = 4 AND DELETE_FLAG = 0 ";
		$res5 = parent::execQuery($sql5);

		$sql6 = "SELECT A.*,get_institute_demo_count(A.INSTITUTE_ID) AS INSTITUTE_DEMO_COUNT, get_student_name(A.STUDENT_ID) AS STUDENT_NAME,get_student_code(A.STUDENT_ID) AS STUDENT_CODE , (SELECT C.EXAM_STATUS FROM exam_status_master C WHERE C.EXAM_STATUS_ID=A.EXAM_STATUS) AS EXAM_STATUS_NAME, (SELECT D.EXAM_TYPE FROM exam_types_master D WHERE D.EXAM_TYPE_ID=A.EXAM_TYPE) AS EXAM_TYPE_NAME, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON,'%d %M %Y') AS ACCOUNT_REGISTERED_DATE FROM student_course_details A LEFT JOIN user_login_master B ON A.STUDENT_ID=B.USER_ID  WHERE A.DELETE_FLAG=0 AND B.USER_ROLE=4 AND  A.STUDENT_ID = '$userid' ORDER BY  A.STUD_COURSE_DETAIL_ID DESC";
		$res6 = parent::execQuery($sql6);

		$inst_id = $this->get_student_institute_id($userid);

		$sql4 = "SELECT * FROM institute_files WHERE ACTIVE = 1 AND INSTITUTE_ID = '$inst_id' AND FILE_LABEL = 'logo'";
		$res4 = parent::execQuery($sql4);


		$sql7 = "SELECT * FROM marquee_tags WHERE delete_flag = 0 AND inst_id = '$inst_id'";
		$res7 = parent::execQuery($sql7);


		if ($res && $res->num_rows > 0 || $res3 && $res3->num_rows > 0 || $res4 && $res4->num_rows > 0 || $res5 && $res5->num_rows > 0 || $res6 && $res6->num_rows > 0 || $res7 && $res7->num_rows > 0) {
			$data = $res->fetch_assoc();
			$sql2 = "SELECT (" . $data['TOTAL_COURSE_FEES'] . " - SUM(B.FEES_PAID)) AS TOTAL_BALANCE_FEES FROM student_payments B WHERE B.STUD_COURSE_DETAIL_ID='" . $data['STUD_COURSE_DETAIL_ID'] . "' AND B.DELETE_FLAG=0";
			$res2 = parent::execQuery($sql2);
			if ($res2 && $res2->num_rows > 0) {
				$data3 = $res2->fetch_assoc();
				include_once('student.class.php');
				$student = new student();

				$ALL_COURSE_FEES = $TOTAL_FEES_PAID = $TOTAL_FEES_BALANCE = 0;
				$ALL_COURSE_FEES = $student->total_coursefess_student($userid);
				$TOTAL_FEES_PAID = $student->total_paidfess_student($userid);
				$TOTAL_FEES_BALANCE = $ALL_COURSE_FEES - $TOTAL_FEES_PAID;

				$dataR['TOTAL_COURSE_FEES'] = $ALL_COURSE_FEES;
				$dataR['FEES_BALANCE'] = "$TOTAL_FEES_BALANCE";
				$dataS[] = $dataR;
			}

			while ($data3 = $res3->fetch_assoc()) {
				$dataA['STUDENT_ID'] = $data3['STUDENT_ID'];
				$dataA['STUDENT_FNAME'] = $data3['STUDENT_FNAME'];
				$dataA['STUDENT_MNAME'] = $data3['STUDENT_MNAME'];
				$dataA['STUDENT_LNAME'] = $data3['STUDENT_LNAME'];

				$dataA['STUDENT_MOBILE'] = $data3['STUDENT_MOBILE'];
				$dataA['DOB_FORMATTED'] = $data3['DOB_FORMATTED'];

				$dataB[] = $dataA;
			}

			while ($data4 = $res4->fetch_assoc()) {
				$inst_name = $this->get_institute_name($inst_id);
				$dataC['INSTITUTE_ID '] = $data4['INSTITUTE_ID '];
				$dataC['name'] = $inst_name;
				$dataC['FILE_NAME'] = $data4['FILE_NAME'];
				$dataC['path'] = HTTP_HOST . 'uploads/institute/docs/' . $data4['INSTITUTE_ID'] . '/' . $data4['FILE_NAME'];
				$dataD[] = $dataC;
			}

			while ($data5 = $res5->fetch_assoc()) {
				$dataE['WALLET_ID'] = $data5['WALLET_ID'];
				$dataE['USER_ID'] = $data5['USER_ID'];
				$dataE['USER_ROLE'] = $data5['USER_ROLE'];
				$dataE['TOTAL_BALANCE'] = $data5['TOTAL_BALANCE'];
				$dataF[] = $dataE;
			}
			$i = 0;
			while ($data6 = $res6->fetch_assoc()) {
				$dataG['STUD_COURSE_DETAIL_ID'] = $data6['STUD_COURSE_DETAIL_ID'];
				$dataG['INSTITUTE_COURSE_ID'] = $data6['INSTITUTE_COURSE_ID'];
				$dataG['STUDENT_ID'] = $data6['STUDENT_ID'];
				$dataG['COURSE_NAME'] =  $this->get_inst_course_name($data6['INSTITUTE_COURSE_ID']);
				$dataG['COURSE_FEES'] = $data6['COURSE_FEES'];
				$dataG['EXAM_STATUS'] = $data6['EXAM_STATUS_NAME'];
				$dataG['JOINED_ON'] = $data6['ACCOUNT_REGISTERED_DATE'];

				$dataG['EXAM_STATUS_ID'] = $data6['EXAM_STATUS'];
				$dataG['EXAM_TYPE'] = $data6['EXAM_TYPE'];
				$exam_button_status = "0";
				if ($data6['EXAM_STATUS'] == '3') {
					$exam_button_status = "1";
				}
				$COURSE_INFO = $this->get_inst_course_info($data6['INSTITUTE_COURSE_ID']);
				$COURSE_ID = $MULTI_SUB_COURSE_ID = $TYPING_COURSE_ID = 0;
				if ($COURSE_INFO['COURSE_ID'] != '' && !empty($COURSE_INFO['COURSE_ID']) && $COURSE_INFO['COURSE_ID'] != '0') {
					$checkCertPrintAvilability = $this->getCertPrintAvailablity($COURSE_INFO['COURSE_ID'], $userid, $data6['INSTITUTE_ID']);
					$COURSE_ID = $COURSE_INFO['COURSE_ID'];
					if ($checkCertPrintAvilability == '1') {
						$exam_button_status = "1";
					};
				}
				if ($COURSE_INFO['MULTI_SUB_COURSE_ID'] != '' && !empty($COURSE_INFO['MULTI_SUB_COURSE_ID']) && $COURSE_INFO['MULTI_SUB_COURSE_ID'] != '0') {
					$checkCertPrintAvilability = $this->getCertPrintAvailablityMulti($COURSE_INFO['MULTI_SUB_COURSE_ID'], $userid, $data6['INSTITUTE_ID']);
					$MULTI_SUB_COURSE_ID = $COURSE_INFO['MULTI_SUB_COURSE_ID'];
					$exam_button_status = "1";
				}
				if ($COURSE_INFO['TYPING_COURSE_ID'] != '' && !empty($COURSE_INFO['TYPING_COURSE_ID']) && $COURSE_INFO['TYPING_COURSE_ID'] != '0') {
					$checkCertPrintAvilability = $this->getCertPrintAvailablityTyping($COURSE_INFO['TYPING_COURSE_ID'], $userid, $data6['INSTITUTE_ID']);
					$TYPING_COURSE_ID = $COURSE_INFO['TYPING_COURSE_ID'];
					$exam_button_status = "1";
				}

				$dataG['COURSE_ID'] = "$COURSE_ID";
				$dataG['MULTI_SUB_COURSE_ID'] = "$MULTI_SUB_COURSE_ID";
				$dataG['TYPING_COURSE_ID'] = "$TYPING_COURSE_ID";
				$inst_id = $data6['INSTITUTE_ID'];
				$dataG['INSTITUTE_ID'] = "$inst_id";
				if ($checkCertPrintAvilability == 1) {
					$dataG['CERTIFICATE'] = 1;
					$dataG['CERTIFICATE_PATH'] =  HTTP_HOST . "studentCertificate.php?user_id=" . $data6['STUDENT_ID'] . "&course=" . $COURSE_ID . "&course_multi_sub=" . $MULTI_SUB_COURSE_ID . "&course_typing=" . $TYPING_COURSE_ID . "&inst_id=" . $inst_id;
					$dataG['MARKSHEET_PATH']   =  HTTP_HOST . "studentMarksheet.php?user_id=" . $data6['STUDENT_ID'] . "&course=" . $COURSE_ID . "&course_multi_sub=" . $MULTI_SUB_COURSE_ID . "&course_typing=" . $TYPING_COURSE_ID . "&inst_id=" . $inst_id;
				} else {
					$dataG['CERTIFICATE'] = 0;
					$dataG['CERTIFICATE_PATH'] = '';
					$dataG['MARKSHEET_PATH'] = '';
				}
				$dataG['exam_button_status'] = "$exam_button_status";

				$dataG['ADMISSION_FORM'] =  HTTP_HOST . "studentAdmissionForm.php?id=" . $data6['STUDENT_ID'] . "&courseid=" . $data6['INSTITUTE_COURSE_ID'];
				$dataG['ID_CARD'] =  HTTP_HOST . "studentAdmissionIDCard.php?id=" . $data6['STUDENT_ID'] . "&courseid=" . $data6['INSTITUTE_COURSE_ID'];

				if ($i % 2 == 0) {
					$dataG['BCK_IMAGE'] = HTTP_HOST . 'resources/list1.png';
				}
				if ($i % 2 == 1) {
					$dataG['BCK_IMAGE'] = HTTP_HOST . 'resources/list2.png';
				}
				if ($i % 3 == 0) {
					$dataG['BCK_IMAGE'] = HTTP_HOST . 'resources/list3.png';
				}
				if ($i % 3 == 1) {
					$dataG['BCK_IMAGE'] = HTTP_HOST . 'resources/list4.png';
				}
				$i++;
				$dataH[] = $dataG;
			}

			while ($data7 = $res7->fetch_assoc()) {
				$dataP['marquee'] = html_entity_decode($data7['name']);
				//$dataMQQ = $dataMQ;
			}

			$stud_code = $this->get_stud_code($userid);
			$inst_name = $this->get_institute_name($inst_id);
			$dataJ['websiteLink'] = HTTP_HOST;
			$dataJ['verificationLink'] = HTTP_HOST . "studentVerification";

			$dataP['balance_fees'] = $dataS;
			$dataP['today_birthday'] = $dataB;
			$dataP['logo'] = $dataD;
			$dataP['wallet_amount'] = $dataF;
			$dataP['purchase_courses'] = $dataH;
			$dataP['links'] = $dataJ;
			$dataP['student_name'] = $stud_name;
			$dataP['resume_link'] = HTTP_HOST . "studentResume.php?id=" . $userid;
			$dataP['share_msg'] = "Hey Friends, - Myself " . $stud_name . " - I have got something awesome to share! I have been studying with " . $inst_name . " and I think you will love it too. Use my referral code: " . $stud_code . " when you sign up. Do not miss out on the benefits. Join me at " . HTTP_HOST . " . – Thankyou";
		}
		return $dataP;
	}

	// student payment fees history
	public function studentFeesHistory($userid, $stud_course_details_id)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();

		$sql = "SELECT A.*,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME, 
				get_institute_staff_name(A.STAFF_ID) AS INSTITUTE_STAFF_NAME,
				DATE_FORMAT(A.FEES_PAID_DATE, '%d %M %Y') as FEES_PAID_ON,
				get_student_name(A.STUDENT_ID) AS STUDENT_NAME
				FROM student_payments A 
				WHERE A.DELETE_FLAG=0 AND A.STUDENT_ID=$userid AND A.STUD_COURSE_DETAIL_ID = $stud_course_details_id ORDER BY A.PAYMENT_ID DESC";
		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['PAYMENT_ID']      = $data['PAYMENT_ID'];
				$dataR['COURSE_NAME'] =  $this->get_inst_course_name($data['INSTITUTE_COURSE_ID']);
				$dataR['RECIEPT_NO']   = $data['RECIEPT_NO'];
				$dataR['FEES_PAID_ON']   = $data['FEES_PAID_ON'];
				$dataR['TOTAL_COURSE_FEES']   = $data['TOTAL_COURSE_FEES'];
				$dataR['FEES_PAID'] = $data['FEES_PAID'];
				$dataR['FEES_BALANCE'] = $data['FEES_BALANCE'];
				$dataR['FEES_RECEIPT'] = HTTP_HOST . "studentPaymentReceipt.php?payid=" . $data['PAYMENT_ID'];


				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Fees details Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	//get demo exam results
	public function getDemoExamResult($userid)
	{
		$dataR    =  array();
		$dataS    =  array();
		$dataP    =  array();
		$dataQ    =  array();

		$sql = "SELECT DISTINCT A.session_id, A.exam_id FROM p_exam_attempt A WHERE A.student_id ='$userid' ORDER BY A.id DESC";
		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$session_id      = $data['session_id'];
				$exam_id      = $data['exam_id'];

				$sql1 = "SELECT B.EXAM_TITLE,B.TOTAL_MARKS,B.TOTAL_QUESTIONS,B.MARKS_PER_QUE,B.PASSING_MARKS,B.EXAM_TIME,B.SHOW_RESULT,B.COURSE_ID FROM exam_structure B WHERE  B.EXAM_ID = '$exam_id'";

				$res1 = parent::execQuery($sql1);

				if ($res1 && $res1->num_rows > 0) {

					while ($data1 = $res1->fetch_assoc()) {
						$sql2 = "SELECT COUNT(id) AS CorrectAnswer FROM p_exam_attempt WHERE answer_status = 1 AND student_id ='$userid' AND session_id ='$session_id'";

						$res2 = parent::execQuery($sql2);
						if ($res2 && $res2->num_rows > 0) {

							while ($data2 = $res2->fetch_assoc()) {
								// $data1['exammode'] = "ONLINE"; 
								$grade = '';
								$result_status = '';
								$incorrectAns =  $data1['TOTAL_QUESTIONS'] - $data2['CorrectAnswer'];

								$marksObt = $data1['MARKS_PER_QUE'] * $data2['CorrectAnswer'];
								$markPer = '';
								if ($marksObt > 0) {
									$markPer = (($marksObt * 100) / $data1['TOTAL_MARKS']);
								}

								if ($markPer >= 85) {
									$grade = "A+ : Excellent";
									$result_status = 'Passed';
								} elseif ($markPer >= 70 && $markPer < 85) {
									$grade = "A : Very Good";
									$result_status = 'Passed';
								} elseif ($markPer >= 55 && $markPer < 70) {
									$grade = "B : Good";
									$result_status = 'Passed';
								} elseif ($markPer >= 40 && $markPer < 55) {
									$grade = "C : Average";
									$result_status = 'Passed';
								} else {
									$grade = "";
									$result_status = 'Failed';
								}

								if ($data1['EXAM_TITLE'] == '') {
									$data1['EXAM_TITLE'] = "";
								}
								$dataR['EXAM_TITLE']          = $data1['EXAM_TITLE'];
								$dataR['EXAM_MODE']           = "ONLINE";
								$dataR['CORRECT_ANSWER']      = $data2['CorrectAnswer'];
								$dataR['INCORRECT_ANSWER']    = "$incorrectAns";
								$dataR['TOTAL_MARKS']         = $data1['TOTAL_MARKS'];
								$dataR['MARKS_OBTAINED']      = "$marksObt";
								$dataR['MARKS_PER']           = "$markPer";
								$dataR['GRADE']               = $grade;
								$dataR['RESULT_STATUS']       = $result_status;

								$dataP[] = $dataR;
							}
						} else {
							$dataR['EXAM_TITLE']          = $data1['EXAM_TITLE'];
							$dataR['EXAM_MODE']           = "ONLINE";
							$dataR['CORRECT_ANSWER']      = "0";
							$dataR['INCORRECT_ANSWER']    = $data1['TOTAL_QUESTIONS'];
							$dataR['TOTAL_MARKS']         = $data1['TOTAL_MARKS'];
							$dataR['MARKS_OBTAINED']      = "0";
							$dataR['MARKS_PER']           = "0";
							$dataR['GRADE']               = "";
							$dataR['RESULT_STATUS']       = "Failed";

							$dataP[] = $dataR;
						}
					}
				}
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
			//echo "<pre>";
			//print_r($dataS);
		} else {
			$dataS['message'] = 'No Demo Results Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		//echo "<pre>";
		//print_r($dataS);
		return $dataS;
	}

	// student wallet history
	public function studentWalletHistory($userid)
	{
		$dataR = array();
		$dataS = array();
		$dataP = array();
		$output = array();

		//online payment data
		$sql = "SELECT A.*, DATE_FORMAT(A.CREATED_ON, '%d/%m/%Y') AS CREATED_DATE FROM online_payments A WHERE A.DELETE_FLAG=0 AND A.USER_ID='$userid' AND A.USER_ROLE='4' ORDER BY PAYMENT_ID DESC";
		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {
			$result = array();
			$i = 0;
			while ($data = $res->fetch_assoc()) {
				$inst_id = $this->get_student_institute_id($data['USER_ID']);
				$inst_mobile = $this->get_institute_mobile($inst_id);

				$result['PAYMENT_ID'] 		= $data['PAYMENT_ID'];
				$result['TRANSACTION_NO'] 	= $data['TRANSACTION_NO'];
				$result['TRANSACTION_TYPE'] = 'CREDIT';
				$result['USER_ID'] 			= $data['USER_ID'];
				$result['USER_ROLE'] 		= $data['USER_ROLE'];
				$result['USER_FULLNAME'] 	= $data['USER_FULLNAME'];
				$result['USER_EMAIL'] 		= $data['USER_EMAIL'];
				$result['USER_MOBILE'] 		= $data['USER_MOBILE'];
				$result['AMOUNT'] 			= $data['PAYMENT_AMOUNT'];
				$result['STATUS'] 			= $data['PAYMENT_STATUS'];
				$result['CREATED_BY'] 		= $data['CREATED_BY'];
				$result['CREATED_ON'] 		= $data['CREATED_ON'];
				$result['CREATED_DATE'] 	= $data['CREATED_DATE'];
				$result['PAYMENT_MODE'] 	= 'ONLINE';
				$result['GST'] 	            =  $data['GST'];
				$result['TOTAL_AMOUNT'] 	=  $data['TOTAL_AMOUNT'];
				$result['INSTITUTE_MOILE'] 	=  $inst_mobile;

				if ($i % 2 == 0) {
					$result['BCK_IMAGE'] = HTTP_HOST . 'resources/list1.png';
				}
				if ($i % 2 == 1) {
					$result['BCK_IMAGE'] = HTTP_HOST . 'resources/list2.png';
				}
				if ($i % 3 == 0) {
					$result['BCK_IMAGE'] = HTTP_HOST . 'resources/list3.png';
				}
				if ($i % 3 == 1) {
					$result['BCK_IMAGE'] = HTTP_HOST . 'resources/list4.png';
				}
				$i++;
				array_push($output, $result);
			}
		}

		//offline payment data
		$sql1 = "SELECT A.*, DATE_FORMAT(A.CREATED_ON, '%d/%m/%Y') AS CREATED_DATE FROM offline_payments A WHERE A.DELETE_FLAG=0 AND A.USER_ID='$userid' AND A.USER_ROLE='4' ORDER BY PAYMENT_ID DESC";
		$res2 = parent::execQuery($sql1);

		if ($res2 && $res2->num_rows > 0) {
			$result2 = array();
			$i = 0;
			while ($data = $res2->fetch_assoc()) {
				$inst_id = $this->get_student_institute_id($data['USER_ID']);
				$inst_mobile = $this->get_institute_mobile($inst_id);

				$result2['PAYMENT_ID'] 		= $data['PAYMENT_ID'];
				$result2['TRANSACTION_NO'] 	= $data['TRANSACTION_NO'];
				$result2['TRANSACTION_TYPE'] = $data['TRANSACTION_TYPE'];
				$result2['USER_ID'] 		= $data['USER_ID'];
				$result2['USER_FULLNAME'] 	= $data['USER_FULLNAME'];
				$result2['USER_EMAIL'] 		= $data['USER_EMAIL'];
				$result2['USER_MOBILE'] 	= $data['USER_MOBILE'];
				$result2['AMOUNT'] 			= $data['PAYMENT_AMOUNT'];
				$result2['STATUS'] 			= $data['PAYMENT_REMARK'];
				$result2['CREATED_BY'] 		= $data['CREATED_BY'];
				$result2['CREATED_ON'] 		= $data['CREATED_ON'];
				$result2['CREATED_DATE'] 	= $data['CREATED_DATE'];
				$result2['PAYMENT_MODE'] 	= 'OFFLINE';
				$result2['INSTITUTE_MOILE'] 	=  $inst_mobile;

				if ($i % 2 == 0) {
					$result2['BCK_IMAGE'] = HTTP_HOST . 'resources/list1.png';
				}
				if ($i % 2 == 1) {
					$result2['BCK_IMAGE'] = HTTP_HOST . 'resources/list2.png';
				}
				if ($i % 3 == 0) {
					$result2['BCK_IMAGE'] = HTTP_HOST . 'resources/list3.png';
				}
				if ($i % 3 == 1) {
					$result2['BCK_IMAGE'] = HTTP_HOST . 'resources/list4.png';
				}
				$i++;
				array_push($output, $result2);
			}
		}
		//print_r($output);
		if (!empty($output)) {
			$dataP['result'] = $output;
			$dataP['success'] = true;
		} else {
			$dataP['success'] = false;
			$dataP['message']  = 'No Transactions Available.';
		}
		return $dataP;
	}

	//help support
	public function listHelpSupport($userid)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();

		$sql = "SELECT *,get_student_name(STUDENT_ID) as STUDENT_NAME FROM help_support WHERE DELETE_FLAG=0 AND STUDENT_ID='$userid' ORDER BY CREATED_ON DESC";
		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {

				$dataP['TICKET_ID'] 		= $data['TICKET_ID'];
				$dataP['STUDENT_ID'] 		= $data['STUDENT_ID'];
				// $dataP['SUPPORT_TYPE_ID'] = $data['SUPPORT_TYPE_ID'];
				// $dataP['SUPPORT_CAT_ID']  = $data['SUPPORT_CAT_ID'];
				$dataP['DESCRIPTION']       = $data['DESCRIPTION'];
				//$dataP['AUTHOR_NAME']     = $data['AUTHOR_NAME'];
				$dataP['MOBILE']            = $data['MOBILE'];
				//$dataP['ALT_MOBILE']      = $data['ALT_MOBILE'];
				$dataP['EMAIL']            = $data['EMAIL'];
				// $dataP['ALT_EMAIL']     = $data['ALT_EMAIL'];
				//$dataP['RATING']        = $data['RATING'];
				$dataP['ADMIN_UPDATES']   = $data['ADMIN_UPDATES'];
				$dataP['CURRENT_STATUS']  = $data['CURRENT_STATUS'];
				$dataP['CREATED_ON']      = date("d/m/Y", strtotime($data['CREATED_ON']));
				$dataP['ACTIVE']          = $data['ACTIVE'];

				$dataR[] = $dataP;
			}
			$dataS['result'] = $dataR;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Ticket Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	public function addHelpSupport($userid, $inst_id, $mobile, $email, $description)
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		// array to pass back data
		$userid 	    = 	parent::test($userid);
		$inst_id 		= 	parent::test($inst_id);
		$mobile 		= 	parent::test($mobile);
		$email 		= 	parent::test($email);
		$description   = 	parent::test($description);

		//required validations 
		$requiredArr = array('userid' => $userid, 'inst_id' => $inst_id, 'mobile' => $mobile, 'email' => $email, 'description' => $description);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "help_support";
			$tabFields 	= "(TICKET_ID,STUDENT_ID,INSTITUTE_ID,DESCRIPTION,MOBILE,EMAIL,CURRENT_STATUS, ACTIVE,CREATED_ON)";
			$insertVals	= "(NULL,'$userid','$inst_id','$description','$mobile','$email','1','1',NOW())";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Help Support has been added successfully!';
			} else {
				$data['message'] = 'Sorry! Something went wrong!';
				$data['success'] = false;
			}
		}
		return $data;
	}

	//list attendance
	public function listAttendance($userid, $inst_course_id)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();
		$dataQ = array();

		$sql = "SELECT A.*, get_student_name(A.STUDENT_ID) AS STUDENT_FULLNAME, get_stud_photo(A.STUDENT_ID) AS  STUD_PHOTO,B.BATCH_ID,B.INSTITUTE_COURSE_ID,B.CREATED_ON as JOIN_DATE  FROM student_details A LEFT JOIN student_course_details B ON A.STUDENT_ID = B.STUDENT_ID WHERE A.ACTIVE=1 AND A.DELETE_FLAG=0 AND A.STUDENT_ID='$userid' AND B.INSTITUTE_COURSE_ID = '$inst_course_id'";
		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				extract($data);

				$JOIN_DATE = date("Y-m-d", strtotime($JOIN_DATE));
				$enddate = date("Y-m-d");

				$sdate = strtotime($JOIN_DATE);
				$edate = strtotime($enddate);

				for ($k = $edate; $k >= $sdate; $k = $k - 86400) {
					$thisDate = date('d-m-Y', $k);

					$batch_name = '';
					if (!empty($BATCH_ID) && $BATCH_ID !== 0 && $BATCH_ID !== '') {
						$batch_name = parent::get_batchname($BATCH_ID);
					}

					$date = date('Y-m-d', $k);
					$block = '';
					$attendancedateStatus = parent::get_attendancedateStatus($BATCH_ID, $STUDENT_ID, $INSTITUTE_COURSE_ID, $date);

					if ($attendancedateStatus != '') {
						if ($attendancedateStatus == '1') {
							$present = "Present";
						}
						if ($attendancedateStatus == '0') {
							$present = "Absent";
						}
					} else {
						$present = 'No Attendance';
					}
					$dataP['STUDENT_FULLNAME'] = $STUDENT_FULLNAME;
					$dataP['DATE'] = $thisDate;
					$dataP['ATTENDANCE'] = $present;

					$dataR[] = $dataP;
				}
			}
			$dataS['result'] = $dataR;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Attendance Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	//new course purchase
	public function newCoursePurchase($student_id, $inst_id, $inst_course_id, $paying_amount, $minimum_courseamount, $wallet_amount, $coursefees)
	{
		$errors = '';  // array to hold validation errors
		$data = array();

		//array to pass back data
		$student_id 	    	= 	parent::test($student_id);
		$inst_id 				= 	parent::test($inst_id);
		$inst_course_id 		= 	parent::test($inst_course_id);
		$paying_amount 		= 	parent::test($paying_amount);
		$minimum_courseamount  = 	parent::test($minimum_courseamount);
		$wallet_amount  		= 	parent::test($wallet_amount);
		$coursefees  			= 	parent::test($coursefees);

		$examtype1 		= '1';
		$examstatus1 		= '2';
		$studcode 			= $this->get_stud_code($student_id);

		$requiredArr = array('paying_amount' => $paying_amount);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors = 'Required field!';
		}

		if ($wallet_amount < $paying_amount) {
			$errors = 'Sorry! Please recharge your wallet to purchase this course.';
		}

		if ($paying_amount < $minimum_courseamount) {
			$errors = 'Sorry! Minimum amount to purchase this course is ' . $minimum_courseamount;
		}

		if ($paying_amount > $coursefees) {
			$errors = 'Course fees for this course is ' . $coursefees . '. Please enter correct amount.';
		}

		$role 			= '4';
		$created_by_id 	= $student_id;
		$created_by  	= $student_id;

		//print_r($errors); exit();
		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();

			//QRCODE	
			include('/home/srcti.in/public_html/admin/resources/phpqrcode/qrlib.php');
			$text = STUDENT_VERIFY_QRURL . 'verify_student=1&code=' . $studcode;
			$path = 'resources/studentDetailsQR/' . $student_id . '/';
			if (!file_exists($path)) {
				@mkdir($path, 0777, true);
			}
			$file = $path . uniqid() . ".png";
			$ecc = 'L';
			$pixel_Size = 100;
			$frame_Size = 100;
			QRcode::png($text, $file, $ecc, $pixel_Size, $frame_size);
			////////////////////////////////////////////////////////////		
			$amtbalance = $coursefees - $paying_amount;

			$tableName1 	= "student_course_details";
			$tabFields1 	= "(STUD_COURSE_DETAIL_ID, STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID, COURSE_FEES,TOTAL_COURSE_FEES,FEES_RECIEVED, FEES_BALANCE, PAYMENT_RECIEVED_FLAG,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON,QRFILE)";

			$insertVals1	= "(NULL, '$student_id','$inst_id','$inst_course_id','$coursefees','$coursefees', '$paying_amount','$amtbalance',1,'1','0','$created_by',NOW(),'$file')";
			$insertSql1     = parent::insertData($tableName1, $tabFields1, $insertVals1);
			$exSql1			= parent::execQuery($insertSql1);

			if ($exSql1) {
				$stud_course_detail_id = parent::last_id();
				$receipt_no = date('d-m-Y') . '/' . $this->generate_student_receipt_no() . $student_id;
				//student payment details
				$tableName2 	= "student_payments";
				$tabFields2 	= "(PAYMENT_ID, RECIEPT_NO,STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID,STUD_COURSE_DETAIL_ID, COURSE_FEES, TOTAL_COURSE_FEES, FEES_PAID, FEES_BALANCE, FEES_PAID_DATE, ACTIVE,DELETE_FLAG, CREATED_ON)";
				$insertVals2	= "(NULL,'$receipt_no', '$student_id','$inst_id', '$inst_course_id', '$stud_course_detail_id','$coursefees','$coursefees','$paying_amount','$amtbalance',NOW(),'1','0', NOW())";
				$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
				$exSql2			= parent::execQuery($insertSql2);
				$payment_id = parent::last_id();

				//QR payment
				$text1 = STUDENT_PAYMENT_QRURL . 'verify_student=1&code=' . $receipt_no;
				$path1 = 'resources/studentFeesQR/' . $payment_id . '/';
				if (!file_exists($path1)) {
					@mkdir($path1, 0777, true);
				}
				$file1 = $path1 . uniqid() . ".png";
				$ecc = 'L';
				$pixel_Size = 100;
				$frame_Size = 100;
				QRcode::png($text1, $file1, $ecc, $pixel_Size, $frame_size);
				////////////////////////////////////////////////////////////
				$sqlQRP = "UPDATE student_payments SET QRFILE = '$file1' WHERE PAYMENT_ID='$payment_id'";
				$exSqlQRP	=  parent::execQuery($sqlQRP);

				//update the first payment id
				$sql = "UPDATE student_course_details SET PAYMENT_ID='$payment_id', ADMISSION_CONFIRMED='1' WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
				parent::execQuery($sql);

				//wallet details	

				$sqlWallet = "UPDATE wallet SET TOTAL_BALANCE = TOTAL_BALANCE - $paying_amount  WHERE USER_ID='$student_id' AND  USER_ROLE = '4'";
				$exSqlWallet	=  parent::execQuery($sqlWallet);

				//institute wallet
				$tableName91 	= " wallet";
				$setValuesInst 	= "TOTAL_BALANCE = TOTAL_BALANCE + $paying_amount, UPDATED_BY='$created_by', UPDATED_ON=NOW()";
				$whereClauseInst 	= "WHERE USER_ID='$inst_id' AND USER_ROLE = 2";
				$updSqlInst 		= parent::updateData($tableName91, $setValuesInst, $whereClauseInst);
				$exSqlInst 		= parent::execQuery($updSqlInst);

				if ($exSqlInst) {
					$trans_typeInst == 'CREDIT';
					$tableNameInst 	= "offline_payments";
					$tabFieldsInst	= "(PAYMENT_ID, TRANSACTION_TYPE,USER_ID,USER_ROLE,PAYMENT_AMOUNT,PAYMENT_REMARK,ACTIVE,CREATED_BY, CREATED_ON)";
					$insertValsInst	= "(NULL,'$trans_typeInst','$inst_id','2','$paying_amount','Student Fees','1','$created_by',NOW())";
					$insertSqlInst	= parent::insertData($tableNameInst, $tabFieldsInst, $insertValsInst);
					$exSqlInstPayment		= parent::execQuery($insertSqlInst);

					$user_info 	= $this->get_user_info($student_id, '4');
					$NAME 		= $user_info['NAME'];
					$MOBILE 	= $user_info['MOBILE'];
					$EMAIL 		= $user_info['EMAIL'];

					$tableName414 	= "offline_payments";
					$tabFields414 	= "(PAYMENT_ID, TRANSACTION_NO, TRANSACTION_TYPE,USER_ID,USER_ROLE,USER_FULLNAME,USER_EMAIL,USER_MOBILE,PAYMENT_AMOUNT,PAYMENT_MODE,PAYMENT_DATE,PAYMENT_STATUS,PAYMENT_REMARK,ACTIVE,CREATED_BY,CREATED_ON,STUDENT_ID)";
					$insertVals414	= "(NULL,get_payment_transaction_id_admin(), 'DEBIT','$student_id','4', '$NAME','$EMAIL','$MOBILE','$paying_amount','OFFLINE',NOW(), 'success', 'Admission Confirmed', '1','$created_by',NOW(),'$student_id')";
					$insertSql414	= parent::insertData($tableName414, $tabFields414, $insertVals414);
					$exSql414		= parent::execQuery($insertSql414);
				}


				if ($exSqlWallet) {

					$instcourse = parent::get_inst_course_info($inst_course_id);
					$COURSE_ID = isset($instcourse['COURSE_ID']) ? $instcourse['COURSE_ID'] : '';
					$MULTI_SUB_COURSE_ID = isset($instcourse['MULTI_SUB_COURSE_ID']) ? $instcourse['MULTI_SUB_COURSE_ID'] : '';
					$TYPING_COURSE_ID = isset($instcourse['TYPING_COURSE_ID']) ? $instcourse['TYPING_COURSE_ID'] : '';

					$aicpe_course_id = $COURSE_ID;
					$aicpe_course_id_multi = $MULTI_SUB_COURSE_ID;
					$course_typing = $TYPING_COURSE_ID;

					if ($aicpe_course_id !== '') {
						$valid_exam = parent::validate_apply_exam($aicpe_course_id, '', '');

						if (!empty($valid_exam)) {
							$invalidArr = array();
							$validerrors = isset($valid_exam['errors']) ? $valid_exam['errors'] : '';
							$success_flag 	= isset($valid_exam['success']) ? $valid_exam['success'] : '';
							if ($success_flag == true) {
								$exam_modes = isset($valid_exam['exam_modes']) ? $valid_exam['exam_modes'] : '';
								$exam_modes = json_decode($exam_modes);
								if (in_array($examtype1, $exam_modes)) {
									$setValues9 	= "EXAM_STATUS='$examstatus1', EXAM_TYPE='$examtype1', UPDATED_BY='$created_by', UPDATED_ON=NOW()";

									if ($examtype1 == '1' && $examstatus1 == '2') $setValues9 .= ",DEMO_COUNT=0";
									if ($examtype1 == '1' && $examstatus1 == '1') $setValues9 .= ",DEMO_COUNT=0";
									if ($examtype1 == '1' && $examstatus1 == '3') $setValues9 .= ",DEMO_COUNT=10";

									$whereClause9 = " WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
									$updateSql9	= parent::updateData($tableName1, $setValues9, $whereClause9);
									$exSql9		= parent::execQuery($updateSql9);
								}
							}
						}
					}
					if ($aicpe_course_id_multi !== '') {
						$setValues9 	= "EXAM_STATUS='$examstatus1', EXAM_TYPE='$examtype1', DEMO_COUNT=0,UPDATED_BY='$created_by', UPDATED_ON=NOW()";
						$whereClause9 = " WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
						$updateSql9	= parent::updateData($tableName1, $setValues9, $whereClause9);
						$exSql9		= parent::execQuery($updateSql9);
					}
					if ($course_id_typing !== '') {
						$setValues9 	= "EXAM_STATUS='$examstatus1', EXAM_TYPE='$examtype1', DEMO_COUNT=0,UPDATED_BY='$created_by', UPDATED_ON=NOW()";
						$whereClause9 = " WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
						$updateSql9	= parent::updateData($tableName1, $setValues9, $whereClause9);
						$exSql9		= parent::execQuery($updateSql9);
					}
				}

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Course has been added successfully!';
				$data['errors']  = "";
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the course.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return $data;
	}

	//final exam OTP
	public function getFinalExamOTP($userid, $inst_course_id)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();

		$sql = "SELECT * FROM student_course_details WHERE DELETE_FLAG=0 AND STUDENT_ID='$userid' AND INSTITUTE_COURSE_ID = '$inst_course_id' AND ACTIVE = 1";
		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataP['EXAM_SECRETE_CODE'] 		    = $data['EXAM_SECRETE_CODE'];
				$dataR[] = $dataP;
			}
			$dataS['result'] = $dataR;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Code Available';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	//final exam OTP
	public function verifyFinalExamOTP($userid, $inst_course_id, $otp)
	{

		$dataR = array();
		$dataS = array();
		$dataP = array();

		$sql = "SELECT * FROM student_course_details WHERE DELETE_FLAG=0 AND STUDENT_ID='$userid' AND INSTITUTE_COURSE_ID = '$inst_course_id' AND ACTIVE = 1";
		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataP['EXAM_SECRETE_CODE'] = $data['EXAM_SECRETE_CODE'];
				if ($otp == $data['EXAM_SECRETE_CODE']) {
					$dataS['message'] = "Exam Code Verify Successfully !!!";
					$dataS['success'] = true;
				} else {
					$dataS['message'] = "Invalid Exam Code";
					$dataS['success'] = false;
				}
			}
		} else {
			$dataS['message'] = 'Invalid OTP';
			$dataS['success'] = false;
			//$dataS['errors']  = $errors;
		}
		return $dataS;
	}

	public function generate_exam_code()
	{
		$code = '';
		$numbers = '0123456789';
		$code .=  substr(str_shuffle($numbers), 0, 8);
		return $code;
	}
	public function get_stud_name1($stud_id)
	{
		$stud_name = '';
		$sql = "SELECT CONCAT(CONCAT(ABBREVIATION,'. ',STUDENT_FNAME,' ',STUDENT_MNAME),' ',STUDENT_LNAME) AS STUD_NAME FROM student_details WHERE STUDENT_ID='$stud_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['STUD_NAME'];
		}
		return $stud_name;
	}

	public function aboutUs()
	{
		$dataR = array();
		$dataS = array();
		$dataP = array();

		$sql = "SELECT A.* FROM  about_us A WHERE A.delete_flag=0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['about_short']       	= html_entity_decode($data['about_short']);
				$dataR['about_long']        	= html_entity_decode($data['about_long']);
				$dataR['homepage_image']  	= HTTP_HOST . 'uploads/aboutus/' . $data['id'] . '/' . $data['homepage_image'];
				$dataR['mission_short']       = html_entity_decode($data['mission_short']);
				$dataR['mission_long'] 	    = html_entity_decode($data['mission_long']);
				$dataR['mission_image'] 	    = HTTP_HOST . 'uploads/aboutus/' . $data['id'] . '/' . $data['mission_image'];
				$dataR['vision_short'] 		= html_entity_decode($data['vision_short']);
				$dataR['vision_long']        	= html_entity_decode($data['vision_long']);
				$dataR['vision_image']        = HTTP_HOST . 'uploads/aboutus/' . $data['id'] . '/' . $data['vision_image'];

				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Data Available.';
			$dataS['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataS;
	}

	public function privacyPolicy()
	{
		$dataR = array();
		$dataS = array();
		$dataP = array();

		$sql = "SELECT A.* FROM  our_policies A WHERE A.delete_flag=0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['privacy_policies']   = html_entity_decode($data['privacy_policies']);
				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Data Available.';
			$dataS['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataS;
	}

	public function termsCondition()
	{
		$dataR = array();
		$dataS = array();
		$dataP = array();

		$sql = "SELECT A.* FROM  our_policies A WHERE A.delete_flag=0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$dataR['terms_condition']       	= html_entity_decode($data['terms_condition']);
				$dataP[] = $dataR;
			}
			$dataS['result'] = $dataP;
			$dataS['success'] = true;
		} else {
			$dataS['message'] = 'No Data Available.';
			$dataS['success'] = false;
			//$dataR['errors']  = $errors;
		}
		return $dataS;
	}
}
