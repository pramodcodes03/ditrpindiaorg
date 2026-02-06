<?php
class access extends database_results
{
	function check($username, $password, $flag = NULL)
	{
		$info = NULL;
		$username = parent::test($username);
		$password = parent::test($password);
		$password_enc = md5($password);

		$tablename 	= ' user_login_master a ';
		$tabFields 	= " a.USER_LOGIN_ID,a.USER_ID, a.USER_NAME, a.USER_ROLE ";
		$whereClause   = " where a.USER_NAME ='" . $username . "' and a.PASS_WORD ='" . $password_enc . "' AND a.ACTIVE=1 AND a.DELETE_FLAG=0";

		$selQue 	= parent::selectData($tabFields, $tablename, $whereClause);
		$selectAccess 	= parent::execQuery($selQue);
		$info = $selectAccess->fetch_assoc();

		return $info;
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
	function create_session($info = NULL)
	{
		//start session		
		$user_login_id 	= $_SESSION['user_login_id'] = isset($info['USER_LOGIN_ID']) ? $info['USER_LOGIN_ID'] : '';
		$user_id 		= $_SESSION['user_id'] 		= isset($info['USER_ID']) ? $info['USER_ID'] : '';
		$user_name 		= $_SESSION['user_name'] 	= isset($info['USER_NAME']) ? $info['USER_NAME'] : '';
		$user_role 		= $_SESSION['user_role'] 	= isset($info['USER_ROLE']) ? $info['USER_ROLE'] : '';
		$session_id 	= $_SESSION['sid']			= session_id();
		$login_time  	= $_SESSION['login_time'] 	= time();
		$ip_address  	= $_SESSION['ip_address'] 	= $this->get_client_ip();
		$res 			= $this->set_login_time($user_login_id);
		$user_fullname 	= $_SESSION['user_fullname'] = $this->get_curr_username($user_id, $user_role);
		$user_photo 	= $_SESSION['user_photo'] 	= $this->get_curr_userphoto($user_id, $user_role);

		return true;
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
				$tabFields 	= " a.INSTITUTE_OWNER_NAME AS name ";
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
				$tablename 	= ' employer_details a ';
				$tabFields 	= " a.EMPLOYER_NAME AS name ";
				$whereClause = " where a.EMPLOYER_ID ='" . $user_id . "'";
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
				$whereClause = " where a.EMPLOYER_ID ='" . $user_id . "' AND a.FILE_LABEL='logo' AND a.DELETE_FLAG=0 LIMIT 0,1";
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
					$photo = HTTP_HOST . '/' . ADMIN_PHOTO_PATH . '/' . $user_id . '/thumb/' . $name;
					break;
					//institute
				case (2):
					$photo = HTTP_HOST . '/' . INSTITUTE_DOCUMENTS_PATH . '/' . $user_id . '/thumb/' . $name;
					break;
					//emplyer
				case (3):
					$photo = HTTP_HOST . '/' . EMPLOYER_PHOTO_PATH . '/' . $user_id . '/thumb/' . $name;
					break;
					//student
				case (4):
					$photo = HTTP_HOST . '/' . STUDENT_DOCUMENTS_PATH . '/' . $user_id . '/thumb/' . $name;
					break;
					// Institue staff
				case (5):
					$photo = HTTP_HOST . '/' . INSTITUTE_STAFF_PHOTO_PATH . '/' . $user_id . '/thumb/' . $name;
					break;
					// Admin staff
				case (6):
					$photo = HTTP_HOST . '/' . ADMIN_STAFF_PHOTO_PATH . '/' . $user_id . '/thumb/' . $name;
					break;
				default:
					$photo = HTTP_HOST . '/' . DEFAULT_USER_LOGO;
					break;
			}
		}
		if ($name == '')
			$photo = DEFAULT_USER_LOGO;
		return $photo;
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
					$tablename 	= ' admin_details_master a LEFT JOIN user_login_master b ON a.ADMIN_ID=b.USER_ID ';
					$whereClause = " where a.USER_EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " CONCAT(a.FIRST_NAME,' ',a.LAST_NAME) AS NAME ";
					break;
					//institute
				case (2):
					$tablename = ' institute_details a LEFT JOIN user_login_master b ON a.INSTITUTE_ID=b.USER_ID ';
					$whereClause  = " where a.EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " a.INSTITUTE_NAME AS NAME ";
					break;
					//employer
				case (3):
					$tablename 	= ' employer_details a LEFT JOIN user_login_master b ON a.EMPLOYER_ID=b.USER_ID ';
					$whereClause  = " where a.EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " a.EMPLOYER_NAME AS NAME ";
					break;
					//Student
				case (4):
					$tablename 	  = ' student_details a LEFT JOIN user_login_master b ON a.STUDENT_ID=b.USER_ID ';
					$whereClause  = " where a.STUDENT_EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " CONCAT(a.STUDENT_FNAME,' ',a.STUDENT_LNAME) AS NAME ";
					break;
					//institute staff
				case (5):
					$tablename = 'institute_staff_details a LEFT JOIN user_login_master b ON a.STAFF_ID=b.USER_ID';
					$whereClause  = " where a.STAFF_EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " a.STAFF_FULLNAME AS NAME ";
					break;
					// admin staff
				case (6):
					$tablename 	 = ' admin_staff_details a LEFT JOIN user_login_master b ON a.STAFF_ID=b.USER_ID ';
					$whereClause  = " where a.STAFF_EMAIL ='" . $email . "' and b.USER_ROLE='" . $role . "'";
					$name = " a.STAFF_FULLNAME AS NAME ";
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
						<a href='https://" . HTTP_HOST . "/login' target='_blank'>Click to Login</a><br><br>
						Thanks
						";
					$data['message']  = 'Success! Your password has been sent to your email address. Please check your email account. Thanks!';
					//send email
					require_once("phpmailer/PHPMailerAutoload.php");
					require_once("email/config.php");
					require_once("email/templates/forget_password.php");
				} else {
					//reset the password
					$rand_pass = $this->getRandomCode(8);

					$sql = "UPDATE user_login_master SET PASS_WORD=MD5('$rand_pass'),PASSWORD_CHANGE_DATE=NOW() WHERE USER_LOGIN_ID='$user_login_id'";
					$res = parent::execQuery($sql);
					if ($res && parent::rows_affected() > 0) {
						$message = "Hi $name, <br>
						This is your new password <strong>$rand_pass</strong><br><br>
						<a href='https://" . HTTP_HOST . "/login' target='_blank'>Click to Login</a><br><br>
						Thanks.";
						$data['message']  = 'Success! Your password has been reset. Your new pasword has been sent to your email address. Please check your email account. Thanks!';
						//send email
						require_once("phpmailer/PHPMailerAutoload.php");
						require_once("email/config.php");
						require_once("email/templates/reset_password.php");
					}
				}
				if ($message != '') {
					//$check = $this->send_email($email,'',EMAIL_USERNAME, 'AICPE', 'Forgot Password Request', $message);
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
	function change_password($current_password, $new_password, $confirm_new_password, $last_updated_by, $last_update_date, $whereClause)
	{
		$current_password = $this->test_input($current_password);
		$new_password = $this->test_input($new_password);
		$confirm_new_password = $this->test_input($confirm_new_password);

		$password_enc = md5($current_password);
		$tableName 	= 'app_users';
		$tabFields 	= "*";
		$whereClause   = "where USER_ID =" . $_SESSION['user_id'] . " AND PASSWORD='$password_enc'";

		$selQue 	= parent::selectData($tabFields, $tableName, $whereClause);
		$selectAccess 	= parent::execQuery($selQue);

		$info = mysql_fetch_assoc($selectAccess);

		if (!isset($info['USER_ID'])) {
			$msg = 'Sorry, your current password does not match';
		} else {
			if ($new_password == '' || $confirm_new_password == '') {
				$msg = 'You have missed to enter one of the field';
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
		}

		return $msg;
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
	public function readmore($content, $limit)
	{
		$string = '';
		$string = strip_tags($content, '<p>');
		if (strlen($string) > $limit) {

			// truncate string
			$stringCut = substr($string, 0, $limit);
			// make sure it ends in a word so assassinate doesn't become ass...
			$string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
		}
		return $string;
	}
	function test_input($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		$data = $this->mysqli->real_escape_string($data);
		return $data;
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
	function create_thumb_img($target, $newcopy,  $ext, $w = 300, $h = 280)
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
	public function valid_name($empname)
	{
		$resp = '';
		if ($empname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $empname)) {
				$resp = "Only letters and white space allowed";
			}
		}
		return $resp;
	}
	public function valid_email($email)
	{
		$resp = '';
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$resp = "Invalid email format";
		}
		return $resp;
	}
	public function valid_mobile($mobile)
	{
		$resp = '';
		if (strlen($mobile) != 10) {
			$resp = 'Mobile number should have 10 digits.';
		}
		$first_no = $mobile[0]; //substr($mobile,1);
		$arr = array('0', '7', '8', '9', '2', '6', '5', '1', '4', '3');
		if (!in_array($first_no, $arr)) {
			$resp = 'Only numbers are allowed. This is not a valid mobile number.';
		}
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
	public function valid_alphanumeric($value)
	{
		$regex = '/^[a-zA-Z0-9 ]*$/';
		return preg_match($regex, $value);
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
	public function validate_employer_code($code, $emp_id = '')
	{
		$sql = "SELECT EMPLOYER_CODE FROM employer_details WHERE EMPLOYER_CODE='$code'";
		if ($emp_id != '')
			$sql .= " AND EMPLOYER_ID!='$emp_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			return false;
		}
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
	public function generate_institute_code()
	{
		$code = '';
		$code = $this->getRandomCode2();
		$sql = "SELECT INSTITUTE_CODE FROM institute_details WHERE INSTITUTE_CODE='$code'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_institute_code();
		}
		return $code;
	}
	/* generate institute code */
	public function generate_employer_code()
	{
		$code = '';
		$code = $this->getRandomCode(6);
		$sql = "SELECT EMPLOYER_CODE FROM employer_details WHERE EMPLOYER_CODE='$code'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_employer_code();
		}
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
	/*	public function generate_password()
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
	public function contact()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name_contact 		= parent::test(isset($_POST['name_contact']) ? $_POST['name_contact'] : '');
		$lastname_contact 		= parent::test(isset($_POST['lastname_contact']) ? $_POST['lastname_contact'] : '');
		$email_contact 		= parent::test(isset($_POST['email_contact']) ? $_POST['email_contact'] : '');
		$phone_contact 		= parent::test(isset($_POST['phone_contact']) ? $_POST['phone_contact'] : '');
		$message_contact 		= parent::test(isset($_POST['message_contact']) ? $_POST['message_contact'] : '');
		$verify_contact 		= parent::test(isset($_POST['verify_contact']) ? $_POST['verify_contact'] : '');

		$created_by  		= $name_contact;;
		$created_by_ip  	= $this->get_client_ip();

		/* check validations */
		//new validations
		if (!filter_var($email_contact, FILTER_VALIDATE_EMAIL)) {
			$errors['email_contact'] = "Invalid email format";
		}
		if ($name_contact != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $name_contact)) {
				$errors['name_contact'] = "Only letters and white space allowed";
			}
		}
		/*
			if($lastname_contact!='')
			{
				if (!preg_match("/^[a-zA-Z ]*$/",$lastname_contact)) {
				$errors['lastname_contact'] = "Only letters and white space allowed";}
			}	*/
		if ($phone_contact != '') {
			if (strlen($phone_contact) != 10) {
				$errors['phone_contact'] = 'Only 10 Digits allowed.';
			}
			$first_no = $phone_contact[0];
			$arr = array('9', '8', '7');
			if (!in_array($first_no, $arr)) {
				$errors['phone_contact'] = 'Only letters and white space allowed. Mobile number should start with 9 or 8 or 7 only.';
			}
		}

		//new validations
		if ($name_contact == '')
			$errors['name_contact'] = 'First name is required.';
		//if ($lastname_contact=='')
		//$errors['lastname_contact'] = 'Last name is required.';
		if ($email_contact == '')
			$errors['email_contact'] = 'Email is required.';
		if ($message_contact == '')
			$errors['message_contact'] = 'Message is required.';
		if ($phone_contact == '')
			$errors['phone_contact'] = 'Mobile is required.';


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//if($dob!='') 
			//$dob = @date('Y-m-d', strtotime($dob));
			parent::start_transaction();
			$tableName 	= "contact_enquiry";
			$tabFields 	= "(CONTACT_ID, FNAME, LNAME, EMAIL,MOBILE,MESSAGE,CREATED_BY, CREATED_ON, CREATED_ON_IP)";
			$insertVals	= "(NULL, UPPER('$name_contact'), UPPER('$lastname_contact'), '$email_contact','$phone_contact','$message_contact','$created_by',NOW(),'$created_by_ip')";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Your enquiry submitted successfully!';
				//send email
				require_once("phpmailer/PHPMailerAutoload.php");
				require_once("email/config.php");
				require_once("email/templates/contact.php");
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not submit your enquiry.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function course_enquiry()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$course_id 			= parent::test(isset($_POST['course_id']) ? $_POST['course_id'] : '');
		$course_name 			= parent::test(isset($_POST['course_name']) ? $_POST['course_name'] : '');
		$name_enquiry 		= parent::test(isset($_POST['name_enquiry']) ? $_POST['name_enquiry'] : '');
		$lastname_enquiry 	= parent::test(isset($_POST['lastname_enquiry']) ? $_POST['lastname_enquiry'] : '');
		$email_enquiry 		= parent::test(isset($_POST['email_enquiry']) ? $_POST['email_enquiry'] : '');
		$phone_enquiry 		= parent::test(isset($_POST['phone_enquiry']) ? $_POST['phone_enquiry'] : '');
		$message_enquiry 		= parent::test(isset($_POST['message_enquiry']) ? $_POST['message_enquiry'] : '');
		$verify_enquiry 		= parent::test(isset($_POST['verify_enquiry']) ? $_POST['verify_enquiry'] : '');
		$pincode_enquiry 		= parent::test(isset($_POST['pincode_enquiry']) ? $_POST['pincode_enquiry'] : '');
		$city_enquiry 		= parent::test(isset($_POST['city_enquiry']) ? $_POST['city_enquiry'] : '');

		$created_by  		= $name_enquiry;;
		$created_by_ip  	= $this->get_client_ip();

		/* check validations */


		//new validations
		if (!filter_var($email_enquiry, FILTER_VALIDATE_EMAIL)) {
			$errors['email_enquiry'] = "Invalid email format";
		}
		if ($name_enquiry != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $name_enquiry)) {
				$errors['name_enquiry'] = "Only letters and white space allowed";
			}
		}
		if ($lastname_enquiry != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $lastname_enquiry)) {
				$errors['lastname_enquiry'] = "Only letters and white space allowed";
			}
		}
		if ($phone_enquiry != '') {
			if (strlen($phone_enquiry) != 10) {
				$errors['phone_enquiry'] = 'Only 10 Digits allowed.';
			}
			$first_no = $phone_enquiry[0];
			$arr = array('9', '8', '7');
			if (!in_array($first_no, $arr)) {
				$errors['phone_enquiry'] = 'Only letters and white space allowed. Mobile number should start with 9 or 8 or 7 only.';
			}
		}
		//if($pincode_enquiry=='')
		//  $errors['pincode_enquiry'] = 'postal code is required.';
		if ($pincode_enquiry != '') {
			if (strlen($pincode_enquiry) != 6) {
				$errors['pincode_enquiry'] = 'Postal code must contain maximum 6 Digits.';
			}
			if ($pincode_enquiry < 0) {
				$errors['pincode_enquiry'] = 'Please enter valid postal code.';
			}
		}
		//capcha validation
		if ($verify_enquiry != $_SESSION["code"]) {
			$errors['verify_enquiry'] = "Captcha code is wrong! Please enter correct code.";
		}

		//new validations
		if ($verify_enquiry == '')
			$errors['verify_enquiry'] = 'Please enter code.';
		if ($name_enquiry == '')
			$errors['name_enquiry'] = 'First name is required.';
		if ($lastname_enquiry == '')
			$errors['lastname_enquiry'] = 'Last name is required.';
		if ($email_enquiry == '')
			$errors['email_enquiry'] = 'Email is required.';
		if ($message_enquiry == '')
			$errors['message_enquiry'] = 'Message is required.';
		if ($phone_enquiry == '')
			$errors['phone_enquiry'] = 'Mobile is required.';


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//if($dob!='') 
			//$dob = @date('Y-m-d', strtotime($dob));
			parent::start_transaction();
			$tableName 	= "website_course_enquiry";
			$tabFields 	= "(ENQUIRY_ID,COURSE_ID, FNAME, LNAME, EMAIL,MOBILE,CITY,PINCODE,MESSAGE,CREATED_BY, CREATED_ON, CREATED_ON_IP)";
			$insertVals	= "(NULL,'$course_id', UPPER('$name_enquiry'), UPPER('$lastname_enquiry'), '$email_enquiry','$phone_enquiry','$city_enquiry','$pincode_enquiry','$message_enquiry','$created_by',NOW(),'$created_by_ip')";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Your enquiry submitted successfully!';
				//send email
				require_once($_SERVER['DOCUMENT_ROOT'] . "/phpmailer/PHPMailerAutoload.php");
				require_once($_SERVER['DOCUMENT_ROOT'] . "/email/config.php");
				require_once($_SERVER['DOCUMENT_ROOT'] . "/email/templates/course_enquiry.php");
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not submit your enquiry.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	/*	
public function trigger_sms($message,$mobiles)
	{
		//Prepare you post parameters
		$postData = array(
			'user' => SMS_USERNAME,
			'password' => SMS_PASSWORD,
			'sid' => SMS_SENDER,
			'msisdn' => $mobiles,
			'msg' => urlencode($message),			
			'fl' => '0',
			'gwid' => '2'
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
			//,CURLOPT_FOLLOWLOCATION => true
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

	//	echo $output;
	}*/
	/*public function trigger_sms($message,$mobiles)
	{
		//Prepare you post parameters
	/*	$postData = array(
			'authkey' => SMS_AUTH_KEY,
			'mobiles' => $mobiles,
			'message' => urlencode($message),
			'sender' => SMS_SENDER,
			'route' => SMS_ROUTE,
                        'country' => 0
		);

		//API URL
		$url= SMS_SEND_URL;
		$param = 'user=nextfair&key=3c59cf1ca0XX&mobile='.$mobiles.'&message= '.urlencode($message).'&senderid=DITRPi&accusage=1';

		// init the resource
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $param
			//,CURLOPT_FOLLOWLOCATION => true
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

		echo $output;
	}*/

	public function trigger_sms($message, $mobiles)
	{
		/*   $postData = array(
			'user' => 'ditrpi',
			'password' => '123456',
			'msisdn' => $mobiles,
			'sid' => 'DITRPi',
			'msg' => $message,
			'fl' => 0,
			'gwid'=>2
		);*/

		/*	 $postData = array(
        			'user' => 'NextStepComputers',
        			'password' => 'NextStepComputers765',
        			'msisdn' => $mobiles,
        			'sid' => 'DITRPI',
        			'msg' => $message,
        			'fl' => 0,
        			'gwid'=>2
        		);*/

		$postData = array(
			'username' => 'ditrpi',
			'password' => 'Ditrpi22',
			'type' => 'TEXT',
			'sender' => 'DITRPI',
			'mobile' => $mobiles,
			'message' => $message,
			'fl' => 0,
			'gwid' => 2
		);

		//API URL
		$url = SMS_SEND_URL;
		$ch = curl_init();

		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postData,
			CURLOPT_FOLLOWLOCATION => true
		));


		//Ignore SSL certificate verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


		//get response
		$output = curl_exec($ch);
		//print_r($output); exit();
		//Print error if any
		if (curl_errno($ch)) {
			echo 'error:' . curl_error($ch);
		}

		curl_close($ch);

		$output;
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

	//valid refferal code
	public function valid_refferal_code($code)
	{
		$sql = "SELECT STUDENT_CODE  FROM  student_details WHERE STUDENT_CODE ='$code' AND ACTIVE = '1' AND DELETE_FLAG = '0' ";

		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}
}
