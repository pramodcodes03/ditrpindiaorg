<?php

include_once('database_results.class.php');

include_once('access.class.php');
include_once('s3.php');
include_once('s3Class.php');


class registrations extends access

{





	/* add new staff in institute 

	@param: 

	@return: json

	*/

	public function add_institute()

	{

		$errors = array();  // array to hold validation errors

		$data = array();        // array to pass back data



		$instcode 		= isset($_POST['instcode']) ? $_POST['instcode'] : '';

		$instname 		= isset($_POST['instname']) ? $_POST['instname'] : '';

		$instowner 		= parent::test(isset($_POST['instowner']) ? $_POST['instowner'] : '');

		$designation 		= parent::test(isset($_POST['designation']) ? $_POST['designation'] : '');

		$email 			= parent::test(isset($_POST['email']) ? $_POST['email'] : '');

		$mobile 			= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');

		$address1 		= parent::test(isset($_POST['address1']) ? $_POST['address1'] : '');

		$address2			= parent::test(isset($_POST['address2']) ? $_POST['address2'] : '');

		$state 			= parent::test(isset($_POST['state']) ? $_POST['state'] : '');

		$city 			= parent::test(isset($_POST['city']) ? $_POST['city'] : '');

		$country 			= parent::test(isset($_POST['country']) ? $_POST['country'] : '');

		$postcode 		= parent::test(isset($_POST['postcode']) ? $_POST['postcode'] : '');

		$instdetails 		= parent::test(isset($_POST['instdetails']) ? $_POST['instdetails'] : '');

		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');



		$uname 			= isset($_POST['uname']) ? $_POST['uname'] : '';

		$pword 			= isset($_POST['pword']) ? $_POST['pword'] : '';

		$confpword 		= isset($_POST['confpword']) ? $_POST['confpword'] : '';



		$creditcount 		= isset($_POST['creditcount']) ? $_POST['creditcount'] : '';

		$democount 		= isset($_POST['democount']) ? $_POST['democount'] : '';



		/* Files */

		$instlogo 			= isset($_FILES['instlogo']['name']) ? $_FILES['instlogo']['name'] : '';

		$passphoto 		= isset($_FILES['passphoto']['name']) ? $_FILES['passphoto']['name'] : '';

		$photoidproof 		= isset($_FILES['photoidproof']['name']) ? $_FILES['photoidproof']['name'] : '';

		$instregcertificate = isset($_FILES['instregcertificate']['name']) ? $_FILES['instregcertificate']['name'] : '';

		$educationalproof 	= isset($_FILES['educationalproof']['name']) ? $_FILES['educationalproof']['name'] : '';

		$profcourseproof 	= isset($_FILES['profcourseproof']['name']) ? $_FILES['profcourseproof']['name'] : '';

		$instphotos 		= isset($_FILES['instphotos']['name']) ? $_FILES['instphotos']['name'] : '';



		$admin_id 		= $_SESSION['user_id'];

		$role 			= 2; //institute staff;

		$created_by  		= $_SESSION['user_name'];



		/* check validations */

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

		if ($uname == '')

			$errors['uname'] = 'Username is required.';

		if ($pword == '')

			$errors['pword'] = 'Password is required.';

		if ($confpword == '')

			$errors['confpword'] = 'Confirm Password is required.';

		if ($pword != $confpword)

			$errors['confpword'] = 'Confirm password doesnt match!.';



		if ($creditcount == '')

			$errors['creditcount'] = 'Please enter credit amount.';

		if ($democount == '')

			$errors['democount'] = 'Please enter Demo count to allow per students.';



		if (!parent::valid_username($uname))

			$errors['uname'] = 'Sorry! Username is already used.';

		if (!parent::valid_institute_email($email, ''))

			$errors['email'] = 'Sorry! Email is already used.';

		if (!$this->validate_institute_code($instcode))

			$errors['instcode'] = 'Sorry! Institute code is already present.';



		/* files validations */

		if ($passphoto == '') {
			$errors['passphoto'] 			= 'Please upload owner photo.';
		}

		if ($photoidproof == '') {
			$errors['photoidproof'] 		= 'Please upload Photo ID proof.';
		}

		if ($instregcertificate == '') {
			$errors['instregcertificate'] 	= 'Please upload Institute Certificate';
		}

		if ($educationalproof == '') {
			$errors['educationalproof'] 	= 'Please upload educational certificates.';
		}

		if ($profcourseproof == '') {
			$errors['profcourseproof'] 		= 'Please upload any other professional courses certificates.';
		}

		if ($instphotos == '') {
			$errors['instphotos'] 			= 'Please upload institute photos.';
		}



		if ($passphoto != '') {

			$allowed_ext = array('jpg', 'jpeg', 'png');

			$extension = pathinfo($passphoto, PATHINFO_EXTENSION);

			if (!in_array($extension, $allowed_ext)) {

				$errors['passphoto'] = 'Invalid file format! Please select valid file.';
			}
		}

		if ($photoidproof != '') {

			$allowed_ext = array('jpg', 'jpeg', 'png');

			$extension = pathinfo($photoidproof, PATHINFO_EXTENSION);

			if (!in_array($extension, $allowed_ext)) {

				$errors['photoidproof'] = 'Invalid file format! Please select valid file.';
			}
		}

		if ($instregcertificate != '') {

			$allowed_ext = array('jpg', 'jpeg', 'png');

			$extension = pathinfo($instregcertificate, PATHINFO_EXTENSION);

			if (!in_array($extension, $allowed_ext)) {

				$errors['instregcertificate'] = 'Invalid file format! Please select valid file.';
			}
		}

		if ($educationalproof != '') {

			$allowed_ext = array('jpg', 'jpeg', 'png');

			$extension = pathinfo($educationalproof, PATHINFO_EXTENSION);

			if (!in_array($extension, $allowed_ext)) {

				$errors['educationalproof'] = 'Invalid file format! Please select valid file.';
			}
		}

		if ($profcourseproof != '') {

			$allowed_ext = array('jpg', 'jpeg', 'png');

			$extension = pathinfo($profcourseproof, PATHINFO_EXTENSION);

			if (!in_array($extension, $allowed_ext)) {

				$errors['profcourseproof'] = 'Invalid file format! Please select valid file.';
			}
		}

		/*

		  if($instphotos!='')

		  {

				$allowed_ext = array('jpg','jpeg','png');				

				$extension = pathinfo($instphotos, PATHINFO_EXTENSION);

				if(!in_array($extension, $allowed_ext))

				{					

					$errors['instphotos'] = 'Invalid file format! Please select valid file.';

				}

		  }

		  */

		if (! empty($errors)) {

			// if there are items in our errors array, return those errors

			$data['success'] = false;

			$data['errors']  = $errors;

			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();

			$tableName 	= "institute_details";

			$tabFields 	= "(INSTITUTE_ID, INSTITUTE_CODE, INSTITUTE_NAME, INSTITUTE_OWNER_NAME,DESIGNATION,ADDRESS_LINE1,ADDRESS_LINE2,MOBILE,EMAIL,CITY,STATE,COUNTRY,POSTCODE,DETAIL_DESCRIPTION,CREDIT,DEMO_PER,ACTIVE, CREATED_BY, CREATED_ON)";

			$insertVals	= "(NULL, '$instcode', '$instname', '$instowner','$designation','$address1','$address1','$mobile','$email','$city','$state','$country','$postcode','$instdetails','$creditcount','$democount','$status','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);

			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {

				/* -----Get the last insert ID ----- */

				$last_insert_id = parent::last_id();

				$tableName2 	= "user_login_master";

				$tabFields2 	= "(USER_LOGIN_ID, USER_ID, USER_NAME, PASS_WORD,USER_ROLE, ACTIVE, CREATED_BY,CREATED_ON)";

				$insertVals2	= "(NULL, '$last_insert_id', '$instcode', MD5('$confpword'),'$role','$status','$created_by',NOW())";

				$insertSql2		= parent::insertData($tableName2, $tabFields2, $insertVals2);

				$exSql2			= parent::execQuery($insertSql2);



				if ($exSql2) {

					//$courseImgPathDir 		= 	INSTITUTE_DOCUMENTS_PATH.'/'.$last_insert_id.'/';

					$bucket_directory = 'institute/docs/' . $last_insert_id . '/';


					$tableName3 			= "institute_files";

					/* upload files */

					if ($instlogo != '') {

						$ext 			= pathinfo($_FILES["instlogo"]["name"], PATHINFO_EXTENSION);

						$file_name 		= INST_LOGO . '_' . mt_rand(0, 123456789) . '.' . $ext;

						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON)";

						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_LOGO . "','1',0,'$created_by',NOW())";

						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);

						$exec3   		= parent::execQuery($insertSql3);

						$s3_obj = new S3Class();
						$activityContent = $_FILES['instlogo']['name'];
						$fileTempName = $_FILES['instlogo']['tmp_name'];
						$new_width = 800;
						$new_height = 750;
						$image_p = imagecreatetruecolor($new_width, $new_height);
						$image = imagecreatefromstring(file_get_contents($fileTempName));
						imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

						$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
						imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

						$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["instlogo"]["type"]));

						//var_dump($response);
						//exit();



						/*	$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;

								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';

								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;

								@mkdir($courseImgPathDir,0777,true);

								@mkdir($courseImgThumbPathDir,0777,true);								

								parent::create_thumb_img($_FILES["instlogo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;

								parent::create_thumb_img($courseImgPathFile, $courseImgThumbPathFile,  $ext, 300, 280);	*/
					}

					if ($passphoto != '') {

						$ext 			= pathinfo($_FILES["passphoto"]["name"], PATHINFO_EXTENSION);

						$file_name 		= INST_OWNER_PHOTO . '_' . mt_rand(0, 123456789) . '.' . $ext;

						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON)";

						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_OWNER_PHOTO . "','1',0,'$created_by',NOW())";

						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);

						$exec3   		= parent::execQuery($insertSql3);

						$s3_obj = new S3Class();
						$activityContent = $_FILES['passphoto']['name'];
						$fileTempName = $_FILES['passphoto']['tmp_name'];
						$new_width = 800;
						$new_height = 750;
						$image_p = imagecreatetruecolor($new_width, $new_height);
						$image = imagecreatefromstring(file_get_contents($fileTempName));
						imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

						$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
						imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

						$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["passphoto"]["type"]));

						//var_dump($response);
						//exit();



						/*	$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;

								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';

								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;

								@mkdir($courseImgPathDir,0777,true);

								@mkdir($courseImgThumbPathDir,0777,true);								

								parent::create_thumb_img($_FILES["passphoto"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;

								parent::create_thumb_img($courseImgPathFile, $courseImgThumbPathFile,  $ext, 300, 280);		*/
					}

					if ($photoidproof != '') {

						$ext 			= pathinfo($_FILES["photoidproof"]["name"], PATHINFO_EXTENSION);

						$file_name 		= INST_PHOTO_PROOF . '_' . mt_rand(0, 123456789) . '.' . $ext;

						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON)";

						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_PHOTO_PROOF . "','1',0,'$created_by',NOW())";

						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);

						$exec3   		= parent::execQuery($insertSql3);

						$s3_obj = new S3Class();
						$activityContent = $_FILES['photoidproof']['name'];
						$fileTempName = $_FILES['photoidproof']['tmp_name'];
						$new_width = 800;
						$new_height = 750;
						$image_p = imagecreatetruecolor($new_width, $new_height);
						$image = imagecreatefromstring(file_get_contents($fileTempName));
						imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

						$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
						imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

						$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["photoidproof"]["type"]));

						//var_dump($response);
						//exit();



						/*	$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;

								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';

								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;

								@mkdir($courseImgPathDir,0777,true);

								@mkdir($courseImgThumbPathDir,0777,true);								

								parent::create_thumb_img($_FILES["photoidproof"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;

								parent::create_thumb_img($courseImgPathFile, $courseImgThumbPathFile,  $ext, 300, 280);		*/
					}

					if ($instregcertificate != '') {

						$ext 			= pathinfo($_FILES["instregcertificate"]["name"], PATHINFO_EXTENSION);

						$file_name 		= INST_REG_CERTIFICATE . '_' . mt_rand(0, 123456789) . '.' . $ext;

						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON)";

						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_REG_CERTIFICATE . "','1',0,'$created_by',NOW())";

						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);

						$exec3   		= parent::execQuery($insertSql3);

						$s3_obj = new S3Class();
						$activityContent = $_FILES['instregcertificate']['name'];
						$fileTempName = $_FILES['instregcertificate']['tmp_name'];
						$new_width = 800;
						$new_height = 750;
						$image_p = imagecreatetruecolor($new_width, $new_height);
						$image = imagecreatefromstring(file_get_contents($fileTempName));
						imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

						$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
						imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

						$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["instregcertificate"]["type"]));

						//var_dump($response);
						//exit();



						/*	$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;

								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';

								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;

								@mkdir($courseImgPathDir,0777,true);

								@mkdir($courseImgThumbPathDir,0777,true);								

								parent::create_thumb_img($_FILES["instregcertificate"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;

								parent::create_thumb_img($courseImgPathFile, $courseImgThumbPathFile,  $ext, 300, 280);									
*/
					}

					if ($educationalproof != '') {

						$ext 			= pathinfo($_FILES["educationalproof"]["name"], PATHINFO_EXTENSION);

						$file_name 		= INST_EDU_DOCS . '_' . mt_rand(0, 123456789) . '.' . $ext;

						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON)";

						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_EDU_DOCS . "','1',0,'$created_by',NOW())";

						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);

						$exec3   		= parent::execQuery($insertSql3);

						$s3_obj = new S3Class();
						$activityContent = $_FILES['educationalproof']['name'];
						$fileTempName = $_FILES['educationalproof']['tmp_name'];
						$new_width = 800;
						$new_height = 750;
						$image_p = imagecreatetruecolor($new_width, $new_height);
						$image = imagecreatefromstring(file_get_contents($fileTempName));
						imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

						$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
						imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

						$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["educationalproof"]["type"]));

						//var_dump($response);
						//exit();



						/*$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;

								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';

								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;

								@mkdir($courseImgPathDir,0777,true);

								@mkdir($courseImgThumbPathDir,0777,true);								

								parent::create_thumb_img($_FILES["educationalproof"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;

								parent::create_thumb_img($courseImgPathFile, $courseImgThumbPathFile,  $ext, 300, 280);									
*/
					}

					if ($profcourseproof != '') {

						$ext 			= pathinfo($_FILES["profcourseproof"]["name"], PATHINFO_EXTENSION);

						$file_name 		= INST_PROF_COURSE_DOCS . '_' . mt_rand(0, 123456789) . '.' . $ext;

						$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON)";

						$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_PROF_COURSE_DOCS . "','1',0,'$created_by',NOW())";

						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);

						$exec3   		= parent::execQuery($insertSql3);

						$s3_obj = new S3Class();
						$activityContent = $_FILES['profcourseproof']['name'];
						$fileTempName = $_FILES['profcourseproof']['tmp_name'];
						$new_width = 800;
						$new_height = 750;
						$image_p = imagecreatetruecolor($new_width, $new_height);
						$image = imagecreatefromstring(file_get_contents($fileTempName));
						imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

						$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
						imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

						$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["profcourseproof"]["type"]));

						//var_dump($response);
						//exit();



						/*$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;

								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';

								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;

								@mkdir($courseImgPathDir,0777,true);

								@mkdir($courseImgThumbPathDir,0777,true);								

								parent::create_thumb_img($_FILES["profcourseproof"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;

								parent::create_thumb_img($courseImgPathFile, $courseImgThumbPathFile,  $ext, 300, 280);									
*/
					}

					if ($instphotos != '') {

						while (list($key, $value) = each($_FILES["instphotos"]["name"])) {

							$cover_image		= $_FILES["instphotos"]["name"][$key];

							//if product record is not blank

							if ($cover_image != '') {

								$ext 			= pathinfo($_FILES["instphotos"]["name"][$key], PATHINFO_EXTENSION);

								$file_name 		= INST_OTHER_PHOTOS . '_' . mt_rand(0, 123456789) . '.' . $ext;

								$tabFields3 	= "(FILE_ID,INSTITUTE_ID,FILE_NAME,FILE_LABEL,ACTIVE,VERIFIED,CREATED_BY,CREATED_ON)";

								$insertVals3	= "(NULL, '$last_insert_id', '$file_name','" . INST_OTHER_PHOTOS . "','1',0,'$created_by',NOW())";

								$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);

								$exec3   		= parent::execQuery($insertSql3);

								$s3_obj = new S3Class();
								$activityContent = $_FILES['instphotos']['name'][$key];
								$fileTempName = $_FILES['instphotos']['tmp_name'][$key];
								$new_width = 800;
								$new_height = 750;
								$image_p = imagecreatetruecolor($new_width, $new_height);
								$image = imagecreatefromstring(file_get_contents($fileTempName));
								imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

								$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
								imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

								$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["instphotos"]["type"][$key]));

								//var_dump($response);
								//exit();



								/*$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;

										$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';

										$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;

										@mkdir($courseImgPathDir,0777,true);

										@mkdir($courseImgThumbPathDir,0777,true);								

										parent::create_thumb_img($_FILES["instphotos"]["tmp_name"][$key], $courseImgPathFile,  $ext, 800, 750) ;

										parent::create_thumb_img($courseImgPathFile, $courseImgThumbPathFile,  $ext, 300, 280);	*/
							}
						}
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

	/* generate institute code */

	public function generate_institute_code()

	{

		$code = '';

		$code = parent::getRandomCode();

		$sql = "SELECT INSTITUTE_CODE FROM institute_details WHERE INSTITUTE_CODE='$code'";

		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {

			$this->generate_institute_code();
		}

		return $code;
	}

	/* validate institute code */

	public function validate_institute_code($code)

	{

		$sql = "SELECT INSTITUTE_CODE FROM institute_details WHERE INSTITUTE_CODE='$code'";

		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {

			return false;
		}

		return true;
	}

	/* add new staff in institute 

	@param: 

	@return: json

	*/

	public function update_institute_staff()

	{

		$errors = array();  // array to hold validation errors

		$data = array();        // array to pass back data



		$action 		= isset($_POST['update_staff']) ? $_POST['update_staff'] : '';

		$login_id 	= parent::test(isset($_POST['login_id']) ? $_POST['login_id'] : '');

		$staff_id 	= parent::test(isset($_POST['staff_id']) ? $_POST['staff_id'] : '');

		$institute_id	= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : '');

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

		$status 		= isset($_POST['status']) ? $_POST['status'] : '';

		$staff_photo = isset($_FILES['staff_photo']['name']) ? $_FILES['staff_photo']['name'] : '';

		$institute_id = $_SESSION['user_id'];

		$role 		= 5; //institute staff;

		$updated_by  	= $_SESSION['user_name'];



		/* check validations */

		if ($dob != '')

			$dob = date("Y-m-d", strtotime($dob));

		if ($fullname == '')

			$errors['fullname'] = 'Fullname is required.';

		if ($email == '')

			$errors['email'] = 'Email is required.';

		if ($mobile == '')

			$errors['mobile'] = 'Mobile number is required.';

		if ($uname == '')

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



		if ($staff_photo != '') {

			$allowed_ext = array('jpg', 'jpeg');

			$extension = pathinfo($staff_photo, PATHINFO_EXTENSION);

			if (!in_array($extension, $allowed_ext)) {

				$errors['staff_photo'] = 'Please select only JPG format file';
			}
		}

		if (! empty($errors)) {

			// if there are items in our errors array, return those errors

			$data['success'] = false;

			$data['errors']  = $errors;

			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();

			$tableName 	= "institute_staff_details";

			$setValues 	= "STAFF_FULLNAME='$fullname', STAFF_GENDER='$gender', STAFF_DOB='$dob',STAFF_EMAIL='$email',STAFF_MOBILE='$mobile', STAFF_TEMP_ADDRESS='$temp_add',STAFF_PER_ADDRESS='$per_add',STAFF_CITY='$city',STAFF_STATE='$state', STAFF_PINCODE='$pincode', STAFF_EDUCATION='$qualification',STAFF_DESIGNATION='$designation', ACTIVE='$status', UPDATED_BY='$updated_by', UPDATED_ON=NOW()";

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



						$updProspSql 	= "UPDATE institute_staff_details SET STAFF_PHOTO='$file_name' WHERE  STAFF_ID='$staff_id'";

						$exec   	= parent::execQuery($updProspSql);



						//$courseImgPathDir 		= 	INSTITUTE_STAFF_PHOTO_PATH.'/'.$staff_id.'/';

						$bucket_directory = 'institute/staff/' . $staff_id . '/';

						$s3_obj = new S3Class();
						$activityContent = $_FILES['staff_photo']['name'];
						$fileTempName = $_FILES['staff_photo']['tmp_name'];
						$new_width = 800;
						$new_height = 750;
						$image_p = imagecreatetruecolor($new_width, $new_height);
						$image = imagecreatefromstring(file_get_contents($fileTempName));
						imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, imagesx($image), imagesy($image));

						$newFielName = tempnam(null, null); // take a llok at the tempnam and adjust parameters if needed
						imagepng($image_p, $newFielName, 9); // use $newFielName in putObjectFile()

						$response = $s3_obj->putObjectFile($newFielName, BUCKET_NAME, $bucket_directory . '' . $file_name, S3::ACL_PUBLIC_READ, array(), array("Content-Type" => $_FILES["staff_photo"]["type"]));

						//var_dump($response);
						//exit();

						/*$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;

								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';

								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;

								@mkdir($courseImgPathDir,0777,true);

								@mkdir($courseImgThumbPathDir,0777,true);

								

								parent::create_thumb_img($_FILES["staff_photo"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;

								parent::create_thumb_img($courseImgPathFile, $courseImgThumbPathFile,  $ext, 300, 280) ;*/
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

	public function list_institute_staff($staff_id = '', $institute_id = '')

	{

		$data = '';

		$sql = "SELECT A.*, DATE_FORMAT(A.STAFF_DOB, '%d-%m-%Y') AS STAFF_DOB_FORMATED, B.USER_NAME, B.USER_LOGIN_ID FROM institute_staff_details A LEFT JOIN user_login_master B ON A.STAFF_ID=B.USER_ID AND B.USER_ROLE=5 WHERE 1 ";

		if ($staff_id != '') {

			$sql .= " AND A.STAFF_ID='$staff_id' ";
		}

		if ($institute_id != '') {

			$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
		}

		$sql .= 'ORDER BY CREATED_ON DESC';

		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0)

			$data = $res;

		return $data;
	}

	/* update user 

	@param: int user_id

	@return: true or false

	*/

	public function update_user($user_id)

	{

		$action 		= isset($_POST['update_user']) ? $_POST['update_user'] : '';

		$user_id 		= isset($_POST['user_id']) ? $_POST['user_id'] : '';

		if ($action != '' && $user_id != '') {

			$id			= $user_id;

			$first_name 	= parent::test(isset($_POST['first_name']) ? $_POST['first_name'] : '');

			$last_name 	= parent::test(isset($_POST['last_name']) ? $_POST['last_name'] : '');

			$email 		= parent::test(isset($_POST['email']) ? $_POST['email'] : '');

			$mobile 		= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');

			$description	= parent::test(isset($_POST['description']) ? $_POST['description'] : '');

			$role 		= parent::test(isset($_POST['role']) ? $_POST['role'] : '');

			$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

			$responsibility = isset($_POST['responsibility']) ? $_POST['responsibility'] : '';

			$responsibility_str = '';

			if (!empty($responsibility)) {

				foreach ($responsibility as $value)

					$responsibility_str .= $value . ",";
			}



			$valid		= true;

			$updated_by  = $_SESSION['user_name'];



			if ($valid) {



				//update user detail



				// start transaction 

				parent::start_transaction();

				$tableName 	= "admin_details_master";

				$setValues 	= "FIRST_NAME='$first_name', LAST_NAME='$last_name', USER_EMAIL='$email',MOBILE='$mobile',DESCRIPTION='$description', UPDATED_BY='$updated_by', UPDATED_ON=NOW()";

				$whereClause = " WHERE ADMIN_DETAIL_ID='$id'";

				$updateSql	= parent::updateData($tableName, $setValues, $whereClause);

				$exSql		=  parent::execQuery($updateSql);



				// insert responsibilities details

				if (!empty($responsibility)) {

					//delete all the records for admin details id

					$sqlDel = "DELETE FROM admin_resposibility_details WHERE ADMIN_DETAIL_ID='$id'";

					parent::execQuery($sqlDel);

					foreach ($responsibility as $value) {

						$responsibility_str .= $value . ",";



						$tableName1 	= "admin_resposibility_details";

						$tabFields1 	= "(ADMIN_RESPONSIBILITY_DETAIL_ID, ADMIN_DETAIL_ID, RESPOSIBILITY, CREATED_BY,CREATED_ON)";

						$insertVals1	= "(NULL, '$id', '$value', '$updated_by',NOW())";

						$insertSql1	= parent::insertData($tableName1, $tabFields1, $insertVals1);

						$exSql1		=  parent::execQuery($insertSql1);
					}
				}

				if ($exSql) {

					// update login details



					$tableName2 	= "admin_login_master";

					$setValues2 	= "ADMIN_STATUS='$status', ROLE_ID='$role', UPDATED_BY='$updated_by',UPDATED_ON=NOW()";

					$whereClause2	= " WHERE ADMIN_DETAIL_ID='$id'";

					$updateSql2	= parent::updateData($tableName2, $setValues2, $whereClause2);

					$exSql2		= parent::execQuery($updateSql2);

					if ($exSql2) {

						// update resposibilities



						if ($responsibility_str != '') {

							$responsibility_str = rtrim($responsibility_str, ',');

							$tableName3 	= "admin_view_mapping";

							$setValues3 	= "ADMIN_ID='$id',ROLE_ID='$role',RESPONSIBILTY_ID='$responsibility_str', UPDATED_BY='$updated_by',UPDATED_ON=NOW()";

							$whereClause3	= " WHERE ADMIN_ID='$id'";

							$updateSql3	= parent::updateData($tableName3, $setValues3, $whereClause3);

							$exSql3		= parent::execQuery($updateSql3);
						}



						parent::commit();

						$msg = UPDATE_USER_SUCCESS . " $first_name $last_name ";

						parent::add_activity("UPDATE_USER_SUCCESS", $msg);

						return true;
					} else {

						parent::rollback();

						$msg = UPDATE_USER_FAILED . " $first_name $last_name ";

						parent::add_activity("UPDATE_USER_FAILED", $msg);

						return false;
					}
				}
			}
		}
	}

	/* show user details 

	@param: int user_id

	@return mixed

	*/

	public function view_user($user_id)

	{

		$data = '';

		$user_id = parent::test($user_id);

		$sql = "SELECT A.*, B.USER_NAME,B.ADMIN_STATUS, B.ROLE_ID FROM admin_details_master A LEFT JOIN admin_login_master B ON A.ADMIN_DETAIL_ID=B.ADMIN_DETAIL_ID WHERE A.ADMIN_DETAIL_ID='$user_id' LIMIT 0,1";

		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {

			$data = $res;
		}

		return $data;
	}

	/* show all the frontend users by list*/

	public function list_users_profiles()

	{

		$role_id = $_SESSION['role_id'];

		$output = '';

		$minDate = isset($_POST['minDate']) ? $_POST['minDate'] : '';

		$maxDate = isset($_POST['maxDate']) ? $_POST['maxDate'] : '';

		if ($minDate != '')

			$minDate = date('Y-m-d', strtotime($minDate));

		if ($maxDate != '')

			$maxDate = date('Y-m-d', strtotime($maxDate));



		$sql = "SELECT A.APP_USER_ID, A.APP_USER_DETAILS_ID,C.APP_USER_LOGIN_ID, CONCAT(A.FIRST_NAME,' ',A.LAST_NAME) AS NAME,D.USER_TYPE,  B.MOBILE,B.EMAIL,B.POSTALCODE,DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y') AS CREATED_DATE, C.ACTIVE FROM app_users A LEFT JOIN app_users_details B ON A.APP_USER_DETAILS_ID=B.APP_USERS_DETAIL_ID LEFT JOIN app_users_login_master C ON A.APP_USER_ID=C.APP_USER_ID LEFT JOIN app_users_type_master D ON A.USER_TYPE_ID=D.USER_TYPE_ID  WHERE 1";



		$where = "";

		if ($minDate != '' && $maxDate == '')

			$where .=  " AND A.CREATED_ON >= '$minDate'";

		if ($maxDate != '' && $minDate == '')

			$where .=  " AND A.CREATED_ON <= '$maxDate'";

		if ($maxDate != '' && $minDate != '')

			$where .=  " AND A.CREATED_ON BETWEEN '$minDate' AND '$maxDate'";

		$where .= " ORDER BY A.CREATED_ON DESC";



		$sql .= $where;

		$exc = parent::execQuery($sql);



		if ($exc->num_rows > 0) {

			while ($data = $exc->fetch_assoc()) {

				$APP_USER_ID 			= $data['APP_USER_ID'];

				$APP_USER_DETAILS_ID	= $data['APP_USER_DETAILS_ID'];

				$APP_USER_LOGIN_ID 		= $data['APP_USER_LOGIN_ID'];

				$NAME 					= $data['NAME'];



				$USER_TYPE 				= $data['USER_TYPE'];

				$MOBILE 				= $data['MOBILE'];

				$EMAIL 					= $data['EMAIL'];

				$POSTALCODE 			= $data['POSTALCODE'];

				$CREATED_DATE 			= $data['CREATED_DATE'];

				$ACTIVE 				= $data['ACTIVE'];

				if ($ACTIVE == 0)

					$ACTIVE = 'Inactive';

				elseif ($ACTIVE == 1)

					$ACTIVE = 'Active';

				//$action					= '<a href="page.php?p=update-user&id='.$ADMIN_DETAIL_ID.'">Edit</a>';



				$delete_action = '<a href="javascript:void(0);" id="del_' . $APP_USER_ID . '" onclick="deleteUser(this.id)">Delete</a>';



				$action					= '<a href="#">View</a> |' . $delete_action;



				$output .= '<tr class="odd gradeX">

					<td>' . $NAME . '</td>

					<td>' . $EMAIL . '</td>

					<td>' . $MOBILE . '</td>

					<td>' . $POSTALCODE . '</td>

					<td>' . $USER_TYPE . '</td>

					<td>' . $CREATED_DATE . '</td>

					<td class="center">' . $ACTIVE . '</td>

					<td class="center">' . $action . '</td>

				</tr>';
			}
		}

		return $output;
	}

	/* get users all table IDs using app_user_id*/

	public function getIDs($user_id)

	{

		$result = array();

		$sql = "SELECT A.APP_USERS_DETAIL_ID,B.APP_USER_ID,C.APP_USER_LOGIN_ID FROM app_users_details A LEFT JOIN  app_users B ON A.APP_USERS_DETAIL_ID=B.APP_USER_DETAILS_ID LEFT JOIN app_users_login_master C ON B.APP_USER_ID=C.APP_USER_ID WHERE B.APP_USER_ID= '$user_id'";

		$res = parent::execQuery($sql);

		if ($res) {

			if ($res->num_rows > 0) {

				while ($data = $res->fetch_assoc()) {

					$APP_USERS_DETAIL_ID = $data['APP_USERS_DETAIL_ID'];

					$APP_USER_ID		 = $data['APP_USER_ID'];

					$APP_USER_LOGIN_ID	 = $data['APP_USER_LOGIN_ID'];



					$result = array("APP_USERS_DETAIL_ID" => $APP_USERS_DETAIL_ID, "APP_USER_ID" => $APP_USER_ID, "APP_USER_LOGIN_ID" => $APP_USER_LOGIN_ID);



					/*

					array_push($result, "APP_USERS_DETAIL_ID"=>$APP_USERS_DETAIL_ID);

					array_push($result, "APP_USER_ID"=>$APP_USER_ID);

					array_push($result, "APP_USER_LOGIN_ID"=>$APP_USER_LOGIN_ID);

					*/
				}
			}
		}

		return $result;
	}

	/* delete the user */

	public function delete_user($user_id)

	{

		$res = '';

		$ids = $this->getIDs($user_id);

		if (!empty($ids)) {

			$APP_USERS_DETAIL_ID = $ids['APP_USERS_DETAIL_ID'];

			$APP_USER_ID		 = $ids['APP_USER_ID'];

			$APP_USER_LOGIN_ID	 = $ids['APP_USER_LOGIN_ID'];



			$sql1 = "DELETE FROM app_users_details WHERE APP_USERS_DETAIL_ID= '$APP_USERS_DETAIL_ID';";

			$sql2 = "DELETE FROM app_users  WHERE APP_USER_ID= '$APP_USER_ID';";

			$sql3 = "DELETE FROM app_users_login_master WHERE APP_USER_LOGIN_ID= '$APP_USER_LOGIN_ID';";

			$ex1 = parent::execQuery($sql1);

			$ex2 = parent::execQuery($sql2);

			$ex3 = parent::execQuery($sql3);

			if ($ex1 && $ex2 && $ex3) {

				return true;
			}
		}

		return false;
	}
}
