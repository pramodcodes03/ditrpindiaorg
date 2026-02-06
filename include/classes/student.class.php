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
		$institute_id	 	= parent::test(isset($institute_id) ? $institute_id : '');
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
		$postcode	 		= parent::test(isset($postcode) ? $postcode : '');
		$interested_course = isset($interested_course) ? $interested_course : '';
		$sonof	 	= strtoupper(parent::test(isset($sonof) ? $sonof : ''));
		$refferal_code	 	= strtoupper(parent::test(isset($refferal_code) ? $refferal_code : ''));
		$created_by  		= $fname;

		if ($interested_course == '') {
			$errors['interested_course'] = "Required! Select atleast one course.";
		}


		/* check validations */
		//required validations 
		$requiredArr = array('fname' => $fname, 'mobile' => $mobile);
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
		if ($institute_id == '') {
			$errors['institute_id'] = "Required! Please Select Your Institute.";
		}


		/* files validations */

		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "student_enquiry";
			$tabFields 	= "(ENQUIRY_ID,INSTITUTE_ID,STUDENT_FNAME,STUDENT_GENDER,STUDENT_MOBILE,STUDENT_MOBILE2,INSTRESTED_COURSE,CREATED_BY, CREATED_ON,ADMISSION_FROM)";
			$insertVals	= "(NULL,'$institute_id','$fname','$gender','$mobile','$mobile2','$interested_course','$created_by',NOW(),'Course Enquiry')";
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

	//admission enquiry
	public function add_student_admission_enquiry()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data
		//print_r($_POST); exit();
		extract($_POST);
		$institute_id	 	= parent::test(isset($institute_id) ? $institute_id : '');
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
		$postcode	 		= parent::test(isset($postcode) ? $postcode : '');
		$interested_course = isset($interested_course) ? $interested_course : '';
		$sonof	 	= strtoupper(parent::test(isset($sonof) ? $sonof : ''));
		$refferal_code	 	= strtoupper(parent::test(isset($refferal_code) ? $refferal_code : ''));
		$created_by  		= $fname;
		//   $stud_photo		= isset($_FILES['stud_photo']['name'])?$_FILES['stud_photo']['name']:''; 
		//   $stud_sign			= isset($_FILES['stud_sign']['name'])?$_FILES['stud_sign']['name']:'';
		$stud_photo_id_desc = parent::test(isset($_POST['stud_photo_id_desc']) ? $_POST['stud_photo_id_desc'] : '');

		if ($interested_course == '') {
			$errors['interested_course'] = "Required! Select atleast one course.";
		}

		/* check validations */
		//required validations 
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
		if ($dob != '')
			$dob = @date('Y-m-d', strtotime($dob));

		if ($email != '') {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors['email'] = "Invalid email format";
			}
		}

		if ($refferal_code != '') {
			if (parent::valid_refferal_code($refferal_code))
				$errors['refferal_code'] = 'Sorry! Invalid refferal code. Please insert valid code.';
		}
		if ($institute_id == '') {
			$errors['institute_id'] = "Required! Please Select Your Institute.";
		}


		/* files validations */

		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "student_enquiry";
			$tabFields 	= "(ENQUIRY_ID,INSTITUTE_ID,ABBREVIATION, STUDENT_FNAME,STUDENT_MNAME,STUDENT_LNAME,STUDENT_MOTHERNAME, STUDENT_DOB,STUDENT_GENDER,STUDENT_MOBILE,STUDENT_MOBILE2,STUDENT_EMAIL,STUDENT_PER_ADD,STUDENT_STATE,STUDENT_CITY,STUDENT_PINCODE,INSTRESTED_COURSE,SONOF,CREATED_BY, CREATED_ON,REFFERAL_CODE,STUDENT_ADHAR_NUMBER,ADMISSION_FROM)";
			$insertVals	= "(NULL,'$institute_id','$abbreviation','$fname','$mname','$lname','$mothername','$dob','$gender','$mobile','$mobile2','$email','$per_add','$state','$city','$postcode','$interested_course','$sonof','$created_by',NOW(),'$refferal_code','$stud_photo_id_desc','Admission Enquiry')";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				$lastInsertId = parent::last_id();

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New student admission enquiry has been added successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the student admission enquiry.';
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

		$adharid			= isset($adharid) ? $adharid : '';
		$qualification	= isset($qualification) ? $qualification : '';
		$occupation		= isset($occupation) ? $occupation : '';
		$interested_course = isset($interested_course) ? $interested_course : '';
		$reason_for_course = parent::test(isset($reason_for_course) ? $reason_for_course : '');
		$daily_invest_time = parent::test(isset($daily_invest_time) ? $daily_invest_time : '');
		$todo_future		= parent::test(isset($todo_future) ? $todo_future : '');
		$how_know_us		= parent::test(isset($how_know_us) ? $how_know_us : '');
		$expectations		= parent::test(isset($expectations) ? $expectations : '');
		$news_letter		= parent::test(isset($news_letter) ? $news_letter : 0);
		$enquiry_status	= parent::test(isset($enquiry_status) ? $enquiry_status : '');
		$enquiry_by		= parent::test(isset($enquiry_by) ? $enquiry_by : '');
		$remarks			= parent::test(isset($remarks) ? $remarks : '');

		///////
		$sonof	 	= strtoupper(parent::test(isset($sonof) ? $sonof : ''));
		$doj	 			= parent::test(isset($doj) ? $doj : '');

		if ($doj != '')
			$doj = @date('Y-m-d', strtotime($doj));

		$curr_date = date('Y-m-d');

		$newEndingDate = date("Y-m-d", strtotime($curr_date . " - 1 year"));

		if ($doj < $newEndingDate) {
			echo $errors['doj'] = "Date Should be greater than one year span";
		}



		$institute_id		= parent::test(isset($institute_id) ? $institute_id : '');
		$staff_id			= parent::test(isset($staff_id) ? $staff_id : '');
		$role 			= $_SESSION['user_role'];

		$created_by  		= $_SESSION['user_fullname'];
		$created_by_ip  	= $_SESSION['ip_address'];

		if ($interested_course != '')
			$interested_course = json_encode($interested_course);

		if ($interested_course == '') {
			$errors['interested_course'] = "Required! Select atleast one course.";
		}

		/* check validations */
		//required validations 
		$requiredArr = array('dob' => $dob, 'fname' => $fname, 'mobile' => $mobile, 'gender' => $gender,);
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
		// if(!parent::valid_student_email($email,''))
		//$errors['email'] = 'Sorry! Email is already used.';

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

		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "student_enquiry";
			$setValues 	= "INSTITUTE_ID='$institute_id', STAFF_ID='$staff_id',ABBREVIATION='$abbreviation', STUDENT_FNAME='$fname',STUDENT_MNAME='$mname',STUDENT_LNAME='$lname',STUDENT_MOTHERNAME='$mothername', STUDENT_DOB='$dob',STUDENT_GENDER='$gender',STUDENT_MOBILE='$mobile', STUDENT_MOBILE2='$mobile2', STUDENT_EMAIL='$email', STUDENT_PER_ADD='$per_add', STUDENT_STATE='$state', STUDENT_CITY='$city', STUDENT_PINCODE='$postcode', STUDENT_ADHAR_NUMBER='$adharid', EDUCATIONAL_QUALIFICATION='$qualification', OCCUPATION='$occupation', INSTRESTED_COURSE='$interested_course', REASON_FOR_COURSE='$reason_for_course', DAILY_INVEST_TIME='$daily_invest_time', LIKE_TODO_FUTURE='$todo_future', HOW_KNOW_US='$how_know_us', EXPECTATIONS='$expectations', REMARK='$remarks', ENQUIRY_STATUS='$enquiry_status', NEWS_LETTER='$news_letter',CERT_MNAME='$cert_mname',CERT_LNAME='$cert_lname',SONOF='$sonof',DATE_JOINING='$doj', UPDATED_BY='$created_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip'";

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
		$sql = "SELECT A.*,CONCAT(CONCAT(STUDENT_FNAME,' ',STUDENT_MNAME), ' ', STUDENT_LNAME) AS STUDENT_FULLNAME, DATE_FORMAT(A.STUDENT_DOB, '%d-%m-%Y') AS STUD_DOB_FORMATED, DATE_FORMAT(A.DATE_JOINING, '%d-%m-%Y') JOINING_FORMATED, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y') AS CREATED_DATE FROM student_enquiry A WHERE A.DELETE_FLAG=0 ";
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
		//print_r($_POST); exit();
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data
		extract($_POST);

		$enquiry_id	 	= parent::test(isset($enquiry_id) ? $enquiry_id : '');
		$institute_id	 	= parent::test(isset($institute_id) ? $institute_id : '');
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

		$adharid				= isset($adharid) ? $adharid : '';
		$interested_course	= isset($interested_course) ? $interested_course : '';
		$status 				= parent::test(isset($_POST['status']) ? $_POST['status'] : 1);

		$sonof	 	= strtoupper(parent::test(isset($sonof) ? $sonof : ''));
		$batch	 	= strtoupper(parent::test(isset($batch) ? $batch : ''));

		$photo_id_category = parent::test(isset($_POST['photo_id_category']) ? $_POST['photo_id_category'] : '');
		$photo_id_category_other = parent::test(isset($_POST['photo_id_category_other']) ? $_POST['photo_id_category_other'] : '');


		$stud_photo_id_desc = parent::test(isset($_POST['stud_photo_id_desc']) ? $_POST['stud_photo_id_desc'] : '');
		/* Files */
		$stud_photo		= isset($_FILES['stud_photo']['name']) ? $_FILES['stud_photo']['name'] : '';
		$stud_photo_id		= isset($_FILES['stud_photo_id']['name']) ? $_FILES['stud_photo_id']['name'] : '';

		$stud_sign			= isset($_FILES['stud_sign']['name']) ? $_FILES['stud_sign']['name'] : '';

		if ($stud_photo == '') {
			$errors['stud_photo'] 			= 'Please upload student photo.';
		}
		if ($stud_sign == '') {
			$errors['stud_sign'] 			= 'Please upload student sign.';
		}

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

		$requiredArr = array('dob' => $dob, 'fname' => $fname, 'mobile' => $mobile, 'gender' => $gender);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}

		//new validations
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Invalid email format";
		}
		if ($email == '')
			$errors['email'] = 'Email is required.';

		if (!parent::valid_username($email))
			$errors['email'] = 'Sorry! Email is already used.';
		if (!parent::valid_student_email($email, ''))
			$errors['email'] = 'Sorry! Email is already used.';


		$stringArr = array('fname' => $fname, 'mname' => $mname, 'lname' => $lname);
		$checkString = parent::valid_string($stringArr);
		if (!empty($checkString)) {
			foreach ($checkString as $value)
				$errors[$value] = 'Only letters and white space allowed!';
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
		if ($dob != '') {
			$dob = @date('Y-m-d', strtotime($dob));
		}

		if ($institute_id == '') {
			$errors['institute_id'] = "Required! Please Select Your Institute.";
		}


		//payment details		   		 
		$coursefees 		= isset($_POST['coursefees']) ? $_POST['coursefees'] : 0;
		$minimumamount 	= isset($_POST['minimumamount']) ? $_POST['minimumamount'] : 0;


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
		$exam_secrete_code	= $this->generate_student_exam_secrete_code();
		$confpword 		= parent::generate_password();

		$role 			= $_SESSION['user_role'];

		$created_by_id = $_SESSION['user_id'];
		$created_by  		= $_SESSION['user_fullname'];



		//print_r($errors); exit();
		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "student_details";
			$tabFields 	= "(STUDENT_ID,INSTITUTE_ID,ABBREVIATION,STUDENT_CODE, STUDENT_FNAME,STUDENT_MNAME,STUDENT_LNAME,STUDENT_MOTHERNAME,STUDENT_DOB,STUDENT_GENDER,STUDENT_MOBILE,STUDENT_MOBILE2,STUDENT_EMAIL,STUDENT_PER_ADD,STUDENT_STATE,STUDENT_CITY,STUDENT_PINCODE,STUDENT_ADHAR_NUMBER,SONOF,ENQUIRY_ID,ACTIVE, CREATED_BY, CREATED_ON,REFFERAL_CODE,BATCH_ID)";

			$insertVals	= "(NULL,'$institute_id', '$abbreviation','$studcode', '$fname','$mname','$lname','$mothername','$dob','$gender','$mobile','$mobile2','$email','$per_add','$state','$city','$postcode','$stud_photo_id_desc','$sonof','$enquiry_id','$status','$created_by',NOW(),'$refferal_code','$batch')";

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
				$ecc = 'H';
				$pixel_Size = 20;
				$frame_Size = 5;
				QRcode::png($text, $file, $ecc, $pixel_Size, $frame_size);
				////////////////////////////////////////////////////////////

				$sqlQR = "UPDATE student_details SET QRFILE = '$file' WHERE STUDENT_ID='$student_id'";
				$exSqlQR = parent::execQuery($sqlQR);

				if ($refferal_code !== '') {
					$refferarStudent =  parent::get_refferar_details($refferal_code);
					$refferalAmount =  parent::get_refferal_amount();
				}

				$tableName1 	= "student_course_details";
				$tabFields1 	= "(STUD_COURSE_DETAIL_ID, STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID, COURSE_FEES,DISCOUNT_RATE,DISCOUNT_AMOUNT,TOTAL_COURSE_FEES,FEES_RECIEVED, FEES_BALANCE, REMARKS, PAYMENT_RECIEVED_FLAG,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON)";

				$insertVals1	= "(NULL, '$student_id','$institute_id','$inst_course_id','$coursefees','$discrate','$discamt','$totalcoursefee', '$amtrecieved','$amtbalance','$payremarks',1,'1','0','$created_by',NOW())";
				$insertSql1 = parent::insertData($tableName1, $tabFields1, $insertVals1);
				$exSql1			= parent::execQuery($insertSql1);

				if ($exSql1) {
					$stud_course_detail_id = parent::last_id();
					$receipt_no = date('d-m-Y') . '/' . $this->generate_student_receipt_no() . $student_id;
					//student payment details
					$tableName2 	= "student_payments";
					$tabFields2 	= "(PAYMENT_ID, RECIEPT_NO,STUDENT_ID,INSTITUTE_COURSE_ID,STUD_COURSE_DETAIL_ID, COURSE_FEES, TOTAL_COURSE_FEES, FEES_PAID, FEES_BALANCE, FEES_PAID_DATE, PAYMENT_NOTE,ACTIVE,DELETE_FLAG, CREATED_BY, CREATED_ON)";
					$insertVals2	= "(NULL,'$receipt_no', '$student_id', '$inst_course_id', '$stud_course_detail_id','$coursefees','$totalcoursefee','$amtrecieved','$amtbalance',NOW(),'$payremarks','1','0','$created_by', NOW())";
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
						$tabFieldsR1	= "(PAYMENT_ID, TRANSACTION_NO,TRANSACTION_TYPE,USER_ID,USER_ROLE,PAYMENT_AMOUNT,PAYMENT_REMARK,ACTIVE,CREATED_BY, CREATED_ON)";
						$insertVals1	= "(NULL,'$trans_type','$refferarStudent','4','$refferalAmount','Refferal Amount From $fname $lname','1','$created_by',NOW())";
						$insertSqlR1	= parent::insertData($tableNameR1, $tabFieldsR1, $insertValsR1);
						$exSqlR1		= parent::execQuery($insertSqlR1);

						//reference amount
						$setValuesR 	= "TOTAL_BALANCE = TOTAL_BALANCE + $refferalAmount, UPDATED_BY='$created_by', UPDATED_ON=NOW()";
						$whereClauseR 	= "WHERE USER_ID='$refferarStudent' AND USER_ROLE = 4";
						$updSqlR 		= parent::updateData($tableName91, $setValuesR, $whereClauseR);
						$exSqlR 		= parent::execQuery($updSqlR);
					}

					$sql1 = "UPDATE student_course_details SET ADMISSION_CONFIRMED='1' WHERE STUD_COURSE_DETAIL_ID=$stud_course_detail_id";
					$exSql4	= parent::execQuery($sql1);

					if ($exSql4) {
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
						if ($stud_photo_id != '') {
							$ext 			= pathinfo($_FILES["stud_photo_id"]["name"], PATHINFO_EXTENSION);
							$file_name 		= STUD_PHOTO_ID . '_' . mt_rand(0, 123456789) . '.' . $ext;
							if ($photo_id_category_other != '')
								$photo_id_category = $photo_id_category_other;
							$tabFields7 	= "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,FILE_CATEGORY,FILE_DESC,ACTIVE,CREATED_BY,CREATED_ON)";
							$insertVals7	= "(NULL, '$student_id', '$file_name','" . STUD_PHOTO_ID . "','$photo_id_category','$stud_photo_id_desc','1','$created_by',NOW())";
							$insertSql7		= parent::insertData($tableName6, $tabFields7, $insertVals7);
							$exec7   		= parent::execQuery($insertSql7);


							$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
							$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
							$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
							@mkdir($courseImgPathDir, 0777, true);
							//@mkdir($courseImgThumbPathDir,0777,true);								
							parent::create_thumb_img($_FILES["stud_photo_id"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
							//parent::create_thumb_img($_FILES["stud_photo_id"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
						}
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

						$instcourse 			= parent::get_inst_course_info($inst_course_id);
						$COURSE_ID = isset($instcourse['COURSE_ID']) ? $instcourse['COURSE_ID'] : '';
						$MULTI_SUB_COURSE_ID = isset($instcourse['MULTI_SUB_COURSE_ID']) ? $instcourse['MULTI_SUB_COURSE_ID'] : '';

						$aicpe_course_id = $COURSE_ID;
						$aicpe_course_id_multi = $MULTI_SUB_COURSE_ID;
						$valid_exam = parent::validate_apply_exam($aicpe_course_id, $aicpe_course_id_multi);

						if (!empty($valid_exam)) {
							$invalidArr = array();
							$validerrors = isset($valid_exam['errors']) ? $valid_exam['errors'] : '';
							$success_flag 	= isset($valid_exam['success']) ? $valid_exam['success'] : '';
							if ($success_flag == true) {
								$exam_modes = isset($valid_exam['exam_modes']) ? $valid_exam['exam_modes'] : '';
								$exam_modes = json_decode($exam_modes);
								if (in_array($examtype1, $exam_modes)) {
									$setValues9 	= "EXAM_STATUS='$examstatus1', EXAM_TYPE='$examtype1', UPDATED_BY='$created_by', UPDATED_ON=NOW()";

									if ($examtype1 == '1' && $examstatus1 == '2') $setValues .= ",DEMO_COUNT=0";
									if ($examtype1 == '1' && $examstatus1 == '1') $setValues .= ",DEMO_COUNT=0";
									if ($examtype1 == '1' && $examstatus1 == '3') $setValues .= ",DEMO_COUNT=10";

									$whereClause9 = " WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
									$updateSql9	= parent::updateData($tableName1, $setValues9, $whereClause9);
									$exSql9		= parent::execQuery($updateSql9);
								}
							}
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
		extract($_POST);

		//print_r($_POST); exit(); 	 				 

		$student_id 		= parent::test(isset($_POST['student_id']) ? $_POST['student_id'] : '');
		$institute_id		= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : '');
		$staff_id			= parent::test(isset($_POST['staff_id']) ? $_POST['staff_id'] : '');

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

		$adharid			= isset($adharid) ? $adharid : '';
		$qualification	= isset($qualification) ? $qualification : '';
		$occupation		= isset($occupation) ? $occupation : '';
		$interested_course = isset($interested_course) ? $interested_course : '';
		$reason_for_course = parent::test(isset($reason_for_course) ? $reason_for_course : '');
		$daily_invest_time = parent::test(isset($daily_invest_time) ? $daily_invest_time : '');
		$todo_future		= parent::test(isset($todo_future) ? $todo_future : '');
		$how_know_us		= parent::test(isset($how_know_us) ? $how_know_us : '');
		$expectations		= parent::test(isset($expectations) ? $expectations : '');
		$news_letter		= parent::test(isset($news_letter) ? $news_letter : 0);

		$remarks			= parent::test(isset($remarks) ? $remarks : '');
		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : 1);

		$stud_course_detail_id = parent::test(isset($stud_course_detail_id) ? $stud_course_detail_id : '');

		///////
		$sonof	 	= strtoupper(parent::test(isset($sonof) ? $sonof : ''));
		$doj	 			= parent::test(isset($doj) ? $doj : '');

		if ($interested_course == '') {
			$errors['interested_course'] = "Required! Select Course.";
		}

		if ($doj != '') {
			$doj = @date('Y-m-d', strtotime($doj));
		}

		$curr_date = date('Y-m-d');

		$newEndingDate = date("Y-m-d", strtotime($curr_date . " - 1 year"));

		if ($doj < $newEndingDate) {
			echo $errors['doj'] = "Date Should be greater than one year span";
		}

		// educational details
		$max_edu 			= parent::test(isset($_POST['max_edu']) ? $_POST['max_edu'] : 0);
		$max_exp 			= parent::test(isset($_POST['max_exp']) ? $_POST['max_exp'] : 0);


		$photo_id_category = parent::test(isset($_POST['photo_id_category']) ? $_POST['photo_id_category'] : '');
		$photo_id_category_other = parent::test(isset($_POST['photo_id_category_other']) ? $_POST['photo_id_category_other'] : '');

		//$stud_photo_id_type = parent::test(isset($_POST['stud_photo_id_type'])?$_POST['stud_photo_id_type']:'');
		$stud_photo_id_desc = parent::test(isset($_POST['stud_photo_id_desc']) ? $_POST['stud_photo_id_desc'] : '');
		/* Files */
		$stud_photo		= isset($_FILES['stud_photo']['name']) ? $_FILES['stud_photo']['name'] : '';
		$stud_photo_id		= isset($_FILES['stud_photo_id']['name']) ? $_FILES['stud_photo_id']['name'] : '';

		$stud_sign			= isset($_FILES['stud_sign']['name']) ? $_FILES['stud_sign']['name'] : '';

		//payment details

		$examtype1 		= isset($_POST['examtype1']) ? $_POST['examtype1'] : '';
		$examstatus1 		= isset($_POST['examstatus1']) ? $_POST['examstatus1'] : '';

		$language 		= isset($_POST['language']) ? $_POST['language'] : '';

		/*  $examfees 		= isset($_POST['examfees'])?$_POST['examfees']:'';*/

		$inst_course_id = $interested_course;

		if ($examtype1 == '') $errors['examtype1'] = 'Please select exam mode!';


		$institute_id		= parent::test(isset($institute_id) ? $institute_id : '');
		$staff_id			= parent::test(isset($staff_id) ? $staff_id : '');
		$role 			= $_SESSION['user_role'];

		$created_by_id  		= ($role == 5) ? $_SESSION['user_id'] : 0;
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
		if ($dob != '') {
			$dob = @date('Y-m-d', strtotime($dob));
		}


		/* files validations */
		//print_r($errors); exit();
		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			//student personal details
			$tableName 	= "student_details";
			$setValues 	= "ABBREVIATION='$abbreviation',STUDENT_FNAME='$fname',STUDENT_MNAME='$mname',STUDENT_LNAME='$lname',CERT_MNAME='$cert_mname', CERT_LNAME='$cert_lname',STUDENT_MOTHERNAME='$mothername',STUDENT_DOB='$dob',STUDENT_GENDER='$gender',STUDENT_MOBILE='$mobile', STUDENT_MOBILE2='$mobile2',STUDENT_EMAIL='$email',STUDENT_PER_ADD='$per_add',STUDENT_STATE='$state', STUDENT_CITY='$city', STUDENT_PINCODE='$postcode',STUDENT_ADHAR_NUMBER='$adharid', EDUCATIONAL_QUALIFICATION='$qualification', OCCUPATION='$occupation',STUD_LANG='$language',SONOF='$sonof',DATE_JOINING='$doj', ACTIVE='$status', UPDATED_BY='$updated_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip'";
			$whereClause = " WHERE STUDENT_ID='$student_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);


			//student update password
		$tableName 	= "user_login_master";
			$setValues 	= "PASS_WORD='MD5('$mobile')'";
			$whereClause = " WHERE USER_ID='$student_id' AND USER_ROLE=4";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			$instcourse = parent::get_inst_course_info($inst_course_id);
			$COURSE_ID = isset($instcourse['COURSE_ID']) ? $instcourse['COURSE_ID'] : '';
			$MULTI_SUB_COURSE_ID = isset($instcourse['MULTI_SUB_COURSE_ID']) ? $instcourse['MULTI_SUB_COURSE_ID'] : '';

			$aicpe_course_id = $COURSE_ID;
			$aicpe_course_id_multi = $MULTI_SUB_COURSE_ID;
			$valid_exam = parent::validate_apply_exam($aicpe_course_id, $aicpe_course_id_multi);

			$tableName1 	= "student_course_details";
			if (!empty($valid_exam)) {
				$invalidArr = array();
				$validerrors = isset($valid_exam['errors']) ? $valid_exam['errors'] : '';
				$success_flag 	= isset($valid_exam['success']) ? $valid_exam['success'] : '';
				if ($success_flag == true) {
					$exam_modes = isset($valid_exam['exam_modes']) ? $valid_exam['exam_modes'] : '';
					$exam_modes = json_decode($exam_modes);
					if (in_array($examtype1, $exam_modes)) {
						$setValues9 	= "EXAM_STATUS='$examstatus1', EXAM_TYPE='$examtype1', UPDATED_BY='$updated_by', UPDATED_ON=NOW(),UPDATED_ON_IP='$created_by_ip'";

						if ($examtype1 == '1' && $examstatus1 == '2') $setValues .= ",DEMO_COUNT=0";
						if ($examtype1 == '1' && $examstatus1 == '1') $setValues .= ",DEMO_COUNT=0";
						if ($examtype1 == '1' && $examstatus1 == '3') $setValues .= ",DEMO_COUNT=10";

						$whereClause9 = " WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
						$updateSql9	= parent::updateData($tableName1, $setValues9, $whereClause9);
						$exSql9		= parent::execQuery($updateSql9);
					}
				}
			}


			$courseImgPathDir 		= STUDENT_DOCUMENTS_PATH . '/' . $student_id . '/';

			/*	 $bucket_directory = 'student/'.$student_id.'/'; */

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
				/*
						$s3_obj = new S3Class();
						$activityContent = $_FILES['stud_photo']['name'];
                        $fileTempName = $_FILES['stud_photo']['tmp_name'];
                        $new_width = 800;
                        $new_height = 750;
                        $image_p = imagecreatetruecolor($new_width, $new_height);
                        $image = imagecreatefromstring(file_get_contents($fileTempName));
                        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));
                        
                        $newFielName = tempnam(null,null); // take a llok at the tempnam and adjust parameters if needed
                        imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()
                        
                        $response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory.''.$file_name , S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["stud_photo"]["type"]));
						*/
				//var_dump($response);
				//exit();

				$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
				$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
				//$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
				@mkdir($courseImgPathDir, 0777, true);
				//@mkdir($courseImgThumbPathDir,0777,true);								
				parent::create_thumb_img($_FILES["stud_photo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
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
				/*	
						$s3_obj = new S3Class();
						$activityContent = $_FILES['stud_photo_id']['name'];
                        $fileTempName = $_FILES['stud_photo_id']['tmp_name'];
                        $new_width = 800;
                        $new_height = 750;
                        $image_p = imagecreatetruecolor($new_width, $new_height);
                        $image = imagecreatefromstring(file_get_contents($fileTempName));
                        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));
                        
                        $newFielName = tempnam(null,null); // take a llok at the tempnam and adjust parameters if needed
                        imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()
                        
                        $response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory.''.$file_name , S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["stud_photo_id"]["type"]));*/

				//var_dump($response);
				//exit();


				$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
				$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
				//$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
				@mkdir($courseImgPathDir, 0777, true);
				//@mkdir($courseImgThumbPathDir,0777,true);								
				parent::create_thumb_img($_FILES["stud_photo_id"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
				//parent::create_thumb_img($_FILES["stud_photo_id"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
			}
			if ($stud_photo_id_desc != '') {
				$tableName5 	= "student_files";
				$setValues5 	= "FILE_DESC='$stud_photo_id_desc', UPDATED_BY='$updated_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip'";
				$whereClause5 = " WHERE FILE_ID='$photo_id_desc_id'";
				$updateSql5	= parent::updateData($tableName5, $setValues5, $whereClause5);
				$exSql5		= parent::execQuery($updateSql5);
			}
			if ($stud_sign != '') {

				$ext 			= pathinfo($_FILES["stud_sign"]["name"], PATHINFO_EXTENSION);
				$file_name 		= STUD_PHOTO_SIGN . '_' . mt_rand(0, 123456789) . '.' . $ext;

				$sqlUpd1 = "UPDATE student_files SET DELETE_FLAG=0, ACTIVE=0 WHERE STUDENT_ID='$student_id' AND FILE_LABEL='" . STUD_PHOTO_SIGN . "'";
				parent::execQuery($sqlUpd1);


				$tabFields8 	= "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
				$insertVals8	= "(NULL, '$student_id', '$file_name','" . STUD_PHOTO_SIGN . "','1','$updated_by',NOW(), '$created_by_ip')";
				$insertSql8		= parent::insertData($tableName3, $tabFields8, $insertVals8);
				$exec8   		= parent::execQuery($insertSql8);

				$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
				$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
				//$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
				@mkdir($courseImgPathDir, 0777, true);
				//@mkdir($courseImgThumbPathDir,0777,true);								
				parent::create_thumb_img($_FILES["stud_sign"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
				//parent::create_thumb_img($_FILES["stud_photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);	


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
	//list student from institute
	public function list_student_direct_admission($student_id = '', $institute_id = '', $staff_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT A.*, get_student_name(A.STUDENT_ID) AS STUDENT_FULLNAME,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME, get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_address(A.INSTITUTE_ID) as INSTITUTE_ADDRESS,(SELECT F.CITY_NAME as city_name FROM city_master F WHERE A.INSTITUTE_ID=F.CITY_ID) as INSTITUTE_CITY,get_institute_state(A.INSTITUTE_ID) as INSTITUTE_STATE,get_institute_mobile(A.INSTITUTE_ID) as INSTITUTE_MOBILE, get_stud_photo(A.STUDENT_ID) AS  STUD_PHOTO, DATE_FORMAT(A.STUDENT_DOB, '%d-%m-%Y') AS STUD_DOB_FORMATED,DATE_FORMAT(A.DATE_JOINING, '%d-%m-%Y') JOINING_FORMATED, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS ACCOUNT_REGISTERED_DATE, B.USER_NAME, B.USER_LOGIN_ID,C.STUD_COURSE_DETAIL_ID,C.INSTITUTE_COURSE_ID,C.COURSE_FEES,C.DISCOUNT_RATE,C.DISCOUNT_AMOUNT,C.TOTAL_COURSE_FEES,C.FEES_RECIEVED,C.FEES_BALANCE,C.REMARKS,C.PAYMENT_RECIEVED_FLAG,C.PAYMENT_ID,C.DEMO_COUNT,C.EXAM_STATUS,C.EXAM_TYPE,C.EXAM_ATTEMPT,C.ADMISSION_CONFIRMED,C.OFFLINE_PAYMENT_ID,D.RECIEPT_NO,D.FEES_PAID,D.FEES_PAID_DATE,D.FEES_PAYMENT_MODE,D.PAYMENT_NOTE FROM student_details A LEFT JOIN user_login_master B ON A.STUDENT_ID=B.USER_ID AND B.USER_ROLE=4 LEFT JOIN student_course_details C ON A.STUDENT_ID=C.STUDENT_ID LEFT JOIN student_payments D ON A.STUDENT_ID=D.STUDENT_ID WHERE A.DELETE_FLAG=0 ";
		if ($student_id != '') {
			$sql .= " AND A.STUDENT_ID='$student_id' ";
		}
		if ($institute_id != '') {
			$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
		}
		if ($staff_id != '') {
			$sql .= " AND A.STAFF_ID='$staff_id' ";
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
	public function list_student_educational_info($edu_datail_id = '', $student_id)
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
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//list student educational info
	public function list_student_experience_info($exp_datail_id = '', $student_id)
	{
		$data = '';
		$sql = "SELECT A.*, DATE_FORMAT(A.START_DATE, '%d-%m-%Y') AS START_DATE_FORMATTED,DATE_FORMAT(A.END_DATE, '%d-%m-%Y') AS END_DATE_FORMATTED FROM student_experience_details A WHERE 1";
		if ($exp_datail_id != '') {
			$sql .= " AND A.STUDENT_EXPERIENCE_ID='$exp_datail_id' ";
		}
		if ($student_id != '') {
			$sql .= " AND A.STUDENT_ID='$student_id' ";
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

		$sql = "SELECT A.*,get_institute_demo_count(A.INSTITUTE_ID) AS INSTITUTE_DEMO_COUNT, get_student_name(A.STUDENT_ID) AS STUDENT_NAME,get_student_code(A.STUDENT_ID) AS STUDENT_CODE , (SELECT C.EXAM_STATUS FROM exam_status_master C WHERE C.EXAM_STATUS_ID=A.EXAM_STATUS) AS EXAM_STATUS_NAME, (SELECT D.EXAM_TYPE FROM exam_types_master D WHERE D.EXAM_TYPE_ID=A.EXAM_TYPE) AS EXAM_TYPE_NAME, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON,'%d-%m-%Y') AS ACCOUNT_REGISTERED_DATE FROM student_course_details A LEFT JOIN user_login_master B ON A.STUDENT_ID=B.USER_ID  WHERE A.DELETE_FLAG=0 AND B.USER_ROLE=4 ";
		if ($course_detail_id != '') {
			$sql .= " AND A.STUD_COURSE_DETAIL_ID='$course_detail_id' ";
		}
		if ($student_id != '') {
			$sql .= " AND A.STUDENT_ID='$student_id' ";
		}

		$sql .= 'ORDER BY A.CREATED_ON DESC';
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

	/* generate student code */
	/*
	public function generate_student_code()
	{
		$code = '';
		$code = parent::getRandomCode(8);
		$sql = "SELECT STUDENT_CODE FROM student_details WHERE STUDENT_CODE='$code'";
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$this->generate_student_code();
		}
		return $code;
	}
	*/
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

		$studcode 		= $this->generate_student_code($institute_id);
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
				$insertVals2	= "(NULL, '$student_id', '$uname', MD5('$confpword'),'$role',NOW(),'$status','$created_by',NOW(), '$created_by_ip')";
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

		//  $studcode 		= parent::test(isset($_POST['studcode'])?$_POST['studcode']:'');
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
		$doj		 	= parent::test(isset($_POST['doj']) ? $_POST['doj'] : '');

		if ($doj != '')
			$doj = @date('Y-m-d', strtotime($doj));

		$curr_date = date('Y-m-d');

		$newEndingDate = date("Y-m-d", strtotime($curr_date . " - 1 year"));

		if ($doj < $newEndingDate) {
			echo $errors['doj'] = "Date Should be greater than one year span";
		}




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
		$studcode 	= $this->generate_student_code($institute_id);
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
					$insertVals2	= "(NULL, '$student_id', '$uname', MD5('$confpword'),'4',NOW(),'1','$created_by',NOW(), '$created_by_ip')";
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
	public function get_student_course_id($inst_course_id)
	{
		$data = '';
		$result = '';
		echo $sql = "SELECT COURSE_ID, MULTI_SUB_COURSE_ID FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id' AND DELETE_FLAG=0";
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
		echo $sql = "SELECT COURSE_NAME FROM courses WHERE COURSE_ID='$course_id' AND DELETE_FLAG=0";
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
		echo $sql = "SELECT COURSE_CODE FROM courses WHERE COURSE_ID='$course_id' AND DELETE_FLAG=0";
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
		echo $sql = "SELECT COURSE_AWARD FROM courses WHERE COURSE_ID='$course_id' AND DELETE_FLAG=0";
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
		echo $sql = "SELECT AWARD FROM course_awards WHERE AWARD_ID='$award_id' AND DELETE_FLAG=0";
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
		echo $sql = "SELECT CITY_NAME FROM city_master WHERE CITY_ID='$city_id'";
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
		echo $sql = "SELECT STATE_NAME FROM states_master WHERE STATE_ID='$state_id'";
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
		echo $sql = "SELECT MULTI_SUB_COURSE_NAME FROM multi_sub_courses WHERE MULTI_SUB_COURSE_ID='$course_id' AND DELETE_FLAG=0";
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
		echo $sql = "SELECT MULTI_SUB_COURSE_CODE FROM multi_sub_courses WHERE MULTI_SUB_COURSE_ID='$course_id' AND DELETE_FLAG=0";
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
		echo $sql = "SELECT MULTI_SUB_COURSE_AWARD FROM multi_sub_courses WHERE MULTI_SUB_COURSE_ID='$course_id' AND DELETE_FLAG=0";
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
		echo $sql = "SELECT AWARD FROM course_awards WHERE AWARD_ID='$award_id' AND DELETE_FLAG=0";
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
}
