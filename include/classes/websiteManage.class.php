<?php
include_once('database_results.class.php');
include_once('access.class.php');

class websiteManage extends access
{
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
					parent::create_thumb_img($_FILES["bannerimg"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
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
		$sql = "SELECT A.*,B.image as icon FROM social_media_links A LEFT JOIN social_media_master B ON A.master_id = B.id WHERE A.delete_flag=0 ";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY B.name ASC';
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
			$tabFields 	= "(id, name, active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','1','0','$created_by',NOW())";

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

	public function list_advertise($id = '', $condition = '', $limit = '')
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
		if ($limit != '') {
			$sql .= " $limit ";
		}
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
			$setValues 	= "name='$name', updated_by='$updated_by', updated_at=NOW()";
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
			$tabFields 	= "(id, name,designation,description,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL, '$name','$designation','$description','1','0','$created_by',NOW())";

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
		$sql = "SELECT A.* FROM our_team A WHERE A.delete_flag=0";

		if ($id != '') {
			$sql .= " AND A.id ='$id ' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= ' ORDER BY A.position ASC';

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
			$setValues 	= "name='$name', description='$description',designation='$designation',updated_by='$updated_by', updated_at=NOW()";
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
			$setValues 	= "terms_condition='$terms_condition',privacy_policies='$privacy_policies',disclaimer='$disclaimer',updated_by='$updated_by', updated_at=NOW()";
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
	public function list_courses($course_id = '', $condition = '', $limit = '')
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
		if ($limit != '')
			$sql .= $limit;
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
	//admission form
	public function submit_admission()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$course_id 	= parent::test(isset($_POST['course_id']) ? $_POST['course_id'] : '');
		$fname 		= parent::test(isset($_POST['fname']) ? $_POST['fname'] : '');
		$lname 		= parent::test(isset($_POST['lname']) ? $_POST['lname'] : '');
		$email 		= parent::test(isset($_POST['email']) ? $_POST['email'] : '');
		$phone 		= parent::test(isset($_POST['phone']) ? $_POST['phone'] : '');
		$address 		= parent::test(isset($_POST['address']) ? $_POST['address'] : '');
		$city 		= parent::test(isset($_POST['city']) ? $_POST['city'] : '');
		$state 		= parent::test(isset($_POST['state']) ? $_POST['state'] : '');
		$pincode 		= parent::test(isset($_POST['pincode']) ? $_POST['pincode'] : '');

		$created_by  		= $fname;

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = "Invalid email format";
		}
		if ($fname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $fname)) {
				$errors['fname'] = "Only letters and white space allowed";
			}
		}
		if ($lname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $lname)) {
				$errors['lname'] = "Only letters and white space allowed";
			}
		}

		if ($phone != '') {
			if (strlen($phone) != 10) {
				$errors['phone'] = 'Only 10 Digits allowed.';
			}
			$first_no = $phone[0];
			$arr = array('9', '8', '7', '6', '5', '4', '3', '2', '1', '0');
			if (!in_array($first_no, $arr)) {
				$errors['phone'] = 'Only letters and white space allowed.';
			}
		}

		//new validations
		if ($fname == '')
			$errors['fname'] = 'First name is required.';
		if ($lname == '')
			$errors['lname'] = 'Last name is required.';
		if ($email == '')
			$errors['email'] = 'Email is required.';
		if ($course_id == '')
			$errors['course_id'] = 'Please select course.';
		if ($phone == '')
			$errors['phone'] = 'Mobile is required.';


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//if($dob!='') 
			//$dob = @date('Y-m-d', strtotime($dob));
			parent::start_transaction();
			$tableName 	= "student_admission";
			$tabFields 	= "(id , course_id, fname, lname,email,phone,address, city, state,pincode,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL,'$course_id','$fname','$lname','$email','$phone','$address','$city','$state','$pincode','1','0','$created_by',NOW())";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Your admission form submitted successfully!';

				//Email Code
				$admin_email = $this->get_admin_email(1);
				$course_name = $this->get_course_name($course_id);
				$to = $admin_email;
				$subject = "Student AdmissionFrom Website";

				$message   = '<!doctype html> 
                                <html>
                                  <head>
                                    <meta name="viewport" content="width=device-width" />
                                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                    <title>DITRP</title>
                                   
                                  <style type="text/css">
                                       /* -------------------------------------
                                          GLOBAL RESETS
                                      ------------------------------------- */
                                      img {
                                        border: none;
                                        -ms-interpolation-mode: bicubic;
                                        max-width: 100%; }
                                
                                      body {
                                        background-color: #f6f6f6;
                                        font-family: sans-serif;
                                        -webkit-font-smoothing: antialiased;
                                        font-size: 14px;
                                        line-height: 1.4;
                                        margin: 0;
                                        padding: 0; 
                                        -ms-text-size-adjust: 100%;
                                        -webkit-text-size-adjust: 100%; }
                                
                                      table {
                                        border-collapse: separate;
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        width: 100%; }
                                        table td {
                                          font-family: sans-serif;
                                          font-size: 14px;
                                          vertical-align: top; }
                                
                                      /* -------------------------------------
                                          BODY & CONTAINER
                                      ------------------------------------- */
                                
                                      .body {
                                        background-color: #f6f6f6;
                                        width: 100%; }
                                
                                      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                                      .container {
                                        display: block;
                                        Margin: 0 auto !important;
                                        /* makes it centered */
                                        max-width: 100%;
                                        padding: 10px;
                                        width: 100%; }
                                
                                      /* This should also be a block element, so that it will fill 100% of the .container */
                                      .content {
                                        box-sizing: border-box;
                                        display: block;
                                        Margin: 0 auto;
                                        max-width: 90%;
                                        padding: 10px; }
                                
                                      /* -------------------------------------
                                          HEADER, FOOTER, MAIN
                                      ------------------------------------- */
                                      .main {
                                        background: #fff;
                                        border-radius: 3px;
                                        width: 100%; }
                                
                                      .wrapper {
                                        box-sizing: border-box;
                                        padding: 20px; }
                                
                                      .footer {
                                        clear: both;
                                        padding-top: 10px;
                                        text-align: center;
                                        width: 100%; }
                                        .footer td,
                                        .footer p,
                                        .footer span,
                                        .footer a {
                                          color: #999999;
                                          font-size: 12px;
                                          text-align: center; }
                                
                                      /* -------------------------------------
                                          TYPOGRAPHY
                                      ------------------------------------- */
                                      h1,
                                      h2,
                                      h3,
                                      h4 {
                                        color: #000000;
                                        font-family: sans-serif;
                                        font-weight: 400;
                                        line-height: 1.4;
                                        margin: 0;
                                        Margin-bottom: 30px; }
                                
                                      h1 {
                                        font-size: 35px;
                                        font-weight: 300;
                                        text-align: center;
                                        text-transform: capitalize; }
                                
                                      p,
                                      ul,
                                      ol {
                                        font-family: sans-serif;
                                        font-size: 14px;
                                        font-weight: normal;
                                        margin: 0;
                                        Margin-bottom: 15px; }
                                        p li,
                                        ul li,
                                        ol li {
                                          list-style-position: inside;
                                          margin-left: 5px; }
                                
                                      a {
                                        color: #3498db;
                                        text-decoration: underline; }
                                
                                      /* -------------------------------------
                                          BUTTONS
                                      ------------------------------------- */
                                      .btn {
                                        box-sizing: border-box;
                                        width: 100%; }
                                        .btn > tbody > tr > td {
                                          padding-bottom: 15px; }
                                        .btn table {
                                          width: auto; }
                                        .btn table td {
                                          background-color: #ffffff;
                                          border-radius: 5px;
                                          text-align: center; }
                                        .btn a {
                                          background-color: #ffffff;
                                          border: solid 1px #3498db;
                                          border-radius: 5px;
                                          box-sizing: border-box;
                                          color: #3498db;
                                          cursor: pointer;
                                          display: inline-block;
                                          font-size: 14px;
                                          font-weight: bold;
                                          margin: 0;
                                          padding: 12px 25px;
                                          text-decoration: none;
                                          text-transform: capitalize; }
                                
                                      .btn-primary table td {
                                        background-color: #3498db; }
                                
                                      .btn-primary a {
                                        background-color: #3498db;
                                        border-color: #3498db;
                                        color: #ffffff; }
                                
                                      /* -------------------------------------
                                          OTHER STYLES THAT MIGHT BE USEFUL
                                      ------------------------------------- */
                                      .last {
                                        margin-bottom: 0; }
                                
                                      .first {
                                        margin-top: 0; }
                                
                                      .align-center {
                                        text-align: center; }
                                
                                      .align-right {
                                        text-align: right; }
                                
                                      .align-left {
                                        text-align: left; }
                                
                                      .clear {
                                        clear: both; }
                                
                                      .mt0 {
                                        margin-top: 0; }
                                
                                      .mb0 {
                                        margin-bottom: 0; }
                                
                                      .preheader {
                                        color: transparent;
                                        display: none;
                                        height: 0;
                                        max-height: 0;
                                        max-width: 0;
                                        opacity: 0;
                                        overflow: hidden;
                                        mso-hide: all;
                                        visibility: hidden;
                                        width: 0; }
                                
                                      .powered-by a {
                                        text-decoration: none; }
                                
                                      hr {
                                        border: 0;
                                        border-bottom: 1px solid #f6f6f6;
                                        Margin: 20px 0; }
                                
                                      /* -------------------------------------
                                          RESPONSIVE AND MOBILE FRIENDLY STYLES
                                      ------------------------------------- */
                                      @media only screen and (max-width: 620px) {
                                        table[class=body] h1 {
                                          font-size: 28px !important;
                                          margin-bottom: 10px !important; }
                                        table[class=body] p,
                                        table[class=body] ul,
                                        table[class=body] ol,
                                        table[class=body] td,
                                        table[class=body] span,
                                        table[class=body] a {
                                          font-size: 16px !important; }
                                        table[class=body] .wrapper,
                                        table[class=body] .article {
                                          padding: 10px !important; }
                                        table[class=body] .content {
                                          padding: 0 !important; }
                                        table[class=body] .container {
                                          padding: 0 !important;
                                          width: 100% !important; }
                                        table[class=body] .main {
                                          border-left-width: 0 !important;
                                          border-radius: 0 !important;
                                          border-right-width: 0 !important; }
                                        table[class=body] .btn table {
                                          width: 100% !important; }
                                        table[class=body] .btn a {
                                          width: 100% !important; }
                                        table[class=body] .img-responsive {
                                          height: auto !important;
                                          max-width: 100% !important;
                                          width: auto !important; }}
                                
                                      /* -------------------------------------
                                          PRESERVE THESE STYLES IN THE HEAD
                                      ------------------------------------- */
                                      @media all {
                                        .ExternalClass {
                                          width: 100%; }
                                        .ExternalClass,
                                        .ExternalClass p,
                                        .ExternalClass span,
                                        .ExternalClass font,
                                        .ExternalClass td,
                                        .ExternalClass div {
                                          line-height: 100%; }
                                        .apple-link a {
                                          color: inherit !important;
                                          font-family: inherit !important;
                                          font-size: inherit !important;
                                          font-weight: inherit !important;
                                          line-height: inherit !important;
                                          text-decoration: none !important; } 
                                        .btn-primary table td:hover {
                                          background-color: #34495e !important; }
                                        .btn-primary a:hover {
                                          background-color: #34495e !important;
                                          border-color: #34495e !important; } }
                                .header{text-align:center; border-bottom:1px solid #ccc;margin-bottom:15px;}
                                .header .logo{float:left;}
                                .header .toplinks{float:right;}
                                .header .toplinks h2{padding-top: 5%;color: #234e88;font-size: 1.2em;}
                                  </style>
                                  </head>
                                  <body class="">
                                    <table border="0" cellpadding="0" cellspacing="0" class="body">
                                      <tr>
                                        <td>&nbsp;</td>
                                        <td class="container">
                                          <div class="content">           
                                            <span class="preheader">Enquiry recieved from ' . $fname . ' ' . $lname . '</span>
                                            <table class="main">
                                              <tr>
                                                <td class="wrapper">
                                                  <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                      <td>
                                            <div class="header">
                                            <div class="toplinks">
                                            <h2>For assistance email at ' . $admin_email . '</h2>
                                              
                                            </div>
                                            <div style="clear:both"></div>
                                            </div>
                                            <p>Admission Details : </p>
                                            <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                								<th align="left">Course Name</th>
                								<td align="left">' . $course_name . '</td>
                							</tr>
                                              <tr>
                                                <th align="left">Full Name</th>
                                                <td align="left">' . $fname . ' ' . $lname . '</td>
                                              </tr>
                                              <tr>
                                                <th align="left">Email</th>
                                                <td align="left">' . $email . '</td>
                                              </tr>
                                              <tr>
                                                <th align="left">Mobile</th>
                                                <td align="left">' . $phone . '</td>
                                              </tr>
                                              <tr>
                                                <th align="left">Address</th>
                                                <td align="left">' . $address . ' ' . $city . '</td>
                                              </tr>
                                              <tr>
                                                <th align="left">Pincode</th>
                                                <td align="left">' . $pincode . '</td>
                                              </tr>
                                            </table>
                                                      </td>
                                                    </tr>
                                                  </table>
                                                </td>
                                              </tr>
                                              </table>
                                            <div class="footer">
                                              <table border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                  <td class="content-block">
                                                    <br> Don\'t like these emails? <a href="mailto:' . $admin_email . '">Unsubscribe</a>.
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td class="content-block powered-by">
                                                    Powered by <a href="https://www.hellodigitalindia.co.in">Hello Digital India</a>.
                                                  </td>
                                                </tr>
                                              </table>
                                            </div>            
                                    </div>
                                        </td>
                                        <td>&nbsp;</td>
                                      </tr>
                                    </table>
                                  </body>
                                </html>';

				$header = "From:$email \r\n";
				$header .= "Reply-To: $email\r\n";
				$header .= "Cc:info@hellodigitalindia.co.in \r\n";
				$header .= "MIME-Version: 1.0\r\n";
				$header .= "Content-type: text/html\r\n";
				$retval = mail($to, $subject, $message, $header);
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not submit your enquiry.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//enquiry form
	public function submit_enquiry()
	{
		//print_r($_POST); exit();
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$course_id 	= parent::test(isset($_POST['course_id']) ? $_POST['course_id'] : '');
		$fullname 	= parent::test(isset($_POST['fullname']) ? $_POST['fullname'] : '');
		$emailId 		= parent::test(isset($_POST['emailId']) ? $_POST['emailId'] : '');
		$mobile 		= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$message 		= parent::test(isset($_POST['message']) ? $_POST['message'] : '');

		$created_by  		= $fullname;

		/* check validations */
		//new validations
		if (!filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
			$errors['emailId'] = "Invalid email format";
		}
		if ($fullname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $fullname)) {
				$errors['fullname'] = "Only letters and white space allowed";
			}
		}

		if ($mobile != '') {
			if (strlen($mobile) != 10) {
				$errors['mobile'] = 'Only 10 Digits allowed.';
			}
			$first_no = $mobile[0];
			$arr = array('9', '8', '7', '6', '5', '4', '3', '2', '1');
			if (!in_array($first_no, $arr)) {
				$errors['mobile'] = 'Only letters and white space allowed. Mobile number should start with 9 or 8 or 7 or 6 only.';
			}
		}

		//new validations
		if ($course_id == '')
			$errors['course_id'] = 'Please select course';
		if ($fullname == '')
			$errors['fullname'] = 'First name is required.';
		if ($emailId == '')
			$errors['emailId'] = 'Email is required.';
		//  if ($message_contact=='')
		// $errors['message_contact'] = 'Message is required.';
		if ($mobile == '')
			$errors['mobile'] = 'Mobile is required.';


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//if($dob!='') 
			//$dob = @date('Y-m-d', strtotime($dob));
			parent::start_transaction();
			$tableName 	= "student_website_enquiry";
			$tabFields 	= "(id, course_id,name, emailid, mobile,message,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL,'$course_id','$fullname','$emailId','$mobile','$message','1','0','$created_by',NOW())";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Your enquiry submitted successfully!';
				$admin_email = $this->get_admin_email(1);
				$course_name = $this->get_course_name($course_id);
				//Email Code
				$to = $admin_email;
				$subject = "Course Enquiry From Website";

				$message 	= '<!doctype html> 
                                <html>
                                  <head>
                                    <meta name="viewport" content="width=device-width" />
                                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                    <title>DITRP</title>
                                   
                                	<style type="text/css">
                                	     /* -------------------------------------
                                          GLOBAL RESETS
                                      ------------------------------------- */
                                      img {
                                        border: none;
                                        -ms-interpolation-mode: bicubic;
                                        max-width: 100%; }
                                
                                      body {
                                        background-color: #f6f6f6;
                                        font-family: sans-serif;
                                        -webkit-font-smoothing: antialiased;
                                        font-size: 14px;
                                        line-height: 1.4;
                                        margin: 0;
                                        padding: 0; 
                                        -ms-text-size-adjust: 100%;
                                        -webkit-text-size-adjust: 100%; }
                                
                                      table {
                                        border-collapse: separate;
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        width: 100%; }
                                        table td {
                                          font-family: sans-serif;
                                          font-size: 14px;
                                          vertical-align: top; }
                                
                                      /* -------------------------------------
                                          BODY & CONTAINER
                                      ------------------------------------- */
                                
                                      .body {
                                        background-color: #f6f6f6;
                                        width: 100%; }
                                
                                      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                                      .container {
                                        display: block;
                                        Margin: 0 auto !important;
                                        /* makes it centered */
                                        max-width: 100%;
                                        padding: 10px;
                                        width: 100%; }
                                
                                      /* This should also be a block element, so that it will fill 100% of the .container */
                                      .content {
                                        box-sizing: border-box;
                                        display: block;
                                        Margin: 0 auto;
                                        max-width: 90%;
                                        padding: 10px; }
                                
                                      /* -------------------------------------
                                          HEADER, FOOTER, MAIN
                                      ------------------------------------- */
                                      .main {
                                        background: #fff;
                                        border-radius: 3px;
                                        width: 100%; }
                                
                                      .wrapper {
                                        box-sizing: border-box;
                                        padding: 20px; }
                                
                                      .footer {
                                        clear: both;
                                        padding-top: 10px;
                                        text-align: center;
                                        width: 100%; }
                                        .footer td,
                                        .footer p,
                                        .footer span,
                                        .footer a {
                                          color: #999999;
                                          font-size: 12px;
                                          text-align: center; }
                                
                                      /* -------------------------------------
                                          TYPOGRAPHY
                                      ------------------------------------- */
                                      h1,
                                      h2,
                                      h3,
                                      h4 {
                                        color: #000000;
                                        font-family: sans-serif;
                                        font-weight: 400;
                                        line-height: 1.4;
                                        margin: 0;
                                        Margin-bottom: 30px; }
                                
                                      h1 {
                                        font-size: 35px;
                                        font-weight: 300;
                                        text-align: center;
                                        text-transform: capitalize; }
                                
                                      p,
                                      ul,
                                      ol {
                                        font-family: sans-serif;
                                        font-size: 14px;
                                        font-weight: normal;
                                        margin: 0;
                                        Margin-bottom: 15px; }
                                        p li,
                                        ul li,
                                        ol li {
                                          list-style-position: inside;
                                          margin-left: 5px; }
                                
                                      a {
                                        color: #3498db;
                                        text-decoration: underline; }
                                
                                      /* -------------------------------------
                                          BUTTONS
                                      ------------------------------------- */
                                      .btn {
                                        box-sizing: border-box;
                                        width: 100%; }
                                        .btn > tbody > tr > td {
                                          padding-bottom: 15px; }
                                        .btn table {
                                          width: auto; }
                                        .btn table td {
                                          background-color: #ffffff;
                                          border-radius: 5px;
                                          text-align: center; }
                                        .btn a {
                                          background-color: #ffffff;
                                          border: solid 1px #3498db;
                                          border-radius: 5px;
                                          box-sizing: border-box;
                                          color: #3498db;
                                          cursor: pointer;
                                          display: inline-block;
                                          font-size: 14px;
                                          font-weight: bold;
                                          margin: 0;
                                          padding: 12px 25px;
                                          text-decoration: none;
                                          text-transform: capitalize; }
                                
                                      .btn-primary table td {
                                        background-color: #3498db; }
                                
                                      .btn-primary a {
                                        background-color: #3498db;
                                        border-color: #3498db;
                                        color: #ffffff; }
                                
                                      /* -------------------------------------
                                          OTHER STYLES THAT MIGHT BE USEFUL
                                      ------------------------------------- */
                                      .last {
                                        margin-bottom: 0; }
                                
                                      .first {
                                        margin-top: 0; }
                                
                                      .align-center {
                                        text-align: center; }
                                
                                      .align-right {
                                        text-align: right; }
                                
                                      .align-left {
                                        text-align: left; }
                                
                                      .clear {
                                        clear: both; }
                                
                                      .mt0 {
                                        margin-top: 0; }
                                
                                      .mb0 {
                                        margin-bottom: 0; }
                                
                                      .preheader {
                                        color: transparent;
                                        display: none;
                                        height: 0;
                                        max-height: 0;
                                        max-width: 0;
                                        opacity: 0;
                                        overflow: hidden;
                                        mso-hide: all;
                                        visibility: hidden;
                                        width: 0; }
                                
                                      .powered-by a {
                                        text-decoration: none; }
                                
                                      hr {
                                        border: 0;
                                        border-bottom: 1px solid #f6f6f6;
                                        Margin: 20px 0; }
                                
                                      /* -------------------------------------
                                          RESPONSIVE AND MOBILE FRIENDLY STYLES
                                      ------------------------------------- */
                                      @media only screen and (max-width: 620px) {
                                        table[class=body] h1 {
                                          font-size: 28px !important;
                                          margin-bottom: 10px !important; }
                                        table[class=body] p,
                                        table[class=body] ul,
                                        table[class=body] ol,
                                        table[class=body] td,
                                        table[class=body] span,
                                        table[class=body] a {
                                          font-size: 16px !important; }
                                        table[class=body] .wrapper,
                                        table[class=body] .article {
                                          padding: 10px !important; }
                                        table[class=body] .content {
                                          padding: 0 !important; }
                                        table[class=body] .container {
                                          padding: 0 !important;
                                          width: 100% !important; }
                                        table[class=body] .main {
                                          border-left-width: 0 !important;
                                          border-radius: 0 !important;
                                          border-right-width: 0 !important; }
                                        table[class=body] .btn table {
                                          width: 100% !important; }
                                        table[class=body] .btn a {
                                          width: 100% !important; }
                                        table[class=body] .img-responsive {
                                          height: auto !important;
                                          max-width: 100% !important;
                                          width: auto !important; }}
                                
                                      /* -------------------------------------
                                          PRESERVE THESE STYLES IN THE HEAD
                                      ------------------------------------- */
                                      @media all {
                                        .ExternalClass {
                                          width: 100%; }
                                        .ExternalClass,
                                        .ExternalClass p,
                                        .ExternalClass span,
                                        .ExternalClass font,
                                        .ExternalClass td,
                                        .ExternalClass div {
                                          line-height: 100%; }
                                        .apple-link a {
                                          color: inherit !important;
                                          font-family: inherit !important;
                                          font-size: inherit !important;
                                          font-weight: inherit !important;
                                          line-height: inherit !important;
                                          text-decoration: none !important; } 
                                        .btn-primary table td:hover {
                                          background-color: #34495e !important; }
                                        .btn-primary a:hover {
                                          background-color: #34495e !important;
                                          border-color: #34495e !important; } }
                                .header{text-align:center; border-bottom:1px solid #ccc;margin-bottom:15px;}
                                .header .logo{float:left;}
                                .header .toplinks{float:right;}
                                .header .toplinks h2{padding-top: 5%;color: #234e88;font-size: 1.2em;}
                                	</style>
                                  </head>
                                  <body class="">
                                    <table border="0" cellpadding="0" cellspacing="0" class="body">
                                      <tr>
                                        <td>&nbsp;</td>
                                        <td class="container">
                                          <div class="content">           
                                            <span class="preheader">Enquiry recieved from ' . $fullname . '</span>
                                            <table class="main">
                                              <tr>
                                                <td class="wrapper">
                                                  <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                      <td>
                                					  <div class="header">
                                					  <div class="toplinks">
                                						<h2>For assistance email at ' . $admin_email . '</h2>
                                							
                                					  </div>
                                					  <div style="clear:both"></div>
                                					  </div>
                                                        <p>New Course Enquiry</p>
                                                        
                                						<p>Enquiry Details:</p>
                                						<table border="0" cellpadding="0" cellspacing="0">
                                						    <tr>
                                								<th align="left">Course Name</th>
                                								<td align="left">' . $course_name . '</td>
                                							</tr>
                                							<tr>
                                								<th align="left">Name</th>
                                								<td align="left">' . $fullname . '</td>
                                							</tr>
                                							<tr>
                                								<th align="left">Email</th>
                                								<td align="left">' . $emailId . '</td>
                                							</tr>
                                							<tr>
                                								<th align="left">Mobile</th>
                                								<td align="left">' . $mobile . '</td>
                                							</tr>
                                							<tr>
                                								<th align="left">Message</th>
                                								<td align="left">' . $message . '</td>
                                							</tr>
                                						</table>
                                                      </td>
                                                    </tr>
                                                  </table>
                                                </td>
                                              </tr>
                                              </table>
                                            <div class="footer">
                                              <table border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                  <td class="content-block">
                                                    <br> Don\'t like these emails? <a href="mailto:' . $admin_email . '">Unsubscribe</a>.
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td class="content-block powered-by">
                                                    Powered by <a href="https://www.hellodigitalindia.co.in">Hello Digital India</a>.
                                                  </td>
                                                </tr>
                                              </table>
                                            </div>            
                                		</div>
                                        </td>
                                        <td>&nbsp;</td>
                                      </tr>
                                    </table>
                                  </body>
                                </html>';

				$header = "From:$emailId \r\n";
				$header .= "Reply-To: $emailId\r\n";
				$header .= "Cc:info@hellodigitalindia.co.in \r\n";
				$header .= "MIME-Version: 1.0\r\n";
				$header .= "Content-type: text/html\r\n";
				$retval = mail($to, $subject, $message, $header);
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not submit your enquiry.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	//apply for job
	public function submit_job_enquiry()
	{
		//print_r($_POST); exit();
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$job_id 	= parent::test(isset($_POST['job_id']) ? $_POST['job_id'] : '');
		$fullname 	= parent::test(isset($_POST['fullname']) ? $_POST['fullname'] : '');
		$emailId 		= parent::test(isset($_POST['emailId']) ? $_POST['emailId'] : '');
		$mobile 		= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$message 		= parent::test(isset($_POST['message']) ? $_POST['message'] : '');

		$created_by  		= $fullname;

		/* check validations */
		//new validations
		if (!filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
			$errors['emailId'] = "Invalid email format";
		}
		if ($fullname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $fullname)) {
				$errors['fullname'] = "Only letters and white space allowed";
			}
		}

		if ($mobile != '') {
			if (strlen($mobile) != 10) {
				$errors['mobile'] = 'Only 10 Digits allowed.';
			}
			$first_no = $mobile[0];
			$arr = array('9', '8', '7', '6', '5', '4', '3', '2', '1');
			if (!in_array($first_no, $arr)) {
				$errors['mobile'] = 'Only letters and white space allowed. Mobile number should start with 9 or 8 or 7 or 6 only.';
			}
		}

		//new validations
		if ($job_id == '')
			$errors['job_id'] = 'Please select job';
		if ($fullname == '')
			$errors['fullname'] = 'First name is required.';
		if ($emailId == '')
			$errors['emailId'] = 'Email is required.';
		//  if ($message_contact=='')
		// $errors['message_contact'] = 'Message is required.';
		if ($mobile == '')
			$errors['mobile'] = 'Mobile is required.';


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//if($dob!='') 
			//$dob = @date('Y-m-d', strtotime($dob));
			parent::start_transaction();
			$tableName 	= "job_apply_student";
			$tabFields 	= "(id, job_id,inst_id,name, email_id, mobile,message,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL,'$job_id','1','$fullname','$emailId','$mobile','$message','1','0','$created_by',NOW())";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Your enquiry submitted successfully!';

				//   $admin_email = $this->get_admin_email(1);
				//   $job_title = $this->get_job_title($job_id);
				//                   //Email Code
				//                      $to = $admin_email;
				//                      $subject = "Job Enquiry From Website";

				//                      $message   = '<!doctype html> 
				//                         <html>
				//                           <head>
				//                             <meta name="viewport" content="width=device-width" />
				//                             <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				//                             <title>Job Enquiry</title>

				//                           <style type="text/css">
				//                               /* -------------------------------------
				//                                   GLOBAL RESETS
				//                               ------------------------------------- */
				//                               img {
				//                                 border: none;
				//                                 -ms-interpolation-mode: bicubic;
				//                                 max-width: 100%; }

				//                               body {
				//                                 background-color: #f6f6f6;
				//                                 font-family: sans-serif;
				//                                 -webkit-font-smoothing: antialiased;
				//                                 font-size: 14px;
				//                                 line-height: 1.4;
				//                                 margin: 0;
				//                                 padding: 0; 
				//                                 -ms-text-size-adjust: 100%;
				//                                 -webkit-text-size-adjust: 100%; }

				//                               table {
				//                                 border-collapse: separate;
				//                                 mso-table-lspace: 0pt;
				//                                 mso-table-rspace: 0pt;
				//                                 width: 100%; }
				//                                 table td {
				//                                   font-family: sans-serif;
				//                                   font-size: 14px;
				//                                   vertical-align: top; }

				//                               /* -------------------------------------
				//                                   BODY & CONTAINER
				//                               ------------------------------------- */

				//                               .body {
				//                                 background-color: #f6f6f6;
				//                                 width: 100%; }

				//                               /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
				//                               .container {
				//                                 display: block;
				//                                 Margin: 0 auto !important;
				//                                 /* makes it centered */
				//                                 max-width: 100%;
				//                                 padding: 10px;
				//                                 width: 100%; }

				//                               /* This should also be a block element, so that it will fill 100% of the .container */
				//                               .content {
				//                                 box-sizing: border-box;
				//                                 display: block;
				//                                 Margin: 0 auto;
				//                                 max-width: 90%;
				//                                 padding: 10px; }

				//                               /* -------------------------------------
				//                                   HEADER, FOOTER, MAIN
				//                               ------------------------------------- */
				//                               .main {
				//                                 background: #fff;
				//                                 border-radius: 3px;
				//                                 width: 100%; }

				//                               .wrapper {
				//                                 box-sizing: border-box;
				//                                 padding: 20px; }

				//                               .footer {
				//                                 clear: both;
				//                                 padding-top: 10px;
				//                                 text-align: center;
				//                                 width: 100%; }
				//                                 .footer td,
				//                                 .footer p,
				//                                 .footer span,
				//                                 .footer a {
				//                                   color: #999999;
				//                                   font-size: 12px;
				//                                   text-align: center; }

				//                               /* -------------------------------------
				//                                   TYPOGRAPHY
				//                               ------------------------------------- */
				//                               h1,
				//                               h2,
				//                               h3,
				//                               h4 {
				//                                 color: #000000;
				//                                 font-family: sans-serif;
				//                                 font-weight: 400;
				//                                 line-height: 1.4;
				//                                 margin: 0;
				//                                 Margin-bottom: 30px; }

				//                               h1 {
				//                                 font-size: 35px;
				//                                 font-weight: 300;
				//                                 text-align: center;
				//                                 text-transform: capitalize; }

				//                               p,
				//                               ul,
				//                               ol {
				//                                 font-family: sans-serif;
				//                                 font-size: 14px;
				//                                 font-weight: normal;
				//                                 margin: 0;
				//                                 Margin-bottom: 15px; }
				//                                 p li,
				//                                 ul li,
				//                                 ol li {
				//                                   list-style-position: inside;
				//                                   margin-left: 5px; }

				//                               a {
				//                                 color: #3498db;
				//                                 text-decoration: underline; }

				//                               /* -------------------------------------
				//                                   BUTTONS
				//                               ------------------------------------- */
				//                               .btn {
				//                                 box-sizing: border-box;
				//                                 width: 100%; }
				//                                 .btn > tbody > tr > td {
				//                                   padding-bottom: 15px; }
				//                                 .btn table {
				//                                   width: auto; }
				//                                 .btn table td {
				//                                   background-color: #ffffff;
				//                                   border-radius: 5px;
				//                                   text-align: center; }
				//                                 .btn a {
				//                                   background-color: #ffffff;
				//                                   border: solid 1px #3498db;
				//                                   border-radius: 5px;
				//                                   box-sizing: border-box;
				//                                   color: #3498db;
				//                                   cursor: pointer;
				//                                   display: inline-block;
				//                                   font-size: 14px;
				//                                   font-weight: bold;
				//                                   margin: 0;
				//                                   padding: 12px 25px;
				//                                   text-decoration: none;
				//                                   text-transform: capitalize; }

				//                               .btn-primary table td {
				//                                 background-color: #3498db; }

				//                               .btn-primary a {
				//                                 background-color: #3498db;
				//                                 border-color: #3498db;
				//                                 color: #ffffff; }

				//                               /* -------------------------------------
				//                                   OTHER STYLES THAT MIGHT BE USEFUL
				//                               ------------------------------------- */
				//                               .last {
				//                                 margin-bottom: 0; }

				//                               .first {
				//                                 margin-top: 0; }

				//                               .align-center {
				//                                 text-align: center; }

				//                               .align-right {
				//                                 text-align: right; }

				//                               .align-left {
				//                                 text-align: left; }

				//                               .clear {
				//                                 clear: both; }

				//                               .mt0 {
				//                                 margin-top: 0; }

				//                               .mb0 {
				//                                 margin-bottom: 0; }

				//                               .preheader {
				//                                 color: transparent;
				//                                 display: none;
				//                                 height: 0;
				//                                 max-height: 0;
				//                                 max-width: 0;
				//                                 opacity: 0;
				//                                 overflow: hidden;
				//                                 mso-hide: all;
				//                                 visibility: hidden;
				//                                 width: 0; }

				//                               .powered-by a {
				//                                 text-decoration: none; }

				//                               hr {
				//                                 border: 0;
				//                                 border-bottom: 1px solid #f6f6f6;
				//                                 Margin: 20px 0; }

				//                               /* -------------------------------------
				//                                   RESPONSIVE AND MOBILE FRIENDLY STYLES
				//                               ------------------------------------- */
				//                               @media only screen and (max-width: 620px) {
				//                                 table[class=body] h1 {
				//                                   font-size: 28px !important;
				//                                   margin-bottom: 10px !important; }
				//                                 table[class=body] p,
				//                                 table[class=body] ul,
				//                                 table[class=body] ol,
				//                                 table[class=body] td,
				//                                 table[class=body] span,
				//                                 table[class=body] a {
				//                                   font-size: 16px !important; }
				//                                 table[class=body] .wrapper,
				//                                 table[class=body] .article {
				//                                   padding: 10px !important; }
				//                                 table[class=body] .content {
				//                                   padding: 0 !important; }
				//                                 table[class=body] .container {
				//                                   padding: 0 !important;
				//                                   width: 100% !important; }
				//                                 table[class=body] .main {
				//                                   border-left-width: 0 !important;
				//                                   border-radius: 0 !important;
				//                                   border-right-width: 0 !important; }
				//                                 table[class=body] .btn table {
				//                                   width: 100% !important; }
				//                                 table[class=body] .btn a {
				//                                   width: 100% !important; }
				//                                 table[class=body] .img-responsive {
				//                                   height: auto !important;
				//                                   max-width: 100% !important;
				//                                   width: auto !important; }}

				//                               /* -------------------------------------
				//                                   PRESERVE THESE STYLES IN THE HEAD
				//                               ------------------------------------- */
				//                               @media all {
				//                                 .ExternalClass {
				//                                   width: 100%; }
				//                                 .ExternalClass,
				//                                 .ExternalClass p,
				//                                 .ExternalClass span,
				//                                 .ExternalClass font,
				//                                 .ExternalClass td,
				//                                 .ExternalClass div {
				//                                   line-height: 100%; }
				//                                 .apple-link a {
				//                                   color: inherit !important;
				//                                   font-family: inherit !important;
				//                                   font-size: inherit !important;
				//                                   font-weight: inherit !important;
				//                                   line-height: inherit !important;
				//                                   text-decoration: none !important; } 
				//                                 .btn-primary table td:hover {
				//                                   background-color: #34495e !important; }
				//                                 .btn-primary a:hover {
				//                                   background-color: #34495e !important;
				//                                   border-color: #34495e !important; } }
				//                         .header{text-align:center; border-bottom:1px solid #ccc;margin-bottom:15px;}
				//                         .header .logo{float:left;}
				//                         .header .toplinks{float:right;}
				//                         .header .toplinks h2{padding-top: 5%;color: #234e88;font-size: 1.2em;}
				//                           </style>
				//                           </head>
				//                           <body class="">
				//                             <table border="0" cellpadding="0" cellspacing="0" class="body">
				//                               <tr>
				//                                 <td>&nbsp;</td>
				//                                 <td class="container">
				//                                   <div class="content">           
				//                                     <span class="preheader">Enquiry recieved from '.$fullname.'</span>
				//                                     <table class="main">
				//                                       <tr>
				//                                         <td class="wrapper">
				//                                           <table border="0" cellpadding="0" cellspacing="0">
				//                                             <tr>
				//                                               <td>
				//                                     <div class="header">
				//                                     <div class="toplinks">
				//                                     <h2>For assistance email at '. $admin_email .'</h2>

				//                                     </div>
				//                                     <div style="clear:both"></div>
				//                                     </div>
				//                                      <p>Job Enquiry :</p>
				//                                     <table border="0" cellpadding="0" cellspacing="0">
				//                                      <tr>
				//                                         <th align="left">Job Title</th>
				//                                         <td align="left">'.$job_title.'</td>
				//                                       </tr>
				//                                       <tr>
				//                                         <th align="left">Name</th>
				//                                         <td align="left">'.$fullname.'</td>
				//                                       </tr>
				//                                       <tr>
				//                                         <th align="left">Email</th>
				//                                         <td align="left">'.$emailId.'</td>
				//                                       </tr>
				//                                       <tr>
				//                                         <th align="left">Mobile</th>
				//                                         <td align="left">'.$mobile.'</td>
				//                                       </tr>
				//                                       <tr>
				//                                         <th align="left">Message</th>
				//                                         <td align="left">'.$message.'</td>
				//                                       </tr>
				//                                     </table>
				//                                               </td>
				//                                             </tr>
				//                                           </table>
				//                                         </td>
				//                                       </tr>
				//                                       </table>
				//                                     <div class="footer">
				//                                       <table border="0" cellpadding="0" cellspacing="0">
				//                                         <tr>
				//                                           <td class="content-block">
				//                                             <br> Don\'t like these emails? <a href="mailto:'.$admin_email.'">Unsubscribe</a>.
				//                                           </td>
				//                                         </tr>
				//                                         <tr>
				//                                           <td class="content-block powered-by">
				//                                             Powered by <a href="https://www.hellodigitalindia.co.in">Hello Digital India</a>.
				//                                           </td>
				//                                         </tr>
				//                                       </table>
				//                                     </div>            
				//                             </div>
				//                                 </td>
				//                                 <td>&nbsp;</td>
				//                               </tr>
				//                             </table>
				//                           </body>
				//                         </html>';

				//                      $header = "From:$emailId \r\n";
				//                      $header .= "Reply-To: $emailId\r\n";
				//                      $header .= "Cc:info@hellodigitalindia.co.in \r\n";
				//                      $header .= "MIME-Version: 1.0\r\n";
				//                      $header .= "Content-type: text/html\r\n";
				//                      $retval = mail ($to,$subject,$message,$header);
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not submit your enquiry.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	public function list_blogs($id = '', $condition = '', $limit = '')
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
		if ($limit != '')
			$sql .= $limit;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
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

	//sample certificates
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

	//apply for job
	public function submit_services_enquiry()
	{
		//print_r($_POST); exit();
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$services_id 	= parent::test(isset($_POST['id']) ? $_POST['id'] : '');
		$fullname 	= parent::test(isset($_POST['fullname']) ? $_POST['fullname'] : '');
		$emailId 		= parent::test(isset($_POST['emailId']) ? $_POST['emailId'] : '');
		$mobile 		= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');
		$state 		= parent::test(isset($_POST['state']) ? $_POST['state'] : '');
		$city 		= parent::test(isset($_POST['city']) ? $_POST['city'] : '');
		$pincode 		= parent::test(isset($_POST['pincode']) ? $_POST['pincode'] : '');
		$remark 		= parent::test(isset($_POST['remark']) ? $_POST['remark'] : '');

		$created_by  		= $fullname;

		/* check validations */
		//new validations
		if (!filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
			$errors['emailId'] = "Invalid email format";
		}
		if ($fullname != '') {
			if (!preg_match("/^[a-zA-Z ]*$/", $fullname)) {
				$errors['fullname'] = "Only letters and white space allowed";
			}
		}

		if ($mobile != '') {
			if (strlen($mobile) != 10) {
				$errors['mobile'] = 'Only 10 Digits allowed.';
			}
			$first_no = $mobile[0];
			$arr = array('9', '8', '7', '6', '5', '4', '3', '2', '1');
			if (!in_array($first_no, $arr)) {
				$errors['mobile'] = 'Only letters and white space allowed. Mobile number should start with 9 or 8 or 7 or 6 only.';
			}
		}

		//new validations
		if ($services_id == '')
			$errors['id'] = 'Please select service';
		if ($fullname == '')
			$errors['fullname'] = 'Center name is required.';
		if ($emailId == '')
			$errors['emailId'] = 'Email is required.';
		if ($mobile == '')
			$errors['mobile'] = 'Mobile is required.';


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			//if($dob!='') 
			//$dob = @date('Y-m-d', strtotime($dob));
			parent::start_transaction();
			$tableName 	= "services_enquiry";
			$tabFields 	= "(id, services_id,name,email, mobile, state,city,pincode,remark,active,delete_flag,created_by,created_at)";
			$insertVals	= "(NULL,'$services_id','$fullname','$emailId','$mobile','$state','$city','$pincode','$remark','1','0','$created_by',NOW())";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Your service enquiry submitted successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not submit your service enquiry.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
}
