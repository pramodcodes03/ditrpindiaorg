<?php
include_once('database_results.class.php');
include_once('access.class.php');

class account extends access
{


	/* list admin staff  */
	public function list_admin_staff($staff_id = '', $admin_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT A.*, DATE_FORMAT(A.STAFF_DOB, '%d-%m-%Y') AS STAFF_DOB_FORMATED, B.USER_NAME, B.USER_LOGIN_ID FROM admin_staff_details A LEFT JOIN user_login_master B ON A.STAFF_ID=B.USER_ID WHERE A.DELETE_FLAG=0 ";
		if ($staff_id != '') {
			$sql .= " AND A.STAFF_ID='$staff_id' ";
		}
		if ($admin_id != '') {
			$sql .= " AND A.ADMIN_ID='$admin_id' ";
		}
		if ($cond != '') {
			$sql .= $cond;
		}
		$sql .= ' ORDER BY CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	/* add new staff in admin 
	@param: 
	@return: json
	*/
	public function add_admin_staff()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$action 		= isset($_POST['add_user']) ? $_POST['add_user'] : '';
		$fullname 	= parent::test(isset($_POST['fullname']) ? $_POST['fullname'] : '');
		$email 		= parent::test(isset($_POST['email']) ? $_POST['email'] : '');
		$mobile 		= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$dob 			= parent::test(isset($_POST['dob']) ? $_POST['dob'] : '');
		$gender 		= parent::test(isset($_POST['gender']) ? $_POST['gender'] : '');
		$temp_add		= parent::test(isset($_POST['temp_add']) ? $_POST['temp_add'] : '');
		$per_add 		= parent::test(isset($_POST['per_add']) ? $_POST['per_add'] : '');
		$state 		= parent::test(isset($_POST['state']) ? $_POST['state'] : '');
		$city 		= parent::test(isset($_POST['city']) ? $_POST['city'] : '');
		$pincode 		= parent::test(isset($_POST['pincode']) ? $_POST['pincode'] : '');
		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$designation 	= isset($_POST['designation']) ? $_POST['designation'] : '';
		$qualification = isset($_POST['qualification']) ? $_POST['qualification'] : '';
		$uname 		= isset($_POST['uname']) ? $_POST['uname'] : '';
		$pword 		= isset($_POST['pword']) ? $_POST['pword'] : '';
		$confpword 	= isset($_POST['confpword']) ? $_POST['confpword'] : '';
		$photo 		= isset($_FILES['photo']['name']) ? $_FILES['photo']['name'] : '';
		$photoid 		= isset($_FILES['photoid']['name']) ? $_FILES['photoid']['name'] : '';
		$status 		= isset($_POST['status']) ? $_POST['status'] : '';
		$responsibilities	= isset($_POST['responsibilities']) ? $_POST['responsibilities'] : '';
		$institute_id = $_SESSION['user_id'];
		$role 	= parent::test(isset($_POST['role']) ? $_POST['role'] : '');
		//$role 		= 6; //admin staff;
		$created_by  	= $_SESSION['user_name'];

		/* check validations */
		if ($fullname == '')
			$errors['fullname'] = 'Fullname is required.';
		if ($email == '')
			$errors['email'] = 'Email is required.';
		if ($mobile == '')
			$errors['mobile'] = 'Mobile number is required.';
		if ($uname == '')
			$errors['uname'] = 'Username is required.';
		if ($responsibilities == '' || empty($responsibilities))
			$errors['responsibilities'] = 'Responsibilities is required!';
		if ($pword == '')
			$errors['pword'] = 'Password is required.';
		if ($confpword == '')
			$errors['confpword'] = 'Confirm Password is required.';
		if ($pword != $confpword)
			$errors['confpword'] = 'Confirm password doesnt match!.';
		if (!parent::valid_username($uname))
			$errors['uname'] = 'Sorry! Username is already used.';
		if (!$this->valid_admin_staff_email($email, ''))
			$errors['email'] = 'Sorry! Email is already used.';

		if ($photo != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($photo, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['photo'] = 'Invalid file format! Please select valid file.';
			}
		}
		if ($photoid != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($photoid, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['photoid'] = 'Invalid file format! Please select valid file.';
			}
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = "Please correct all the errors.";
		} else {
			parent::start_transaction();
			$responsibilities = json_encode($responsibilities);
			$dob = date('Y-m-d', strtotime($dob));
			$tableName 	= "admin_staff_details";
			$tabFields 	= "(STAFF_ID, ADMIN_ID, STAFF_FULLNAME, STAFF_GENDER,STAFF_DOB,STAFF_EMAIL,STAFF_MOBILE,STAFF_TEMP_ADDRESS,STAFF_PER_ADDRESS,STAFF_CITY,STAFF_STATE,STAFF_PINCODE,STAFF_EDUCATION,STAFF_PHOTO,STAFF_DESIGNATION,STAFF_RESPONSIBILITIES,ACTIVE, CREATED_BY, CREATED_ON,USER_ROLE,INSTITUTE_ID)";
			$insertVals	= "(NULL, '$institute_id', UPPER('$fullname'), '$gender','$dob','$email','$mobile','$temp_add','$per_add','$city','$state','$pincode','$qualification','','$designation','$responsibilities','$status','$created_by',NOW(),'$role','$institute_id')";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				/* -----Get the last insert ID ----- */
				$last_insert_id = parent::last_id();
				$tableName2 	= "user_login_master";
				$tabFields2 	= "(USER_LOGIN_ID, USER_ID, USER_NAME, PASS_WORD,USER_ROLE, ACTIVE, CREATED_BY,CREATED_ON)";
				$insertVals2	= "(NULL, '$last_insert_id', '$uname', MD5('$confpword'),'$role','$status','$created_by',NOW())";
				$insertSql2	= parent::insertData($tableName2, $tabFields2, $insertVals2);
				$exSql2		= parent::execQuery($insertSql2);
				if ($exSql2) {
					if ($photo != '') {
						$ext 			= pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'staff_' . $last_insert_id . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tableName3 	= "admin_staff_details";
						$setValues3 	= "STAFF_PHOTO='$file_name'";
						$whereClause3	= " WHERE STAFF_ID='$last_insert_id'";
						$updateSql3		= parent::updateData($tableName3, $setValues3, $whereClause3);
						$exec3			= parent::execQuery($updateSql3);

						/*	$bucket_directory = 'admin/staff/'.$last_insert_id.'/'; 
								
								$s3_obj = new S3Class();
                                $activityContent = $_FILES['photo']['name'];
                                $fileTempName = $_FILES['photo']['tmp_name'];
                                $new_width = 800;
                                $new_height = 750;
                                $image_p = imagecreatetruecolor($new_width, $new_height);
                                $image = imagecreatefromstring(file_get_contents($fileTempName));
                                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));
                                
                                $newFielName = tempnam(null,null); // take a llok at the tempnam and adjust parameters if needed
                                imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()
                                
                                $response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory.''.$file_name , S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["photo"]["type"]));*/

						//var_dump($response);
						//exit();

						$courseImgPathDir 		= ADMIN_STAFF_PHOTO_PATH . '/' . $last_insert_id . '/';
						$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["photo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					if ($photoid != '') {
						$ext 			= pathinfo($_FILES["photoid"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'staff_photoid_' . $last_insert_id . '_' . mt_rand(0, 123456789) . '.' . $ext;
						$tableName3 	= "admin_staff_details";
						$setValues3 	= "STAFF_PHOTO_ID='$file_name'";
						$whereClause3	= " WHERE STAFF_ID='$last_insert_id'";
						$updateSql3		= parent::updateData($tableName3, $setValues3, $whereClause3);
						$exec3			= parent::execQuery($updateSql3);

						/*$bucket_directory = 'admin/staff/'.$last_insert_id.'/'; 
								
								$s3_obj = new S3Class();
                                $activityContent = $_FILES['photoid']['name'];
                                $fileTempName = $_FILES['photoid']['tmp_name'];
                                $new_width = 800;
                                $new_height = 750;
                                $image_p = imagecreatetruecolor($new_width, $new_height);
                                $image = imagecreatefromstring(file_get_contents($fileTempName));
                                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));
                                
                                $newFielName = tempnam(null,null); // take a llok at the tempnam and adjust parameters if needed
                                imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()
                                
                                $response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory.''.$file_name , S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["photoid"]["type"]));*/

						//var_dump($response);
						//exit();

						$courseImgPathDir 		= ADMIN_STAFF_PHOTO_PATH . '/' . $last_insert_id . '/';
						$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["photoid"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["photoid"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
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
	/* add new staff in institute 
	@param: 
	@return: json
	*/
	public function update_admin_staff()
	{
		$errors 		= array();  // array to hold validation errors
		$data 		= array();        // array to pass back data
		$action 		= isset($_POST['update_staff']) ? $_POST['update_staff'] : '';
		$login_id 	= parent::test(isset($_POST['login_id']) ? $_POST['login_id'] : '');
		$staff_id 	= parent::test(isset($_POST['staff_id']) ? $_POST['staff_id'] : '');
		$institute_id	= parent::test(isset($_POST['admin_id']) ? $_POST['admin_id'] : '');
		$fullname 	= parent::test(isset($_POST['fullname']) ? $_POST['fullname'] : '');
		$email 		= parent::test(isset($_POST['email']) ? $_POST['email'] : '');
		$mobile 		= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$dob 			= parent::test(isset($_POST['dob']) ? $_POST['dob'] : '');
		$gender 		= parent::test(isset($_POST['gender']) ? $_POST['gender'] : '');
		$temp_add		= parent::test(isset($_POST['temp_add']) ? $_POST['temp_add'] : '');
		$per_add 		= parent::test(isset($_POST['per_add']) ? $_POST['per_add'] : '');
		$state 		= parent::test(isset($_POST['state']) ? $_POST['state'] : '');
		$city 		= parent::test(isset($_POST['city']) ? $_POST['city'] : '');
		$pincode 		= parent::test(isset($_POST['pincode']) ? $_POST['pincode'] : '');
		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$designation 	= parent::test(isset($_POST['designation']) ? $_POST['designation'] : '');
		$responsibilities	= isset($_POST['responsibilities']) ? json_encode($_POST['responsibilities']) : '';
		$qualification = parent::test(isset($_POST['qualification']) ? $_POST['qualification'] : '');
		$uname 		= parent::test(isset($_POST['uname']) ? $_POST['uname'] : '');
		$pword 		= parent::test(isset($_POST['pword']) ? $_POST['pword'] : '');
		$confpword 	= parent::test(isset($_POST['confpword']) ? $_POST['confpword'] : '');
		$status 		= isset($_POST['status']) ? $_POST['status'] : '';
		$staff_photo 	= isset($_FILES['staff_photo']['name']) ? $_FILES['staff_photo']['name'] : '';
		$staff_photoid 	= isset($_FILES['staff_photoid']['name']) ? $_FILES['staff_photoid']['name'] : '';
		$role 	= parent::test(isset($_POST['role']) ? $_POST['role'] : '');
		//$role 		= 6; //admin staff;
		$updated_by  	= $_SESSION['user_name'];
		/* check validations */
		//if($dob!='')
		//$dob = date("Y-m-d", strtotime($dob));
		if ($fullname == '')
			$errors['fullname'] = 'Fullname is required.';
		if ($email == '')
			$errors['email'] = 'Email is required.';
		if ($mobile == '')
			$errors['mobile'] = 'Mobile number is required.';
		if ($uname == '')
			$errors['uname'] = 'Username is required.';
		// if($responsibilities=='' || empty($responsibilities))
		//	$errors['responsibilities'] = 'Responsibilities is required!';
		if ($fullname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $fullname)) {
				$errors['fullname'] = "Only letters and white space allowed";
			}
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Invalid email format";
		}
		if ($mobile == '')
			$errors['mobile'] = 'Mobile number is required.';
		if ($mobile != '') {
			if (strlen($mobile) != 10)
				$errors['mobile'] = ' Please enter valid numbers,Only 10 Digits allowed.';
			if ($mobile <= 0)
				$errors['mobile'] = 'Please enter valid numbers.';
		}

		// if ($pword=='')
		//	$errors['pword'] = 'Password is required.';
		//  if ($confpword=='')
		//   $errors['confpword'] = 'Confirm Password is required.';

		if ($pword != $confpword)
			$errors['confpword'] = 'Confirm password doesnt match!.';
		if (!parent::valid_username_onupdate($uname, $login_id))
			$errors['uname'] = 'Sorry! Username is already used.';
		if (!$this->valid_admin_staff_email($email, $staff_id))
			$errors['email'] = 'Sorry! Email is already used.';

		if ($staff_photo != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($staff_photo, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['staff_photo'] = 'Invalid file format!';
			}
		}
		if ($staff_photoid != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($staff_photoid, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['staff_photoid'] = 'Invalid file format!';
			}
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			//$responsibilities = json_encode($responsibilities);					
			//$dob = date('Y-m-d',strtotime($dob));					
			$tableName 	= "admin_staff_details";
			$setValues 	= "STAFF_FULLNAME='$fullname', STAFF_GENDER='$gender', STAFF_DOB='$dob',STAFF_EMAIL='$email',STAFF_MOBILE='$mobile', STAFF_TEMP_ADDRESS='$temp_add',STAFF_PER_ADDRESS='$per_add',STAFF_CITY='$city',STAFF_STATE='$state', STAFF_PINCODE='$pincode', STAFF_EDUCATION='$qualification',STAFF_DESIGNATION='$designation',STAFF_RESPONSIBILITIES='$responsibilities', ACTIVE='$status', UPDATED_BY='$updated_by', UPDATED_ON=NOW(),USER_ROLE='$role'";
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
					if ($staff_photo != '') {
						$ext = pathinfo($_FILES["staff_photo"]["name"], PATHINFO_EXTENSION);
						$file_name = time() . '' . mt_rand(0, 123456789) . '.' . $ext;
						$updProspSql 	= "UPDATE admin_staff_details SET STAFF_PHOTO='$file_name' WHERE  STAFF_ID='$staff_id'";
						$exec = parent::execQuery($updProspSql);

						/*$bucket_directory = 'admin/staff/'.$staff_id.'/'; 
								
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

						$courseImgPathDir 		= 	ADMIN_STAFF_PHOTO_PATH . '/' . $staff_id . '/';
						$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);

						parent::create_thumb_img($_FILES["staff_photo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["staff_photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280) ;	

					}
					if ($staff_photoid != '') {
						$ext = pathinfo($_FILES["staff_photoid"]["name"], PATHINFO_EXTENSION);
						$file_name = time() . '' . mt_rand(0, 123456789) . '.' . $ext;
						$updProspSql 	= "UPDATE admin_staff_details SET STAFF_PHOTO_ID='$file_name' WHERE  STAFF_ID='$staff_id'";
						$exec = parent::execQuery($updProspSql);

						/*$bucket_directory = 'admin/staff/'.$staff_id.'/'; 
                                                                
                                $s3_obj = new S3Class();
                                $activityContent = $_FILES['staff_photoid']['name'];
                                $fileTempName = $_FILES['staff_photoid']['tmp_name'];
                                $new_width = 800;
                                $new_height = 750;
                                $image_p = imagecreatetruecolor($new_width, $new_height);
                                $image = imagecreatefromstring(file_get_contents($fileTempName));
                                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));
                                
                                $newFielName = tempnam(null,null); // take a llok at the tempnam and adjust parameters if needed
                                imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()
                                
                                $response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory.''.$file_name , S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["staff_photoid"]["type"]));*/

						//var_dump($response);
						//exit();

						$courseImgPathDir 		= 	ADMIN_STAFF_PHOTO_PATH . '/' . $staff_id . '/';
						$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						//@mkdir($courseImgThumbPathDir,0777,true);

						parent::create_thumb_img($_FILES["staff_photoid"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
						//parent::create_thumb_img($_FILES["staff_photoid"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280) ;	

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
	/* delete admin staff */
	public function delete_admin_staff($staff_id, $login_id)
	{
		$sql 	= "UPDATE admin_staff_details SET DELETE_FLAG=1, UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW() WHERE STAFF_ID='$staff_id'";

		$sql1 	= "UPDATE user_login_master SET ACTIVE=0, DELETE_FLAG=1, UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW() WHERE USER_LOGIN_ID='$login_id'";
		$res 	= parent::execQuery($sql);
		$res1 	= parent::execQuery($sql1);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}
	/* verify admis staff email address */
	public function valid_admin_staff_email($email, $staff_id = '')
	{
		$sql = "SELECT STAFF_EMAIL FROM admin_staff_details WHERE STAFF_EMAIL='$email'";
		if ($staff_id != '')
			$sql .= " AND STAFF_ID!='$staff_id'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			return false;
		return true;
	}
}
