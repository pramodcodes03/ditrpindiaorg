<?php

include('connection.class.php');
class database_results
{

	function __construct()
	{
		$db = new connection();
		$this->mysqli = $db->getDbConnection();
		if ($this->mysqli->connect_errno) {
			//echo "Failed to connect to MySQL: (" .$mysqli->connect_errno . ") " .
			$this->mysqli->connect_error;
		}
	}

	public function insertData($tableName, $tabFields, $insertValues) // function to insert records in table 
	{
		$iQuery = "insert into" . " " . $tableName . " " . $tabFields . "  " . "values" . " " . $insertValues;
		return $iQuery;
	}
	public function updateData($tableName, $setValues, $whereClause) // function to update records in table 
	{
		$uQuery = "update" . " " . $tableName . " " . "set" . " " . $setValues . "  " . $whereClause;
		return $uQuery;
	}
	public function selectData($selVals, $tableName, $whereClause)
	{
		$selQry = "select" . " " . $selVals . " " . "from" . " " . $tableName . " " . $whereClause;
		return $selQry;
	}
	public function deleteData($tableName, $whereClause)
	{
		$uQuery = "delete from " . " " . $tableName . " " . $whereClause;
		return $uQuery;
	}

	public function execQuery($query) // function to execute queries
	{
		$exexQry = $this->mysqli->query($query);
		//if(!$exexQry) echo $this->execQueryError();
		return $exexQry;
	}
	public function execQueryError() // function to execute queries
	{
		$error = $this->mysqli->error;

		return $error;
	}
	public function start_transaction()
	{
		$sql  = "START TRANSACTION ";
		$this->execQuery($sql);
	}
	public function commit()
	{
		$sql  = "COMMIT ";
		$this->execQuery($sql);
	}
	public function rollback()
	{
		$sql  = "ROLLBACK";
		$this->execQuery($sql);
	}
	public function last_id()
	{
		return $this->mysqli->insert_id;
	}
	public function rows_affected()
	{
		return $this->mysqli->affected_rows;
	}
	public function set_mysql_charset()
	{
		return $this->mysqli->set_charset("utf8");
	}
	public function wset_mysql_charset()
	{
		$this->mysqli->set_charset("utf8");
	}
	public function MenuItemsDropdown($tableName, $value, $option, $selVals, $selected, $whereClause) // to list all existing 
	{
		$selVals 		= "$selVals";
		$whereClause	= "$whereClause";
		echo $selectAM 		= $this->selectData($selVals, $tableName, $whereClause);
		$execAM 		= $this->execQuery($selectAM);

		$dropdown = '';
		$dropdown = '<option value="">--select--</option>';
		if ($execAM->num_rows > 0) {
			while ($Row = $execAM->fetch_assoc()) {
				$id 	 	= $Row['' . $value . ''];
				$name 		= $Row['' . $option . ''];
				if ($id == $selected) {
					$dropdown .= '<option value="' . $Row['' . $value . ''] . '" selected>' . ucwords(strtoupper(htmlspecialchars($Row['' . $option . '']))) . '</option>';
				} else {
					$dropdown .= '<option value="' . $Row['' . $value . ''] . '" >' . ucwords(strtoupper(htmlspecialchars(htmlspecialchars($Row['' . $option . ''])))) . '</option>';
				}
			}
		}
		echo $dropdown;
	}

	function get_insert_id()
	{
		$insert_id = $this->mysqli->insert_id;
		return $insert_id;
	}
	//form inputs data cleaning
	function test($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		$data = $this->mysqli->real_escape_string($data);
		return $data;
	}

	function validateDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}

	public function id_exists($tableName, $selVal, $whereClause)
	{
		$sql = $this->selectData($selVal, $tableName, $whereClause);
		$execSql = $this->execQuery($sql);
		if (!$execSql) {
			return false;
		}
		$row = $execSql->num_rows;
		if ($row > 0)
			return true;
		else {
			return false;
		}
	}
	public function get_value_by_id($tableName, $where)
	{
		$sql = "SELECT * FROM $tableName WHERE $where";
		$res = $this->execQuery($sql);
		if (!$res)
			return false;
		$data = $res->fetch_assoc();
		return $data;
	}




	public function get_user_name($user_id)
	{
		$tableName = "app_users";
		$selVals = "NAME";
		$whereClause = " WHERE USER_ID=" . $user_id;
		$sql = $this->selectData($selVals, $tableName, $whereClause);
		$res = $this->execQuery($sql);
		if (!$res) {
			return false;
		}
		$name = $res->fetch_assoc();
		return $name['NAME'];
	}
	public function get_user_role($role_id)
	{
		$tableName = "admin_role_master";
		$selVals = "ROLE_NAME";
		$whereClause = " WHERE ADMIN_ROLE_ID=" . $role_id;
		$sql = $this->selectData($selVals, $tableName, $whereClause);
		$res = $this->execQuery($sql);
		if (!$res) {
			return false;
		}
		$name = $res->fetch_assoc();
		return $name['ROLE_NAME'];
	}
	public function add_gallery()
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		$title		 	= $this->test(isset($_POST['title']) ? $_POST['title'] : '');
		$description 	= $this->test(isset($_POST['description']) ? $_POST['description'] : '');
		$type		 	= $this->test(isset($_POST['type']) ? $_POST['type'] : '');
		$status 		= $this->test(isset($_POST['status']) ? $_POST['status'] : '');
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
			$tableName  	= 'gallery';
			$tabFields  	= '(GALLERY_ID,GALLERY_TITLE, GALLERY_DESC,GALLERY_TYPE,ACTIVE, CREATED_BY, CREATED_ON)';
			$insertValues 	= "(NULL, '$title','$description','$type','$status','$created_by',NOW())";
			$insert 		= $this->insertData($tableName, $tabFields, $insertValues);
			$exec   		= $this->execQuery($insert);
			$current_id 	= $this->last_id();
			if ($exec) {
				$message = '';
				while (list($key, $value) = each($_FILES["event_imgs"]["name"])) {
					//rename the file
					$true = true;
					$ext 			= pathinfo($_FILES["event_imgs"]["name"][$key], PATHINFO_EXTENSION);
					$allowed_ext 	= array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'cdr', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'tar', 'cdr', 'JPG', 'JPEG', 'PNG', 'GIF', 'PDF', 'CDR', 'DOC', 'DOCX', 'PPT', 'PPTX', 'XLS', 'XLSX', 'TXT', 'ZIP', 'TAR', 'CDR', 'wmv');
					if ($true) {
						$file_name 		= $_FILES["event_imgs"]["name"][$key];
						$tableName  	= 'gallery_files';
						$tabFields  	= '(GALLERY_FILE_ID,GALLERY_ID, FILE_NAME,FILE_MIME,ACTIVE, CREATED_BY, CREATED_ON)';
						$insertValues 	= "(NULL, '$current_id','$file_name','$ext','$status','$created_by',NOW())";
						$insert 		= $this->insertData($tableName, $tabFields, $insertValues);
						$exec   		= $this->execQuery($insert);
						$path = GALLERY;
						if ($type == 'marketing')	$path = '../uploads/marketing';
						$courseImgPathDir 		= $path . '/' . $current_id . '/';
						$courseImgPathFile 		= $courseImgPathDir . '' . $file_name;
						$courseImgThumbPathDir 	= $courseImgPathDir . 'thumb/';
						$courseImgThumbPathFile = $courseImgThumbPathDir . '' . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						@mkdir($courseImgThumbPathDir, 0777, true);


						if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif'))) {

							include_once('access.class.php');
							$access = new access();
							$access->create_thumb_img($_FILES["event_imgs"]["tmp_name"][$key], $courseImgThumbPathFile,  $ext, 800, 750);
						}
						@move_uploaded_file($_FILES["event_imgs"]["tmp_name"][$key], $courseImgPathFile);
					} else {
						$message .= '<br><strong>' . $_FILES["event_imgs"]["name"][$key] . '</strong> : file is not supported!';
					}
				}
				$data['success'] = true;
				$data['message'] = 'Success! New gallery has been added successfully!' . $message;
			}
		}
		return json_encode($data);
	}
	public function update_gallery()
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		$gallery_id		 	= $this->test(isset($_POST['gallery_id']) ? $_POST['gallery_id'] : '');
		$type		 	= $this->test(isset($_POST['type']) ? $_POST['type'] : '');
		$title		 	= $this->test(isset($_POST['title']) ? $_POST['title'] : '');
		$description 	= $this->test(isset($_POST['description']) ? $_POST['description'] : '');
		$status 		= $this->test(isset($_POST['status']) ? $_POST['status'] : '');
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
			$tableName  	= 'gallery';
			$setValues  	= "GALLERY_TITLE='$title', GALLERY_DESC='$description',ACTIVE='$status', UPDATED_BY='$created_by', UPDATED_ON=NOW()";
			$whereClause = " WHERE GALLERY_ID='$gallery_id'";
			$updateSql	= $this->updateData($tableName, $setValues, $whereClause);
			$exec   		= $this->execQuery($updateSql);
			if ($event_img != '') {
				$message = '';
				while (list($key, $value) = each($_FILES["event_imgs"]["name"])) {
					//rename the file
					$true = true;
					$ext 			= pathinfo($_FILES["event_imgs"]["name"][$key], PATHINFO_EXTENSION);
					$allowed_ext 	= array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'cdr', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'tar', 'cdr', 'JPG', 'JPEG', 'PNG', 'GIF', 'PDF', 'CDR', 'DOC', 'DOCX', 'PPT', 'PPTX', 'XLS', 'XLSX', 'TXT', 'ZIP', 'TAR', 'CDR', 'wmv');
					$file_name 		=	$_FILES["event_imgs"]["name"][$key];
					if ($true) {
						$tableName  	= 'gallery_files';
						$tabFields  	= '(GALLERY_FILE_ID,GALLERY_ID, FILE_NAME,FILE_MIME,ACTIVE, CREATED_BY, CREATED_ON)';
						$insertValues 	= "(NULL, '$gallery_id','$file_name','$ext','$status','$created_by',NOW())";
						$insert 		= $this->insertData($tableName, $tabFields, $insertValues);
						$exec   		= $this->execQuery($insert);
						$path = GALLERY;
						if ($type == 'marketing')	$path = '../uploads/marketing';

						$courseImgPathDir 		= $path . '/' . $gallery_id . '/';
						$courseImgPathFile 		= $courseImgPathDir . '' . $file_name;
						$courseImgThumbPathDir 	= $courseImgPathDir . 'thumb/';
						$courseImgThumbPathFile = $courseImgThumbPathDir . '' . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						@mkdir($courseImgThumbPathDir, 0777, true);

						if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif'))) {

							include_once('access.class.php');
							$access = new access();
							$access->create_thumb_img($_FILES["event_imgs"]["tmp_name"][$key], $courseImgThumbPathFile,  $ext, 800, 750);
						}
						@move_uploaded_file($_FILES["event_imgs"]["tmp_name"][$key], $courseImgPathFile);
					} else {

						$message .= '<br><strong>' . $_FILES["event_imgs"]["name"][$key] . '</strong> : file is not supported!';
					}
				}
				$data['success'] = true;
				$data['message'] = 'Success! Gallery has been updated successfully!' . $message;
			}
		}
		return json_encode($data);
	}
	public function list_gallery($id = 0, $type = '', $status = '')
	{
		$tableName = "gallery";
		$selVals = "*, (SELECT COUNT(*) FROM gallery_files WHERE GALLERY_ID=gallery.GALLERY_ID) AS TOTAL_FILES , DATE_FORMAT(CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE";
		$whereClause = "WHERE DELETE_FLAG=0 ";
		if ($id != 0)
			$whereClause .= " AND GALLERY_ID='$id'";
		if ($type != '')
			$whereClause .= " AND GALLERY_TYPE='$type'";
		if ($status != '')
			$whereClause .= " AND ACTIVE='$status'";

		$whereClause .= ' ORDER BY CREATED_ON DESC';
		$sql = $this->selectData($selVals, $tableName, $whereClause);
		$res = $this->execQuery($sql);
		if (!$res) {
			return false;
		}
		return $res;
	}
	public function list_gallery_files_all($gallery_id = 0, $cond = '')
	{
		$tableName = "gallery_files A LEFT JOIN gallery B ON A.GALLERY_ID=B.GALLERY_ID";
		$selVals = "A.*, B.GALLERY_TYPE";
		$whereClause = "WHERE A.DELETE_FLAG=0 ";
		if ($gallery_id != 0)
			$whereClause .= " AND A.GALLERY_ID='$gallery_id'";
		if ($cond != 0)
			$whereClause .= " $cond";
		$sql = $this->selectData($selVals, $tableName, $whereClause);
		$res = $this->execQuery($sql);
		if (!$res) {
			return false;
		}
		return $res;
	}
	public function list_gallery_files_single($gallery_id)
	{
		$image = '';
		$tableName = "gallery_files";
		$selVals = "*";
		$whereClause = " WHERE DELETE_FLAG=0 AND GALLERY_ID='$gallery_id' AND FILE_MIME IN('jpg','png','jpeg','JPG','gif') ";

		$whereClause .= "LIMIT 0,1 ";
		$sql = $this->selectData($selVals, $tableName, $whereClause);
		$res = $this->execQuery($sql);
		if (!$res) {
			return false;
		} else {
			$data = $res->fetch_assoc();
			$image = $data['FILE_NAME'];
		}
		return $image;
	}
	public function delete_gallery_file($id)
	{
		/*$tableName  	= 'gallery_files';
		$setValues  	= "DELETE_FLAG=1, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW()";
		$whereClause = " WHERE GALLERY_FILE_ID='$id'";
		 $updateSql	= $this->updateData($tableName,$setValues,$whereClause);*/
		$updateSql = "DELETE FROM gallery_files WHERE GALLERY_FILE_ID='$id'";
		$exec   		= $this->execQuery($updateSql);
		if (!$exec) {
			return false;
		}
		return true;
	}
	public function delete_gallery($id)
	{
		/*$tableName  	= 'gallery';
		$setValues  	= "DELETE_FLAG=1, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW()";
		$whereClause = " WHERE GALLERY_ID='$id'";
		 $updateSql	= $this->updateData($tableName,$setValues,$whereClause);*/
		$updateSql = "DELETE FROM gallery WHERE GALLERY_ID='$id'";
		$updateSql2 = "DELETE FROM gallery_files WHERE GALLERY_ID='$id'";
		$exec   		= $this->execQuery($updateSql);
		$exec2   		= $this->execQuery($updateSql2);
		if (!$exec) {
			return false;
		}
		return true;
	}
	public function change_gallery_status($gallery_id, $flag)
	{
		$tableName  	= 'gallery';
		$setValues  	= "ACTIVE='$flag', UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW()";
		$whereClause = " WHERE GALLERY_ID='$gallery_id'";
		$updateSql	= $this->updateData($tableName, $setValues, $whereClause);
		$exec   		= $this->execQuery($updateSql);
		if (!$exec) {
			return false;
		}
		return true;
	}
	public function get_course_detail($course_id)
	{
		$course_name = array();
		$selVals = '*';
		$tableName = "courses";
		$selVals .= " ,get_course_award_name (COURSE_AWARD) AS COURSE_AWARD, get_course_title_modify(COURSE_ID) AS COURSE_NAME_MODIFY";

		$whereClause = " WHERE COURSE_ID='$course_id' AND DELETE_FLAG=0 ";
		$whereClause .= " LIMIT 0,1";
		$sql = $this->selectData($selVals, $tableName, $whereClause);
		$res = $this->execQuery($sql);
		while ($data = $res->fetch_assoc()) {
			//print_r($data); exit();
			$course_name['COURSE_ID'] = $data['COURSE_ID'];
			$course_name['COURSE_NAME'] = $data['COURSE_NAME'];
			$course_name['COURSE_FEES'] = $data['COURSE_FEES'];

			$course_name['COURSE_MRP'] = $data['COURSE_MRP'];
			$course_name['MINIMUM_AMOUNT'] = $data['MINIMUM_AMOUNT'];

			//$course_name['EXAM_FEES'] = $data['EXAM_FEES'];
			$course_name['COURSE_DURATION'] = $data['COURSE_DURATION'];
			$course_name['COURSE_DETAILS'] = $data['COURSE_DETAILS'];
			$course_name['COURSE_ELIGIBILITY'] = $data['COURSE_ELIGIBILITY'];
			$course_name['ACTIVE'] = $data['ACTIVE'];

			$course_name['COURSE_CODE'] = $data['COURSE_CODE'];
			$course_name['COURSE_AWARD'] = $data['COURSE_AWARD'];
			$course_name['COURSE_NAME_MODIFY'] = $data['COURSE_NAME_MODIFY'];
			$course_name['COURSE_IMAGE'] = $data['COURSE_IMAGE'];
			$course_name['VIDEO1'] = $data['VIDEO1'];
			$course_name['VIDEO2'] = $data['VIDEO2'];
		}
		return $course_name;
	}

	/// for multi sub course
	public function get_course_detail_multi_sub($multisubcourse_id)
	{
		$course_name = array();
		$selVals = '*';

		$tableName = "multi_sub_courses";
		$selVals .= " ,get_course_award_name (MULTI_SUB_COURSE_AWARD) AS MULTI_SUB_COURSE_AWARD, get_course_multi_sub_title_modify(MULTI_SUB_COURSE_ID) AS COURSE_NAME_MODIFY";


		$whereClause = " WHERE MULTI_SUB_COURSE_ID='$multisubcourse_id' AND DELETE_FLAG=0 ";
		$whereClause .= " LIMIT 0,1";
		$sql = $this->selectData($selVals, $tableName, $whereClause);
		$res = $this->execQuery($sql);
		while ($data = $res->fetch_assoc()) {
			$course_name['MULTI_SUB_COURSE_ID'] = $data['MULTI_SUB_COURSE_ID'];
			$course_name['MULTI_SUB_COURSE_NAME'] = $data['MULTI_SUB_COURSE_NAME'];
			$course_name['MULTI_SUB_COURSE_FEES'] = $data['MULTI_SUB_COURSE_FEES'];

			$course_name['MULTI_SUB_COURSE_MRP'] = $data['MULTI_SUB_COURSE_MRP'];
			$course_name['MULTI_SUB_MINIMUM_AMOUNT'] = $data['MULTI_SUB_MINIMUM_AMOUNT'];

			//$course_name['EXAM_FEES'] = $data['EXAM_FEES'];
			$course_name['MULTI_SUB_COURSE_DURATION'] = $data['MULTI_SUB_COURSE_DURATION'];
			$course_name['MULTI_SUB_COURSE_DETAILS'] = $data['MULTI_SUB_COURSE_DETAILS'];
			$course_name['MULTI_SUB_COURSE_ELIGIBILITY'] = $data['MULTI_SUB_COURSE_ELIGIBILITY'];
			$course_name['ACTIVE'] = $data['ACTIVE'];

			$course_name['MULTI_SUB_COURSE_CODE'] = $data['MULTI_SUB_COURSE_CODE'];
			$course_name['MULTI_SUB_COURSE_AWARD'] = $data['MULTI_SUB_COURSE_AWARD'];
			$course_name['COURSE_NAME_MODIFY'] = $data['COURSE_NAME_MODIFY'];
			$course_name['MULTI_SUB_COURSE_IMAGE'] = $data['MULTI_SUB_COURSE_IMAGE'];
			$course_name['VIDEO1'] = $data['VIDEO1'];
			$course_name['VIDEO2'] = $data['VIDEO2'];
		}
		return $course_name;
	}

	//for typing course	
	public function get_course_detail_typing($course_id)
	{
		$course_name = array();
		$selVals = '*';

		$tableName = "courses_typing";
		$selVals .= " ,TYPING_COURSE_NAME AS COURSE_NAME_MODIFY";


		$whereClause = " WHERE TYPING_COURSE_ID ='$course_id' AND DELETE_FLAG=0 ";
		$whereClause .= " LIMIT 0,1";
		$sql = $this->selectData($selVals, $tableName, $whereClause);
		$res = $this->execQuery($sql);
		while ($data = $res->fetch_assoc()) {
			$course_name['TYPING_COURSE_ID'] = $data['TYPING_COURSE_ID'];
			$course_name['TYPING_COURSE_NAME'] = $data['TYPING_COURSE_NAME'];
			$course_name['TYPING_COURSE_FEES'] = $data['TYPING_COURSE_FEES'];

			$course_name['TYPING_COURSE_MRP'] = $data['TYPING_COURSE_MRP'];
			$course_name['TYPING_MINIMUM_AMOUNT'] = $data['TYPING_MINIMUM_AMOUNT'];

			//$course_name['EXAM_FEES'] = $data['EXAM_FEES'];
			$course_name['TYPING_COURSE_DURATION'] = $data['TYPING_COURSE_DURATION'];
			$course_name['TYPING_COURSE_DETAILS'] = $data['TYPING_COURSE_DETAILS'];
			$course_name['TYPING_COURSE_ELIGIBILITY'] = $data['TYPING_COURSE_ELIGIBILITY'];
			$course_name['ACTIVE'] = $data['ACTIVE'];

			$course_name['TYPING_COURSE_CODE'] = $data['TYPING_COURSE_CODE'];
			$course_name['COURSE_NAME_MODIFY'] = $data['COURSE_NAME_MODIFY'];
			$course_name['TYPING_COURSE_IMAGE'] = $data['TYPING_COURSE_IMAGE'];
			$course_name['DISPLAY_FEES'] = $data['DISPLAY_FEES'];
		}
		return $course_name;
	}

	public function get_stud_name($stud_id)
	{
		$stud_name = '';
		$sql = "SELECT CONCAT(CONCAT(STUDENT_FNAME,' ',STUDENT_MNAME),' ',STUDENT_LNAME) AS STUD_NAME FROM student_details WHERE STUDENT_ID='$stud_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['STUD_NAME'];
		}
		return $stud_name;
	}
	// student code
	public function get_stud_code($stud_id)
	{
		$stud_name = '';
		$sql = "SELECT STUDENT_CODE FROM student_details WHERE STUDENT_ID='$stud_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['STUDENT_CODE'];
		}
		return $stud_name;
	}
	public function get_stud_refferalcode($stud_id)
	{
		$stud_name = '';
		$sql = "SELECT REFFERAL_CODE FROM student_details WHERE STUDENT_ID='$stud_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['REFFERAL_CODE'];
		}
		return $stud_name;
	}
	public function get_course_name($course_id)
	{
		$stud_name = '';
		$sql = "SELECT COURSE_NAME  FROM courses WHERE COURSE_ID='$course_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['COURSE_NAME'];
		}
		return $stud_name;
	}

	//multi_sub
	public function get_course_name_multi_sub($multi_sub_course_id)
	{
		$stud_name = '';
		$sql = "SELECT MULTI_SUB_COURSE_NAME  FROM multi_sub_courses WHERE MULTI_SUB_COURSE_ID='$multi_sub_course_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['MULTI_SUB_COURSE_NAME'];
		}
		return $stud_name;
	}

	//course
	public function get_course_duration($course_id)
	{
		$stud_name = '';
		$sql = "SELECT COURSE_DURATION FROM courses WHERE COURSE_ID='$course_id'";
		//echo $sql; exit();
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['COURSE_DURATION'];
		}
		return $stud_name;
	}

	public function get_course_duration_multi_sub($multi_sub_course_id)
	{
		$stud_name = '';
		$sql = "SELECT MULTI_SUB_COURSE_DURATION FROM multi_sub_courses WHERE MULTI_SUB_COURSE_ID='$multi_sub_course_id'";
		//echo $sql; exit();
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['MULTI_SUB_COURSE_DURATION'];
		}
		return $stud_name;
	}

	public function get_course_duration_typing($typing_course_id)
	{
		$stud_name = '';
		$sql = "SELECT TYPING_COURSE_DURATION FROM courses_typing WHERE TYPING_COURSE_ID='$typing_course_id'";
		//echo $sql; exit();
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['TYPING_COURSE_DURATION'];
		}
		return $stud_name;
	}

	public function get_course_award($course_id)
	{
		$stud_name = '';
		$sql = "SELECT B.AWARD  FROM courses A LEFT JOIN course_awards B ON A.COURSE_AWARD=B.AWARD_ID WHERE A.COURSE_ID='$course_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['AWARD'];
		}
		return $stud_name;
	}
	public function get_exam_total_marks($course_id)
	{
		$stud_name = '';
		$sql = "SELECT get_exam_structure_total_marks ($course_id) AS TOTAL_MARKS";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['TOTAL_MARKS'];
		}
		return $stud_name;
	}
	public function get_exam_structure($course_id)
	{
		$result = array();
		$sql = "SELECT * FROM exam_structure WHERE COURSE_ID='$course_id' AND ACTIVE=1 AND DELETE_FLAG=0 LIMIT 0,1";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result  = $res;
		}
		return $result;
	}
	// for multi sub course exam structure
	public function get_exam_structure_multi_sub($course_id)
	{
		$result = array();

		$sql = "SELECT * FROM multi_sub_course_exam_structure WHERE MULTI_SUB_COURSE_ID='$course_id' AND ACTIVE=1 AND DELETE_FLAG=0";

		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result  = $res;
		}
		return $result;
	}

	public function get_exam_structure_typing_course($course_id)
	{
		$result = array();

		$sql = "SELECT * FROM course_typing_exam_structure WHERE TYPING_COURSE_ID ='$course_id' AND ACTIVE=1 AND DELETE_FLAG=0";

		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result  = $res;
		}
		return $result;
	}

	public function get_exam_ques_count($course_id)
	{
		$result = 0;
		$sql = "SELECT COUNT(*) AS TOTAL_QUESTIONS_COUNT FROM exam_question_bank WHERE COURSE_ID='$course_id' AND  ACTIVE=1 AND DELETE_FLAG=0";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['TOTAL_QUESTIONS_COUNT'];
		}
		return $result;
	}
	// for multi sub course exam
	public function get_exam_ques_count_multi_sub($course_id)
	{
		$result = 0;
		$sql = "SELECT COUNT(*) AS TOTAL_QUESTIONS_COUNT FROM multi_sub_exam_question_bank WHERE MULTI_SUB_COURSE_ID='$course_id' AND  ACTIVE=1 AND DELETE_FLAG=0";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['TOTAL_QUESTIONS_COUNT'];
		}
		return $result;
	}
	/*public function validate_apply_exam($aicpe_course_id)
	{
		$errors = array();
		$data = array();
		$data['success'] = false;
		$res = $this->get_exam_structure($aicpe_course_id);
		if(!empty($res))
		{
			$data1 = $res->fetch_assoc();
			$TOTAL_QUESTIONS = $data1['TOTAL_QUESTIONS'];
			$EXAM_MODE_TYPE = $data1['EXAM_MODE_TYPE'];
			$TOTAL_QUESTIONS_QUE_BANK =$this->get_exam_ques_count($aicpe_course_id);
			if($TOTAL_QUESTIONS_QUE_BANK!=0)
			{
				if($TOTAL_QUESTIONS>$TOTAL_QUESTIONS_QUE_BANK)
				{
					$errors['qb_insufficient'] = "QB insuffiecient!";
				}else{
					$data['success'] = true;
					$data['exam_modes'] = $EXAM_MODE_TYPE;
				}
			}else{
				$errors['qb_unavailable'] = "QB unavailable!";
			}
		}else{
			$errors['exam_unavailable'] = "Exam unavailable!";
		}
		if(!empty($errors)){
			$data['errors'] = $errors;	
			$data['success'] = false;
		}			
		return $data;
	}*/
	public function validate_apply_exam($aicpe_course_id, $aicpe_course_id_multi, $course_typing)
	{
		$errors = array();
		$data = array();
		$data['success'] = false;
		if ($aicpe_course_id != '' && !empty($aicpe_course_id)) {

			$res = $this->get_exam_structure($aicpe_course_id);
		}
		if ($aicpe_course_id_multi != '' && !empty($aicpe_course_id_multi)) {

			$res = $this->get_exam_structure_multi_sub($aicpe_course_id_multi);
		}
		if ($course_typing != '' && !empty($course_typing)) {

			$res = $this->get_exam_structure_typing_course($course_typing);
		}
		if (!empty($res)) {
			$data1 = $res->fetch_assoc();
			$TOTAL_QUESTIONS = $data1['TOTAL_QUESTIONS'];
			$EXAM_MODE_TYPE = $data1['EXAM_MODE_TYPE'];

			if ($aicpe_course_id != '' && !empty($aicpe_course_id)) {
				$TOTAL_QUESTIONS_QUE_BANK = $this->get_exam_ques_count($aicpe_course_id);
			}
			if ($aicpe_course_id_multi != '' && !empty($aicpe_course_id_multi)) {
				$TOTAL_QUESTIONS_QUE_BANK = 50;
			}
			if ($course_typing != '' && !empty($course_typing)) {
				$TOTAL_QUESTIONS_QUE_BANK = 1000;
			}

			if ($TOTAL_QUESTIONS_QUE_BANK != 0) {
				if ($TOTAL_QUESTIONS > $TOTAL_QUESTIONS_QUE_BANK) {
					$errors['qb_insufficient'] = "QB insuffiecient!";
				} else {
					$data['success'] = true;
					$data['exam_modes'] = $EXAM_MODE_TYPE;
				}
			} else {
				$errors['qb_unavailable'] = "QB unavailable!";
			}
		} else {
			$errors['exam_unavailable'] = "Exam unavailable!";
		}
		if (!empty($errors)) {
			$data['errors'] = $errors;
			$data['success'] = false;
		}
		return $data;
	}
	public function get_student_files($stud_id, $doctype = '')
	{
		$result = '';
		$sql = "SELECT FILE_LABEL FROM student_files WHERE STUDENT_ID='$stud_id' AND ACTIVE=1 AND DELETE_FLAG=0";
		if ($doctype != '')
			$sql .= " AND FILE_LABEL='$doctype'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res;
		}
		return $result;
	}
	public function rename_offline_paper_pdf($course_name, $file_type)
	{
		$filename = trim(addslashes($course_name));
		$filename = str_replace(' ', '_', $filename);
		$curr_date		= date('d/m/Y');
		$rand_num		= mt_rand(5, 15);
		$filename		= $curr_date . '_' . $filename . '_' . $file_type . '_' . $rand_num;
		return $filename;
	}


	//get institute id by staff id
	public function get_parent_id($user_role, $staff_id)
	{
		$res = "";
		//admin staff
		if ($user_role == 6)
			$sql = "SELECT ADMIN_ID AS PARENT_ID FROM admin_staff_details WHERE STAFF_ID='$staff_id'";
		//institute staff
		if ($user_role == 3)
			$sql = "SELECT INSTITUTE_ID AS PARENT_ID FROM admin_staff_details WHERE STAFF_ID='$staff_id'";

		$ex = $this->execQuery($sql);
		if ($ex && $ex->num_rows > 0) {
			$data = $ex->fetch_assoc();
			$res = $data['PARENT_ID'];
		}
		return $res;
	}
	//get course name from institute couse table
	/*public function get_inst_course_name($inst_course_id)
	{
		$res = "";
		$sql= "SELECT * FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
		$ex = $this->execQuery($sql);
		if($ex && $ex->num_rows>0)
		{
			$data = $ex->fetch_assoc();
			$COURSE_ID =$data['COURSE_ID'];
			$COURSE_TYPE =$data['COURSE_TYPE'];
			$course = $this->get_course_detail($COURSE_ID,$COURSE_TYPE);
			$res = $course['COURSE_NAME_MODIFY'];
		}
		return $res;
	}*/
	//get course name from institute couse table
	public function get_inst_course_name($inst_course_id)
	{
		$res = "";
		$sql = "SELECT * FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
		$ex = $this->execQuery($sql);
		if ($ex && $ex->num_rows > 0) {
			$data = $ex->fetch_assoc();
			$COURSE_ID 			 = $data['COURSE_ID'];
			$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
			$TYPING_COURSE_ID = $data['TYPING_COURSE_ID'];

			if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
				$course = $this->get_course_detail($COURSE_ID);
				$res = $course['COURSE_NAME_MODIFY'];
			}
			if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
				$course = $this->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
				$res = $course['COURSE_NAME_MODIFY'];
			}
			if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
				$course = $this->get_course_detail_typing($TYPING_COURSE_ID);
				$res = $course['COURSE_NAME_MODIFY'];
			}
		}
		return $res;
	}
	public function get_inst_course_duration($inst_course_id)
	{
		$res = "";
		$sql = "SELECT * FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
		$ex = $this->execQuery($sql);
		if ($ex && $ex->num_rows > 0) {
			$data = $ex->fetch_assoc();
			$COURSE_ID 			 = $data['COURSE_ID'];
			$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
			$TYPING_COURSE_ID = $data['TYPING_COURSE_ID'];

			if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
				$course = $this->get_course_detail($COURSE_ID);
				$res = $course['COURSE_DURATION'];
			}
			if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
				$course = $this->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
				$res = $course['MULTI_SUB_COURSE_DURATION'];
			}
			if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
				$course = $this->get_course_detail_typing($TYPING_COURSE_ID);
				$res = $course['TYPING_COURSE_DURATION'];
			}
		}
		return $res;
	}
	public function get_inst_course_code($inst_course_id)
	{
		$res = "";
		$sql = "SELECT * FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
		$ex = $this->execQuery($sql);
		if ($ex && $ex->num_rows > 0) {
			$data = $ex->fetch_assoc();
			$COURSE_ID 			 = $data['COURSE_ID'];
			$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];

			if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
				$course = $this->get_course_detail($COURSE_ID);
				$res = $course['COURSE_CODE'];
			}
			if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
				$course = $this->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
				$res = $course['MULTI_SUB_COURSE_CODE'];
			}
		}
		return $res;
	}
	// public function get_inst_course_name($inst_course_id)
	// {
	// 	$res = "";
	// 	$sql= "SELECT * FROM courses WHERE COURSE_ID ='$inst_course_id'";
	// 	$ex = $this->execQuery($sql);
	// 	if($ex && $ex->num_rows>0)
	// 	{
	// 		$data = $ex->fetch_assoc();
	// 		//$COURSE_ID 			 = $data['COURSE_ID'];
	// 		$res = $data['COURSE_NAME'];
	// 		// $COURSE_TYPE =$data['COURSE_TYPE'];
	// 		// if($COURSE_ID!=''){
	// 		// 	$course = $this->get_course_detail($COURSE_ID,$COURSE_TYPE);
	// 		// 	$res = $course['COURSE_NAME_MODIFY'];
	// 		// }
	// 		// if($MULTI_SUB_COURSE_ID!=''){
	// 		// 	$course = $this->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID,$COURSE_TYPE);
	// 		// 	$res = $course['COURSE_NAME_MODIFY'];
	// 		// }
	// 	}
	// 	return $res;
	// }
	public function get_inst_course_exam_fees($inst_course_id)
	{
		$res = array();
		$sql = "SELECT COURSE_FEES FROM courses WHERE COURSE_ID ='$inst_course_id'";
		$ex = $this->execQuery($sql);
		if ($ex && $ex->num_rows > 0) {
			$data = $ex->fetch_assoc();
			$res['COURSE_FEES'] = $data['COURSE_FEES'];
		}
		return $res;
	}
	//get course name from institute couse table
	/*public function get_inst_course_info($inst_course_id)
	{
		$res = "";
		 $sql= "SELECT * FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
		$ex = $this->execQuery($sql);
		if($ex && $ex->num_rows>0)
		{
			$data = $ex->fetch_assoc();
			$COURSE_ID =$data['COURSE_ID'];
			$COURSE_TYPE =$data['COURSE_TYPE'];
			$course = $this->get_course_detail($COURSE_ID,$COURSE_TYPE);
			$res= $course;
		}
		return $res;
	}*/
	//get course name from institute couse table
	public function get_inst_course_info($inst_course_id)
	{
		$res = "";
		$sql = "SELECT * FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
		$ex = $this->execQuery($sql);
		if ($ex && $ex->num_rows > 0) {
			$data = $ex->fetch_assoc();
			$COURSE_ID = $data['COURSE_ID'];
			$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
			$TYPING_COURSE_ID = $data['TYPING_COURSE_ID'];

			if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
				$course = $this->get_course_detail($COURSE_ID);
			}

			if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
				$course = $this->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
			}

			if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
				$course = $this->get_course_detail_typing($TYPING_COURSE_ID);
			}

			$res = $course;
		}
		return $res;
	}
	//get course name from institute couse table
	public function get_aicpe_course_files($course_id)
	{
		$res = "";
		$sql = "SELECT * FROM courses_files WHERE COURSE_ID='$course_id' AND DELETE_FLAG=0 AND ACTIVE=1";
		$ex = $this->execQuery($sql);
		if ($ex && $ex->num_rows > 0) {
			$res = $ex;
		}
		return $res;
	}


	public function get_inst_staff_name($staff_id = 0)
	{
		$stud_name = '';
		$sql = "SELECT STAFF_FULLNAME  FROM institute_staff_details WHERE STAFF_ID='$staff_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['STAFF_FULLNAME'];
		}
		return $stud_name;
	}
	public function get_institute_name($inst_id = 0)
	{
		$stud_name = '';
		$sql = "SELECT INSTITUTE_NAME  FROM institute_details WHERE INSTITUTE_ID='$inst_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['INSTITUTE_NAME'];
		}
		return $stud_name;
	}
	// for support

	public function get_institute_mobile($inst_id = 0)
	{
		$stud_name = '';
		$sql = "SELECT MOBILE  FROM institute_details WHERE INSTITUTE_ID='$inst_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['MOBILE'];
		}
		return $stud_name;
	}
	public function get_institute_email($inst_id = 0)
	{
		$stud_name = '';
		$sql = "SELECT EMAIL  FROM institute_details WHERE INSTITUTE_ID='$inst_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['EMAIL'];
		}
		return $stud_name;
	}

	public function get_student_name($user_id = 0)
	{
		$name = '';
		$sql = "SELECT CONCAT(STUDENT_FNAME,' ',STUDENT_LNAME) as FULLNAME  FROM student_details WHERE STUDENT_ID='$user_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$name = $data['FULLNAME'];
		}
		return $name;
	}
	//student Email and Mobile
	public function get_student_mobile($user_id = 0)
	{
		$mobile = '';
		$sql = "SELECT STUDENT_MOBILE  FROM student_details WHERE STUDENT_ID='$user_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$mobile = $data['STUDENT_MOBILE'];
		}
		return $mobile;
	}
	public function get_student_email($user_id = 0)
	{
		$email = '';
		$sql = "SELECT STUDENT_EMAIL  FROM student_details WHERE STUDENT_ID='$user_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$email = $data['STUDENT_EMAIL'];
		}
		return $email;
	}

	public function get_student_institute_id($user_id = '')
	{
		$id = '';
		$sql = "SELECT INSTITUTE_ID  FROM student_details WHERE STUDENT_ID='$user_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$id = $data['INSTITUTE_ID'];
		}
		return $id;
	}

	public function get_inst_course_fees($inst_course_id)
	{
		$coursefee = 0;
		$sql = "SELECT COURSE_FEES FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$coursefee = $data['COURSE_FEES'];
		}
		return $coursefee;
	}

	public function get_course_exam_fees($inst_course_id)
	{
		$coursefee = 0;
		$sql = "SELECT EXAM_FEES FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$coursefee = $data['EXAM_FEES'];
		}
		return $coursefee;
	}

	public function get_institute_code($inst_id)
	{
		$stud_name = '';
		$sql = "SELECT INSTITUTE_CODE FROM institute_details WHERE INSTITUTE_ID='$inst_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_name = $data['INSTITUTE_CODE'];
		}
		return $stud_name;
	}
	public function get_responsibilities($id = '', $role = '', $cond = '')
	{
		$result = '';
		$sql = "SELECT * FROM user_responsibilities_master WHERE 1 ";
		if ($id != '') {
			$sql .= " AND RESPONSIBILITY_ID='$id'";
		}
		if ($role != '') {
			$sql .= " AND USER_ROLE='$role'";
		}
		if ($cond != '') {
			$sql .= " $cond ";
		}
		$sql .= " ORDER BY SORT_ORDER ASC";
		// echo $sql;
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res;
		}
		return $result;
	}


	public function list_contact_enquiries()
	{
		$data = '';
		$tableName = "contact_enquiry";
		$selVals = "*, CONCAT(FNAME,' ',LNAME) AS NAME, DATE_FORMAT(CREATED_ON,'%d-%m-%Y %h:%i %p') AS CREATED_DATE";
		$whereClause = " ORDER BY CREATED_ON DESC ";
		$sql = $this->selectData($selVals, $tableName, $whereClause);
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}
	public function list_course_enquiries()
	{
		$data = '';
		$tableName = "website_course_enquiry";
		$selVals = "*, CONCAT(FNAME,' ',LNAME) AS NAME,get_course_title_modify(COURSE_ID) AS COURSE_NAME, DATE_FORMAT(CREATED_ON,'%d-%m-%Y %h:%i %p') AS CREATED_DATE";
		$whereClause = " ORDER BY CREATED_ON DESC ";
		$sql = $this->selectData($selVals, $tableName, $whereClause);
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}

	public function add_page()
	{
		$errors = array();
		$data = array();
		$page_name 		= $this->test(isset($_POST['page_name']) ? $_POST['page_name'] : '');
		$page_title		= $this->test(isset($_POST['page_title']) ? $_POST['page_title'] : '');
		$page_link 		= $this->test(isset($_POST['page_link']) ? $_POST['page_link'] : '');
		$meta_keys 		= $this->test(isset($_POST['meta_keys']) ? $_POST['meta_keys'] : '');
		$meta_desc 		= $this->test(isset($_POST['meta_desc']) ? $_POST['meta_desc'] : '');
		$page_data 		= isset($_POST['page_data']) ? $_POST['page_data'] : '';
		$status 		= isset($_POST['status']) ? $_POST['status'] : '';
		$is_dynamic 		= isset($_POST['is_dynamic']) ? $_POST['is_dynamic'] : '';
		$created_by 	= $_SESSION['user_name'];
		if ($page_name == '')
			$errors['page_name'] = 'Page name is required.';
		if ($page_title == '')
			$errors['page_title'] = 'Page title is required.';
		if ($page_link == '')
			$errors['page_link'] = 'Page link is required.';
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			$sql = "INSERT INTO page(PAGE_NAME,PAGE_DATA,PAGE_LINK,META_TAGS,META_DESCRIPTION,PAGE_TITLE,IS_DYNAMIC,ACTIVE,CREATED_BY,CREATED_ON) VALUES('$page_name', '$page_data', '$page_link','$meta_keys','$meta_desc','$page_title', '$is_dynamic','$status', '$created_by', NOW())";

			$exc = $this->execQuery($sql);

			if (!$exc) {
				$errors['message'] = 'Sorry! Something went wrong! Could not add the page.';
				$data['success'] = false;
				$data['errors']  = $errors;
			} else {
				$data['success'] = true;
				$data['message'] = 'Success! New page has been added successfully!';
			}
		}
		return json_encode($data);
	}

	public function update_page()
	{
		$errors = array();
		$data = array();
		$page_id 		= $this->test(isset($_POST['page_id']) ? $_POST['page_id'] : '');
		$page_name 		= $this->test(isset($_POST['page_name']) ? $_POST['page_name'] : '');
		$page_title		= $this->test(isset($_POST['page_title']) ? $_POST['page_title'] : '');
		$page_link 		= $this->test(isset($_POST['page_link']) ? $_POST['page_link'] : '');
		$meta_keys 		= $this->test(isset($_POST['meta_keys']) ? $_POST['meta_keys'] : '');
		$meta_desc 		= $this->test(isset($_POST['meta_desc']) ? $_POST['meta_desc'] : '');
		$page_data 		= isset($_POST['page_data']) ? $_POST['page_data'] : '';
		$status 		= isset($_POST['status']) ? $_POST['status'] : '';
		$is_dynamic 		= isset($_POST['is_dynamic']) ? $_POST['is_dynamic'] : '';
		$created_by 	= $_SESSION['user_name'];
		if ($page_name == '')
			$errors['page_name'] = 'Page name is required.';
		if ($page_title == '')
			$errors['page_title'] = 'Page title is required.';
		if ($page_link == '')
			$errors['page_link'] = 'Page link is required.';
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {

			$sql = "UPDATE page SET PAGE_NAME='$page_name', PAGE_DATA='$page_data', PAGE_LINK='$page_link', META_TAGS='$meta_keys', META_DESCRIPTION='$meta_desc', PAGE_TITLE='$page_title',IS_DYNAMIC='$is_dynamic', ACTIVE='$status', UPDATED_BY='$created_by', UPDATED_ON=NOW() WHERE PAGE_ID='$page_id' ";

			$exc = $this->execQuery($sql);

			if (!$exc) {
				$errors['message'] = 'Sorry! Something went wrong! Could not update the page.';
				$data['success'] = false;
				$data['errors']  = $errors;
			} else {
				$data['success'] = true;
				$data['message'] = 'Success! Page has been updated successfully!';
			}
		}
		return json_encode($data);
	}
	public function list_pages($cond)
	{
		$result 	= '';
		$tableName 	= 'page';
		$selVals 	= '*';
		$whereClause = $cond;
		$select 	= $this->selectData($selVals, $tableName, $whereClause);
		$exec       = $this->execQuery($select);
		if ($exec && $exec->num_rows > 0) {
			$result = $exec;
		}
		return $result;
	}
	public function get_inst_staff_responsibilities($staff_id)
	{
		$resp_array = array();
		$sql2 = "SELECT STAFF_RESPONSIBILITIES FROM institute_staff_details WHERE STAFF_ID ='$staff_id'";
		$res2 = $this->execQuery($sql2);
		if ($res2 && $res2->num_rows > 0) {
			$data  		= $res2->fetch_assoc();
			$resp_array = $data['STAFF_RESPONSIBILITIES'];
		}
		return $resp_array;
	}
	public function get_admin_staff_responsibilities($staff_id)
	{
		$resp_array = array();
		$sql2 = "SELECT STAFF_RESPONSIBILITIES FROM admin_staff_details WHERE STAFF_ID ='$staff_id'";
		$res2 = $this->execQuery($sql2);
		if ($res2 && $res2->num_rows > 0) {
			$data  		= $res2->fetch_assoc();
			$resp_array = $data['STAFF_RESPONSIBILITIES'];
		}
		return $resp_array;
	}
	public function permission($action)
	{
		$result = false;
		$staff_id 			= $_SESSION['user_id'];
		$user_role 			= $_SESSION['user_role'];
		if ($user_role == 3 || $user_role == 6) {
			$resposibilities = json_decode($this->get_admin_staff_responsibilities($staff_id));
			if (is_array($resposibilities) && in_array($action, $resposibilities)) {
				$result = true;
			}
		} else {
			$result = true;
		}
		return $result;
	}
	public function certificate_pending($stud_id, $course_id, $multi_course_id)
	{
		$result = false;
		//$sql = "SELECT COUNT(*) AS COUNT_TOTAL FROM certificate_requests WHERE STUDENT_ID=$stud_id AND INSTITUTE_ID='$inst_id' AND ACTIVE=1 AND DELETE_FLAG=0 AND REQUEST_STATUS=1";
		$sql = "SELECT COUNT(*) AS COUNT_TOTAL FROM certificate_requests WHERE STUDENT_ID=$stud_id AND ACTIVE=1 AND DELETE_FLAG=0 ";
		if ($course_id != '') {
			$sql .= " AND COURSE_ID ='$course_id'";
		}
		if ($multi_course_id != '') {
			$sql .= " AND MULTI_SUB_COURSE_ID ='$multi_course_id'";
		}
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$COUNT_TOTAL = $data['COUNT_TOTAL'];
			if ($COUNT_TOTAL > 0)
				$result = true;
		}
		return $result;
	}
	public function exam_pending($stud_id, $stud_course_id)
	{
		$result = false;
		$sql = "SELECT COUNT(*) AS COUNT_TOTAL FROM student_course_details WHERE STUDENT_ID=$stud_id AND STUD_COURSE_DETAIL_ID = $stud_course_id AND ACTIVE=1 AND DELETE_FLAG=0 AND EXAM_STATUS=3";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$COUNT_TOTAL = $data['COUNT_TOTAL'];
			if ($COUNT_TOTAL > 0)
				$result = true;
		}
		return $result;
	}
	public function delete_certificate_request($req_id)
	{
		$sql = "UPDATE certificate_requests SET DELETE_FLAG=1 WHERE CERTIFICATE_REQUEST_ID='$req_id'";
		$res = $this->execQuery($sql);
		if ($res)
			return true;
		else return false;
	}
	public function delete_certificate_request_all($req_id)
	{
		$sql = "UPDATE certificate_requests_master SET DELETE_FLAG=1 WHERE CERTIFICATE_REQUEST_MASTER_ID='$req_id'";
		$sql2 = "UPDATE certificate_requests SET DELETE_FLAG=1 WHERE CERTIFICATE_REQUEST_MASTER_ID='$req_id'";
		$res = $this->execQuery($sql);
		$res2 = $this->execQuery($sql2);
		if ($res && $res2)
			return true;
		else return false;
	}
	// order certificate
	public function delete_order_certificate_request_all($req_id)
	{
		$sql = "UPDATE certificate_order_requests_master SET DELETE_FLAG=1 WHERE CERTIFICATE_REQUEST_MASTER_ID='$req_id'";
		$sql2 = "UPDATE certificate_order_requests SET DELETE_FLAG=1 WHERE CERTIFICATE_REQUEST_MASTER_ID='$req_id'";
		$res = $this->execQuery($sql);
		$res2 = $this->execQuery($sql2);
		if ($res && $res2)
			return true;
		else return false;
	}
	public function delete_order_certificate_request($req_id)
	{
		$sql = "UPDATE certificate_order_requests SET DELETE_FLAG=1 WHERE CERTIFICATE_REQUEST_ID='$req_id'";
		$res = $this->execQuery($sql);
		if ($res)
			return true;
		else return false;
	}
	/*------------------------*/
	public function get_user_mobile($user_id, $user_role)
	{
		$mobile = '';
		switch ($user_role) {
			case (2):
				$sql = "SELECT MOBILE FROM institute_details WHERE INSTITUTE_ID='$user_id'";
				break;
			case (3):
				$sql = "SELECT MOBILE FROM employer_details WHERE EMPLOYER_ID='$user_id'";
				break;
			case (4):
				$sql = "SELECT STUDENT_MOBILE AS MOBILE FROM student_details WHERE STUDENT_ID='$user_id'";
				break;
			case (5):
				$sql = "SELECT STAFF_MOBILE AS MOBILE FROM institute_staff_details WHERE STAFF_ID='$user_id'";
				break;
			case (6):
				$sql = "SELECT STAFF_MOBILE AS MOBILE FROM admin_staff_details WHERE STAFF_ID='$user_id'";
				break;
			case (7):
				$sql = "SELECT MOBILE AS MOBILE FROM typing_institute_details WHERE INSTITUTE_ID='$user_id'";
				break;
			case (8):
				$sql = "SELECT MOBILE AS MOBILE FROM amc_details WHERE AMC_ID='$user_id'";
				break;
		}

		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$mobile = $data['MOBILE'];
		}
		return $mobile;
	}
	public function get_user_login_info($user_id, $user_role)
	{
		$info = array();
		$sql = "SELECT USER_LOGIN_ID,USER_ID,USER_ROLE,USER_NAME FROM user_login_master WHERE USER_ID='$user_id' AND USER_ROLE=$user_role";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$info['USER_NAME'] = $data['USER_NAME'];
		}
		return $info;
	}
	public function get_owner_fullname($user_id, $user_role)
	{
		$name = '';
		switch ($user_role) {
			case (2):
				$sql = "SELECT INSTITUTE_OWNER_NAME AS NAME FROM institute_details WHERE INSTITUTE_ID='$user_id'";
				break;
			case (3):
				$sql = "SELECT EMPLOYER_NAME AS NAME FROM employer_details WHERE EMPLOYER_ID='$user_id'";
				break;
			case (4):
				$sql = "SELECT CONCAT(STUDENT_FNAME,'',STUDENT_LNAME) AS NAME FROM student_details WHERE STUDENT_ID='$user_id'";
				break;
			case (5):
				$sql = "SELECT STAFF_FULLNAME AS NAME FROM institute_staff_details WHERE STAFF_ID='$user_id'";
				break;
			case (6):
				$sql = "SELECT STAFF_FULLNAME AS NAME FROM admin_staff_details WHERE STAFF_ID='$user_id'";
				break;
		}
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$name = $data['NAME'];
		}
		return $name;
	}

	public function add_dynamic_page()
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		$field_name		 	= isset($_POST['field_name']) ? $_POST['field_name'] : '';
		$field_value 		= isset($_POST['field_value']) ? $_POST['field_value'] : '';
		$page_id 	= $this->test(isset($_POST['page_id']) ? $_POST['page_id'] : '');
		$blockno 	= $this->test(isset($_POST['blockno']) ? $_POST['blockno'] : '');

		$status 		= $this->test(isset($_POST['status']) ? $_POST['status'] : '');
		$created_by 	= $_SESSION['user_name'];
		$created_by_ip 	= $_SESSION['ip_address'];
		/* ---------------------file uploads------------------------ */
		$field_image		= isset($_FILES["field_image"]["name"]) ? $_FILES["field_image"]["name"] : '';

		/* validations */
		/*  if ($field_name=='')
			$errors['field_name'] = 'Field name is required.';
		 	  
		 if ($field_value=='')
			$errors['field_value'] = 'Field value is required.';
		 	  
		  */
		/* ---------------file uploads----------------------------- */
		if ($field_image != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($field_image, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['field_image'] = 'Invalid file format! Please select valid file.';
			}
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			$post = array();
			$img = array();
			$img['index'] = 0;
			$img['field_name'] = "photo";
			$img['field_value'] = $field_image;
			array_push($post, $img);
			for ($i = 0; $i < count($field_name); $i++) {
				$block = array();
				$block['index'] = $i + 1;
				$block['field_name'] = $this->test(!empty($field_name[$i]) ? $field_name[$i] : '');
				$block['field_value'] = strtoupper($this->test(!empty($field_value[$i]) ? $field_value[$i] : ''));
				array_push($post, $block);
			}
			//$post['block'] = $block;

			$tableName  	= 'pages_dynamic';
			$tabFields  	= '(DYNAMIC_PAGE_ID,PAGE_ID,BLOCK_NO,FIELD_NAME,FIELD_VALUE,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)';
			foreach ($post as $key => $value) {

				$field_name = $value['field_name'];
				$field_value = $value['field_value'];

				$insertValues 	.= "(NULL,'$page_id','$blockno','$field_name','$field_value','$status','$created_by',NOW(),'$created_by_ip'),";
			}
			$insertValues = rtrim($insertValues, ",");
			$insert 		= $this->insertData($tableName, $tabFields, $insertValues);
			$res = $this->execQuery($insert);

			//upload photo 
			if ($field_image != '') {
				$courseImgPathDir 		= 	'../uploads/pages/';
				/* upload files */
				if ($field_image != '') {
					$ext 			= pathinfo($_FILES["field_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $_FILES["field_image"]["name"];


					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@unlink($courseImgPathFile);
					@mkdir($courseImgPathDir, 0777, true);
					//@mkdir($courseImgThumbPathDir,0777,true);
					$access = new access();

					$access->create_thumb_img($_FILES["field_image"]["tmp_name"], $courseImgPathFile,  $ext, 300, 280);
					//$access->create_thumb_img($_FILES["field_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);								
				}
			}
			$data['success'] = true;
			$data['message'] = 'Success! New data has been added successfully!';
		}
		return json_encode($data);
	}
	public function update_dynamic_page()
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		$field_name		 	= isset($_POST['field_name']) ? $_POST['field_name'] : '';
		$field_value 		= isset($_POST['field_value']) ? $_POST['field_value'] : '';
		$page_id 	= $this->test(isset($_POST['page_id']) ? $_POST['page_id'] : '');
		$blockno 	= $this->test(isset($_POST['blockno']) ? $_POST['blockno'] : '');
		$field_image_name 	= $this->test(isset($_POST['field_image_name']) ? $_POST['field_image_name'] : '');

		$status 		= $this->test(isset($_POST['status']) ? $_POST['status'] : '');
		$created_by 	= $_SESSION['user_name'];
		$created_by_ip 	= $_SESSION['ip_address'];
		/* ---------------------file uploads------------------------ */
		$field_image		= isset($_FILES["field_image"]["name"]) ? $_FILES["field_image"]["name"] : '';

		/* validations */
		/*  if ($field_name=='')
			$errors['field_name'] = 'Field name is required.';
		 	  
		 if ($field_value=='')
			$errors['field_value'] = 'Field value is required.';
		 	  
		  */
		/* ---------------file uploads----------------------------- */
		if ($field_image != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($field_image, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['field_image'] = 'Invalid file format! Please select valid file.';
			}
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			echo $sqlDel = "DELETE FROM pages_dynamic WHERE BLOCK_NO='$blockno' AND PAGE_ID='$page_id'";
			$resDel = $this->execQuery($sqlDel);
			$post = array();
			$img = array();
			$img['index'] = "0";
			$img['field_name'] = "photo";
			$img['field_value'] = ($field_image != '') ? $field_image : $field_image_name;
			array_push($post, $img);
			for ($i = 0; $i < count($field_name); $i++) {
				$block = array();
				$block['index'] = $i + 1;
				$block['field_name'] = $this->test(!empty($field_name[$i]) ? $field_name[$i] : '');
				$block['field_value'] = $this->test(!empty($field_value[$i]) ? $field_value[$i] : '');
				array_push($post, $block);
			}
			//$post['block'] = $block;


			foreach ($post as $key => $value) {

				$field_name = $value['field_name'];
				$field_value = $value['field_value'];
				$tableName  	= 'pages_dynamic';
				$tabFields  	= '(DYNAMIC_PAGE_ID,PAGE_ID,BLOCK_NO,FIELD_NAME,FIELD_VALUE,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)';
				$insertValues 	= "(NULL,'$page_id','$blockno','$field_name',UPPER('$field_value'),'$status','$created_by',NOW(),'$created_by_ip')";
				$insert 		= $this->insertData($tableName, $tabFields, $insertValues);
				$this->execQuery($insert);
			}

			//upload photo 
			if ($field_image != '') {
				$courseImgPathDir 		= 	'../uploads/pages/' . $blockno . '/';
				/* upload files */
				if ($field_image != '') {
					$ext 			= pathinfo($_FILES["field_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $_FILES["field_image"]["name"];


					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@unlink($courseImgPathFile);
					//	@mkdir($courseImgPathDir,0777,true);
					//	@mkdir($courseImgThumbPathDir,0777,true);
					$access = new access();

					$access->create_thumb_img($_FILES["field_image"]["tmp_name"], $courseImgPathFile,  $ext, 300, 280);
					//$access->create_thumb_img($_FILES["field_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);								
				}
			}
			$data['success'] = true;
			$data['message'] = 'Success! New data has been added successfully!';
		}
		return json_encode($data);
	}
	public function list_dynamic_pages($cond)
	{
		$result 	= '';
		$tableName 	= 'pages_dynamic';
		$selVals 	= '*';
		$whereClause = $cond;
		$select 	= $this->selectData($selVals, $tableName, $whereClause);
		$exec       = $this->execQuery($select);
		if ($exec && $exec->num_rows > 0) {
			$result = $exec;
		}
		return $result;
	}
	public function add_dynamic_page_temp()
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		$field_image		= isset($_FILES["field_image"]["name"]) ? $_FILES["field_image"]["name"] : '';
		$field_name0		 = "photo";
		$field_value0		= $field_image;

		$field_name1		 	= isset($_POST['field_name1']) ? $_POST['field_name1'] : '';
		$field_value1		= isset($_POST['field_value1']) ? $_POST['field_value1'] : '';
		$field_name2		 	= isset($_POST['field_name2']) ? $_POST['field_name2'] : '';
		$field_value2		= isset($_POST['field_value2']) ? $_POST['field_value2'] : '';
		$field_name3		 	= isset($_POST['field_name3']) ? $_POST['field_name3'] : '';
		$field_value3		= isset($_POST['field_value3']) ? $_POST['field_value3'] : '';
		$field_name4		 	= isset($_POST['field_name4']) ? $_POST['field_name4'] : '';
		$field_value4		= isset($_POST['field_value4']) ? $_POST['field_value4'] : '';
		$page_id 	= $this->test(isset($_POST['page_id']) ? $_POST['page_id'] : '');
		$blockno 	= $this->test(isset($_POST['blockno']) ? $_POST['blockno'] : '');

		$status 		= $this->test(isset($_POST['status']) ? $_POST['status'] : '');
		$created_by 	= $_SESSION['user_name'];
		$created_by_ip 	= $_SESSION['ip_address'];
		/* ---------------------file uploads------------------------ */


		/* validations */
		/*  if ($field_name=='')
			$errors['field_name'] = 'Field name is required.';
		 	  
		 if ($field_value=='')
			$errors['field_value'] = 'Field value is required.';
		 	  
		  */
		/* ---------------file uploads----------------------------- */
		if ($field_image != '') {
			$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
			$extension = pathinfo($field_image, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['field_image'] = 'Invalid file format! Please select valid file.';
			}
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			$tableName  	= 'pages_dynamic';
			$tabFields  	= '(DYNAMIC_PAGE_ID,PAGE_ID,BLOCK_NO,FIELD_NAME,FIELD_VALUE,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)';

			$insertValues = "(NULL,'$page_id','$blockno','$field_name0','$field_value0','$status','$created_by',NOW(),'$created_by_ip'),";
			$insertValues .= "(NULL,'$page_id','$blockno','$field_name1','$field_value1','$status','$created_by',NOW(),'$created_by_ip'),";
			$insertValues .= "(NULL,'$page_id','$blockno','$field_name2','$field_value2','$status','$created_by',NOW(),'$created_by_ip'),";
			$insertValues .= "(NULL,'$page_id','$blockno','$field_name3','$field_value3','$status','$created_by',NOW(),'$created_by_ip'),";
			$insertValues .= "(NULL,'$page_id','$blockno','$field_name4','$field_value4','$status','$created_by',NOW(),'$created_by_ip')";

			echo	$insert 		= $this->insertData($tableName, $tabFields, $insertValues);
			$this->execQuery($insert);
			/*			
			$post = array();
			$img=array();
			$img['index'] = "0";
			$img['field_name'] = "photo";
			$img['field_value'] = $field_image;
			array_push($post,$img);
			for($i=0; $i<count($field_name);$i++)
			{
				$block = array();
				$block['index'] = $i+1;
				$block['field_name'] = $this->test(!empty($field_name[$i])?$field_name[$i]:'');
				$block['field_value'] = $this->test(!empty($field_value[$i])?$field_value[$i]:'');
				array_push($post,$block);
			}
			//$post['block'] = $block;
			ksort($post);
			print_r($post);
			
			foreach($post as $key=>$value)
			{
				
					$field_name = $value['field_name'];
					$field_value = $value['field_value'];
					$tableName  	= 'pages_dynamic';
					$tabFields  	= '(DYNAMIC_PAGE_ID,PAGE_ID,BLOCK_NO,FIELD_NAME,FIELD_VALUE,ACTIVE,CREATED_BY,CREATED_ON,CREATED_ON_IP)';
					$insertValues 	= "(NULL,'$page_id','$blockno','$field_name',UPPER('$field_value'),'$status','$created_by',NOW(),'$created_by_ip')";
					$insert 		= $this-> insertData($tableName,$tabFields,$insertValues);
					$this->execQuery($insert);
			}
			*/
			//upload photo 
			if ($field_image != '') {
				$courseImgPathDir 		= 	'../uploads/pages/' . $blockno . '/';
				/* upload files */
				if ($field_image != '') {
					$ext 			= pathinfo($_FILES["field_image"]["name"], PATHINFO_EXTENSION);
					$file_name 		= $_FILES["field_image"]["name"];


					$courseImgPathFile 		= 	$courseImgPathDir . '' . $file_name;
					$courseImgThumbPathDir 	= 	$courseImgPathDir . '/thumb/';
					$courseImgThumbPathFile = 	$courseImgThumbPathDir . '' . $file_name;
					@mkdir($courseImgPathDir, 0777, true);
					@mkdir($courseImgThumbPathDir, 0777, true);
					$access = new access();

					$access->create_thumb_img($_FILES["field_image"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750);
					$access->create_thumb_img($_FILES["field_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);
				}
			}
			$data['success'] = true;
			$data['message'] = 'Success! New data has been added successfully!';
		}
		return json_encode($data);
	}
	//Typing Software Code
	public function get_typing_key($user_id)
	{
		$key = '';
		$sql = "SELECT ACTIVATION_KEY FROM  typing_institute_details WHERE INSTITUTE_ID='$user_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['ACTIVATION_KEY'];
		}
		return $key;
	}

	public function get_typing_inst_code($user_id)
	{
		$key = '';
		$sql = "SELECT INSTITUTE_CODE FROM  typing_institute_details WHERE INSTITUTE_ID='$user_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['INSTITUTE_CODE'];
		}
		return $key;
	}

	/*------------------------ Help Support --------------------------------*/
	public function get_support_type_name($supporttype_id)
	{
		$key = '';
		$sql = "SELECT SUPPORT_NAME FROM  help_support_type WHERE SUPPORT_TYPE_ID='$supporttype_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['SUPPORT_NAME'];
		}
		return $key;
	}

	public function get_support_cat_name($supportcat_id)
	{
		$key = '';
		$sql = "SELECT CATEGORY_NAME FROM  help_support_category WHERE SUPPORT_CAT_ID='$supportcat_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['CATEGORY_NAME'];
		}
		return $key;
	}
	//get course plan name
	public function get_course_planname($plan_id)
	{
		$key = '';
		$sql = "SELECT PLAN_NAME FROM  institute_plans WHERE PLAN_ID='$plan_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['PLAN_NAME'];
		}
		return $key;
	}
	public function get_institute_state($inst_id)
	{
		$state_id = '';
		$sql = "SELECT STATE FROM institute_details WHERE INSTITUTE_ID='$inst_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$state_id = $data['STATE'];
		}
		return $state_id;
	}
	public function get_institute_state_name($state_id)
	{
		$state_name = '';
		$sql = "SELECT STATE_NAME FROM states_master WHERE STATE_ID='$state_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$state_name = $data['STATE_NAME'];
		}
		return $state_name;
	}

	public function get_state_code($state_id)
	{
		$state_code = '';
		$sql = "SELECT STATE_CODE FROM states_master WHERE STATE_ID='$state_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$state_code = $data['STATE_CODE'];
		}
		return $state_code;
	}

	public function get_state_name($state_id)
	{
		$state_code = '';
		$sql = "SELECT STATE_NAME FROM states_master WHERE STATE_ID='$state_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$state_code = $data['STATE_NAME'];
		}
		return $state_code;
	}

	///get course id by course code
	public function get_course_id_code($course_code)
	{
		$key = '';
		$sql = "SELECT COURSE_ID FROM  courses WHERE COURSE_CODE='$course_code'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['COURSE_ID'];
		}
		return $key;
	}

	public function get_inst_course_id($course_id, $inst_id)
	{
		$key = '';
		$sql = "SELECT INSTITUTE_COURSE_ID FROM  institute_courses WHERE COURSE_ID='$course_id' AND INSTITUTE_ID='$inst_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['INSTITUTE_COURSE_ID'];
		}
		return $key;
	}
	//multi sub excel
	public function get_multi_course_id_code($course_code)
	{
		$key = '';
		$sql = "SELECT MULTI_SUB_COURSE_ID FROM  multi_sub_courses WHERE MULTI_SUB_COURSE_CODE='$course_code'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['MULTI_SUB_COURSE_ID'];
		}
		return $key;
	}

	public function get_inst_multi_course_id($course_id, $inst_id)
	{
		$key = '';
		$sql = "SELECT INSTITUTE_COURSE_ID FROM  institute_courses WHERE MULTI_SUB_COURSE_ID='$course_id' AND INSTITUTE_ID='$inst_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['INSTITUTE_COURSE_ID'];
		}
		return $key;
	}

	public function get_course_id($inst_course_id)
	{
		$key = '';
		$sql = "SELECT COURSE_ID FROM  institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id' AND DELETE_FLAG='0'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['COURSE_ID'];
		}
		return $key;
	}
	public function get_institute_gstnumber($user_id)
	{
		$key = '';
		$sql = "SELECT GSTNO FROM  institute_details WHERE INSTITUTE_ID='$user_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['GSTNO'];
		}
		return $key;
	}

	public function get_institute_city($inst_id)
	{
		$key = '';
		$sql = "SELECT CITY FROM institute_details G WHERE G.INSTITUTE_ID= $inst_id";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['CITY'];
		}
		return $key;
	}

	//referar details
	public function get_refferar_details($code, $institute_id)
	{
		$stud_id = '';
		$sql = "SELECT STUDENT_ID FROM student_details WHERE STUDENT_CODE='$code' AND INSTITUTE_ID = '$institute_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_id = $data['STUDENT_ID'];
		}
		return $stud_id;
	}
	public function get_refferal_amount($institute_id)
	{
		$amount = '';
		$sql = "SELECT amount FROM referral_amount WHERE inst_id = '$institute_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$amount = $data['amount'];
		}
		return $amount;
	}
	public function get_batchname($id)
	{
		$batch_name = '';
		$sql = "SELECT batch_name FROM course_batches WHERE id = '$id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$batch_name = $data['batch_name'];
		}
		return $batch_name;
	}

	public function get_student_batchid($id)
	{
		$batch_id = '';
		$sql = "SELECT BATCH_ID FROM student_details WHERE STUDENT_ID = '$id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$batch_id = $data['BATCH_ID'];
		}
		return $batch_id;
	}

	public function get_batchid_fees($stud_id = '', $inst_course_id = '')
	{
		$batch_id = '';
		$sql = "SELECT BATCH_ID FROM student_course_details WHERE STUDENT_ID  = '$stud_id' AND INSTITUTE_COURSE_ID   = '$inst_course_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$batch_id = $data['BATCH_ID'];
		}
		return $batch_id;
	}

	//get refferal name 
	public function get_refferar_name($code)
	{
		$stud_details = array();
		$sql = "SELECT STUDENT_FNAME,STUDENT_MNAME,STUDENT_LNAME FROM student_details WHERE STUDENT_CODE='$code'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_details['STUDENT_FNAME'] = $data['STUDENT_FNAME'];
			$stud_details['STUDENT_MNAME'] = $data['STUDENT_MNAME'];
			$stud_details['STUDENT_LNAME'] = $data['STUDENT_LNAME'];
		}
		return $stud_details;
	}

	//single subject minimum fee
	public function get_courseMinFeesSingle($course_id)
	{
		$key = '';
		$sql = "SELECT MINIMUM_AMOUNT FROM  courses WHERE COURSE_ID='$course_id' AND DELETE_FLAG='0'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['MINIMUM_AMOUNT'];
		}
		return $key;
	}

	//multiple subject minimum fee
	public function get_courseMinFeesMultiple($course_id)
	{
		$key = '';
		$sql = "SELECT MULTI_SUB_MINIMUM_AMOUNT FROM  multi_sub_courses WHERE MULTI_SUB_COURSE_ID='$course_id' AND DELETE_FLAG='0'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$key = $data['MULTI_SUB_MINIMUM_AMOUNT'];
		}
		return $key;
	}

	public function get_instituteMinFees($id)
	{
		$amount = '';
		$COURSE_ID = '';
		$MULTI_SUB_COURSE_ID = '';
		$sql = "SELECT COURSE_ID,MULTI_SUB_COURSE_ID FROM institute_courses WHERE INSTITUTE_COURSE_ID = '$id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$COURSE_ID = $data['COURSE_ID'];
			$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];

			if (!empty($COURSE_ID) && $COURSE_ID !== '' && $COURSE_ID !== 0) {
				$amount = $this->get_courseMinFeesSingle($COURSE_ID);
			}
			if (!empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID !== '' && $MULTI_SUB_COURSE_ID !== 0) {
				$amount = $this->get_courseMinFeesMultiple($MULTI_SUB_COURSE_ID);
			}
		}
		return $amount;
	}

	public function checkIsCoursePurchase($student_id, $instCourse_id)
	{
		$stud_courseId = '';
		$sql = "SELECT STUD_COURSE_DETAIL_ID FROM student_course_details WHERE STUDENT_ID = '$student_id' AND INSTITUTE_COURSE_ID = '$instCourse_id' ";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$stud_courseId = $data['STUD_COURSE_DETAIL_ID'];
		}
		return $stud_courseId;
	}

	//check Attendance Status
	public function get_attendancedateStatus($batch_id, $student_id, $course_id, $date)
	{
		$status = '';
		$sql = "SELECT is_present FROM attendance WHERE batch_id = '$batch_id' AND student_id = '$student_id' AND course_id = '$course_id' AND date = '$date' ";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$status = $data['is_present'];
		}
		return $status;
	}
	//wallet amount
	public function get_institute_walletamount($user_id = '', $role_id = '')
	{
		$amount = '';
		$sql = "SELECT TOTAL_BALANCE FROM wallet WHERE USER_ID = '$user_id' AND USER_ROLE = '$role_id' ";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$amount = $data['TOTAL_BALANCE'];
		}
		return $amount;
	}

	public function get_totalexpenses($user_id)
	{
		$amount = 0;
		$sql = "SELECT SUM(AMOUNT) as AMOUNT FROM expenses WHERE INSTITUTE_ID = '$user_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$amount = $data['AMOUNT'];
		}
		return $amount;
	}

	public function get_singlecourse_count()
	{
		$result = 0;
		$sql = "SELECT COUNT(*) AS COURSE_COUNT FROM courses WHERE DELETE_FLAG=0 AND ACTIVE = 1";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['COURSE_COUNT'];
		}
		return $result;
	}

	public function get_multicourse_count()
	{
		$result = 0;
		$sql = "SELECT COUNT(*) AS MULTICOURSE_COUNT FROM multi_sub_courses WHERE DELETE_FLAG=0 AND ACTIVE = 1";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['MULTICOURSE_COUNT'];
		}
		return $result;
	}

	public function get_typingourse_count()
	{
		$result = 0;
		$sql = "SELECT COUNT(*) AS TYPING_COUNT FROM courses_typing WHERE DELETE_FLAG=0 AND ACTIVE = 1";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['TYPING_COUNT'];
		}
		return $result;
	}

	public function get_franchise_enquiry_count()
	{
		$result = 0;
		$sql = "SELECT COUNT(*) AS ENQUIRY_COUNT FROM franchise_enquiry WHERE delete_flag=0 AND active = 1";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['ENQUIRY_COUNT'];
		}
		return $result;
	}

	//minimum amount from inst_id
	public function get_course_minimum_amount($inst_course_id)
	{
		$sql = "SELECT COURSE_ID,MULTI_SUB_COURSE_ID,COURSE_FEES,EXAM_FEES FROM institute_courses WHERE INSTITUTE_COURSE_ID= $inst_course_id";
		$res = $this->execQuery($sql);

		$output = '';

		if ($res != '') {
			while ($data = $res->fetch_assoc()) {
				$COURSE_ID 	= $data['COURSE_ID'];
				$MULTI_SUB_COURSE_ID 	= $data['MULTI_SUB_COURSE_ID'];
				$minamount = '';

				if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != NULL && $COURSE_ID != '0') {
					$course 		 = $this->get_course_detail($COURSE_ID);
					$minamount 		 = $course['MINIMUM_AMOUNT'];
				}

				if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != NULL && $MULTI_SUB_COURSE_ID != '0') {
					$course 			 = $this->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
					$minamount 		 = $course['MULTI_SUB_MINIMUM_AMOUNT'];
				}
				$output 		= $minamount;
			}
		}
		return $output;
	}

	public function get_student_displayform_status($student_id = '', $cond = '')
	{
		$res = array();;
		$sql = "SELECT DISPLAY_FORM_STATUS FROM student_details WHERE 1";
		if ($student_id != '') {
			$sql .= " AND STUDENT_ID='$student_id'";
		}
		if ($cond != '') {
			$sql .= $cond;
		}
		//echo $sql;
		$exc = $this->execQuery($sql);
		if ($exc && $exc->num_rows > 0) {
			while ($data = $exc->fetch_assoc()) {
				$res['DISPLAY_FORM_STATUS'] = $data['DISPLAY_FORM_STATUS'];
			}
		}
		return $res;
	}

	//exam duration
	public function get_inst_exam_duration($inst_course_id)
	{
		$res = "";
		$course = "";
		$sql = "SELECT * FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
		$ex = $this->execQuery($sql);
		if ($ex && $ex->num_rows > 0) {
			$data = $ex->fetch_assoc();
			$COURSE_ID 			 = $data['COURSE_ID'];
			$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];

			if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
				$course = $this->get_exam_structure($COURSE_ID);
				if ($course && $course->num_rows > 0) {
					while ($data = $course->fetch_assoc()) {
						$res = $data['EXAM_TIME'];
					}
				}
			}
			if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
				$course = $this->get_exam_structure_multi_sub($MULTI_SUB_COURSE_ID);
				if ($course && $course->num_rows > 0) {
					while ($data = $course->fetch_assoc()) {
						$res = $data['EXAM_TIME'];
					}
				}
			}
		}
		return $res;
	}

	public function check_certificate_applystatus($student_id, $course_id, $course_id_multi)
	{
		$res = array();;
		$sql = "SELECT CERTIFICATE_REQUEST_ID FROM certificate_requests WHERE 1";
		if ($student_id != '') {
			$sql .= " AND STUDENT_ID='$student_id'";
		}
		if ($course_id != '') {
			$sql .= " AND COURSE_ID ='$course_id'";
		}
		if ($course_id_multi != '') {
			$sql .= " AND MULTI_SUB_COURSE_ID ='$course_id_multi'";
		}
		//echo $sql;
		$exc = $this->execQuery($sql);
		if ($exc && $exc->num_rows > 0) {
			while ($data = $exc->fetch_assoc()) {
				$res['CERTIFICATE_REQUEST_ID'] = $data['CERTIFICATE_REQUEST_ID'];
			}
		}
		return $res;
	}

	/* generate student receipt number */
	public function generate_student_receipt_no()
	{
		$code = '';
		$code = $this->getRandomCode3();
		$sql = "SELECT RECIEPT_NO FROM student_payments WHERE RECIEPT_NO ='$code'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$this->generate_student_receipt_no();
		}
		return $code;
	}

	public function get_stud_exam_status($stud_id = '', $inst_course_id = '')
	{
		$exam_status = '';
		$sql = "SELECT EXAM_STATUS  FROM student_course_details WHERE STUDENT_ID  = '$stud_id' AND INSTITUTE_COURSE_ID  = '$inst_course_id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$exam_status = $data['EXAM_STATUS'];
		}
		return $exam_status;
	}

	public function get_wallet_password()
	{
		$wallet_password = '';
		$sql = "SELECT wallet_password  FROM master_password WHERE id  = '1' ";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$wallet_password = $data['wallet_password'];
		}
		return $wallet_password;
	}

	public function get_courier_wallet_password()
	{
		$courier_password = '';
		$sql = "SELECT courier_password  FROM master_password WHERE id  = '1' ";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$courier_password = $data['courier_password'];
		}
		return $courier_password;
	}

	public function get_certificate_request_id($cond = '')
	{
		$courier_password = '';
		$sql = "SELECT CERTIFICATE_REQUEST_ID  FROM certificates_details WHERE $cond ";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$courier_password = $data['CERTIFICATE_REQUEST_ID'];
		}
		return $courier_password;
	}

	public function check_prime_membership($inst_id = '')
	{
		$status = 0;
		$sql = "SELECT PRIMEMEMBER,PRIMEMEMBER_DATE,NUMBER_OF_ADMISSION  FROM institute_details WHERE INSTITUTE_ID ='$inst_id' ";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$PRIMEMEMBER = $data['PRIMEMEMBER'];
			$PRIMEMEMBER_DATE = $data['PRIMEMEMBER_DATE'];
			$NUMBER_OF_ADMISSION = $data['NUMBER_OF_ADMISSION'];
			$dateNow = date("Y-m-d");

			if ($PRIMEMEMBER = 1 && $PRIMEMEMBER != NULL && $PRIMEMEMBER != 0 && ($PRIMEMEMBER_DATE >= $dateNow)) {

				include_once('student.class.php');
				$student = new student();
				$count = '';
				$cond21  = " AND INSTITUTE_ID = $inst_id";
				$count = $student->get_admission_count($cond21);
				if ($NUMBER_OF_ADMISSION >= $count) {
					$status = 1;
				}
			}
		}
		return $status;
	}

	public function get_prime_member($inst_id = '')
	{
		$status = '';
		$sql = "SELECT PRIMEMEMBER  FROM institute_details WHERE INSTITUTE_ID ='$inst_id' ";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$status = $data['PRIMEMEMBER'];
		}
		return $status;
	}
}
