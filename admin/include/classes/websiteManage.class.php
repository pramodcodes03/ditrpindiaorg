<?php
include_once('database_results.class.php');
include_once('access.class.php');

class websiteManage extends access
{
	public function list_student_website_enquiry($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*,get_course_title_modify(B.COURSE_ID) as course_name FROM student_website_enquiry A LEFT JOIN courses B ON A.course_id = B.COURSE_ID WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//list admission
	public function list_student_admission($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*,get_course_title_modify(B.COURSE_ID) as course_name FROM student_admission A LEFT JOIN courses B ON A.course_id = B.COURSE_ID WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function list_job_apply_student($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*,B.title as job_name FROM job_apply_student A LEFT JOIN job_updates B ON A.job_id = B.id WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//logo section
	public function list_logo($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM logo_management A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_logo($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');
		$title 		= parent::test(isset($_POST['title']) ? $_POST['title'] : '');
		$logoimg 		= isset($_FILES['logoimg']['name']) ? $_FILES['logoimg']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($logoimg == '') {
			$errors['logoimg'] = 'Logo Is Required!';
		}

		if ($logoimg != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($logoimg, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['logoimg'] = 'Invalid file format! Please select valid image file.';
			}
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "logo_management";
			$setValues 	= "name='$title', updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= LOGO_PATH . '/' . $id . '/';

				if ($logoimg != '') {
					$ext 			= pathinfo($_FILES["logoimg"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $title . '_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["logoimg"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["logoimg"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function add_logo()
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$title 		= parent::test(isset($_POST['title']) ? $_POST['title'] : '');
		$logoimg 		= isset($_FILES['logoimg']['name']) ? $_FILES['logoimg']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($logoimg == '') {
			$errors['logoimg'] = 'Logo Is Required!';
		}

		if ($logoimg != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($logoimg, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['logoimg'] = 'Invalid file format! Please select valid image file.';
			}
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "logo_management";
			$tabFields 	= "(id, name, active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$title','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= LOGO_PATH . '/' . $last_insert_id . '/';

				if ($logoimg != '') {
					$ext 			= pathinfo($_FILES["logoimg"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $title . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//slider section
	public function add_slider()
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$bannerimg 		= isset($_FILES['bannerimg']['name']) ? $_FILES['bannerimg']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($bannerimg == '') {
			$errors['bannerimg'] = 'Banner Image Is Required!';
		}

		if ($bannerimg != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($bannerimg, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['bannerimg'] = 'Invalid file format! Please select valid image file.';
			}
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "slider";
			$tabFields 	= "(CREATED_BY, CREATED_ON, DELETE_FLAG	)";
			$insertVals	= "('$created_by', NOW(),'0')";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= SLIDERIMAGE_PATH . '/' . $last_insert_id . '/';

				if ($bannerimg != '') {
					$ext 			= pathinfo($_FILES["bannerimg"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'slider' . '_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '.' . $ext;
					$setValues 		= "SLIDER_IMG='$file_name'";
					$whereClause	= " WHERE SLIDER_ID ='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["bannerimg"]["tmp_name"], $courseImgPathFile,  $ext, 1200, 750);
					parent::create_thumb_img($_FILES["bannerimg"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Slider has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function list_slider($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM slider A WHERE A.DELETE_FLAG=0 ";

		if ($id != '') {
			$sql .= " AND A.SLIDER_ID  ='$id ' ";
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

	public function edit_slider($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');
		$bannerimg 		= isset($_FILES['bannerimg']['name']) ? $_FILES['bannerimg']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($bannerimg == '') {
			$errors['bannerimg'] = 'Logo Is Required!';
		}

		if ($bannerimg != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($bannerimg, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['bannerimg'] = 'Invalid file format! Please select valid image file.';
			}
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "slider";
			$setValues 	= "CREATED_BY='$updated_by', CREATED_ON=NOW()";
			$whereClause = " WHERE SLIDER_ID ='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= SLIDERIMAGE_PATH . '/' . $id . '/';

				if ($bannerimg != '') {
					$ext 			= pathinfo($_FILES["bannerimg"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $title . '_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "SLIDER_IMG='$file_name'";
					$whereClause	= " WHERE SLIDER_ID ='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["bannerimg"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["bannerimg"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New slider has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//maqrquee section

	public function edit_marquee($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 				= parent::test(isset($_POST['id']) ? $_POST['id'] : '');
		$marqueetext 		= parent::test(isset($_POST['marqueetext']) ? $_POST['marqueetext'] : '');

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($marqueetext == '') {
			$errors['marqueetext'] = 'Message Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "marquee_tags";
			$setValues 	= "name='$marqueetext', updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! New logo has been added successfully!';
		}

		return json_encode($data);
	}
	public function list_marquee($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM  marquee_tags A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//Testimonial Section

	public function add_testimonial()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');

		$testimonialimage 		= isset($_FILES['testimonialimage']['name']) ? $_FILES['testimonialimage']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($description == '') {
			$errors['description'] = 'Description Is Required!';
		}

		if ($testimonialimage == '') {
			$errors['testimonialimage'] = 'Image Is Required!';
		}

		if ($testimonialimage != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($testimonialimage, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['testimonialimage'] = 'Invalid file format! Please select valid image file.';
			}
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "manage_testimonials";
			$tabFields 	= "(id, name,description, active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','$description','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= TESTIMONIAL_PATH . '/' . $last_insert_id . '/';

				if ($testimonialimage != '') {
					$ext 			= pathinfo($_FILES["testimonialimage"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '_T.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["testimonialimage"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["testimonialimage"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_testimonial($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM manage_testimonials A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_testimonial($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$testimonialimage 		= isset($_FILES['testimonialimage']['name']) ? $_FILES['testimonialimage']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($description == '') {
			$errors['description'] = 'Description Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "manage_testimonials";
			$setValues 	= "name='$name', description='$description',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= TESTIMONIAL_PATH . '/' . $id . '/';

				if ($testimonialimage != '') {
					$ext 			= pathinfo($_FILES["testimonialimage"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["testimonialimage"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["testimonialimage"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}


	//Social Media Links
	public function add_social_links()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$master_id 			= parent::test(isset($_POST['master_id']) ? $_POST['master_id'] : '');
		$link 				= parent::test(isset($_POST['link']) ? $_POST['link'] : '');

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($master_id == '') {
			$errors['master_id'] = 'Please select link type';
		}

		if ($link == '') {
			$errors['link'] = 'Link Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "social_media_links";
			$tabFields 	= "(id, master_id,link, active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$master_id','$link','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function list_social_links($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*,B.image as icon,B.name as socialName FROM social_media_links A LEFT JOIN social_media_master B ON A.master_id = B.id WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	public function edit_social_links($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$master_id 			= parent::test(isset($_POST['master_id']) ? $_POST['master_id'] : '');
		$link 				= parent::test(isset($_POST['link']) ? $_POST['link'] : '');

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($master_id == '') {
			$errors['master_id'] = 'Please select link type';
		}

		if ($link == '') {
			$errors['link'] = 'Link Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "social_media_links";
			$setValues 	= "master_id='$master_id', link='$link',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//Advertise offer

	public function add_advertise()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$link 			= parent::test(isset($_POST['link']) ? $_POST['link'] : '');

		$website 			= parent::test(isset($_POST['website']) ? $_POST['website'] : '');

		$advertiseimage 		= isset($_FILES['advertiseimage']['name']) ? $_FILES['advertiseimage']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($advertiseimage == '') {
			$errors['advertiseimage'] = 'Image Is Required!';
		}

		if ($advertiseimage != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($advertiseimage, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['advertiseimage'] = 'Invalid file format! Please select valid image file.';
			}
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "advertise_popup";
			$tabFields 	= "(id, name,link,website, active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','$link','$website','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= ADVERTISE_PATH . '/' . $last_insert_id . '/';

				if ($advertiseimage != '') {
					$ext 			= pathinfo($_FILES["advertiseimage"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '_T.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["advertiseimage"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["advertiseimage"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Advertise has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_advertise($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM advertise_popup A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_advertise($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$link 			= parent::test(isset($_POST['link']) ? $_POST['link'] : '');
		$website 			= parent::test(isset($_POST['website']) ? $_POST['website'] : '');
		$advertiseimage 		= isset($_FILES['advertiseimage']['name']) ? $_FILES['advertiseimage']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "advertise_popup";
			$setValues 	= "name='$name',link='$link',website='$website',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= ADVERTISE_PATH . '/' . $id . '/';

				if ($advertiseimage != '') {
					$ext 			= pathinfo($_FILES["advertiseimage"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $title . '_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["advertiseimage"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["advertiseimage"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New advertise has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//about section

	public function edit_about($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 					= parent::test(isset($_POST['id']) ? $_POST['id'] : '');
		$about_short 			= parent::test(isset($_POST['about_short']) ? $_POST['about_short'] : '');
		$about_long 			= parent::test(isset($_POST['about_long']) ? $_POST['about_long'] : '');
		$mission_short 		= parent::test(isset($_POST['mission_short']) ? $_POST['mission_short'] : '');
		$mission_long 		= parent::test(isset($_POST['mission_long']) ? $_POST['mission_long'] : '');
		$vision_short 		= parent::test(isset($_POST['vision_short']) ? $_POST['vision_short'] : '');
		$vision_long 			= parent::test(isset($_POST['vision_long']) ? $_POST['vision_long'] : '');

		$homepage_image 		= isset($_FILES['homepage_image']['name']) ? $_FILES['homepage_image']['name'] : '';
		$mission_image 		= isset($_FILES['mission_image']['name']) ? $_FILES['mission_image']['name'] : '';
		$vision_image 		= isset($_FILES['vision_image']['name']) ? $_FILES['vision_image']['name'] : '';


		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "about_us";
			$setValues 	= "about_short='$about_short',about_long='$about_long',mission_short='$mission_short',mission_long='$mission_long',vision_short='$vision_short',vision_long='$vision_long', updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= ABOUTUS_PATH . '/' . $id . '/';

				if ($homepage_image != '') {
					$ext 			= pathinfo($_FILES["homepage_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Homepage_' . mt_rand(0, 123456789) . '.' . $ext;
					$setValues 		= "homepage_image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["homepage_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["homepage_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				if ($mission_image != '') {
					$ext 			= pathinfo($_FILES["mission_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Mission_' . mt_rand(0, 123456789) . '.' . $ext;
					$setValues 		= "mission_image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["mission_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["mission_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				if ($vision_image != '') {
					$ext 			= pathinfo($_FILES["vision_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Vision_' . mt_rand(0, 123456789) . '.' . $ext;
					$setValues 		= "vision_image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["vision_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["vision_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}
	public function list_about($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM  about_us A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//Services Section

	public function add_services()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');

		$servicesimage 		= isset($_FILES['servicesimage']['name']) ? $_FILES['servicesimage']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($description == '') {
			$errors['description'] = 'Description Is Required!';
		}

		if ($servicesimage == '') {
			$errors['servicesimage'] = 'Image Is Required!';
		}

		if ($servicesimage != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($servicesimage, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['servicesimage'] = 'Invalid file format! Please select valid image file.';
			}
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "our_services";
			$tabFields 	= "(id, name,description, active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','$description','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= SERVICES_PATH . '/' . $last_insert_id . '/';

				if ($servicesimage != '') {
					$ext 			= pathinfo($_FILES["servicesimage"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '_T.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["servicesimage"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["servicesimage"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_services($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM our_services A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_services($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$servicesimage 		= isset($_FILES['servicesimage']['name']) ? $_FILES['servicesimage']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($description == '') {
			$errors['description'] = 'Description Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "our_services";
			$setValues 	= "name='$name', description='$description',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= SERVICES_PATH . '/' . $id . '/';

				if ($servicesimage != '') {
					$ext 			= pathinfo($_FILES["servicesimage"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["servicesimage"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["servicesimage"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//Our Affiliations
	public function add_affiliations()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');

		$aff_image 		= isset($_FILES['aff_image']['name']) ? $_FILES['aff_image']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($description == '') {
			$errors['description'] = 'Description Is Required!';
		}

		if ($aff_image == '') {
			$errors['aff_image'] = 'Image Is Required!';
		}

		if ($aff_image != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($aff_image, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['aff_image'] = 'Invalid file format! Please select valid image file.';
			}
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "our_affiliations";
			$tabFields 	= "(id, name,description, active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','$description','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= AFFILIATION_PATH . '/' . $last_insert_id . '/';

				if ($aff_image != '') {
					$ext 			= pathinfo($_FILES["aff_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '_T.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["aff_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["aff_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_affiliations($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM our_affiliations A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_affiliations($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$aff_image 		= isset($_FILES['aff_image']['name']) ? $_FILES['aff_image']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($description == '') {
			$errors['description'] = 'Description Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "our_affiliations";
			$setValues 	= "name='$name', description='$description',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= AFFILIATION_PATH . '/' . $id . '/';

				if ($aff_image != '') {
					$ext 			= pathinfo($_FILES["aff_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["aff_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["aff_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//Our Achievers
	public function add_achievers()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$course 			= parent::test(isset($_POST['course']) ? $_POST['course'] : '');

		$achievers_image 		= isset($_FILES['achievers_image']['name']) ? $_FILES['achievers_image']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($description == '') {
			$errors['description'] = 'Description Is Required!';
		}

		if ($achievers_image == '') {
			$errors['achievers_image'] = 'Image Is Required!';
		}

		if ($achievers_image != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($achievers_image, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['achievers_image'] = 'Invalid file format! Please select valid image file.';
			}
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "our_achievers";
			$tabFields 	= "(id, name,description,course, active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','$description','$course','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= ACHIEVERS_PATH . '/' . $last_insert_id . '/';

				if ($achievers_image != '') {
					$ext 			= pathinfo($_FILES["achievers_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '_T.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["achievers_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["achievers_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_achievers($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM our_achievers A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_achievers($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$course 			= parent::test(isset($_POST['course']) ? $_POST['course'] : '');
		$achievers_image 		= isset($_FILES['achievers_image']['name']) ? $_FILES['achievers_image']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($description == '') {
			$errors['description'] = 'Description Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "our_achievers";
			$setValues 	= "name='$name', description='$description',course='$course',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= ACHIEVERS_PATH . '/' . $id . '/';

				if ($achievers_image != '') {
					$ext 			= pathinfo($_FILES["achievers_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["achievers_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["achievers_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//Our Team
	public function add_team()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$designation 			= parent::test(isset($_POST['designation']) ? $_POST['designation'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$position 		= parent::test(isset($_POST['position']) ? $_POST['position'] : '');

		$team_image 		= isset($_FILES['team_image']['name']) ? $_FILES['team_image']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($designation == '') {
			$errors['designation'] = 'Designation Is Required!';
		}

		if ($team_image == '') {
			$errors['team_image'] = 'Image Is Required!';
		}

		if ($team_image != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($team_image, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['team_image'] = 'Invalid file format! Please select valid image file.';
			}
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "our_team";
			$tabFields 	= "(id, name,designation,description,active,delete_flag,created_by,created_at,position)";
			$insertVals	= "(NULL, '$name','$designation','$description','1','0','$created_by',NOW(),'$position')";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= OURTEAM_PATH . '/' . $last_insert_id . '/';

				if ($team_image != '') {
					$ext 			= pathinfo($_FILES["team_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '_T.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["team_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["team_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_team($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM our_team A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_team($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$designation 		= parent::test(isset($_POST['designation']) ? $_POST['designation'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$position 		= parent::test(isset($_POST['position']) ? $_POST['position'] : '');
		$team_image 		= isset($_FILES['team_image']['name']) ? $_FILES['team_image']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($designation == '') {
			$errors['designation'] = 'Designation Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "our_team";
			$setValues 	= "name='$name', description='$description',designation='$designation',updated_by='$updated_by', updated_at=NOW(),position='$position'";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= OURTEAM_PATH . '/' . $id . '/';

				if ($team_image != '') {
					$ext 			= pathinfo($_FILES["team_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["team_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["team_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//Gallery Images
	public function add_galleryImages()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$gallery_image 		= isset($_FILES['gallery_image']['name']) ? $_FILES['gallery_image']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($gallery_image == '') {
			$errors['gallery_image'] = 'Image Is Required!';
		}

		if ($gallery_image != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($gallery_image, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['gallery_image'] = 'Invalid file format! Please select valid image file.';
			}
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "gallery_images";
			$tabFields 	= "(id, name,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= GALLERYIMAGE_PATH . '/' . $last_insert_id . '/';

				if ($gallery_image != '') {
					$ext 			= pathinfo($_FILES["gallery_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '_T.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["gallery_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["gallery_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_galleryImages($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM gallery_images A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_galleryImages($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$gallery_image 		= isset($_FILES['gallery_image']['name']) ? $_FILES['gallery_image']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "gallery_images";
			$setValues 	= "name='$name', updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= GALLERYIMAGE_PATH . '/' . $id . '/';

				if ($gallery_image != '') {
					$ext 			= pathinfo($_FILES["gallery_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["gallery_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["gallery_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//Gallery Videos
	public function add_galleryVideos()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$video 			= parent::test(isset($_POST['video']) ? $_POST['video'] : '');

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($video == '') {
			$errors['video'] = 'Video Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "gallery_video";
			$tabFields 	= "(id, name,video,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','$video','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_galleryVideos($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM gallery_video A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_galleryVideos($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$video 			= parent::test(isset($_POST['video']) ? $_POST['video'] : '');

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}
		if ($video == '') {
			$errors['video'] = 'Video Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "gallery_video";
			$setValues 	= "name='$name',video='$video', updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//contact section
	public function edit_contact($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 					= parent::test(isset($_POST['id']) ? $_POST['id'] : '');
		$email_id 			= parent::test(isset($_POST['email_id']) ? $_POST['email_id'] : '');
		$contact_number1 		= parent::test(isset($_POST['contact_number1']) ? $_POST['contact_number1'] : '');
		$contact_number2 		= parent::test(isset($_POST['contact_number2']) ? $_POST['contact_number2'] : '');
		$address 				= parent::test(isset($_POST['address']) ? $_POST['address'] : '');
		$map 					= parent::test(isset($_POST['map']) ? $_POST['map'] : '');

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "contact_details";
			$setValues 	= "email_id='$email_id',contact_number1='$contact_number1',contact_number2='$contact_number2',address='$address',map='$map',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}
	public function list_contact($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM  contact_details A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//policy section
	public function edit_policy($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 					= parent::test(isset($_POST['id']) ? $_POST['id'] : '');
		$terms_condition 		= parent::test(isset($_POST['terms_condition']) ? $_POST['terms_condition'] : '');
		$privacy_policies 	= parent::test(isset($_POST['privacy_policies']) ? $_POST['privacy_policies'] : '');
		$disclaimer 			= parent::test(isset($_POST['disclaimer']) ? $_POST['disclaimer'] : '');
		$refund_policy 		= parent::test(isset($_POST['refund_policy']) ? $_POST['refund_policy'] : '');

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "our_policies";
			$setValues 	= "terms_condition='$terms_condition',privacy_policies='$privacy_policies',disclaimer='$disclaimer',refund_policy='$refund_policy',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}
	public function list_policy($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM  our_policies A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//Job Post Section

	public function add_jobpost()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$job_code 		= parent::test(isset($_POST['job_code']) ? $_POST['job_code'] : '');
		$title 			= parent::test(isset($_POST['title']) ? $_POST['title'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$skills 			= parent::test(isset($_POST['skills']) ? $_POST['skills'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$post_date 		= parent::test(isset($_POST['post_date']) ? $_POST['post_date'] : '');
		$last_date 		= parent::test(isset($_POST['last_date']) ? $_POST['last_date'] : '');

		$job_image 		= isset($_FILES['job_image']['name']) ? $_FILES['job_image']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($job_code == '') {
			$errors['job_code'] = 'Job Code Is Required!';
		}

		if ($title == '') {
			$errors['title'] = 'Title Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "job_updates";
			$tabFields 	= "(id, job_code,title,description,skills,post_date,last_date, active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$job_code','$title','$description','$skills','$post_date','$last_date','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= JOBPOST_PATH . '/' . $last_insert_id . '/';

				if ($job_image != '') {
					$ext 			= pathinfo($_FILES["job_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Jobpost_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '_T.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["job_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["job_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_jobpost($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM job_updates A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_jobpost($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$job_code 		= parent::test(isset($_POST['job_code']) ? $_POST['job_code'] : '');
		$title 			= parent::test(isset($_POST['title']) ? $_POST['title'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$skills 			= parent::test(isset($_POST['skills']) ? $_POST['skills'] : '');
		$description 		= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$post_date 		= parent::test(isset($_POST['post_date']) ? $_POST['post_date'] : '');
		$last_date 		= parent::test(isset($_POST['last_date']) ? $_POST['last_date'] : '');

		$job_image 		= isset($_FILES['job_image']['name']) ? $_FILES['job_image']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($job_code == '') {
			$errors['job_code'] = 'Job Code Is Required!';
		}

		if ($title == '') {
			$errors['title'] = 'Title Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "job_updates";
			$setValues 	= "job_code='$job_code',title='$title',description='$description',skills='$skills',post_date='$post_date',last_date='$last_date',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= JOBPOST_PATH . '/' . $id . '/';

				if ($job_image != '') {
					$ext 			= pathinfo($_FILES["job_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Jobpost_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["job_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["job_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	//our course 
	public function list_courses($course_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*, (SELECT B.AWARD FROM course_awards B WHERE B.AWARD_ID=A.COURSE_AWARD) AS COURSE_AWARD_NAME FROM courses A WHERE A.DELETE_FLAG=0 ";

		if ($course_id != '') {
			$sql .= " AND A.COURSE_ID='$course_id' ";
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

	//multisubject courses
	public function list_courses_multi_sub($course_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*, (SELECT B.AWARD FROM course_awards B WHERE B.AWARD_ID=A.MULTI_SUB_COURSE_AWARD) AS COURSE_AWARD_NAME FROM multi_sub_courses A WHERE A.DELETE_FLAG=0 ";

		if ($course_id != '') {
			$sql .= " AND A.MULTI_SUB_COURSE_ID='$course_id' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//delete Data Functions
	public function delete_slider($id)
	{
		$sql = "UPDATE slider SET DELETE_FLAG=1 WHERE SLIDER_ID='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_testimonial($id)
	{
		$sql = "UPDATE manage_testimonials SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_socialinks($id)
	{
		$sql = "UPDATE social_media_links SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_advertise($id)
	{
		$sql = "UPDATE advertise_popup SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_courses($id)
	{
		$sql = "UPDATE courses SET DELETE_FLAG = 1 WHERE COURSE_ID='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_services($id)
	{
		$sql = "UPDATE our_services SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_affiliations($id)
	{
		$sql = "UPDATE our_affiliations SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_achievers($id)
	{
		$sql = "UPDATE our_achievers SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_team($id)
	{
		$sql = "UPDATE our_team SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_galleryImages($id)
	{
		$sql = "UPDATE gallery_images SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_galleryVideos($id)
	{
		$sql = "UPDATE gallery_video SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_jobupdate($id)
	{
		$sql = "UPDATE job_updates SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_verification($id)
	{
		$sql = "UPDATE website_student_verification SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_blog($id)
	{
		$sql = "UPDATE our_blogs SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_partner($id)
	{
		$sql = "UPDATE our_partners SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_sample_certificate($id)
	{
		$sql = "UPDATE sample_certificates SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_payment($id)
	{
		$sql = "UPDATE institute_payment SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function delete_download_material($id)
	{
		$sql = "UPDATE download_materials SET delete_flag = 1 WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	//Verification Section
	public function add_verification()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$title 			= parent::test(isset($_POST['title']) ? $_POST['title'] : '');
		$link 			= parent::test(isset($_POST['link']) ? $_POST['link'] : '');

		$verification_image 		= isset($_FILES['verification_image']['name']) ? $_FILES['verification_image']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($title == '') {
			$errors['title'] = 'Title Is Required!';
		}

		if ($link == '') {
			$errors['link'] = 'Link Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "website_student_verification";
			$tabFields 	= "(id, title,link,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$title','$link','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= VERIFICATION_PATH . '/' . $last_insert_id . '/';

				if ($verification_image != '') {
					$ext 			= pathinfo($_FILES["verification_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Verification_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["verification_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["verification_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Verification has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_verification($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM website_student_verification A WHERE A.delete_flag = 0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_verification($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 				= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$title 			= parent::test(isset($_POST['title']) ? $_POST['title'] : '');
		$link 			= parent::test(isset($_POST['link']) ? $_POST['link'] : '');

		$verification_image 		= isset($_FILES['verification_image']['name']) ? $_FILES['verification_image']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($title == '') {
			$errors['title'] = 'Title Is Required!';
		}

		if ($link == '') {
			$errors['link'] = 'Link Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "website_student_verification";
			$setValues 	= "title='$title',link='$link',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= VERIFICATION_PATH . '/' . $id . '/';

				if ($verification_image != '') {
					$ext 			= pathinfo($_FILES["verification_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Verification_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["verification_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["verification_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Verification has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//Blogs Section

	public function add_blogs()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$description 			= parent::test(isset($_POST['description']) ? $_POST['description'] : '');

		$blog_image 		= isset($_FILES['blog_image']['name']) ? $_FILES['blog_image']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($description == '') {
			$errors['description'] = 'Description Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "our_blogs";
			$tabFields 	= "(id, name,description,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','$description','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= BLOGS_PATH . '/' . $last_insert_id . '/';

				if ($blog_image != '') {
					$ext 			= pathinfo($_FILES["blog_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'blogs_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["blog_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["blog_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Blogs has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_blogs($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM our_blogs A WHERE A.delete_flag = 0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_blogs($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 				= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$description 			= parent::test(isset($_POST['description']) ? $_POST['description'] : '');

		$blog_image 		= isset($_FILES['blog_image']['name']) ? $_FILES['blog_image']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */
		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($description == '') {
			$errors['description'] = 'Description Is Required!';
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "our_blogs";
			$setValues 	= "name='$name',description='$description',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= BLOGS_PATH . '/' . $id . '/';

				if ($blog_image != '') {
					$ext 			= pathinfo($_FILES["blog_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Blogs_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["blog_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["blog_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Blog has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//Partners Section

	public function add_partners()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name 				= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$partner_image 		= isset($_FILES['partner_image']['name']) ? $_FILES['partner_image']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "our_partners";
			$tabFields 	= "(id, name,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= PARTNERS_PATH . '/' . $last_insert_id . '/';

				if ($partner_image != '') {
					$ext 			= pathinfo($_FILES["partner_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Partner_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["partner_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["partner_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Partner has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the partner.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_partners($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM our_partners A WHERE A.delete_flag = 0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_partners($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 				= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$partner_image 		= isset($_FILES['partner_image']['name']) ? $_FILES['partner_image']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */
		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "our_partners";
			$setValues 	= "name='$name',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= PARTNERS_PATH . '/' . $id . '/';

				if ($partner_image != '') {
					$ext 			= pathinfo($_FILES["partner_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Partner_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["partner_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["partner_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Partner has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//Payments Section

	public function add_payment()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name 				= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$description 			= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$link 				= parent::test(isset($_POST['link']) ? $_POST['link'] : '');

		$payment_image 		= isset($_FILES['payment_image']['name']) ? $_FILES['payment_image']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "institute_payment";
			$tabFields 	= "(id, name,description,link,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','$description','$link','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= PAYMENTS_PATH . '/' . $last_insert_id . '/';

				if ($payment_image != '') {
					$ext 			= pathinfo($_FILES["payment_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Payments_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["payment_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["payment_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Payment has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_payment($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM institute_payment A WHERE A.delete_flag = 0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_payment($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 				= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 				= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$description 			= parent::test(isset($_POST['description']) ? $_POST['description'] : '');
		$link 				= parent::test(isset($_POST['link']) ? $_POST['link'] : '');

		$payment_image 		= isset($_FILES['payment_image']['name']) ? $_FILES['payment_image']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */
		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "institute_payment";
			$setValues 	= "name='$name',description='$description',link='$link',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= PAYMENTS_PATH . '/' . $id . '/';

				if ($payment_image != '') {
					$ext 			= pathinfo($_FILES["payment_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Partner_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["payment_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["payment_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Payment has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	//color section
	public function edit_color($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 					= parent::test(isset($_POST['id']) ? $_POST['id'] : '');
		$header_color 		= parent::test(isset($_POST['header_color']) ? $_POST['header_color'] : '');
		$footer_color 		= parent::test(isset($_POST['footer_color']) ? $_POST['footer_color'] : '');
		$top_header_color 	= parent::test(isset($_POST['top_header_color']) ? $_POST['top_header_color'] : '');
		$address_box_color 	= parent::test(isset($_POST['address_box_color']) ? $_POST['address_box_color'] : '');
		$marquee_color 		= parent::test(isset($_POST['marquee_color']) ? $_POST['marquee_color'] : '');

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "color_management";
			$setValues 	= "header_color='$header_color',footer_color='$footer_color',top_header_color='$top_header_color',address_box_color='$address_box_color',marquee_color='$marquee_color',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Color Code has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}
	public function list_color($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM  color_management A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//page heading background images
	public function edit_headimages($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 					= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$aboutus 		= isset($_FILES['aboutus']['name']) ? $_FILES['aboutus']['name'] : '';
		$courses 		= isset($_FILES['courses']['name']) ? $_FILES['courses']['name'] : '';
		$services 		= isset($_FILES['services']['name']) ? $_FILES['services']['name'] : '';
		$achiever 		= isset($_FILES['achiever']['name']) ? $_FILES['achiever']['name'] : '';
		$gallery 		= isset($_FILES['gallery']['name']) ? $_FILES['gallery']['name'] : '';
		$team 			= isset($_FILES['team']['name']) ? $_FILES['team']['name'] : '';
		$jobs 			= isset($_FILES['jobs']['name']) ? $_FILES['jobs']['name'] : '';
		$verification   = isset($_FILES['verification']['name']) ? $_FILES['verification']['name'] : '';
		$contact 		= isset($_FILES['contact']['name']) ? $_FILES['contact']['name'] : '';
		$policies 		= isset($_FILES['policies']['name']) ? $_FILES['policies']['name'] : '';

		$certificate 	= isset($_FILES['certificate']['name']) ? $_FILES['certificate']['name'] : '';
		$affiliations 	= isset($_FILES['affiliations']['name']) ? $_FILES['affiliations']['name'] : '';

		$download_materials 	= isset($_FILES['download_materials']['name']) ? $_FILES['download_materials']['name'] : '';
		$refund_policy 	= isset($_FILES['refund_policy']['name']) ? $_FILES['refund_policy']['name'] : '';
		$our_blogs 	= isset($_FILES['our_blogs']['name']) ? $_FILES['our_blogs']['name'] : '';
		$term_condition 	= isset($_FILES['term_condition']['name']) ? $_FILES['term_condition']['name'] : '';
		$disclaimer 	= isset($_FILES['disclaimer']['name']) ? $_FILES['disclaimer']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "page_heading_images";
			$setValues 	= "updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= BANNERS_PATH . '/' . $id . '/';

				if ($aboutus != '') {
					$ext 			= pathinfo($_FILES["aboutus"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'About_' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "aboutus='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["aboutus"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["aboutus"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($courses != '') {
					$ext 			= pathinfo($_FILES["courses"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Courses_' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "courses='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["courses"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["courses"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($services != '') {
					$ext 			= pathinfo($_FILES["services"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Services_' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "services='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["services"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["services"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($achiever != '') {
					$ext 			= pathinfo($_FILES["achiever"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Achiever_' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "achiever='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["achiever"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["achiever"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($gallery != '') {
					$ext 			= pathinfo($_FILES["gallery"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Gallery_' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "gallery='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["gallery"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["gallery"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($team != '') {
					$ext 			= pathinfo($_FILES["team"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Team_' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "team='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["team"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["team"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($jobs != '') {
					$ext 			= pathinfo($_FILES["jobs"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Jobs_' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "jobs='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["jobs"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["jobs"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($verification != '') {
					$ext 			= pathinfo($_FILES["verification"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Verification_' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "verification='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["verification"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["verification"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($contact != '') {
					$ext 			= pathinfo($_FILES["contact"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Contact_' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "contact='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["contact"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["contact"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($policies != '') {
					$ext 			= pathinfo($_FILES["policies"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Policies_' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "policies='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["policies"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["policies"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($certificate != '') {
					$ext 			= pathinfo($_FILES["certificate"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Certificate_' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "certificate='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["certificate"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["certificate"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($affiliations != '') {
					$ext 			= pathinfo($_FILES["affiliations"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Affiliations_' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "affiliations='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["affiliations"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["affiliations"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($download_materials != '') {
					$ext 			= pathinfo($_FILES["download_materials"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'DownlaodMaterial' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "download_materials='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["download_materials"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["download_materials"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($refund_policy != '') {
					$ext 			= pathinfo($_FILES["refund_policy"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'RefundPolicy' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "refund_policy='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["refund_policy"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["refund_policy"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($our_blogs != '') {
					$ext 			= pathinfo($_FILES["our_blogs"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'OurPolicy' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "our_blogs='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["our_blogs"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["our_blogs"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($term_condition != '') {
					$ext 			= pathinfo($_FILES["term_condition"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'TermCondition' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "term_condition='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["term_condition"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["term_condition"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				if ($disclaimer != '') {
					$ext 			= pathinfo($_FILES["disclaimer"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Disclaimer' . mt_rand(0, 123456789) . '_images.' . $ext;
					$setValues 		= "disclaimer='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["disclaimer"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["disclaimer"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Header Banner has been added successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}
	public function list_headimages($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM  page_heading_images A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//Download Material Section

	public function add_download_materials()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$title 				= parent::test(isset($_POST['title']) ? $_POST['title'] : '');

		$files 		= isset($_FILES['files']['name']) ? $_FILES['files']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($title == '') {
			$errors['title'] = 'Title Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "download_materials";
			$tabFields 	= "(id, title,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$title','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 	= parent::last_id();
				$courseImgPathDir 	= DOWNLOADMATERIAL_PATH . '/' . $last_insert_id . '/';

				if ($files != '') {

					$ext 			= pathinfo($_FILES["files"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'DownoadMateriall_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '.' . $ext;
					$setValues 		= "files='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@move_uploaded_file($_FILES["files" . $i]["tmp_name"], $courseImgPathFile);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New File has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_download_materials($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM download_materials A WHERE A.delete_flag = 0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_download_materials($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$title 		= parent::test(isset($_POST['title']) ? $_POST['title'] : '');

		$files 		= isset($_FILES['files']['name']) ? $_FILES['files']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */
		if ($title == '') {
			$errors['title'] = 'Title Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "download_materials";
			$setValues 	= "title='$title',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= DOWNLOADMATERIAL_PATH . '/' . $id . '/';

				if ($files != '') {
					$ext 			= pathinfo($_FILES["files"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'DownoadMateriall_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "files='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@move_uploaded_file($_FILES["files" . $i]["tmp_name"], $courseImgPathFile);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Payment has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//slider box
	public function edit_sliderbox($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 					= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$box1_title 			= parent::test(isset($_POST['box1_title']) ? $_POST['box1_title'] : '');
		$box1_desc 			= parent::test(isset($_POST['box1_desc']) ? $_POST['box1_desc'] : '');
		$box2_title 		= parent::test(isset($_POST['box2_title']) ? $_POST['box2_title'] : '');
		$box2_desc 		= parent::test(isset($_POST['box2_desc']) ? $_POST['box2_desc'] : '');
		$box3_title 		= parent::test(isset($_POST['box3_title']) ? $_POST['box3_title'] : '');
		$box3_desc 			= parent::test(isset($_POST['box3_desc']) ? $_POST['box3_desc'] : '');
		$box4_title 			= parent::test(isset($_POST['box4_title']) ? $_POST['box4_title'] : '');
		$box4_desc = parent::test(isset($_POST['box4_desc']) ? $_POST['box4_desc'] : '');

		$box_color1 = parent::test(isset($_POST['box_color1']) ? $_POST['box_color1'] : '');
		$box_color2 = parent::test(isset($_POST['box_color2']) ? $_POST['box_color2'] : '');
		$box_color3 = parent::test(isset($_POST['box_color3']) ? $_POST['box_color3'] : '');
		$box_color4 = parent::test(isset($_POST['box_color4']) ? $_POST['box_color4'] : '');

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "sliderbox";
			$setValues 	= "box1_title='$box1_title',box1_desc='$box1_desc',box2_title='$box2_title',box2_desc='$box2_desc',box3_title='$box3_title',box3_desc='$box3_desc',box4_title='$box4_title', box4_desc='$box4_desc',box_color1='$box_color1', box_color2='$box_color2', box_color3='$box_color3', box_color4='$box_color4',  updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}
	public function list_sliderbox($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM  sliderbox A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	// sample certificates
	public function add_sample_certificates()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name = parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$position = parent::test(isset($_POST['position']) ? $_POST['position'] : '');
		$sample_image 		= isset($_FILES['sample_image']['name']) ? $_FILES['sample_image']['name'] : '';

		$type = parent::test(isset($_POST['type']) ? $_POST['type'] : '');

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "sample_certificates";
			$tabFields 	= "(id, name,position,active,delete_flag,created_by,created_at,type)";
			$insertVals	= "(NULL, '$name','$position','1','0','$created_by',NOW(),'$type')";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= WEBSITES_SAMPLE_CERT_PATH . '/' . $last_insert_id . '/';

				if ($sample_image != '') {
					$ext 			= pathinfo($_FILES["sample_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Sample_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["sample_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["sample_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Sample Certificate has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the partner.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_sample_certificates($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM sample_certificates A WHERE A.delete_flag = 0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.position ASC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_sample_certificates($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 				= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$position = parent::test(isset($_POST['position']) ? $_POST['position'] : '');
		$sample_image 		= isset($_FILES['sample_image']['name']) ? $_FILES['sample_image']['name'] : '';

		$type = parent::test(isset($_POST['type']) ? $_POST['type'] : '');

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */
		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "sample_certificates";
			$setValues 	= "name='$name',position='$position',updated_by='$updated_by', updated_at=NOW(),type='$type'";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= WEBSITES_SAMPLE_CERT_PATH . '/' . $id . '/';

				if ($sample_image != '') {
					$ext 			= pathinfo($_FILES["sample_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'Sample_' . mt_rand(0, 123456789) . '_' . $i . '.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["sample_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["sample_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Sample Certificate has been updated successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	//franchise section
	public function edit_franchise_details($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 					= parent::test(isset($_POST['id']) ? $_POST['id'] : '');
		$details 		= parent::test(isset($_POST['details']) ? $_POST['details'] : '');

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "franchise_details";
			$setValues 	= "details='$details',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Franchise Details has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the Franchise Details.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}
	public function list_franchise_details($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM  franchise_details A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//password
	//contact section
	public function edit_masterpassword($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 					= parent::test(isset($_POST['id']) ? $_POST['id'] : '');
		$wallet_password 			= parent::test(isset($_POST['wallet_password']) ? $_POST['wallet_password'] : '');
		$courier_password 		= parent::test(isset($_POST['courier_password']) ? $_POST['courier_password'] : '');

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "master_password";
			$setValues 	= "wallet_password='$wallet_password',courier_password='$courier_password',updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Password has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the Password.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}
	public function list_masterpassword($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM  master_password A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//rechare offers
	public function add_rechargeoffers()
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		$title		 	= parent::test(isset($_POST['title']) ? $_POST['title'] : '');
		$description 	= parent::test(isset($_POST['description']) ? $_POST['description'] : '');


		$date 	= parent::test(isset($_POST['date']) ? $_POST['date'] : '');
		$end_date 	= parent::test(isset($_POST['end_date']) ? $_POST['end_date'] : '');
		$time 	= parent::test(isset($_POST['time']) ? $_POST['time'] : '');

		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$created_by 	= $_SESSION['user_name'];
		/* ---------------------file uploads------------------------ */
		$event_img		= isset($_FILES["event_imgs"]["name"]) ? $_FILES["event_imgs"]["name"] : '';

		/* validations */
		if ($title == '')
			$errors['title'] = 'Title is required.';
		if ($event_img == '') {
			$errors['event_imgs'] = 'Please upload gallery photos.';
		}

		/* ---------------file uploads----------------------------- */
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//the uplaod of multiple file document
			$tableName  	= 'recharge_offer';
			$tabFields  	= '(id,name, description,date,end_date,time,active,delete_flag, created_by, created_at)';
			$insertValues 	= "(NULL, '$title','$description','$date','$end_date','$time','$status','0','$created_by',NOW())";
			$insert 		= parent::insertData($tableName, $tabFields, $insertValues);
			$exec   		= parent::execQuery($insert);
			//$current_id 	= parent::last_id();	
			if ($exec) {
				/* upload course files */
				$last_insert_id 	= parent::last_id();
				$courseImgPathDir 	= RECHARGEOFFER_PATH . '/' . $last_insert_id . '/';

				if ($event_img != '') {
					$ext 			= pathinfo($_FILES["event_imgs"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'recharge' . '_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id ='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);

					parent::create_thumb_img($_FILES["event_imgs"]["tmp_name"], $courseImgPathFile,  $ext, 1200, 750);
					parent::create_thumb_img($_FILES["event_imgs"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Recharge Offers has been added successfully!';
			}

			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add Recharge Offers.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function update_rechargeoffers()
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		$id		 	= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$title		 	= parent::test(isset($_POST['title']) ? $_POST['title'] : '');
		$description 	= parent::test(isset($_POST['description']) ? $_POST['description'] : '');

		$date 	= parent::test(isset($_POST['date']) ? $_POST['date'] : '');
		$end_date 	= parent::test(isset($_POST['end_date']) ? $_POST['end_date'] : '');
		$time 	= parent::test(isset($_POST['time']) ? $_POST['time'] : '');

		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$created_by 	= $_SESSION['user_name'];
		/* ---------------------file uploads------------------------ */
		$event_img		= isset($_FILES["event_imgs"]["name"]) ? $_FILES["event_imgs"]["name"] : '';

		/* validations */
		if ($title == '')
			$errors['title'] = 'Title is required.';
		// if($event_img=='')	{ $errors['event_imgs'] = 'Please upload gallery photos.'; }		  

		/* ---------------file uploads----------------------------- */
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//the uplaod of multiple file document
			$tableName  	= 'recharge_offer';
			$setValues  	= "name='$title', description='$description',date='$date',end_date='$end_date',time='$time',active='$status', updated_by='$created_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	=      parent::updateData($tableName, $setValues, $whereClause);
			$exec   		= parent::execQuery($updateSql);
			if ($exec) {
				$courseImgPathDir 	= RECHARGEOFFER_PATH . '/' . $id . '/';

				if ($event_img != '') {
					$ext 			= pathinfo($_FILES["event_imgs"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'recharge' . '_' . mt_rand(0, 123456789) . '_' . $id . '.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id ='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);

					parent::create_thumb_img($_FILES["event_imgs"]["tmp_name"], $courseImgPathFile,  $ext, 1200, 750);
					parent::create_thumb_img($_FILES["event_imgs"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Recharge Offers has been added successfully!';
			}

			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add Recharge Offers.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}
	public function list_rechargeoffers($id = '', $cond = '')
	{
		$tableName = "recharge_offer";
		$selVals = "*";
		$whereClause = "WHERE delete_flag=0 ";
		if ($id != '')
			$whereClause .= " AND id='$id'";
		if ($cond != '')
			$whereClause .= " $cond ";

		$whereClause .= ' ORDER BY created_at DESC';
		$sql = parent::selectData($selVals, $tableName, $whereClause);
		$res = parent::execQuery($sql);
		if (!$res) {
			return false;
		}
		return $res;
	}

	public function delete_rechargeoffers($id)
	{
		$sql = "UPDATE recharge_offer SET active='0',delete_flag='1', updated_by='" . $_SESSION['user_fullname'] . "' WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	//recharge request
	public function add_rechargerequest()
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		$wallet_name = parent::test(isset($_POST['wallet_name']) ? $_POST['wallet_name'] : '');

		$title		 	= parent::test(isset($_POST['title']) ? $_POST['title'] : '');
		$amount 	= parent::test(isset($_POST['amount']) ? $_POST['amount'] : '');

		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$inst_id 	= $_SESSION['user_id'];
		$created_by = $_SESSION['user_name'];
		/* ---------------------file uploads------------------------ */

		/* validations */
		if ($wallet_name == '')
			$errors['wallet_name'] = 'Please Select Type.';

		if ($title == '')
			$errors['title'] = 'Title is required.';

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//the uplaod of multiple file document
			$tableName  	= 'recharge_request';
			$tabFields  	= '(id,inst_id, title,amount,status,active,delete_flag, created_by, created_at,wallet_name)';
			$insertValues 	= "(NULL, '$inst_id','$title','$amount','0','$status','0','$created_by',NOW(),'$wallet_name')";
			$insert 		= parent::insertData($tableName, $tabFields, $insertValues);
			$exec   		= parent::execQuery($insert);
			//$current_id 	= parent::last_id();	
			if ($exec) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Recharge Request has been added successfully!';
			}

			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add Recharge Request.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function update_rechargerequest()
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		$id		 	= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$wallet_name = parent::test(isset($_POST['wallet_name']) ? $_POST['wallet_name'] : '');

		$title		 	= parent::test(isset($_POST['title']) ? $_POST['title'] : '');
		$amount 	= parent::test(isset($_POST['amount']) ? $_POST['amount'] : '');

		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$created_by 	= $_SESSION['user_name'];

		/* validations */
		if ($title == '')
			$errors['title'] = 'Title is required.';

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//the uplaod of multiple file document
			$tableName  	= 'recharge_request';
			$setValues  	= "title='$title', amount='$amount',active='$status', updated_by='$created_by', updated_at=NOW(),wallet_name='$wallet_name'";
			$whereClause = " WHERE id='$id'";
			$updateSql	=      parent::updateData($tableName, $setValues, $whereClause);
			$exec   		= parent::execQuery($updateSql);
			if ($exec) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Recharge Request has been added successfully!';
			}

			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add Recharge Request.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}
	public function list_rechargerequest($id = '', $cond = '')
	{
		$tableName = "recharge_request";
		$selVals = "*";
		$whereClause = "WHERE delete_flag=0 ";
		if ($id != '')
			$whereClause .= " AND id='$id'";
		if ($cond != '')
			$whereClause .= " $cond ";

		$whereClause .= ' ORDER BY created_at DESC';
		$sql = parent::selectData($selVals, $tableName, $whereClause);
		$res = parent::execQuery($sql);
		if (!$res) {
			return false;
		}
		return $res;
	}

	public function delete_rechargerequest($id)
	{
		$sql = "UPDATE recharge_request SET active='0',delete_flag='1', updated_by='" . $_SESSION['user_fullname'] . "' WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}
	public function update_rechargerequest_admin()
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		$id		 	= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$title		 	= parent::test(isset($_POST['title']) ? $_POST['title'] : '');
		$amount 	= parent::test(isset($_POST['amount']) ? $_POST['amount'] : '');
		$status1 	= parent::test(isset($_POST['status1']) ? $_POST['status1'] : '');
		$remark 	= parent::test(isset($_POST['remark']) ? $_POST['remark'] : '');

		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$created_by 	= $_SESSION['user_name'];

		/* validations */
		if ($title == '')
			$errors['title'] = 'Title is required.';

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//the uplaod of multiple file document
			$tableName  	= 'recharge_request';
			$setValues  	= "title='$title', amount='$amount',status='$status1',remark='$remark',active='$status', updated_by='$created_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	=      parent::updateData($tableName, $setValues, $whereClause);
			$exec   		= parent::execQuery($updateSql);
			if ($exec) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Recharge Request has been added successfully!';
			}

			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add Recharge Request.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}

	//teacher
	public function add_teacher()
	{
		$errors = array();  // array to hold validation errors
		$data = array();

		$code		 	= parent::test(isset($_POST['code']) ? $_POST['code'] : '');
		$name 	= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$designation		 	= parent::test(isset($_POST['designation']) ? $_POST['designation'] : '');
		$mobile 	= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$email		 	= parent::test(isset($_POST['email']) ? $_POST['email'] : '');

		$photo		= isset($_FILES["photo"]["name"]) ? $_FILES["photo"]["name"] : '';

		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$inst_id 	= $_SESSION['user_id'];
		$created_by = $_SESSION['user_name'];
		/* ---------------------file uploads------------------------ */

		/* validations */
		if ($name == '')
			$errors['name'] = 'Name is required.';

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//the uplaod of multiple file document
			$tableName  	= ' teacher';
			$tabFields  	= '(id,inst_id,code,name,designation,mobile,email,active,delete_flag, created_by, created_at)';
			$insertValues 	= "(NULL, '$inst_id','$code','$name','$designation','$mobile','$email','$status','0','$created_by',NOW())";
			$insert 		= parent::insertData($tableName, $tabFields, $insertValues);
			$exec   		= parent::execQuery($insert);

			if ($exec) {

				$last_insert_id = parent::last_id();
				//if verified them change the username to center code
				//QRCODE	
				include('resources/phpqrcode/qrlib.php');
				$text = TEACHER_QRURL . 'verify_teacher=1&id=' . $last_insert_id;
				$path = 'resources/TeacherQR/' . $last_insert_id . '/';
				if (!file_exists($path)) {
					@mkdir($path, 0777, true);
				}
				$file = $path . uniqid() . ".png";
				$ecc = 'L';
				$pixel_Size = 100;
				$frame_Size = 100;
				QRcode::png($text, $file, $ecc, $pixel_Size, $frame_size);

				$sqlQR = "UPDATE teacher SET qr_file = '$file' WHERE id='$last_insert_id'";
				$exSqlQR = parent::execQuery($sqlQR);
				////////////////////////////////////////////////////////////

				$courseImgPathDir 	= TEACHERPHOTO_PATH . '/' . $last_insert_id . '/';

				if ($photo != '') {
					$ext 			= pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'photo' . '_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '.' . $ext;
					$setValues 		= "photo='$file_name'";
					$whereClause	= " WHERE id ='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);

					parent::create_thumb_img($_FILES["photo"]["tmp_name"], $courseImgPathFile,  $ext, 1200, 750);
					parent::create_thumb_img($_FILES["photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}


				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Teacher has been added successfully!';
			}

			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add Teacher.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function update_teacher()
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		$id		 	= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$code		 	= parent::test(isset($_POST['code']) ? $_POST['code'] : '');
		$name 	= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$designation		 	= parent::test(isset($_POST['designation']) ? $_POST['designation'] : '');
		$mobile 	= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$email		 	= parent::test(isset($_POST['email']) ? $_POST['email'] : '');

		$photo		= isset($_FILES["photo"]["name"]) ? $_FILES["photo"]["name"] : '';

		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$created_by 	= $_SESSION['user_name'];

		/* validations */
		if ($title == '')
			$errors['title'] = 'Title is required.';

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//the uplaod of multiple file document
			$tableName  	= 'teacher';
			$setValues  	= "code='$code', name='$name',designation='$designation',mobile='$mobile',email='$email',active='$status', updated_by='$created_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	=      parent::updateData($tableName, $setValues, $whereClause);
			$exec   		= parent::execQuery($updateSql);
			if ($exec) {
				$courseImgPathDir 	= TEACHERPHOTO_PATH . '/' . $id . '/';

				if ($photo != '') {
					$ext 			= pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
					$file_name 		= 'photo' . '_' . mt_rand(0, 123456789) . '_' . $id . '.' . $ext;
					$setValues 		= "photo='$file_name'";
					$whereClause	= " WHERE id ='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);

					parent::create_thumb_img($_FILES["photo"]["tmp_name"], $courseImgPathFile,  $ext, 1200, 750);
					parent::create_thumb_img($_FILES["photo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Teacher has been added successfully!';
			}

			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not update Teacher.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}

		return json_encode($data);
	}
	public function list_teacher($id = '', $cond = '')
	{
		$tableName = " teacher";
		$selVals = "*";
		$whereClause = "WHERE delete_flag=0 ";
		if ($id != '')
			$whereClause .= " AND id='$id'";
		if ($cond != '')
			$whereClause .= " $cond ";

		$whereClause .= ' ORDER BY created_at DESC';
		$sql = parent::selectData($selVals, $tableName, $whereClause);
		$res = parent::execQuery($sql);
		if (!$res) {
			return false;
		}
		return $res;
	}

	public function delete_teacher($id)
	{
		$sql = "UPDATE  teacher SET active='0',delete_flag='1', updated_by='" . $_SESSION['user_fullname'] . "' WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	public function add_news()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$gallery_image 		= isset($_FILES['gallery_image']['name']) ? $_FILES['gallery_image']['name'] : '';

		$role 			= 2; //institute;
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if ($gallery_image == '') {
			$errors['gallery_image'] = 'Image Is Required!';
		}

		if ($gallery_image != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
			$extension = pathinfo($gallery_image, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['gallery_image'] = 'Invalid file format! Please select valid image file.';
			}
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "news";
			$tabFields 	= "(id, name,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','1','0','$created_by',NOW())";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);

			if ($exSql) {
				/* upload course files */
				$last_insert_id 		= parent::last_id();
				$courseImgPathDir 	= NEWS_PATH . '/' . $last_insert_id . '/';

				if ($gallery_image != '') {
					$ext 			= pathinfo($_FILES["gallery_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_' . $last_insert_id . '_T.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$last_insert_id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["gallery_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["gallery_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! News has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the News.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_news($id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM news A WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function edit_news($id)
	{

		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id']) ? $_POST['id'] : '');

		$name 			= parent::test(isset($_POST['name']) ? $_POST['name'] : '');
		$gallery_image 		= isset($_FILES['gallery_image']['name']) ? $_FILES['gallery_image']['name'] : '';

		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */

		if ($name == '') {
			$errors['name'] = 'Name Is Required!';
		}

		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			parent::start_transaction();
			$tableName 	= "news";
			$setValues 	= "name='$name', updated_by='$updated_by', updated_at=NOW()";
			$whereClause = " WHERE id='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);

			if ($exSql) {
				/* upload course files */
				$courseImgPathDir 	= NEWS_PATH . '/' . $id . '/';

				if ($gallery_image != '') {
					$ext 			= pathinfo($_FILES["gallery_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $name . '_' . mt_rand(0, 123456789) . '_logo.' . $ext;
					$setValues 		= "image='$file_name'";
					$whereClause	= " WHERE id='$id'";
					$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
					$exSql			= parent::execQuery($updateSql);

					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					parent::create_thumb_img($_FILES["gallery_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					parent::create_thumb_img($_FILES["gallery_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! News has been added successfully!';
			}
			//upload course image
			else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function delete_news($id)
	{
		$sql = "UPDATE  news SET active='0',delete_flag='1', updated_by='" . $_SESSION['user_fullname'] . "' WHERE id='$id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}
}
