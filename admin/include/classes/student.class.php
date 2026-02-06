<?php
include_once('database_results.class.php');
include_once('access.class.php');

class student extends access
{
	/* add new staff in institute 
	@param: 
	@return: json
	*/
	//Start Enquiry Section
	public function add_student_enquiry()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data
		extract($_POST);

		$save	 			= isset($save) ? $save : '';
		$register	 		= isset($register) ? $register : '';
		$sonof	 		= strtoupper(parent::test(isset($sonof) ? $sonof : ''));
		$abbreviation	 	= strtoupper(parent::test(isset($abbreviation) ? $abbreviation : ''));
		$fname	 		= strtoupper(parent::test(isset($fname) ? $fname : ''));
		$mname	 		= strtoupper(parent::test(isset($mname) ? $mname : ''));
		$lname	 		= strtoupper(parent::test(isset($lname) ? $lname : ''));
		$mothername	 	= strtoupper(parent::test(isset($mothername) ? $mothername : ''));
		$mobile	 		= parent::test(isset($mobile) ? $mobile : '');

		$mobile2	 		= parent::test(isset($mobile2) ? $mobile2 : '');
		$email	 		= parent::test(isset($email) ? $email : '');
		$dob	 			= parent::test(isset($dob) ? $dob : '');
		$gender	 		= parent::test(isset($gender) ? $gender : '');
		$per_add	 		= parent::test(isset($per_add) ? $per_add : '');
		$state	 		= parent::test(isset($state) ? $state : '');
		$city		 		= parent::test(isset($city) ? $city : '');
		$postcode	 		= parent::test(isset($pincode) ? $pincode : '');
		$interested_course = parent::test(isset($interested_course) ? $interested_course : '');

		$enquiry_date = parent::test(isset($enquiry_date) ? $enquiry_date : '');

		$remark	 		= parent::test(isset($remark) ? $remark : '');
		$remark_date	 	= parent::test(isset($remark_date) ? $remark_date : '');
		$date = date("Y-m-d");
		if ($enquiry_date == '') {
			$enquiry_date = "$date";
		}
		if ($remark_date == '') {
			$remark_date = "$date";
		}

		$refferal_code	= parent::test(isset($refferal_code) ? $refferal_code : '');
		$institute_id		= parent::test(isset($institute_id) ? $institute_id : '');
		$role 			= $_SESSION['user_role'];
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */
		$requiredArr = array('fname' => $fname, 'mobile' => $mobile);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}
		// validate strings
		$stringArr = array('fname' => $fname, 'mname' => $mname, 'lname' => $lname);
		$checkString = parent::valid_string($stringArr);
		if (!empty($checkString)) {
			foreach ($checkString as $value)
				$errors[$value] = 'Only letters and white space allowed!';
		}

		if ($mobile != '') {
			$valid_mob = parent::valid_mobile($mobile);
			if ($valid_mob != '') $errors['mobile'] = $valid_mob;
		}
		if ($mobile2 != '') {
			$valid_mob2 = parent::valid_mobile($mobile2);
			if ($valid_mob2 != '') $errors['mobile2'] = $valid_mob2;
		}
		if ($postcode != '') {
			if (strlen($postcode) != 6)
				$errors['postcode'] = 'Postal code must be in number and 6 digits only.';
			if (!preg_match("/^[a-zA-Z0-9 ]*$/", $postcode)) {
				$errors['postcode'] = "Only letters and white space allowed";
			}
		}
		// if($dob!='') 
		// $dob = @date('Y-m-d', strtotime($dob));		
		//print_r(parent::valid_refferal_code($refferal_code)); exit();
		if ($refferal_code != '') {
			if (parent::valid_refferal_code($refferal_code, $institute_id))
				$errors['refferal_code'] = 'Sorry! Invalid refferal code. Please insert valid code.';
		}
		// if($email ==''){
		// 	$errors['email'] = 'Please enter email id';
		// }
		if ($email != '') {
			if (!parent::valid_student_email($email, ''))
				$errors['email'] = 'Sorry! Email is already used.';

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors['email'] = "Invalid email format";
			}
		}
		if ($interested_course == '') {
			$errors['interested_course'] = "Required! Select atleast one course.";
		}

		if ($enquiry_date != '') {
			$enquiry_date = @date('Y-m-d', strtotime($enquiry_date));
		}


		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "student_enquiry";
			$tabFields 	= "(ENQUIRY_ID,INSTITUTE_ID,ABBREVIATION, STUDENT_FNAME,STUDENT_MNAME,STUDENT_LNAME,STUDENT_MOTHERNAME, STUDENT_DOB,STUDENT_GENDER,STUDENT_MOBILE,STUDENT_MOBILE2,STUDENT_EMAIL,STUDENT_PER_ADD,STUDENT_STATE,STUDENT_CITY,STUDENT_PINCODE,INSTRESTED_COURSE,SONOF,CREATED_BY, CREATED_ON,REFFERAL_CODE,ENQ_DATE,REMARK,REMARK_DATE)";
			$insertVals	= "(NULL, '$institute_id','$abbreviation','$fname','$mname','$lname','$mothername','$dob','$gender','$mobile','$mobile2','$email','$per_add','$state','$city','$postcode','$interested_course','$sonof','$created_by',NOW(),'$refferal_code','$enquiry_date','$remark','$remark_date')";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				$data['enquiry_id'] = parent::last_id();

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New student enquiry has been added successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the student enquiry.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function update_student_enquiry()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data
		extract($_POST);

		$enquiry_id 		= parent::test(isset($enquiry_id) ? $enquiry_id : '');

		$abbreviation	 	= strtoupper(parent::test(isset($abbreviation) ? $abbreviation : ''));
		$fname	 		= strtoupper(parent::test(isset($fname) ? $fname : ''));
		$mname	 		= strtoupper(parent::test(isset($mname) ? $mname : ''));
		$lname	 		= strtoupper(parent::test(isset($lname) ? $lname : ''));
		$mothername	 	= strtoupper(parent::test(isset($mothername) ? $mothername : ''));

		$mobile	 		= parent::test(isset($mobile) ? $mobile : '');
		$mobile2	 		= parent::test(isset($mobile2) ? $mobile2 : '');
		$email	 		= parent::test(isset($email) ? $email : '');
		$dob	 			= parent::test(isset($dob) ? $dob : '');
		$gender	 		= parent::test(isset($gender) ? $gender : '');
		$per_add	 		= parent::test(isset($per_add) ? $per_add : '');
		$state	 		= parent::test(isset($state) ? $state : '');
		$city		 		= parent::test(isset($city) ? $city : '');
		$postcode	 		= parent::test(isset($pincode) ? $pincode : '');
		$interested_course = parent::test(isset($interested_course) ? $interested_course : '');
		$sonof	 	= strtoupper(parent::test(isset($sonof) ? $sonof : ''));

		$enquiry_date = parent::test(isset($enquiry_date) ? $enquiry_date : '');

		$refferal_code	= parent::test(isset($refferal_code) ? $refferal_code : '');

		$remark	 		= parent::test(isset($remark) ? $remark : '');
		$remark_date	 	= parent::test(isset($remark_date) ? $remark_date : '');

		$role 			= $_SESSION['user_role'];
		$created_by  		= $_SESSION['user_fullname'];
		/* check validations */
		if ($interested_course == '') {
			$errors['interested_course'] = "Required! Select atleast one course.";
		}

		if ($enquiry_date != '') {
			$enquiry_date = @date('Y-m-d', strtotime($enquiry_date));
		}


		$requiredArr = array('fname' => $fname, 'mobile' => $mobile);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}
		// validate strings
		$stringArr = array('fname' => $fname, 'mname' => $mname, 'lname' => $lname);
		$checkString = parent::valid_string($stringArr);
		if (!empty($checkString)) {
			foreach ($checkString as $value)
				$errors[$value] = 'Only letters and white space allowed!';
		}
		if ($email != '') {
			if (!parent::valid_student_email($email, ''))
				$errors['email'] = 'Sorry! Email is already used.';

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors['email'] = "Invalid email format";
			}
		}
		if ($refferal_code != '') {
			if (parent::valid_refferal_code($refferal_code))
				$errors['refferal_code'] = 'Sorry! Invalid refferal code. Please insert valid code.';
		}

		if ($mobile != '') {
			$valid_mob = parent::valid_mobile($mobile);
			if ($valid_mob != '') $errors['mobile'] = $valid_mob;
		}
		if ($mobile2 != '') {
			$valid_mob2 = parent::valid_mobile($mobile2);
			if ($valid_mob2 != '') $errors['mobile2'] = $valid_mob2;
		}
		if ($postcode != '') {
			if (strlen($postcode) != 6)
				$errors['postcode'] = 'Postal code must be in number and 6 digits only.';
			if (!preg_match("/^[a-zA-Z0-9 ]*$/", $postcode)) {
				$errors['postcode'] = "Only letters and white space allowed";
			}
		}
		// if($dob!='') 
		// $dob = @date('Y-m-d', strtotime($dob));	 

		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "student_enquiry";
			$setValues 	= "ABBREVIATION='$abbreviation', STUDENT_FNAME='$fname',STUDENT_MNAME='$mname',STUDENT_LNAME='$lname',STUDENT_MOTHERNAME='$mothername', STUDENT_DOB='$dob',STUDENT_GENDER='$gender',STUDENT_MOBILE='$mobile', STUDENT_MOBILE2='$mobile2', STUDENT_EMAIL='$email', STUDENT_PER_ADD='$per_add', STUDENT_STATE='$state', STUDENT_CITY='$city', STUDENT_PINCODE='$postcode', INSTRESTED_COURSE='$interested_course',SONOF='$sonof',REFFERAL_CODE='$refferal_code',UPDATED_BY='$created_by', UPDATED_ON=NOW(),ENQ_DATE='$enquiry_date',REMARK='$remark',REMARK_DATE='$remark_date'";

			$whereClause = " WHERE ENQUIRY_ID='$enquiry_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);
			if ($exSql && parent::rows_affected() > 0) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Student enquiry has been updated successfully!';
			} else {

				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not update the student enquiry.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	//list student from institute
	public function list_student_enquiry($enquiry_id = '', $institute_id = '', $staff_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT A.*,CONCAT(CONCAT(STUDENT_FNAME,' ',STUDENT_MNAME), ' ', STUDENT_LNAME) AS STUDENT_FULLNAME, DATE_FORMAT(A.DATE_JOINING, '%d-%m-%Y') JOINING_FORMATED, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y') AS CREATED_DATE FROM student_enquiry A WHERE A.DELETE_FLAG=0 AND A.REGISTRATION  = 0 ";
		if ($enquiry_id != '') {
			$sql .= " AND A.ENQUIRY_ID='$enquiry_id' ";
		}
		if ($institute_id != '') {
			$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
		}
		if ($staff_id != '') {
			$sql .= " AND A.STAFF_ID='$staff_id' ";
		}
		if ($cond != '') {
			$sql .= $cond;
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//delete student enquiry
	/* validate institute code */
	public function delete_student_enquiry($enq_id = '')
	{
		$sql = "UPDATE student_enquiry SET DELETE_FLAG=1 WHERE ENQUIRY_ID='$enq_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}
	// END Enquiry Section

	//direct admission function

	public function add_student_direct_admission()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data
		extract($_POST);
		//print_r($_POST); exit();
		$enquiry_id	 	= parent::test(isset($enquiry_id) ? $enquiry_id : '');

		$register	 		= isset($register) ? $register : '';
		$abbreviation	 	= strtoupper(parent::test(isset($abbreviation) ? $abbreviation : ''));
		$fname	 		= strtoupper(parent::test(isset($fname) ? $fname : ''));
		$mname	 		= strtoupper(parent::test(isset($mname) ? $mname : ''));
		$lname	 		= strtoupper(parent::test(isset($lname) ? $lname : ''));
		$mothername	 	= strtoupper(parent::test(isset($mothername) ? $mothername : ''));
		$cert_mname	 	= parent::test(isset($cert_mname) ? $cert_mname : '');
		$cert_lname	 	= parent::test(isset($cert_lname) ? $cert_lname : '');
		$mobile	 		= parent::test(isset($mobile) ? $mobile : '');

		$mobile2	 		= parent::test(isset($mobile2) ? $mobile2 : '');
		$email	 		= parent::test(isset($email) ? $email : '');
		$dob	 			= parent::test(isset($dob) ? $dob : '');
		$gender	 		= parent::test(isset($gender) ? $gender : '');
		$per_add	 		= parent::test(isset($per_add) ? $per_add : '');
		$state	 		= parent::test(isset($state) ? $state : '');
		$city		 		= parent::test(isset($city) ? $city : '');
		$postcode	 		= parent::test(isset($pincode) ? $pincode : '');
		$refferal_code	= parent::test(isset($refferal_code) ? $refferal_code : '');

		$caste			= parent::test(isset($caste) ? $caste : '');
		$qualification	= parent::test(isset($qualification) ? $qualification : '');
		$occupation		= parent::test(isset($occupation) ? $occupation : '');

		$roll_number		= parent::test(isset($roll_number) ? $roll_number : '');

		$adharid				= parent::test(isset($adharid) ? $adharid : '');
		$interested_course	= parent::test(isset($interested_course) ? $interested_course : '');
		$status 				= parent::test(isset($_POST['status']) ? $_POST['status'] : 1);

		$sonof	 	= strtoupper(parent::test(isset($sonof) ? $sonof : ''));
		$batch	 	= strtoupper(parent::test(isset($batch) ? $batch : ''));

		$admission_date = parent::test(isset($admission_date) ? $admission_date : '');
		$date = date("Y-m-d");
		if ($admission_date == '') {
			$admission_date = "$date";
		}

		$remainingStudent	 	= parent::test(isset($remainingStudent) ? $remainingStudent : '');
		$display_status	 	= strtoupper(parent::test(isset($display_status) ? $display_status : ''));

		$filecount4 		= parent::test(isset($_POST['filecount4']) ? $_POST['filecount4'] : '');

		//   $photo_id_category = parent::test(isset($_POST['photo_id_category'])?$_POST['photo_id_category']:'');
		//   $photo_id_category_other = parent::test(isset($_POST['photo_id_category_other'])?$_POST['photo_id_category_other']:'');
       

		$stud_photo_id_desc = parent::test(isset($_POST['stud_photo_id_desc']) ? $_POST['stud_photo_id_desc'] : '');
		/* Files */
		$stud_photo		= isset($_FILES['stud_photo']['name']) ? $_FILES['stud_photo']['name'] : '';
		$stud_photo_id		= isset($_FILES['stud_photo_id']['name']) ? $_FILES['stud_photo_id']['name'] : '';

		$stud_sign			= isset($_FILES['stud_sign']['name']) ? $_FILES['stud_sign']['name'] : '';

		//   if($stud_photo=='')		{	$errors['stud_photo'] 			= 'Please upload student photo.';}
		//   if($stud_sign=='')		{	$errors['stud_sign'] 			= 'Please upload student sign.';}

		if ($_FILES['stud_photo']['size'] > 256000) {
			$errors['stud_photo'] = 'Please upload student photo below 256 KB.';
		}
		if ($_FILES['stud_sign']['size'] > 256000) {
			$errors['stud_sign'] = 'Please upload student sign below 256 KB.';
		}

		if ($stud_photo != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($stud_photo, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['stud_photo'] = 'Invalid file format! Please select valid file.';
			}
		}
		if ($stud_sign != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($stud_sign, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['stud_sign'] = 'Invalid file format! Please select valid file.';
			}
		}
		if ($interested_course == '') {
			$errors['interested_course'] = "Required! Select Course.";
		}
		$inst_course_id = $interested_course;

		$requiredArr = array('dob' => $dob, 'fname' => $fname, 'mobile' => $mobile, 'roll_number' => $roll_number);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}


		//new validations
		if ($email != '') {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors['email'] = "Invalid email format";
			}
			// if ($email=='')
			// $errors['email'] = 'Email is required.';

			// if(!parent::valid_username($email))
			// $errors['email'] = 'Sorry! Email is already used.';
			//   if(!parent::valid_student_email($email,''))
			// $errors['email'] = 'Sorry! Email is already used.';
		}



		$stringArr = array('fname' => $fname);
		$checkString = parent::valid_string($stringArr);
		if (!empty($checkString)) {
			foreach ($checkString as $value)
				$errors[$value] = 'Only letters and white space allowed!';
		}

		if ($batch == '') {
			$errors['batch'] = 'Please Select Batch.';
		}

		if ($remainingStudent == '' || $remainingStudent == 0) {
			$errors['remainingStudent'] = 'Please Re-Select Your Batch And Please Check Your Remaining Admission Not Zero. ';
		}


		if ($mobile != '') {
			$valid_mob = parent::valid_mobile($mobile);
			if ($valid_mob != '') $errors['mobile'] = $valid_mob;
		}
		if ($mobile2 != '') {
			$valid_mob2 = parent::valid_mobile($mobile2);
			if ($valid_mob2 != '') $errors['mobile2'] = $valid_mob2;
		}
		if ($postcode != '') {
			if (strlen($postcode) != 6)
				$errors['postcode'] = 'Postal code must be in number and 6 digits only.';
			if (!preg_match("/^[a-zA-Z0-9 ]*$/", $postcode)) {
				$errors['postcode'] = "Only letters and white space allowed";
			}
		}
		if ($dob != '') {
			$dob = @date('Y-m-d', strtotime($dob));
		}
		if ($admission_date != '') {
			$admission_date = @date('Y-m-d', strtotime($admission_date));
		}


		//payment details		   		 
		$coursefees 		= isset($_POST['coursefees']) ? $_POST['coursefees'] : 0;
		$discrate 		= isset($_POST['discrate']) ? $_POST['discrate'] : '';
		$discamt 			= isset($_POST['discamt']) ? $_POST['discamt'] : 0;
		$totalcoursefee	= isset($_POST['totalcoursefee']) ? $_POST['totalcoursefee'] : 0;
		$amtrecieved 		= isset($_POST['amtrecieved']) ? $_POST['amtrecieved'] : 0;
		$amtbalance 		= isset($_POST['amtbalance']) ? $_POST['amtbalance'] : 0;
		$payremarks 		= isset($_POST['payremarks']) ? $_POST['payremarks'] : '';

		$minAmount = parent::get_instituteMinFees($inst_course_id);

		if ($amtrecieved < $minAmount) {
			$errors['amtrecieved'] = 'Sorry! Minimum amount to purchase this course is' . $minAmount;
		}

		if ($discamt == 0 || $discamt == '') {
			$totalcoursefee = $coursefees;
			$discamt = 0;
		}

		$examtype1 		= isset($_POST['examtype1']) ? $_POST['examtype1'] : '';
		$examstatus1 		= isset($_POST['examstatus1']) ? $_POST['examstatus1'] : '';
		if ($examtype1 == '') $errors['examtype1'] = 'Please select exam mode!';

		$studcode 		= $this->generate_student_code();
		$uname 			= $studcode;
		$exam_secrete_code = $this->generate_student_exam_secrete_code();
		$confpword 		= parent::generate_password();
		$institute_id		= parent::test(isset($institute_id) ? $institute_id : '');

		$role 			= $_SESSION['user_role'];

		$created_by_id = $_SESSION['user_id'];
		$created_by  		= $_SESSION['user_fullname'];

		if ($refferal_code != '') {
			if (parent::valid_refferal_code($refferal_code, $institute_id))
				$errors['refferal_code'] = 'Sorry! Invalid refferal code. Please insert valid code.';
		}

		if (!parent::valid_rollnumber($roll_number, $institute_id))
			$errors['roll_number'] = 'Sorry! Roll Number is already used.';


		if ($role == '8') {
			$examfees 		= isset($_POST['examfees']) ? $_POST['examfees'] : '';

			if ($examfees == '') {
				$errors['examfees'] = 'Please Select Course.';
			}

			$walletBal = 0;
			$totalToPay = 0;
			$wallet_id = '';
			$res = parent::get_wallet('', $institute_id, $role);
			if ($res != '') {
				$data1 = $res->fetch_assoc();
				$walletBal = $data1['TOTAL_BALANCE'];
				$wallet_id = $data1['WALLET_ID'];
			} else {
				$errors['examfees'] = "Sorry! Your wallet is empty!  Please rechrage your wallet and Tray again! <a href='pay-online' class='btn btn-sm bg-teal'>Click to Recharge Now!</a>";
			}

			$totalToPay = $examfees;
			if ($totalToPay > $walletBal)
				$errors['examfees'] = "Sorry! Your total bill is <strong>Rs. $totalToPay</strong>.  You have only <strong>Rs. $walletBal</strong> availabel in your wallet! You need more <strong> Rs. " . ($totalToPay - $walletBal) . "</strong> to order the certificates.<br> Please rechrage your wallet. <a href='pay-online'>Recharge Now!</a>";
		}

		// if($filecount4>=1)
		// {
		// 	for($i=0; $i<$filecount4; $i++)
		// 	{
		// 		$installment_name 	= parent::test(isset($_POST['installment_name'.$i])?$_POST['installment_name'.$i]:'');
		// 		$installment_amount = parent::test(isset($_POST['installment_amount'.$i])?$_POST['installment_amount'.$i]:'');
		// 		$installment_date 	= parent::test(isset($_POST['installment_date'.$i])?$_POST['installment_date'.$i]:'');

		// 		if ($installment_name=='')
		// 		$errors['installment_name'.$i] = 'Please Select Installment!';    

		// 		if ($installment_amount=='')
		// 		$errors['installment_amount'.$i] = 'Amount is required!';

		// 		if ($installment_date=='')
		// 		$errors['installment_date'.$i] = 'Date is required!';

		// 	}			 

		// }

		//print_r($errors); exit();
		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "student_details";
			$tabFields 	= "(STUDENT_ID,INSTITUTE_ID,ABBREVIATION,STUDENT_CODE, STUDENT_FNAME,STUDENT_MNAME,STUDENT_LNAME,STUDENT_MOTHERNAME,STUDENT_DOB,STUDENT_GENDER,STUDENT_MOBILE,STUDENT_MOBILE2,STUDENT_EMAIL,STUDENT_PER_ADD,STUDENT_STATE,STUDENT_CITY,STUDENT_PINCODE,STUDENT_ADHAR_NUMBER,SONOF,ENQUIRY_ID,ACTIVE, CREATED_BY, CREATED_ON,REFFERAL_CODE,ROLL_NUMBER,DISPLAY_FORM_STATUS,EDUCATIONAL_QUALIFICATION,OCCUPATION,CASTE)";

			$insertVals	= "(NULL,'$institute_id', '$abbreviation','$studcode', '$fname','$mname','$lname','$mothername','$dob','$gender','$mobile','$mobile2','$email','$per_add','$state','$city','$postcode','$stud_photo_id_desc','$sonof','$enquiry_id','$status','$created_by',NOW(),'$refferal_code','$roll_number','$display_status','$qualification','$occupation','$caste')";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				$student_id = parent::last_id();
				//QRCODE	
				include('resources/phpqrcode/qrlib.php');
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

				$sqlQR = "UPDATE student_details SET QRFILE = '$file' WHERE STUDENT_ID='$student_id'";
				$exSqlQR = parent::execQuery($sqlQR);

				if ($refferal_code !== '') {
					$refferarStudent	=  parent::get_refferar_details($refferal_code, $institute_id);
					$refferalAmount 	=  parent::get_refferal_amount($institute_id);
				}

				$tableName1 	= "student_course_details";
				$tabFields1 	= "(STUD_COURSE_DETAIL_ID, STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID, COURSE_FEES,DISCOUNT_RATE,DISCOUNT_AMOUNT,TOTAL_COURSE_FEES,FEES_RECIEVED, FEES_BALANCE, REMARKS, PAYMENT_RECIEVED_FLAG,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON,BATCH_ID,ADMISSION_DATE)";

				$insertVals1	= "(NULL, '$student_id','$institute_id','$inst_course_id','$coursefees','$discrate','$discamt','$totalcoursefee', '$amtrecieved','$amtbalance','$payremarks',1,'1','0','$created_by',NOW(),'$batch','$admission_date')";
				$insertSql1 = parent::insertData($tableName1, $tabFields1, $insertVals1);
				$exSql1			= parent::execQuery($insertSql1);

				if ($exSql1) {
					$stud_course_detail_id = parent::last_id();
					$receipt_no = date('d-m-Y') . '/' . $this->generate_student_receipt_no() . $student_id;
					//student payment details
					$tableName2 	= "student_payments";
					$tabFields2 	= "(PAYMENT_ID, RECIEPT_NO,STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID,STUD_COURSE_DETAIL_ID, COURSE_FEES, TOTAL_COURSE_FEES, FEES_PAID, FEES_BALANCE, FEES_PAID_DATE, PAYMENT_NOTE,ACTIVE,DELETE_FLAG, CREATED_BY, CREATED_ON)";
					$insertVals2	= "(NULL,'$receipt_no', '$student_id','$institute_id', '$inst_course_id', '$stud_course_detail_id','$coursefees','$totalcoursefee','$amtrecieved','$amtbalance',NOW(),'$payremarks','1','0','$created_by', NOW())";
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
					$sql = "UPDATE student_course_details SET PAYMENT_ID='$payment_id' WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
					parent::execQuery($sql);

					// student login details
					$tableName3 	= "user_login_master";
					$tabFields3	= "(USER_LOGIN_ID, USER_ID, USER_NAME, PASS_WORD,USER_ROLE, ACCOUNT_REGISTERED_ON,ACTIVE, CREATED_BY,CREATED_ON)";
					$insertVals3	= "(NULL, '$student_id', '$uname', MD5('$mobile'),'4',NOW(),'$status','$created_by',NOW())";
					$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
					$exSql3			= parent::execQuery($insertSql3);

					//wallet details						
					$tableName91 	= " wallet";
					$tabFields91	= "(WALLET_ID, USER_ID, USER_ROLE, TOTAL_BALANCE,ACTIVE, CREATED_BY,CREATED_ON)";
					$insertVals91	= "(NULL, '$student_id', '4', '0.00','1','$created_by',NOW())";
					$insertSql91		= parent::insertData($tableName91, $tabFields91, $insertVals91);
					$exSql91			= parent::execQuery($insertSql91);

					//refferal payment record 
					if (!empty($refferarStudent)) {
						$trans_type == 'CREDIT';
						$tableNameR1 	= "offline_payments";
						$tabFieldsR1	= "(PAYMENT_ID,TRANSACTION_TYPE,USER_ID,USER_ROLE,PAYMENT_AMOUNT,PAYMENT_REMARK,ACTIVE,CREATED_BY, CREATED_ON,STUDENT_ID)";
						$insertValsR1	= "(NULL,'$trans_type','$refferarStudent','4','$refferalAmount','Refferal Amount','1','$created_by',NOW(),'$student_id')";
						$insertSqlR1	= parent::insertData($tableNameR1, $tabFieldsR1, $insertValsR1);
						$exSqlR1		= parent::execQuery($insertSqlR1);

						//reference amount
						$setValuesR 	= "TOTAL_BALANCE = TOTAL_BALANCE + $refferalAmount, UPDATED_BY='$created_by', UPDATED_ON=NOW()";
						$whereClauseR 	= "WHERE USER_ID='$refferarStudent' AND USER_ROLE = 4";
						$updSqlR 		= parent::updateData($tableName91, $setValuesR, $whereClauseR);
						$exSqlR 		= parent::execQuery($updSqlR);
					}

					//institute wallet
					$setValuesInst 	= "TOTAL_BALANCE = TOTAL_BALANCE + $amtrecieved, UPDATED_BY='$created_by', UPDATED_ON=NOW()";
					$whereClauseInst 	= "WHERE USER_ID='$institute_id' AND USER_ROLE = 2";
					$updSqlInst 		= parent::updateData($tableName91, $setValuesInst, $whereClauseInst);
					$exSqlInst 		= parent::execQuery($updSqlInst);

					/*if($exSqlInst){
							$trans_typeInst='CREDIT';
							$tableNameInst 	= "offline_payments";
							$tabFieldsInst	= "(PAYMENT_ID, TRANSACTION_TYPE,USER_ID,PAYMENT_AMOUNT,PAYMENT_REMARK,ACTIVE,CREATED_BY, CREATED_ON,STUDENT_ID)";
							$insertValsInst	= "(NULL,'$trans_typeInst','$institute_id','$amtrecieved','Student Fees','1','$created_by',NOW(),'$student_id')";
							$insertSqlInst	= parent::insertData($tableNameInst,$tabFieldsInst,$insertValsInst);
							$exSqlInstPayment		= parent::execQuery($insertSqlInst);
						}*/

					/*	Deduct money from wallet */
					if ($wallet_id != '') {
						$user_info 	= $this->get_user_info($institute_id, $role);
						$NAME 		= $user_info['NAME'];
						$MOBILE 	= $user_info['MOBILE'];
						$EMAIL 		= $user_info['EMAIL'];

						$tableName4 	= "offline_payments";
						$tabFields4 	= "(PAYMENT_ID, TRANSACTION_TYPE,USER_ID,USER_ROLE,USER_FULLNAME,USER_EMAIL,USER_MOBILE,PAYMENT_AMOUNT,PAYMENT_MODE,PAYMENT_DATE,PAYMENT_STATUS,PAYMENT_REMARK,WALLET_ID,ACTIVE,CREATED_BY, CREATED_ON,CREATED_BY_IP,STUDENT_ID)";
						$insertVals4	= "(NULL, 'DEBIT','$institute_id','$role', '$NAME','$EMAIL','$MOBILE','$totalToPay','OFFLINE',NOW(), 'success', 'Admission Confirmed','$wallet_id', '1','$created_by',NOW(),'$created_by_ip','$student_id')";
						$insertSql4	= parent::insertData($tableName4, $tabFields4, $insertVals4);
						$exSql5		= parent::execQuery($insertSql4);

						$payment_id1 = parent::last_id();

						$sqlwallet = "UPDATE wallet SET TOTAL_BALANCE= TOTAL_BALANCE - $totalToPay, UPDATED_BY='$created_by', UPDATED_ON=NOW(),UPDATED_ON_IP='$created_by_ip' WHERE WALLET_ID='$wallet_id'";
						$reswallet = parent::execQuery($sqlwallet);

						//insert payment table
						$tableName5 = 'institute_payments';
						$tabFields5 = "(RECIEPT_NO, INSTITUTE_ID, TOTAL_EXAM_FEES,TOTAL_EXAM_FEES_RECIEVED,TOTAL_EXAM_FEES_BALANCE,PAYMENT_DATE,PAYMENT_CATEGORY, CREATED_BY, CREATED_ON, CREATED_ON_IP)";
						$insertVals5 = "(generate_admin_reciept_num(),'$institute_id','$totalToPay','$totalToPay',0,NOW(),'Admission Confirmed','$created_by',NOW(),'$created_by_ip')";
						$insertSql5 = parent::insertData($tableName5, $tabFields5, $insertVals5);
						$exSql6		= parent::execQuery($insertSql5);
					}

					$sql1 = "UPDATE student_course_details SET ADMISSION_CONFIRMED='1' WHERE STUD_COURSE_DETAIL_ID=$stud_course_detail_id";
					$exSql4	= parent::execQuery($sql1);

					if ($exSql4) {

						$tableName41 		= "student_payments_installments";
						if ($filecount4 >= 1) {
							for ($j = 0; $j < $filecount4; $j++) {
								$installment_name 	= parent::test(isset($_POST['installment_name' . $j]) ? $_POST['installment_name' . $j] : '');
								$installment_amount = parent::test(isset($_POST['installment_amount' . $j]) ? $_POST['installment_amount' . $j] : '');
								$installment_date 	= parent::test(isset($_POST['installment_date' . $j]) ? $_POST['installment_date' . $j] : '');

								if ($installment_name != '' && $installment_amount != '' && $installment_date != '') {

									$tabFields41 	= "(INSTALLMENT_ID,STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID,DATE,AMOUNT,INSTALLMENT_NAME,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_AT)";
									$insertVals41	= "(NULL, '$student_id', '$institute_id','$inst_course_id','$installment_date','$installment_amount','$installment_name','1','0','$created_by',NOW())";
									$insertSql41		= parent::insertData($tableName41, $tabFields41, $insertVals41);
									$exec41   	 = parent::execQuery($insertSql41);
								}
							}
						}

						$courseImgPathDir 		= 	STUDENT_DOCUMENTS_PATH . '/' . $student_id . '/';

						$tableName6 			= "student_files";
						/* upload files */
						if ($stud_photo != '') {
							$ext 			= pathinfo($_FILES["stud_photo"]["name"], PATHINFO_EXTENSION);
							$file_name 		= STUD_PHOTO . '_' . mt_rand(0, 123456789) . '.' . $ext;
							$tabFields6 	= "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,FILE_DESC,ACTIVE,CREATED_BY,CREATED_ON)";
							$insertVals6	= "(NULL, '$student_id', '$file_name','" . STUD_PHOTO . "','','1','$created_by',NOW())";
							$insertSql6		= parent::insertData($tableName6, $tabFields6, $insertVals6);
							$exec6   		= parent::execQuery($insertSql6);


							$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
							$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
							$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
							@mkdir($courseImgPathDir, 0777, true);
							//@mkdir($courseImgThumbPathDir,0777,true);								
							parent::create_thumb_img($_FILES["stud_photo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
							//parent::create_thumb_img($_FILES["stud_photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
						}
						// if($stud_photo_id!='')
						// {								
						// 	$ext 			= pathinfo($_FILES["stud_photo_id"]["name"], PATHINFO_EXTENSION);
						// 	$file_name 		= STUD_PHOTO_ID.'_'.mt_rand(0,123456789).'.'.$ext;
						// 	if($photo_id_category_other!='') 
						// 		$photo_id_category = $photo_id_category_other;
						// 	$tabFields7 	= "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,FILE_CATEGORY,FILE_DESC,ACTIVE,CREATED_BY,CREATED_ON)";
						// 	$insertVals7	= "(NULL, '$student_id', '$file_name','".STUD_PHOTO_ID."','$photo_id_category','$stud_photo_id_desc','1','$created_by',NOW())";
						// 	$insertSql7		= parent::insertData($tableName6,$tabFields7,$insertVals7);
						// 	$exec7   		= parent::execQuery ($insertSql7);								


						// 	$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						// 	$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						// 	$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						// 	@mkdir($courseImgPathDir,0777,true);
						// 	//@mkdir($courseImgThumbPathDir,0777,true);								
						// 	parent::create_thumb_img($_FILES["stud_photo_id"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
						// 	//parent::create_thumb_img($_FILES["stud_photo_id"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
						// }
						if ($stud_sign != '') {
							$ext 			= pathinfo($_FILES["stud_sign"]["name"], PATHINFO_EXTENSION);
							$file_name 		= STUD_PHOTO_SIGN . '_' . mt_rand(0, 123456789) . '.' . $ext;
							$tabFields8 	= "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,FILE_DESC,ACTIVE,CREATED_BY,CREATED_ON)";
							$insertVals8	= "(NULL, '$student_id', '$file_name','" . STUD_PHOTO_SIGN . "','','1','$created_by',NOW())";
							$insertSql8		= parent::insertData($tableName6, $tabFields8, $insertVals8);
							$exec8   		= parent::execQuery($insertSql8);

							$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
							$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
							$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
							@mkdir($courseImgPathDir, 0777, true);
							//@mkdir($courseImgThumbPathDir,0777,true);								
							parent::create_thumb_img($_FILES["stud_sign"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
							//parent::create_thumb_img($_FILES["stud_photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);

						}
					}

					$sql2 = "UPDATE student_course_details SET ADMISSION_CONFIRMED='1' WHERE STUD_COURSE_DETAIL_ID=$stud_course_detail_id";
					$exSql7		= parent::execQuery($sql2);

					if ($exSql7) {

						$instcourse = parent::get_inst_course_info($inst_course_id);
						$COURSE_ID = isset($instcourse['COURSE_ID']) ? $instcourse['COURSE_ID'] : '';
						$MULTI_SUB_COURSE_ID = isset($instcourse['MULTI_SUB_COURSE_ID']) ? $instcourse['MULTI_SUB_COURSE_ID'] : '';
						$TYPING_COURSE_ID = isset($instcourse['TYPING_COURSE_ID']) ? $instcourse['TYPING_COURSE_ID'] : '';

						$aicpe_course_id = $COURSE_ID;
						$aicpe_course_id_multi = $MULTI_SUB_COURSE_ID;
						$course_id_typing = $TYPING_COURSE_ID;
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
				}
				$sql18 = "UPDATE student_enquiry SET REGISTRATION=1, ADMISSION_BY='$created_by_id' WHERE ENQUIRY_ID='$enquiry_id'";
				parent::execQuery($sql18);
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New student has been added successfully!';
				//send sms
				$message = "Hello $fname, Your admission is confirmed.\r\n Your login crediantial is \r\n Username : $uname \r\n Password : $confpword \r\n Please login on portal.";
				//parent::trigger_sms($message,$mobile);		

			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the student.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function update_student_direct_admission()
{
    $errors = array();  // array to hold validation errors
    $data = array();        // array to pass back data

    $student_id = parent::test(isset($_POST['student_id']) ? $_POST['student_id'] : '');

    if (!$student_id) {
        die("Student ID is required.");
    }

    // Query to check if QR file already exists
    $sqlCheckQR = "SELECT QRFILE,STUDENT_CODE FROM student_details WHERE STUDENT_ID = '$student_id'";
    $result = parent::execQuery($sqlCheckQR);

    if ($result) {
        $result = $result->fetch_assoc();
        $file = HTTP_HOST_ADMIN . '/' . $result['QRFILE'];
        $studcode = $result['STUDENT_CODE'];
        if ($result && !empty($result['QRFILE']) && file_exists($file)) {
            // echo "QR code already exists: " . $result['QRFILE'];
        } else {
            include('resources/phpqrcode/qrlib.php');
            $text = STUDENT_VERIFY_QRURL . 'verify_student=1&code=' . $studcode;
            $path = 'resources/studentDetailsQR/' . $student_id . '/';

            if (!file_exists($path)) {
                @mkdir($path, 0777, true);
            }

            $file = $path . uniqid() . ".png";
            $ecc = 'L';
            $pixel_Size = 100;

            QRcode::png($text, $file, $ecc, $pixel_Size);

            // Update the QR file in the database
            $sqlQR = "UPDATE student_details SET QRFILE = '$file' WHERE STUDENT_ID = '$student_id'";
            $exSqlQR = parent::execQuery($sqlQR);

            if ($exSqlQR) {
                echo "QR code generated and updated: " . $file;
            } else {
                echo "Failed to update QR code in the database.";
            }
        }
    }

    $abbreviation = strtoupper(parent::test($_POST['abbreviation'] ?? ''));
    $fname = strtoupper(parent::test($_POST['fname'] ?? ''));
    $mname = strtoupper(parent::test($_POST['mname'] ?? ''));
    $lname = strtoupper(parent::test($_POST['lname'] ?? ''));
    $mothername = strtoupper(parent::test($_POST['mothername'] ?? ''));

    // FIX: Uncomment and properly handle certificate checkbox values
    $cert_mname = parent::test($_POST['cert_mname'] ?? '0');
    $cert_lname = parent::test($_POST['cert_lname'] ?? '0');


    $mobile = parent::test($_POST['mobile'] ?? '');
    $roll_number = parent::test($_POST['roll_number'] ?? '');
    $mobile2 = parent::test($_POST['mobile2'] ?? '');
    $email = parent::test($_POST['email'] ?? '');
    $dob = parent::test($_POST['dob'] ?? '');
    $gender = parent::test($_POST['gender'] ?? '');
    $per_add = parent::test($_POST['per_add'] ?? '');
    $state = parent::test($_POST['state'] ?? '');
    $city = parent::test($_POST['city'] ?? '');
    $postcode = parent::test($_POST['pincode'] ?? '');
    $caste = parent::test($_POST['caste'] ?? '');
    $qualification = parent::test($_POST['qualification'] ?? '');
    $occupation = parent::test($_POST['occupation'] ?? '');
    $admission_date = parent::test($_POST['admission_date'] ?? '');
    $filecount4 = parent::test($_POST['filecount4'] ?? '');
    $adharid = parent::test($_POST['adharid'] ?? '');
    $interested_course = parent::test($_POST['interested_course'] ?? '');
    $status = parent::test($_POST['status'] ?? 1);
    $stud_course_detail_id = parent::test($_POST['stud_course_detail_id'] ?? '');
    $display_status = parent::test($_POST['display_status'] ?? '');
    $sonof = strtoupper(parent::test($_POST['sonof'] ?? ''));
    $batch = strtoupper(parent::test($_POST['batch'] ?? ''));

    if ($interested_course == '') {
        $errors['interested_course'] = "Required! Select Course.";
    }

    $inst_course_id = $interested_course;
    $photo_id_category = parent::test($_POST['photo_id_category'] ?? '');
    $photo_id_category_other = parent::test($_POST['photo_id_category_other'] ?? '');
    $stud_photo_id_desc = parent::test($_POST['stud_photo_id_desc'] ?? '');

    $stud_photo = isset($_FILES['stud_photo']['name']) ? $_FILES['stud_photo']['name'] : '';
    $stud_photo_id = isset($_FILES['stud_photo_id']['name']) ? $_FILES['stud_photo_id']['name'] : '';
    $stud_sign = isset($_FILES['stud_sign']['name']) ? $_FILES['stud_sign']['name'] : '';

    //payment details
    $coursefees = isset($_POST['coursefees']) ? $_POST['coursefees'] : 0;
    $discrate = isset($_POST['discrate']) ? $_POST['discrate'] : '';
    $discamt = isset($_POST['discamt']) ? $_POST['discamt'] : 0;
    $totalcoursefee = isset($_POST['totalcoursefee']) ? $_POST['totalcoursefee'] : 0;
    $amtrecieved = isset($_POST['amtrecieved']) ? $_POST['amtrecieved'] : 0;
    $amtbalance = isset($_POST['amtbalance']) ? $_POST['amtbalance'] : 0;
    $payremarks = isset($_POST['payremarks']) ? $_POST['payremarks'] : '';
    $payment_id = isset($_POST['payment_id']) ? $_POST['payment_id'] : '';

    $minAmount = parent::get_instituteMinFees($inst_course_id);

    if ($discamt == 0 || $discamt == '') {
        $totalcoursefee = $coursefees;
        $discamt = 0;
    }

    $examtype1 = isset($_POST['examtype1']) ? $_POST['examtype1'] : '';
    $examstatus1 = isset($_POST['examstatus1']) ? $_POST['examstatus1'] : '';

    $inst_course_id = $interested_course;

    if ($examtype1 == '') $errors['examtype1'] = 'Please select exam mode!';

    $institute_id = parent::test($_POST['institute_id'] ?? '');
    $staff_id = parent::test($_POST['staff_id'] ?? '');

    $role = $_SESSION['user_role'];
    $created_by_id = ($role == 5) ? $_SESSION['user_id'] : 0;
    $updated_by = $_SESSION['user_fullname'];
    $created_by_ip = $_SESSION['ip_address'];

    /* check validations */
    //required validations 
    $requiredArr = array('dob' => $dob, 'fname' => $fname, 'mobile' => $mobile, 'roll_number' => $roll_number);
    $checkRequired = parent::valid_required($requiredArr);
    if (!empty($checkRequired)) {
        foreach ($checkRequired as $value)
            $errors[$value] = 'Required field!';
    }

    // validate strings
    $stringArr = array('fname' => $fname);
    $checkString = parent::valid_string($stringArr);
    if (!empty($checkString)) {
        foreach ($checkString as $value)
            $errors[$value] = 'Only letters and white space allowed!';
    }

    if ($mobile != '') {
        $valid_mob = parent::valid_mobile($mobile);
        if ($valid_mob != '') $errors['mobile'] = $valid_mob;
    }
    if ($mobile2 != '') {
        $valid_mob2 = parent::valid_mobile($mobile2);
        if ($valid_mob2 != '') $errors['mobile2'] = $valid_mob2;
    }
    if ($postcode != '') {
        if (strlen($postcode) != 6)
            $errors['postcode'] = 'Postal code must be in number and 6 digits only.';
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $postcode)) {
            $errors['postcode'] = "Only letters and white space allowed";
        }
    }
    if ($dob != '') {
        $dob = @date('Y-m-d', strtotime($dob));
    }
    if ($admission_date != '') {
        $admission_date = @date('Y-m-d', strtotime($admission_date));
    }

    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors'] = $errors;
        $data['message'] = 'Please correct all the errors.';
    } else {
        parent::start_transaction();
        
        //student personal details - FIX: Include certificate fields in update
        $tableName = "student_details";
         $setValues = "ABBREVIATION='$abbreviation',STUDENT_FNAME='$fname',STUDENT_MNAME='$mname',STUDENT_LNAME='$lname',STUDENT_MOTHERNAME='$mothername',STUDENT_DOB='$dob',STUDENT_GENDER='$gender',STUDENT_MOBILE='$mobile', STUDENT_MOBILE2='$mobile2',STUDENT_EMAIL='$email',STUDENT_PER_ADD='$per_add',STUDENT_STATE='$state', STUDENT_CITY='$city', STUDENT_PINCODE='$postcode',STUDENT_ADHAR_NUMBER='$stud_photo_id_desc',SONOF='$sonof', ACTIVE='$status', UPDATED_BY='$updated_by', UPDATED_ON=NOW(),ROLL_NUMBER='$roll_number',DISPLAY_FORM_STATUS='$display_status',EDUCATIONAL_QUALIFICATION='$qualification',OCCUPATION='$occupation',CASTE='$caste',CERT_MNAME='$cert_mname',CERT_LNAME='$cert_lname'";
        $whereClause = " WHERE STUDENT_ID='$student_id'";
        $updateSql = parent::updateData($tableName, $setValues, $whereClause);
        $exSql = parent::execQuery($updateSql);

        // Rest of your existing code continues...
        $instcourse = parent::get_inst_course_info($inst_course_id);
        $COURSE_ID = isset($instcourse['COURSE_ID']) ? $instcourse['COURSE_ID'] : '';
        $MULTI_SUB_COURSE_ID = isset($instcourse['MULTI_SUB_COURSE_ID']) ? $instcourse['MULTI_SUB_COURSE_ID'] : '';
        $TYPING_COURSE_ID = isset($instcourse['TYPING_COURSE_ID']) ? $instcourse['TYPING_COURSE_ID'] : '';

        $aicpe_course_id = $COURSE_ID;
        $aicpe_course_id_multi = $MULTI_SUB_COURSE_ID;
        $course_id_typing = $TYPING_COURSE_ID;
        $tableName1 = "student_course_details";

        $instcourse = parent::get_inst_course_info($inst_course_id);
        $COURSE_ID = isset($instcourse['COURSE_ID']) ? $instcourse['COURSE_ID'] : '';
        $MULTI_SUB_COURSE_ID = isset($instcourse['MULTI_SUB_COURSE_ID']) ? $instcourse['MULTI_SUB_COURSE_ID'] : '';
        $TYPING_COURSE_ID = isset($instcourse['TYPING_COURSE_ID']) ? $instcourse['TYPING_COURSE_ID'] : '';

        $aicpe_course_id = $COURSE_ID;
        $aicpe_course_id_multi = $MULTI_SUB_COURSE_ID;
        $course_id_typing = $TYPING_COURSE_ID;
        $tableName1 = "student_course_details";

        // FIX: Always update exam type and batch regardless of course type
        $setValues9 = "EXAM_STATUS='$examstatus1', EXAM_TYPE='$examtype1', BATCH_ID='$batch',UPDATED_BY='$updated_by', UPDATED_ON=NOW(),UPDATED_ON_IP='$created_by_ip',ADMISSION_DATE='$admission_date',COURSE_FEES='$coursefees',DISCOUNT_RATE='$discrate',DISCOUNT_AMOUNT='$discamt',TOTAL_COURSE_FEES='$totalcoursefee',FEES_RECIEVED='$amtrecieved',FEES_BALANCE='$amtbalance',REMARKS='$payremarks'";

        // Set demo count based on exam type and status
        if ($examtype1 == '1' && $examstatus1 == '2') $setValues9 .= ",DEMO_COUNT=0";
        if ($examtype1 == '1' && $examstatus1 == '1') $setValues9 .= ",DEMO_COUNT=0";
        if ($examtype1 == '1' && $examstatus1 == '3') $setValues9 .= ",DEMO_COUNT=10";

        $whereClause9 = " WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
        $updateSql9 = parent::updateData($tableName1, $setValues9, $whereClause9);
        $exSql9 = parent::execQuery($updateSql9);

        // Update payment table
        $tableName145 = "student_payments";
        $setValues145 = "COURSE_FEES='$coursefees',TOTAL_COURSE_FEES='$totalcoursefee',FEES_PAID='$amtrecieved',FEES_BALANCE='$amtbalance',PAYMENT_NOTE='$payremarks'";
        $whereClause145 = " WHERE PAYMENT_ID='$payment_id'";
        $updateSql145 = parent::updateData($tableName145, $setValues145, $whereClause145);
        $exSql145 = parent::execQuery($updateSql145);

        // Handle specific course type validations only for regular courses
        if ($aicpe_course_id !== '') {
            $valid_exam = parent::validate_apply_exam($aicpe_course_id, '', '');
            if (!empty($valid_exam)) {
                $success_flag = isset($valid_exam['success']) ? $valid_exam['success'] : '';
                if ($success_flag == true) {
                    $exam_modes = isset($valid_exam['exam_modes']) ? $valid_exam['exam_modes'] : '';
                    $exam_modes = json_decode($exam_modes);
                    if (!in_array($examtype1, $exam_modes)) {
                        // Log warning but don't prevent update
                        error_log("Warning: Exam type $examtype1 not in allowed modes for course $aicpe_course_id");
                    }
                }
            }
        }

        $courseImgPathDir = STUDENT_DOCUMENTS_PATH . '/' . $student_id . '/';
        $tableName3 = "student_files";

        /* upload files */
        if ($stud_photo != '') {
            $ext = pathinfo($_FILES["stud_photo"]["name"], PATHINFO_EXTENSION);
            $file_name = STUD_PHOTO . '_' . mt_rand(0, 123456789) . '.' . $ext;

            $sqlUpd = "UPDATE student_files SET DELETE_FLAG=0, ACTIVE=0 WHERE STUDENT_ID='$student_id' AND FILE_LABEL='" . STUD_PHOTO . "'";
            $exec311 = parent::execQuery($sqlUpd);

            $tabFields3 = "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
            $insertVals3 = "(NULL, '$student_id', '$file_name','" . STUD_PHOTO . "','1','$updated_by',NOW(),'$created_by_ip')";
            $insertSql3 = parent::insertData($tableName3, $tabFields3, $insertVals3);
            $exec3 = parent::execQuery($insertSql3);

            $courseImgPathFile = $courseImgPathDir . '' . $file_name;
            @mkdir($courseImgPathDir, 0777, true);
            parent::create_thumb_img($_FILES["stud_photo"]["tmp_name"], $courseImgPathFile, $ext, 800, 750);
        }

        if ($stud_photo_id != '') {
            $ext = pathinfo($_FILES["stud_photo_id"]["name"], PATHINFO_EXTENSION);
            $file_name = STUD_PHOTO_ID . '_' . mt_rand(0, 123456789) . '.' . $ext;
            if ($photo_id_category_other != '')
                $photo_id_category = $photo_id_category_other;
            $tabFields3 = "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,FILE_CATEGORY,FILE_DESC,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
            $insertVals3 = "(NULL, '$student_id', '$file_name','" . STUD_PHOTO_ID . "','$photo_id_category','$stud_photo_id_desc','1','$updated_by',NOW(),'$created_by_ip')";
            $insertSql3 = parent::insertData($tableName3, $tabFields3, $insertVals3);
            $exec3 = parent::execQuery($insertSql3);

            $courseImgPathFile = $courseImgPathDir . '' . $file_name;
            @mkdir($courseImgPathDir, 0777, true);
            parent::create_thumb_img($_FILES["stud_photo_id"]["tmp_name"], $courseImgPathFile, $ext, 800, 750);
        }

        if ($stud_photo_id_desc != '') {
            $tableName5 = "student_files";
            $setValues5 = "FILE_DESC='$stud_photo_id_desc', UPDATED_BY='$updated_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip'";
            $whereClause5 = " WHERE FILE_ID='$photo_id_desc_id'";
            $updateSql5 = parent::updateData($tableName5, $setValues5, $whereClause5);
            $exSql5 = parent::execQuery($updateSql5);
        }

        if ($stud_sign != '') {
            $ext = pathinfo($_FILES["stud_sign"]["name"], PATHINFO_EXTENSION);
            $file_name = STUD_PHOTO_SIGN . '_' . mt_rand(0, 123456789) . '.' . $ext;

            $sqlUpd1 = "UPDATE student_files SET DELETE_FLAG=0, ACTIVE=0 WHERE STUDENT_ID='$student_id' AND FILE_LABEL='" . STUD_PHOTO_SIGN . "'";
            $exec811 = parent::execQuery($sqlUpd1);

            $tabFields8 = "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
            $insertVals8 = "(NULL, '$student_id', '$file_name','" . STUD_PHOTO_SIGN . "','1','$updated_by',NOW(), '$created_by_ip')";
            $insertSql8 = parent::insertData($tableName3, $tabFields8, $insertVals8);
            $exec8 = parent::execQuery($insertSql8);

            $courseImgPathFile = $courseImgPathDir . '' . $file_name;
            @mkdir($courseImgPathDir, 0777, true);
            parent::create_thumb_img($_FILES["stud_sign"]["tmp_name"], $courseImgPathFile, $ext, 800, 750);
        }

        // Handle installments
        $tableName41 = "student_payments_installments";
        if ($filecount4 >= 1) {
            for ($j = 0; $j < $filecount4; $j++) {
                $installment_id = parent::test(isset($_POST['installment_id' . $j]) ? $_POST['installment_id' . $j] : '');
                $installment_name = parent::test(isset($_POST['installment_name' . $j]) ? $_POST['installment_name' . $j] : '');
                $installment_amount = parent::test(isset($_POST['installment_amount' . $j]) ? $_POST['installment_amount' . $j] : '');
                $installment_date = parent::test(isset($_POST['installment_date' . $j]) ? $_POST['installment_date' . $j] : '');

                if ($installment_id == '') {
                    $tabFields41 = "(INSTALLMENT_ID,STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID,DATE,AMOUNT,INSTALLMENT_NAME,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_AT)";
                    $insertVals41 = "(NULL, '$student_id', '$institute_id','$inst_course_id','$installment_date','$installment_amount','$installment_name','1','0','$updated_by',NOW())";
                    $insertSql41 = parent::insertData($tableName41, $tabFields41, $insertVals41);
                    $exec41 = parent::execQuery($insertSql41);
                } else {
                    $setValues41 = "DATE='$installment_date', AMOUNT='$installment_amount', INSTALLMENT_NAME='$installment_name',UPDATED_BY='$updated_by', UPDATED_AT=NOW()";
                    $whereClause41 = " WHERE INSTALLMENT_ID='$installment_id'";
                    $updateSql41 = parent::updateData($tableName41, $setValues41, $whereClause41);
                    $exSql41 = parent::execQuery($updateSql41);
                }
            }
        }

        /* Handle educational and experience details if needed */
        // Note: You may need to uncomment and adjust these sections based on your form
        /*
        $tableName = "student_educational_details";
        $sql = "DELETE FROM $tableName WHERE STUDENT_ID='$student_id'";
        parent::execQuery($sql);
        for ($i = 1; $i <= $max_edu; $i++) {
            // Educational details processing...
        }

        $tableName = "student_experience_details";
        $sql = "DELETE FROM $tableName WHERE STUDENT_ID='$student_id'";
        parent::execQuery($sql);
        for ($i = 1; $i <= $max_exp; $i++) {
            // Experience details processing...
        }
        */
        
        parent::commit();
        $data['success'] = true;
        $data['message'] = 'Success! Student has been updated successfully!';
    }
    return json_encode($data);
}
	//list student from institute
	// public function list_student_direct_admission($student_id='', $institute_id='', $staff_id='', $cond='')
	// {
	// 	$data = '';
	//     $sql= "SELECT A.*, get_student_name(A.STUDENT_ID) AS STUDENT_FULLNAME,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME, get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_address(A.INSTITUTE_ID) as INSTITUTE_ADDRESS,get_institute_mobile(A.INSTITUTE_ID) as INSTITUTE_MOBILE, get_stud_photo(A.STUDENT_ID) AS  STUD_PHOTO, DATE_FORMAT(A.STUDENT_DOB, '%d-%m-%Y') AS STUD_DOB_FORMATED,DATE_FORMAT(A.DATE_JOINING, '%d-%m-%Y') JOINING_FORMATED, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS ACCOUNT_REGISTERED_DATE, B.USER_NAME, B.USER_LOGIN_ID,C.STUD_COURSE_DETAIL_ID,C.INSTITUTE_COURSE_ID,C.COURSE_FEES,C.DISCOUNT_RATE,C.DISCOUNT_AMOUNT,C.TOTAL_COURSE_FEES,C.FEES_RECIEVED,C.FEES_BALANCE,C.REMARKS,C.PAYMENT_RECIEVED_FLAG,C.PAYMENT_ID,C.DEMO_COUNT,C.EXAM_STATUS,C.EXAM_TYPE,C.EXAM_ATTEMPT,C.ADMISSION_CONFIRMED,C.OFFLINE_PAYMENT_ID,C.BATCH_ID FROM student_details A LEFT JOIN user_login_master B ON A.STUDENT_ID=B.USER_ID AND B.USER_ROLE=4 LEFT JOIN student_course_details C ON A.STUDENT_ID=C.STUDENT_ID WHERE A.DELETE_FLAG=0 ";
	// 	if($student_id!='')
	// 	{
	// 		$sql .= " AND A.STUDENT_ID='$student_id' ";
	// 	}
	// 	if($institute_id!='')
	// 	{
	// 		$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
	// 	}
	// 	if($staff_id!='')
	// 	{
	// 		$sql .= " AND A.STAFF_ID='$staff_id' ";
	// 	}
	// 	if($cond!='')
	// 		$sql .= " $cond";
	// 	$sql .= 'ORDER BY A.CREATED_ON DESC';
	// 	//echo $sql; exit();
	// 	$res = parent:: execQuery($sql);
	// 	if($res && $res->num_rows>0)
	// 		$data = $res;
	// 	return $data;
	// }

	public function list_student_direct_admission($student_id = '', $institute_id = '', $staff_id = '', $cond = '')
	{

		$data = '';
		$sql = "SELECT A.*,get_student_name(B.STUDENT_ID) AS STUDENT_FULLNAME,get_institute_name(B.INSTITUTE_ID) AS INSTITUTE_NAME, get_institute_code(B.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_address(B.INSTITUTE_ID) as INSTITUTE_ADDRESS,get_institute_mobile(B.INSTITUTE_ID) as INSTITUTE_MOBILE, get_stud_photo(B.STUDENT_ID) AS  STUD_PHOTO, DATE_FORMAT(B.STUDENT_DOB, '%d-%m-%Y') AS STUD_DOB_FORMATED,DATE_FORMAT(B.DATE_JOINING, '%d-%m-%Y') JOINING_FORMATED,B.STUDENT_MOBILE, B.REFFERAL_CODE,B.DISPLAY_FORM_STATUS,B.ROLL_NUMBER,B.ABBREVIATION,B.STUDENT_CODE,B.STUDENT_FNAME,B.STUDENT_MNAME,B.STUDENT_LNAME,B.STUDENT_MOTHERNAME,B.STUDENT_DOB,B.STUDENT_GENDER,B.STUDENT_MOBILE,B.STUDENT_MOBILE2,B.STUDENT_EMAIL,B.STUDENT_TEMP_ADD,B.STUDENT_PER_ADD,B.STUDENT_STATE,B.STUDENT_CITY,B.STUDENT_PINCODE,B.STUDENT_ADHAR_NUMBER,B.EDUCATIONAL_QUALIFICATION,B.OCCUPATION,B.INTERESTS,B.CERT_MNAME,B.CERT_LNAME,B.SONOF,B.DATE_JOINING,B.ENQUIRY_ID,B.STUD_LANG,B.VERIFIED,B.CASTE, DATE_FORMAT(C.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS ACCOUNT_REGISTERED_DATE, C.USER_NAME, C.USER_LOGIN_ID,C.PASS_WORD  FROM student_course_details A LEFT JOIN student_details B ON A.STUDENT_ID=B.STUDENT_ID LEFT JOIN user_login_master C ON A.STUDENT_ID=C.USER_ID AND C.USER_ROLE=4  WHERE A.DELETE_FLAG=0 ";
		if ($student_id != '') {
			$sql .= " AND A.STUDENT_ID='$student_id' ";
		}
		if ($institute_id != '') {
			$sql .= " AND B.INSTITUTE_ID='$institute_id' ";
		}
		if ($staff_id != '') {
			$sql .= " AND B.STAFF_ID='$staff_id' ";
		}
		if ($cond != '') {
			$sql .= " $cond";
		}
		$sql .= ' ORDER BY B.CREATED_ON DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//delete student enquiry

	/* validate institute code */
	public function delete_student_direct_admission($enq_id = '')
	{
		$sql = "UPDATE student_enquiry SET DELETE_FLAG=1 WHERE ENQUIRY_ID='$enq_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}

	////Ends Direct Admission

	//list student from institute
	public function list_student($student_id = '', $institute_id = '', $staff_id = '')
	{
		$data = '';
		$sql = "SELECT A.*, get_student_name(A.STUDENT_ID) AS STUDENT_FULLNAME,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME, get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_address(A.INSTITUTE_ID) as INSTITUTE_ADDRESS,(SELECT F.CITY_NAME as city_name FROM city_master F WHERE A.INSTITUTE_ID=F.CITY_ID) as INSTITUTE_CITY,get_institute_state(A.INSTITUTE_ID) as INSTITUTE_STATE,get_institute_mobile(A.INSTITUTE_ID) as INSTITUTE_MOBILE, get_stud_photo(A.STUDENT_ID) AS  STUD_PHOTO,get_stud_sign(A.STUDENT_ID) AS STUD_SIGN, DATE_FORMAT(A.STUDENT_DOB, '%d-%m-%Y') AS STUD_DOB_FORMATED,DATE_FORMAT(A.DATE_JOINING, '%d-%m-%Y') JOINING_FORMATED, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS ACCOUNT_REGISTERED_DATE, B.USER_NAME, B.USER_LOGIN_ID FROM student_details A LEFT JOIN user_login_master B ON A.STUDENT_ID=B.USER_ID AND B.USER_ROLE=4 WHERE A.DELETE_FLAG=0 ";
		if ($student_id != '') {
			$sql .= " AND A.STUDENT_ID='$student_id' ";
		}
		if ($institute_id != '') {
			$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
		}
		if ($staff_id != '') {
			$sql .= " AND A.STAFF_ID='$staff_id' ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//list student educational info
	public function list_student_educational_info($edu_datail_id = '', $student_id = '')
	{
		$data = '';
		$sql = "SELECT A.*, DATE_FORMAT(A.START_DATE, '%d-%m-%Y') AS START_DATE_FORMATTED, DATE_FORMAT(A.START_DATE, '%Y') AS START_DATE_YEAR, DATE_FORMAT(A.END_DATE, '%d-%m-%Y') AS END_DATE_FORMATTED, DATE_FORMAT(A.END_DATE, '%Y') AS END_DATE_YEAR FROM student_educational_details A WHERE 1";
		if ($edu_datail_id != '') {
			$sql .= " AND A.STUDENT_EDUCATIONAL_ID='$edu_datail_id' ";
		}
		if ($student_id != '') {
			$sql .= " AND A.STUDENT_ID='$student_id' ";
		}

		//$sql .= 'ORDER BY A.CREATED_ON ASC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//list student educational info
	public function list_student_experience_info($exp_datail_id = '', $student_id = '')
	{
		$data = '';
		$sql = "SELECT A.*, DATE_FORMAT(A.START_DATE, '%d-%m-%Y') AS START_DATE_FORMATTED,DATE_FORMAT(A.END_DATE, '%d-%m-%Y') AS END_DATE_FORMATTED FROM student_experience_details A WHERE 1";
		if ($student_id != '') {
			$sql .= " AND A.STUDENT_ID='$student_id' ";
		}
		if ($exp_datail_id != '') {
			$sql .= " AND A.STUDENT_EXPERIENCE_ID='$exp_datail_id' ";
		}


		$sql .= 'ORDER BY A.CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//list student courses 
	public function list_student_courses($course_detail_id = '', $student_id = '', $course_type = '')
	{
		$data = '';

		$sql = "SELECT A.*,get_institute_demo_count(A.INSTITUTE_ID) AS INSTITUTE_DEMO_COUNT, get_student_name(A.STUDENT_ID) AS STUDENT_NAME,get_student_code(A.STUDENT_ID) AS STUDENT_CODE , (SELECT C.EXAM_STATUS FROM exam_status_master C WHERE C.EXAM_STATUS_ID=A.EXAM_STATUS) AS EXAM_STATUS_NAME, (SELECT D.EXAM_TYPE FROM exam_types_master D WHERE D.EXAM_TYPE_ID=A.EXAM_TYPE) AS EXAM_TYPE_NAME, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON,'%d %M %Y') AS ACCOUNT_REGISTERED_DATE FROM student_course_details A LEFT JOIN user_login_master B ON A.STUDENT_ID=B.USER_ID  WHERE A.DELETE_FLAG=0 AND B.USER_ROLE=4 ";
		if ($course_detail_id != '') {
			$sql .= " AND A.STUD_COURSE_DETAIL_ID='$course_detail_id' ";
		}
		if ($student_id != '') {
			$sql .= " AND A.STUDENT_ID='$student_id' ";
		}

		$sql .= 'ORDER BY A.CREATED_ON DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	/* display student course details info */
	public function show_stud_course_info($stud_id)
	{
		$courseDetail = '';
		$courses = $this->list_student_courses('', $stud_id, '');
		$courseDetail .= '<table  class="table table-bordered table-hover">
									<tr>
										<th>#</th>
										<th>Course Name</th>
										<!-- <th>Course Duration</th>
										<th>Exam Fees</th> -->
										<th>Course Type</th>
										<th>Status</th>
										<!-- <th>Action</th> -->
									</tr>';
		$courseSrNo = 1;
		while ($courseData = $courses->fetch_assoc()) {
			$STUD_COURSE_DETAIL_ID = $courseData['STUD_COURSE_DETAIL_ID'];
			$COURSE_ID 	= $courseData['COURSE_ID'];
			$COURSE_TYPE = $courseData['COURSE_TYPE'];
			$ACTIVE = $courseData['ACTIVE'];

			$COURSE_TYPE_NAME = $courseData['COURSE_TYPE_NAME'];
			$COURSE_INFO = parent::get_course_detail($COURSE_ID, $COURSE_TYPE);
			$COURSE_NAME = isset($COURSE_INFO['COURSE_NAME']) ? $COURSE_INFO['COURSE_NAME'] : '';
			$COURSE_FEES = isset($COURSE_INFO['COURSE_FEES']) ? $COURSE_INFO['COURSE_FEES'] : '';
			$COURSE_DURATION = isset($COURSE_INFO['COURSE_DURATION']) ? $COURSE_INFO['COURSE_DURATION'] : '';
			$COURSE_FEES = $COURSE_INFO['COURSE_FEES'];
			if ($ACTIVE == 1)
				$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentCourseStatus(' . $STUD_COURSE_DETAIL_ID . ',0)"><i class="fa fa-check"></i></a>';
			elseif ($ACTIVE == 0)
				$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentCourseStatus(' . $STUD_COURSE_DETAIL_ID . ',1)"><i class="fa fa-times"></i></a>';

			$action = '<a href="#"><i class="fa fa-pencil"></i></a>';
			$courseDetail .= "<tr><td>$courseSrNo</td>
							  <td>$COURSE_NAME</td>	
							  <!-- <td>$COURSE_DURATION</td>	
							  <td>$COURSE_FEES</td>	 -->										 
							  <td>$COURSE_TYPE_NAME</td>	
							  <td>$ACTIVE</td>	
							 <!-- <td>$action</td> --> </tr>";
			$courseSrNo++;
		}
		$courseDetail .= '</table>';
		return $courseDetail;
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
	// public function generate_student_code($inst_id='')
	// {
	// 	$code = '';
	// 	$sql = "SELECT generate_student_code($inst_id) AS STUD_CODE";		
	// 	$res = parent::execQuery($sql);
	// 	if($res && $res->num_rows>0)
	// 	{
	// 		$data = $res->fetch_assoc();
	// 		$code = $data['STUD_CODE'];
	// 	}
	// 	return $code;
	// }

	/* generate student code */
	public function generate_student_code()
	{
		$code = '';
		$code = parent::getRandomCode(8);
		$sql = "SELECT STUDENT_CODE FROM student_details WHERE STUDENT_CODE='$code'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_student_code();
		}
		return $code;
	}

	/* generate student receipt number */
	public function generate_student_receipt_no()
	{
		$code = '';
		$code = parent::getRandomCode3();
		$sql = "SELECT RECIEPT_NO FROM student_payments WHERE RECIEPT_NO ='$code'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_student_receipt_no();
		}
		return $code;
	}

	/* generate student code */
	public function generate_student_exam_secrete_code()
	{
		$code = '';
		$code = parent::getRandomCode(8);
		$sql = "SELECT EXAM_SECRETE_CODE FROM student_course_details WHERE EXAM_SECRETE_CODE='$code'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_student_exam_secrete_code();
		}
		return $code;
	}
	/* add new student 
	@param: 
	@return: json
	*/
	public function add_student()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$enquiry_id 		= parent::test(isset($_POST['enquiry_id']) ? $_POST['enquiry_id'] : '');
		$institute_id		= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : '');
		$staff_id			= parent::test(isset($_POST['staff_id']) ? $_POST['staff_id'] : '');

		$studcode 		= $this->generate_student_code();
		$disp_course_type	= parent::test(isset($_POST['disp_course_type']) ? $_POST['disp_course_type'] : '');
		$inst_course_id	= parent::test(isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '');

		$abbreviation	 	= strtoupper(parent::test(isset($_POST['abbreviation']) ? $_POST['abbreviation'] : ''));
		$fname	 		= strtoupper(parent::test(isset($_POST['fname']) ? $_POST['fname'] : ''));
		$mname	 		= strtoupper(parent::test(isset($_POST['mname']) ? $_POST['mname'] : ''));
		$lname	 		= strtoupper(parent::test(isset($_POST['lname']) ? $_POST['lname'] : ''));

		$cert_mname	 	= parent::test(isset($_POST['cert_mname']) ? $_POST['cert_mname'] : '');
		$cert_lname	 	= parent::test(isset($_POST['cert_lname']) ? $_POST['cert_lname'] : '');

		$mothername	 	= strtoupper(parent::test(isset($_POST['mothername']) ? $_POST['mothername'] : ''));

		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$mobile2 			= parent::test(isset($_POST['mobile2']) ? $_POST['mobile2'] : '');
		$email 			= parent::test(isset($_POST['email']) ? $_POST['email'] : '');
		$dob		 		= parent::test(isset($_POST['dob']) ? $_POST['dob'] : '');
		$gender	 		= parent::test(isset($_POST['gender']) ? $_POST['gender'] : '');
		$adharid	 		= parent::test(isset($_POST['adharid']) ? $_POST['adharid'] : '');
		$qualification	= parent::test(isset($_POST['qualification']) ? $_POST['qualification'] : '');
		$occupation		= parent::test(isset($_POST['occupation']) ? $_POST['occupation'] : '');

		$per_add			= parent::test(isset($_POST['per_add']) ? $_POST['per_add'] : '');
		$state 			= parent::test(isset($_POST['state']) ? $_POST['state'] : '');
		$city 			= parent::test(isset($_POST['city']) ? $_POST['city'] : '');
		$postcode 		= parent::test(isset($_POST['pincode']) ? $_POST['pincode'] : '');
		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : 1);

		///////
		$sonof	 	= strtoupper(parent::test(isset($_POST['sonof']) ? $_POST['sonof'] : ''));
		$doj		 		= parent::test(isset($_POST['doj']) ? $_POST['doj'] : '');

		if ($doj != '')
			$doj = @date('Y-m-d', strtotime($doj));

		$curr_date = date('Y-m-d');

		$newEndingDate = date("Y-m-d", strtotime($curr_date . " - 1 year"));

		if ($doj < $newEndingDate) {
			echo $errors['doj'] = "Date Should be greater than one year span";
		}




		$photo_id_category = parent::test(isset($_POST['photo_id_category']) ? $_POST['photo_id_category'] : '');
		$photo_id_category_other = parent::test(isset($_POST['photo_id_category_other']) ? $_POST['photo_id_category_other'] : '');

		//  $stud_photo_id_type = 	parent::test(isset($_POST['stud_photo_id_type'])?$_POST['stud_photo_id_type']:'');
		$stud_photo_id_desc = parent::test(isset($_POST['stud_photo_id_desc']) ? $_POST['stud_photo_id_desc'] : '');
		/* Files */
		$stud_photo		= isset($_FILES['stud_photo']['name']) ? $_FILES['stud_photo']['name'] : '';
		$stud_photo_id		= isset($_FILES['stud_photo_id']['name']) ? $_FILES['stud_photo_id']['name'] : '';

		//payment details				

		$course 			= isset($_POST['course']) ? $_POST['course'] : '';
		$coursefees 		= isset($_POST['coursefees']) ? $_POST['coursefees'] : 0;
		$discrate 		= isset($_POST['discrate']) ? $_POST['discrate'] : '';
		$discamt 			= isset($_POST['discamt']) ? $_POST['discamt'] : 0;
		$totalcoursefee	= isset($_POST['totalcoursefee']) ? $_POST['totalcoursefee'] : 0;
		$amtrecieved 		= isset($_POST['amtrecieved']) ? $_POST['amtrecieved'] : 0;
		$amtbalance 		= isset($_POST['amtbalance']) ? $_POST['amtbalance'] : 0;
		$payremarks 		= isset($_POST['payremarks']) ? $_POST['payremarks'] : '';
		$countcourses 	= isset($_POST['countcourses']) ? $_POST['countcourses'] : '';

		//payment validations
		if ($course != '' && is_array($course) && !empty($course)) {
			foreach ($course as $key => $value) {
				if ($discamt[$key] != 0 && $discamt[$key] != '' && !parent::valid_decimal($discamt[$key])) {
					$errors['discamt'] = 'Please enter valid discount amount.';
				}
				if ($amtrecieved[$key] != 0 && $amtrecieved[$key] != '' && !parent::valid_decimal($amtrecieved[$key])) {
					$errors['amtrecieved'] = 'Please enter valid recieved amount.';
				}
				if ($amtrecieved[$key] != 0 && $amtrecieved[$key] != '' && !parent::valid_decimal($amtrecieved[$key])) {
					$errors['amtrecieved'] = 'Please enter valid recieved amount.';
				}
				if ($amtbalance[$key] < 0) {
					$errors['amtbalance'] = 'Invalid balance amount. Please check the course fees and recieved fees.';
				}
			}
			$countcourses = count($course);
		}

		$uname 			= $studcode;
		$exam_secrete_code = $this->generate_student_exam_secrete_code();
		$confpword 		= parent::generate_password();

		$role 				= 4; //student login role
		$created_by_id  		= ($_SESSION['user_role'] == 5) ? $_SESSION['user_id'] : 0;
		$created_by  		= $_SESSION['user_fullname'];
		$user_login_id  	= $_SESSION['user_login_id'];
		$created_by_ip  	= $_SESSION['ip_address'];
		$stud_course_id = array();
		/* check validations */
		//required validations 
		$requiredArr = array('dob' => $dob, 'fname' => $fname, 'mobile' => $mobile, 'gender' => $gender, 'course' => $course);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}

		if ($photo_id_category == 'Other' && $photo_id_category_other == '') {
			$errors['photo_id_category_other'] = 'Required! Enter Photo ID type name.';
		}
		if ($stud_photo_id_desc == '' && $stud_photo_id != '') {
			$errors['stud_photo_id_desc'] = 'Required! Enter Photo ID number.';
		}
		if ($stud_photo_id_desc != '' && $stud_photo_id == '') {
			$errors['stud_photo_id'] = 'Required! Upload Photo ID file.';
		}
		if ($photo_id_category != '' && $stud_photo_id_desc == '')
			$errors['stud_photo_id_desc'] = 'Required! Enter Photo ID number.';
		if ($photo_id_category != '' && $stud_photo_id == '')
			$errors['stud_photo_id'] = 'Required! Upload Photo ID file.';

		// validate strings
		/*	$stringArr= array('fname'=>$fname,'mname'=>$mname,'lname'=>$lname);
		$checkString = parent::valid_string($stringArr);
		if(!empty($checkString)){
			foreach($checkString as $value)
				$errors[$value] = 'Only letters and white space allowed!';			
		}*/

		if (!parent::valid_username($uname))
			$errors['uname'] = 'Sorry! Username is already used.';
		// if(!parent::valid_student_email($email,''))
		//	$errors['email'] = 'Sorry! Email is already used.';
		if (!$this->validate_student_code($studcode, ''))
			$errors['studcode'] = 'Sorry! Student code <strong>' . $studcode . '</strong> is already used.';

		/*if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$errors['email'] = "Invalid email format";
				}*/

		if ($mobile != '') {
			$valid_mob = parent::valid_mobile($mobile);
			if ($valid_mob != '') $errors['mobile'] = $valid_mob;
		}
		if ($mobile2 != '') {
			$valid_mob2 = parent::valid_mobile($mobile2);
			if ($valid_mob2 != '') $errors['mobile2'] = $valid_mob2;
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

		/*	if($doj!='') 
			$doj = @date('Y-m-d', strtotime($doj));
			*/
		/* files validations */

		// validate images format			
		$imageArr = array('stud_photo' => $stud_photo, 'stud_photo_id' => $stud_photo_id);
		$checkImage = parent::valid_image_format($imageArr);
		if (!empty($checkImage)) {
			foreach ($checkImage as $value)
				$errors[$value] = 'Invalid file format! Please select valid file!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "student_details";
			$tabFields 	= "(STUDENT_ID,INSTITUTE_ID, STAFF_ID,ABBREVIATION,STUDENT_CODE, STUDENT_FNAME,STUDENT_MNAME,STUDENT_LNAME,STUDENT_MOTHERNAME,STUDENT_DOB,STUDENT_GENDER,STUDENT_MOBILE,STUDENT_MOBILE2,STUDENT_EMAIL,STUDENT_PER_ADD,STUDENT_STATE,STUDENT_CITY,STUDENT_PINCODE,STUDENT_ADHAR_NUMBER,EDUCATIONAL_QUALIFICATION,OCCUPATION,ENQUIRY_ID,SONOF,DATE_JOINING,ACTIVE, CREATED_BY, CREATED_ON, CREATED_ON_IP,CERT_MNAME,CERT_LNAME)";

			$insertVals	= "(NULL, '$institute_id','$staff_id','$abbreviation','$studcode', '$fname','$mname','$lname','$mothername','$dob','$gender','$mobile','$mobile2','$email','$per_add','$state','$city','$postcode','$adharid','$qualification','$occupation','$enquiry_id','$sonof','$doj','$status','$created_by',NOW(),'$created_by_ip','$cert_mname','$cert_lname')";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				/* -----Get the last insert ID ----- */
				$student_id = parent::last_id();

				//student course fees details 
				if ($course != '' && is_array($course) && !empty($course)) {
					//print_r($coursefees);
					for ($key = 0; $key < count($course); $key++) {
						$inst_course_id	= isset($course[$key]) ? $course[$key] : 0;
						$course_fees	= isset($coursefees[$key]) ? $coursefees[$key] : 0;
						$disc_rate	 	= isset($discrate[$key]) ? $discrate[$key] : '';
						$disc_amt	 	= isset($discamt[$key]) ? $discamt[$key] : 0;
						$total_course_fee = isset($totalcoursefee[$key]) ? $totalcoursefee[$key] : 0;
						$amt_recieved	= isset($amtrecieved[$key]) ? $amtrecieved[$key] : 0;
						$amt_balance	= isset($amtbalance[$key]) ? $amtbalance[$key] : 0;
						$pay_remarks	= isset($payremarks[$key]) ? $payremarks[$key] : '';
						if ($disc_amt == 0 || $disc_amt == '') {
							$total_course_fee = $course_fees;
						}
						/*if($amt_recieved==0 || $amt_recieved==''){
									$amt_recieved = $course_fees;
								}*/
						$tableName4 	= "student_course_details";
						$tabFields4 	= "(STUD_COURSE_DETAIL_ID, STUDENT_ID,INSTITUTE_ID,STAFF_ID,INSTITUTE_COURSE_ID, COURSE_FEES,DISCOUNT_RATE,DISCOUNT_AMOUNT,TOTAL_COURSE_FEES,FEES_RECIEVED, FEES_BALANCE, REMARKS, PAYMENT_RECIEVED_FLAG,EXAM_STATUS,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON,CREATED_ON_IP)";

						$insertVals4	= "(NULL, '$student_id','$institute_id', '$staff_id', '$inst_course_id','$course_fees','$disc_rate','$disc_amt','$total_course_fee', '$amt_recieved','$amt_balance','$pay_remarks',1,1,'1','0','$created_by',NOW(), '$created_by_ip')";
						$insertSql4 = parent::insertData($tableName4, $tabFields4, $insertVals4);
						$exSql4			= parent::execQuery($insertSql4);

						if ($exSql4) {
							//student payment details									
							$stud_course_detail_id = parent::last_id();
							$tableName5 	= "student_payments";
							$tabFields5 	= "(PAYMENT_ID, RECIEPT_NO,STUDENT_ID,INSTITUTE_ID,STAFF_ID,INSTITUTE_COURSE_ID,STUD_COURSE_DETAIL_ID, COURSE_FEES, TOTAL_COURSE_FEES, FEES_PAID, FEES_BALANCE, FEES_PAID_DATE, PAYMENT_NOTE,ACTIVE,DELETE_FLAG, CREATED_BY, CREATED_ON, CREATED_ON_IP)";
							$insertVals5	= "(NULL,generate_institute_reciept_num($institute_id), '$student_id', '$institute_id', '$staff_id','$inst_course_id', '$stud_course_detail_id','$course_fees','$total_course_fee','$amt_recieved','$amt_balance',NOW(),'$pay_remarks','1','0','$created_by', NOW(), '$created_by_ip')";
							$insertSql5		= parent::insertData($tableName5, $tabFields5, $insertVals5);
							$exSql5			= parent::execQuery($insertSql5);
							$payment_id = parent::last_id();
							//update the first payment id
							$sql = "UPDATE student_course_details SET PAYMENT_ID='$payment_id' WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
							parent::execQuery($sql);
							array_push($stud_course_id, $stud_course_detail_id);
						}
					}
				}
				// student login details
				$tableName2 	= "user_login_master";
				$tabFields2 	= "(USER_LOGIN_ID, USER_ID, USER_NAME, PASS_WORD,USER_ROLE, ACCOUNT_REGISTERED_ON,ACTIVE, CREATED_BY,CREATED_ON,CREATED_ON_IP)";
				$insertVals2	= "(NULL, '$student_id', '$uname', MD5('$mobile'),'$role',NOW(),'$status','$created_by',NOW(), '$created_by_ip')";
				$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
				$exSql2			= parent::execQuery($insertSql2);
				if ($exSql2) {
					//$courseImgPathDir 		= 	STUDENT_DOCUMENTS_PATH.'/'.$student_id.'/';

					$bucket_directory = 'student/' . $student_id . '/';

					$tableName3 			= "student_files";
					/* upload files */
					if ($stud_photo != '') {
						$ext 			= pathinfo($_FILES["stud_photo"]["name"], PATHINFO_EXTENSION);
						$file_name 		= STUD_PHOTO . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tabFields3 	= "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,FILE_DESC,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
						$insertVals3	= "(NULL, '$student_id', '$file_name','" . STUD_PHOTO . "','','1','$created_by',NOW(), '$created_by_ip')";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$s3_obj = new S3Class();
						$activityContent = $_FILES['stud_photo']['name'];
						$fileTempName = $_FILES['stud_photo']['tmp_name'];
						$new_width = 800;
						$new_height = 750;
						$image_p = imagecreatetruecolor($new_width, $new_height);
						$image = imagecreatefromstring(file_get_contents($fileTempName));
						imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

						$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
						imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

						$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["stud_photo"]["type"]));

						//var_dump($response);
						//exit();

						/*$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
								@mkdir($courseImgPathDir,0777,true);*/
						//@mkdir($courseImgThumbPathDir,0777,true);								
						//parent::create_thumb_img($_FILES["stud_photo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
						//parent::create_thumb_img($_FILES["stud_photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					if ($stud_photo_id != '') {
						$ext 			= pathinfo($_FILES["stud_photo_id"]["name"], PATHINFO_EXTENSION);
						$file_name 		= STUD_PHOTO_ID . '_' . mt_rand(0, 123456789) . '.' . $ext;
						if ($photo_id_category_other != '')
							$photo_id_category = $photo_id_category_other;
						$tabFields3 	= "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,FILE_CATEGORY,FILE_DESC,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
						$insertVals3	= "(NULL, '$student_id', '$file_name','" . STUD_PHOTO_ID . "','$photo_id_category','$stud_photo_id_desc','1','$created_by',NOW(), '$created_by_ip')";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$s3_obj = new S3Class();
						$activityContent = $_FILES['stud_photo_id']['name'];
						$fileTempName = $_FILES['stud_photo_id']['tmp_name'];
						$new_width = 800;
						$new_height = 750;
						$image_p = imagecreatetruecolor($new_width, $new_height);
						$image = imagecreatefromstring(file_get_contents($fileTempName));
						imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

						$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
						imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

						$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["stud_photo_id"]["type"]));

						//var_dump($response);
						//exit();


						/*
								$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
								@mkdir($courseImgPathDir,0777,true);*/
						//@mkdir($courseImgThumbPathDir,0777,true);								
						//parent::create_thumb_img($_FILES["stud_photo_id"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
						//parent::create_thumb_img($_FILES["stud_photo_id"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					$sql = "UPDATE student_enquiry SET REGISTRATION=1, ADMISSION_BY='$created_by_id' WHERE ENQUIRY_ID='$enquiry_id'";
					parent::execQuery($sql);
					parent::commit();
				}

				//send email
				//require_once(ROOT."/include/email/config.php");						
				//require_once(ROOT."/include/email/templates/student_admission_success.php");
				/**require_once("../email/config.php");				
						//require_once("../email/templates/student_admission_success.php");*/
				$data['success'] = true;
				$data['message'] = 'Success! New student has been added successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the student.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function update_student_admission()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$stud_course_detail_id 		= parent::test(isset($_POST['stud_course_detail_id']) ? $_POST['stud_course_detail_id'] : '');
		$payment_id 		= isset($_POST['payment_id']) ? $_POST['payment_id'] : '';
		//payment details				
		$course 			= isset($_POST['course']) ? $_POST['course'] : '';
		$coursefees 		= isset($_POST['coursefees']) ? $_POST['coursefees'] : 0;
		$discrate 		= isset($_POST['discrate']) ? $_POST['discrate'] : '';
		$discamt 			= isset($_POST['discamt']) ? $_POST['discamt'] : 0;
		$totalcoursefee	= isset($_POST['totalcoursefee']) ? $_POST['totalcoursefee'] : 0;
		$amtrecieved 		= isset($_POST['amtrecieved']) ? $_POST['amtrecieved'] : 0;
		$amtbalance 		= isset($_POST['amtbalance']) ? $_POST['amtbalance'] : 0;
		$payremarks 		= isset($_POST['payremarks']) ? $_POST['payremarks'] : '';

		$countcourses 	= isset($_POST['countcourses']) ? $_POST['countcourses'] : '';

		//payment validations
		if ($course != '' && is_array($course) && !empty($course)) {
			foreach ($course as $key => $value) {
				if ($discamt[$key] != 0 && $discamt[$key] != '' && !parent::valid_decimal($discamt[$key])) {
					$errors['discamt'] = 'Please enter valid discount amount.';
				}
				if ($amtrecieved[$key] != 0 && $amtrecieved[$key] != '' && !parent::valid_decimal($amtrecieved[$key])) {
					$errors['amtrecieved'] = 'Please enter valid recieved amount.';
				}
				/*if($amtrecieved[$key]!=0 && $amtrecieved[$key]!='' && !parent::valid_decimal($amtrecieved[$key])){
					$errors['amtrecieved'] = 'Please enter valid recieved amount.';
				}*/
				if ($amtbalance[$key] < 0) {
					$errors['amtbalance'] = 'Invalid balance amount! Please check the Course Fees and Recieved Fees.';
				}
			}
			$countcourses = count($course);
		}
		$created_by  		= $_SESSION['user_fullname'];
		$user_login_id  	= $_SESSION['user_login_id'];
		$created_by_ip  	= $_SESSION['ip_address'];
		//required validations 
		$requiredArr = array('course' => $course);
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
			//student course fees details 
			if ($course != '' && is_array($course) && !empty($course)) {

				for ($key = 0; $key < count($course); $key++) {
					$inst_course_id	= isset($course[$key]) ? $course[$key] : 0;
					$course_fees	= isset($coursefees[$key]) ? $coursefees[$key] : 0;
					$disc_rate	 	= isset($discrate[$key]) ? $discrate[$key] : '';
					$disc_amt	 	= isset($discamt[$key]) ? $discamt[$key] : 0;
					$total_course_fee = isset($totalcoursefee[$key]) ? $totalcoursefee[$key] : 0;
					$amt_recieved	= isset($amtrecieved[$key]) ? $amtrecieved[$key] : 0;
					$amt_balance	= isset($amtbalance[$key]) ? $amtbalance[$key] : 0;
					$pay_remarks	= isset($payremarks[$key]) ? $payremarks[$key] : '';
					if ($disc_amt == 0 || $disc_amt == '') {
						$total_course_fee = $course_fees;
					}
					/*if($amt_recieved==0 || $amt_recieved==''){
							$amt_recieved = $course_fees;
						}*/
					$tableName 	= "student_course_details";
					$setValues 	= "INSTITUTE_COURSE_ID='$inst_course_id', COURSE_FEES='$course_fees',DISCOUNT_RATE='$disc_rate',DISCOUNT_AMOUNT='$disc_amt',TOTAL_COURSE_FEES='$total_course_fee',FEES_RECIEVED='$amt_recieved', FEES_BALANCE='$amt_balance', REMARKS='$pay_remarks', UPDATED_BY='$created_by',UPDATED_ON=NOW(),UPDATED_ON_IP='$created_by_ip'";

					$whereClause = " WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
					$updSql = parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updSql);
					if ($exSql) {
						//student payment details
						$tableName 	= "student_payments";
						$setValues 	= "INSTITUTE_COURSE_ID='$inst_course_id',STUD_COURSE_DETAIL_ID='$stud_course_detail_id', COURSE_FEES='$course_fees', TOTAL_COURSE_FEES='$total_course_fee', FEES_PAID='$amt_recieved', FEES_BALANCE='$amt_balance', PAYMENT_NOTE='$pay_remarks', UPDATED_BY='$created_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip'";
						$whereClause = " WHERE PAYMENT_ID='$payment_id' AND STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
						$insertSql5		= parent::updateData($tableName, $setValues, $whereClause);
						$exSql5			= parent::execQuery($insertSql5);

						//update all course 
						$sql = "SELECT STUD_COURSE_DETAIL_ID FROM student_payments WHERE PAYMENT_ID='$payment_id' LIMIT 0,1";
						$res = parent::execQuery($sql);
						if ($res && $res->num_rows > 0) {
							$data = $res->fetch_assoc();
							$stud_course_detail_id = $data['STUD_COURSE_DETAIL_ID'];

							$tableName 	= "student_payments";
							$setValues 	= "INSTITUTE_COURSE_ID='$inst_course_id',STUD_COURSE_DETAIL_ID='$stud_course_detail_id', COURSE_FEES='$course_fees', TOTAL_COURSE_FEES='$total_course_fee', UPDATED_BY='$created_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip'";

							$whereClause 	= " WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
							$insertSql5		= parent::updateData($tableName, $setValues, $whereClause);
							$exSql5			= parent::execQuery($insertSql5);
						}
					}
				}
			}
			$data['success'] = true;
			$data['message'] = 'Success! Admission has been updated successfully!';
		}
		return json_encode($data);
	}

	/* update institute 
	@param: 
	@return: json
	*/
	public function update_student()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$student_id 		= parent::test(isset($_POST['student_id']) ? $_POST['student_id'] : '');
		$enquiry_id 		= parent::test(isset($_POST['enquiry_id']) ? $_POST['enquiry_id'] : '');
		$institute_id		= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : '');
		$staff_id			= parent::test(isset($_POST['staff_id']) ? $_POST['staff_id'] : '');

		//$studcode 		= parent::test(isset($_POST['studcode'])?$_POST['studcode']:'');
		$disp_course_type	= parent::test(isset($_POST['disp_course_type']) ? $_POST['disp_course_type'] : '');
		$inst_course_id	= parent::test(isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '');

		$abbreviation	 	= strtoupper(parent::test(isset($_POST['abbreviation']) ? $_POST['abbreviation'] : ''));
		$fname	 		= strtoupper(parent::test(isset($_POST['fname']) ? $_POST['fname'] : ''));
		$mname	 		= strtoupper(parent::test(isset($_POST['mname']) ? $_POST['mname'] : ''));
		$lname	 		= strtoupper(parent::test(isset($_POST['lname']) ? $_POST['lname'] : ''));
		$mothername	 	= strtoupper(parent::test(isset($_POST['mothername']) ? $_POST['mothername'] : ''));

		$cert_mname 			= parent::test(isset($_POST['cert_mname']) ? $_POST['cert_mname'] : '');
		$cert_lname 			= parent::test(isset($_POST['cert_lname']) ? $_POST['cert_lname'] : '');
		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$mobile2 			= parent::test(isset($_POST['mobile2']) ? $_POST['mobile2'] : '');
		$email 			= parent::test(isset($_POST['email']) ? $_POST['email'] : '');
		$dob		 		= parent::test(isset($_POST['dob']) ? $_POST['dob'] : '');
		$gender	 		= parent::test(isset($_POST['gender']) ? $_POST['gender'] : '');
		$adharid	 		= parent::test(isset($_POST['adharid']) ? $_POST['adharid'] : '');
		$qualification	= parent::test(isset($_POST['qualification']) ? $_POST['qualification'] : '');
		$occupation		= parent::test(isset($_POST['occupation']) ? $_POST['occupation'] : '');

		//
		$sonof	 	= strtoupper(parent::test(isset($_POST['sonof']) ? $_POST['sonof'] : ''));

		$per_add			= parent::test(isset($_POST['per_add']) ? $_POST['per_add'] : '');
		$state 			= parent::test(isset($_POST['state']) ? $_POST['state'] : '');
		$city 			= parent::test(isset($_POST['city']) ? $_POST['city'] : '');
		$postcode 		= parent::test(isset($_POST['pincode']) ? $_POST['pincode'] : '');
		$interests 		= parent::test(isset($_POST['interests']) ? $_POST['interests'] : '');
		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : 1);

		// educational details
		$max_edu 			= parent::test(isset($_POST['max_edu']) ? $_POST['max_edu'] : 0);
		$max_exp 			= parent::test(isset($_POST['max_exp']) ? $_POST['max_exp'] : 0);

		$photo_id_category = parent::test(isset($_POST['photo_id_category']) ? $_POST['photo_id_category'] : '');
		$photo_id_category_other = parent::test(isset($_POST['photo_id_category_other']) ? $_POST['photo_id_category_other'] : '');
		if ($photo_id_category == '' && $photo_id_category_other != '')
			$photo_id_category = $photo_id_category_other;
		//  $stud_photo_id_type = parent::test(isset($_POST['stud_photo_id_type'])?$_POST['stud_photo_id_type']:'');
		$stud_photo_id_desc = parent::test(isset($_POST['stud_photo_id_desc']) ? $_POST['stud_photo_id_desc'] : '');
		$photo_id_desc_id = parent::test(isset($_POST['photo_id_desc_id']) ? $_POST['photo_id_desc_id'] : '');

		//payment details		 
		$amtpaid 			= parent::test(isset($_POST['amtpaid']) ? $_POST['amtpaid'] : 0);
		$paymentnote		= parent::test(isset($_POST['paymentnote']) ? $_POST['paymentnote'] : '');
		$disp_course_fees = parent::test(isset($_POST['disp_course_fees']) ? $_POST['disp_course_fees'] : 0);
		$disp_course_name = parent::test(isset($_POST['disp_course_name']) ? $_POST['disp_course_name'] : '');
		$disp_course_type = parent::test(isset($_POST['disp_course_type']) ? $_POST['disp_course_type'] : '');
		$disp_amtbalance 	= parent::test(isset($_POST['disp_amtbalance']) ? $_POST['disp_amtbalance'] : '');

		// $uname 			= $studcode;		  
		$confpword 		= parent::generate_password();
		/* Files */
		$stud_photo		= isset($_FILES['stud_photo']['name']) ? $_FILES['stud_photo']['name'] : '';
		$stud_photo_id		= isset($_FILES['stud_photo_id']['name']) ? $_FILES['stud_photo_id']['name'] : '';

		$role 				= 4; //student login role
		$updated_by  		= $_SESSION['user_fullname'];
		$created_by_ip  	= $_SESSION['ip_address'];
		/* check validations */
		//required validations 
		$requiredArr = array('dob' => $dob, 'fname' => $fname, 'mobile' => $mobile, 'gender' => $gender);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}
		// validate strings
		/*	$stringArr= array('fname'=>$fname,'mname'=>$mname,'lname'=>$lname);
		$checkString = parent::valid_string($stringArr);
		if(!empty($checkString)){
			foreach($checkString as $value)
				$errors[$value] = 'Only letters and white space allowed!';			
		}*/

		// if(!parent::valid_username($uname))
		//	$errors['uname'] = 'Sorry! Username is already used.';
		// if(!parent::valid_student_email($email,$student_id))
		//	$errors['email'] = 'Sorry! Email is already used.';
		//if(!$this->validate_student_code($studcode,''))
		//$errors['studcode'] = 'Sorry! Student code is already present.';

		/*if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$errors['email'] = "Invalid email format";
				}*/

		if ($mobile != '') {
			$valid_mob = parent::valid_mobile($mobile);
			if ($valid_mob != '') $errors['mobile'] = $valid_mob;
		}
		if ($mobile2 != '') {
			$valid_mob2 = parent::valid_mobile($mobile2);
			if ($valid_mob2 != '') $errors['mobile2'] = $valid_mob2;
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

		/*	if($doj!='') 
			$doj = @date('Y-m-d', strtotime($doj));*/

		/* files validations */

		// validate images format			
		$imageArr = array('stud_photo' => $stud_photo, 'stud_photo_id' => $stud_photo_id);
		$checkImage = parent::valid_image_format($imageArr);
		if (!empty($checkImage)) {
			foreach ($checkImage as $value)
				$errors[$value] = 'Invalid file format! Please select valid file!';
		}
		//payment validations
		if ($amtpaid != 0 && $amtpaid != '' && !parent::valid_decimal($amtpaid)) {
			$errors['amtpaid'] = 'Please enter valid amount.';
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			//student personal details
			$tableName 	= "student_details";
			$setValues 	= "ABBREVIATION='$abbreviation',STUDENT_FNAME='$fname',STUDENT_MNAME='$mname',STUDENT_LNAME='$lname',CERT_MNAME='$cert_mname', CERT_LNAME='$cert_lname',STUDENT_MOTHERNAME='$mothername',STUDENT_DOB='$dob',STUDENT_GENDER='$gender',STUDENT_MOBILE='$mobile', STUDENT_MOBILE2='$mobile2',STUDENT_EMAIL='$email',STUDENT_PER_ADD='$per_add',STUDENT_STATE='$state', STUDENT_CITY='$city', STUDENT_PINCODE='$postcode',STUDENT_ADHAR_NUMBER='$adharid', EDUCATIONAL_QUALIFICATION='$qualification', OCCUPATION='$occupation',INTERESTS='$interests',SONOF='$sonof',DATE_JOINING='$doj', ACTIVE='$status', UPDATED_BY='$updated_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip'";
			$whereClause = " WHERE STUDENT_ID='$student_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);


			//$courseImgPathDir 		= STUDENT_DOCUMENTS_PATH.'/'.$student_id.'/';

			$bucket_directory = 'student/' . $student_id . '/';

			$tableName3 			= "student_files";
			/* upload files */
			if ($stud_photo != '') {
				$ext 			= pathinfo($_FILES["stud_photo"]["name"], PATHINFO_EXTENSION);
				$file_name 		= STUD_PHOTO . '_' . mt_rand(0, 123456789) . '.' . $ext;

				$sqlUpd = "UPDATE student_files SET DELETE_FLAG=0, ACTIVE=0 WHERE STUDENT_ID='$student_id' AND FILE_LABEL='" . STUD_PHOTO . "'";
				parent::execQuery($sqlUpd);

				$tabFields3 	= "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
				$insertVals3	= "(NULL, '$student_id', '$file_name','" . STUD_PHOTO . "','1','$updated_by',NOW(),'$created_by_ip')";
				$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
				$exec3   		= parent::execQuery($insertSql3);

				$s3_obj = new S3Class();
				$activityContent = $_FILES['stud_photo']['name'];
				$fileTempName = $_FILES['stud_photo']['tmp_name'];
				$new_width = 800;
				$new_height = 750;
				$image_p = imagecreatetruecolor($new_width, $new_height);
				$image = imagecreatefromstring(file_get_contents($fileTempName));
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

				$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
				imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

				$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["stud_photo"]["type"]));

				//var_dump($response);
				//exit();

				/*	$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);*/
				//@mkdir($courseImgThumbPathDir,0777,true);								
				/*	parent::create_thumb_img($_FILES["stud_photo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;*/
				//parent::create_thumb_img($_FILES["stud_photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
			}
			if ($stud_photo_id != '') {
				$ext 			= pathinfo($_FILES["stud_photo_id"]["name"], PATHINFO_EXTENSION);
				$file_name 		= STUD_PHOTO_ID . '_' . mt_rand(0, 123456789) . '.' . $ext;
				if ($photo_id_category_other != '')
					$photo_id_category = $photo_id_category_other;
				$tabFields3 	= "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,FILE_CATEGORY,FILE_DESC,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
				$insertVals3	= "(NULL, '$student_id', '$file_name','" . STUD_PHOTO_ID . "','$photo_id_category','$stud_photo_id_desc','1','$updated_by',NOW(),'$created_by_ip')";
				$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
				$exec3   		= parent::execQuery($insertSql3);

				$s3_obj = new S3Class();
				$activityContent = $_FILES['stud_photo_id']['name'];
				$fileTempName = $_FILES['stud_photo_id']['tmp_name'];
				$new_width = 800;
				$new_height = 750;
				$image_p = imagecreatetruecolor($new_width, $new_height);
				$image = imagecreatefromstring(file_get_contents($fileTempName));
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

				$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
				imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

				$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["stud_photo_id"]["type"]));

				//var_dump($response);
				//exit();


				/*	$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);*/
				//@mkdir($courseImgThumbPathDir,0777,true);								
				/*parent::create_thumb_img($_FILES["stud_photo_id"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;*/
				//parent::create_thumb_img($_FILES["stud_photo_id"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
			}
			if ($stud_photo_id_desc != '') {
				$tableName5 	= "student_files";
				$setValues5 	= "FILE_DESC='$stud_photo_id_desc', UPDATED_BY='$updated_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip'";
				$whereClause5 = " WHERE FILE_ID='$photo_id_desc_id'";
				$updateSql5	= parent::updateData($tableName5, $setValues5, $whereClause5);
				$exSql5		= parent::execQuery($updateSql5);
			}
			/* -----------student educational details-------------------- */
			$tableName			= "student_educational_details";
			$sql = "DELETE FROM $tableName WHERE STUDENT_ID='$student_id'";
			parent::execQuery($sql);
			for ($i = 1; $i <= $max_edu; $i++) {
				$edu_coursename		= parent::test(isset($_POST['edu_coursename' . $i]) ? $_POST['edu_coursename' . $i] : '');
				$edu_instname 		= parent::test(isset($_POST['edu_instname' . $i]) ? $_POST['edu_instname' . $i] : '');
				$edu_universityname = parent::test(isset($_POST['edu_universityname' . $i]) ? $_POST['edu_universityname' . $i] : '');
				$edu_startdate 		= parent::test(isset($_POST['edu_startdate' . $i]) ? $_POST['edu_startdate' . $i] : '0000-00-00');
				$edu_enddate 		= parent::test(isset($_POST['edu_enddate' . $i]) ? $_POST['edu_enddate' . $i] : '0000-00-00');
				$edu_otherinfo 		= parent::test(isset($_POST['edu_otherinfo' . $i]) ? $_POST['edu_otherinfo' . $i] : '');
				$edu_marks 		= parent::test(isset($_POST['edu_marks' . $i]) ? $_POST['edu_marks' . $i] : '');
				if ($edu_startdate != '')
					$edu_startdate = date('Y-m-d', strtotime($edu_startdate));
				if ($edu_enddate != '')
					$edu_enddate = date('Y-m-d', strtotime($edu_enddate));
				$tabFields	= "(STUDENT_EDUCATIONAL_ID, STUDENT_ID, COURSE_NAME, INSTITUTE_NAME, UNIVERSITY_NAME, START_DATE, END_DATE, MARKS, DESCRIPTION, CREATED_BY, CREATED_ON, UPDATED_BY, UPDATED_ON, CREATED_ON_IP, UPDATED_ON_IP)";
				$insertVal	= "(NULL, '$student_id','$edu_coursename', '$edu_instname', '$edu_universityname', '$edu_startdate', '$edu_enddate', '$edu_marks','$edu_otherinfo','$updated_by',NOW(),'$updated_by',NOW(),'$created_by_ip','$created_by_ip')";
				$insertSql	= parent::insertData($tableName, $tabFields, $insertVal);
				parent::execQuery($insertSql);
			}
			/* -----------stduent educational details-------------------- */
			/* -----------student work experience details-------------------- */
			$tableName			= "student_experience_details";
			$sql = "DELETE FROM $tableName WHERE STUDENT_ID='$student_id'";
			parent::execQuery($sql);
			for ($i = 1; $i <= $max_exp; $i++) {
				$exp_jobtitle		= parent::test(isset($_POST['exp_jobtitle' . $i]) ? $_POST['exp_jobtitle' . $i] : '');
				$exp_companyname 		= parent::test(isset($_POST['exp_companyname' . $i]) ? $_POST['exp_companyname' . $i] : '');

				$exp_startdate 		= parent::test(isset($_POST['exp_startdate' . $i]) ? $_POST['exp_startdate' . $i] : '0000-00-00');
				$exp_enddate 		= parent::test(isset($_POST['exp_enddate' . $i]) ? $_POST['exp_enddate' . $i] : '0000-00-00');
				$exp_otherinfo 		= parent::test(isset($_POST['exp_otherinfo' . $i]) ? $_POST['exp_otherinfo' . $i] : '');

				if ($exp_startdate != '')
					$exp_startdate = date('Y-m-d', strtotime($exp_startdate));
				if ($exp_enddate != '')
					$exp_enddate = date('Y-m-d', strtotime($exp_enddate));



				$tabFields	= "(STUD_EXP_DETAILS_ID, STUDENT_ID, JOB_TITLE, COMPANY_NAME, START_DATE,  END_DATE, DESCRIPTION, CREATED_BY, CREATED_ON, UPDATED_BY, UPDATED_ON, CREATED_ON_IP, UPDATED_ON_IP)";
				$insertVal	= "(NULL, '$student_id','$exp_jobtitle', '$exp_companyname', '$exp_startdate', '$exp_enddate', '$exp_otherinfo', '$updated_by', NOW(), '$updated_by', NOW(), '$created_by_ip', '$created_by_ip')";
				$insertSql	= parent::insertData($tableName, $tabFields, $insertVal);
				parent::execQuery($insertSql);
			}
			/* -----------stduent experience details-------------------- */
			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! Student has been updated successfully!';
		}
		return json_encode($data);
	}

	public function get_student_docs($student_id = '', $condition = '')
	{
		$data = array();
		$sql = "SELECT *, DATE_FORMAT(CREATED_ON, '%d-%m-%Y %h:%m:%p') AS CREATED_DATE FROM student_files WHERE DELETE_FLAG=0";
		if ($student_id != '')
			$sql .= " AND STUDENT_ID='$student_id'";
		if ($condition != '')
			$sql .= " $condition";
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {

			while ($result = $res->fetch_assoc()) {
				$data[$result['FILE_ID']] = array('FILE_ID' => $result['FILE_ID'], 'FILE_MIME' => $result['FILE_MIME'], 'STUDENT_ID' => $result['STUDENT_ID'], 'FILE_NAME' => $result['FILE_NAME'], 'FILE_LABEL' => $result['FILE_LABEL'], 'FILE_CATEGORY' => $result['FILE_CATEGORY'], 'FILE_DESC' => $result['FILE_DESC'], 'CREATED_DATE' => $result['CREATED_DATE']);
			}
		}
		return $data;
	}

	/* validate institute code */
	public function delete_student_file($file_id, $stud_id = '')
	{
		$sql = "UPDATE student_files SET DELETE_FLAG=1, ACTIVE=0 WHERE FILE_ID='$file_id'";
		if ($stud_id != '')
			$sql .= " AND STUDENT_ID='$stud_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	/* change institute name website visibility flag */
	public function change_student_status($stud_id, $flag)
	{
		$sql = "UPDATE student_details SET ACTIVE='$flag' WHERE STUDENT_ID='$stud_id'";
		$sql2 = "UPDATE user_login_master SET ACTIVE='$flag' WHERE USER_ID='$stud_id' AND USER_ROLE=4";
		$res = parent::execQuery($sql);
		$res2 = parent::execQuery($sql2);
		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}

	/* change institute name website visibility flag */
	public function delete_student($stud_id)
	{
		$sql = "UPDATE student_details SET DELETE_FLAG='1', ACTIVE=0, UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE STUDENT_ID='$stud_id'";

		$sql2 = "UPDATE student_course_details SET DELETE_FLAG='1', ACTIVE=0, UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE STUDENT_ID='$stud_id'";

		$sql3 = "UPDATE student_educational_details SET DELETE_FLAG='1', ACTIVE=0, UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE STUDENT_ID='$stud_id'";

		$sql4 = "UPDATE student_experience_details SET DELETE_FLAG='1', ACTIVE=0, UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE STUDENT_ID='$stud_id'";

		$sql5 = "UPDATE student_files SET DELETE_FLAG='1', ACTIVE=0, UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE STUDENT_ID='$stud_id'";

		$sql6 = "UPDATE student_payments SET DELETE_FLAG='1', ACTIVE=0, UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE STUDENT_ID='$stud_id'";
		$res = parent::execQuery($sql2);
		$res = parent::execQuery($sql3);
		$res = parent::execQuery($sql4);
		$res = parent::execQuery($sql5);
		$res = parent::execQuery($sql6);
		$res = parent::execQuery($sql);
		if ($res) {
			$sql = "UPDATE user_login_master SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE USER_ID='$stud_id' AND USER_ROLE=4";
			$res = parent::execQuery($sql);
			return true;
		}
		return false;
	}

	/* change student status */
	public function changeStudentStatusFlag($stud_id, $flag)
	{
		$sql = "UPDATE student_details SET ACTIVE='$flag', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "' WHERE STUDENT_ID='$stud_id'";

		$sql2 = "UPDATE student_course_details SET ACTIVE='$flag', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "' WHERE STUDENT_ID='$stud_id'";

		$sql3 = "UPDATE student_educational_details SET ACTIVE='$flag', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "' WHERE STUDENT_ID='$stud_id'";

		$sql4 = "UPDATE student_experience_details SET ACTIVE='$flag', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "' WHERE STUDENT_ID='$stud_id'";

		$sql5 = "UPDATE student_files SET ACTIVE='$flag', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "' WHERE STUDENT_ID='$stud_id'";

		$sql6 = "UPDATE student_payments SET ACTIVE='$flag', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "' WHERE STUDENT_ID='$stud_id'";
		$res = parent::execQuery($sql2);
		$res = parent::execQuery($sql3);
		$res = parent::execQuery($sql4);
		$res = parent::execQuery($sql5);
		$res = parent::execQuery($sql6);
		$res = parent::execQuery($sql);
		if ($res) {
			$sql = "UPDATE user_login_master SET ACTIVE='$flag',UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "' WHERE USER_ID='$stud_id' AND USER_ROLE=4";
			$res = parent::execQuery($sql);
			return true;
		}
		return false;
	}


	//delete admission
	public function delete_student_admission($stud_course_id)
	{
		$sql = "UPDATE student_course_details SET DELETE_FLAG='1', ACTIVE=0, UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE STUD_COURSE_DETAIL_ID='$stud_course_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			$sql = "UPDATE student_payments SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE STUD_COURSE_DETAIL_ID='$stud_course_id'";
			$res = parent::execQuery($sql);
			return true;
		}
		return false;
	}
	/* add course to student */
	public function add_student_course($stud_id, $course_id, $course_type, $status = '')
	{
		$res = '';
		$sql = "INSERT INTO student_course_details (STUD_COURSE_DETAIL_ID,STUDENT_ID,COURSE_ID,COURSE_TYPE, CREATED_BY, CREATED_ON,CREATED_ON_IP) VALUES(NULL, '$stud_id', '$course_id','$course_type','" . $_SESSION['user_fullname'] . "', NOW(), '" . $_SESSION['ip_address'] . "')";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}
	// register new course for existing student
	public function add_student_new_course()
	{
		$student_id 	= parent::test(isset($_POST['student_id']) ? $_POST['student_id'] : '');
		$course_type 	= parent::test(isset($_POST['course_type']) ? $_POST['course_type'] : '');
		$course	 		= parent::test(isset($_POST['course']) ? $_POST['course'] : '');
		$examstatus	 		= parent::test(isset($_POST['examstatus']) ? $_POST['examstatus'] : '');
		$examtype	 		= parent::test(isset($_POST['examtype']) ? $_POST['examtype'] : '');
		$status	 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$errors = array();  // array to hold validation errors
		$data = array();
		$requiredArr = array('course_type' => $course_type, 'course' => $course, 'student_id' => $student_id, 'examstatus' => $examstatus, 'examtype' => $examtype);
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
			$tableName4 	= "student_course_details";
			$tabFields4 	= "(STUD_COURSE_DETAIL_ID, STUDENT_ID, COURSE_ID,COURSE_TYPE, EXAM_STATUS,EXAM_TYPE,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
			$insertVals4	= "(NULL, '$student_id', '$course', '$course_type','$examstatus','$examtype','$status','" . $_SESSION['user_fullname'] . "',NOW(), '" . $_SESSION['ip_address'] . "')";
			$insertSql4		= parent::insertData($tableName4, $tabFields4, $insertVals4);
			$exSql4			= parent::execQuery($insertSql4);
			if ($exSql4) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New course has been added successfully!';
			}
		}
		return json_encode($data);
	}

	//get studetn payment details
	public function get_payment_info($payment_id = '', $stud_id = '', $course_id = '')
	{
		$result = "";
		$sql = "SELECT *, DATE_FORMAT(FEES_PAID_DATE, '%d-%m-%Y') AS FEES_PAID_ON FROM student_payments WHERE DELETE_FLAG=0";
		if ($payment_id != '')
			$sql .= " AND PAYMENT_ID='$payment_id'";
		if ($stud_id != '')
			$sql .= " AND STUDENT_ID='$stud_id'";
		if ($course_id != '')
			$sql .= " AND COURSE_ID='$course_id'";
		$exec = parent::execQuery($sql);
		if ($exec && $exec->num_rows > 0) {
			$result = $exec;
		}
		return $result;
	}
	//display payment info
	public function display_payment_info($stud_id, $course_id)
	{
		$result = '';
		$res = $this->get_payment_info('', $stud_id, $course_id);
		if ($res !== '') {
			$result .= '<table class="table">					
					<tr>
						<th>Amount Paid</th>
						<th>Date</th>						
					</tr>';
			while ($data = $res->fetch_assoc()) {
				$PAYMENT_ID 		= $data['PAYMENT_ID'];
				$TOTAL_EXAM_FEES 	= $data['TOTAL_EXAM_FEES'];
				$FEES_PAID 			= $data['FEES_PAID'];
				$FEES_BALANCE 		= $data['FEES_BALANCE'];
				$FEES_PAID_DATE 	= $data['FEES_PAID_DATE'];
				$FEES_PAID_ON 		= $data['FEES_PAID_ON'];
				$FEES_PAYMENT_MODE 	= $data['FEES_PAYMENT_MODE'];

				$result .= '<tr>
								<td>' . $FEES_PAID . '</td>
								<td>' . $FEES_PAID_ON . '</td>
							</tr>';
			}
			$result .= ' </table>';
		}
		return $result;
	}

	public function get_student_allcourses($stud_id)
	{
		$data = '';
		$sql = "SELECT * FROM student_course_details WHERE STUDENT_ID='$stud_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}
	public function count_total_files_uploaded($stud_id)
	{
		$data = '';
		$sql = "SELECT COUNT(FILE_ID) AS TOTAL FROM student_files WHERE STUDENT_ID='$stud_id' AND DELETE_FLAG=0 AND FILE_LABEL='" . STUD_DRIVE_FILE . "'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}
		return $data;
	}
	public function store_file($stud_id)
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data
		$file		= isset($_FILES['file']['name']) ? $_FILES['file']['name'] : '';
		$created_by = isset($_SESSION['user_fullname']) ? $_SESSION['user_fullname'] : '';
		$created_by_ip = isset($_SESSION['ip_address']) ? $_SESSION['ip_address'] : '';
		if ($file == '') $errors['file'] = "Please select a file to upload.";

		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			while (list($key, $value) = each($_FILES["file"]["name"])) {
				$cover_image		= $_FILES["file"]["name"][$key];
				//if product record is not blank
				if ($cover_image != '') {
					$tmp_name		= $_FILES["file"]["tmp_name"][$key];
					$ext 			= pathinfo($_FILES["file"]["name"][$key], PATHINFO_EXTENSION);
					$file_name 		= STUD_DRIVE_FILE . '_' . mt_rand(0, 123456789) . '.' . $ext;
					$tableName3		= "student_files";
					$tabFields3 	= "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_MIME,FILE_LABEL,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
					$insertVals3	= "(NULL, '$stud_id', '$file_name','$ext','" . STUD_DRIVE_FILE . "','1','$created_by',NOW(), '$created_by_ip')";
					$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
					$exec3   		= parent::execQuery($insertSql3);


					//$courseImgPathFile 		= 	STUDENT_DOCUMENTS_PATH.'/'.$stud_id.'/'.$file_name;
					$bucket_directory = 'student/' . $stud_id . '/';

					$s3_obj = new S3Class();

					$response = $s3_obj->putObjectFile($tmp_name, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["file"]["type"][$key]));

					//var_dump($response);
					//exit();

					/*@mkdir($courseImgPathDir,0777,true);
						@move_uploaded_file($tmp_name, $courseImgPathFile);*/
				}
			}
		}
		return json_encode($data);
	}

	public function get_stud_resume($stud_id)
	{
		$res = '';
		$sql = "SELECT FILE_NAME FROM student_files WHERE STUDENT_ID='$stud_id' AND FILE_LABEL='resume'";
		$result = parent::execQuery($sql);
		if ($result && $result->num_rows > 0) {
			$data = $result->fetch_assoc();
			$res = $data['FILE_NAME'];
		}
		return $res;
	}
	public function get_stud_name($stud_id)
	{
		$res = '';
		$sql = "SELECT get_student_name('$stud_id') AS STUD_NAME";
		$result = parent::execQuery($sql);
		if ($result && $result->num_rows > 0) {
			$data = $result->fetch_assoc();
			$res = $data['STUD_NAME'];
		}
		return $res;
	}
	// list job updates matching to skills	
	public function list_jobs($job_id = '', $employer_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT *, A.ACTIVE AS JOB_STATUS, DATE_FORMAT(A.JOB_POST_DATE, '%d-%m-%Y') AS JOB_POSTED_ON, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m %p') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%m') AS UPDATED_DATE FROM employer_job_posts A LEFT JOIN employer_details B ON A.EMPLOYER_ID=B.EMPLOYER_ID WHERE A.DELETE_FLAG=0 ";

		if ($job_id != '') {
			$sql .= " AND A.JOB_POST_ID='$job_id' ";
		}
		if ($employer_id != '') {
			$sql .= " AND A.EMPLOYER_ID='$employer_id' ";
		}
		if ($cond != '') {
			$sql .= " $cond ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	public function save_bulk_student()
	{
		$errors 	= array();
		$data 		= array();

		$key 		= isset($_POST['key']) ? $_POST['key'] : '';
		$institute_id = isset($_POST['institute_id']) ? $_POST['institute_id'] : '';
		$staff_id 	= isset($_POST['staff_id']) ? $_POST['staff_id'] : '';
		$created_by 	= isset($_POST['created_by']) ? $_POST['created_by'] : '';
		$created_by_ip 	= isset($_POST['created_by_ip']) ? $_POST['created_by_ip'] : '';
		$reqData 	= isset($_POST['data']) ? json_decode($_POST['data']) : '';
		$reqData 	= (array)$reqData;
		//print_r($reqData); exit();

		$abbr	 	= parent::test(isset($reqData['abbr']) ? $reqData['abbr'] : '');
		$fname	 	= parent::test(isset($reqData['fname']) ? $reqData['fname'] : '');
		$mname	 	= parent::test(isset($reqData['mname']) ? $reqData['mname'] : '');
		$lname	 	= parent::test(isset($reqData['lname']) ? $reqData['lname'] : '');
		$cert_mname	= isset($reqData['cert_mname']) ? $reqData['cert_mname'] : true;
		$cert_lname	= isset($reqData['cert_lname']) ? $reqData['cert_lname'] : true;

		$cert_mname = ($cert_mname == true) ? 1 : 0;
		$cert_lname = ($cert_lname == true) ? 1 : 0;

		$mothername	 	= parent::test(isset($reqData['mothername']) ? $reqData['mothername'] : '');
		$adharno	= parent::test(isset($reqData['adharno']) ? $reqData['adharno'] : '');
		$mobile	 	= parent::test(isset($reqData['mobile']) ? $reqData['mobile'] : '');
		$dob	 	= parent::test(isset($reqData['dob']) ? $reqData['dob'] : '');
		$course		= parent::test(isset($reqData['course']) ? $reqData['course'] : '');

		$photoid 	= isset($_FILES['photoid' . $key]['name']) ? $_FILES['photoid' . $key]['name'] : '';
		$photo 		= isset($_FILES['photo' . $key]['name']) ? $_FILES['photo' . $key]['name'] : '';
		$studcode 	= $this->generate_student_code();
		$confpword 	= parent::generate_password();
		$uname 		= $studcode;

		$role 		= $_SESSION['user_role'];
		$created_by_id = ($role == 5) ? $_SESSION['user_id'] : 0;



		$requiredArr = array('abbr_' . $key => $abbr, 'fname_' . $key => $fname, 'dob_' . $key => $dob, 'course_' . $key => $course, 'photo' => $photo);
		$checkRequired = $this->valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}
		/*    if($fname!='' && $adharno=='')
	    {
		 	//$errors['adharno_'.$key] = "Required!";
		 	$errors['photoid_'.$key] = "Required!";
	    }	  
	    if($lname=='' && $adharno=='')
		{
			$errors['adharno_'.$key] = 'Required!';	
		}
*/
		if ($mobile != '') {
			$valid_mob = parent::valid_mobile($mobile);
			if ($valid_mob != '') $errors['mobile_' . $key] = $valid_mob;
		}
		/*	if($adharno!='' && (strlen($adharno)<12 || strlen($adharno)>12))
		{
			$errors['adharno_'.$key] = 'Invalid!';	
		}*/
		if ($photo != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($photo, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['photo_' . $key] = 'Invalid file!';
			}
		}
		/*	  	if($photoid!='')
		{
			$allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
			$extension = pathinfo($photoid, PATHINFO_EXTENSION);
			if(!in_array($extension, $allowed_ext))
			{					
				$errors['photoid_'.$key] = 'Invalid file!';
			}
		}*/

		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			//enter enquiry details				  	
			$tableName 	= "student_enquiry";
			$tabFields 	= "(ENQUIRY_ID,INSTITUTE_ID, STAFF_ID,ABBREVIATION, STUDENT_FNAME,STUDENT_MNAME,STUDENT_LNAME,STUDENT_MOTHERNAME,STUDENT_DOB,STUDENT_MOBILE,STUDENT_ADHAR_NUMBER,INSTRESTED_COURSE,ENQUIRY_BY, CREATED_BY, CREATED_ON, CREATED_ON_IP,REGISTRATION,ADMISSION_BY, CERT_MNAME,CERT_LNAME)";
			$insertVals	= "(NULL, '$institute_id','$staff_id', '$abbr','$fname','$mname','$lname','$mothername',STR_TO_DATE('$dob','%d-%m-%Y'),'$mobile','$adharno','$course','$created_by_id','$created_by',NOW(),'$created_by_ip','1','$created_by_id','$cert_mname','$cert_lname')";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				//enter admission details
				$enquiry_id = parent::last_id();
				$tableName 	= "student_details";
				$tabFields 	= "(STUDENT_ID,INSTITUTE_ID, STAFF_ID, ABBREVIATION,STUDENT_CODE, STUDENT_FNAME,STUDENT_MNAME,STUDENT_LNAME,STUDENT_MOTHERNAME,STUDENT_DOB, STUDENT_MOBILE,STUDENT_ADHAR_NUMBER,ENQUIRY_ID,ACTIVE, CREATED_BY, CREATED_ON, CREATED_ON_IP, CERT_MNAME,CERT_LNAME)";
				$insertVals	= "(NULL, '$institute_id','$staff_id','$abbr','$studcode', '$fname','$mname','$lname','$mothername',STR_TO_DATE('$dob','%d-%m-%Y'),'$mobile','$adharno','$enquiry_id','1','$created_by',NOW(),'$created_by_ip', '$cert_mname','$cert_lname')";

				$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
				$exSql		= parent::execQuery($insertSql);
				if ($exSql) {
					$student_id = parent::last_id();
					$courseinfo = parent::get_inst_course_exam_fees($course);
					$coursefees	= isset($courseinfo['COURSE_FEES']) ? $courseinfo['COURSE_FEES'] : 0;
					// enter course details
					$tableName4 	= "student_course_details";
					$tabFields4 	= "(STUD_COURSE_DETAIL_ID, STUDENT_ID,INSTITUTE_ID,STAFF_ID,INSTITUTE_COURSE_ID, COURSE_FEES,DISCOUNT_RATE,DISCOUNT_AMOUNT,TOTAL_COURSE_FEES,FEES_RECIEVED, FEES_BALANCE, REMARKS, PAYMENT_RECIEVED_FLAG,EXAM_STATUS,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON,CREATED_ON_IP)";

					$insertVals4	= "(NULL, '$student_id','$institute_id', '$staff_id', '$course','$coursefees','','0','$coursefees', '0','$coursefees','',1,1,'1','0','$created_by',NOW(), '$created_by_ip')";
					$insertSql4 = parent::insertData($tableName4, $tabFields4, $insertVals4);
					$exSql4			= parent::execQuery($insertSql4);

					if ($exSql4) {
						//enter student payment details									
						$stud_course_detail_id = parent::last_id();
						$tableName5 	= "student_payments";
						$tabFields5 	= "(PAYMENT_ID, RECIEPT_NO,STUDENT_ID,INSTITUTE_ID,STAFF_ID,INSTITUTE_COURSE_ID,STUD_COURSE_DETAIL_ID, COURSE_FEES, TOTAL_COURSE_FEES, FEES_PAID, FEES_BALANCE, FEES_PAID_DATE, PAYMENT_NOTE,ACTIVE,DELETE_FLAG, CREATED_BY, CREATED_ON, CREATED_ON_IP)";
						$insertVals5	= "(NULL,generate_institute_reciept_num($institute_id), '$student_id', '$institute_id', '$staff_id','$course', '$stud_course_detail_id','$coursefees','$coursefees','0','$coursefees',NOW(),'','1','0','$created_by', NOW(), '$created_by_ip')";
						$insertSql5		= parent::insertData($tableName5, $tabFields5, $insertVals5);
						$exSql5			= parent::execQuery($insertSql5);
						$payment_id = parent::last_id();
						//update the first payment id
						$sql = "UPDATE student_course_details SET PAYMENT_ID='$payment_id' WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
						parent::execQuery($sql);
					}
					//save login details
					$tableName2 	= "user_login_master";
					$tabFields2 	= "(USER_LOGIN_ID, USER_ID, USER_NAME, PASS_WORD,USER_ROLE, ACCOUNT_REGISTERED_ON,ACTIVE, CREATED_BY,CREATED_ON,CREATED_ON_IP)";
					$insertVals2	= "(NULL, '$student_id', '$uname', MD5('$mobile'),'4',NOW(),'1','$created_by',NOW(), '$created_by_ip')";
					$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
					$exSql2			= parent::execQuery($insertSql2);

					$sql3 = "UPDATE student_enquiry SET REGISTRATION=1, ADMISSION_BY='$created_by_id' WHERE ENQUIRY_ID='$enquiry_id'";
					if ($exSql2)
						parent::execQuery($sql3);

					//upload photo
					//$courseImgPathDir 		= 	'../../'.STUDENT_DOCUMENTS_PATH.'/'.$student_id.'/';

					$bucket_directory = 'student/' . $student_id . '/';

					$tableName3 			= "student_files";
					if ($photo != '') {
						//$student_id = parent::last_id();	

						$ext 			= pathinfo($_FILES['photo' . $key]['name'], PATHINFO_EXTENSION);
						$file_name 		= STUD_PHOTO . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tabFields3 	= "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,FILE_DESC,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
						$insertVals3	= "(NULL, '$student_id', '$file_name','" . STUD_PHOTO . "','','1','$created_by',NOW(), '$created_by_ip')";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$s3_obj = new S3Class();
						$activityContent = $_FILES['photo' . $key]['name'];
						$fileTempName = $_FILES['photo' . $key]['tmp_name'];
						$new_width = 800;
						$new_height = 750;
						$image_p = imagecreatetruecolor($new_width, $new_height);
						$image = imagecreatefromstring(file_get_contents($fileTempName));
						imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

						$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
						imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

						$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["photo" . $key]["type"]));

						//var_dump($response);
						//exit();

						/*$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
								@mkdir($courseImgPathDir,0777,true);*/
						//@mkdir($courseImgThumbPathDir,0777,true);								
						//parent::create_thumb_img($_FILES['photo'.$key]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
						//parent::create_thumb_img($_FILES['photo'.$key]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					if ($photoid != '') {
						$ext 			= pathinfo($_FILES["photoid" . $key]["name"], PATHINFO_EXTENSION);
						$file_name 		= STUD_PHOTO_ID . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$photo_id_category = 'Aadhar Card';
						$tabFields3 	= "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,FILE_CATEGORY,FILE_DESC,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
						$insertVals3	= "(NULL, '$student_id', '$file_name','" . STUD_PHOTO_ID . "','$photo_id_category','$adharno','1','$created_by',NOW(), '$created_by_ip')";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$s3_obj = new S3Class();
						$activityContent = $_FILES['photoid' . $key]['name'];
						$fileTempName = $_FILES['photoid' . $key]['tmp_name'];
						$new_width = 800;
						$new_height = 750;
						$image_p = imagecreatetruecolor($new_width, $new_height);
						$image = imagecreatefromstring(file_get_contents($fileTempName));
						imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

						$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
						imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

						$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["photoid" . $key]["type"]));

						//var_dump($response);
						//exit();


						/*
								$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
								@mkdir($courseImgPathDir,0777,true);*/
						//@mkdir($courseImgThumbPathDir,0777,true);								
						/*parent::create_thumb_img($_FILES["photoid".$key]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;*/
					}
				}
			}
		}
		if (empty($errors)) {


			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! New student has been added successfully!';
		} else {
			parent::rollback();
			$errors['message'] = 'Errors! Please correct all the errors!';
			$data['success'] = false;
			$data['errors']  = $errors;
		}
		echo json_encode($data);
	}

	public function get_institutecourse_id($stud_id)
	{
		$data = '';
		$result = '';
		echo $sql = "SELECT INSTITUTE_COURSE_ID FROM student_course_details WHERE STUDENT_ID='$stud_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['INSTITUTE_COURSE_ID'];
		}
		return $result;
	}
	public function get_institutecourse_id_onlineclasses($stud_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT INSTITUTE_COURSE_ID FROM student_course_details WHERE STUDENT_ID='$stud_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
			//$result = array_push($data['INSTITUTE_COURSE_ID']);
			//$result = $data['INSTITUTE_COURSE_ID'];
		}
		return $data;
	}
	public function get_student_course_id($inst_course_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT COURSE_ID, MULTI_SUB_COURSE_ID,TYPING_COURSE_ID FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
			//$result = $data['COURSE_ID'];
		}
		return $data;
	}
	/*	public function get_student_course_id($inst_course_id)
	{
		$data = '';
		$result = '';
		echo $sql = "SELECT COURSE_ID FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$data = $res->fetch_assoc();
			$result = $data['COURSE_ID'];
		}
		return $result;
	}*/
	public function get_student_coursesname($course_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT COURSE_NAME FROM courses WHERE COURSE_ID='$course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['COURSE_NAME'];
		}
		return $result;
	}
	public function get_student_coursescode($course_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT COURSE_CODE FROM courses WHERE COURSE_ID='$course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['COURSE_CODE'];
		}
		return $result;
	}
	public function get_student_coursesawardid($course_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT COURSE_AWARD FROM courses WHERE COURSE_ID='$course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['COURSE_AWARD'];
		}
		return $result;
	}
	public function get_student_awardname($award_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT AWARD FROM course_awards WHERE AWARD_ID='$award_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['AWARD'];
		}
		return $result;
	}
	public function get_city($city_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT CITY_NAME FROM city_master WHERE CITY_ID='$city_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['CITY_NAME'];
		}
		return $result;
	}
	public function get_state($state_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT STATE_NAME FROM states_master WHERE STATE_ID='$state_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['STATE_NAME'];
		}
		return $result;
	}
	//multi sub data
	public function get_student_coursesname_multi_sub($course_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT MULTI_SUB_COURSE_NAME FROM multi_sub_courses WHERE MULTI_SUB_COURSE_ID='$course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['MULTI_SUB_COURSE_NAME'];
		}
		return $result;
	}
	public function get_student_coursescode_multi_sub($course_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT MULTI_SUB_COURSE_CODE FROM multi_sub_courses WHERE MULTI_SUB_COURSE_ID='$course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['MULTI_SUB_COURSE_CODE'];
		}
		return $result;
	}
	public function get_student_coursesawardid_multi_sub($course_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT MULTI_SUB_COURSE_AWARD FROM multi_sub_courses WHERE MULTI_SUB_COURSE_ID='$course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['MULTI_SUB_COURSE_AWARD'];
		}
		return $result;
	}
	public function get_student_awardname_multi_sub($award_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT AWARD FROM course_awards WHERE AWARD_ID='$award_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['AWARD'];
		}
		return $result;
	}

	// Student List Of Payment Deduction
	public function payment_student_details($payment_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT get_student_name(A.STUDENT_ID) AS STUDENT_FULLNAME, get_instplan_fees(A.INSTITUTE_COURSE_ID) AS EXAM_FEES FROM student_course_details A WHERE A.OFFLINE_PAYMENT_ID='$payment_id' AND A.ADMISSION_CONFIRMED='1' AND A.DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}

	///Student Attendance	
	public function add_attendance()
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data
		//print_r($_POST); 
		//exit();
		$is_present 		= isset($_POST['is_present']) ? $_POST['is_present'] : '';
		$studId 			= isset($_POST['studId']) ? $_POST['studId'] : '';
		$courseId 			= isset($_POST['courseId']) ? $_POST['courseId'] : '';
		$batch_id 		= parent::test(isset($_POST['batch_id']) ? $_POST['batch_id'] : '');
		$attendancedate 	= parent::test(isset($_POST['attendancedate']) ? $_POST['attendancedate'] : '');

		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		///if($is_present=='' || empty($is_present)){ $errors['is_present'] = 'Please select atleast one student!';}

		$created_by  		= $_SESSION['user_fullname'];
		$updated_by  		= $_SESSION['user_fullname'];
		$inst_id 		= $_SESSION['user_id'];
		/* check validations */
		if ($attendancedate == '') $errors['attendancedate'] = 'Attendance Date is required!';
		if ($batch_id == '') $errors['batch_id'] = 'Please select batch';

		if (!empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			if (is_array($studId) && count($studId) > 0) {
				$failedArr = array();
				foreach ($studId as $student_id) {

					foreach ($courseId as $course_id) {

						$group_id		= isset($_POST["group$student_id$course_id"]) ? $_POST["group$student_id$course_id"] : '';

						if ($group_id != '') {
							$tableName 	= "attendance";

							$sql = "SELECT * FROM $tableName WHERE batch_id = '$batch_id' AND date = '$attendancedate' AND student_id ='$student_id' AND course_id = '$course_id' AND delete_flag ='0'";
							$res = parent::execQuery($sql);
							if ($res && $res->num_rows > 0) {
								$setValues 	= " is_present='$group_id', updated_by='$updated_by',updated_at=NOW()";
								$whereClause = " WHERE batch_id ='$batch_id' AND date = '$attendancedate' AND student_id = '$student_id' AND course_id = '$course_id'";
								$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
								$exSql		= parent::execQuery($updateSql);
								if (!$exSql && parent::rows_affected() <= 0)
									array_push($failedArr, $isPresent);
							} else {
								$tabFields 	= "(id, inst_id,batch_id,course_id,student_id,date,is_present,active,delete_flag,created_by,created_at)";
								$insertVals	= "(NULL,'$inst_id','$batch_id','$course_id','$student_id','$attendancedate','$group_id','1','0','$created_by',NOW())";
								$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
								$exSql		= parent::execQuery($insertSql);
								if (!$exSql && parent::rows_affected() <= 0)
									array_push($failedArr, $isPresent);
							}
						}
					}
				}
			}

			if (empty($failedArr)) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Attendance has been added successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the attendance.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function update_attendance($id = '')
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data 

		$id 		= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$title 		= parent::test(isset($_POST['title']) ? $_POST['title'] : '');
		$link 		= parent::test(isset($_POST['link']) ? $_POST['link'] : '');
		$description 	= parent::test(isset($_POST['description']) ? $_POST['description'] : '');

		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		$admin_id 		= $_SESSION['user_id'];
		$updated_by  		= $_SESSION['user_fullname'];


		/* check validations */
		/* check validations */
		if ($title == '') $errors['title'] = 'Title is required!';
		if ($link == '') $errors['link'] = 'Link is required!';


		if (!empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "online_classes";
			$setValues 	= " title='$title',link='$link',description='$description', updated_by='$updated_by',updated_at=NOW()";
			$whereClause = " WHERE id ='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! Link has been updated successfully!';
		}
		return json_encode($data);
	}

	//list attendance
	public function list_attendance($student_id = '', $batch_id = '', $cond = '')
	{
		$data = '';
		//$sql= "SELECT A.*,B.date as attendancedate,B.is_present as isPresent, get_student_name(A.STUDENT_ID) AS STUDENT_FULLNAME, get_stud_photo(A.STUDENT_ID) AS  STUD_PHOTO  FROM student_details A LEFT JOIN student_attendance B ON A.STUDENT_ID = B.student_id WHERE A.DELETE_FLAG=0 AND A.BATCH_ID != '' ";

		$sql = "SELECT A.*, get_student_name(A.STUDENT_ID) AS STUDENT_FULLNAME, get_stud_photo(A.STUDENT_ID) AS  STUD_PHOTO,B.BATCH_ID,B.INSTITUTE_COURSE_ID,B.ADMISSION_DATE as JOIN_DATE  FROM student_details A LEFT JOIN student_course_details B ON A.STUDENT_ID = B.STUDENT_ID WHERE A.ACTIVE=1 AND A.DELETE_FLAG=0 ";

		if ($student_id != '') {
			$sql .= " AND A.STUDENT_ID='$student_id' ";
		}
		if ($batch_id != '') {
			$sql .= " AND B.BATCH_ID='$batch_id' ";
		}
		if ($cond != '')
			$sql .= " $cond";
		$sql .= ' ORDER BY A.CREATED_ON DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function delete_attendance($id)
	{
		$sql = "UPDATE online_classes SET delete_flag=1, updated_by='" . $_SESSION['user_fullname'] . "', updated_at=NOW() WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function get_enquiry_count($cond = '')
	{
		$result = 0;
		$sql = "SELECT COUNT(*) AS ENQUIRY_COUNT FROM student_enquiry WHERE DELETE_FLAG=0 AND REGISTRATION = 0";

		if ($cond != '') {
			$sql .= $cond;
		}
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['ENQUIRY_COUNT'];
		}
		return $result;
	}
	public function get_admission_count($cond = '')
	{
		$result = 0;
		$sql = "SELECT COUNT(*) AS ADMISSION_COUNT FROM  student_course_details WHERE ACTIVE=1";
		if ($cond != '') {
			$sql .= $cond;
		}
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['ADMISSION_COUNT'];
		}
		return $result;
	}

	//purchase course by wallet
	public function purchase_course()
	{
		//print_r($_POST); exit();
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$student_id 				= parent::test(isset($_POST['student_id']) ? $_POST['student_id'] : '');
		$inst_id 					= parent::test(isset($_POST['inst_id']) ? $_POST['inst_id'] : '');
		$inst_course_id 			= parent::test(isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '');
		$paying_amount 			= parent::test(isset($_POST['paying_amount']) ? $_POST['paying_amount'] : '');
		$minimum_courseamount 	= parent::test(isset($_POST['minimum_courseamount']) ? $_POST['minimum_courseamount'] : '');
		$wallet_amount 			= parent::test(isset($_POST['wallet_amount']) ? $_POST['wallet_amount'] : '');
		$coursefees 				= parent::test(isset($_POST['course_fees']) ? $_POST['course_fees'] : '');

		$examtype1 		= '1';
		$examstatus1 		= '2';
		$studcode = $this->get_stud_code($student_id);

		$requiredArr = array('paying_amount' => $paying_amount);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}

		if ($wallet_amount < $paying_amount) {
			$errors['paying_amount'] = 'Sorry! Please recharge your wallet to purchase this course.';
		}

		if ($paying_amount < $minimum_courseamount) {
			$errors['paying_amount'] = 'Sorry! Minimum amount to purchase this course is ' . $minimum_courseamount;
		}

		if ($paying_amount > $coursefees) {
			$errors['paying_amount'] = 'Course fees for this course is ' . $coursefees . '. Please enter correct amount.';
		}

		$role 			= $_SESSION['user_role'];

		$created_by_id 	= $_SESSION['user_id'];
		$created_by  	= $_SESSION['user_fullname'];

		//print_r($errors); exit();
		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();

			//QRCODE	
			include('resources/phpqrcode/qrlib.php');
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
			$tabFields1 	= "(STUD_COURSE_DETAIL_ID, STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID, COURSE_FEES,TOTAL_COURSE_FEES,FEES_RECIEVED, FEES_BALANCE, REMARKS, PAYMENT_RECIEVED_FLAG,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON,QRFILE)";

			$insertVals1	= "(NULL, '$student_id','1','$inst_course_id','$coursefees','$coursefees', '$paying_amount','$amtbalance','',1,'1','0','$created_by',NOW(),'$file')";
			$insertSql1     = parent::insertData($tableName1, $tabFields1, $insertVals1);
			$exSql1			= parent::execQuery($insertSql1);

			if ($exSql1) {
				$stud_course_detail_id = parent::last_id();
				$receipt_no = date('d-m-Y') . '/' . $this->generate_student_receipt_no() . $student_id;
				//student payment details
				$tableName2 	= "student_payments";
				$tabFields2 	= "(PAYMENT_ID, RECIEPT_NO,STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID,STUD_COURSE_DETAIL_ID, COURSE_FEES, TOTAL_COURSE_FEES, FEES_PAID, FEES_BALANCE, FEES_PAID_DATE, PAYMENT_NOTE,ACTIVE,DELETE_FLAG, CREATED_BY, CREATED_ON)";
				$insertVals2	= "(NULL,'$receipt_no', '$student_id','1', '$inst_course_id', '$stud_course_detail_id','$coursefees','$coursefees','$paying_amount','$amtbalance',NOW(),'','1','0','$created_by', NOW())";
				echo $insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
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
				$whereClauseInst 	= "WHERE USER_ID='1' AND USER_ROLE = 2";
				$updSqlInst 		= parent::updateData($tableName91, $setValuesInst, $whereClauseInst);
				$exSqlInst 		= parent::execQuery($updSqlInst);

				if ($exSqlInst) {
					$trans_typeInst == 'CREDIT';
					$tableNameInst 	= "offline_payments";
					$tabFieldsInst	= "(PAYMENT_ID, TRANSACTION_TYPE,USER_ID,USER_ROLE,PAYMENT_AMOUNT,PAYMENT_REMARK,ACTIVE,CREATED_BY, CREATED_ON)";
					$insertValsInst	= "(NULL,'$trans_typeInst','1','2','$paying_amount','Student Fees','1','$created_by',NOW())";
					$insertSqlInst	= parent::insertData($tableNameInst, $tabFieldsInst, $insertValsInst);
					$exSqlInstPayment		= parent::execQuery($insertSqlInst);
				}


				if ($exSqlWallet) {

					$instcourse = parent::get_inst_course_info($inst_course_id);
					$COURSE_ID = isset($instcourse['COURSE_ID']) ? $instcourse['COURSE_ID'] : '';
					$MULTI_SUB_COURSE_ID = isset($instcourse['MULTI_SUB_COURSE_ID']) ? $instcourse['MULTI_SUB_COURSE_ID'] : '';
					$TYPING_COURSE_ID = isset($instcourse['TYPING_COURSE_ID']) ? $instcourse['TYPING_COURSE_ID'] : '';

					$aicpe_course_id = $COURSE_ID;
					$aicpe_course_id_multi = $MULTI_SUB_COURSE_ID;
					$course_typing = $TYPING_COURSE_ID;
					$valid_exam = parent::validate_apply_exam($aicpe_course_id, $aicpe_course_id_multi, $course_typing);

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

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Course has been added successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the course.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//walllet recharge	
	public function make_payment()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data
	}

	//attendance Report
	public function list_attendance_report($student_id = '', $batch_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT A.*,B.date as attendancedate,B.is_present as isPresent, get_student_name(A.STUDENT_ID) AS STUDENT_FULLNAME, get_stud_photo(A.STUDENT_ID) AS  STUD_PHOTO  FROM student_details A LEFT JOIN attendance B ON A.STUDENT_ID = B.student_id WHERE A.DELETE_FLAG=0 AND A.BATCH_ID != '' ";

		//$sql= "SELECT A.*, get_student_name(A.STUDENT_ID) AS STUDENT_FULLNAME, get_stud_photo(A.STUDENT_ID) AS  STUD_PHOTO  FROM student_details A WHERE A.DELETE_FLAG=0 AND A.BATCH_ID != '' ";

		if ($student_id != '') {
			$sql .= " AND A.STUDENT_ID='$student_id' ";
		}
		if ($batch_id != '') {
			$sql .= " AND A.BATCH_ID='$batch_id' ";
		}
		if ($cond != '')
			$sql .= " $cond";
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//student attendance report
	public function list_attendance_student($student_id = '', $batch_id = '', $date = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM attendance A WHERE A.delete_flag=0 ";

		//$sql= "SELECT A.*, get_student_name(A.STUDENT_ID) AS STUDENT_FULLNAME, get_stud_photo(A.STUDENT_ID) AS  STUD_PHOTO  FROM student_details A WHERE A.DELETE_FLAG=0 AND A.BATCH_ID != '' ";

		if ($student_id != '') {
			$sql .= " AND A.student_id='$student_id' ";
		}
		if ($batch_id != '') {
			$sql .= " AND A.batch_id='$batch_id' ";
		}
		if ($date != '') {
			$sql .= " AND A.date='$date' ";
		}
		if ($cond != '')
			$sql .= " $cond";
		$sql .= 'ORDER BY A.created_at DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//get total payment student
	public function total_payments($stud_id = '', $cond = '')
	{
		$res = array();;
		$sql = "SELECT SUM(TOTAL_COURSE_FEES) AS ALL_COURSE_FEES, SUM(FEES_PAID) AS TOTAL_FEES_PAID, SUM(FEES_BALANCE) AS TOTAL_FEES_BALANCE FROM student_payments WHERE DELETE_FLAG=0";
		if ($stud_id != '') {
			$sql .= " AND STUDENT_ID='$stud_id'";
		}
		if ($cond != '') {
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


	//list batch details
	public function list_batch_report($batch_id = '', $user_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM course_batches A WHERE A.delete_flag=0 AND A.id != '' AND A.inst_id='$user_id' ";
		if ($batch_id != '') {
			$sql .= " AND A.id='$batch_id' ";
		}
		if ($cond != '')
			$sql .= " $cond";
		$sql .= 'ORDER BY A.created_at DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//batch report count
	public function batchStudentCounts($id = '', $cond = '')
	{
		$res = array();;
		$sql = "SELECT COUNT(BATCH_ID) AS TOTAL FROM  student_course_details WHERE ACTIVE=1";
		if ($id != '') {
			$sql .= " AND BATCH_ID='$id'";
		}
		if ($cond != '') {
			$sql .= $cond;
		}
		//echo $sql;
		$exc = parent::execQuery($sql);
		if ($exc && $exc->num_rows > 0) {
			while ($data = $exc->fetch_assoc()) {
				$res['TOTAL'] = $data['TOTAL'];
			}
		}
		return $res;
	}

	public function listStudentCourses($id = '', $cond = '')
	{
		$res = array();
		$count = 0;
		$sql = "SELECT A.* FROM  student_course_details A WHERE A.ACTIVE=1";
		if ($id != '') {
			$sql .= " AND A.BATCH_ID='$id'";
		}
		if ($cond != '') {
			$sql .= $cond;
		}
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		// print_r($exc);
		//$count = $exc->num_rows;
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function certificate_status_check($stud_id = '', $courseid = '', $multicourse = '')
	{
		$res = array();
		$data = array();
		$count = 0;
		$sql = "SELECT A.* FROM  certificates_details A WHERE A.ACTIVE=1";
		if ($stud_id != '') {
			$sql .= " AND A.STUDENT_ID ='$stud_id'";
		}
		if ($courseid != '') {
			$sql .= " AND A.COURSE_ID ='$courseid'";
		}
		if ($multicourse != '') {
			$sql .= " AND A.MULTI_SUB_COURSE_ID ='$multicourse'";
		}
		// echo $sql; exit();
		$res = parent::execQuery($sql);
		// print_r($exc);
		//$count = $exc->num_rows;
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	public function batchRemainingCounts($batchId, $instituteId)
	{
		// Fetch total number of students for the batch
		$totalStudents = 0;
		$queryTotal = "SELECT numberofstudent FROM course_batches WHERE id = '$batchId'";
		$resultTotal = parent::execQuery($queryTotal);
		if ($resultTotal && $resultTotal->num_rows > 0) {
			$data = $resultTotal->fetch_assoc();
			$totalStudents = (int)$data['numberofstudent'];
		}

		// If no students are allocated for the batch, return 0
		if ($totalStudents === 0) {
			return 0;
		}

		// Fetch all active students for the batch and institute
		$queryStudents = "
        SELECT scd.STUDENT_ID, scd.INSTITUTE_COURSE_ID
        FROM student_course_details scd
        WHERE scd.ACTIVE = 1 
          AND scd.BATCH_ID = '$batchId' 
          AND scd.INSTITUTE_ID = '$instituteId'";
		$resultStudents = parent::execQuery($queryStudents);

		// If no students found, remaining is equal to total students
		if (!$resultStudents || $resultStudents->num_rows === 0) {
			return $totalStudents;
		}

		// Check certificates for each student
		$unverifiedCount = 0;
		while ($student = $resultStudents->fetch_assoc()) {
			$studentId = $student['STUDENT_ID'];
			$courseInfo = parent::get_inst_course_info($student['INSTITUTE_COURSE_ID']);

			if (!empty($courseInfo)) {
				$courseId = $courseInfo['COURSE_ID'] ?? '';
				$multiSubCourseId = $courseInfo['MULTI_SUB_COURSE_ID'] ?? '';

				// Check certificate status
				// $hasCertificate = $this->certificate_status_check($studentId, $courseId, $multiSubCourseId);
				// if (empty($hasCertificate)) {
				// 	$unverifiedCount++;
				// }
				$unverifiedCount++;

			}
		}

		// Calculate remaining count
		$remaining = $totalStudents - $unverifiedCount;
		return $remaining;
	}

	//installment list
	public function get_student_installments($student_id = '', $inst_course_id = '', $display = true)
	{
		$img = '';
		$data = array();
		$target = '';

		$sql = "SELECT A.* FROM student_payments_installments A WHERE 1";
		if ($student_id != '')
			$sql .= " AND A.STUDENT_ID='$student_id'";

		if ($inst_course_id != '')
			$sql .= " AND A.INSTITUTE_COURSE_ID='$inst_course_id'";

		$sql .= ' ORDER BY A.INSTALLMENT_ID ASC';
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$img .= '<table class="table table-responsive table-bordered"><thead>
				<tr>										
					<th>Installment Name</th>
					<th>Amount</th>										
					<th>Date</th>
				</tr>
			</thead>';
			while ($rec = $res->fetch_assoc()) {
				$INSTALLMENT_ID 	= $rec['INSTALLMENT_ID'];
				$STUDENT_ID 		= $rec['STUDENT_ID'];
				$INSTITUTE_ID 		= $rec['INSTITUTE_ID'];
				$INSTITUTE_COURSE_ID 		= $rec['INSTITUTE_COURSE_ID'];
				$DATE 				= $rec['DATE'];
				$AMOUNT 			= $rec['AMOUNT'];
				$INSTALLMENT_NAME 	= $rec['INSTALLMENT_NAME'];

				if ($INSTALLMENT_ID != '') {

					$img .= '<tr id="file-area' . $INSTALLMENT_ID . '">
									<td>' . $INSTALLMENT_NAME . '</td>
									<td>' . $AMOUNT . '</td>
									<td>' . $DATE . '</td>									
								  </tr>';
				}
				array_push($data, $rec);
			}
		}

		$img .= '</table>';
		if (!$display)
			return $data;
		else return $img;
	}
	public function add_student_re_admission()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data
		extract($_POST);
		//print_r($_POST); exit();	 	 				 

		$student_id	 	= parent::test(isset($student_id) ? $student_id : '');
		$interested_course	= parent::test(isset($interested_course) ? $interested_course : '');
		$status 				= parent::test(isset($_POST['status']) ? $_POST['status'] : 1);

		$filecount4 		= parent::test(isset($_POST['filecount4']) ? $_POST['filecount4'] : '');

		if ($interested_course == '') {
			$errors['interested_course'] = "Required! Select Course.";
		}
		$inst_course_id = $interested_course;

		$requiredArr = array('student_id' => $student_id);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}
		if ($batch == '') {
			$errors['batch'] = 'Please Select Batch.';
		}
		if ($remainingStudent == '' || $remainingStudent == 0) {
			$errors['remainingStudent'] = 'Please Re-Select Your Batch And Please Check Your Remaining Admission Not Zero. ';
		}

		//payment details		   		 
		$coursefees 		= isset($_POST['coursefees']) ? $_POST['coursefees'] : 0;
		$discrate 		= isset($_POST['discrate']) ? $_POST['discrate'] : '';
		$discamt 			= isset($_POST['discamt']) ? $_POST['discamt'] : 0;
		$totalcoursefee	= isset($_POST['totalcoursefee']) ? $_POST['totalcoursefee'] : 0;
		$amtrecieved 		= isset($_POST['amtrecieved']) ? $_POST['amtrecieved'] : 0;
		$amtbalance 		= isset($_POST['amtbalance']) ? $_POST['amtbalance'] : 0;
		$payremarks 		= isset($_POST['payremarks']) ? $_POST['payremarks'] : '';
		$admission_date 		= isset($_POST['admission_date']) ? $_POST['admission_date'] : '';

		$minAmount = parent::get_instituteMinFees($inst_course_id);

		if ($amtrecieved < $minAmount) {
			$errors['amtrecieved'] = 'Sorry! Minimum amount to purchase this course is' . $minAmount;
		}

		if ($discamt == 0 || $discamt == '') {
			$totalcoursefee = $coursefees;
			$discamt = 0;
		}

		$examtype1 		= isset($_POST['examtype1']) ? $_POST['examtype1'] : '';
		$examstatus1 		= isset($_POST['examstatus1']) ? $_POST['examstatus1'] : '';
		if ($examtype1 == '') $errors['examtype1'] = 'Please select exam mode!';

		$institute_id		= parent::test(isset($institute_id) ? $institute_id : '');

		$role 			= $_SESSION['user_role'];

		$created_by_id = $_SESSION['user_id'];
		$created_by  		= $_SESSION['user_fullname'];
		if ($admission_date != '') {
			$admission_date = @date('Y-m-d', strtotime($admission_date));
		}

		$refferal_code = parent::get_stud_refferalcode($student_id);

		if ($refferal_code != '') {
			if (parent::valid_refferal_code($refferal_code, $institute_id))
				$errors['refferal_code'] = 'Sorry! Invalid refferal code. Please insert valid code.';
		}

		if (!parent::valid_rollnumber($roll_number, $institute_id))
			$errors['roll_number'] = 'Sorry! Roll Number is already used.';


		if ($role == '8') {
			$examfees 		= isset($_POST['examfees']) ? $_POST['examfees'] : '';

			if ($examfees == '') {
				$errors['examfees'] = 'Please Select Course.';
			}

			$walletBal = 0;
			$totalToPay = 0;
			$wallet_id = '';
			$res = parent::get_wallet('', $institute_id, $role);
			if ($res != '') {
				$data1 = $res->fetch_assoc();
				$walletBal = $data1['TOTAL_BALANCE'];
				$wallet_id = $data1['WALLET_ID'];
			} else {
				$errors['examfees'] = "Sorry! Your wallet is empty!  Please rechrage your wallet and Tray again! <a href='pay-online' class='btn btn-sm bg-teal'>Click to Recharge Now!</a>";
			}

			$totalToPay = $examfees;
			if ($totalToPay > $walletBal)
				$errors['examfees'] = "Sorry! Your total bill is <strong>Rs. $totalToPay</strong>.  You have only <strong>Rs. $walletBal</strong> availabel in your wallet! You need more <strong> Rs. " . ($totalToPay - $walletBal) . "</strong> to order the certificates.<br> Please rechrage your wallet. <a href='pay-online'>Recharge Now!</a>";
		}

		// if($filecount4>=1)
		// {
		// 	for($i=0; $i<$filecount4; $i++)
		// 	{
		// 		$installment_name 	= parent::test(isset($_POST['installment_name'.$i])?$_POST['installment_name'.$i]:'');
		// 		$installment_amount = parent::test(isset($_POST['installment_amount'.$i])?$_POST['installment_amount'.$i]:'');
		// 		$installment_date 	= parent::test(isset($_POST['installment_date'.$i])?$_POST['installment_date'.$i]:'');

		// 		if ($installment_name=='')
		// 		$errors['installment_name'.$i] = 'Please Select Installment!';    

		// 		if ($installment_amount=='')
		// 		$errors['installment_amount'.$i] = 'Amount is required!';

		// 		if ($installment_date=='')
		// 		$errors['installment_date'.$i] = 'Date is required!';

		// 	}			 

		// }
		$studcode 		= parent::get_stud_code($student_id);

		//print_r($errors); exit();
		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();

			$tableName1 	= "student_course_details";
			$tabFields1 	= "(STUD_COURSE_DETAIL_ID, STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID, COURSE_FEES,DISCOUNT_RATE,DISCOUNT_AMOUNT,TOTAL_COURSE_FEES,FEES_RECIEVED, FEES_BALANCE, REMARKS, PAYMENT_RECIEVED_FLAG,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON,BATCH_ID,ADMISSION_DATE)";

			$insertVals1	= "(NULL, '$student_id','$institute_id','$inst_course_id','$coursefees','$discrate','$discamt','$totalcoursefee', '$amtrecieved','$amtbalance','$payremarks','1','1','0','$created_by',NOW(),'$batch','$admission_date')";
			$insertSql1 = parent::insertData($tableName1, $tabFields1, $insertVals1);
			$exSql1		= parent::execQuery($insertSql1);
			if ($exSql1) {

				$stud_course_detail_id = parent::last_id();

				include('resources/phpqrcode/qrlib.php');
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

				$sqlQR = "UPDATE student_course_details SET QRFILE = '$file' WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
				$exSqlQR = parent::execQuery($sqlQR);

				if ($refferal_code !== '') {
					$refferarStudent	=  parent::get_refferar_details($refferal_code, $institute_id);
					$refferalAmount 	=  parent::get_refferal_amount($institute_id);
				}


				if ($exSqlQR) {
					$receipt_no = date('d-m-Y') . '/' . $this->generate_student_receipt_no() . $student_id;
					//student payment details
					$tableName2 	= "student_payments";
					$tabFields2 	= "(PAYMENT_ID, RECIEPT_NO,STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID,STUD_COURSE_DETAIL_ID, COURSE_FEES, TOTAL_COURSE_FEES, FEES_PAID, FEES_BALANCE, FEES_PAID_DATE, PAYMENT_NOTE,ACTIVE,DELETE_FLAG, CREATED_BY, CREATED_ON)";
					$insertVals2	= "(NULL,'$receipt_no', '$student_id','$institute_id', '$inst_course_id', '$stud_course_detail_id','$coursefees','$totalcoursefee','$amtrecieved','$amtbalance',NOW(),'$payremarks','1','0','$created_by', NOW())";
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
					$sql = "UPDATE student_course_details SET PAYMENT_ID='$payment_id' WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
					parent::execQuery($sql);

					$tableName91 	= " wallet";
					//refferal payment record 
					if (!empty($refferarStudent)) {
						$trans_type == 'CREDIT';
						$tableNameR1 	= "offline_payments";
						$tabFieldsR1	= "(PAYMENT_ID, TRANSACTION_NO,TRANSACTION_TYPE,USER_ID,USER_ROLE,PAYMENT_AMOUNT,PAYMENT_REMARK,ACTIVE,CREATED_BY, CREATED_ON,STUDENT_ID)";
						$insertVals1	= "(NULL,'$trans_type','$refferarStudent','4','$refferalAmount','Refferal Amount From','1','$created_by',NOW(),'$student_id')";
						$insertSqlR1	= parent::insertData($tableNameR1, $tabFieldsR1, $insertValsR1);
						$exSqlR1		= parent::execQuery($insertSqlR1);

						//reference amount
						$setValuesR 	= "TOTAL_BALANCE = TOTAL_BALANCE + $refferalAmount, UPDATED_BY='$created_by', UPDATED_ON=NOW()";
						$whereClauseR 	= "WHERE USER_ID='$refferarStudent' AND USER_ROLE = 4";
						$updSqlR 		= parent::updateData($tableName91, $setValuesR, $whereClauseR);
						$exSqlR 		= parent::execQuery($updSqlR);
					}

					if ($role != '8') {
						//institute wallet
						$setValuesInst 	= "TOTAL_BALANCE = TOTAL_BALANCE + $amtrecieved, UPDATED_BY='$created_by', UPDATED_ON=NOW()";
						$whereClauseInst 	= "WHERE USER_ID='$institute_id' AND USER_ROLE = '$role'";
						$updSqlInst 		= parent::updateData($tableName91, $setValuesInst, $whereClauseInst);
						$exSqlInst 		= parent::execQuery($updSqlInst);
					}

					/*if($exSqlInst){
									$trans_typeInst=='CREDIT';
									$tableNameInst 	= "offline_payments";
									$tabFieldsInst	= "(PAYMENT_ID, TRANSACTION_TYPE,USER_ID,USER_ROLE,PAYMENT_AMOUNT,PAYMENT_REMARK,ACTIVE,CREATED_BY, CREATED_ON,STUDENT_ID)";
									$insertValsInst	= "(NULL,'$trans_typeInst','$institute_id','$role','$amtrecieved','Student Fees','1','$created_by',NOW(),'$student_id')";
									$insertSqlInst	= parent::insertData($tableNameInst,$tabFieldsInst,$insertValsInst);
									$exSqlInstPayment		= parent::execQuery($insertSqlInst);
								}*/

					/*	Deduct money from wallet */
					if ($wallet_id != '') {
						$user_info 	= $this->get_user_info($institute_id, $role);
						$NAME 		= $user_info['NAME'];
						$MOBILE 	= $user_info['MOBILE'];
						$EMAIL 		= $user_info['EMAIL'];

						$tableName4 	= "offline_payments";
						$tabFields4 	= "(PAYMENT_ID, TRANSACTION_TYPE,USER_ID,USER_ROLE,USER_FULLNAME,USER_EMAIL,USER_MOBILE,PAYMENT_AMOUNT,PAYMENT_MODE,PAYMENT_DATE,PAYMENT_STATUS,PAYMENT_REMARK,WALLET_ID,ACTIVE,CREATED_BY, CREATED_ON,CREATED_BY_IP,STUDENT_ID)";
						$insertVals4	= "(NULL, 'DEBIT','$institute_id','$role', '$NAME','$EMAIL','$MOBILE','$totalToPay','OFFLINE',NOW(), 'success', 'Admission Confirmed','$wallet_id', '1','$created_by',NOW(),'$created_by_ip','$student_id')";
						$insertSql4	= parent::insertData($tableName4, $tabFields4, $insertVals4);
						$exSql5		= parent::execQuery($insertSql4);

						$payment_id1 = parent::last_id();

						$sqlwallet = "UPDATE wallet SET TOTAL_BALANCE= TOTAL_BALANCE - $totalToPay, UPDATED_BY='$created_by', UPDATED_ON=NOW(),UPDATED_ON_IP='$created_by_ip' WHERE WALLET_ID='$wallet_id'";
						$reswallet = parent::execQuery($sqlwallet);

						//insert payment table
						$tableName5 = 'institute_payments';
						$tabFields5 = "(RECIEPT_NO, INSTITUTE_ID, TOTAL_EXAM_FEES,TOTAL_EXAM_FEES_RECIEVED,TOTAL_EXAM_FEES_BALANCE,PAYMENT_DATE,PAYMENT_CATEGORY, CREATED_BY, CREATED_ON, CREATED_ON_IP)";
						$insertVals5 = "(generate_admin_reciept_num(),'$institute_id','$totalToPay','$totalToPay',0,NOW(),'Admission Confirmed','$created_by',NOW(),'$created_by_ip')";
						$insertSql5 = parent::insertData($tableName5, $tabFields5, $insertVals5);
						$exSql6		= parent::execQuery($insertSql5);
					}

					$sql1 = "UPDATE student_course_details SET ADMISSION_CONFIRMED='1' WHERE STUD_COURSE_DETAIL_ID=$stud_course_detail_id";
					$exSql4	= parent::execQuery($sql1);

					if ($exSql4) {

						$tableName41 		= "student_payments_installments";
						if ($filecount4 >= 1) {
							for ($j = 0; $j < $filecount4; $j++) {
								$installment_name 	= parent::test(isset($_POST['installment_name' . $j]) ? $_POST['installment_name' . $j] : '');
								$installment_amount = parent::test(isset($_POST['installment_amount' . $j]) ? $_POST['installment_amount' . $j] : '');
								$installment_date 	= parent::test(isset($_POST['installment_date' . $j]) ? $_POST['installment_date' . $j] : '');

								if ($installment_name != '' && $installment_amount != '' && $installment_date != '') {

									$tabFields41 	= "(INSTALLMENT_ID,STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID,DATE,AMOUNT,INSTALLMENT_NAME,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_AT)";
									$insertVals41	= "(NULL, '$student_id', '$institute_id','$inst_course_id','$installment_date','$installment_amount','$installment_name','1','0','$created_by',NOW())";
									$insertSql41		= parent::insertData($tableName41, $tabFields41, $insertVals41);
									$exec41   	 = parent::execQuery($insertSql41);
								}
							}
						}
					}
				}

				if ($exSqlQR) {

					$instcourse 			= parent::get_inst_course_info($inst_course_id);
					$COURSE_ID = isset($instcourse['COURSE_ID']) ? $instcourse['COURSE_ID'] : '';
					$MULTI_SUB_COURSE_ID = isset($instcourse['MULTI_SUB_COURSE_ID']) ? $instcourse['MULTI_SUB_COURSE_ID'] : '';
					$TYPING_COURSE_ID = isset($instcourse['TYPING_COURSE_ID']) ? $instcourse['TYPING_COURSE_ID'] : '';

					$aicpe_course_id = $COURSE_ID;
					$aicpe_course_id_multi = $MULTI_SUB_COURSE_ID;
					$course_id_typing = $TYPING_COURSE_ID;

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
				//send sms
				$message = "Hello $fname, Your admission is confirmed.\r\n Your login crediantial is \r\n Username : $uname \r\n Password : $confpword \r\n Please login on portal.";
				//parent::trigger_sms($message,$mobile);	

			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the student course.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//student course details fees for form
	public function get_fees_details_form($student_id = '', $course_id = '')
	{
		$res = '';
		$sql = "SELECT A.* FROM student_course_details A WHERE DELETE_FLAG=0";
		if ($course_id != '')
			$sql .= " AND A.INSTITUTE_COURSE_ID=$course_id";
		if ($student_id != '')
			$sql .= " AND A.STUDENT_ID=$student_id";
		$sql .=	" ORDER BY A.STUD_COURSE_DETAIL_ID DESC";
		//echo $sql; exit();
		$exec = parent::execQuery($sql);
		if ($exec && $exec->num_rows > 0)
			$res = $exec;
		return $res;
	}

	public function get_allcoursefeestotal($user_id = '', $cond = '')
	{
		$result = 0;
		$sql = "SELECT SUM(TOTAL_COURSE_FEES) as ALL_COURSE_FEES FROM student_course_details WHERE DELETE_FLAG=0 AND INSTITUTE_ID = $user_id";
		if ($cond != '') {
			$sql .= $cond;
		}
		//echo $sql;
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['ALL_COURSE_FEES'];
		}
		return $result;
	}
	public function get_allpaidfeestotal($user_id = '', $cond = '')
	{
		$result = 0;
		$sql = "SELECT SUM(FEES_PAID) as FEES_PAID FROM  student_payments WHERE DELETE_FLAG=0 AND INSTITUTE_ID = $user_id";
		if ($cond != '') {
			$sql .= $cond;
		}
		//echo $sql;
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['FEES_PAID'];
		}
		return $result;
	}

	//update student education details
	public function update_student_education()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$student_id 		= parent::test(isset($_POST['student_id']) ? $_POST['student_id'] : '');
		$enquiry_id 		= parent::test(isset($_POST['enquiry_id']) ? $_POST['enquiry_id'] : '');
		$institute_id		= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : '');
		$staff_id			= parent::test(isset($_POST['staff_id']) ? $_POST['staff_id'] : '');

		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : 1);
		// educational details
		$max_edu 			= parent::test(isset($_POST['max_edu']) ? $_POST['max_edu'] : 0);
		$max_exp 			= parent::test(isset($_POST['max_exp']) ? $_POST['max_exp'] : 0);

		$role 				= 4; //student login role
		$updated_by  		= $_SESSION['user_fullname'];
		$created_by_ip  	= $_SESSION['ip_address'];

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			/* -----------student educational details-------------------- */
			$tableName			= "student_educational_details";
			$sql = "DELETE FROM $tableName WHERE STUDENT_ID='$student_id'";
			parent::execQuery($sql);
			for ($i = 1; $i <= $max_edu; $i++) {
				$edu_coursename		= parent::test(isset($_POST['edu_coursename' . $i]) ? $_POST['edu_coursename' . $i] : '');
				$edu_instname 		= parent::test(isset($_POST['edu_instname' . $i]) ? $_POST['edu_instname' . $i] : '');
				$edu_universityname = parent::test(isset($_POST['edu_universityname' . $i]) ? $_POST['edu_universityname' . $i] : '');
				$edu_startdate 		= parent::test(isset($_POST['edu_startdate' . $i]) ? $_POST['edu_startdate' . $i] : '0000-00-00');
				$edu_enddate 		= parent::test(isset($_POST['edu_enddate' . $i]) ? $_POST['edu_enddate' . $i] : '0000-00-00');
				$edu_otherinfo 		= parent::test(isset($_POST['edu_otherinfo' . $i]) ? $_POST['edu_otherinfo' . $i] : '');
				$edu_marks 		= parent::test(isset($_POST['edu_marks' . $i]) ? $_POST['edu_marks' . $i] : '');
				if ($edu_startdate != '')
					$edu_startdate = date('Y-m-d', strtotime($edu_startdate));
				if ($edu_enddate != '')
					$edu_enddate = date('Y-m-d', strtotime($edu_enddate));
				$tabFields	= "(STUDENT_EDUCATIONAL_ID, STUDENT_ID, COURSE_NAME, INSTITUTE_NAME, UNIVERSITY_NAME, START_DATE, END_DATE, MARKS, DESCRIPTION, CREATED_BY, CREATED_ON, UPDATED_BY, UPDATED_ON, CREATED_ON_IP, UPDATED_ON_IP)";
				$insertVal	= "(NULL, '$student_id','$edu_coursename', '$edu_instname', '$edu_universityname', '$edu_startdate', '$edu_enddate', '$edu_marks','$edu_otherinfo','$updated_by',NOW(),'$updated_by',NOW(),'$created_by_ip','$created_by_ip')";
				$insertSql	= parent::insertData($tableName, $tabFields, $insertVal);
				parent::execQuery($insertSql);
			}
			/* -----------student work experience details-------------------- */
			$tableName			= "student_experience_details";
			$sql = "DELETE FROM $tableName WHERE STUDENT_ID='$student_id'";
			parent::execQuery($sql);
			for ($i = 1; $i <= $max_exp; $i++) {
				$exp_jobtitle		= parent::test(isset($_POST['exp_jobtitle' . $i]) ? $_POST['exp_jobtitle' . $i] : '');
				$exp_companyname 		= parent::test(isset($_POST['exp_companyname' . $i]) ? $_POST['exp_companyname' . $i] : '');

				$exp_startdate 		= parent::test(isset($_POST['exp_startdate' . $i]) ? $_POST['exp_startdate' . $i] : '0000-00-00');
				$exp_enddate 		= parent::test(isset($_POST['exp_enddate' . $i]) ? $_POST['exp_enddate' . $i] : '0000-00-00');
				$exp_otherinfo 		= parent::test(isset($_POST['exp_otherinfo' . $i]) ? $_POST['exp_otherinfo' . $i] : '');

				if ($exp_startdate != '')
					$exp_startdate = date('Y-m-d', strtotime($exp_startdate));
				if ($exp_enddate != '')
					$exp_enddate = date('Y-m-d', strtotime($exp_enddate));



				$tabFields	= "(STUD_EXP_DETAILS_ID, STUDENT_ID, JOB_TITLE, COMPANY_NAME, START_DATE,  END_DATE, DESCRIPTION, CREATED_BY, CREATED_ON, UPDATED_BY, UPDATED_ON, CREATED_ON_IP, UPDATED_ON_IP)";
				$insertVal	= "(NULL, '$student_id','$exp_jobtitle', '$exp_companyname', '$exp_startdate', '$exp_enddate', '$exp_otherinfo', '$updated_by', NOW(), '$updated_by', NOW(), '$created_by_ip', '$created_by_ip')";
				$insertSql	= parent::insertData($tableName, $tabFields, $insertVal);
				parent::execQuery($insertSql);
			}
			/* -----------stduent experience details-------------------- */
			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! Student has been updated successfully!';
		}
		return json_encode($data);
	}

	public function get_student_installment_details($student_id = '', $INSTITUTE_COURSE_ID = '', $display = true)
	{
		$img = '';
		$data = array();
		$target = '';

		$sql = "SELECT A.* FROM  student_payments_installments A WHERE A.ACTIVE = '1' AND A.DELETE_FLAG = '0'";
		if ($student_id != '')
			$sql .= " AND A.STUDENT_ID='$student_id'";
		if ($INSTITUTE_COURSE_ID != '')
			$sql .= " AND A.INSTITUTE_COURSE_ID='$INSTITUTE_COURSE_ID'";
		$sql .= ' ORDER BY A.INSTALLMENT_ID ASC';
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$img .= '<table class="table table-responsive table-bordered">';
			while ($rec = $res->fetch_assoc()) {
				$INSTALLMENT_ID  	= $rec['INSTALLMENT_ID '];
				$INSTALLMENT_NAME 	= $rec['INSTALLMENT_NAME'];
				$AMOUNT         	= $rec['AMOUNT'];
				$DATE 	            = $rec['DATE'];
				if ($INSTALLMENT_ID != '') {

					$img .= '<tr id="file-area' . $INSTALLMENT_ID . '">
    								<td>' . $INSTALLMENT_NAME . '</td>	
    							  </tr>';
				}
				array_push($data, $rec);
			}
		}

		$img .= '</table>';
		if (!$display)
			return $data;
		else return $img;
	}

	public function total_coursefess_student($user_id = '')
	{
		$result = 0;
		$sql = "SELECT SUM(COURSE_FEES) as ALL_COURSE_FEES FROM student_course_details WHERE DELETE_FLAG=0 AND STUDENT_ID  = $user_id";

		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['ALL_COURSE_FEES'];
		}
		return $result;
	}
	public function total_paidfess_student($user_id = '')
	{
		$result = 0;
		$sql = "SELECT SUM(FEES_PAID) as FEES_PAID FROM  student_payments WHERE DELETE_FLAG=0 AND STUDENT_ID = $user_id";

		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['FEES_PAID'];
		}
		return $result;
	}

	public function get_admission_count_enquiry($cond = '')
	{
		$result = 0;
		$sql = "SELECT COUNT(*) AS ADMISSION_COUNT FROM  student_enquiry WHERE 1 AND REGISTRATION = 0 ";
		if ($cond != '') {
			$sql .= $cond;
		}
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['ADMISSION_COUNT'];
		}
		return $result;
	}
	//multi subject final exam done check
	public function getExamDoneCheck($subjectId, $courseId, $stud, $inst)
	{
		$output = '';
		$sql = "SELECT COUNT(EXAM_RESULT_ID) AS COUNT1 FROM multi_sub_exam_result WHERE STUDENT_ID='$stud' AND MULTI_SUB_COURSE_ID='$courseId' AND INSTITUTE_ID='$inst' AND STUDENT_SUBJECT_ID='$subjectId' AND DELETE_FLAG = '0' ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$output = $result['COUNT1'];
		}
		return $output;
	}

	public function list_exam_demo_paper_student($user_id = '', $session_id = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM p_exam_attempt A WHERE A.student_id='$user_id' AND A.session_id  = '$session_id' ";

		$sql .= 'ORDER BY A.id DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function list_exam_final_paper_student($user_id = '', $session_id = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM exam_attempt A WHERE A.student_id='$user_id' AND A.session_id  = '$session_id' ";

		$sql .= 'ORDER BY A.id DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function list_exam_final_paper_student_multiple($user_id = '', $session_id = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM multi_sub_exam_attempt A WHERE A.student_id='$user_id' AND A.session_id  = '$session_id' ";

		$sql .= 'ORDER BY A.id DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}


	//typing
	public function get_student_coursesname_typing($course_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT TYPING_COURSE_NAME FROM courses_typing WHERE TYPING_COURSE_ID ='$course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['TYPING_COURSE_NAME'];
		}
		return $result;
	}
	public function get_student_coursescode_typing($course_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT TYPING_COURSE_CODE FROM courses_typing WHERE TYPING_COURSE_ID='$course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['TYPING_COURSE_CODE'];
		}
		return $result;
	}
}
