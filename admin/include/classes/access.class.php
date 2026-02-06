<?php
class access extends database_results
{

	function check($username, $password, $flag = NULL)
	{
		$info = NULL;
		$username = parent::test($username);
		$password = parent::test($password);
		//$password_enc = md5($password);
		$password_enc = ($flag != 'direct_login') ? md5($password) : $password;

		$tablename 	= ' user_login_master a ';
		$tabFields 	= " a.USER_LOGIN_ID,a.USER_ID, a.USER_NAME, a.USER_ROLE ";
		$whereClause   = " where a.USER_NAME ='" . $username . "' and a.PASS_WORD ='" . $password_enc . "' AND a.ACTIVE=1 AND a.DELETE_FLAG=0";

		$selQue 	= parent::selectData($tabFields, $tablename, $whereClause);
		$selectAccess 	= parent::execQuery($selQue);
		$info = $selectAccess->fetch_assoc();

		return $info;
	}

	function create_session($info = NULL)
	{
		//start session
		//$this->start_session();		
		//add session values
		$user_login_id 	= $_SESSION['user_login_id'] = isset($info['USER_LOGIN_ID']) ? $info['USER_LOGIN_ID'] : '';
		$user_id 		= $_SESSION['user_id'] = isset($info['USER_ID']) ? $info['USER_ID'] : '';
		$user_name 		= $_SESSION['user_name'] = isset($info['USER_NAME']) ? $info['USER_NAME'] : '';
		$user_role 		= $_SESSION['user_role'] = isset($info['USER_ROLE']) ? $info['USER_ROLE'] : '';
		$session_id 	= $_SESSION['sid'] = session_id();
		$login_time  	= $_SESSION['login_time'] = time();
		$ip_address  	= $_SESSION['ip_address'] = $this->get_client_ip();
		$res 			= $this->set_login_time($user_login_id);
		$user_fullname 	= $_SESSION['user_fullname'] = $this->get_curr_username($user_id, $user_role);
		$user_photo 	= $_SESSION['user_photo'] = $this->get_curr_userphoto($user_id, $user_role);

		return true;
	}
	public function	set_login_time($login_id)
	{
		$ip = $this->get_client_ip();
		$sql = "UPDATE user_login_master SET LAST_LOGIN_DATE=NOW(), LAST_LOGIN_IP=CURR_LOGIN_IP, CURR_LOGIN_IP='$ip' WHERE USER_LOGIN_ID='$login_id'";
		$exc = parent::execQuery($sql);
		if (!$exc)
			return false;
		return true;
	}
	public function user_login($uname, $pword, $flag = NULL)
	{

		$errors = array();
		$data = array();
		if ($uname == '')
			$errors['uname'] = 'Username is required.';
		if ($pword == '')
			$errors['pword'] = 'Password is required.';
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			$info 	=	$this->check($uname, $pword, $flag);
			if ($info != NULL) {
				$result = $this->create_session($info);
				if (!$result) {
					$errors['message'] = 'Sorry! Login credentials does not matched.';
					$data['success'] = false;
					$data['errors']  = $errors;
					//$msg = "Login failed! with username='$username' and password='$password'";
					//$this->add_activity("Login Failed",$msg);
					return false;
				} else {

					$data['success'] = true;
					$data['message'] = 'Success!User logged in successfully!';
					//$msg = "Login Success! with username='$username' and password='$password'";
					//$this->add_activity("Login Success",$msg);
				}
			} else {
				$errors['message'] = 'Sorry! Login credentials does not matched.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}

	function start_session()
	{
		if (!isset($_SESSION))
			session_start();
	}
	function destroy_session()
	{
		$flag = session_destroy();

		return $flag;
	}
	public function user_logout()
	{
		$res = $this->destroy_session();
		if (!$res)
			return false;
		return true;
	}
	function redirect($url)
	{
		header('location:' . $url);
	}
	public function valid_mobile($mobile)
	{
		$resp = '';
		if (strlen($mobile) != 10) {
			$resp = 'Mobile number should have 10 digits.';
		}
		/*$first_no = $mobile[0];//substr($mobile,1);
		$arr = unserialize (MOBILE_START_DIGIT);
		if(!in_array($first_no,$arr))
		{
			$resp = 'Only numbers are allowed. Mobile number should start with 9 or 8 or 7 only.';
		}*/
		return $resp;
	}
	public function valid_required($reqArr)
	{
		$resp = array();
		if (!empty($reqArr)) {
			foreach ($reqArr as $key => $value) {
				if ($value == '')
					array_push($resp, $key);
			}
		}
		return $resp;
	}
	public function valid_decimal($value)
	{
		$regex = '/^\s*[+\-]?(?:\d+(?:\.\d*)?|\.\d+)\s*$/';
		return preg_match($regex, $value);
	}
	public function valid_string($stringArr)
	{
		$errors = array();
		if (!empty($stringArr)) {
			foreach ($stringArr as $key => $value) {
				if (!preg_match("/^[a-zA-Z ]*$/", $value))
					array_push($errors, $key);
			}
		}
		return $errors;
	}
	public function valid_image_format($imageArr)
	{
		$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
		$errors = array();
		if (!empty($imageArr)) {
			foreach ($imageArr as $key => $value) {
				if ($value != '') {
					$extension = pathinfo($value, PATHINFO_EXTENSION);
					if (!in_array($extension, $allowed_ext))
						array_push($errors, $key);
				}
			}
		}
		return $errors;
	}
	public function valid_username($uname)
	{
		$sql = "SELECT USER_NAME FROM user_login_master WHERE USER_NAME='$uname'";

		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}

	public function valid_rollnumber($roll_number, $institute_id)
	{
		$sql = "SELECT ROLL_NUMBER FROM student_details WHERE ROLL_NUMBER='$roll_number' AND INSTITUTE_ID = '$institute_id'";

		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}

	//valid refferal code
	public function valid_refferal_code($code, $institute_id)
	{
		$sql = "SELECT STUDENT_CODE  FROM  student_details WHERE STUDENT_CODE ='$code' AND INSTITUTE_ID = '$institute_id' AND ACTIVE = '1' AND DELETE_FLAG = '0' ";

		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}

	public function valid_username_onupdate($uname, $login_id = '')
	{
		$sql = "SELECT USER_NAME FROM user_login_master WHERE USER_NAME='$uname'";
		if ($login_id != '')
			$sql .= " AND USER_LOGIN_ID!='$login_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}

	public function valid_institute_staff_email($email, $staff_id = '')
	{

		$sql = "SELECT STAFF_EMAIL FROM institute_staff_details WHERE STAFF_EMAIL='$email'";
		if ($staff_id != '')
			$sql .= " AND STAFF_ID!='$staff_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}
	public function valid_institute_email($email, $inst_id = '')
	{
		$sql = "SELECT EMAIL FROM institute_details WHERE EMAIL='$email'";
		if ($inst_id != '')
			$sql .= " AND INSTITUTE_ID!='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}
	public function valid_institute_mobile($mobile, $inst_id = '')
	{
		$sql = "SELECT MOBILE FROM institute_details WHERE MOBILE='$mobile'";
		if ($inst_id != '')
			$sql .= " AND INSTITUTE_ID!='$inst_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}
	public function valid_student_email($email, $stud_id = '')
	{
		$sql = "SELECT STUDENT_EMAIL FROM student_details WHERE STUDENT_EMAIL='$email' AND ACTIVE= '1' AND DELETE_FLAG = '0'";
		if ($stud_id != '')
			$sql .= " AND STUDENT_ID!='$stud_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}
	public function valid_employer_email($email, $emp_id = '')
	{
		$sql = "SELECT EMAIL FROM employer_details WHERE EMAIL='$email'";
		if ($emp_id != '')
			$sql .= " AND EMPLOYER_ID!='$emp_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}
	//Email Validator For AMC
	public function valid_amc_email($email, $amc_id = '')
	{
		$sql = "SELECT EMAIL FROM amc_details WHERE EMAIL='$email'";
		if ($emp_id != '')
			$sql .= " AND AMC_ID!='$amc_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}

	public function validate_amc_code($empcode, $amc_id = '')
	{
		$sql = "SELECT AMC_CODE FROM amc_details WHERE AMC_CODE='$empcode'";
		if ($emp_id != '')
			$sql .= " AND AMC_ID!='$amc_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}
	public function getRandomCode($length)
	{
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		return substr(str_shuffle($chars), 0, $length);
	}
	public function getRandomCode2()
	{
		$code = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$numbers = '0123456789';
		$code .=  substr(str_shuffle($chars), 0, 1);
		$code .=  substr(str_shuffle($numbers), 0, 3);
		return $code;
	}
	public function getRandomCode3()
	{
		$code = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$numbers = '0123456789';
		$code .=  substr(str_shuffle($chars), 0, 3);
		$code .=  substr(str_shuffle($numbers), 0, 3);
		return $code;
	}
	public function curr_date()
	{
		$data = '';
		$sql = "SELECT DATE_FORMAT(NOW(), '%d-%m-%Y') AS TODAY_DATE";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$rec = $res->fetch_assoc();
			$data = $rec['TODAY_DATE'];
		}
		return $data;
	}
	public function acc_expiry_date($start_date = '')
	{
		$data = '';
		if ($start_date != '') {
			$start_date = date('Y-m-d', strtotime($start_date));
			$sql = "SELECT `calculate_account_expiry_date`('$start_date') AS `expiry_date`;";
		} else
			$sql = "SELECT `calculate_account_expiry_date`(CURDATE()) AS `expiry_date`;";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$rec = $res->fetch_assoc();
			$data = $rec['expiry_date'];
		}
		return $data;
	}
	public function get_curr_username($user_id, $role)
	{
		$name = '';
		$tablename = '';
		switch ($role) {
			//admin
			case (1):
				$tablename 	= ' admin_details_master a ';
				$tabFields 	= " CONCAT(a.FIRST_NAME,' ' , a.LAST_NAME) AS name ";
				$whereClause = " where a.ADMIN_ID ='" . $user_id . "'";
				break;
			// Admin staff
			case (6):
				$tablename 	= ' admin_staff_details a ';
				$tabFields 	= " a.STAFF_FULLNAME AS name ";
				$whereClause = " where a.STAFF_ID ='" . $user_id . "'";
				break;
			// Institute
			case (2):
				$tablename 	= ' institute_details a ';
				$tabFields 	= " a.INSTITUTE_NAME AS name ";
				$whereClause = " where a.INSTITUTE_ID ='" . $user_id . "'";
				break;
			// Institue staff
			case (5):
				$tablename 	= ' institute_staff_details a ';
				$tabFields 	= " a.STAFF_FULLNAME AS name ";
				$whereClause = " where a.STAFF_ID ='" . $user_id . "'";
				break;
			// Student
			case (4):
				$tablename 	= ' student_details a ';
				$tabFields 	= " a.STUDENT_FNAME AS name ";
				$whereClause = " WHERE a.STUDENT_ID='$user_id'";
				break;
			// EMPLOYER
			case (3):
				$tablename 	= ' admin_staff_details a ';
				$tabFields 	= " a.STAFF_FULLNAME AS name ";
				$whereClause = " where a.STAFF_ID ='" . $user_id . "' AND USER_ROLE = '3'";
				break;
			// Franchise
			case (8):
				$tablename 	= ' institute_details a ';
				$tabFields 	= " a.INSTITUTE_NAME AS name ";
				$whereClause = " where a.INSTITUTE_ID ='" . $user_id . "'";
				break;
			default:
				$tablename 	= ' user_login_master a ';
				$tabFields 	= " a.USER_NAME AS name ";
				$whereClause = " where a.USER_ID ='" . $user_id . "' AND USER_ROLE='$role'";
				break;
		}
		$selQue = parent::selectData($tabFields, $tablename, $whereClause);
		$res 	= parent::execQuery($selQue);
		if ($res && $res->num_rows > 0) {
			$rec = $res->fetch_assoc();
			$name = $rec['name'];
		}
		return $name;
	}
	public function get_curr_userphoto($user_id, $role)
	{
		$photo = '';
		$name = '';
		$tablename = '';
		switch ($role) {
			//admin
			case (1):
				$tablename 	= ' admin_details_master a ';
				$tabFields 	= " PHOTO AS photo ";
				$whereClause = " where a.ADMIN_ID ='" . $user_id . "'";
				break;
			// Admin staff
			case (6):
				$tablename 	= ' admin_staff_details a ';
				$tabFields 	= " a.STAFF_PHOTO AS photo ";
				$whereClause = " where a.STAFF_ID ='" . $user_id . "'";
				break;
			// Institute
			case (2):
				$tablename 	= ' institute_files a ';
				$tabFields 	= " a.FILE_NAME AS photo ";
				$whereClause = " where a.INSTITUTE_ID ='" . $user_id . "' AND a.FILE_LABEL='logo'";
				break;

			// Franchise
			case (8):
				$tablename 	= ' institute_files a ';
				$tabFields 	= " a.FILE_NAME AS photo ";
				$whereClause = " where a.INSTITUTE_ID ='" . $user_id . "' AND a.FILE_LABEL='logo'";
				break;
			// Institue staff
			case (5):
				$tablename 	= ' institute_staff_details a ';
				$tabFields 	= " a.STAFF_PHOTO AS photo ";
				$whereClause = " where a.STAFF_ID ='" . $user_id . "'";
				break;
			// Student
			case (4):
				$tablename 	= ' student_files a ';
				$tabFields 	= " A.FILE_NAME AS photo ";
				$whereClause = " WHERE a.STUDENT_ID='$user_id' AND a.FILE_LABEL='photo'";
				break;
			// Emplouyer
			case (3):
				$tablename 	= ' employer_files a ';
				$tabFields 	= " a.FILE_NAME AS photo ";
				$whereClause = " where a.EMPLOYER_ID ='" . $user_id . "' AND a.FILE_LABEL='EMP_PHOTO'";
				break;
			default:
				$tablename 	= ' user_login_master a ';
				$tabFields 	= " a.USER_NAME AS name ";
				$whereClause = " where a.USER_ID ='" . $user_id . "' AND USER_ROLE='.$role.'";
				break;
		}
		$selQue = parent::selectData($tabFields, $tablename, $whereClause);
		$res 	= parent::execQuery($selQue);
		if ($res && $res->num_rows > 0) {
			$rec = $res->fetch_assoc();
			$name = $rec['photo'];
			switch ($role) {
				//admin
				//case(1): $photo = '../uploads/admin/1/admin.jpg';break;
				case (1):
					$photo = ADMIN_PHOTO_PATH . '/' . $user_id . '/' . $name;
					break;
				//institute
				case (2):
					$photo = INSTITUTE_DOCUMENTS_PATH . '/' . $user_id . '/' . $name;
					break;
				//institute
				case (8):
					$photo = INSTITUTE_DOCUMENTS_PATH . '/' . $user_id . '/' . $name;
					break;
				//emplyer
				case (3):
					$photo = EMPLOYER_PHOTO_PATH . '/' . $user_id . '/' . $name;
					break;
				//student
				case (4):
					$photo = STUDENT_DOCUMENTS_PATH . '/' . $user_id . '/' . $name;
					break;
				// Institue staff
				case (5):
					$photo = INSTITUTE_STAFF_PHOTO_PATH . '/' . $user_id . '/' . $name;
					break;
				// Admin staff
				case (6):
					$photo = ADMIN_STAFF_PHOTO_PATH . '/' . $user_id . '/' . $name;
					break;
			}
		}
		if ($name == '')
			$photo = '../uploads/default_user.png';
		return $photo;
	}

	function forgot_password($username)
	{
		// generate new password
		$username = $this->test_input($username);
		$characters = '0123456789ABCDEFGHJKMNOPQRSTUWXYZ';
		$password = '';
		for ($p = 0; $p < 7; $p++) {
			$password .= $characters[mt_rand(0, strlen($characters))];
		}

		$password_enc = md5($password);

		$tablename 	= 'user_login_master';
		$setValues 	= "PASS_WORD = $password_enc";
		$whereClause   = "where USER_NAME ='" . $username . "'";

		$selQue 	= parent::updateData($tablename, $setValues, $whereClause);
		$resetpassword = parent::execQuery($selQue);

		//send email

		$to = $username;
		$sub = 'New password for DITRP';
		$from =  EMAIL_USERNAME;

		$message = '';
		$message .= 'Your new password=';
		$message .= $password;
		$headers = "From: $from";
		mail($to, $sub, $message, $headers);
		return true;
	}

	public function change_password()
	{
		$errors 			= array();  // array to hold validation errors
		$data 				= array();
		$current_password 	= parent::test($_POST['current_password']) ? $_POST['current_password'] : '';
		$new_password	 	= parent::test($_POST['new_password']) ? $_POST['new_password'] : '';
		$confirm_new_password = parent::test($_POST['confirm_new_password']) ? $_POST['confirm_new_password'] : '';
		$updated_by 		= $_SESSION['user_fullname'];
		$updated_on_ip 		= $_SESSION['ip_address'];
		if ($new_password == '') $errors['new_password'] = 'Please enter new password.';
		if ($confirm_new_password == '')  $errors['confirm_new_password'] = 'Please confirm the password.';
		$requiredArr = array('new_password' => $new_password, 'confirm_new_password' => $confirm_new_password);
		$checkRequired = $this->valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}
		if ($new_password != $confirm_new_password) $errors['confirm_new_password']	= "Confirm password not matched!";
		$password_enc 	= md5($current_password);
		$tableName 	= 'user_login_master';
		$tabFields 	= "*";
		$whereClause   = "where USER_LOGIN_ID =" . $_SESSION['user_login_id'] . " AND PASS_WORD='$password_enc'";
		$selQue 	= parent::selectData($tabFields, $tableName, $whereClause);
		$selectAccess 	= parent::execQuery($selQue);
		$info = $selectAccess->fetch_assoc();
		if (!isset($info['USER_LOGIN_ID']))
			$errors['current_password'] = 'Sorry, your current password does not match!';
		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			$new_password_enc 	= md5($confirm_new_password);
			$setValues 			= "PASS_WORD = '$new_password_enc', PASSWORD_CHANGE_DATE=NOW(), UPDATED_BY = '$updated_by', UPDATED_ON = NOW(), UPDATED_ON_IP='$updated_on_ip'";
			$whereClause		= "$whereClause";
			$changePassword 	= parent::updateData($tableName, $setValues, $whereClause);
			$execPassword 		= parent::execQuery($changePassword);
			if ($execPassword) {
				$msg = 'Your password has been successfully reset.';
				$data['success'] = true;
				$data['message'] = 'Success! Password has been changed successfully!';
			} else {
				$errors['message'] = 'Sorry! Something went wrong! Could not add change the password.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	function reset_password($new_password, $confirm_new_password, $last_updated_by, $last_update_date, $whereClause)
	{
		$new_password = $this->test_input($new_password);
		$confirm_new_password = $this->test_input($confirm_new_password);

		$tableName 	= 'app_users';
		$tabFields 	= "*";


		$selQue 	= parent::selectData($tabFields, $tableName, $whereClause);
		$selectAccess 	= parent::execQuery($selQue);

		$info = mysql_fetch_assoc($selectAccess);


		if ($new_password == '' || $confirm_new_password == '') {
			//do nothing
		} elseif ($new_password != $confirm_new_password) {
			$msg = 'Sorry, Re-enter Password does not match with your New Password';
		} else {
			$new_password_enc = md5($confirm_new_password);
			$setValues 		= "PASSWORD = '$new_password_enc',last_updated_by = '$last_updated_by',last_update_date = '$last_update_date'";
			$whereClause	= "$whereClause";
			$changePassword 	= parent::updateData($tableName, $setValues, $whereClause);
			$execPassword 	= parent::execQuery($changePassword);

			if (!$execPassword) {
				echo 'update:fail' . mysql_error();
			} else {
				$msg = 'Your password has been successfully reset.';
			}
		}
		return $msg;
	}
	/* get admin details */
	public function get_user_details($user_id, $user_login_id, $role)
	{
		$res = '';
		switch ($role) {
			//admin
			case (1):
				$tablename 	= ' admin_details_master a LEFT JOIN user_login_master b ON a.ADMIN_ID=b.USER_ID ';
				$whereClause = " where a.ADMIN_ID ='" . $user_id . "' and b.USER_LOGIN_ID ='" . $user_login_id . "' and b.USER_ROLE='" . $role . "'";
				break;
			//institute
			case (2):
				$tablename = ' institute_details a LEFT JOIN user_login_master b ON a.INSTITUTE_ID=b.USER_ID ';
				$whereClause  = " where a.INSTITUTE_ID ='" . $user_id . "' and b.USER_LOGIN_ID ='" . $user_login_id . "' and b.USER_ROLE='" . $role . "'";
				break;
			//employer
			case (3):
				$tablename 	= ' employer_details a LEFT JOIN user_login_master b ON a.EMPLOYER_ID=b.USER_ID ';
				$whereClause  = " where a.EMPLOYER_ID ='" . $user_id . "' and b.USER_LOGIN_ID ='" . $user_login_id . "' and b.USER_ROLE='" . $role . "'";
				break;
			//Student
			case (4):
				$tablename 	  = ' student_details a LEFT JOIN user_login_master b ON a.STUDENT_ID=b.USER_ID ';
				$whereClause  = " where a.STUDENT_ID ='" . $user_id . "' and b.USER_LOGIN_ID ='" . $user_login_id . "' and b.USER_ROLE='" . $role . "'";
				break;
			//institute staff
			case (5):
				$tablename = 'institute_staff_details a LEFT JOIN user_login_master b ON a.STAFF_ID=b.USER_ID';
				$whereClause  = " where a.STAFF_ID ='" . $user_id . "' and b.USER_LOGIN_ID ='" . $user_login_id . "' and b.USER_ROLE='" . $role . "'";
				break;
			// admin staff
			case (6):
				$tablename 	 = ' admin_staff_details a LEFT JOIN user_login_master b ON a.STAFF_ID=b.USER_ID ';
				$whereClause  = " where a.STAFF_ID ='" . $user_id . "' and b.USER_LOGIN_ID ='" . $user_login_id . "' and b.USER_ROLE='" . $role . "'";
				break;
		}
		$tabFields 	= " *, DATE_FORMAT(a.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE, DATE_FORMAT(a.UPDATED_ON, '%d-%m-%Y %h:%i %p') AS UPDATED_DATE";
		$whereClause .= " LIMIT 0,1";
		$selQue 	= parent::selectData($tabFields, $tablename, $whereClause);
		$selectAccess 	= parent::execQuery($selQue);
		if ($selectAccess && $selectAccess->num_rows > 0)
			$res =  $selectAccess;
		return $res;
	}
	/* get admin details */
	public function get_user_info($user_id, $role)
	{
		$res = array();
		$tablename = '';
		$whereClause = 'WHERE 1 ';
		//$whereClause=' where a.DELETE_FLAG=0  ';
		switch ($role) {
			//Admin
			case (1):
				$tablename = ' admin_details_master a INNER JOIN user_login_master b ON a.ADMIN_ID=b.USER_ID ';
				$whereClause  .= " and a.ADMIN_ID ='" . $user_id . "' and b.USER_ROLE='" . $role . "'";
				break;
			//institute
			case (2):
				$tablename = ' institute_details a INNER JOIN user_login_master b ON a.INSTITUTE_ID=b.USER_ID ';
				$whereClause  .= " and  b.USER_ID ='$user_id' and b.USER_ROLE='$role'";
				break;
			//institute staff
			case (5):
				$tablename = ' institute_staff_details a INNER JOIN user_login_master b ON a.STAFF_ID=b.USER_ID ';
				$whereClause  .= " and a.STAFF_ID ='" . $user_id . "' and b.USER_ROLE='" . $role . "'";
				break;

			//employer
			case (3):
				$tablename 	= ' employer_details a INNER JOIN user_login_master b ON a.EMPLOYER_ID=b.USER_ID ';
				$whereClause  .= " and a.EMPLOYER_ID ='" . $user_id . "' AND b.USER_ROLE='" . $role . "'";
				break;
			//AMC
			case (7):
				$tablename 	= ' amc_details a INNER JOIN user_login_master b ON a.AMC_ID=b.USER_ID ';
				$whereClause  .= " and a.AMC_ID ='" . $user_id . "' AND b.USER_ROLE='" . $role . "'";
				break;
			//Student
			case (4):
				$tablename 	= ' student_details a INNER JOIN user_login_master b ON a.STUDENT_ID=b.USER_ID ';
				$whereClause  .= " and a.STUDENT_ID ='" . $user_id . "' AND b.USER_ROLE='" . $role . "'";
				break;

			//franchise
			case (8):
				$tablename = ' institute_details a INNER JOIN user_login_master b ON a.INSTITUTE_ID=b.USER_ID ';
				$whereClause  .= " and  b.USER_ID ='$user_id' and b.USER_ROLE='$role' and a.active=1";
				break;
		}
		$tabFields 	= " a.*, DATE_FORMAT(a.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE, DATE_FORMAT(a.UPDATED_ON, '%d-%m-%Y %h:%i %p') AS UPDATED_DATE";
		//	$whereClause .= "  and a.DELETE_FLAG=0 LIMIT 0,1";
		if ($tablename != '') {
			$selQue 	= parent::selectData($tabFields, $tablename, $whereClause);
			$selectAccess 	= parent::execQuery($selQue);
			if ($selectAccess && $selectAccess->num_rows > 0) {

				$data =  $selectAccess->fetch_assoc();
				//print_r($data);
				$res['MOBILE'] = parent::test($data['MOBILE']);
				$res['EMAIL'] 	= parent::test($data['EMAIL']);
				$res['NAME']	= "";
				if ($role == 3)
					$res['NAME'] = parent::test($data['EMPLOYER_COMPANY_NAME']);
				if ($role == 2)
					$res['NAME'] = parent::test($data['INSTITUTE_NAME']);

				if ($role == 7)
					$res['NAME'] = parent::test($data['AMC_COMPANY_NAME']);

				if ($role == 4) {
					$studentFullName = $data['STUDENT_FNAME'] . ' ' . $data['STUDENT_MNAME'] . ' ' . $data['STUDENT_LNAME'];
					$res['NAME'] = $studentFullName;
					$res['MOBILE'] = parent::test($data['STUDENT_MOBILE']);
					$res['EMAIL'] 	= parent::test($data['STUDENT_EMAIL']);
				}

				if ($role == 8) {
					$res['NAME'] = parent::test($data['INSTITUTE_NAME']);
					$res['MOBILE'] = parent::test($data['MOBILE']);
					$res['EMAIL'] 	= parent::test($data['EMAIL']);
				}
			}
		}
		return $res;
	}
	public function get_institute_code($user_id)
	{
		$tabFields = "INSTITUTE_CODE";
		$tablename = "institute_details";
		$whereClause = "WHERE INSTITUTE_ID = '$user_id'";

		$selQue = parent::selectData($tabFields, $tablename, $whereClause);
		$selectAccess = parent::execQuery($selQue);

		if ($selectAccess && $selectAccess->num_rows > 0) {
			$data = $selectAccess->fetch_assoc();
			return parent::test($data['INSTITUTE_CODE']);
		}

		return ''; // Return empty string if no data found
	}
	/* for creating image thumbnail */
	function create_thumb_img($target, $newcopy,  $ext, $w = 312, $h = 190)
	{

		list($w_orig, $h_orig) = getimagesize($target);
		$scale_ratio = $w_orig / $h_orig;
		if (($w / $h) > $scale_ratio) {
			$w = $h * $scale_ratio;
		} else {
			$h = $w / $scale_ratio;
		}
		$img = "";
		$ext = strtolower($ext);
		if ($ext == "gif") {
			$img = imagecreatefromgif($target);
		} else if ($ext == "png") {
			$img = imagecreatefrompng($target);
		} else {
			$img = imagecreatefromjpeg($target);
		}
		$tci = imagecreatetruecolor($w, $h);
		imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
		imagejpeg($tci, $newcopy, 80);
	}
	/* check email id exists 
	@param: email address
	@return: true or false 
	*/
	public function check_email($email)
	{
		$email = parent::test($email);
		$sql = "SELECT USER_EMAIL FROM admin_details_master WHERE USER_EMAIL='$email'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			return true;
		}
		return false;
	}

	/* check username exists 
	@param: strin username
	@return: true or false 
	*/
	public function check_username($username)
	{
		$email = parent::test($username);
		$sql = "SELECT USER_NAME FROM admin_login_master WHERE USER_NAME='$username'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			return true;
		}
		return false;
	}
	/* auditing function */
	// insert activity details into database
	public function add_activity($activity, $message)
	{
		$admin_login_id = isset($_SESSION['admin_login_id']) ? $_SESSION['admin_login_id'] : '';
		$sessionid 	= isset($_SESSION['sid']) ? $_SESSION['sid'] : '';
		$role_id	= isset($_SESSION['role_id']) ? $_SESSION['role_id'] : '';
		$role		= parent::get_user_role($role_id);
		$user_fame	= isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : '';
		$user_lame	= isset($_SESSION['user_lname']) ? $_SESSION['user_lname'] : '';
		$created_by	= $user_fame . ' ' . $user_lame;
		$ip_address	= $this->get_client_ip();
		$agent		= $this->get_user_agent();

		$tableName 		= "audit_master";
		$tabFields 		= "(AUDIT_ID,ACTIVITY,USER_LOGIN_ID,USER_TYPE,MESSAGE,SESSION_ID,IP_ADDRESS,AGENT,CREATED_BY,CREATED_ON)";
		$insertValues	= "(NULL, '$activity','$admin_login_id','$role','$message','$sessionid','$ip_address','$agent', '$created_by',NOW())";

		$insertSql	= parent::insertData($tableName, $tabFields, $insertValues);
		$exSql		= parent::execQuery($insertSql);
	}
	// Function to get the client IP address
	public function get_client_ip()
	{
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if (getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if (getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if (getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if (getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if (getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	public function get_user_agent()
	{
		return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	}
	public function utf_encode($txt)
	{
		return iconv(mb_detect_encoding($txt, mb_detect_order(), true), "UTF-8", $txt);
	}
	/*public function generate_password()
	{
		$length = 8;
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		return substr( str_shuffle( $chars ), 0, $length );
	}*/
	public function generate_password()
	{
		$length = 12;
		$chars = '0123456789';
		return substr(str_shuffle($chars), 0, $length);
	}
	public function change_pass($login_id, $email)
	{
		$data = false;
		$random_pass = $this->generate_password();
		$sql = "UPDATE user_login_master SET PASS_WORD=md5('$random_pass'), PASSWORD_CHANGE_DATE=NOW(),UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE USER_LOGIN_ID='$login_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			$subject = "Account Password changed!";
			$message = "Hi,<br>
						Your password was changed by DITRP administrator.<br>
						Your new password is <strong>$random_pass</strong> <br>
						<br>
						Thanks & Regards<br>
						<strong>DITRP</strong>
						";
			$resp = $this->send_email($email, '', '', '', $subject, $message);
			if ($resp)
				$data = true;
		}
		return $data;
	}
	/* send email */
	public function send_email($to, $name, $from = '', $from_name = '', $subject, $message)
	{
		//echo PHP_MAILER_PATH; exit();
		//	include_once(PHP_MAILER_PATH);

		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               

		$mail->isSMTP();
		$mail->Host = EMAIL_HOST;
		$mail->SMTPAuth = true;
		$mail->Username = EMAIL_USERNAME;
		$mail->Password = EMAIL_PASSWORD;
		$mail->SMTPSecure = 'tls';
		$mail->Port = EMAIL_PORT;

		$mail->setFrom($from, $from_name);
		$mail->addAddress($to, $name);
		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body    = html_entity_decode($message);
		//$mail->AltBody = 'Hello '.$name.',  '.$message;

		if (!$mail->send()) {
			return false;
		} else {
			return true;
		}
	}
	public function book_icon($ext)
	{
		$ico = '';
		switch ($ext) {
			case ('pdf'):
				$ico = 'fa-file-pdf-o';
				break;
			case ('pptx'):
			case ('ppt'):
				$ico = 'fa-file-powerpoint-o';
				break;

			case ('xlsx'):
			case ('xlsm'):
			case ('xlsb'):
			case ('xltm'):
			case ('xlam'):
			case ('xls'):
			case ('xla'):
			case ('xlc'):
			case ('csv'):
			case ('sql'):
				$ico = 'fa-file-excel-o';
				break;
			case ('doc'):
			case ('docx'):
			case ('dot'):
				$ico = 'fa-file-word-o';
				break;
			case ('jpg'):
			case ('jpeg'):
			case ('gif'):
			case ('png'):
			case ('bmp'):
				$ico = 'fa-file-image-o';
				break;
			case ('mp4'):
			case ('avi'):
			case ('mov'):
			case ('amv'):
			case ('mpeg'):
			case ('3gp'):
			case ('flv'):
			case ('mkv'):
				$ico = 'fa-file-video-o';
				break;
			case ('mp3'):
			case ('aac'):
			case ('wav'):
			case ('wma'):
			case ('webm'):
				$ico = 'fa-file-sound-o';
				break;
			case ('html'):
			case ('css'):
			case ('php'):
			case ('java'):
			case ('js'):
				$ico = 'fa-file-code-o';
				break;
			case ('zip'):
			case ('rar'):
			case ('tar'):
			case ('bz2'):
			case ('gz'):
			case ('7z'):
				$ico = 'fa-file-zip-o';
				break;
			case ('txt'):
			case ('rtf'):
				$ico = 'fa-file-text-o';
				break;
			default:
				$ico = 'fa-file-o';
				break;
		}
		return $ico;
	}
	//gentarte code for certificate 



	public function generate_marksheet_prefix($inst_id, $stud_id, $last_id)
	{
		$prefix = '';
		//	$sql ="SELECT CONCAT(CONCAT(get_institute_code ($inst_id),'',get_student_code($stud_id)),'', YEAR(CURDATE())) AS prefix  FROM certificates_details";
		$sql = "SELECT get_student_code($stud_id) AS prefix  FROM marksheet_details";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$prefix	= $data['prefix'];
		}
		return $prefix;
	}

	//download file
	function output_file($file, $name, $mime_type = '')
	{
		/*
		This function takes a path to a file to output ($file),  the filename that the browser will see ($name) and  the MIME type of the file ($mime_type, optional).
		*/

		//Check the file premission
		if (!is_readable($file)) die('File not found or inaccessible!');

		$size = filesize($file);
		$name = rawurldecode($name);

		/* Figure out the MIME type | Check in array */
		$known_mime_types = array(
			"pdf" => "application/pdf",
			"txt" => "text/plain",
			"html" => "text/html",
			"htm" => "text/html",
			"exe" => "application/octet-stream",
			"zip" => "application/zip",
			"doc" => "application/msword",
			"xls" => "application/vnd.ms-excel",
			"ppt" => "application/vnd.ms-powerpoint",
			"gif" => "image/gif",
			"png" => "image/png",
			"jpeg" => "image/jpg",
			"jpg" =>  "image/jpg",
			"php" => "text/plain"
		);

		if ($mime_type == '') {
			$file_extension = strtolower(substr(strrchr($file, "."), 1));
			if (array_key_exists($file_extension, $known_mime_types)) {
				$mime_type = $known_mime_types[$file_extension];
			} else {
				$mime_type = "application/force-download";
			};
		};

		//turn off output buffering to decrease cpu usage
		@ob_end_clean();

		// required for IE, otherwise Content-Disposition may be ignored
		if (ini_get('zlib.output_compression'))
			ini_set('zlib.output_compression', 'Off');

		header('Content-Type: ' . $mime_type);
		header('Content-Disposition: attachment; filename="' . $name . '"');
		header("Content-Transfer-Encoding: binary");
		header('Accept-Ranges: bytes');

		/* The three lines below basically make the 
		download non-cacheable */
		header("Cache-control: private");
		header('Pragma: private');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

		// multipart-download and download resuming support
		if (isset($_SERVER['HTTP_RANGE'])) {
			list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
			list($range) = explode(",", $range, 2);
			list($range, $range_end) = explode("-", $range);
			$range = intval($range);
			if (!$range_end) {
				$range_end = $size - 1;
			} else {
				$range_end = intval($range_end);
			}
			$new_length = $range_end - $range + 1;
			header("HTTP/1.1 206 Partial Content");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range-$range_end/$size");
		} else {
			$new_length = $size;
			header("Content-Length: " . $size);
		}

		/* Will output the file itself */
		$chunksize = 1 * (1024 * 1024); //you may want to change this
		$bytes_send = 0;
		if ($file = fopen($file, 'r')) {
			if (isset($_SERVER['HTTP_RANGE']))
				fseek($file, $range);

			while (
				!feof($file) &&
				(!connection_aborted()) &&
				($bytes_send < $new_length)
			) {
				$buffer = fread($file, $chunksize);
				print($buffer); //echo($buffer); // can also possible
				flush();
				$bytes_send += strlen($buffer);
			}
			fclose($file);
		} else
			//If no permissiion
			die('Error - can not open file.');
		//die
		die();
	}
	public function encrypt($pure_string, $encryption_key)
	{
		$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
		return $encrypted_string;
	}

	/**
	 * Returns decrypted original string
	 */
	public function decrypt($encrypted_string, $encryption_key)
	{
		$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
		return $decrypted_string;
	}
	public function generate_certificate_prefix($inst_id, $stud_id, $last_id)
	{
		$prefix = '';
		//	$sql ="SELECT CONCAT(CONCAT(get_institute_code ($inst_id),'',get_student_code($stud_id)),'', YEAR(CURDATE())) AS prefix  FROM certificates_details";
		$sql = "SELECT get_student_code($stud_id) AS prefix  FROM certificates_details";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$prefix	= $data['prefix'];
		}
		return $prefix;
	}
	public function list_printed_certificates($cert_detail_id = '', $cert_req_id = '', $cond = '')
	{
		$data = '';

		/*$sql ="SELECT *,STUDENT_PHOTO AS STUD_PHOTO, DATE_FORMAT(ISSUE_DATE,'%d.%m.%Y') AS ISSUE_DATE_FORMAT,get_institute_city(INSTITUTE_ID) AS INSTITUTE_CITY,get_stud_photo(STUDENT_ID) AS STUDENT_PHOTO,get_course_title_modify(COURSE_ID) AS AICPE_COURSE_AWARD,(SELECT UPPER(D.COURSE_DURATION) FROM courses D WHERE D.COURSE_ID=COURSE_ID) AS COURSE_DURATION  FROM certificates_details WHERE 1 ";
*/
		$sql = "SELECT *,DATE_FORMAT(STUDENT_DOB,'%d.%m.%Y') AS STUDENT_DOB_F,STUDENT_PHOTO AS STUD_PHOTO, DATE_FORMAT(ISSUE_DATE,'%d-%m-%Y') AS ISSUE_DATE_FORMAT, get_stud_photo(STUDENT_ID) AS STUDENT_PHOTO,get_stud_sign(STUDENT_ID) AS STUDENT_SIGN,get_institute_name(INSTITUTE_ID) as INSTITUTE_NAME, get_institute_owner_name(INSTITUTE_ID) as OWNER_NAME  FROM certificates_details WHERE 1 ";
		if ($cert_detail_id != '') {
			$sql .= " AND CERTIFICATE_DETAILS_ID='$cert_detail_id'";
		}
		if ($cert_req_id != '') {
			$sql .= " AND CERTIFICATE_REQUEST_ID='$cert_req_id'";
		}
		if ($cond != '') {
			$sql .= " $cond";
		}
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}
	//order certificate print student certificate
	public function list_order_printed_certificates($cert_detail_id = '', $cert_req_id = '', $cond = '')
	{
		$data = '';

		/*$sql ="SELECT *,STUDENT_PHOTO AS STUD_PHOTO, DATE_FORMAT(ISSUE_DATE,'%d.%m.%Y') AS ISSUE_DATE_FORMAT,get_institute_city(INSTITUTE_ID) AS INSTITUTE_CITY,get_stud_photo(STUDENT_ID) AS STUDENT_PHOTO,get_course_title_modify(COURSE_ID) AS AICPE_COURSE_AWARD,(SELECT UPPER(D.COURSE_DURATION) FROM courses D WHERE D.COURSE_ID=COURSE_ID) AS COURSE_DURATION  FROM certificates_details WHERE 1 ";
*/
		$sql = "SELECT *,DATE_FORMAT(STUDENT_DOB,'%d.%m.%Y') AS STUDENT_DOB_F,STUDENT_PHOTO AS STUD_PHOTO, DATE_FORMAT(ISSUE_DATE,'%d-%m-%Y') AS ISSUE_DATE_FORMAT,get_institute_owner_name(INSTITUTE_ID) AS OWNER_NAME,get_stud_photo(STUDENT_ID) AS STUDENT_PHOTO,get_stud_sign(STUDENT_ID) AS STUDENT_SIGN FROM certificates_order_details WHERE 1 ";
		if ($cert_detail_id != '') {
			$sql .= " AND CERTIFICATE_DETAILS_ID='$cert_detail_id'";
		}
		if ($cert_req_id != '') {
			$sql .= " AND CERTIFICATE_REQUEST_ID='$cert_req_id'";
		}
		if ($cond != '') {
			$sql .= " $cond";
		}
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}
	//forgot password
	public function forgot_pass($email, $role)
	{

		$result = false;
		$pass = "";
		$user = "";
		$message = "";
		$name = "";
		$errors = array();
		$data = array();
		$data['success'] = false;
		if ($email == '')
			$errors['email'] = 'Email is required.';
		if ($role == '')
			$errors['role'] = 'Role is required.';
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = "Please enter valid forget password form details. <a href='javascript:void(0)' class='btn btn-link' title='Forgot Password' data-toggle='modal' data-target='.forgot-pass'>Try again!</a>";
		} else {
			switch ($role) {
				//admin
				case (1):
					$tablename 	= ' admin_details_master a INNER JOIN user_login_master b ON a.ADMIN_ID=b.USER_ID ';
					$whereClause = " where a.USER_EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " CONCAT(a.FIRST_NAME,' ',a.LAST_NAME) AS NAME ";
					break;
				//institute
				case (2):
					$tablename = ' institute_details a INNER JOIN user_login_master b ON a.INSTITUTE_ID=b.USER_ID ';
					$whereClause  = " where a.EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " a.INSTITUTE_NAME AS NAME ";
					break;
				//employer
				case (3):
					$tablename 	= ' employer_details a INNER JOIN user_login_master b ON a.EMPLOYER_ID=b.USER_ID ';
					$whereClause  = " where a.EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " a.EMPLOYER_NAME AS NAME ";
					break;
				//Student
				case (4):
					$tablename 	  = ' student_details a INNER JOIN user_login_master b ON a.STUDENT_ID=b.USER_ID ';
					$whereClause  = " where a.STUDENT_EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " CONCAT(a.STUDENT_FNAME,' ',a.STUDENT_LNAME) AS NAME ";
					break;
				//institute staff
				case (5):
					$tablename = 'institute_staff_details a INNER JOIN user_login_master b ON a.STAFF_ID=b.USER_ID';
					$whereClause  = " where a.STAFF_EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " a.STAFF_FULLNAME AS NAME ";
					break;
				// admin staff
				case (6):
					$tablename 	 = ' admin_staff_details a INNER JOIN user_login_master b ON a.STAFF_ID=b.USER_ID ';
					$whereClause  = " where a.STAFF_EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " a.STAFF_FULLNAME AS NAME ";
					break;
				// AMC
				case (7):
					$tablename 	 = ' amc_details a INNER JOIN user_login_master b ON a.AMC_ID=b.USER_ID ';
					$whereClause  = " where a.EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " a.AMC_NAME AS NAME ";
					break;
			}
			$tabFields 	= "b.USER_NAME,b.USER_LOGIN_ID ";
			if ($name != '') $tabFields .= ", $name";
			$whereClause .= " LIMIT 0,1";
			$selQue 	= parent::selectData($tabFields, $tablename, $whereClause);
			$selectAccess 	= parent::execQuery($selQue);
			if ($selectAccess && $selectAccess->num_rows > 0) {
				$data =  $selectAccess->fetch_assoc();
				$user = $data['USER_NAME'];
				$name = $data['NAME'];
				$user_login_id = $data['USER_LOGIN_ID'];
				//if password present then show
				if ($pass != '') {
					$message = "Hi $name, <br>
						This is your password <strong>$pass</strong><br><br>
						<a href='" . HTTP_HOST_ADMIN . "' target='_blank'>Click to Login</a><br><br>
						Thanks
						";
					$data['message']  = 'Success! Your password has been sent to your email address. Please check your email account. Thanks!';
				} else {
					//reset the password
					$rand_pass = $this->getRandomCode(8);
					$pass = $rand_pass;
					$sql = "UPDATE user_login_master SET PASS_WORD=MD5('$rand_pass'),PASSWORD_CHANGE_DATE=NOW() WHERE USER_LOGIN_ID='$user_login_id'";
					$res = parent::execQuery($sql);
					if ($res && parent::rows_affected() > 0) {
						$message = "Hi $name, <br>
						This is your new password <strong>$rand_pass</strong><br><br>
						<a href='" . HTTP_HOST_ADMIN . "' target='_blank'>Click to Login</a><br><br>
						Thanks.
						";
						$data['message']  = 'Success! Your password has been reset. Your new pasword has been sent to your email address. Please check your email account. Thanks!';
					}
				}
				if ($message != '') {
					//send email
					require_once("include/email/config.php");
					//echo $email; exit();
					require_once("include/email/templates/forgot_password.php");
					//	$check = $this->send_email($email,'',EMAIL_USERNAME, 'DITRP', 'Forgot Password Request', $message);
					//if($check)
					//{
					$data['success'] = true;
					$result = true;
					//}
				}
			} else {
				$data['success'] = false;
				$data['message']  = 'Sorry! No user found with this email address.';
			}
		}
		return json_encode($data);
	}
	//pay online
	public function pay_online()
	{
		$errors = array();
		$data 	= array();
		$data['success'] = false;

		$key 		= parent::test(isset($_POST['key']) ? $_POST['key'] : '');
		$hash 		= parent::test(isset($_POST['hash']) ? $_POST['hash'] : '');
		$txnid 		= parent::test(isset($_POST['txnid']) ? $_POST['txnid'] : '');
		$amount 	= parent::test(isset($_POST['amount']) ? $_POST['amount'] : '');
		$surl 		= parent::test(isset($_POST['surl']) ? $_POST['surl'] : '');
		$furl 		= parent::test(isset($_POST['furl']) ? $_POST['furl'] : '');
		$service_provider = parent::test(isset($_POST['service_provider']) ? $_POST['service_provider'] : '');
		$hashSequence = "key|txnid|amount";

		if ($key == '' || $txnid == '')
			$errors['message'] = 'Something went wrong!';
		if ($amount == '')
			$errors['amount'] = 'Amount is required.';
		if ($amount != '' && !$this->valid_decimal($amount))
			$errors['amount'] = 'Please enter valid amount. Should be positive integer only.';

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = "Please fill all mandatory fields.";
			$hashVarsSeq = explode('|', $hashSequence);

			$hash_string = '';
			foreach ($hashVarsSeq as $hash_var) {
				$hash_string .= isset($_POST[$hash_var]) ? $_POST[$hash_var] : '';
				$hash_string .= '|';
			}
			$hash_string .= SALT;
			$data['hash'] = strtolower(hash('sha512', $hash_string));
			$data['action'] = PAYU_BASE_URL . '/_payment';
		} else {
			$data['hash'] 		= $hash;
			$data['action'] 	= PAYU_BASE_URL . '/_payment';
			$data['success'] 	= true;
		}
		return json_encode($data);
	}
	public function get_wallet($wallet_id = '', $user_id = '', $user_role = '', $cond = '')
	{
		$result = '';
		$sql = "SELECT *,DATE_FORMAT(UPDATED_ON, '%d-%m-%Y %h:%i %p') AS LAST_ADDED_ON, DATE_FORMAT(CREATED_ON, '%d-%m-%Y %h:%i %p') AS LAST_CREATED_ON FROM wallet WHERE DELETE_FLAG=0 ";
		if ($wallet_id != '')
			$sql .= " AND WALLET_ID='$wallet_id' ";
		if ($user_id != '')
			$sql .= " AND USER_ID='$user_id' ";
		if ($user_role != '')
			$sql .= " AND USER_ROLE='$user_role' ";
		if ($cond != '')
			$sql .= $cond;
		$sql .= " ORDER BY WALLET_ID DESC";
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res;
		}
		return $result;
	}
	public function get_online_payment_details($payment_id = '', $user_id, $user_role)
	{
		$result = '';
		$sql = "SELECT *, DATE_FORMAT(PAYMENT_DATE, '%d-%m-%Y %h:%i %p') AS PAYMENT_DATE_FORMATED FROM online_payments WHERE USER_ID='$user_id' AND USER_ROLE='$user_role' AND DELETE_FLAG=0 ORDER BY CREATED_ON DESC";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$result = $res;
		return $result;
	}
	public function get_recharge_history($wallet_id = '', $user_id = '', $user_role = '', $cond = '')
	{
		$output = array();
		//online payment data
		$sql = "SELECT *, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m %p') AS CREATED_DATE FROM online_payments A WHERE A.DELETE_FLAG=0 ";
		if ($wallet_id != '')
			$sql .= " AND A.WALLET_ID='$wallet_id'";
		if ($user_id != '')
			$sql .= " AND A.USER_ID='$user_id'";
		if ($user_role != '')
			$sql .= " AND A.USER_ROLE='$user_role'";
		if ($cond != '')
			$sql .= " $cond";

		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = array();
			while ($data = $res->fetch_assoc()) {
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
				$result['CREATED_DATE'] 		= $data['CREATED_DATE'];
				$result['PAYMENT_MODE'] 	= 'ONLINE';
				array_push($output, $result);
			}
			//$output[$result['TRANSACTION_NO']] = $result;
			//$output['TRANSACTION_NO'] = $result;

		}
		//offline payment data
		$sql = "SELECT *, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m %p') AS CREATED_DATE FROM offline_payments A WHERE A.DELETE_FLAG=0 ";
		if ($wallet_id != '')
			$sql .= " AND A.WALLET_ID='$wallet_id'";
		if ($user_id != '')
			$sql .= " AND A.USER_ID='$user_id'";
		if ($user_role != '')
			$sql .= " AND A.USER_ROLE='$user_role'";
		if ($cond != '')
			$sql .= " $cond";
		//echo $sql;
		$res2 = parent::execQuery($sql);
		if ($res2 && $res2->num_rows > 0) {
			$result2 = array();
			while ($data = $res2->fetch_assoc()) {
				$result2['PAYMENT_ID'] 		= $data['PAYMENT_ID'];
				$result2['TRANSACTION_NO'] 	= $data['TRANSACTION_NO'];
				$result2['TRANSACTION_TYPE'] = $data['TRANSACTION_TYPE'];
				$result2['USER_ID'] 		= $data['USER_ID'];
				$result2['USER_ROLE'] 		= $data['USER_ROLE'];
				$result2['USER_FULLNAME'] 	= $data['USER_FULLNAME'];
				$result2['USER_EMAIL'] 		= $data['USER_EMAIL'];
				$result2['USER_MOBILE'] 	= $data['USER_MOBILE'];
				$result2['AMOUNT'] 			= $data['PAYMENT_AMOUNT'];
				$result2['STATUS'] 			= $data['PAYMENT_REMARK'];
				$result2['CREATED_ON'] 		= $data['CREATED_ON'];
				$result2['CREATED_DATE'] 	= $data['CREATED_DATE'];
				$result2['PAYMENT_MODE'] 	= 'OFFLINE';
				array_push($output, $result2);
			}
			//$output[$result2['TRANSACTION_NO']] = $result2;

		}
		return $output;
	}
	public function recharge_wallet_offline()
	{
		$errors 	= array();
		$data 	= array();
		$trans_type 	= parent::test(isset($_POST['trans_type']) ? $_POST['trans_type'] : '');
		$amount 		= parent::test(isset($_POST['amount']) ? $_POST['amount'] : '');
		$user_id 		= parent::test(isset($_POST['user_id']) ? $_POST['user_id'] : '');
		$user_role 	= parent::test(isset($_POST['user_role']) ? $_POST['user_role'] : '');

		$pay_mode 	= parent::test(isset($_POST['pay_mode']) ? $_POST['pay_mode'] : '');
		$pay_remark 	= parent::test(isset($_POST['pay_remark']) ? $_POST['pay_remark'] : '');
		$cheque_no 	= parent::test(isset($_POST['cheque_no']) ? $_POST['cheque_no'] : '');
		$cheque_date 	= parent::test(isset($_POST['cheque_date']) ? $_POST['cheque_date'] : '');
		$cheque_bank 	= parent::test(isset($_POST['cheque_bank']) ? $_POST['cheque_bank'] : '');

		$bonus_type 	= parent::test(isset($_POST['bonus_type']) ? $_POST['bonus_type'] : '');

		$recharge_by 	= parent::test(isset($_POST['recharge_by']) ? $_POST['recharge_by'] : '');
		$lead_by 		= parent::test(isset($_POST['lead_by']) ? $_POST['lead_by'] : '');

		$password  = parent::test(isset($_POST['password']) ? $_POST['password'] : '');

		if ($cheque_date != '') $cheque_date = date('Y-m-d', strtotime($cheque_date));
		$admin_id 	= $_SESSION['user_id'];
		$role 		= 1; //institute staff;
		$created_by  	= $_SESSION['user_fullname'];
		$created_by_ip = $_SESSION['ip_address'];
		/* check validations */
		if ($password == '')
			$errors['password'] = 'Master Password is required.';

		if ($amount == '')
			$errors['amount'] = 'Recharge amount is required.';
		if (!$this->valid_decimal($amount))
			$errors['amount'] = 'Invalid Recharge amount!.';
		if ($user_id == '')
			$errors['user_id'] = 'Select account holder is required.';
		if ($user_role == '')
			$errors['user_role'] = 'Select account role!';
		if ($trans_type == 'DEBIT') {
			$checkwallet = $this->get_wallet('', $user_id, $user_role);
			if ($checkwallet != '' && $checkwallet->num_rows > 0) {
				$data1 = $checkwallet->fetch_assoc();
				$TOTAL_BALANCE = $data1['TOTAL_BALANCE'];
				if ($TOTAL_BALANCE < $amount)
					$errors['trans_type'] = "Balance amount is low! Can't debit the <strong>$amount</strong> amount!";
			} else {
				$errors['trans_type'] = 'Sorry! Can not debit amount! Wallet is empty!';
			}
		}

		$wallet_password = parent::get_wallet_password();

		if ($password != $wallet_password) {
			$errors['password'] = 'Password Not Matched.';
		}

		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$user_info 	= $this->get_user_info($user_id, $user_role);
			$NAME 		= $user_info['NAME'];
			$MOBILE 	= $user_info['MOBILE'];
			$EMAIL 		= $user_info['EMAIL'];
			//insert into offline payment 
			$tableName1 	= "offline_payments";
			$tabFields1 	= "(PAYMENT_ID, TRANSACTION_NO,TRANSACTION_TYPE,USER_ID,USER_ROLE,USER_FULLNAME,USER_EMAIL,USER_MOBILE,PAYMENT_AMOUNT,PAYMENT_MODE,PAYMENT_DATE,PAYMENT_STATUS,CHEQUE_BANK,CHEQUE_NO,CHEQUE_DATE,PAYMENT_REMARK,BONUS_STAUS,ACTIVE,CREATED_BY, CREATED_ON,CREATED_BY_IP,RECHARG_BY,LEAD_BY)";
			$insertVals1	= "(NULL, get_payment_transaction_id_admin(), '$trans_type','$user_id','$user_role', '$NAME','$EMAIL','$MOBILE','$amount','$pay_mode',NOW(), '', '$cheque_bank', '$cheque_no', STR_TO_DATE('$cheque_date','%d-%m-%Y'), '$pay_remark','$bonus_type','1','$created_by',NOW(),'$created_by_ip','$recharge_by','$lead_by')";
			$insertSql1	= parent::insertData($tableName1, $tabFields1, $insertVals1);
			$exSql1		= parent::execQuery($insertSql1);

			if ($exSql1) {
				$last_id = parent::last_id();
				$checkwallet = $this->get_wallet('', $user_id, $user_role);
				if ($checkwallet != '' && $checkwallet->num_rows > 0) {
					if ($trans_type == 'CREDIT') $trans_type = "+";
					if ($trans_type == 'DEBIT') $trans_type = "-";
					$data1 = $checkwallet->fetch_assoc();
					$WALLET_ID = $data1['WALLET_ID'];
					$tableName = "wallet";
					$setValues = "TOTAL_BALANCE = TOTAL_BALANCE $trans_type $amount, UPDATED_BY='$created_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip'";
					$whereClause = "WHERE WALLET_ID='$WALLET_ID'";
					$updSql = parent::updateData($tableName, $setValues, $whereClause);
					$exSql 	= parent::execQuery($updSql);
				} else {
					$tableName 	= "wallet";
					$tabFields 	= "(WALLET_ID, USER_ID,USER_ROLE,TOTAL_BALANCE, CREATED_BY, CREATED_ON,CREATED_ON_IP)";
					$insertVals	= "(NULL, '$user_id', '$user_role', '$amount','$created_by',NOW(),'$created_by_ip')";
					$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
					$exSql		= parent::execQuery($insertSql);
					$WALLET_ID = parent::last_id();
				}
				//add wallet id 
				if ($exSql) {
					$sql = "UPDATE offline_payments SET WALLET_ID='$WALLET_ID' WHERE PAYMENT_ID='$last_id'";
					$res = parent::execQuery($sql);
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Wallet was recharged successfully!';
				}
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not recharge the wallet.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function check_institute_verified($inst_id)
	{
		$verify  = false;
		$sql = "SELECT VERIFIED FROM institute_details WHERE INSTITUTE_ID='$inst_id' LIMIT 0,1";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$check = $data['VERIFIED'];
			if ($check == 1)
				$verify = true;
		}
		return $verify;
	}
	public function check_employer_verified($emp_id)
	{
		$verify  = false;
		$sql = "SELECT VERIFIED FROM employer_details WHERE EMPLOYER_ID='$emp_id' LIMIT 0,1";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$check = $data['VERIFIED'];
			if ($check == 1)
				$verify = true;
		}
		return $verify;
	}
	/*public function trigger_sms($message,$mobiles)
	{
		//Prepare you post parameters
		$postData = array(
			'user' => SMS_USERNAME,
			'password' => SMS_PASSWORD,
			'sid' => SMS_SENDER,
			'msisdn' => $mobile,
			'msg' => $message,
                        'fl'=>'0',
                        'gwid'=>'2'
		);

		//API URL
		$url= SMS_SEND_URL;

		// init the resource
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postData
			,CURLOPT_FOLLOWLOCATION => true
		));


		//Ignore SSL certificate verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


		//get response
		$output = curl_exec($ch);

		//Print error if any
		if(curl_errno($ch))
		{
			echo 'error:' . curl_error($ch);
		}

		curl_close($ch);

		//echo $output;
	}*/
	public function trigger_sms($message, $mobiles)
	{

		// $postData = array(
		// 	'type' => 'text',
		// 	'number' => $mobiles,
		// 	'message'=>$message,
		// 	'instance_id' => INSTANCE_ID,
		// 	'access_token'=>'e614902fe12202189548ac89c72dd6d8'
		// );

		//API URL
		//$url= SMS_SEND_URL;
		//https://ditrpindia.in/api/send.php?number=84933313xxx&type=text&message=test%20message&instance_id=609ACF283XXXX&access_token=e614902fe12202189548ac89c72dd6d8

		$mobile_number = $mobiles;
		$from = "SENDER";
		$message = urlencode("Hello");
		$url = 'https://ditrpindia.in/api/send.php?number=91' . $mobiles . '&type=text&message=' . $message . '&instance_id=' . INSTANCE_ID . '&access_token=e614902fe12202189548ac89c72dd6d8';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, false);
		$output = curl_exec($ch);
		echo $output;
		exit();
		curl_close($ch);

		// // init the resource
		// $ch = curl_init();
		// curl_setopt_array($ch, array(
		// 	CURLOPT_URL => $url,
		// 	CURLOPT_RETURNTRANSFER => true,
		// 	CURLOPT_POST => true,
		// 	CURLOPT_POSTFIELDS => $postData
		// 	,CURLOPT_FOLLOWLOCATION => true
		// ));

		// //Ignore SSL certificate verification
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		// print_r($ch);exit();
		// //get response
		// $output = curl_exec($ch);
		// print_r($output); 
		// //Print error if any
		// if(curl_errno($ch))
		// {
		// 	echo 'error:' . curl_error($ch);
		// }

		// curl_close($ch);
		// echo $output;	 

	}

	public function getCertPrintAvailablity($course, $stud, $inst)
	{
		$output = '';
		$sql = "SELECT AVAILABLE_FOR_STUD FROM certificate_requests WHERE STUDENT_ID='$stud' AND COURSE_ID='$course' AND INSTITUTE_ID='$inst' AND REQUEST_STATUS=2";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$output = $result['AVAILABLE_FOR_STUD'];
		}
		return $output;
	}

	public function getCertPrintAvailablityMulti($course, $stud, $inst)
	{
		$output = '';
		$sql = "SELECT AVAILABLE_FOR_STUD FROM certificate_requests WHERE STUDENT_ID='$stud' AND MULTI_SUB_COURSE_ID='$course' AND INSTITUTE_ID='$inst' AND REQUEST_STATUS=2";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$output = $result['AVAILABLE_FOR_STUD'];
		}
		return $output;
	}

	public function getCertPrintAvailablityTyping($course, $stud, $inst)
	{
		$output = '';
		$sql = "SELECT AVAILABLE_FOR_STUD FROM certificate_requests WHERE STUDENT_ID='$stud' AND TYPING_COURSE_ID='$course' AND INSTITUTE_ID='$inst' AND REQUEST_STATUS=2";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$output = $result['AVAILABLE_FOR_STUD'];
		}
		return $output;
	}

	/*........................print_marksheet_amdm*/

	public function list_printed_marksheet($cert_detail_id = '', $cert_req_id = '', $cond = '')
	{
		$data = '';
		//	$sql ="SELECT * FROM certificates_details WHERE 1 ";
		$sql = "SELECT *,STUDENT_PHOTO AS STUD_PHOTO, DATE_FORMAT(ISSUE_DATE,'%d.%m.%Y') AS ISSUE_DATE_FORMAT,(SELECT F.CITY_NAME as city_name FROM city_master F WHERE INSTITUTE_ID=F.CITY_ID) AS INSTITUTE_CITY,get_stud_photo(STUDENT_ID) AS STUDENT_PHOTO,get_course_title_modify(COURSE_ID) AS AICPE_COURSE_AWARD,(SELECT UPPER(D.COURSE_DURATION) FROM courses D WHERE D.COURSE_ID=COURSE_ID) AS COURSE_DURATION  FROM certificates_details WHERE 1 ";
		if ($cert_detail_id != '') {
			$sql .= " AND CERTIFICATE_DETAILS_ID='$cert_detail_id'";
		}
		if ($cert_req_id != '') {
			$sql .= " AND CERTIFICATE_REQUEST_ID='$cert_req_id'";
			//$sql.="AND CERTIFICATE_REQUEST_ID='$cert_req_id";
		}
		if ($cond != '') {
			$sql .= " $cond";
		}
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}
	//list of student for hall ticket
	public function list_student_details_hallticket($student_course_details_id = '', $cond = '')
	{
		$data = '';
		//	$sql ="SELECT * FROM certificates_details WHERE 1 ";
		$sql = "SELECT * FROM student_course_details WHERE 1 ";
		if ($student_course_details_id != '') {
			$sql .= " AND STUD_COURSE_DETAIL_ID='$student_course_details_id'";
		}
		if ($cond != '') {
			$sql .= " $cond";
		}
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}

	public function get_payment_bill_details($id = '', $cond = '')
	{
		$data = '';

		$sql = "SELECT A.*,DATE_FORMAT(A.CREATED_ON,'%d.%m.%Y') AS CREATED_DATE,B.INSTITUTE_CODE,B.INSTITUTE_NAME,B.INSTITUTE_OWNER_NAME,B.MOBILE,B.ADDRESS_LINE1,B.TALUKA,B.CITY,B.STATE,B.POSTCODE,B.GSTNO,(SELECT CITY_NAME FROM city_master WHERE CITY_ID=B.CITY) AS CITY_NAME,(SELECT STATE_NAME FROM states_master WHERE STATE_ID=B.STATE) AS STATE_NAME FROM online_payments A LEFT JOIN  institute_details B ON A.USER_ID = B.INSTITUTE_ID WHERE 1 ";
		if ($id != '') {
			$sql .= " AND A.PAYMENT_ID='$id'";
		}
		if ($cond != '') {
			$sql .= " $cond";
		}
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}


	//Institute Multi Couser Subject Details And Position
	public function get_multisub_inst_subject_details($STUDENT_SUBJECT_ID, $MULTI_SUB_COURSE_ID, $INSTITUTE_ID)
	{
		$key = '';
		$sql = "SELECT SUBJECT_DETAILS FROM  institute_course_subjects WHERE INSTITUTE_ID='$INSTITUTE_ID' AND MULTI_SUB_COURSE_ID='$MULTI_SUB_COURSE_ID' AND COURSE_SUBJECT_ID='$STUDENT_SUBJECT_ID' ";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['SUBJECT_DETAILS'];
		}
		return $key;
	}

	public function get_multisub_inst_subject_position($STUDENT_SUBJECT_ID, $MULTI_SUB_COURSE_ID, $INSTITUTE_ID)
	{
		$key = '';
		$sql = "SELECT POSITION FROM  institute_course_subjects WHERE INSTITUTE_ID='$INSTITUTE_ID' AND MULTI_SUB_COURSE_ID='$MULTI_SUB_COURSE_ID' AND COURSE_SUBJECT_ID='$STUDENT_SUBJECT_ID' ";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['POSITION'];
		}
		return $key;
	}

	//Courier Wallet list	
	public function get_courier_wallet($wallet_id = '', $user_id = '', $user_role = '', $cond = '')
	{
		$result = '';
		$sql = "SELECT *,DATE_FORMAT(UPDATED_ON, '%d-%m-%Y %h:%i %p') AS LAST_ADDED_ON, DATE_FORMAT(CREATED_ON, '%d-%m-%Y %h:%i %p') AS LAST_CREATED_ON FROM courier_wallet WHERE DELETE_FLAG=0 ";
		//error_log(print_r("$sql", true));	
		if ($wallet_id != '')
			$sql .= " AND WALLET_ID='$wallet_id' ";
		if ($user_id != '')
			$sql .= " AND USER_ID='$user_id' ";
		if ($user_role != '')
			$sql .= " AND USER_ROLE='$user_role' ";
		if ($cond != '')
			$sql .= $cond;
		$sql .= " ORDER BY WALLET_ID ASC";
		//echo $sql; exit();	
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res;
		}
		return $result;
	}
	public function recharge_courier_wallet_offline()
	{
		$errors 	= array();
		$data 	= array();
		$trans_type 	= parent::test(isset($_POST['trans_type']) ? $_POST['trans_type'] : '');
		$amount 		= parent::test(isset($_POST['amount']) ? $_POST['amount'] : '');
		$user_id 		= parent::test(isset($_POST['user_id']) ? $_POST['user_id'] : '');
		$user_role 	= parent::test(isset($_POST['user_role']) ? $_POST['user_role'] : '');

		$pay_mode 	= parent::test(isset($_POST['pay_mode']) ? $_POST['pay_mode'] : '');
		$pay_remark 	= parent::test(isset($_POST['pay_remark']) ? $_POST['pay_remark'] : '');
		$cheque_no 	= parent::test(isset($_POST['cheque_no']) ? $_POST['cheque_no'] : '');
		$cheque_date 	= parent::test(isset($_POST['cheque_date']) ? $_POST['cheque_date'] : '');
		$cheque_bank 	= parent::test(isset($_POST['cheque_bank']) ? $_POST['cheque_bank'] : '');

		$bonus_type 	= parent::test(isset($_POST['bonus_type']) ? $_POST['bonus_type'] : '');

		$recharge_by 	= parent::test(isset($_POST['recharge_by']) ? $_POST['recharge_by'] : '');
		$lead_by 	= parent::test(isset($_POST['lead_by']) ? $_POST['lead_by'] : '');
		$password 	= parent::test(isset($_POST['password']) ? $_POST['password'] : '');


		if ($cheque_date != '') $cheque_date = date('Y-m-d', strtotime($cheque_date));
		$admin_id 	= $_SESSION['user_id'];
		$role 		= 1; //institute staff;	
		$created_by  	= $_SESSION['user_fullname'];
		$created_by_ip = $_SESSION['ip_address'];
		/* check validations */

		if ($password == '')
			$errors['password'] = 'Master Password is required.';

		if ($amount == '')
			$errors['amount'] = 'Recharge amount is required.';
		if (!$this->valid_decimal($amount))
			$errors['amount'] = 'Invalid Recharge amount!.';
		if ($user_id == '')
			$errors['user_id'] = 'Select account holder is required.';
		if ($user_role == '')
			$errors['user_role'] = 'Select account role!';
		if ($trans_type == 'DEBIT') {
			$checkwallet = $this->get_courier_wallet('', $user_id, $user_role);
			if ($checkwallet != '' && $checkwallet->num_rows > 0) {
				$data1 = $checkwallet->fetch_assoc();
				$TOTAL_BALANCE = $data1['TOTAL_BALANCE'];
				if ($TOTAL_BALANCE < $amount)
					$errors['trans_type'] = "Balance amount is low! Can't debit the <strong>$amount</strong> amount!";
			} else {
				$errors['trans_type'] = 'Sorry! Can not debit amount! Wallet is empty!';
			}
		}

		$courier_password = parent::get_courier_wallet_password();

		if ($password != $courier_password) {
			$errors['password'] = 'Password Not Matched.';
		}

		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$user_info 	= $this->get_user_info($user_id, $user_role);
			$NAME 		= $user_info['NAME'];
			$MOBILE 	= $user_info['MOBILE'];
			$EMAIL 		= $user_info['EMAIL'];
			//insert into offline payment 	
			$tableName1 	= "offline_payments";
			$tabFields1 	= "(PAYMENT_ID, TRANSACTION_NO,TRANSACTION_TYPE,USER_ID,USER_ROLE,USER_FULLNAME,USER_EMAIL,USER_MOBILE,PAYMENT_AMOUNT,PAYMENT_MODE,PAYMENT_DATE,PAYMENT_STATUS,CHEQUE_BANK,CHEQUE_NO,CHEQUE_DATE,PAYMENT_REMARK,BONUS_STAUS,ACTIVE,CREATED_BY, CREATED_ON,CREATED_BY_IP, COURIER_WALLET_PAYMENT,RECHARG_BY,LEAD_BY)";
			$insertVals1	= "(NULL, get_payment_transaction_id_admin(), '$trans_type','$user_id','$user_role', '$NAME','$EMAIL','$MOBILE','$amount','$pay_mode',NOW(), '', '$cheque_bank', '$cheque_no', STR_TO_DATE('$cheque_date','%d-%m-%Y'), '$pay_remark','$bonus_type','1','$created_by',NOW(),'$created_by_ip','1','$recharge_by','$lead_by')";
			$insertSql1	= parent::insertData($tableName1, $tabFields1, $insertVals1);
			$exSql1		= parent::execQuery($insertSql1);

			if ($exSql1) {
				$last_id = parent::last_id();
				$checkwallet = $this->get_courier_wallet('', $user_id, $user_role);
				if ($checkwallet != '' && $checkwallet->num_rows > 0) {
					if ($trans_type == 'CREDIT') $trans_type = "+";
					if ($trans_type == 'DEBIT') $trans_type = "-";
					$data1 = $checkwallet->fetch_assoc();
					$WALLET_ID = $data1['WALLET_ID'];
					$tableName = "courier_wallet";
					$setValues = "TOTAL_BALANCE = TOTAL_BALANCE $trans_type $amount, UPDATED_BY='$created_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip'";
					$whereClause = "WHERE WALLET_ID='$WALLET_ID'";
					$updSql = parent::updateData($tableName, $setValues, $whereClause);
					$exSql 	= parent::execQuery($updSql);
					//echo "<script>alert('ASDF $amount')</script>";	
				} else {
					$tableName 	= "courier_wallet";
					$tabFields 	= "(WALLET_ID, USER_ID,USER_ROLE,TOTAL_BALANCE, CREATED_BY, CREATED_ON,CREATED_ON_IP)";
					$insertVals	= "(NULL, '$user_id', '$user_role', '$amount','$created_by',NOW(),'$created_by_ip')";
					$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
					$exSql		= parent::execQuery($insertSql);
					$WALLET_ID = parent::last_id();
				}

				//Main Wallet Deduction/Addition Reverse action	
				/*if($exSql)	
					{	
					    	
					    if($trans_type=='CREDIT') $trans_type = "-";	
						if($trans_type=='DEBIT') $trans_type = "+";	
							
						$tableName3     = "wallet";	
						$setValues3     = "TOTAL_BALANCE = TOTAL_BALANCE $trans_type $amount, UPDATED_BY='$created_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip'";	
						$whereClause3   = "WHERE USER_ID='$user_id'";	
						$updSql3        = parent::updateData($tableName3,$setValues3,$whereClause3);	
						$exSql3 	    = parent::execQuery($updSql);	
						//error_log(print_r("$tableName3,$setValues3,$whereClause3", true));	
					}*/

				//add wallet id 	
				if ($exSql) {
					$sql = "UPDATE offline_payments SET COURIER_WALLET_ID='$WALLET_ID' WHERE PAYMENT_ID='$last_id'";
					$res = parent::execQuery($sql);
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Courier Wallet was recharged successfully!';
				}
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not recharge the wallet.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function generate_exam_sessioncode()
	{
		$length = 19;
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		return substr(str_shuffle($chars), 0, $length);
	}
}
