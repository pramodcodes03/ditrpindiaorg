<?php
include_once('database_results.class.php');
include_once('access.class.php');

class admin extends access
{
	/* add new staff in institute 
	@param: 
	@return: json
	*/
	public function add_admin()
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$fname 		= parent::test(isset($_POST['fname']) ? $_POST['fname'] : '');
		$mname 		= parent::test(isset($_POST['mname']) ? $_POST['mname'] : '');
		$lname 		= parent::test(isset($_POST['lname']) ? $_POST['lname'] : '');
		$uemail 		= parent::test(isset($_POST['uemail']) ? $_POST['uemail'] : '');

		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');


		$uname 			= parent::test(isset($_POST['uname']) ? $_POST['uname'] : '');
		$pword 			= parent::test(isset($_POST['pword']) ? $_POST['pword'] : '');
		$confpword 		= parent::test(isset($_POST['confpword']) ? $_POST['confpword'] : '');
		$expirationdate 	= parent::test(isset($_POST['expirationdate']) ? $_POST['expirationdate'] : '');
		$registrationdate = parent::test(isset($_POST['registrationdate']) ? $_POST['registrationdate'] : '');

		/* Files */
		$photo 			= isset($_FILES['photo']['name']) ? $_FILES['photo']['name'] : '';


		$admin_id 		= $_SESSION['user_id'];
		$role 			= 1; //institute staff;
		$created_by  		= $_SESSION['user_fullname'];
		$created_by_ip  		= $_SESSION['ip_address'];
		/* check validations */
		if ($fname == '')
			$errors['fname'] = 'First name is required.';
		if ($fname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $fname)) {
				$errors['fname'] = "Only letters and white space allowed";
			}
		}

		if ($mname == '')
			$errors['mname'] = 'Middle name is required.';
		if ($mname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $mname)) {
				$errors['mname'] = "Only letters and white space allowed";
			}
		}

		if ($lname == '')
			$errors['lname'] = 'Last name is required.';
		if ($lname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $lname)) {
				$errors['lname'] = "Only letters and white space allowed";
			}
		}



		if ($uemail == '')
			$errors['uemail'] = 'Email is required.';
		if (!filter_var($uemail, FILTER_VALIDATE_EMAIL)) {
			$errors['uemail'] = "Invalid email format";
		}

		if ($mobile == '')
			$errors['mobile'] = 'Mobile number is required.';
		if ($mobile != '') {
			if (strlen($mobile) != 10)
				$errors['mobile'] = ' Please enter valid numbers,Only 10 Digits allowed.';
			if ($mobile <= 0)
				$errors['mobile'] = 'Please enter valid numbers.';
		}


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
		if (!parent::valid_Institute_email($email, ''))
			$errors['uemail'] = 'Sorry! Email is already used.';

		/* files validations */
		if ($photo == '') {
			$errors['photo'] 			= 'Please upload company logo.';
		}

		if ($photo != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png');
			$extension = pathinfo($photo, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['photo'] = 'Invalid file format! Please select valid file.';
			}
		}



		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "admin_details_master";
			$tabFields 	= "(ADMIN_ID, FIRST_NAME,MIDDLE_NAME,LAST_NAME,USER_EMAIL,MOBILE,ACTIVE,CREATED_BY, CREATED_ON)";
			$insertVals	= "(NULL, '$fname', '$mname', '$lname','$uemail','$mobile', '$status','$created_by',NOW())";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				/* -----Get the last insert ID ----- */
				$last_insert_id = parent::last_id();
				$tableName2 	= "user_login_master";
				$tabFields2 	= "(USER_LOGIN_ID, USER_ID, USER_NAME, PASS_WORD,USER_ROLE, ACCOUNT_REGISTERED_ON,ACCOUNT_EXPIRED_ON,ACTIVE, CREATED_BY,CREATED_ON)";
				$insertVals2	= "(NULL, '$last_insert_id', '$fname', MD5('$confpword'),'$role','$registrationdate','$expirationdate','$status','$created_by',NOW())";
				$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);
				$exSql2			= parent::execQuery($insertSql2);

				if ($exSql2) {
					//$courseImgPathDir = 	ADMIN_PHOTO_PATH.'/'.$last_insert_id.'/';

					$bucket_directory = 'admin/' . $last_insert_id . '/';

					$tableName3 			= "admin_details_master";
					/* upload files */
					if ($photo != '') {
						$ext 			= pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
						$file_name 		= PHOTO . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tabFields3 	= "(ADMIN_ID, PHOTO,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON)";
						$insertVals3	= "(NULL, '$file_name','" . PHOTO . "','1',0,'$created_by',NOW())";
						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);
						$exec3   		= parent::execQuery($insertSql3);

						$s3_obj = new S3Class();
						$activityContent = $_FILES['photo']['name'];
						$fileTempName = $_FILES['photo']['tmp_name'];
						$new_width = 800;
						$new_height = 750;
						$image_p = imagecreatetruecolor($new_width, $new_height);
						$image = imagecreatefromstring(file_get_contents($fileTempName));
						imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

						$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
						imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

						$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["photo"]["type"]));

						//var_dump($response);
						//exit();

						/*$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
								@mkdir($courseImgPathDir,0777,true);
								@mkdir($courseImgThumbPathDir,0777,true);								
								parent::create_thumb_img($_FILES["photo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
								parent::create_thumb_img($_FILES["photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);	*/
					}



					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Admin has been added successfully!';
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

	/* update institute 
	@param: 
	@return: json
	*/
	public function update_admin($emp_id)
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data 


		$fname 		= parent::test(isset($_POST['fname']) ? $_POST['fname'] : '');
		$mname 		= parent::test(isset($_POST['mname']) ? $_POST['mname'] : '');
		$lname 		= parent::test(isset($_POST['lname']) ? $_POST['lname'] : '');
		$uemail 		= parent::test(isset($_POST['uemail']) ? $_POST['uemail'] : '');

		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$updated_by 			= parent::test(isset($_POST['updated_by']) ? $_POST['updated_by'] : '');

		$pword 			= parent::test(isset($_POST['pword']) ? $_POST['pword'] : '');
		$confpword 		= parent::test(isset($_POST['confpword']) ? $_POST['confpword'] : '');


		/* Files */
		$photo 			= isset($_FILES['photo']['name']) ? $_FILES['photo']['name'] : '';

		$user_login_id = $_SESSION['user_login_id'];
		$admin_id 		= $_SESSION['user_id'];
		$role 			= 1; //institute staff;
		$created_by  		= $_SESSION['user_fullname'];
		$created_by_ip  		= $_SESSION['ip_address'];
		/* check validations */
		if ($fname == '')
			$errors['fname'] = 'First name is required.';
		if ($fname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $fname)) {
				$errors['fname'] = "Only letters and white space allowed";
			}
		}

		if ($mname == '')
			$errors['mname'] = 'Middle name is required.';
		if ($mname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $mname)) {
				$errors['mname'] = "Only letters and white space allowed";
			}
		}

		if ($lname == '')
			$errors['lname'] = 'Last name is required.';
		if ($lname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $lname)) {
				$errors['lname'] = "Only letters and white space allowed";
			}
		}



		if ($uemail == '')
			$errors['uemail'] = 'Email is required.';
		if (!filter_var($uemail, FILTER_VALIDATE_EMAIL)) {
			$errors['uemail'] = "Invalid email format";
		}

		if ($mobile == '')
			$errors['mobile'] = 'Mobile number is required.';
		if ($mobile != '') {
			if (strlen($mobile) != 10)
				$errors['mobile'] = ' Please enter valid numbers,Only 10 Digits allowed.';
			if ($mobile <= 0)
				$errors['mobile'] = 'Please enter valid numbers.';
		}
		/* files validations */

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "admin_details_master";
			$setValues 	= "FIRST_NAME='$fname', MIDDLE_NAME='$mname',  LAST_NAME='$lname',USER_EMAIL='$uemail',MOBILE='$mobile', ACTIVE='$status', UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
			$whereClause = " WHERE ADMIN_ID='$admin_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);
			if (!empty($_POST['confpword'])) {
				$tableName2 	= "user_login_master";
				$setValues2 	= " UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
				if ($confpword == $pword)
					$setValues2 .= " , PASS_WORD= MD5('$confpword'), PASSWORD_CHANGE_DATE=NOW()";
				$whereClause2	= " WHERE USER_LOGIN_ID='$user_login_id' AND USER_ID='$admin_id'";
				$updateSql2		= parent::updateData($tableName2, $setValues2, $whereClause2);
				$exSql2			= parent::execQuery($updateSql2);
			}

			//$courseImgPathDir 		= ADMIN_PHOTO_PATH.'/'.$admin_id.'/';

			$bucket_directory = 'admin/' . $admin_id . '/';

			$tableName3 			= "admin_details_master";
			/* upload files */
			if ($photo != '') {
				$ext 			= pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
				$file_name 		= mt_rand(0, 123456789) . '.' . $ext;
				$setValues3 	= "PHOTO='$file_name'";
				$whereClause3 = " WHERE ADMIN_ID='$admin_id'";
				$updateSql3	= parent::updateData($tableName, $setValues3, $whereClause3);
				$exec3   		= parent::execQuery($updateSql3);

				$s3_obj = new S3Class();
				$activityContent = $_FILES['photo']['name'];
				$fileTempName = $_FILES['photo']['tmp_name'];
				$new_width = 800;
				$new_height = 750;
				$image_p = imagecreatetruecolor($new_width, $new_height);
				$image = imagecreatefromstring(file_get_contents($fileTempName));
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

				$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
				imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

				$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["photo"]["type"]));

				//var_dump($response);
				//exit();

				/*
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["photo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
						parent::create_thumb_img($_FILES["photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);	
						$_SESSION['user_photo'] = parent::get_curr_userphoto($admin_id,$_SESSION['user_role']);*/
			}

			$_SESSION['user_fullname'] = parent::get_curr_username($admin_id, $_SESSION['user_role']);
			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! Admin has been updated successfully!';
		}
		return json_encode($data);
	}
	public function update_user_pass()
	{
		$errors 	= array();  // array to hold validation errors
		$data 	= array();        // array to pass back data 

		$currentpassword 	= parent::test(isset($_POST['currentpassword']) ? $_POST['currentpassword'] : '');
		$newpassword 		= parent::test(isset($_POST['newpassword']) ? $_POST['newpassword'] : '');
		$confpassword 		= parent::test(isset($_POST['confpassword']) ? $_POST['confpassword'] : '');
		/* Files */
		$photo 			= isset($_FILES['photo']['name']) ? $_FILES['photo']['name'] : '';

		$user_login_id 	= $_SESSION['user_login_id'];
		$user_id 			= $_SESSION['user_id'];
		$created_by  		= $_SESSION['user_fullname'];
		$created_by_ip  	= $_SESSION['ip_address'];
		/* check validations */
		if ($currentpassword == '')
			$errors['currentpassword'] = 'Current password is required.';
		if ($newpassword == '')
			$errors['newpassword'] = 'New Password password is required.';
		if ($confpassword == '')
			$errors['confpassword'] = 'Confirm new password is required.';

		if ($newpassword != $confpassword)
			$errors['confpassword'] = 'Confirm password is not matching with New password!';
		//check current password is correct
		$sql = "SELECT USER_LOGIN_ID FROm user_login_master WHERE USER_LOGIN_ID='$user_login_id' AND PASS_WORD=MD5('$currentpassword') LIMIT 0,1";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows <= 0) {
			$errors['currentpassword'] = 'Current password is wrong!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName2 	= "user_login_master";
			$setValues2 	= " UPDATED_BY='$created_by', UPDATED_ON=NOW() , PASS_WORD= MD5('$confpassword'), PASSWORD_CHANGE_DATE=NOW()";
			$whereClause2	= " WHERE USER_LOGIN_ID='$user_login_id' AND USER_ID='$user_id'";
			$updateSql2		= parent::updateData($tableName2, $setValues2, $whereClause2);
			$exSql2			= parent::execQuery($updateSql2);
			if ($exSql2) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Your password has been updated successfully!';
			} else {
				parent::rollback();
				$data['success'] = false;
				$data['message'] = 'Failed! Something went wrong!';
			}
		}
		return json_encode($data);
	}
	public function list_admin_data($admin_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y %h:%m:%s') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%d-%m-%Y %h:%m:%s') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%m') AS UPDATED_DATE, B.USER_NAME, B.USER_LOGIN_ID FROM admin_details_master A LEFT JOIN user_login_master B ON A.ADMIN_ID=B.USER_LOGIN_ID AND B.USER_ROLE=1 WHERE A.ACTIVE=1 ";

		if ($admin_id != '') {
			$sql .= " AND A.ADMIN_ID='$admin_id' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= ' ORDER BY A.CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function list_admin_password($admin_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y %h:%m:%s') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%d-%m-%Y %h:%m:%s') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%m') AS UPDATED_DATE, B.USER_NAME, B.USER_LOGIN_ID FROM admin_details_master A LEFT JOIN user_login_master B ON A.ADMIN_ID=B.USER_LOGIN_ID AND B.USER_ROLE=1 WHERE A.ACTIVE=1 ";

		if ($admin_id != '') {
			$sql .= " AND A.ADMIN_ID='$admin_id' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
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

		$sql = "SELECT * FROM employer_files WHERE 1";
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

		$sql = "SELECT FILE_ID,FILE_NAME,FILE_LABEL,EMPLOYER_ID FROM employer_files WHERE 1";
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
												<a href="javascript:void(0)" title= "Delete File" onclick="deleteInstitueFile(' . $FILE_ID . ',' . $EMPLOYER_ID . ')" class="delete-icon"><i class="fa fa-trash-o"></i></a>
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
		$code = parent::getRandomCodeEmp();
		$sql = "SELECT EMPLOYER_CODE FROM employer_details WHERE EMPLOYER_CODE='$code'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_employer_code();
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
	/* validate institute code */
	public function delete_employer_file($emp_id = '')
	{
		$sql = "UPDATE employer_details SET ACTIVE=0,DELETE_FLAG=1 WHERE EMPLOYER_ID='$emp_id'";

		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
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
	/* change institute name website visibility flag */
	public function changeStatusFlag($emp_id, $flag)
	{
		$sql = "UPDATE employer_details SET ACTIVE='$flag' WHERE EMPLOYER_ID='$emp_id'";
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
		$sql = "UPDATE employer_details SET VERIFIED='$flag' WHERE INSTITUTE_ID='$emp_id'";
		$res = parent::execQuery($sql);
		if ($res) {
			return true;
		}
		return false;
	}
	/* change institute name website visibility flag */
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
	//student payments
	//add student payments
	// register new course for existing student
	public function add_institute_payment()
	{
		$reqid 			= parent::test(isset($_POST['reqid']) ? $_POST['reqid'] : '');
		$institute_id	= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : '');
		$totalexamfees 	= parent::test(isset($_POST['totalexamfees']) ? $_POST['totalexamfees'] : '');
		$totalamtrecieved = parent::test(isset($_POST['totalamtrecieved']) ? $_POST['totalamtrecieved'] : '');
		$totalamtbalance = parent::test(isset($_POST['totalamtbalance']) ? $_POST['totalamtbalance'] : '');
		$paymentdate	= parent::test(isset($_POST['paymentdate']) ? $_POST['paymentdate'] : '');
		$paymentmode	= parent::test(isset($_POST['paymentmode']) ? $_POST['paymentmode'] : '');
		$chequeno		= parent::test(isset($_POST['chequeno']) ? $_POST['chequeno'] : '');
		$chequebank		= parent::test(isset($_POST['chequebank']) ? $_POST['chequebank'] : '');
		$chequedate		= parent::test(isset($_POST['chequedate']) ? $_POST['chequedate'] : '');
		$paymentnote	= parent::test(isset($_POST['paymentnote']) ? $_POST['paymentnote'] : '');

		if ($paymentdate != '')
			$paymentdate = date('Y-m-d', strtotime($paymentdate));
		if ($chequedate != '')
			$chequedate = date('Y-m-d', strtotime($chequedate));
		$errors 		= array();  // array to hold validation errors
		$data 			= array();

		if ($totalamtrecieved <= 0) {
			$errors['totalamtrecieved'] = 'Invalid amount! Please enter the amount greater than zero.';
		}

		$requiredArr = array('totalamtrecieved' => $totalamtrecieved);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}
		if ($totalamtrecieved != '' && !parent::valid_decimal($totalamtrecieved))
			$errors['totalamtrecieved'] = 'Please enter valid amount. Should be positive integer only.';
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "institute_payments";
			$tabFields 	= "(PAYMENT_ID,RECIEPT_NO, INSTITUTE_ID, CERTIFICATE_REQUEST_MASTER_ID,TOTAL_EXAM_FEES,TOTAL_EXAM_FEES_RECIEVED,TOTAL_EXAM_FEES_BALANCE,PAYMENT_DATE,PAYMENT_MODE, CHEQUE_NUMBER,CHEQUE_BANK_NAME,CHEQUE_DATE,PAYMENT_NOTE,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
			$insertVals	= "(NULL, generate_admin_reciept_num(), '$institute_id','$reqid','$totalexamfees', '$totalamtrecieved','$totalamtbalance','$paymentdate','$paymentmode','$chequeno','$chequebank','$chequedate','$paymentnote','1','" . $_SESSION['user_fullname'] . "',NOW(), '" . $_SESSION['ip_address'] . "')";
			$insertSql		= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql			= parent::execQuery($insertSql);
			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New payment has been added successfully!';
			} else {
				parent::rollback();
				$data['success'] = false;
				$data['message'] = 'Sorry! New payment was not added successfully!';
			}
		}
		return json_encode($data);
	}

	//list institutes payments
	public function list_institute_payments($cert_req_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*, get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME,DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE,DATE_FORMAT(A.PAYMENT_DATE, '%d-%m-%Y %h:%i:%p') AS PAYMENT_DATE_ON,(SELECT F.CITY_NAME as city_name FROM city_master F WHERE A.INSTITUTE_ID=F.CITY_ID) AS INSTITUTE_CITY  FROM institute_payments A WHERE A.DELETE_FLAG=0 ";
		if ($cert_req_id != '') {
			$sql .= " AND A.CERTIFICATE_REQUEST_MASTER_ID='$cert_req_id' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function get_payment_totals($cert_req_id = '', $inst_id = '')
	{
		$data = array();
		$sql = "SELECT A.TOTAL_EXAM_FEES, SUM(A.TOTAL_EXAM_FEES_RECIEVED) AS TOTAL_FEES_RECIEVED,(A.TOTAL_EXAM_FEES - SUM(A.TOTAL_EXAM_FEES_RECIEVED)) AS TOTAL_FEES_BALANCE FROM institute_payments A WHERE A.DELETE_FLAG=0 ";
		if ($cert_req_id != '') {
			$sql .= " AND A.CERTIFICATE_REQUEST_MASTER_ID='$cert_req_id' ";
		}
		if ($inst_id != '') {
			$sql .= " AND INSTITUTE_ID='$inst_id' ";
		}
		$sql .= " ORDER BY A.PAYMENT_ID DESC LIMIT 0,1";
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result  				= $res->fetch_assoc();
			$data['total_fees'] 	= $result['TOTAL_EXAM_FEES'];
			$data['total_recieved'] = $result['TOTAL_FEES_RECIEVED'];
			$data['total_balance'] 	= $result['TOTAL_FEES_BALANCE'];
		}
		return $data;
	}
	public function list_institute($condition = '')
	{
		$data = '';
		$sql = "SELECT A.*, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%d-%m-%Y ') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%i %p') AS UPDATED_DATE, B.USER_NAME, B.USER_LOGIN_ID, (SELECT CITY_NAME FROM city_master WHERE CITY_ID=A.CITY) AS CITY_NAME FROM institute_details A LEFT JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID AND B.USER_ROLE=2 WHERE A.DELETE_FLAG=0 ";

		if ($condition != '') {
			$sql .= " $condition ";
		}
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	public function list_employer($condition = '')
	{
		$data = '';
		$sql = "SELECT A.*, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%d-%m-%Y') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%m') AS UPDATED_DATE, B.USER_NAME, B.USER_LOGIN_ID,(SELECT CITY_NAME FROM city_master WHERE CITY_ID=A.CITY) AS CITY_NAME FROM employer_details A LEFT JOIN user_login_master B ON A.EMPLOYER_ID=B.USER_ID AND B.USER_ROLE=3 WHERE A.DELETE_FLAG=0 ";
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	public function count_total_users($role)
	{
		$data = 0;
		$tbl = "";
		switch ($role) {
				//studets
			case (4):
				$tbl  = "student_details";
				break;
				//employers
			case (3):
				$tbl = "employer_details";
				break;
			case (2):
				$tbl = "institute_details";
				break;
		}
		if ($tbl != '') {
			$sql = "SELECT COUNT(*) AS TOTAL FROM $tbl WHERE DELETE_FLAG=0 ";
			$res = parent::execQuery($sql);
			if ($res && $res->num_rows > 0) {
				$result = $res->fetch_assoc();
				$data = $result['TOTAL'];
			}
		}
		return $data;
	}
	public function getTotalAdmissions($inst_id = '', $coursetype = '')
	{
		$res = '0';
		$sql = "SELECT COUNT(*) AS TOTAL_ADMISSION FROM student_course_details A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID WHERE  A.DELETE_FLAG=0";
		if ($inst_id != '')
			$sql .= " AND A.INSTITUTE_ID='$inst_id'";
		if ($coursetype != '')
			$sql .= " AND B.COURSE_TYPE='$coursetype'";

		$rec = parent::execQuery($sql);
		if ($rec && $rec->num_rows > 0) {
			$data = $rec->fetch_assoc();
			$res = $data['TOTAL_ADMISSION'];
		}
		return $res;
	}
	public function getTotalInstitutes($verified = '', $status = '')
	{
		$data = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM institute_details A LEFT JOIN user_login_master B ON  A.INSTITUTE_ID = B.USER_ID WHERE A.DELETE_FLAG=0 AND B.USER_ROLE = 8";
		if ($verified != '')
			$sql .= " AND A.VERIFIED='$verified'";
		if ($status != '')
			$sql .= " AND A.ACTIVE='$status'";
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}
	public function getTotalEmployers($verified = '', $status = '')
	{
		$data = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM employer_details WHERE DELETE_FLAG=0 ";
		if ($verified != '')
			$sql .= " AND VERIFIED='$verified'";
		if ($status != '')
			$sql .= " AND ACTIVE='$status'";
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}
	public function getTotalCertificateRequests($status = '', $cond = '')
	{
		$data = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM certificate_requests WHERE DELETE_FLAG=0 ";
		if ($status != '') {
			$sql .= " AND REQUEST_STATUS='$status'";
		}
		if ($cond != '') {
			$sql .= $cond;
		}
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}
	// order certificate
	public function getTotalCertificateRequestsOrder($status = '')
	{
		$data = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM certificate_order_requests WHERE DELETE_FLAG=0 ";
		if ($status != '')
			$sql .= " AND REQUEST_STATUS='$status'";

		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}
	public function getTotalCertificateRequestsFees($status = '')
	{
		$data = 0;
		$sql = "SELECT SUM(EXAM_FEES) AS TOTAL FROM certificate_requests WHERE DELETE_FLAG=0 ";
		if ($status != '')
			$sql .= " AND REQUEST_STATUS='$status'";

		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}
	public function getTotalStudentExams($status = '')
	{
		$data = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM student_course_details A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID  WHERE A.DELETE_FLAG=0 AND B.DELETE_FLAG=0 AND B.COURSE_TYPE=1 ";
		if ($status != '')
			$sql .= " AND A.EXAM_STATUS='$status'";

		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}
	public function getTotalOnlinePayment($status = '')
	{
		$data = 0;
		$sql = "SELECT SUM(PAYMENT_AMOUNT) AS TOTAL FROM online_payments WHERE DELETE_FLAG=0 ";
		if ($status != '')
			$sql .= " AND PAYMENT_STATUS='$status'";

		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}
	public function getTotalOfflinePayment($cond = '')
	{
		$data = 0;
		$sql = "SELECT SUM(PAYMENT_AMOUNT) AS TOTAL FROM offline_payments WHERE DELETE_FLAG=0 ";
		if ($cond != '')
			$sql .= $cond;

		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}



	public function getTotalWallet()
	{
		$data = 0;
		$sql = "SELECT SUM(A.TOTAL_BALANCE) AS TOTAL FROM wallet A WHERE A.DELETE_FLAG=0 AND A.ACTIVE=1 AND A.USER_ROLE = '8'";
		if ($cond != '')
			$sql .= $cond;

		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}
	public function get_recharge_history($paymentmode = '', $wallet_id = '', $user_id = '', $user_role = '', $cond = '')
	{
		$output = array();
		if ($paymentmode == 'ONLINE') {
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

					$result['GST'] 	            =  $data['GST'];
					$result['TOTAL_AMOUNT'] 	=  $data['TOTAL_AMOUNT'];


					array_push($output, $result);
				}
				//$output[$result['TRANSACTION_NO']] = $result;
				//$output['TRANSACTION_NO'] = $result;

			}
		}
		if ($paymentmode == 'OFFLINE') {
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
			//echo $sql; exit();
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
					$result2['CREATED_BY'] 		= $data['CREATED_BY'];
					$result2['CREATED_ON'] 		= $data['CREATED_ON'];
					$result2['CREATED_DATE'] 	= $data['CREATED_DATE'];
					$result2['PAYMENT_MODE'] 	= 'OFFLINE';
					$result2['BONUS_STAUS'] 	= $data['BONUS_STAUS'];
					$result2['STUDENT_ID'] 	= $data['STUDENT_ID'];

					$result2['RECHARG_BY'] 	= $data['RECHARG_BY'];
					$result2['LEAD_BY'] 	    = $data['LEAD_BY'];

					array_push($output, $result2);
				}
				//$output[$result2['TRANSACTION_NO']] = $result2;

			}
		} else if ($paymentmode == '') {

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
					$result['CREATED_DATE'] 	= $data['CREATED_DATE'];
					$result['PAYMENT_MODE'] 	= 'ONLINE';

					$result['GST'] 	            =  $data['GST'];
					$result['TOTAL_AMOUNT'] 	=  $data['TOTAL_AMOUNT'];


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
					$result2['CREATED_BY'] 		= $data['CREATED_BY'];
					$result2['CREATED_ON'] 		= $data['CREATED_ON'];
					$result2['CREATED_DATE'] 	= $data['CREATED_DATE'];
					$result2['PAYMENT_MODE'] 	= 'OFFLINE';
					$result2['BONUS_STAUS'] 	= $data['BONUS_STAUS'];
					$result2['STUDENT_ID'] 	    = $data['STUDENT_ID'];

					$result2['RECHARG_BY'] 	= $data['RECHARG_BY'];
					$result2['LEAD_BY'] 	= $data['LEAD_BY'];

					array_push($output, $result2);
				}
				//$output[$result2['TRANSACTION_NO']] = $result2;

			}
		}
		return $output;
	}

	public function get_courier_recharge_history($paymentmode = '', $wallet_id = '', $user_id = '', $user_role = '', $cond = '')
	{
		$output = array();
		if ($paymentmode == 'ONLINE') {
			//online payment data
			$sql = "SELECT *, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m %p') AS CREATED_DATE FROM online_payments A WHERE A.DELETE_FLAG=0 ";
			if ($wallet_id != '')
				$sql .= " AND A.COURIER_WALLET_ID='$wallet_id'";
			if ($user_id != '')
				$sql .= " AND A.USER_ID='$user_id' AND A.COURIER_WALLET_PAYMENT=1";

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

					$result['GST'] 	            =  $data['GST'];
					$result['TOTAL_AMOUNT'] 	=  $data['TOTAL_AMOUNT'];



					array_push($output, $result);
				}
				//$output[$result['TRANSACTION_NO']] = $result;
				//$output['TRANSACTION_NO'] = $result;

			}
		}
		if ($paymentmode == 'OFFLINE') {
			//offline payment data
			$sql = "SELECT *, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m %p') AS CREATED_DATE FROM offline_payments A WHERE A.DELETE_FLAG=0 ";
			if ($wallet_id != '')
				$sql .= " AND A.COURIER_WALLET_ID='$wallet_id'";
			if ($user_id != '')
				$sql .= " AND A.USER_ID='$user_id' AND A.COURIER_WALLET_PAYMENT=1";
			if ($cond != '')
				$sql .= " $cond";
			//echo $sql; exit();
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
					$result2['CREATED_BY'] 		= $data['CREATED_BY'];
					$result2['CREATED_ON'] 		= $data['CREATED_ON'];
					$result2['CREATED_DATE'] 	= $data['CREATED_DATE'];
					$result2['PAYMENT_MODE'] 	= 'OFFLINE';
					$result2['BONUS_STAUS'] 	= $data['BONUS_STAUS'];
					$result2['RECHARG_BY'] 	= $data['RECHARG_BY'];
					$result2['LEAD_BY'] 	= $data['LEAD_BY'];

					array_push($output, $result2);
				}
				//$output[$result2['TRANSACTION_NO']] = $result2;

			}
		} else if ($paymentmode == '') {

			//online payment data
			$sql = "SELECT *, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m %p') AS CREATED_DATE FROM online_payments A WHERE A.DELETE_FLAG=0 ";
			if ($wallet_id != '')
				$sql .= " AND A.COURIER_WALLET_ID='$wallet_id'";
			if ($user_id != '')
				$sql .= " AND A.USER_ID='$user_id' AND A.COURIER_WALLET_PAYMENT=1";

			if ($user_role != '')
				$sql .= " AND A.USER_ROLE='$user_role'";

			if ($cond != '')
				$sql .= " $cond";

			//echo $sql;
			$res = parent::execQuery($sql);
			//error_log(print_r($sql, true));
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
					$result['CREATED_DATE'] 	= $data['CREATED_DATE'];
					$result['PAYMENT_MODE'] 	= 'ONLINE';

					$result['GST'] 	            =  $data['GST'];
					$result['TOTAL_AMOUNT'] 	=  $data['TOTAL_AMOUNT'];

					array_push($output, $result);
				}
				//$output[$result['TRANSACTION_NO']] = $result;
				//$output['TRANSACTION_NO'] = $result;

			}


			//offline payment data
			$sql = "SELECT *, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m %p') AS CREATED_DATE FROM offline_payments A WHERE A.DELETE_FLAG=0 ";
			if ($wallet_id != '')
				$sql .= " AND A.COURIER_WALLET_ID='$wallet_id'";
			if ($user_id != '')
				$sql .= " AND A.USER_ID='$user_id' AND A.COURIER_WALLET_PAYMENT=1";
			if ($user_role != '')
				$sql .= " AND A.USER_ROLE='$user_role'";
			//echo $sql;		
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
					$result2['CREATED_BY'] 		= $data['CREATED_BY'];
					$result2['CREATED_ON'] 		= $data['CREATED_ON'];
					$result2['CREATED_DATE'] 	= $data['CREATED_DATE'];
					$result2['PAYMENT_MODE'] 	= 'OFFLINE';
					$result2['BONUS_STAUS'] 	= $data['BONUS_STAUS'];
					$result2['RECHARG_BY'] 	= $data['RECHARG_BY'];
					$result2['LEAD_BY'] 	= $data['LEAD_BY'];

					array_push($output, $result2);
				}
				//$output[$result2['TRANSACTION_NO']] = $result2;

			}
		}
		return $output;
	}


	public function get_recharge_history_amc($paymentmode = '', $wallet_id = '', $user_id = '', $user_role = '', $cond = '', $onlinecond = '', $offlinecond = '')
	{
		$output = array();
		if ($paymentmode == 'ONLINE') {
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

			$cond = " A.USER_ID IN (SELECT INSTITUE_ID FROM ams_assign WHERE AMC_ID=$amc_id) AND A.USER_ROLE=2";
			if ($onlinecond != '')
				$sql .= " $onlinecond";
			$res = parent::execQuery($sql);
			if ($res && $res->num_rows > 0) {
				$result = array();
				while ($data = $res->fetch_assoc()) {
					$result['PAYMENT_ID']   	= $data['PAYMENT_ID'];
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
					$result['AMC_PAYMENT_STATUS'] 		= $data['AMC_PAYMENT_STATUS'];
					array_push($output, $result);
				}
			}
		}
		if ($paymentmode == 'OFFLINE') {
			//offline payment data
			$sql = "SELECT *, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m %p') AS CREATED_DATE FROM offline_payments A WHERE A.DELETE_FLAG=0 ";
			//$sql .=" AND A.BONUS_STAUS!='YES'";
			if ($wallet_id != '')
				$sql .= " AND A.WALLET_ID='$wallet_id'";
			if ($user_id != '')
				$sql .= " AND A.USER_ID='$user_id'";
			if ($user_role != '')
				$sql .= " AND A.USER_ROLE='$user_role'";
			if ($cond != '')
				$sql .= " $cond";
			if ($offlinecond != '')
				$sql .= " $offlinecond";
			echo $sql;
			$res2 = parent::execQuery($sql);
			if ($res2 && $res2->num_rows > 0) {
				$result2 = array();
				while ($data = $res2->fetch_assoc()) {
					$result2['PAYMENT_ID']   	= $data['PAYMENT_ID'];
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
					$result2['BONUS_STAUS'] 	= $data['BONUS_STAUS'];
					$result2['AMC_PAYMENT_STATUS'] 		= $data['AMC_PAYMENT_STATUS'];
					array_push($output, $result2);
				}
			}
		} else if ($paymentmode == '') {

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
			if ($onlinecond != '')
				$sql .= " $onlinecond";
			//	echo $sql;
			$res = parent::execQuery($sql);
			if ($res && $res->num_rows > 0) {
				$result = array();
				while ($data = $res->fetch_assoc()) {
					$result['PAYMENT_ID']   	= $data['PAYMENT_ID'];
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
					$result['AMC_PAYMENT_STATUS'] 		= $data['AMC_PAYMENT_STATUS'];
					array_push($output, $result);
				}
			}


			//offline payment data
			$sql = "SELECT *, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%m %p') AS CREATED_DATE FROM offline_payments A WHERE A.DELETE_FLAG=0 ";
			//$sql .=" AND A.BONUS_STAUS!='YES'";
			if ($wallet_id != '')
				$sql .= " AND A.WALLET_ID='$wallet_id'";
			if ($user_id != '')
				$sql .= " AND A.USER_ID='$user_id'";
			if ($user_role != '')
				$sql .= " AND A.USER_ROLE='$user_role'";
			if ($cond != '')
				$sql .= " $cond";
			if ($offlinecond != '')
				$sql .= " $offlinecond";
			//	echo $sql; 
			$res2 = parent::execQuery($sql);
			if ($res2 && $res2->num_rows > 0) {
				$result2 = array();
				while ($data = $res2->fetch_assoc()) {
					$result2['PAYMENT_ID']   	= $data['PAYMENT_ID'];
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
					$result2['BONUS_STAUS'] 	= $data['BONUS_STAUS'];
					$result2['AMC_PAYMENT_STATUS'] 		= $data['AMC_PAYMENT_STATUS'];
					array_push($output, $result2);
				}
				//$output[$result2['TRANSACTION_NO']] = $result2;

			}
		}
		return $output;
	}
	public function get_birth_day_report($month = '', $day = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT A.*,DATE_FORMAT(A.STUDENT_DOB, '%d-%m-%Y') AS DOB_FORMATTED,DAY(A.STUDENT_DOB) AS DOB_DAY, MONTH(A.STUDENT_DOB) AS DOB_MONTH, B.USER_NAME, B.USER_LOGIN_ID ,get_stud_photo(A.STUDENT_ID) as STUDENT_PHOTO FROM student_details A LEFT JOIN user_login_master B ON A.STUDENT_ID =B.USER_ID AND B.USER_ROLE=4 WHERE A.DELETE_FLAG=0 ";

		if ($month != '') {
			$sql .= " AND MONTH(A.STUDENT_DOB) = $month";
		}
		if ($day != '') {
			$sql .= " AND DAY(A.STUDENT_DOB) = '$day'";
		}
		//$datefrom = date('Y-m-d', strtotime($datefrom));
		//	$dateto = date('Y-m-d', strtotime($dateto));
		//$cond .= " AND DATE_FORMAT(FROM_UNIXTIME(A.STUDENT_DOB),'%m-%d') = DATE_FORMAT(NOW(),'%m-%d')";


		$sql .= $cond;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}

		return $data;
	}
	public function student_reports_count($studid = '', $instid = '', $courseid = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT COUNT(*) AS TOTAL FROM student_course_details A LEFT JOIN student_details B ON A.STUDENT_ID=B.STUDENT_ID LEFT JOIN institute_courses C ON A.INSTITUTE_COURSE_ID=C.INSTITUTE_COURSE_ID WHERE A.DELETE_FLAG=0 AND B.DELETE_FLAG=0  ";

		if ($studid != '') {
			$sql .= " AND A.STUDENT_ID='$studid' ";
		}
		if ($instid != '') {
			$sql .= " AND A.INSTITUTE_ID='$instid' ";
		}
		if ($courseid != '') {
			$sql .= " AND A.INSTITUTE_COURSE_ID='$courseid' ";
		}

		if ($cond != '') {
			$sql .= $cond;
		}

		$sql .= " ";
		$res = parent::execQuery($sql);

		$result = $res->fetch_assoc();
		$data = $result['TOTAL'];

		return $data;
	}
	public function total_exam_result($cond = '')
	{
		$data = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM exam_result WHERE DELETE_FLAG=0 $cond";
		$res = parent::execQuery($sql);
		if ($res) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}
		return $data;
	}
	public function getTotalWalletInstitutesCount($cond = '')
	{
		$data = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM wallet A WHERE A.DELETE_FLAG=0 $cond";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}


	//Typing Software
	public function getTotalTypingInstitutes($active = '')
	{
		$data = 0;
		$sql = "SELECT COUNT(*) AS TOTAL FROM typing_institute_details WHERE DELETE_FLAG=0 ";
		if ($active != '')
			$sql .= " AND ACTIVE='$active'";
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res->fetch_assoc();
			$data = $result['TOTAL'];
		}

		return $data;
	}

	//DITRP Staff Institute Report Handle

	public function add_institute_report_staff($institute_id = '')
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$institute_id 	            = parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : '');

		$regis_fees		            = parent::test(isset($_POST['regis_fees']) ? $_POST['regis_fees'] : '');
		$regis_fees_details 		    = parent::test(isset($_POST['regis_fees_details']) ? $_POST['regis_fees_details'] : '');

		$welcome_kit 		            = parent::test(isset($_POST['welcome_kit']) ? $_POST['welcome_kit'] : '');
		$welcome_kit_details			= parent::test(isset($_POST['welcome_kit_details']) ? $_POST['welcome_kit_details'] : '');

		$admission 		            = parent::test(isset($_POST['admission']) ? $_POST['admission'] : '');
		$admission_details 			= parent::test(isset($_POST['admission_details']) ? $_POST['admission_details'] : '');

		$contest		                = parent::test(isset($_POST['contest']) ? $_POST['contest'] : '');
		$contest_details		        = parent::test(isset($_POST['contest_details']) ? $_POST['contest_details'] : '');

		$typing		                = parent::test(isset($_POST['typing']) ? $_POST['typing'] : '');
		$typing_details		        = parent::test(isset($_POST['typing_details']) ? $_POST['typing_details'] : '');

		$mobileapp		            = parent::test(isset($_POST['mobileapp']) ? $_POST['mobileapp'] : '');
		$mobileapp_details		    = parent::test(isset($_POST['mobileapp_details']) ? $_POST['mobileapp_details'] : '');

		$remark_details	            = parent::test(isset($_POST['remark_details']) ? $_POST['remark_details'] : '');

		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		$created_by  		= $_SESSION['user_fullname'];

		//$errors=array();
		if (!empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();

			$tableName 	= "ditrpstaff_inst_report";
			$tabFields 	= "(REPORT_ID,INSTITUTE_ID, ADMISSION_DEMO,ADMISSION_DETAILS,TYPING_DEMO,TYPING_DETAILS,MOBILEAPP_DEMO,MOBILEAPP_DETAILS,ECONTEST_DEMO,ECONTEST_DETAILS,WELCOMEKIT_DEMO,WELCOMEKIT_DETAILS,REGISTRATION_FEE,REGISTRATIONFEES_DETAILS,REMARK,ACTIVE,DELETE_FLAG,CREATED_BY, CREATED_ON)";
			$insertVals	= "(NULL,'$institute_id','$admission','$admission_details','$typing','$typing_details','$mobileapp','$mobileapp_details','$contest','$contest_details','$welcome_kit','$welcome_kit_details','$regis_fees','$regis_fees_details','$remark_details','$status','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);

			$exSql		= parent::execQuery($insertSql);


			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! Institute Report has been added successfully!';
		}

		return json_encode($data);
	}

	public function list_institute_report_staff($report_id = '', $institute_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT * FROM ditrpstaff_inst_report WHERE DELETE_FLAG=0";
		if ($report_id != '') {
			$sql .= " AND REPORT_ID='$report_id' ";
		}
		if ($institute_id != '') {
			$sql .= " AND INSTITUTE_ID='$institute_id' ";
		}
		if ($cond != '') {
			$sql .= $cond;
		}
		$sql .= ' ORDER BY CREATED_ON DESC';
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}


	public function update_institute_report_staff($report_id = '')
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$report_id 	                = parent::test(isset($_POST['report_id']) ? $_POST['report_id'] : '');

		$institute_id 	            = parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : '');

		$regis_fees		            = parent::test(isset($_POST['regis_fees']) ? $_POST['regis_fees'] : '');
		$regis_fees_details 		    = parent::test(isset($_POST['regis_fees_details']) ? $_POST['regis_fees_details'] : '');

		$welcome_kit 		            = parent::test(isset($_POST['welcome_kit']) ? $_POST['welcome_kit'] : '');
		$welcome_kit_details			= parent::test(isset($_POST['welcome_kit_details']) ? $_POST['welcome_kit_details'] : '');

		$admission 		            = parent::test(isset($_POST['admission']) ? $_POST['admission'] : '');
		$admission_details 			= parent::test(isset($_POST['admission_details']) ? $_POST['admission_details'] : '');

		$contest		                = parent::test(isset($_POST['contest']) ? $_POST['contest'] : '');
		$contest_details		        = parent::test(isset($_POST['contest_details']) ? $_POST['contest_details'] : '');

		$typing		                = parent::test(isset($_POST['typing']) ? $_POST['typing'] : '');
		$typing_details		        = parent::test(isset($_POST['typing_details']) ? $_POST['typing_details'] : '');

		$mobileapp		            = parent::test(isset($_POST['mobileapp']) ? $_POST['mobileapp'] : '');
		$mobileapp_details		    = parent::test(isset($_POST['mobileapp_details']) ? $_POST['mobileapp_details'] : '');

		$remark_details	            = parent::test(isset($_POST['remark_details']) ? $_POST['remark_details'] : '');

		$status 			            = parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		$created_by  		= $_SESSION['user_fullname'];

		//$errors=array();
		if (!empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();

			$tableName 	= "ditrpstaff_inst_report";
			$setValues 	= "ADMISSION_DEMO='$admission',ADMISSION_DETAILS='$admission_details', TYPING_DEMO='$typing',TYPING_DETAILS='$typing_details',MOBILEAPP_DEMO='$mobileapp',MOBILEAPP_DETAILS='$mobileapp_details',ECONTEST_DEMO='$contest',ECONTEST_DETAILS='$contest_details',WELCOMEKIT_DEMO='$welcome_kit',WELCOMEKIT_DETAILS='$welcome_kit_details',REGISTRATION_FEE='$regis_fees',REGISTRATIONFEES_DETAILS='$regis_fees_details',REMARK='$remark_details',ACTIVE='$status',UPDATED_BY='$created_by',UPDATED_ON=NOW()";
			$whereClause = " WHERE REPORT_ID='$report_id' AND INSTITUTE_ID='$institute_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! Institute Report has been updated successfully!';
		}

		return json_encode($data);
	}

	/* change certificate print status  */
	public function changePrintFlag($inst_id, $flag)
	{
		$sql = "UPDATE certificate_order_requests_master SET PRINT_CERT='$flag' WHERE CERTIFICATE_REQUEST_MASTER_ID='$inst_id'";

		$res = parent::execQuery($sql);
		$res2 = parent::execQuery($sql2);
		if ($res) {
			return true;
		}
		return false;
	}
}
