<?php
include_once('database_results.class.php');
include_once('access.class.php');
include_once('s3.php');
include_once('s3Class.php');

class amc extends access
{
	public function deassign_Amc($assign_id = '', $INSTITUTE_ID)
	{
		//	$sql = "UPDATE amc_assign SET ACTIVE=0,DELETE_FLAG=1 WHERE AMC_ID='$assign_id' AND INSTITUTE_ID='$INSTITUTE_ID'";
		$sql = " DELETE FROM `amc_assign` WHERE `AMC_ID`='$assign_id' AND `INSTITUTE_ID`='$INSTITUTE_ID'";
		echo $sql;
		$res = parent::execQuery($sql);
		return true;
	}
	public function get_toal_aassign()
	{
		$data = 0;
		$sql = "SELECT COUNT(DISTINCT AMC_ID) as TOTAL FROM amc_assign";

		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}
	public function get_toal_amc()
	{
		$data = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM amc_details WHERE DELETE_FLAG=0 ";

		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}
	public function save_payment($amc_id = '')
	{
		//print_r($_POST);exit();
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$payment_id 	    = parent::test(isset($_POST['payment_id']) ? $_POST['payment_id'] : '');
		$institute_id 	= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : '');
		$amc_id 		    = parent::test(isset($_POST['amc_id']) ? $_POST['amc_id'] : '');
		$payment_mode 	= parent::test(isset($_POST['payment_mode']) ? $_POST['payment_mode'] : '');
		$amount 		    = parent::test(isset($_POST['amount']) ? $_POST['amount'] : '');

		$pay_mode 		= parent::test(isset($_POST['pay_mode']) ? $_POST['pay_mode'] : '');
		$pay_remark 		= parent::test(isset($_POST['pay_remark']) ? $_POST['pay_remark'] : '');
		$cheque_no 		= parent::test(isset($_POST['cheque_no']) ? $_POST['cheque_no'] : '');
		$cheque_date 		= parent::test(isset($_POST['cheque_date']) ? $_POST['cheque_date'] : '');
		$cheque_bank 		= parent::test(isset($_POST['cheque_bank']) ? $_POST['cheque_bank'] : '');
		//  $ 		= parent::test(isset($_POST['cheque_bank'])?$_POST['cheque_bank']:'');

		$created_by  		= $_SESSION['user_fullname'];



		//  parent::start_transaction();

		$tableName 	= "amc_payment";
		$tabFields 	= "(AMC_PAY_ID,PAYMENT_ID,INSTITUTE_ID, AMC_ID,MODE,AMC_COMISSION,PAYMENT_MODE,PAYMENT_REMARK,CHEQUE_NO,CHEQUE_DATE,CHEQUE_BANK,CURRENT_STATUS, ACTIVE,CREATED_BY, CREATED_ON)";
		$insertVals	= "(NULL,'$payment_id','$institute_id','$amc_id','$payment_mode','$amount','$pay_mode','$pay_remark','$cheque_no','$cheque_date','$cheque_bank','1','1','$created_by',NOW())";


		$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
		$exSql		= parent::execQuery($insertSql);

		if ($payment_mode == "OFFLINE") {


			$tableName 	= "offline_payments";
			$setValues 	= " AMC_PAYMENT_STATUS='1', UPDATED_BY='$created_by', UPDATED_ON=NOW(), UPDATED_BY_IP='" . $_SESSION['ip_address'] . "'";
			$whereClause = " WHERE PAYMENT_ID='$payment_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql1		= parent::execQuery($updateSql);
		}

		if ($payment_mode == "ONLINE") {
			$tableName 	= " online_payments";
			$setValues 	= " AMC_PAYMENT_STATUS='1', UPDATED_BY='$created_by', UPDATED_ON=NOW(), UPDATED_BY_IP='" . $_SESSION['ip_address'] . "'";
			$whereClause = " WHERE PAYMENT_ID='$payment_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql2		= parent::execQuery($updateSql);
		}
		parent::commit();
		$data['success'] = true;
		$data['message'] = 'Success! AMC Payment Has Been Successfully Made!';

		return json_encode($data);
	}

	public function list_unassigned_institutes($amc_id, $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*,DATE_FORMAT(A.VERIFIED_ON, '%d-%m-%Y') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.VERIFIED_ON, '%d-%m-%Y') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.DOB, '%d-%m-%Y') AS DOB_FORMATTED, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%d-%m-%Y ') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%i %p') AS UPDATED_DATE, B.USER_NAME, B.USER_LOGIN_ID, (SELECT CITY_NAME FROM city_master WHERE CITY_ID=A.CITY) AS CITY_NAME,(SELECT STATE_NAME FROM states_master WHERE STATE_ID=A.STATE) AS STATE_NAME FROM institute_details A LEFT JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID WHERE A.DELETE_FLAG=0 AND B.USER_ROLE=2 AND A.INSTITUTE_ID IN(SELECT DISTINCT INSTITUTE_ID FROM amc_assign WHERE AMC_ID=$amc_id AND ACTIVE=1 AND DELETE_FLAG=0) ORDER BY A.CREATED_ON DESC";


		if ($condition != '') {
			$sql .= " $condition ";
		}
		//	$sql .= ' ORDER BY CREATED_ON DESC';
		//	echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function list_assigned_institutes($amc_id, $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*,DATE_FORMAT(A.VERIFIED_ON, '%d-%m-%Y') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.VERIFIED_ON, '%d-%m-%Y') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.DOB, '%d-%m-%Y') AS DOB_FORMATTED, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%d-%m-%Y ') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%i %p') AS UPDATED_DATE, B.USER_NAME, B.USER_LOGIN_ID, (SELECT CITY_NAME FROM city_master WHERE CITY_ID=A.CITY) AS CITY_NAME,(SELECT STATE_NAME FROM states_master WHERE STATE_ID=A.STATE) AS STATE_NAME FROM institute_details A LEFT JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID WHERE A.DELETE_FLAG=0 AND B.USER_ROLE=2 AND A.INSTITUTE_ID NOT IN(SELECT DISTINCT INSTITUTE_ID FROM amc_assign WHERE AMC_ID=$amc_id AND ACTIVE=1) AND A.INSTITUTE_ID NOT IN(SELECT DISTINCT INSTITUTE_ID FROM amc_assign WHERE ACTIVE=1 AND DELETE_FLAG=0 )  ORDER BY A.CREATED_ON DESC";


		if ($condition != '') {
			$sql .= " $condition ";
		}
		//	$sql .= ' ORDER BY CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function list_institute($institute_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*, DATE_FORMAT(A.VERIFIED_ON, '%d-%m-%Y') AS VERIFIED_ON_FORMATTED, DATE_FORMAT(A.VERIFIED_ON, '%d-%m-%Y') AS VERIFIED_ON_FORMATTED, DATE_FORMAT(A.DOB, '%d-%m-%Y') AS DOB_FORMATTED, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS REG_DATE, DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%d-%m-%Y ') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE, DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%i %p') AS UPDATED_DATE, B.USER_NAME, B.USER_LOGIN_ID,P.CURRENT_STATUS as PAY_STATUS, (
			SELECT CITY_NAME
			FROM city_master
			WHERE CITY_ID=A.CITY) AS CITY_NAME,(
			SELECT STATE_NAME
			FROM states_master
			WHERE STATE_ID=A.STATE) AS STATE_NAME,O.PAYMENT_AMOUNT,O.PAYMENT_DATE,O.PAYMENT_STATUS,O.PAYMENT_AMOUNT*0.15 as COMISSION
			FROM institute_details A
			LEFT JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID
			LEFT JOIN online_payments O ON A.INSTITUTE_ID=O.USER_ID
			LEFT JOIN offline_payments F ON A.INSTITUTE_ID=F.USER_ID
			LEFT JOIN amc_payment P ON A.INSTITUTE_ID=P.INSTITUTE_ID
			LEFT JOIN amc_assign D ON A.INSTITUTE_ID=D.INSTITUTE_ID AND B.USER_ROLE=2
			WHERE A.DELETE_FLAG=0 AND O.PAYMENT_STATUS='SUCCESS'";

		if ($institute_id != '') {
			$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		//	$sql .= ' ORDER BY CREATED_ON DESC';
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function get_recharge_history_($user_id = '', $user_role = '', $cond = '')
	{

		//online payment data
		$sql = "SELECT *, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m %p') AS CREATED_DATE FROM online_payments A INNER JOIN amc_assign B
ON A.USER_ID=B.INSTITUTE_ID  where A.DELETE_FLAG=0 ";

		if ($user_id != '')
			$sql .= " AND A.USER_ID='$user_id'";
		if ($user_role != '')
			$sql .= " AND A.USER_ROLE='$user_role'";
		if ($cond != '')
			//	echo $sql .= " $cond";exit();
			$res = parent::execQuery($sql);

		$data = $res;
		return $data;
	}
	//offline 

	public function assign_inst()
	{
		//print_r($_POST);exit();
		$errors = array();  // array to hold validation errors
		$data = array();       // array to pass back data

		$amc_id 				= parent::test(isset($_POST['amc_id']) ? $_POST['amc_id'] : '');
		$institute_id 		= isset($_POST['institute_id']) ? $_POST['institute_id'] : array();
		// print_r($institute_id);exit();  
		$created_by  	= $_SESSION['user_name'];

		if (!empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = "Please correct all the errors.";
		} else {
			parent::start_transaction();
			$tableName 	= "amc_assign";
			$tabFields 	= "(ASSIGN_ID,AMC_ID,INSTITUTE_ID,ACTIVE,DELETE_FLAG,CREATED_BY, CREATED_ON)";
			$insertVals = '';
			foreach ($institute_id as $inst_id) {
				$insertVals	.= "(NULL,'$amc_id','$inst_id','1','0','$created_by',NOW()),";
			}
			$insertVals	= rtrim($insertVals, ",");
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);

			$exSql		= parent::execQuery($insertSql);

			foreach ($institute_id as $inst_id) {
				$update_sql = "update institute_details SET ASSIGN_FLAGF='1' WHERE INSTITUTE_ID=$inst_id";
				$exSql1		= parent::execQuery($update_sql);
			}

			if ($exSql1) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Institute Assign successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not Assign Institute';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}


	public function get_institute_docs_single($institute_id = '', $file_label = '')
	{
		$img = '';
		$data = array();
		$target = '';

		$sql = "SELECT * FROM institute_files WHERE 1";
		if ($institute_id != '')
			$sql .= " AND INSTITUTE_ID='$institute_id'";
		if ($file_label != '')
			$sql .= " AND FILE_LABEL='$file_label'";
		$sql .= ' ORDER BY FILE_ID ';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$rec = $res->fetch_assoc();
			$FILE_ID = $rec['FILE_ID'];
			$FILE_NAME = $rec['FILE_NAME'];
			$INSTITUTE_ID = $rec['INSTITUTE_ID'];
			if ($FILE_NAME != '') {
				$filePath = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/thumb/' . $FILE_NAME;
				$fileLink = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/' . $FILE_NAME;
				$img .=  '<img src="' . $filePath . '" class="img img-responsive" style="height:50px; width:50px" />';
			} else {
				$img .=  '<img src="../uploads/default_user.png" class="img img-responsive" style="height:50px; width:50px" />';
			}
		}

		return $img;
	}

	public function delete_Amc($amc_id = '')
	{
		$sql = "UPDATE amc_details SET ACTIVE=0,DELETE_FLAG=1 WHERE AMC_ID='$amc_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			$sql = "UPDATE user_login_master SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE USER_ID='$amc_id' AND USER_ROLE=7";
			$res = parent::execQuery($sql);
			return true;
		}
	}

	public function generate_AMC_code()
	{
		$code = '';
		$code = $this->getRandomCode(6);
		$sql = "SELECT AMC_CODE FROM amc_details WHERE AMC_CODE='$code'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_employer_code();
		}
		return $code;
	}


	public function add_amc()
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$empcode 		= $this->generate_AMC_code();
		$empcmpname 		= parent::test(isset($_POST['empcmpname']) ? $_POST['empcmpname'] : '');
		$empname 		= parent::test(isset($_POST['empname']) ? $_POST['empname'] : '');
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
		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$verify 			= parent::test(isset($_POST['verify']) ? $_POST['verify'] : '');
		$bankname 			= parent::test(isset($_POST['bankname']) ? $_POST['bankname'] : '');
		$accountnumber 			= parent::test(isset($_POST['accountnumber']) ? $_POST['accountnumber'] : '');
		$ifsc 			= parent::test(isset($_POST['ifsc']) ? $_POST['ifsc'] : '');
		$accountholdername 			= parent::test(isset($_POST['accountholdername']) ? $_POST['accountholdername'] : '');

		$uname 			= $email;
		$pword 			= parent::generate_password();
		$confpword 		= $pword;
		$expirationdate 	= parent::acc_expiry_date('');
		$registrationdate = parent::curr_date('');

		//  $admin_id 		= $_SESSION['user_id'];
		$role 			= 7; //AMC;
		$created_by  		= $empname; //$_SESSION['user_fullname'];
		$created_by_ip  	= parent::get_client_ip();
		/* check validations */
		if ($empcmpname == '')
			$errors['empcmpname'] = 'Employer Company name is required.';
		/*if($empcmpname!='')
			{
				if (!preg_match("/^[a-zA-Z ]*$/",$empcmpname)) {
				$errors['empcmpname'] = "Only letters and white space allowed";}
			}
			*/
		if ($empname == '')
			$errors['empname'] = 'AMC name is required.';
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
			$arr = array('9', '8', '7');
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
		if ($uname == '')
			$errors['uname'] = 'Username is required.';
		if ($pword == '')
			$errors['pword'] = 'Password is required.';
		if ($confpword == '')
			$errors['confpword'] = 'Confirm Password is required.';
		if ($pword != $confpword)
			$errors['confpword'] = 'Confirm password doesnt match!.';


		if (!parent::valid_username($uname))
			$errors['uname'] = 'Sorry! Username is already used.';
		if (!parent::valid_amc_email($email, ''))
			$errors['email'] = 'Sorry! Email is already used.';
		if (!$this->validate_amc_code($empcode, ''))
			$errors['empcode'] = 'Sorry! Employer code is already present.';

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "amc_details";
			$tabFields 	= "(AMC_ID, AMC_CODE, AMC_COMPANY_NAME,AMC_NAME,DESIGNATION,ADDRESS_LINE1,ADDRESS_LINE2,MOBILE,EMAIL,CITY,STATE,COUNTRY,POSTCODE,DETAIL_DESCRIPTION, BANK_NAME,ACCOUNT_NO,IFSC_CODE,ACCOUNT_HOLDER_NAME,CREDIT,DEMO_PER,ACTIVE,VERIFIED, CREATED_BY, CREATED_ON,CREATED_ON_IP)";
			$insertVals	= "(NULL, '$empcode', UPPER('$empcmpname'), UPPER('$empname'),'$designation','$address1','$address2','$mobile','$email','$city','$state','$country','$postcode','$empdetails','$bankname','$accountnumber','$ifsc','$accountholdername','$creditcount','$democount','$status','$verify','$created_by',NOW(), ' $created_by_ip ')";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				/* -----Get the last insert ID ----- */
				$last_insert_id = parent::last_id();
				$tableName2 	= "user_login_master";
				$tabFields2 	= "(USER_LOGIN_ID, USER_ID, USER_NAME, PASS_WORD,USER_ROLE, ACCOUNT_REGISTERED_ON,ACCOUNT_EXPIRED_ON,ACTIVE, CREATED_BY,CREATED_ON,CREATED_ON_IP)";
				$insertVals2	= "(NULL,'$last_insert_id', '$email', MD5('$confpword'),'$role','$registrationdate','$expirationdate','$status','$created_by',NOW(), 'created_by_ip')";
				$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
				$exSql2			= parent::execQuery($insertSql2);

				if ($exSql2) {
					require_once("include/email/config.php");
					require_once("include/email/templates/amc_registration_email.php");

					//send sms
					$message = "Congratulations!!!\r\nYour application for AMC Authorisation is Submitted.\r\nPlease check your email.\r\nDITRP  \r\n" . SUPPORT_NO;
					parent::trigger_sms($message, $mobile);

					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! New AMC has been added successfully!';
				} else {
					parent::rollback();
					$errors['message'] = 'Sorry! Something went wrong! Could not add the AMC.';
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
	public function update_amc($amc_id)
	{


		//print_r($_POST);exit();
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$amc_id 		= parent::test(isset($_POST['amc_id']) ? $_POST['amc_id'] : '');
		$empcode 		= parent::test(isset($_POST['empcode']) ? $_POST['empcode'] : '');
		$empcmpname 		= parent::test(isset($_POST['empcmpname']) ? $_POST['empcmpname'] : '');
		$empname 		= parent::test(isset($_POST['empname']) ? $_POST['empname'] : '');
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
		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$verify 			= parent::test(isset($_POST['verify']) ? $_POST['verify'] : '');
		$bankname 			= parent::test(isset($_POST['bankname']) ? $_POST['bankname'] : '');
		$accountnumber 			= parent::test(isset($_POST['accountnumber']) ? $_POST['accountnumber'] : '');
		$ifsc 			= parent::test(isset($_POST['ifsc']) ? $_POST['ifsc'] : '');
		$accountholdername 			= parent::test(isset($_POST['accountholdername']) ? $_POST['accountholdername'] : '');

		$uname 			= $email;
		$pword 			= parent::generate_password();
		$confpword 		= $pword;
		$expirationdate 	= parent::acc_expiry_date('');
		$registrationdate = parent::curr_date('');
		$creditcount 		= 100;
		$democount 		= 10;

		/* Files */
		$emplogo 			= isset($_FILES['emplogo']['name']) ? $_FILES['emplogo']['name'] : '';
		$emppassphoto 		= isset($_FILES['emppassphoto']['name']) ? $_FILES['emppassphoto']['name'] : '';
		$empphotoidproof 		= isset($_FILES['empphotoidproof']['name']) ? $_FILES['empphotoidproof']['name'] : '';

		//  $admin_id 		= $_SESSION['user_id'];
		$role 			= 7; //AMC;
		$created_by  		= $empname; //$_SESSION['user_fullname'];
		$updated_by  		= $empname; //$_SESSION['user_fullname'];
		$created_by_ip  	= parent::get_client_ip();
		/* check validations */
		if ($empcmpname == '')
			$errors['empcmpname'] = 'Employer Company name is required.';
		/*if($empcmpname!='')
			{
				if (!preg_match("/^[a-zA-Z ]*$/",$empcmpname)) {
				$errors['empcmpname'] = "Only letters and white space allowed";}
			}
			*/
		if ($empname == '')
			$errors['empname'] = 'AMC name is required.';
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
			$arr = array('9', '8', '7');
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
		if ($uname == '')
			$errors['uname'] = 'Username is required.';
		if ($pword == '')
			$errors['pword'] = 'Password is required.';
		if ($confpword == '')
			$errors['confpword'] = 'Confirm Password is required.';
		if ($pword != $confpword)
			$errors['confpword'] = 'Confirm password doesnt match!.';


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "amc_details";
			$setValues 	= "AMC_COMPANY_NAME=UPPER('$empcmpname'), AMC_NAME=UPPER('$empname'),DESIGNATION='$designation',ADDRESS_LINE1='$address1', ADDRESS_LINE2='$address2',MOBILE='$mobile',EMAIL='$email',CITY='$city',STATE='$state', POSTCODE='$postcode', DETAIL_DESCRIPTION='$empdetails',BANK_NAME='$bankname',ACCOUNT_NO='$accountnumber',IFSC_CODE='$ifsc',ACCOUNT_HOLDER_NAME='$accountholdername',CREDIT='$creditcount',DEMO_PER='$democount', ACTIVE='$status', UPDATED_BY='$updated_by', UPDATED_ON=NOW(), UPDATED_ON_IP='" . $_SESSION['ip_address'] . "'";
			$whereClause = " WHERE AMC_ID='$amc_id'";

			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);

			$exSql		= parent::execQuery($updateSql);

			$tableName2 	= "user_login_master";
			$setValues2 	= "USER_NAME='$uname',ACCOUNT_REGISTERED_ON='$registrationdate',ACCOUNT_EXPIRED_ON='$expirationdate', ACTIVE='$status', UPDATED_BY='$updated_by', UPDATED_ON=NOW(), UPDATED_ON_IP='" . $_SESSION['ip_address'] . "'";

			if (!empty($_POST['confpword'])) {
				if ($confpword == $pword)
					$setValues2 .= " , PASS_WORD= MD5('$confpword'), PASSWORD_CHANGE_DATE=NOW()";
			}
			$whereClause2	= " WHERE USER_LOGIN_ID='$institute_login_id' AND USER_ID='$employer_id'";
			$updateSql2		= parent::updateData($tableName2, $setValues2, $whereClause2);
			$exSql2			= parent::execQuery($updateSql2);


			/* upload files */

			//send email
			/*	if($_SESSION['user_role']==3 && ($empphotoidproof!='' || $emppassphoto!='' || $emplogo!=''))
					{	
						require_once(ROOT."/include/email/config.php");						
						require_once(ROOT."/include/email/templates/doc_upload_aicpe_to_franchise.php");
					}*/
			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! AMC has been updated successfully!';
		}
		return json_encode($data);
	}
	public function list_amc($amc_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%d-%m-%Y') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%m') AS UPDATED_DATE, B.USER_NAME, B.USER_LOGIN_ID,B.PASS_WORD, (SELECT CITY_NAME FROM city_master WHERE CITY_ID=A.CITY) AS CITY_NAME,(SELECT STATE_NAME FROM states_master WHERE STATE_ID=A.STATE) AS STATE_NAME,(SELECT COUNT(INSTITUTE_ID) FROM amc_assign WHERE AMC_ID=A.AMC_ID AND ACTIVE=1 AND DELETE_FLAG=0)AS TOTAL FROM amc_details A  LEFT JOIN user_login_master B ON A.AMC_ID=B.USER_ID AND B.USER_ROLE=7 WHERE A.DELETE_FLAG=0";

		if ($amc_id != '') {
			$sql .= " AND A.AMC_ID='$amc_id' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= ' ORDER BY CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	public function get_employer_docs($employer_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT * FROM employer_files WHERE 1";
		if ($employer_id != '')
			$sql .= " AND EMPLOYER_ID='$employer_id'";
		if ($condition != '')
			$sql .= " $condition";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}
	public function get_employer_docs_single($employer_id = '', $file_label = '')
	{
		$img = '';
		$data = array();
		$target = '';

		$sql = "SELECT * FROM employer_files WHERE DELETE_FLAG=0 ";
		if ($employer_id != '')
			$sql .= " AND EMPLOYER_ID='$employer_id'";
		if ($file_label != '')
			$sql .= " AND FILE_LABEL='$file_label'";
		$sql .= ' ORDER BY FILE_ID DESC LIMIT 0,1';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$rec = $res->fetch_assoc();
			$FILE_ID = $rec['FILE_ID'];
			$FILE_NAME = $rec['FILE_NAME'];
			$EMPLOYER_ID = $rec['EMPLOYER_ID'];
			if ($FILE_NAME != '') {
				$filePath = EMPLOYER_PHOTO_PATH . '/' . $EMPLOYER_ID . '/thumb/' . $FILE_NAME;
				$fileLink = EMPLOYER_PHOTO_PATH . '/' . $EMPLOYER_ID . '/' . $FILE_NAME;
				$img .=  '<img src="' . $filePath . '" class="img img-responsive" style="height:80px; width:80px" />';
			} else {
				$img .=  '<img src="../uploads/default_user.png" class="img img-responsive" style="height:80px; width:80px" />';
			}
		}

		return $img;
	}

	public function get_employer_docs_all($employer_id = '', $file_label = '', $display = true)
	{
		$img = '';
		$data = array();
		$target = '';

		$sql = "SELECT FILE_ID,FILE_NAME,FILE_LABEL,EMPLOYER_ID FROM employer_files WHERE DELETE_FLAG=0";
		if ($employer_id != '')
			$sql .= " AND EMPLOYER_ID='$employer_id'";
		if ($file_label != '')
			$sql .= " AND FILE_LABEL='$file_label'";
		$sql .= ' ORDER BY FILE_ID ';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($rec = $res->fetch_assoc()) {
				$FILE_ID 		= $rec['FILE_ID'];
				$FILE_NAME 		= $rec['FILE_NAME'];
				$EMPLOYER_ID 	= $rec['EMPLOYER_ID'];
				if ($FILE_NAME != '') {
					if (!$display) {
						$data1 = array("file_id" => $FILE_ID, "file_name" => $FILE_NAME, "employer_id" => $EMPLOYER_ID);
						array_push($data, $data1);
					} else {
						$filePath = EMPLOYER_PHOTO_PATH . '/' . $EMPLOYER_ID . '/thumb/' . $FILE_NAME;
						$fileLink = EMPLOYER_PHOTO_PATH . '/' . $EMPLOYER_ID . '/' . $FILE_NAME;

						$img .=  '<div id="file-area' . $FILE_ID . '">
												<a href="javascript:void(0)" title= "Delete File" onclick="deleteEmployerFile(' . $FILE_ID . ',' . $EMPLOYER_ID . ')" class="delete-icon"><i class="fa fa-trash-o"></i></a>
												&nbsp;&nbsp;&nbsp;<a href="' . $fileLink . '" target="_blank" title="View File"><i class="fa fa-eye"></i></a>
												<a href="' . $fileLink . '" target="_blank">
												<img src="' . $filePath . '" class="img img-responsive" style="height:80px; width:80px" />
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
	public function generate_employer_code()
	{
		$code = '';
		$code = parent::getRandomCode(6);
		$sql = "SELECT EMPLOYER_CODE FROM employer_details WHERE EMPLOYER_CODE='$code'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_employer_code();
		}
		return $code;
	}
	/* generate job post code */
	public function generate_jobpost_code()
	{
		$code = '';
		$code = parent::getRandomCode(6);
		$sql = "SELECT JOB_POST_CODE FROM employer_job_posts WHERE JOB_POST_CODE='$code'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_jobpost_code();
		}
		return $code;
	}
	/* validate institute code */
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
	/* delete employer  */
	public function delete_employer($emp_id = '')
	{
		$sql = "UPDATE employer_details SET ACTIVE=0,DELETE_FLAG=1 WHERE EMPLOYER_ID='$emp_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			$sql = "UPDATE user_login_master SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE USER_ID='$emp_id' AND USER_ROLE=3";
			$res = parent::execQuery($sql);
			return false;
		}
		return true;
	}

	/* delete employer file */
	public function delete_employer_file($file_id, $emp_id)
	{
		$sql = "UPDATE employer_files SET ACTIVE=0,DELETE_FLAG=1 WHERE FILE_ID='$file_id' AND EMPLOYER_ID='$emp_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}
	/* change institute name website visibility flag */
	public function changeVisiblityFlag($emp_id, $flag)
	{
		$sql = "UPDATE employer_details SET DISPLAY_ON_WEBSITE='$flag' WHERE EMPLOYER_ID='$emp_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			return true;
		}
		return false;
	}

	/* delete employer */
	public function deleteemployer($emp_id)
	{
		$sql = "UPDATE employer_details SET DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE EMPLOYER_ID='$emp_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			$sql = "UPDATE user_login_master SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE USER_ID='$emp_id' AND USER_ROLE=3";
			$res = parent::execQuery($sql);
			return true;
		}
		return false;
	}
	/* change institute name website visibility flag */
	public function changeStatusFlag($emp_id, $flag)
	{
		$sql = "UPDATE employer_details SET ACTIVE='$flag', UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(), UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE EMPLOYER_ID='$emp_id'";
		$sql2 = "UPDATE user_login_master SET ACTIVE='$flag' WHERE USER_ID='$emp_id' AND USER_ROLE=3";
		$res = parent::execQuery($sql);
		$res2 = parent::execQuery($sql2);
		if ($res) {
			return true;
		}
		return false;
	}
	/* change institute name website visibility flag */
	public function changeVerifyFlag($emp_id, $flag)
	{
		$sql = "UPDATE employer_details SET VERIFIED='$flag', UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(), UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE EMPLOYER_ID='$emp_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			if ($flag == 1) {
				require_once("../email/config.php");
				require_once("../email/templates/employer_registration_approved.php");
				//send sms
				$mobile = parent::get_user_mobile($emp_id, 3);
				$message = "Congratulations!!!\r\nYour application for DITRP Authorisation is approved.\r\nPlease check your email.\r\nDITRP  \r\n" . SUPPORT_NO;
				parent::trigger_sms($message, $mobile);
			}
			return true;
		}
		return false;
	}
	/* change job post active flag */
	public function changeJobpostStatus($job_id, $flag)
	{
		$sql = "UPDATE employer_job_posts SET ACTIVE='$flag', UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(), UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE JOB_POST_ID='$job_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			return true;
		}
		return false;
	}
	/* delete job post */
	public function deletejobpost($job_id)
	{
		$sql = "UPDATE employer_job_posts SET DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE JOB_POST_ID='$job_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			return true;
		}
		return false;
	}

	//add job post
	public function add_job_post()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data


		$jobtitle 	= parent::test(isset($_POST['jobtitle']) ? $_POST['jobtitle'] : '');
		$jobdesc 		= parent::test(isset($_POST['jobdesc']) ? $_POST['jobdesc'] : '');
		$skills 		= parent::test(isset($_POST['skills']) ? $_POST['skills'] : '');
		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		$jobcode 		= $this->generate_jobpost_code();
		$emp_id 		= $_SESSION['user_id'];
		$created_by  	= $_SESSION['user_fullname'];
		$created_by_ip = $_SESSION['ip_address'];

		/* check the credit point limit */

		$emp_credit_balance = $this->emp_credit_balance($emp_id);
		if ($emp_credit_balance == 0) {
			$errors['credit'] = 'Sorry! Your credit limit has been exceeded. Please rechrage your credit account. Please contact DITRP.';
		}


		/* check validations */
		if ($jobtitle == '')
			$errors['jobtitle'] = 'Job title is required.';
		if ($jobdesc == '')
			$errors['jobdesc'] = 'Job description is required.';
		if ($skills == '')
			$errors['skills'] = 'Job skills is required.';
		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
			if (isset($errors['credit']))
				$data['message'] = $errors['credit'];
		} else {
			parent::start_transaction();
			$tableName 	= "employer_job_posts";
			$tabFields 	= "(JOB_POST_ID, JOB_POST_CODE, EMPLOYER_ID,JOB_TITLE,JOB_DESCRIPTION,JOB_SKILLS,JOB_POST_DATE, ACTIVE, DELETE_FLAG, CREATED_BY, CREATED_ON,CREATED_ON_IP)";
			$insertVals	= "(NULL, '$jobcode', '$emp_id', '$jobtitle','$jobdesc','$skills',NOW(), '$status',0,'$created_by',NOW(), '$created_by_ip')";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql && parent::rows_affected() > 0) {
				/* -----Get the last insert ID ----- */
				//$last_insert_id = parent::last_id();
				$sql = "UPDATE employer_details SET CREDIT_BALANCE=CREDIT-1, CREDIT_USED=CREDIT_USED+1 WHERE EMPLOYER_ID='$emp_id'";
				$updSql = parent::execQuery($sql);
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New job post has been added successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the job post.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	//update job post
	public function update_job_post()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$jobtitle 	= parent::test(isset($_POST['jobtitle']) ? $_POST['jobtitle'] : '');
		$jobdesc 		= parent::test(isset($_POST['jobdesc']) ? $_POST['jobdesc'] : '');
		$skills 		= parent::test(isset($_POST['skills']) ? $_POST['skills'] : '');
		$job_post_id 		= parent::test(isset($_POST['job_post_id']) ? $_POST['job_post_id'] : '');
		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		// $jobcode 		= $this->generate_jobpost_code();
		$emp_id 		= $_SESSION['user_id'];
		$created_by  	= $_SESSION['user_fullname'];
		$created_by_ip = $_SESSION['ip_address'];
		/* check validations */
		if ($jobtitle == '')
			$errors['jobtitle'] = 'Job title is required.';
		if ($jobdesc == '')
			$errors['jobdesc'] = 'Job description is required.';
		if ($skills == '')
			$errors['skills'] = 'Job skills is required.';
		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "employer_job_posts";
			$setValues = "JOB_TITLE='$jobtitle', JOB_DESCRIPTION='$jobdesc', JOB_SKILLS='$skills', ACTIVE='$status', UPDATED_BY='$created_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_by_ip'";
			$whereClause = " WHERE JOB_POST_ID='$job_post_id'";
			$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
			$exSql			= parent::execQuery($updateSql);

			if ($exSql && parent::rows_affected() > 0) {
				/* -----Get the last insert ID ----- */
				//$last_insert_id = parent::last_id();
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Job post has been updated successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not update the job post.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function list_jobs($job_id = '', $employer_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT *,A.ACTIVE AS JOB_STATUS, DATE_FORMAT(A.JOB_POST_DATE, '%d-%m-%Y') AS JOB_POSTED_ON, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m %p') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%m') AS UPDATED_DATE FROM employer_job_posts A LEFT JOIN employer_details B ON A.EMPLOYER_ID=B.EMPLOYER_ID WHERE A.DELETE_FLAG=0 ";

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
	public function total_jobs_posted($emp_id)
	{
		$total = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM employer_job_posts WHERE EMPLOYER_ID='$emp_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$total = $data['TOTAL'];
		}
		return $total;
	}
	public function emp_total_credits($emp_id)
	{
		$total = 0;
		$sql = "SELECT CREDIT FROM employer_details WHERE EMPLOYER_ID='$emp_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$total = $data['CREDIT'];
		}
		return $total;
	}

	public function total_admission()
	{
		$total = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM student_details";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$total = $data['TOTAL'];
		}
		return $total;
	}
	public function emp_credit_balance($emp_id)
	{
		$credit_bal = 0;
		$total_credits 		= $this->emp_total_credits($emp_id);
		$total_jobs 		= $this->total_jobs_posted($emp_id);
		$total_admission 	= $this->total_admission();
		//$credit_bal = $total_credits - ($total_admission * $total_jobs);
		$credit_bal = $total_credits - $total_jobs;
		return $credit_bal;
	}
	public function get_emp_credit_info($emp_id)
	{
		$data = array();
		$sql = "SELECT CREDIT,CREDIT_BALANCE,CREDIT_USED FROM employer_details WHERE EMPLOYER_ID='$emp_id' LIMIT 0,1";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
		}
		return $data;
	}

	//Verify AMC 
	/* change institute name website visibility flag */
	public function changeAmcVerifyFlag($amc_id, $flag)
	{
		$date = @date('Y-m-d H:i:s');
		$sql = "UPDATE amc_details SET VERIFIED='$flag',VERIFIED_ON='$date' WHERE AMC_ID='$amc_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			if ($flag == 1) {
				//send email			
				require_once("../email/config.php");
				require_once("../email/templates/amc_registration_approved.php");
				//send SMS
				$mobile = parent::get_user_mobile($amc_id, 8);
				$message = "Congratulations!!!\r\nYour application for DITRP Area Managing Center Authorisation is approved.\r\nPlease check your email.\r\nDITRP \r\n" . SUPPORT_NO;
				parent::trigger_sms($message, $mobile);
			}
			return true;
		}
		return false;
	}




	/*---------------------- Support  ------------------------------*/

	public function add_support()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$institute_id 	= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : '');
		$supporttype 		= parent::test(isset($_POST['supporttype']) ? $_POST['supporttype'] : '');
		$supportcat 		= parent::test(isset($_POST['supportcat']) ? $_POST['supportcat'] : '');

		$authorname 		= parent::test(isset($_POST['authorname']) ? $_POST['authorname'] : '');
		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$altmobile 		= parent::test(isset($_POST['altmobile']) ? $_POST['altmobile'] : '');
		$email 			= parent::test(isset($_POST['email']) ? $_POST['email'] : '');
		$altemail 		= parent::test(isset($_POST['altemail']) ? $_POST['altemail'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');

		$supportfiles 		= isset($_FILES['supportfiles']['name']) ? $_FILES['supportfiles']['name'] : '';


		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$created_by  		= $_SESSION['user_fullname'];
		//new validations
		if ($supporttype == '')
			$errors['supporttype'] = 'Select Support Type is required.';
		if ($supportcat == '')
			$errors['supportcat'] = 'Support Category Name is required.';
		if ($description == '')
			$errors['description'] = 'Description is required.';

		//$errors=array();
		if (!empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();

			$tableName 	= "help_support";
			$tabFields 	= "(TICKET_ID,INSTITUTE_ID, SUPPORT_TYPE_ID,SUPPORT_CAT_ID,USER_ROLE,DESCRIPTION,AUTHOR_NAME,MOBILE,ALT_MOBILE,EMAIL,ALT_EMAIL,CURRENT_STATUS, ACTIVE,CREATED_BY, CREATED_ON)";
			$insertVals	= "(NULL,'$institute_id','$supporttype','$supportcat','7','$description','$authorname','$mobile','$altmobile','$email','$altemail',1,'$status','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);

			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				$last_insert_id = parent::last_id();

				//$courseImgPathDir 		= 	HELPSUPPORT_PHOTO_PATH.'/'.$last_insert_id


				$bucket_directory = 'helpsupport/' . $last_insert_id . '/';


				if ($supportfiles != '') {
					while (list($key, $value) = each($_FILES["supportfiles"]["name"])) {
						$cover_image		= $_FILES["supportfiles"]["name"][$key];
						//if product record is not blank
						if ($cover_image != '') {
							$ext 			= pathinfo($_FILES["supportfiles"]["name"][$key], PATHINFO_EXTENSION);
							$file_name 		= helpsupport . '_' . mt_rand(0, 123456789) . '.' . $ext;
							$tableName2 	= "help_support_images";
							$tabFields2 	= "(HELP_SUPPORT_IMG_ID,TICKET_ID,IMAGE,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON)";
							$insertVals2	= "(NULL, '$last_insert_id', '$file_name','1',0,'$created_by',NOW())";
							$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
							$exec2   		= parent::execQuery($insertSql2);

							$s3_obj = new S3Class();
							$activityContent = $_FILES['supportfiles']['name'];
							$fileTempName = $_FILES['supportfiles']['tmp_name'];
							$new_width = 800;
							$new_height = 750;
							$image_p = imagecreatetruecolor($new_width, $new_height);
							$image = imagecreatefromstring(file_get_contents($fileTempName));
							imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

							$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
							imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

							$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["supportfiles"]["type"]));

							//var_dump($response);
							//exit();

							/*$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
							$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
							$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
							@mkdir($courseImgPathDir,0777,true);
							@mkdir($courseImgThumbPathDir,0777,true);								
							parent::create_thumb_img($_FILES["supportfiles"]["tmp_name"][$key], $courseImgPathFile,  $ext, 800, 750) ;
							parent::create_thumb_img($_FILES["supportfiles"]["tmp_name"][$key], $courseImgThumbPathFile,  $ext, 300, 280);	*/
						}
					}
				}
			}
			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! New Help Support has been added successfully!';
		}

		return json_encode($data);
	}
}
