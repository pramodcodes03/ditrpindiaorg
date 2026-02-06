<?php
include('connection.class.php');
class database_results
{

	function __construct()
	{
		$db = new connection();
		$this->mysqli = $db->getDbConnection();
		if ($this->mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " .
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
	public function MenuItemsDropdown($tableName, $value, $option, $selVals, $selected, $whereClause) // to list all existing 
	{
		$selVals 		= "$selVals";
		$whereClause	= "$whereClause";
		$selectAM 		= $this->selectData($selVals, $tableName, $whereClause);
		$execAM 		= $this->execQuery($selectAM);

		$dropdown = '';
		$dropdown = '';
		if ($execAM->num_rows > 0) {
			while ($Row = $execAM->fetch_assoc()) {
				$id 	 	= $Row['' . $value . ''];
				$name 		= $Row['' . $option . ''];
				if ($id == $selected) {
					$dropdown .= '<option value="' . $Row['' . $value . ''] . '" selected>' . ucwords(strtoupper($Row['' . $option . ''])) . '</option>';
				} else {
					$dropdown .= '<option value="' . $Row['' . $value . ''] . '" >' . ucwords(strtoupper($Row['' . $option . ''])) . '</option>';
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
		$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

		// if (!preg_match('/^[a-zA-Z0-9\s]+$/', $data)) {
		// 	return "Invalid data";
		// 	die();
		// }

		$data = $this->mysqli->real_escape_string($data);
		return $data;
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
			$tabFields  	= '(GALLERY_ID,GALLERY_TITLE, GALLERY_DESC,ACTIVE, CREATED_BY, CREATED_ON)';
			$insertValues 	= "(NULL, '$title','$description','$status','$created_by',NOW())";
			$insert 		= $this->insertData($tableName, $tabFields, $insertValues);
			$exec   		= $this->execQuery($insert);
			$current_id 	= $this->last_id();
			if ($exec) {
				while (list($key, $value) = each($_FILES["event_imgs"]["name"])) {
					//rename the file
					$ext 			= pathinfo($_FILES["event_imgs"]["name"][$key], PATHINFO_EXTENSION);
					$allowed_ext 	= array('jpg', 'jpeg', 'png', 'gif');
					if (in_array($ext, $allowed_ext)) {
						$file_name 		= time() . mt_rand(0, 123456789) . '.' . $ext;
						$tableName  	= 'gallery_files';
						$tabFields  	= '(GALLERY_FILE_ID,GALLERY_ID, FILE_NAME,FILE_MIME,ACTIVE, CREATED_BY, CREATED_ON)';
						$insertValues 	= "(NULL, '$current_id','$file_name','$ext','$status','$created_by',NOW())";
						$insert 		= $this->insertData($tableName, $tabFields, $insertValues);
						$exec   		= $this->execQuery($insert);
						$courseImgPathDir 		= GALLERY . '/' . $current_id . '/';
						$courseImgPathFile 		= $courseImgPathDir . '' . $file_name;
						$courseImgThumbPathDir 	= $courseImgPathDir . 'thumb/';
						$courseImgThumbPathFile = $courseImgThumbPathDir . '' . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						@mkdir($courseImgThumbPathDir, 0777, true);
						include_once('access.class.php');
						$access = new access();
						$access->create_thumb_img($_FILES["event_imgs"]["tmp_name"][$key], $courseImgPathFile,  $ext, 800, 750);
						$access->create_thumb_img($_FILES["event_imgs"]["tmp_name"][$key], $courseImgThumbPathFile,  $ext, 300, 280);
					}
				}
				$data['success'] = true;
				$data['message'] = 'Success! New gallery has been added successfully!';
			}
		}
		return json_encode($data);
	}
	public function update_gallery()
	{
		$errors = array();  // array to hold validation errors
		$data = array();
		$gallery_id		 	= $this->test(isset($_POST['gallery_id']) ? $_POST['gallery_id'] : '');
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
				while (list($key, $value) = each($_FILES["event_imgs"]["name"])) {
					//rename the file
					$ext 			= pathinfo($_FILES["event_imgs"]["name"][$key], PATHINFO_EXTENSION);
					$allowed_ext 	= array('jpg', 'jpeg', 'png', 'gif');
					$file_name 		= time() . mt_rand(0, 123456789) . '.' . $ext;
					if (in_array($ext, $allowed_ext)) {
						$tableName  	= 'gallery_files';
						$tabFields  	= '(GALLERY_FILE_ID,GALLERY_ID, FILE_NAME,FILE_MIME,ACTIVE, CREATED_BY, CREATED_ON)';
						$insertValues 	= "(NULL, '$gallery_id','$file_name','$ext','$status','$created_by',NOW())";
						$insert 		= $this->insertData($tableName, $tabFields, $insertValues);
						$exec   		= $this->execQuery($insert);
						$courseImgPathDir 		= GALLERY . '/' . $gallery_id . '/';
						$courseImgPathFile 		= $courseImgPathDir . '' . $file_name;
						$courseImgThumbPathDir 	= $courseImgPathDir . 'thumb/';
						$courseImgThumbPathFile = $courseImgThumbPathDir . '' . $file_name;
						@mkdir($courseImgPathDir, 0777, true);
						@mkdir($courseImgThumbPathDir, 0777, true);
						include_once('access.class.php');
						$access = new access();
						$access->create_thumb_img($_FILES["event_imgs"]["tmp_name"][$key], $courseImgPathFile,  $ext, 800, 750);
						$access->create_thumb_img($_FILES["event_imgs"]["tmp_name"][$key], $courseImgThumbPathFile,  $ext, 300, 280);
					}
				}
				$data['success'] = true;
				$data['message'] = 'Success! Gallery has been updated successfully!';
			}
		}
		return json_encode($data);
	}
	public function list_gallery($id = 0, $type = '')
	{
		$tableName = "gallery";
		$selVals = "*, (SELECT COUNT(*) FROM gallery_files WHERE GALLERY_ID=gallery.GALLERY_ID) AS TOTAL_FILES , DATE_FORMAT(CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE";
		$whereClause = "WHERE DELETE_FLAG=0 ";
		if ($id != 0)
			$whereClause .= " AND GALLERY_ID='$id'";
		if ($type != '')
			$whereClause .= " AND GALLERY_TYPE='$type'";
		$sql = $this->selectData($selVals, $tableName, $whereClause);
		$res = $this->execQuery($sql);
		if (!$res) {
			return false;
		}
		return $res;
	}
	public function list_gallery_files_all($gallery_id = 0, $cond = '')
	{
		$tableName = "gallery_files";
		$selVals = "*, (SELECT GALLERY_TITLE FROM gallery A WHERE A.GALLERY_ID=GALLERY_ID LIMIT 0,1) AS GALLERY_TITLE";
		$whereClause = "WHERE DELETE_FLAG=0 ";
		if ($gallery_id != 0)
			$whereClause .= " AND GALLERY_ID='$gallery_id'";
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
		$selVals = "FILE_NAME";
		$whereClause = " WHERE DELETE_FLAG=0 AND GALLERY_ID='$gallery_id' LIMIT 0,1 ";
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
	// public function get_course_detail($course_id, $course_type)
	// {
	// 	$course_name = array();
	// 	$selVals = '*';
	// 	if($course_type==1)
	// 	{
	// 		$tableName="aicpe_courses";
	// 		$selVals .=" ,get_aicpe_course_award_name (COURSE_AWARD) AS COURSE_AWARD, get_acipe_course_title_modify(COURSE_ID) AS COURSE_NAME_MODIFY";
	// 	}
	// 	if($course_type==2)
	// 		$tableName="non_aicpe_courses";

	// 	$whereClause = " WHERE COURSE_ID='$course_id' AND DELETE_FLAG=0 ";	
	// 	$whereClause .= " LIMIT 0,1";
	// 	 $sql = $this->selectData ($selVals,$tableName,$whereClause);
	// 	$res= $this->execQuery($sql);
	// 	while($data = $res->fetch_assoc())
	// 	{
	// 		$course_name['COURSE_ID'] = $data['COURSE_ID'];
	// 		$course_name['COURSE_NAME'] = $data['COURSE_NAME'];
	// 		$course_name['COURSE_FEES'] = $data['COURSE_FEES'];
	// 		//$course_name['EXAM_FEES'] = $data['EXAM_FEES'];
	// 		$course_name['COURSE_DURATION'] = $data['COURSE_DURATION'];
	// 		$course_name['COURSE_DETAILS'] = $data['COURSE_DETAILS'];
	// 		$course_name['COURSE_ELIGIBILITY'] = $data['COURSE_ELIGIBILITY'];
	// 		$course_name['ACTIVE'] = $data['ACTIVE'];

	// 		$course_name['COURSE_TYPE'] = $course_type;
	// 		if($course_type==1)
	// 		{
	// 			$course_name['COURSE_CODE'] = $data['COURSE_CODE'];
	// 			$course_name['COURSE_AWARD'] = $data['COURSE_AWARD'];
	// 			$course_name['COURSE_NAME_MODIFY'] = $data['COURSE_NAME_MODIFY'];
	// 			$course_name['COURSE_AUTHORITY'] = 'DITRP';
	// 		}
	// 		if($course_type==2)
	// 		{
	// 			$course_name['COURSE_CODE'] = '';
	// 			$course_name['COURSE_AWARD'] = $data['COURSE_AWARD'];
	// 			$course_name['COURSE_AUTHORITY'] = $data['COURSE_AUTHORITY'];
	// 			$course_name['COURSE_NAME_MODIFY'] = $data['COURSE_NAME']." - ".$data['COURSE_AWARD'];
	// 		}
	// 	}
	// 	return $course_name;
	// }

	// public function list_courses($course_id='',$condition='',$limit='')
	// {
	// 	$data = '';
	// 	$sql= "SELECT A.*,B.AWARD AS COURSE_AWARD_NAME, get_course_title_modify(A.COURSE_ID) AS COURSE_NAME_MODIFY FROM  courses A LEFT JOIN course_awards B ON A.COURSE_AWARD=B.AWARD_ID  WHERE A.DELETE_FLAG=0 AND A.ACTIVE=1 ";

	// 	if($course_id!='')
	// 	{
	// 		$sql .= " AND A.COURSE_ID='$course_id' ";
	// 	}
	// 	if($condition!='')
	// 	{
	// 		$sql .= " $condition ";
	// 	}
	// 	//$sql .= 'ORDER BY A.CREATED_ON DESC';
	// 	if($limit!='')
	// 		$sql .= $limit;
	// 	//echo $sql;
	// 	$res = $this->execQuery($sql);
	// 	if($res && $res->num_rows>0)
	// 		$data = $res;
	// 	return $data;
	// }
	public function to_prety_url($str)
	{
		if ($str !== mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
			$str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
		$str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
		$str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
		$str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
		$str = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), '-', $str);
		$str = strtolower(trim($str, '-'));
		return $str;
	}
	public function list_institute($institute_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*,DATE_FORMAT(A.DOB, '%d-%m-%Y') AS DOB_FORMATTED, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%d-%m-%Y ') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%i %p') AS UPDATED_DATE, B.USER_NAME, B.USER_LOGIN_ID , (SELECT CITY_NAME FROM city_master WHERE CITY_ID=A.CITY) AS CITY_NAME FROM institute_details A LEFT JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID AND B.USER_ROLE=2 WHERE A.DELETE_FLAG=0 ";

		if ($institute_id != '') {
			$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	public function get_institute_docs_single($institute_id = '', $file_label = '')
	{
		$filePath = 'uploads/default_user.png';
		$data = array();
		$target = '';

		$sql = "SELECT * FROM institute_files WHERE 1";
		if ($institute_id != '')
			$sql .= " AND INSTITUTE_ID='$institute_id'";
		if ($file_label != '')
			$sql .= " AND FILE_LABEL='$file_label'";
		$sql .= ' ORDER BY FILE_ID ';
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$rec = $res->fetch_assoc();
			$FILE_ID = $rec['FILE_ID'];
			$FILE_NAME = $rec['FILE_NAME'];
			$INSTITUTE_ID = $rec['INSTITUTE_ID'];
			if ($FILE_NAME != '') {
				/*$filePath = INSTITUTE_DOCUMENTS_PATH.'/'.$INSTITUTE_ID.'/thumb/'.$FILE_NAME;
				$fileLink = INSTITUTE_DOCUMENTS_PATH.'/'.$INSTITUTE_ID.'/'.$FILE_NAME;*/

				$filePath = INSTITUTE_DOCUMENTS_PATH . $INSTITUTE_ID . '/' . $FILE_NAME;
				$fileLink = INSTITUTE_DOCUMENTS_PATH . $INSTITUTE_ID . '/' . $FILE_NAME;
			}
		}

		return $filePath;
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
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result = $res;
		}
		return $result;
	}
	public function get_admin_staff_responsibilities($staff_id)
	{
		$resp_array = array();
		$sql2 = "SELECT STAFF_RESPONSIBILITIES FROM admin_staff_details WHERE STAFF_ID ='$staff_id'";
		$res2 = $this->execQuery($sql2);
		if ($res2 && $res2->num_rows > 0) {
			$data  		= $res2->fetch_assoc();
			$resp_array = json_decode($data['STAFF_RESPONSIBILITIES']);
		}
		return $resp_array;
	}
	public function get_admin_staff_responsibilities_details($staff_id)
	{
		$resp = array();
		$resp_array = $this->get_admin_staff_responsibilities($staff_id);
		if ($resp_array != '' && !empty($resp_array)) {
			$arr_str = '';
			foreach ($resp_array as $value) {
				$arr_str .= $value . ",";
			}
			$arr_str = rtrim($arr_str, ",");
			$sql2 = "SELECT * FROM user_responsibilities_master WHERE RESPONSIBILITY_ID IN ($arr_str)";
			$res2 = $this->execQuery($sql2);
			if ($res2 && $res2->num_rows > 0) {
				while ($data2 = $res2->fetch_assoc()) {
					$resp[$data2['RESPONSIBILITY_ID']] = $data2['RESPONSIBILITY'];
				}
			}
		}

		return $resp;
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
	public function get_exam_ques_count($course_id)
	{
		$result = 0;
		$sql = "SELECT COUNT(*) AS TOTAL_QUESTIONS_COUNT FROM exam_question_bank WHERE AICPE_COURSE_ID='$course_id' AND  ACTIVE=1 AND DELETE_FLAG=0";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$result = $data['TOTAL_QUESTIONS_COUNT'];
		}
		return $result;
	}
	public function get_exam_structure($course_id)
	{
		$result = array();
		$sql = "SELECT * FROM exam_structure WHERE AICPE_COURSE_ID='$course_id' AND ACTIVE=1 AND DELETE_FLAG=0 LIMIT 0,1";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$result  = $res;
		}
		return $result;
	}
	public function validate_apply_exam($aicpe_course_id)
	{
		$errors = array();
		$data = array();
		$data['success'] = false;
		$res = $this->get_exam_structure($aicpe_course_id);
		if (!empty($res)) {
			$data1 = $res->fetch_assoc();
			$TOTAL_QUESTIONS = $data1['TOTAL_QUESTIONS'];
			$EXAM_MODE_TYPE = $data1['EXAM_MODE_TYPE'];
			$TOTAL_QUESTIONS_QUE_BANK = $this->get_exam_ques_count($aicpe_course_id);
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
	/* public function apply_coupon_code($couponcode,$instcourse)
	{
		$errors = array();  
		$data = array();
		
		$curr_date 	= date('Y-m-d');
		
		 $sql = "SELECT * FROM coupons WHERE DELETE_FLAG=0 AND COUPON_CODE=UPPER('$couponcode') AND COUPON_END_DATE>='$curr_date'";		
		$res = $this->execQuery($sql);
		if($res!='' && $res->num_rows>0)
		{
			$data1 = $res->fetch_assoc();
			extract($data1);
		}else{
			$errors['coupon_code'] = 'Invalid Coupon Code!';
		}		
		$COUPON_CODE 			= isset($COUPON_CODE)?$COUPON_CODE:'';
		$COUPON_DISCOUNT_RATE 	= isset($COUPON_DISCOUNT_RATE)?$COUPON_DISCOUNT_RATE:'';
		$COUPON_DISCOUNT_VALUE 	= isset($COUPON_DISCOUNT_VALUE)?$COUPON_DISCOUNT_VALUE:'';
		$COUPON_START_DATE 		= isset($COUPON_START_DATE)?$COUPON_START_DATE:'';
		$COUPON_END_DATE 		= isset($COUPON_END_DATE)?$COUPON_END_DATE:'';
		$ACTIVE 				= isset($ACTIVE)?$ACTIVE:'';
		
		
		if ($COUPON_CODE=='')
			$errors['coupon_code'] = 'Invalid Coupon Code!';
		
	
		if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
				$amount = $this->get_inst_course_fees($instcourse);
				$finalAmt = $amount;
				$data['originalamt'] = $amount;
				$data['discountamt'] = $COUPON_DISCOUNT_VALUE;
				if($COUPON_DISCOUNT_RATE=='perminus')
				{
					$data['discountrate'] = 'perminus';					
					$discountAmt = (floatval($amount)/100)*floatval($COUPON_DISCOUNT_VALUE);
					$finalAmt = floatval($amount) - floatval($discountAmt);
				}
				if($COUPON_DISCOUNT_RATE=='amtminus')
				{
					$data['discountrate'] = 'amtminus';
					$finalAmt = floatval($amount) - floatval($COUPON_DISCOUNT_VALUE);
					
				}
				$data['success'] = true;
				$data['amount'] = floatval($finalAmt);
			}
			return json_encode($data);	
	} */

	public function apply_coupon_code($couponcode, $instcourse)
	{
		$errors = array();
		$data = array();

		$curr_date 	= date('Y-m-d');

		$sql = "SELECT * FROM coupons WHERE DELETE_FLAG=0 AND COUPON_CODE=UPPER('$couponcode') AND COUPON_END_DATE>='$curr_date'";
		$res = $this->execQuery($sql);
		if ($res != '' && $res->num_rows > 0) {
			$data1 = $res->fetch_assoc();
			extract($data1);
		} else {
			$errors['coupon_code'] = 'Invalid Coupon Code!';
		}
		$COUPON_CODE 			= isset($COUPON_CODE) ? $COUPON_CODE : '';
		$COUPON_DISCOUNT_RATE 	= isset($COUPON_DISCOUNT_RATE) ? $COUPON_DISCOUNT_RATE : '';
		$COUPON_DISCOUNT_VALUE 	= isset($COUPON_DISCOUNT_VALUE) ? $COUPON_DISCOUNT_VALUE : '';
		$COUPON_START_DATE 		= isset($COUPON_START_DATE) ? $COUPON_START_DATE : '';
		$COUPON_END_DATE 		= isset($COUPON_END_DATE) ? $COUPON_END_DATE : '';
		$ACTIVE 				= isset($ACTIVE) ? $ACTIVE : '';

		if ($COUPON_CODE == '')
			$errors['coupon_code'] = 'Invalid Coupon Code!';


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			$amount = $this->get_inst_course_fees($instcourse);
			$finalAmt = $amount;
			$data['originalamt'] = $amount;
			$data['discountamt'] = $COUPON_DISCOUNT_VALUE;
			if ($COUPON_DISCOUNT_RATE == 'perminus') {
				$data['discountrate'] = 'perminus';
				$discountAmt = (floatval($amount) / 100) * floatval($COUPON_DISCOUNT_VALUE);
				$finalAmt = floatval($amount) - floatval($discountAmt);
			}
			if ($COUPON_DISCOUNT_RATE == 'amtminus') {
				$data['discountrate'] = 'amtminus';
				$finalAmt = floatval($amount) - floatval($COUPON_DISCOUNT_VALUE);
			}
			$data['success'] = true;
			$data['amount'] = floatval($finalAmt);
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
	public function find_institute($institute_code)
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		if ($institute_code == '')
			$errors['institute_code'] = "Plese enter Institute Code!";
		if (! empty($errors)) {
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			$sql = "	SELECT A.*,DATE_FORMAT(A.VERIFIED_ON, '%d-%m-%Y') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.VERIFIED_ON, '%d-%m-%Y') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.DOB, '%d-%m-%Y') AS DOB_FORMATTED, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%d-%m-%Y ') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%i %p') AS UPDATED_DATE, B.USER_NAME, B.USER_LOGIN_ID ,states_master.STATE_NAME FROM institute_details A INNER JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID
            INNER JOIN states_master ON A.STATE=states_master.STATE_ID          
		    WHERE A.DELETE_FLAG=0";


			if ($institute_code != '') {
				$sql .= " AND A.INSTITUTE_CODE='$institute_code' ";
			}
			$sql .= " LIMIT 0,1";
			//echo $sql; exit();
			$res = $this->execQuery($sql);
			if ($res && $res->num_rows > 0) {
				$data['message'] = 'Success! Institute verified successfully!';
				$data['success'] = true;
				$data['data']  = $res->fetch_assoc();
			} else {
				$errors['message'] = 'Sorry! No Institute found!';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
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

	public function list_slidernew($contest_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM slider A WHERE A.DELETE_FLAG=0 ";

		if ($contest_id != '') {
			$sql .= " AND A.SLIDER_ID='$contest_id' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}


	public function list_video($contest_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.* FROM video A WHERE A.DELETE_FLAG=0 AND A.ACTIVE=1";

		if ($contest_id != '') {
			$sql .= " AND A.RESULT_ID='$contest_id' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		//$sql .= 'ORDER BY A.CREATED_ON DESC';
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function get_admin_email($id)
	{
		$state_code = '';
		$sql = "SELECT USER_EMAIL FROM admin_details_master WHERE ADMIN_ID='$id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$state_code = $data['USER_EMAIL'];
		}
		return $state_code;
	}

	public function get_course_name($id)
	{
		$state_code = '';
		$sql = "SELECT get_course_title_modify(A.COURSE_ID) as course_title FROM courses A WHERE A.COURSE_ID='$id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$state_code = $data['course_title'];
		}
		return $state_code;
	}
	public function get_job_title($id)
	{
		$state_code = '';
		$sql = "SELECT A.title FROM  job_updates A WHERE A.id='$id'";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data  = $res->fetch_assoc();
			$state_code = $data['title'];
		}
		return $state_code;
	}

	//get institute course details 
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

	//
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
			$course_name['MULTI_SUB_COURSE_ID'] = 0;
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
			$course_name['DISPLAY_FEES'] = $data['DISPLAY_FEES'];

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
		$selVals .= " ,get_course_award_name (MULTI_SUB_COURSE_AWARD) AS MULTI_SUB_COURSE_AWARD, 	get_course_multi_sub_title_modify(MULTI_SUB_COURSE_ID) AS COURSE_NAME_MODIFY";

		$whereClause = " WHERE MULTI_SUB_COURSE_ID='$multisubcourse_id' AND DELETE_FLAG=0 ";
		$whereClause .= " LIMIT 0,1";
		$sql = $this->selectData($selVals, $tableName, $whereClause);
		$res = $this->execQuery($sql);
		while ($data = $res->fetch_assoc()) {
			$course_name['COURSE_ID'] = 0;
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
			$course_name['DISPLAY_FEES'] = $data['DISPLAY_FEES'];

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

	public function get_institutecourse_id($stud_id)
	{
		$data = '';
		$result = '';
		$sql = "SELECT INSTITUTE_COURSE_ID FROM student_course_details WHERE STUDENT_ID='$stud_id' AND DELETE_FLAG=0";
		$res = $this->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['INSTITUTE_COURSE_ID'];
		}
		return $result;
	}
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
}
