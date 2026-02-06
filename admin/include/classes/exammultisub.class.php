<?php
include_once('database_results.class.php');
include_once('access.class.php');
class exammultisub extends access
{

	/* add new staff in institute 
	@param: 
	@return: json
	*/
	public function add_exam_multi_sub()
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$courseid 		= parent::test(isset($_POST['courseid']) ? $_POST['courseid'] : '');
		$subjectid 		= parent::test(isset($_POST['subjectid']) ? $_POST['subjectid'] : '');
		// $examname 		= parent::test(isset($_POST['examname'])?$_POST['examname']:'');
		$totalmarks 		= parent::test(isset($_POST['totalmarks']) ? $_POST['totalmarks'] : '');
		$totalque 		= parent::test(isset($_POST['totalque']) ? $_POST['totalque'] : '');
		$markperque 		= parent::test(isset($_POST['markperque']) ? $_POST['markperque'] : '');
		$passingmarks 	= parent::test(isset($_POST['passingmarks']) ? $_POST['passingmarks'] : '');
		$examtime 		= parent::test(isset($_POST['examtime']) ? $_POST['examtime'] : '');

		$exam_mode 		= isset($_POST['exam_mode']) ? $_POST['exam_mode'] : '';
		$showresult 		= parent::test(isset($_POST['showresult']) ? $_POST['showresult'] : '');
		$demotest 		= parent::test(isset($_POST['demotest']) ? $_POST['demotest'] : '');

		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		$admin_id 		= $_SESSION['user_id'];
		$created_by  		= $_SESSION['user_fullname'];

		/* check validations */
		if ($courseid == '') $errors['courseid'] = 'Course code is required!';
		if ($subjectid == '') $errors['subjectid'] = 'Subject is required!';
		if ($totalmarks == '') $errors['totalmarks'] = 'Total marks is required!';
		if ($totalque == '') $errors['totalque'] = 'Total questions is required!';
		if ($markperque == '') $errors['markperque'] = 'Marks per questions is required!';
		if ($passingmarks == '') $errors['passingmarks'] = 'Passing marks is required!';
		if ($examtime == '') $errors['examtime'] = 'Exam time is required!';
		if ($exam_mode == '' || empty($exam_mode)) $errors['exam_mode'] = 'Exam mode is required!';

		if ($totalmarks != '' && !is_numeric($totalmarks)) $errors['totalmarks'] = 'Invalid entry!';
		if ($totalque != '' && !is_numeric($totalque)) $errors['totalque'] = 'Invalid entry!';
		if ($passingmarks != '' && !is_numeric($passingmarks)) $errors['passingmarks'] = 'Invalid entry!';

		if ($totalmarks != '' && !ctype_digit($totalmarks))
			$errors['totalmarks'] = 'Please enter valid total marks. Should be positive integer only.';
		if ($totalque != '' && !ctype_digit($totalque))
			$errors['totalque'] = 'Please enter valid total questions. Should be positive integer only.';
		if ($passingmarks != '' && !ctype_digit($passingmarks))
			$errors['passingmarks'] = 'Please enter valid passing marks. Should be positive integer only.';
		if ($passingmarks != '' && !ctype_digit($passingmarks))
			$errors['passingmarks'] = 'Please enter valid passing marks. Should be positive integer only.';
		if ($examtime != '' && !ctype_digit($examtime))
			$errors['examtime'] = 'Please enter valid exam time. Should be positive integer only.';
		if ($markperque != '' && !is_numeric($markperque))
			$errors['markperque'] = 'Please enter valid marks per questions.';


		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			$exam_mode = json_encode($exam_mode);
			parent::start_transaction();
			$tableName 	= "multi_sub_course_exam_structure";
			$tabFields 	= "(EXAM_ID, MULTI_SUB_COURSE_ID, COURSE_SUBJECT_ID, TOTAL_MARKS,TOTAL_QUESTIONS,MARKS_PER_QUE,PASSING_MARKS,EXAM_TIME,EXAM_MODE_TYPE,SHOW_RESULT,DEMO_TEST, ACTIVE,CREATED_BY, CREATED_ON)";
			$insertVals	= "(NULL, '$courseid', '$subjectid', '$totalmarks','$totalque','$markperque','$passingmarks','$examtime', '$exam_mode','$showresult', '$demotest', '$status','$created_by',NOW())";
			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New exam has been added successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the exam.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	/* update institute 
	@param: 
	@return: json
	*/
	public function update_exam_multi_sub($exam_id)
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data 
		$exam_id 			= parent::test(isset($_POST['exam_id']) ? $_POST['exam_id'] : '');
		$courseid 		= parent::test(isset($_POST['courseid']) ? $_POST['courseid'] : '');
		$subjectid 		= parent::test(isset($_POST['subjectid']) ? $_POST['subjectid'] : '');
		/*$examname 		= parent::test(isset($_POST['examname'])?$_POST['examname']:'');*/
		$totalmarks 		= parent::test(isset($_POST['totalmarks']) ? $_POST['totalmarks'] : '');
		$totalque 		= parent::test(isset($_POST['totalque']) ? $_POST['totalque'] : '');
		$passingmarks 	= parent::test(isset($_POST['passingmarks']) ? $_POST['passingmarks'] : '');
		$examtime 		= parent::test(isset($_POST['examtime']) ? $_POST['examtime'] : '');
		$showresult 		= parent::test(isset($_POST['showresult']) ? $_POST['showresult'] : '');
		$demotest 		= parent::test(isset($_POST['demotest']) ? $_POST['demotest'] : '');
		$exam_mode 			= isset($_POST['exam_mode']) ? $_POST['exam_mode'] : '';
		$status 			= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$markperque 			= parent::test(isset($_POST['markperque']) ? $_POST['markperque'] : '');
		$admin_id 		= $_SESSION['user_id'];
		$updated_by  		= $_SESSION['user_fullname'];

		/* check validations */
		if ($courseid == '') $errors['courseid'] = 'Course code is required!';
		if ($subjectid == '') $errors['subjectid'] = 'Subject is required!';
		if ($totalmarks == '') $errors['totalmarks'] = 'Total marks is required!';
		if ($totalque == '') $errors['totalque'] = 'Total questions is required!';
		if ($passingmarks == '') $errors['passingmarks'] = 'Passing marks is required!';
		if ($examtime == '') $errors['examtime'] = 'Exam time hours is required!';
		if ($markperque == '') $errors['markperque'] = 'Marks per questions is required!';
		if ($exam_mode == '' || empty($exam_mode)) $errors['exam_mode'] = 'Exam mode is required!';

		if ($totalmarks != '' && !ctype_digit($totalmarks)) $errors['totalmarks'] = 'Invalid entry!';
		if ($totalque != '' && !ctype_digit($totalque)) $errors['totalque'] = 'Invalid entry!';
		if ($passingmarks != '' && !is_numeric($passingmarks)) $errors['passingmarks'] = 'Invalid entry!';
		if ($examtime != '' && !is_numeric($examtime)) $errors['examtime'] = 'Invalid entry!';
		if ($markperque != '' && !is_numeric($markperque))
			$errors['markperque'] = 'Please enter valid marks per questions.';

		if (!empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			$exam_mode = json_encode($exam_mode);
			parent::start_transaction();
			$tableName 	= "multi_sub_course_exam_structure";
			$setValues 	= "MULTI_SUB_COURSE_ID='$courseid', COURSE_SUBJECT_ID='$subjectid', TOTAL_MARKS='$totalmarks',TOTAL_QUESTIONS='$totalque',MARKS_PER_QUE='$markperque', PASSING_MARKS='$passingmarks', EXAM_TIME='$examtime',EXAM_MODE_TYPE='$exam_mode', SHOW_RESULT='$showresult',DEMO_TEST='$demotest', ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
			$whereClause = " WHERE EXAM_ID='$exam_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);
			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! Exam has been updated successfully!';
		}
		return json_encode($data);
	}
	public function list_exams_multi_sub($exam_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*, get_course_multi_sub_title_modify(A.MULTI_SUB_COURSE_ID) AS COURSE_NAME_MODIFY, (SELECT MULTI_SUB_COURSE_CODE FROM multi_sub_courses WHERE MULTI_SUB_COURSE_ID=A.MULTI_SUB_COURSE_ID) AS MULTI_SUB_COURSE_CODE ,get_subject_title_multi_sub(A.COURSE_SUBJECT_ID) AS SUBJECT_NAME_MODIFY FROM  multi_sub_course_exam_structure A WHERE A.DELETE_FLAG=0 ";

		if ($exam_id != '') {
			$sql .= " AND A.EXAM_ID='$exam_id' ";
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

	/* delete course file */
	public function delete_exam_multi_sub($exam_id)
	{
		$sql = "UPDATE multi_sub_course_exam_structure SET DELETE_FLAG=1, UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW() WHERE EXAM_ID='$exam_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}
	/* list question bank */
	public function list_quetion_bank_multi_sub($que_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT A.*,(SELECT COUNT(*) FROM multi_sub_exam_question_bank B WHERE B.QUEBANK_ID=A.QUEBANK_ID AND B.DELETE_FLAG=0) AS TOTAL_QUE , (SELECT COURSE_CODE FROM courses WHERE COURSE_ID=A.COURSE_ID) AS COURSE_CODE,  DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE, (SELECT MULTI_SUB_COURSE_CODE FROM multi_sub_courses ac WHERE ac.MULTI_SUB_COURSE_ID=A.MULTI_SUB_COURSE_ID) AS MULTI_SUB_COURSE_CODE FROM  multi_sub_exam_question_bank_master A WHERE A.DELETE_FLAG=0 ";
		if ($que_id != '') {
			$sql .= " AND A.QUEBANK_ID='$que_id' ";
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
	/* view question bank */
	public function view_quetion_bank_multi_sub($quebank_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT * FROM multi_sub_exam_question_bank WHERE DELETE_FLAG=0 ";
		if ($quebank_id != '') {
			$sql .= " AND QUEBANK_ID='$quebank_id' ";
		}
		if ($cond != '') {
			$sql .= " $cond ";
		}

		$sql .= ' ORDER BY QUESTION_ID DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	/* upload csv and insert in database */
	public function add_quebank_multi_sub()
	{
		$errors 	= array();  // array to hold validation errors
		$data 	= array();        // array to pass back data

		$courseid 	= parent::test(isset($_POST['courseid']) ? $_POST['courseid'] : '');
		$subjectid 	= parent::test(isset($_POST['subjectid']) ? $_POST['subjectid'] : '');
		//$examname 		= parent::test(isset($_POST['examname'])?$_POST['examname']:'');
		$quebankfile 	= parent::test(isset($_FILES['quebankfile']['name']) ? $_FILES['quebankfile']['name'] : '');

		$quebankfilehindi  = parent::test(isset($_FILES['quebankfilehindi']['name']) ? $_FILES['quebankfilehindi']['name'] : '');

		$quebankimgs 	= parent::test(isset($_FILES['quebankimgs']['name']) ? $_FILES['quebankimgs']['name'] : '');
		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		$admin_id 	= $_SESSION['user_id'];
		$created_by  	= $_SESSION['user_fullname'];

		/* check validations */
		if ($courseid == '') $errors['courseid'] = 'Course code is required!';
		if ($subjectid == '') $errors['subjectid'] = 'Subject is required!';
		if ($quebankfile == '') $errors['quebankfile'] = 'CSV file is required!';
		if ($quebankfile != '') {
			$allowed_ext = array('csv');
			$extension = pathinfo($quebankfile, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['quebankfile'] = 'Invalid file format! Please select valid csv file.';
			}
		}

		if ($quebankfilehindi != '') {
			$allowed_ext = array('csv', 'xls');
			$extension = pathinfo($quebankfilehindi, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['quebankfilehindi'] = 'Invalid file format! Please select valid csv, xls file.';
			}
		}

		if ($quebankimgs != '') {
			$allowed_ext = array('zip');
			$extension = pathinfo($quebankimgs, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['quebankimgs'] = 'Invalid file format! Please select valid zip file.';
			}
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			if ($quebankfile != '' || $quebankfilehindi != '') {

				parent::start_transaction();
				$ext 		= pathinfo($_FILES["quebankfile"]["name"], PATHINFO_EXTENSION);
				$randno 	= @date('d_m_Y') . '_' . mt_rand(0, 123456789);
				$file_name  = 'quebank_english_' . $randno . '.' . $ext;

				$ext1 		= pathinfo($_FILES["quebankfilehindi"]["name"], PATHINFO_EXTENSION);
				$randno1 	= @date('d_m_Y') . '_' . mt_rand(0, 123456789);
				$file_name1  = 'quebank_hindi_' . $randno1 . '.' . $ext1;

				$img_zip_file_name = '';
				if ($quebankimgs != '') {
					$ext 				= pathinfo($_FILES["quebankimgs"]["name"], PATHINFO_EXTENSION);
					$img_zip_file_name  = 'quebank_img_' . $randno . '.' . $ext;
				}
				$tableName 	= "multi_sub_exam_question_bank_master";
				$tabFields 	= "(QUEBANK_ID, MULTI_SUB_COURSE_ID, COURSE_SUBJECT_ID, FILE_NAME,HFILE_NAME,IMG_ZIP_FILE, ACTIVE,CREATED_BY, CREATED_ON)";
				$insertVals	= "(NULL, '$courseid', '$subjectid', '$file_name','$file_name1','$img_zip_file_name','$status','$created_by',NOW())";
				$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
				$exSql = parent::execQuery($insertSql);
				if ($exSql) {
					$last_insert_id = parent::last_id();

					$courseImgPathFile 	= 	QUEBANK_PATH_FOR_MULTI_SUB . '/' . $last_insert_id . '/' . $file_name;
					@mkdir(QUEBANK_PATH_FOR_MULTI_SUB . '/' . $last_insert_id . '/English/', 0777, true);
					@move_uploaded_file($_FILES["quebankfile"]["tmp_name"], $courseImgPathFile);

					$courseImgPathFile1 	= 	QUEBANK_PATH . '/' . $last_insert_id . '/Hindi/' . $file_name1;
					@mkdir(QUEBANK_PATH . '/' . $last_insert_id . '/Hindi/', 0777, true);
					@move_uploaded_file($_FILES["quebankfilehindi"]["tmp_name"], $courseImgPathFile1);

					//upload and extract zip file
					if ($quebankimgs != '') {
						$zipImgPath 	= 	QUEBANK_PATH_FOR_MULTI_SUB . '/' . $last_insert_id . '/';
						$zipImgPathFile = 	$zipImgPath . '' . $img_zip_file_name;
						//$extract_path 	=   $zipImgPath.'images';
						//@mkdir($extract_path,0777,true);
						//	@move_uploaded_file($_FILES["quebankimgs"]["tmp_name"], $zipImgPathFile);

						$zip = new ZipArchive;
						$res = $zip->open($_FILES["quebankimgs"]["tmp_name"]);
						if ($res === TRUE) {
							$zip->extractTo($zipImgPath);
							$zip->close();
						}
					}
					//Import uploaded file to Database
					if (file_exists($courseImgPathFile)) {
						$handle = fopen($courseImgPathFile, "r");
						$tableName1 	= "multi_sub_exam_question_bank";
						$i = 0;
						while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
							//skip the first row					
							if ($i > 0) {

								$QUESTION 	= parent::test(isset($data[0]) ? $data[0] : '');
								$IMAGE 		= isset($data[1]) ? $data[1] : '';
								$OPTION_A 	= parent::test(isset($data[2]) ? $data[2] : '');
								$OPTION_B 	= parent::test(isset($data[3]) ? $data[3] : '');
								$OPTION_C 	= parent::test(isset($data[4]) ? $data[4] : '');
								$OPTION_D 	= parent::test(isset($data[5]) ? $data[5] : '');
								$CORRECT_ANS = isset($data[6]) ? $data[6] : '';

								$tabFields1 	= "(QUESTION_ID,QUEBANK_ID,MULTI_SUB_COURSE_ID,COURSE_SUBJECT_ID, QUESTION,IMAGE, OPTION_A, OPTION_B, OPTION_C,OPTION_D,CORRECT_ANS, CREATED_BY, CREATED_ON,LANG_ID)";
								$insertVals1	= "(NULL,'$last_insert_id','$courseid','$subjectid','$QUESTION','$IMAGE','$OPTION_A','$OPTION_B', '$OPTION_C', '$OPTION_D','$CORRECT_ANS', '$created_by',NOW(),'1')";
								$insertSql1		= parent::insertData($tableName1, $tabFields1, $insertVals1);
								$exSql1 		= parent::execQuery($insertSql1);
							}
							$i++;
						}

						fclose($handle);
					}
					if (file_exists($courseImgPathFile1)) {
						$handle = fopen($courseImgPathFile1, "r");
						$tableName1 	= "multi_sub_exam_question_bank";
						$i = 0;
						while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
							//skip the first row					
							if ($i > 0) {

								$QUESTION 	= parent::test(isset($data[0]) ? $data[0] : '');
								$IMAGE 		= isset($data[1]) ? $data[1] : '';
								$OPTION_A 	= parent::test(isset($data[2]) ? $data[2] : '');
								$OPTION_B 	= parent::test(isset($data[3]) ? $data[3] : '');
								$OPTION_C 	= parent::test(isset($data[4]) ? $data[4] : '');
								$OPTION_D 	= parent::test(isset($data[5]) ? $data[5] : '');
								$CORRECT_ANS = isset($data[6]) ? $data[6] : '';

								$tabFields1 	= "(QUESTION_ID,QUEBANK_ID,MULTI_SUB_COURSE_ID,COURSE_SUBJECT_ID, QUESTION,IMAGE, OPTION_A, OPTION_B, OPTION_C,OPTION_D,CORRECT_ANS, CREATED_BY, CREATED_ON,LANG_ID)";
								$insertVals1	= "(NULL,'$last_insert_id','$courseid','$subjectid','$QUESTION','$IMAGE','$OPTION_A','$OPTION_B', '$OPTION_C', '$OPTION_D','$CORRECT_ANS', '$created_by',NOW(),'2')";
								$insertSql1		= parent::insertData($tableName1, $tabFields1, $insertVals1);
								$exSql1 		= parent::execQuery($insertSql1);
							}
							$i++;
						}

						fclose($handle);
					}
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! New question bank has been added successfully!';
				} else {
					parent::rollback();
					$errors['message'] = 'Sorry! Something went wrong! Could not add the question bank.';
					$data['success'] = false;
					$data['errors']  = $errors;
				}
			}
		}
		return json_encode($data);
	}
	/* upload csv and insert in database */
	public function update_quebank($quebank_id)
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$quebank_id 	= parent::test(isset($_POST['quebank_id']) ? $_POST['quebank_id'] : '');
		$courseid 	= parent::test(isset($_POST['courseid']) ? $_POST['courseid'] : '');
		$examname 		= parent::test(isset($_POST['examname']) ? $_POST['examname'] : '');
		$quebankfile 	= parent::test(isset($_FILES['quebankfile']['name']) ? $_FILES['quebankfile']['name'] : '');
		$quebankimgs 	= parent::test(isset($_FILES['quebankimgs']['name']) ? $_FILES['quebankimgs']['name'] : '');
		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		$admin_id 	= $_SESSION['user_id'];
		$updated_by  	= $_SESSION['user_fullname'];

		/* check validations */
		if ($courseid == '') $errors['courseid'] = 'Course code is required!';
		// if ($examname=='') $errors['examname'] = 'Exam name is required!';
		// if ($quebankfile=='') $errors['quebankfile'] = 'CSV file is required!';
		if ($quebankfile != '') {
			$allowed_ext = array('csv');
			$extension = pathinfo($quebankfile, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['quebankfile'] = 'Invalid file format! Please select valid csv file.';
			}
		}
		if ($quebankimgs != '') {
			$allowed_ext = array('zip');
			$extension = pathinfo($quebankimgs, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['quebankimgs'] = 'Invalid file format! Please select valid zip file.';
			}
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "exam_question_bank_master";
			$setValues 	= "COURSE_ID='$courseid', EXAM_NAME='$examname', ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
			if ($quebankfile != '') {

				$img_zip_file_name = '';

				$ext 		= pathinfo($_FILES["quebankfile"]["name"], PATHINFO_EXTENSION);
				$randno 	= @date('d_m_Y') . '_' . mt_rand(0, 123456789);
				$file_name  = 'quebank_' . $randno . '.' . $ext;
				$setValues .= " , FILE_NAME='$file_name'";
				/*if($quebankimgs!='')
					{
						$ext 				= pathinfo($_FILES["quebankimgs"]["name"], PATHINFO_EXTENSION);
						$img_zip_file_name  = 'quebank_img_'.$randno.'.'.$ext;
						//$setValues .= " , IMG_ZIP_FILE='$img_zip_file_name'";
					}
*/
				$courseImgPathFile 	= 	QUEBANK_PATH . '/' . $quebank_id . '/' . $file_name;
				@mkdir(QUEBANK_PATH . '/' . $quebank_id, 0777, true);
				@move_uploaded_file($_FILES["quebankfile"]["tmp_name"], $courseImgPathFile);

				//upload and extract zip file
				if ($quebankimgs != '') {
					$zipImgPath 	= 	QUEBANK_PATH . '/' . $quebank_id . '/';
					//$zipImgPathFile = 	$zipImgPath.''.$img_zip_file_name;
					//$extract_path 	=   $zipImgPath.''.QUEBANK_IMAGES_PATH;
					//@mkdir($extract_path,0777,true);
					//@move_uploaded_file($_FILES["quebankimgs"]["tmp_name"], $zipImgPathFile);

					$zip = new ZipArchive;
					$res = $zip->open($_FILES["quebankimgs"]["tmp_name"]);
					if ($res === TRUE) {
						$zip->extractTo($zipImgPath);
						$zip->close();
					}
				}
				//Import uploaded file to Database
				if (file_exists($courseImgPathFile)) {
					//$sql = "UPDATE exam_question_bank SET DELETE_FLAG=1, UPDATED_BY='$updated_by', UPDATED_ON=NOW() WHERE QUEBANK_ID='$quebank_id'";
					//parent::execQuery($sql);
					$handle = fopen($courseImgPathFile, "r");
					$tableName1 	= "exam_question_bank";
					$i = 0;
					while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
						//skip the first row					
						if ($i > 0) {
							$tabFields1 	= "(QUESTION_ID,QUEBANK_ID, QUESTION,IMAGE, OPTION_A, OPTION_B, OPTION_C,OPTION_D,CORRECT_ANS, CREATED_BY, CREATED_ON)";
							$insertVals1	= "(NULL,'$quebank_id', '$data[0]','$data[1]','$data[2]','$data[3]', '$data[4]', '$data[5]','$data[6]', '$updated_by',NOW())";
							$insertSql1		= parent::insertData($tableName1, $tabFields1, $insertVals1);
							$exSql1 		= parent::execQuery($insertSql1);
						}
						$i++;
					}
					fclose($handle);
				}
			}
			$whereClause = " WHERE QUEBANK_ID='$quebank_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);
			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Question bank has been updated successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not update the question bank.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}

	/* delete question bank */
	public function delete_que_bank_multi_sub($quebank_id)
	{
		//$sql = "UPDATE exam_question_bank_master SET DELETE_FLAG=1, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW() WHERE QUEBANK_ID='$quebank_id'";		
		//$sql2 = "UPDATE exam_question_bank SET DELETE_FLAG=1, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW() WHERE QUEBANK_ID='$quebank_id'";

		$sql = "DELETE FROM multi_sub_exam_question_bank_master WHERE QUEBANK_ID='$quebank_id'";
		$sql2 = "DELETE FROM multi_sub_exam_question_bank WHERE QUEBANK_ID='$quebank_id'";
		$res = parent::execQuery($sql);
		$res2 = parent::execQuery($sql2);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}
	/* delete question bank */
	public function empty_que_bank_multi_sub($quebank_id)
	{
		$sql2 = "DELETE FROM multi_sub_exam_question_bank WHERE QUEBANK_ID='$quebank_id'";
		$res2 = parent::execQuery($sql2);
		if ($res2 && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}
	public function get_question_detail_multi_sub($question_id, $que_bank = '')
	{
		$data = '';
		$sql = "SELECT *,DATE_FORMAT(CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE FROM multi_sub_exam_question_bank WHERE QUESTION_ID='$question_id'";
		if ($que_bank != '')
			$sql .= " AND QUEBANK_ID='$que_bank'";
		$sql .= ' ORDER BY CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	/* edit question  */
	public function edit_question_multi_sub($question_id)
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$question_id 	= parent::test(isset($_POST['question_id']) ? $_POST['question_id'] : '');
		$quebank_id 	= parent::test(isset($_POST['quebank_id']) ? $_POST['quebank_id'] : '');
		$question 	= parent::test(isset($_POST['question']) ? $_POST['question'] : '');
		$opt1 		= parent::test(isset($_POST['opt1']) ? $_POST['opt1'] : '');
		$opt2 		= parent::test(isset($_POST['opt2']) ? $_POST['opt2'] : '');
		$opt3 		= parent::test(isset($_POST['opt3']) ? $_POST['opt3'] : '');
		$opt4 		= parent::test(isset($_POST['opt4']) ? $_POST['opt4'] : '');
		$correctans 	= parent::test(isset($_POST['correctans']) ? $_POST['correctans'] : '');
		$queimg 		= parent::test(isset($_FILES['queimg']['name']) ? $_FILES['queimg']['name'] : '');

		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		$admin_id 	= $_SESSION['user_id'];
		$updated_by  	= $_SESSION['user_fullname'];
		$ip_address  	= $_SESSION['ip_address'];

		/* check validations */
		if ($question == '') $errors['question'] = 'Question name is required!';
		if ($opt1 == '') $errors['opt1'] = 'Option A is required!';
		if ($opt2 == '') $errors['opt2'] = 'Option B is required!';
		// if ($examname=='') $errors['examname'] = 'Exam name is required!';
		// if ($quebankfile=='') $errors['quebankfile'] = 'CSV file is required!';
		if ($queimg != '') {
			$allowed_ext = array('jpg', 'jpeg', 'gif', 'png');
			$extension = pathinfo($queimg, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['queimg'] = 'Invalid file format! Please select valid image file.';
			}
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "multi_sub_exam_question_bank";
			$setValues 	= "QUESTION='$question', OPTION_A='$opt1',OPTION_B='$opt2', OPTION_C='$opt3',OPTION_D='$opt4',CORRECT_ANS='$correctans', ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW(),UPDATED_BY_IP='$ip_address'";
			if ($queimg != '') {
				$ext 		= pathinfo($_FILES["queimg"]["name"], PATHINFO_EXTENSION);
				$randno 	= mt_rand(0, 123456789);
				$file_name  = 'A' . $randno . '.' . $ext;
				$setValues .= " , IMAGE='$file_name'";
				$courseImgPathFile 	= 	QUEBANK_PATH . '/' . $quebank_id . '/images/' . $file_name;
				@mkdir(QUEBANK_PATH . '/' . $quebank_id . '/images', 0777, true);
				@move_uploaded_file($_FILES["queimg"]["tmp_name"], $courseImgPathFile);
				//parent::create_thumb_img($_FILES["queimg"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
			}
			$whereClause = " WHERE QUESTION_ID='$question_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);
			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Question has been updated successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not update the question.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	/* add new question to the question bank */
	public function add_question_multi_sub($quebank_id)
	{
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$quebank_id 	= parent::test(isset($_POST['quebank_id']) ? $_POST['quebank_id'] : '');
		$course_id 	= parent::test(isset($_POST['course_id']) ? $_POST['course_id'] : '');
		$subject_id 	= parent::test(isset($_POST['subject_id']) ? $_POST['subject_id'] : '');
		$question 	= html_entity_decode(parent::test(isset($_POST['question']) ? $_POST['question'] : ''));
		$opt1 		= parent::test(isset($_POST['opt1']) ? $_POST['opt1'] : '');
		$opt2 		= parent::test(isset($_POST['opt2']) ? $_POST['opt2'] : '');
		$opt3 		= parent::test(isset($_POST['opt3']) ? $_POST['opt3'] : '');
		$opt4 		= parent::test(isset($_POST['opt4']) ? $_POST['opt4'] : '');
		$correctans 	= parent::test(isset($_POST['correctans']) ? $_POST['correctans'] : '');
		$queimg 		= parent::test(isset($_FILES['queimg']['name']) ? $_FILES['queimg']['name'] : '');

		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		$admin_id 	= $_SESSION['user_id'];
		$updated_by  	= $_SESSION['user_fullname'];
		$ip_address  	= $_SESSION['ip_address'];

		/* check validations */
		if ($question == '') $errors['question'] = 'Question name is required!';
		if ($opt1 == '') $errors['opt1'] = 'Option A is required!';
		if ($correctans == '') $errors['correctans'] = 'Correct answer is required!';
		// if ($examname=='') $errors['examname'] = 'Exam name is required!';
		// if ($quebankfile=='') $errors['quebankfile'] = 'CSV file is required!';
		if ($queimg != '') {
			$allowed_ext = array('jpg', 'jpeg', 'gif', 'png');
			$extension = pathinfo($queimg, PATHINFO_EXTENSION);
			if (!in_array($extension, $allowed_ext)) {
				$errors['queimg'] = 'Invalid file format! Please select valid image file.';
			}
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();
			$tableName 	= "multi_sub_exam_question_bank";
			$tabFields = "(QUEBANK_ID,MULTI_SUB_COURSE_ID,COURSE_SUBJECT_ID,QUESTION,OPTION_A,OPTION_B,OPTION_C,OPTION_D,CORRECT_ANS,ACTIVE,CREATED_BY,CREATED_ON,CREATED_BY_IP";
			$insertValues = "('$quebank_id','$course_id','$subject_id','$question', '$opt1', '$opt2', '$opt3', '$opt4', '$correctans', '$status', '$updated_by', NOW(), '$ip_address'";
			if ($queimg != '') {
				$ext 		= pathinfo($_FILES["queimg"]["name"], PATHINFO_EXTENSION);
				$randno 	= mt_rand(0, 123456789);
				$file_name  = 'A' . $randno . '.' . $ext;
				$courseImgPathFile 	= 	QUEBANK_PATH_FOR_MULTI_SUB . '/' . $quebank_id . '/images/' . $file_name;
				@mkdir(QUEBANK_PATH_FOR_MULTI_SUB . '/' . $quebank_id . '/images/', 0777, true);
				@move_uploaded_file($_FILES["queimg"]["tmp_name"], $courseImgPathFile);
				//parent::create_thumb_img($_FILES["queimg"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
				$tabFields .= " , IMAGE";
				$insertValues .= ", '$file_name'";
			}
			$tabFields .= ")";
			$insertValues .= ")";
			$insSql = parent::insertData($tableName, $tabFields, $insertValues);
			$exSql		= parent::execQuery($insSql);
			if ($exSql) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Question has been added successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the question.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function deleteQuestion_multi_sub($question_id)
	{
		$sql = "DELETE FROM multi_sub_exam_question_bank WHERE QUESTION_ID='$question_id'";
		$res = parent::execQuery($sql);

		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}
	/* change exam status */
	public function changeExamStatusMultiSub($exam_id, $flag)
	{
		$sql = "UPDATE multi_sub_course_exam_structure SET ACTIVE='$flag',UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(),UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE EXAM_ID='$exam_id'";
		$res = parent::execQuery($sql);

		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}
	/* change exam status */
	public function changeExamResultDispMultiSub($exam_id, $flag)
	{
		$sql = "UPDATE multi_sub_course_exam_structure SET SHOW_RESULT='$flag',UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(),UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE EXAM_ID='$exam_id'";
		$res = parent::execQuery($sql);

		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}
	/* change exam demo status */
	public function changeExamDemoStatusMultiSub($exam_id, $flag)
	{
		$sql = "UPDATE multi_sub_course_exam_structure SET DEMO_TEST='$flag',UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(),UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE EXAM_ID='$exam_id'";
		$res = parent::execQuery($sql);

		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}
	/* change exam status */
	public function changeQuebankStatus($quebank_id, $flag)
	{
		$sql = "UPDATE exam_question_bank_master SET ACTIVE='$flag',UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(),UPDATED_ON_IP='" . $_SESSION['ip_address'] . "' WHERE QUEBANK_ID='$quebank_id'";
		$res = parent::execQuery($sql);

		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}
	/* get course name by course_id */
	public function get_course_name_multi_sub($course_id)
	{
		$result = '';
		$sql = "SELECT COURSE_NAME FROM multi_sub_courses WHERE COURSE_ID='$course_id' LIMIT 0,1";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$result = $data['COURSE_NAME'];
		}
		return $result;
	}

	//filter exams
	public function filter_aicpe_exams($studid = '', $instid, $courseid = '', $examtype = '', $examstatus = '')
	{
		$data = '';
		$sql = "SELECT A.*,B.STUDENT_MOBILE,B.STUDENT_EMAIL,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, get_student_name (A.STUDENT_ID) AS  STUDENT_NAME, (SELECT C.EXAM_STATUS FROM exam_status_master C WHERE C.EXAM_STATUS_ID=A.EXAM_STATUS) AS EXAM_STATUS_NAME, (SELECT D.EXAM_TYPE FROM exam_types_master D WHERE D.EXAM_TYPE_ID=A.EXAM_TYPE) AS EXAM_TYPE_NAME, student_calculate_balance_fees2 (A.STUD_COURSE_DETAIL_ID,0) AS BALANCE_FEES FROM student_course_details A LEFT JOIN student_details B ON A.STUDENT_ID=B.STUDENT_ID LEFT JOIN institute_courses C ON A.INSTITUTE_COURSE_ID=C.INSTITUTE_COURSE_ID WHERE A.ADMISSION_CONFIRMED=1 AND A.DELETE_FLAG=0 AND C.COURSE_TYPE=1 AND B.DELETE_FLAG=0  ";
		if ($studid != '') {
			$sql .= " AND A.STUDENT_ID='$studid' ";
		}
		if ($instid != '') {
			$sql .= " AND A.INSTITUTE_ID='$instid' ";
		}
		if ($courseid != '') {
			$sql .= " AND A.INSTITUTE_COURSE_ID='$courseid' ";
		}
		if ($examtype != '') {
			$sql .= " AND A.EXAM_TYPE='$examtype' ";
		}
		if ($examstatus != '') {
			$sql .= " AND A.EXAM_STATUS='$examstatus' ";
		}

		$sql .= 'ORDER BY A.CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	public function list_exam_codes($studid = '', $instid, $courseid = '', $examtype = '', $examstatus = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT A.*,B.STUDENT_MOBILE,B.STUDENT_EMAIL,B.STUDENT_CODE,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, get_student_name (A.STUDENT_ID) AS  STUDENT_NAME, (SELECT C.EXAM_STATUS FROM exam_status_master C WHERE C.EXAM_STATUS_ID=A.EXAM_STATUS) AS EXAM_STATUS_NAME, (SELECT D.EXAM_TYPE FROM exam_types_master D WHERE D.EXAM_TYPE_ID=A.EXAM_TYPE) AS EXAM_TYPE_NAME, student_calculate_balance_fees (A.STUDENT_ID,A.INSTITUTE_COURSE_ID,0) AS BALANCE_FEES,DATE_FORMAT(A.EXAM_SECRETE_CODE_DATE, '%d-%m-%Y %h:%i:%p') AS EXAM_CODE_DATE  FROM student_course_details A LEFT JOIN student_details B ON A.STUDENT_ID=B.STUDENT_ID WHERE A.DELETE_FLAG=0 ";
		if ($studid != '') {
			$sql .= " AND A.STUDENT_ID='$studid' ";
		}
		if ($instid != '') {
			$sql .= " AND A.INSTITUTE_ID='$instid' ";
		}
		if ($courseid != '') {
			$sql .= " AND A.INSTITUTE_COURSE_ID='$courseid' ";
		}
		if ($examtype != '') {
			$sql .= " AND A.EXAM_TYPE='$examtype' ";
		}
		if ($examstatus != '') {
			$sql .= " AND A.EXAM_STATUS='$examstatus' ";
		}
		if ($cond != '') {
			$sql .= $cond;
		}

		$sql .= 'ORDER BY A.EXAM_SECRETE_CODE_DATE DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//apply for exam by institute
	public function add_student_exam()
	{
		$errors 	= array();  // array to hold validation errors
		$data 		= array();
		$data['success'] = false;		// array to pass back data
		$data['invalid'] = array();		// array to pass back data
		$data['photo'] = array();		// array to pass back data
		$data['photo_id'] = array();		// array to pass back data
		$examstatus = isset($_POST['examstatus1']) ? $_POST['examstatus1'] : '';
		$examtype 	= isset($_POST['examtype1']) ? $_POST['examtype1'] : '';
		$checkstud 	= isset($_POST['checkstud']) ? $_POST['checkstud'] : '';

		if ($examtype == '') $errors['examtype'] = 'Please select exam mode!';
		if ($checkstud == '' || empty($checkstud)) $errors['checkstud'] = 'Please select atleast one student!';
		if (! empty($errors)) {
			$data['errors']  	= $errors;
			$message  		= isset($errors['examtype']) ? $errors['examtype'] : '';
			if ($message != '') $message .= '<br>';
			$message  		.= isset($errors['checkstud']) ? $errors['checkstud'] : '';
			$data['message']  = $message;
		} else {

			if (is_array($checkstud) && count($checkstud) > 0) {
				foreach ($checkstud as $coursedetailid) {
					$checksql = "SELECT INSTITUTE_COURSE_ID,STUDENT_ID,INSTITUTE_ID,get_student_name(STUDENT_ID) AS STUDENT_NAME FROM student_course_details WHERE STUD_COURSE_DETAIL_ID='$coursedetailid'";
					$checkres = parent::execQuery($checksql);
					if ($checkres && $checkres->num_rows > 0) {
						$checkdata 				= $checkres->fetch_assoc();
						$INSTITUTE_COURSE_ID 	= $checkdata['INSTITUTE_COURSE_ID'];
						$INSTITUTE_ID 			= $checkdata['INSTITUTE_ID'];
						$STUDENT_ID 			= $checkdata['STUDENT_ID'];
						$STUDENT_NAME 			= $checkdata['STUDENT_NAME'];
						//check student docs
						$studphoto 				= parent::get_student_files($STUDENT_ID, STUD_PHOTO);
						/*
							$studphotoid 			= parent::get_student_files($STUDENT_ID,STUD_PHOTO_ID); */
						if ($studphoto == '') {
							array_push($data['photo'], $STUDENT_NAME);
						}
						/*
							if($studphotoid=='')
							{
								array_push($data['photo_id'],$STUDENT_NAME);
							}
							*/
						if ($studphoto != '') {

							$instcourse 			= parent::get_inst_course_info($INSTITUTE_COURSE_ID);
							$aicpe_course_id 		= $instcourse['COURSE_ID'];
							$valid_exam 			= parent::validate_apply_exam($aicpe_course_id);
							if (!empty($valid_exam)) {
								//print_r($valid_exam);

								$invalidArr = array();
								$validerrors = isset($valid_exam['errors']) ? $valid_exam['errors'] : '';
								$success_flag 	= isset($valid_exam['success']) ? $valid_exam['success'] : '';
								if ($success_flag == true) {
									$exam_modes = isset($valid_exam['exam_modes']) ? $valid_exam['exam_modes'] : '';
									$exam_modes = json_decode($exam_modes);
									if (in_array($examtype, $exam_modes)) {
										$tableName 	= "student_course_details";
										$setValues 	= "EXAM_STATUS='$examstatus', EXAM_TYPE='$examtype', UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(),UPDATED_ON_IP='" . $_SESSION['ip_address'] . "'";

										if ($examtype == '1' && $examstatus == '2') $setValues .= ",DEMO_COUNT=0";
										if ($examtype == '1' && $examstatus == '1') $setValues .= ",DEMO_COUNT=0";
										if ($examtype == '1' && $examstatus == '3') $setValues .= ",DEMO_COUNT=10";

										$whereClause = " WHERE STUD_COURSE_DETAIL_ID='$coursedetailid'";
										$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
										$exSql		= parent::execQuery($updateSql);
										if ($exSql && parent::rows_affected() > 0) {
											parent::commit();
											$data['success'] = true;
											$data['message'] = 'Success! Students has been aplied for exam successfully!';
										}
									} else {
										// $invalidArr['coursedetailid'] = $validerrors;
										array_push($data['invalid'], $coursedetailid);
									}
								}
							}
						}
					}
				}
			}
		}
		return json_encode($data);
	}
	public function update_student_exam()
	{
		$course_detail_id = parent::test(isset($_POST['course_detail_id']) ? $_POST['course_detail_id'] : '');
		$student_id 	= parent::test(isset($_POST['student_id']) ? $_POST['student_id'] : '');
		$course_type 	= parent::test(isset($_POST['course_type']) ? $_POST['course_type'] : '');
		$course	 		= parent::test(isset($_POST['course']) ? $_POST['course'] : '');
		$examstatus		= parent::test(isset($_POST['examstatus']) ? $_POST['examstatus'] : '');
		$examtype		= parent::test(isset($_POST['examtype']) ? $_POST['examtype'] : '');
		$status	 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');
		$errors = array();  // array to hold validation errors
		$data = array();
		$requiredArr = array('examstatus' => $examstatus, 'examtype' => $examtype);
		$checkRequired = parent::valid_required($requiredArr);
		if (!empty($checkRequired)) {
			foreach ($checkRequired as $value)
				$errors[$value] = 'Required field!';
		}
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			parent::start_transaction();

			$tableName2 	= "student_course_details";
			$setValues2 	= "EXAM_STATUS='$examstatus',EXAM_TYPE='$examtype', UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(),UPDATED_ON_IP='" . $_SESSION['ip_address'] . "'";
			$whereClause2	= " WHERE STUD_COURSE_DETAIL_ID='$course_detail_id'";
			$updateSql2	= parent::updateData($tableName2, $setValues2, $whereClause2);
			$exSql2			= parent::execQuery($updateSql2);
			if ($exSql2 && parent::rows_affected() > 0) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Student\'s exam has been updated successfully!';
			}
		}
		return json_encode($data);
	}
	// change student course details exam type 
	public function change_exam_type($course_detail_id, $exam_type)
	{
		$res = '';
		$tableName		= "student_course_details";
		$setValues 		= "EXAM_TYPE='$exam_type', UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(), UPDATED_ON_IP='" . $_SESSION['ip_address'] . "'";
		$whereClause 	= " WHERE STUD_COURSE_DETAIL_ID='$course_detail_id' ";
		$exc 			= parent::updateData($tableName, $setValues, $whereClause);
		$res			= parent::execQuery($exc);
		if ($res && parent::rows_affected() > 0)
			return true;
		return false;
	}
	public function change_exam_status($course_detail_id, $exam_status)
	{
		$res = '';
		$tableName		= "student_course_details";
		$setValues 		= "EXAM_STATUS='$exam_status', UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(), UPDATED_ON_IP='" . $_SESSION['ip_address'] . "'";
		if ($exam_status == 2) {
		}
		$whereClause 	= " WHERE STUD_COURSE_DETAIL_ID='$course_detail_id' ";
		$exc 			= parent::updateData($tableName, $setValues, $whereClause);
		$res			= parent::execQuery($exc);
		if ($res && parent::rows_affected() > 0)
			return true;
		return false;
	}
	public function delete_stud_exam_details($course_detail_id)
	{
		$res = '';
		$tableName		= "student_course_details";
		$setValues 		= "DELETE_FLAG='1',ACTIVE='0', UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(), UPDATED_ON_IP='" . $_SESSION['ip_address'] . "'";
		$whereClause 	= " WHERE STUD_COURSE_DETAIL_ID='$course_detail_id' ";
		$exc 			= parent::updateData($tableName, $setValues, $whereClause);
		$res			= parent::execQuery($exc);
		if ($res && parent::rows_affected() > 0)
			return true;
		return false;
	}



	//generate offline quetion paper
	public function generate_offline_paper($course_id, $limit)
	{
		$res = '';
		$sql = "SELECT A.*,DATE_FORMAT(NOW(),'%d/%m/%Y') AS CREATED_DATE,(SELECT D.COURSE_NAME FROM courses D WHERE D.COURSE_ID=A.COURSE_ID) AS EXAM_NAME FROM exam_question_bank A LEFT JOIN exam_question_bank_master B ON A.QUEBANK_ID=B.QUEBANK_ID  WHERE B.COURSE_ID='$course_id' AND A.ACTIVE=1 AND A.DELETE_FLAG=0 ORDER BY A.QUESTION_ID LIMIT 0,$limit";
		$exc = parent::execQuery($sql);
		if ($exc && $exc->num_rows > 0)
			$res = $exc;
		return $res;
	}

	public function get_exam_strucutre($course_id)
	{
		$data = '';
		$sql = "SELECT * FROM exam_structure WHERE COURSE_ID='$course_id' LIMIT 0,1";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
		}
		return $data;
	}
	//check exam secrete code is available or not
	public function get_exam_secrete_code($stucourseid)
	{
		$code = '';
		$sql = "SELECT EXAM_SECRETE_CODE FROM student_course_details WHERE STUD_COURSE_DETAIL_ID='$stucourseid'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			if ($data['EXAM_SECRETE_CODE'] != '' || $data['EXAM_SECRETE_CODE'] != 'NULL')
				$code =  $data['EXAM_SECRETE_CODE'];
		}
		return $code;
	}
	//generate exam secrete code before appearing final exam
	public function generate_esc($stucourseid)
	{
		$code = parent::getRandomCode(6);
		$sql = "UPDATE student_course_details SET EXAM_SECRETE_CODE='$code', DEMO_COUNT=get_institute_demo_count(INSTITUTE_ID), EXAM_SECRETE_CODE_DATE=NOW() WHERE STUD_COURSE_DETAIL_ID='$stucourseid'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return true;
		}
		return false;
	}
	//list student exam results
	public function list_student_exam_results_multi_sub($id = '', $stud_id = '', $inst_id = '', $stud_course_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT A.*,C.REQUEST_STATUS AS REQUEST_STATUS,DATE_FORMAT(C.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS ORDER_DATE,DATE_FORMAT(D.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS APPROVE_DATE,get_student_name(A.STUDENT_ID) AS STUDENT_NAME,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE FROM multi_sub_exam_result_final A LEFT JOIN certificate_order_requests C ON C.EXAM_RESULT_FINAL_ID = A.EXAM_RESULT_FINAL_ID LEFT JOIN certificate_requests D ON D.EXAM_RESULT_FINAL_ID = A.EXAM_RESULT_FINAL_ID WHERE A.DELETE_FLAG=0 ";
		if ($id != '') {
			$sql .= " AND A.EXAM_RESULT_FINAL_ID='$id' ";
		}
		if ($inst_id != '') {
			$sql .= " AND A.INSTITUTE_ID='$inst_id' ";
		}
		if ($stud_id != '') {
			$sql .= " AND A.STUDENT_ID='$stud_id' ";
		}
		if ($stud_course_id != '') {
			$sql .= " AND A.STUD_COURSE_ID='$stud_course_id' ";
		}
		if ($cond != '') {
			$sql .= $cond;
		}
		$sql .= ' ORDER BY A.CREATED_ON DESC';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//list of institute multi subject marks for specific exam result final id 

	public function list_student_exam_results_multi_sub_list($id = '', $stud_id = '', $inst_id = '', $stud_course_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT A.*,get_subject_title_multi_sub(A.STUDENT_SUBJECT_ID) as SUBJECT_NAME, B.TOTAL_MARKS,D.POSITION FROM multi_sub_exam_result A LEFT JOIN multi_sub_course_exam_structure B ON A.STUDENT_SUBJECT_ID = B.COURSE_SUBJECT_ID LEFT JOIN institute_course_subjects D ON D.COURSE_SUBJECT_ID = A.STUDENT_SUBJECT_ID WHERE A.DELETE_FLAG=0 AND A.ACTIVE=1 AND B.DELETE_FLAG=0 AND D.INSTITUTE_ID='$inst_id' AND D.DELETE_FLAG=0 ";
		if ($id != '') {
			$sql .= " AND A.EXAM_RESULT_FINAL_ID='$id' ";
		}
		if ($inst_id != '') {
			$sql .= " AND A.INSTITUTE_ID='$inst_id' ";
		}
		if ($stud_id != '') {
			$sql .= " AND A.STUDENT_ID='$stud_id' ";
		}
		if ($stud_course_id != '') {
			$sql .= " AND A.STUD_COURSE_ID='$stud_course_id' ";
		}
		if ($cond != '') {
			$sql .= $cond;
		}
		$sql .= ' GROUP BY A.STUDENT_SUBJECT_ID ORDER BY D.POSITION ASC';
		// echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	public function list_offline_downloaded_papers($id = '', $studid = '', $inst_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT A.*,get_student_name(A.STUDENT_ID) AS STUDENT_NAME, get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO,(SELECT B.EXAM_STATUS FROM exam_status_master B WHERE B.EXAM_STATUS_ID=A.EXAM_STATUS)  AS EXAM_STATUS_NAME, DATE_FORMAT(A.CREATED_ON,'%d-%m-%Y %h:%i %p') AS CREATED_DATE FROM exam_offline_papers A WHERE DELETE_FLAG=0 ";
		if ($id != '') {
			$sql .= " AND A.OFFLINE_PAPER_ID='$id' ";
		}
		if ($studid != '') {
			$sql .= " AND A.STUDENT_ID='$studid' ";
		}
		if ($inst_id != '') {
			$sql .= " AND A.INSTITUTE_ID='$inst_id' ";
		}

		if ($cond != '') {
			$sql .= $cond;
		}
		$sql .= ' ORDER BY A.CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//download offline exam papers
	public function download_offline_exam_ppr()
	{
		$errors = array();  // array to hold validation errors
		$data 	= array();
		$esc 	= parent::test(isset($_POST['esc']) ? $_POST['esc'] : '');
		$studid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
		//check paper already downloaded or not

		if ($esc == '') $errors['esc'] = 'Required! Please enter ESC code!';
		if (! empty($errors)) {
			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
			$data['message']  = 'Please correct all the errors.';
		} else {
			$check = $this->check_offline_download($studid, $esc);
			if ($check) {
				$data['message'] = 'Exam papers already downloaded!';
				$data['success'] = false;
			}
			$sql = "SELECT STUD_COURSE_DETAIL_ID,EXAM_STATUS,EXAM_TYPE FROM student_course_details WHERE EXAM_SECRETE_CODE='$esc' AND STUDENT_ID='$studid' LIMIT 0,1";
			$res = parent::execQuery($sql);
			if ($res && $res->num_rows > 0) {
				$result = $res->fetch_assoc();
				if ($result['EXAM_STATUS'] == 3)
					$data['message'] = 'Sorry! This exam has been already completed!';
				if ($result['EXAM_TYPE'] != 2)
					$data['message'] = 'Sorry! This code is not valid for this exam. Contact your Institute!';
				if (!isset($data['message']) && empty($data['message'])) {
					$data['success'] = TRUE;
					$data['scd'] = $result['STUD_COURSE_DETAIL_ID'];
				}
			} else {
				$data['message'] = 'Error! Invalid code!';
			}
		}
		return json_encode($data);
	}
	//check the offline function already downloaded
	public function check_offline_download($stud_id, $esc)
	{
		$sql = "SELECT COUNT(*) AS TOTAL FROM exam_offline_papers WHERE STUDENT_ID='$stud_id' AND EXAM_SECRETE_CODE='$esc'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$total = $data['TOTAL'];
			if ($total > 0)
				return true;
		}
		return false;
	}

	//add offline exam marks
	//apply for exam by institute
	public function add_offline_exam_marks()
	{
		$errors 			= array();  // array to hold validation errors
		$data 				= array();
		$data['success'] 	= false;		// array to pass back data
		$offline_paper_id 	= parent::test(isset($_POST['offline_paper_id']) ? $_POST['offline_paper_id'] : '');
		$stud_course_detail_id = parent::test(isset($_POST['stud_course_detail_id']) ? $_POST['stud_course_detail_id'] : '');
		$student_id 		= parent::test(isset($_POST['student_id']) ? $_POST['student_id'] : '');
		$institute_id 		= parent::test(isset($_POST['institute_id']) ? $_POST['institute_id'] : '');
		$exam_id 			= parent::test(isset($_POST['exam_id']) ? $_POST['exam_id'] : '');
		$inst_course_id 	= parent::test(isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '');
		$exam_secrete_code 	= parent::test(isset($_POST['exam_secrete_code']) ? $_POST['exam_secrete_code'] : '');
		$exam_title 		= parent::test(isset($_POST['exam_title']) ? $_POST['exam_title'] : '');
		$exam_attempt 		= parent::test(isset($_POST['exam_attempt']) ? $_POST['exam_attempt'] : '');
		$exam_total_que 	= parent::test(isset($_POST['exam_total_que']) ? $_POST['exam_total_que'] : '');
		$exam_total_marks 	= parent::test(isset($_POST['exam_total_marks']) ? $_POST['exam_total_marks'] : '');
		$exam_passing_marks = parent::test(isset($_POST['exam_passing_marks']) ? $_POST['exam_passing_marks'] : '');
		$exam_marks_per_que = parent::test(isset($_POST['exam_marks_per_que']) ? $_POST['exam_marks_per_que'] : '');
		$exam_time 			= parent::test(isset($_POST['exam_time']) ? $_POST['exam_time'] : '');
		$exam_type 			= parent::test(isset($_POST['exam_type']) ? $_POST['exam_type'] : '');
		$exam_status		= parent::test(isset($_POST['exam_status']) ? $_POST['exam_status'] : '');
		$marksobt 			= parent::test(isset($_POST['marksobt']) ? $_POST['marksobt'] : 0);
		$totalcorrect 		= parent::test(isset($_POST['totalcorrect']) ? $_POST['totalcorrect'] : 0);
		$totalincorrect 		= parent::test(isset($_POST['totalincorrect']) ? $_POST['totalincorrect'] : 0);
		$marks_per 			= parent::test(isset($_POST['marks_per']) ? $_POST['marks_per'] : 0);
		$grade 				= parent::test(isset($_POST['grade']) ? $_POST['grade'] : 0);
		$result_status 		= parent::test(isset($_POST['result_status']) ? $_POST['result_status'] : 0);
		$scananswersheet		= isset($_FILES['scananswersheet']['name']) ? $_FILES['scananswersheet']['name'] : '';

		if ($totalcorrect == '') $errors['totalcorrect'] = 'Please enter the total number of correct answers!';
		if ($totalcorrect != 0 && $totalcorrect != '' && !parent::valid_decimal($totalcorrect)) $errors['totalcorrect'] = 'Invalid entry! Enter number only!';
		if ($totalincorrect != 0 && $totalincorrect != '' && !parent::valid_decimal($totalincorrect)) $errors['totalincorrect'] = 'Invalid entry! Enter number only!';
		if ($marksobt != 0 && $marksobt != '' && !parent::valid_decimal($marksobt)) $errors['marksobt'] = 'Invalid entry! Enter number only!';

		$created_by = $_SESSION['user_fullname'];
		$created_on_ip = $_SESSION['ip_address'];

		if (! empty($errors)) {
			$data['errors']  	= $errors;
			$data['message']  = "Please correct all the errors!";
		} else {
			parent::start_transaction();
			$tableName 	= "exam_result";
			$tabFields 	= "(EXAM_RESULT_ID, STUD_COURSE_ID,STUDENT_ID,INSTITUTE_ID,EXAM_ID, 
									INSTITUTE_COURSE_ID, EXAM_TITLE,EXAM_ATTEMPT, EXAM_SECRETE_CODE,EXAM_TOTAL_QUE, EXAM_TOTAL_MARKS,EXAM_MARKS_PER_QUE, EXAM_PASSING_MARKS, CORRECT_ANSWER,INCORRECT_ANSWER,  MARKS_OBTAINED,MARKS_PER, RESULT_STATUS, GRADE,EXAM_TYPE, CREATED_BY, CREATED_ON,	CREATED_ON_IP)";

			$insertVals	= "(NULL,'$stud_course_detail_id','$student_id','$institute_id','$exam_id','$inst_course_id','$exam_title','1','$exam_secrete_code', '$exam_total_que', '$exam_total_marks','$exam_marks_per_que','$exam_passing_marks','$totalcorrect','$totalincorrect','$marksobt', '$marks_per', '$result_status','$grade', '$exam_type','$created_by',NOW(),'$created_on_ip')";

			$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
			$exSql		= parent::execQuery($insertSql);
			if ($exSql) {
				$last_id = parent::last_id();
				/* upload files */
				if ($scananswersheet != '') {
					$filePath		= EXAM_OFFLINE_PAPER_PATH . '/' . $student_id;
					$ext 			= pathinfo($_FILES["scananswersheet"]["name"], PATHINFO_EXTENSION);
					$filename		= $exam_secrete_code . '_' . mt_rand(0, 123456789) . '_ansproof' . '.' . $ext;
					$file_name		= $filePath . '/' . $filename;
					@move_uploaded_file($_FILES["scananswersheet"]["tmp_name"], $file_name);
					$sqlUpd 	= "UPDATE exam_result SET OFFLINE_ANS_PROOF='$filename' WHERE EXAM_RESULT_ID='$last_id'";
					parent::execQuery($sqlUpd);
				}

				/* update the exam status to appeared*/
				$sqlUpd 	= "UPDATE exam_offline_papers SET EXAM_STATUS='3', EXAM_RESULT_ID='$last_id' WHERE OFFLINE_PAPER_ID='$offline_paper_id'";
				parent::execQuery($sqlUpd);
				$sqlUpd2 	= "UPDATE student_course_details SET EXAM_STATUS=3, EXAM_ATTEMPT=1 WHERE STUD_COURSE_DETAIL_ID='$stud_course_detail_id'";
				parent::execQuery($sqlUpd2);
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Offline exam result has been added successfully!';
			} else {
				parent::rollback();
				$errors['message'] = 'Sorry! Something went wrong! Could not add the offline exam result.';
				$data['success'] = false;
				$data['errors']  = $errors;
			}
		}
		return json_encode($data);
	}
	public function update_offline_exam_marks()
	{
		$errors 			= array();  // array to hold validation errors
		$data 				= array();
		// array to pass back data
		$exam_result_id 	= parent::test(isset($_POST['exam_result_id']) ? $_POST['exam_result_id'] : '');
		$student_id		 	= parent::test(isset($_POST['student_id']) ? $_POST['student_id'] : '');
		$exam_secrete_code 	= parent::test(isset($_POST['exam_secrete_code']) ? $_POST['exam_secrete_code'] : '');
		$exam_total_que 	= parent::test(isset($_POST['exam_total_que']) ? $_POST['exam_total_que'] : '');
		$exam_total_marks 	= parent::test(isset($_POST['exam_total_marks']) ? $_POST['exam_total_marks'] : '');
		$exam_marks_per_que = parent::test(isset($_POST['exam_marks_per_que']) ? $_POST['exam_marks_per_que'] : '');
		$totalcorrect 		= parent::test(isset($_POST['totalcorrect']) ? $_POST['totalcorrect'] : '');
		$totalincorrect 	= parent::test(isset($_POST['totalincorrect']) ? $_POST['totalincorrect'] : '');
		$marksobt 			= parent::test(isset($_POST['marksobt']) ? $_POST['marksobt'] : '');
		$marks_per 			= parent::test(isset($_POST['marks_per']) ? $_POST['marks_per'] : '');
		$grade 				= parent::test(isset($_POST['grade']) ? $_POST['grade'] : '');
		$result_status 		= parent::test(isset($_POST['result_status']) ? $_POST['result_status'] : '');
		$scananswersheet		= isset($_FILES['scananswersheet']['name']) ? $_FILES['scananswersheet']['name'] : '';

		if ($totalcorrect == '') $errors['totalcorrect'] = 'Please enter the total number of correct answers!';
		if ($totalcorrect != 0 && $totalcorrect != '' && !parent::valid_decimal($totalcorrect)) $errors['totalcorrect'] = 'Invalid entry! Enter number only!';
		if ($totalincorrect != 0 && $totalincorrect != '' && !parent::valid_decimal($totalincorrect)) $errors['totalincorrect'] = 'Invalid entry! Enter number only!';


		$created_by = $_SESSION['user_fullname'];
		$created_on_ip = $_SESSION['ip_address'];

		if (! empty($errors)) {
			$data['errors']  	= $errors;
			$data['success'] 	= false;
			$data['message']  = "Please correct all the errors!";
		} else {
			parent::start_transaction();
			if ($scananswersheet != '') {
				$filePath		= EXAM_OFFLINE_PAPER_PATH . '/' . $student_id;
				$ext 			= pathinfo($_FILES["scananswersheet"]["name"], PATHINFO_EXTENSION);
				$filename		= $exam_secrete_code . '_' . mt_rand(0, 123456789) . '_ansproof' . '.' . $ext;
				$file_name		= $filePath . '/' . $filename;
				@move_uploaded_file($_FILES["scananswersheet"]["tmp_name"], $file_name);
			}
			$tableName		= "exam_result";
			$setValues 		= "CORRECT_ANSWER='$totalcorrect', INCORRECT_ANSWER='$totalincorrect', MARKS_OBTAINED='$marksobt', MARKS_PER='$marks_per',RESULT_STATUS='$result_status',GRADE='$grade',UPDATED_BY='$created_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$created_on_ip'";
			if (isset($filename))
				$setValues .= ", OFFLINE_ANS_PROOF='$filename'";
			$whereClause 	= " WHERE EXAM_RESULT_ID='$exam_result_id' ";
			$exc 			= parent::updateData($tableName, $setValues, $whereClause);
			$res			= parent::execQuery($exc);
			if ($res && parent::rows_affected() > 0) {
				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Exam result has been update successfully!';
			} else {
				parent::rollback();
				$data['success'] = false;
				$data['message'] = 'Sorry! Something went wrong! Could not update the exam result.';
			}
		}
		return json_encode($data);
	}
	// delete student exam results
	public function delete_student_exam_result_multi_sub($exam_result_id)
	{
		$res = '';
		$tableName		= "multi_sub_exam_result_final";
		$setValues 		= " DELETE_FLAG='1', ACTIVE='0', UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW(), UPDATED_ON_IP='" . $_SESSION['ip_address'] . "'";
		$whereClause 	= " WHERE EXAM_RESULT_FINAL_ID='$exam_result_id' ";
		$exc 			= parent::updateData($tableName, $setValues, $whereClause);
		$res			= parent::execQuery($exc);
		if ($res && parent::rows_affected() > 0)
			return true;
		return false;
	}

	//apply for certificate by institute
	public function apply_for_certificate()
	{
		$errors 	= array();  // array to hold validation errors
		$data 		= array();
		$data['success'] = false;		// array to pass back data		
		$institute_id 	= isset($_POST['institute_id']) ? $_POST['institute_id'] : '';
		$checkstud 	= isset($_POST['checkstud']) ? $_POST['checkstud'] : '';
		$created_by = $_SESSION['user_fullname'];
		$ip_address = $_SESSION['ip_address'];

		if ($checkstud == '' || empty($checkstud)) $errors['checkstud'] = 'Please select atleast one student!';

		//check if wallet has sufficient balance
		$walletBal = 0;
		$totalToPay = 0;
		$wallet_id = '';
		$res = parent::get_wallet('', $institute_id, 2);
		if ($res != '') {
			$data1 = $res->fetch_assoc();
			$walletBal = $data1['TOTAL_BALANCE'];
			$wallet_id = $data1['WALLET_ID'];
		} else {
			$errors['checkstud'] = "Sorry! Your wallet is empty!";
		}
		if (is_array($checkstud) && count($checkstud) > 0) {
			$resultidstr = rtrim(implode(",", $checkstud), ",");
			/* get total fees */
			//$sql2 = "SELECT DISTINCT A.INSTITUTE_ID, (SUM(B.EXAM_FEES)+100) AS TOTAL_FEES  FROM exam_result A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID WHERE A.EXAM_RESULT_ID IN ($resultidstr)";

			$sql2 = "SELECT DISTINCT A.INSTITUTE_ID,SUM((SELECT C.COURSE_FEES FROM courses C WHERE C.COURSE_ID=B.COURSE_ID))+100 AS TOTAL_FEES FROM exam_result A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID WHERE A.EXAM_RESULT_ID IN ($resultidstr)";

			$res2 = parent::execQuery($sql2);
			if ($res2 && $res2->num_rows > 0) {
				$data2 = $res2->fetch_assoc();
				$totalToPay = $data2['TOTAL_FEES'];
				if ($totalToPay > $walletBal)
					$errors['checkstud'] = "Sorry! Your total bill is <strong>Rs. $totalToPay</strong>.  You have only <strong>Rs. $walletBal</strong> availabel in your wallet! You need more <strong> Rs. " . ($totalToPay - $walletBal) . "</strong> to order the certificates.<br> Please rechrage your wallet. <a href='pay-online'>Recharge Now!</a>";
			}
		}
		if (! empty($errors)) {
			$data['errors']  	= $errors;
			$message  		= isset($errors['checkstud']) ? $errors['checkstud'] : '';
			$data['message']  = $message;
		} else {
			if (is_array($checkstud) && count($checkstud) > 0) {
				$failedArr = array();

				$tableName1 = 'certificate_requests_master';
				$tabFields1 = "(INSTITUTE_ID,TOTAL_EXAM_FEES, CREATED_BY, CREATED_ON, CREATED_ON_IP)";

				$insertSql1 = "INSERT INTO $tableName1 $tabFields1 
										SELECT DISTINCT A.INSTITUTE_ID, SUM((SELECT C.COURSE_FEES FROM courses C WHERE C.COURSE_ID=B.COURSE_ID ))+100,'$created_by',NOW(),'$ip_address'  FROM exam_result A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID WHERE A.EXAM_RESULT_ID IN ($resultidstr) ";
				$exSql1		= parent::execQuery($insertSql1);
				$last_id 	= parent::last_id();
				/*	Deduct money from wallet */
				if ($wallet_id != '') {
					$user_info 	= $this->get_user_info($institute_id, 2);
					$NAME 		= $user_info['NAME'];
					$MOBILE 	= $user_info['MOBILE'];
					$EMAIL 		= $user_info['EMAIL'];

					$tableName3 	= "offline_payments";
					$tabFields3 	= "(PAYMENT_ID, TRANSACTION_NO,TRANSACTION_TYPE,USER_ID,USER_ROLE,USER_FULLNAME,USER_EMAIL,USER_MOBILE,PAYMENT_AMOUNT,PAYMENT_MODE,PAYMENT_DATE,PAYMENT_STATUS,PAYMENT_REMARK,WALLET_ID,ACTIVE,CREATED_BY, CREATED_ON,CREATED_BY_IP)";
					$insertVals3	= "(NULL, get_payment_transaction_id_admin(), 'DEBIT','$institute_id','2', '$NAME','$EMAIL','$MOBILE','$totalToPay','OFFLINE',NOW(), 'success', 'Certificate Ordered','$wallet_id', '1','$created_by',NOW(),'$ip_address')";
					$insertSql3	= parent::insertData($tableName3, $tabFields3, $insertVals3);
					$exSql3		= parent::execQuery($insertSql3);

					$sqlwallet = "UPDATE wallet SET TOTAL_BALANCE= TOTAL_BALANCE - $totalToPay, UPDATED_BY='$created_by', UPDATED_ON=NOW(),UPDATED_ON_IP='$ip_address' WHERE WALLET_ID='$wallet_id'";
					$reswallet = parent::execQuery($sqlwallet);

					//insert payment table
					$tableName2 = 'institute_payments';
					$tabFields2 = "(RECIEPT_NO, INSTITUTE_ID, CERTIFICATE_REQUEST_MASTER_ID, TOTAL_EXAM_FEES,TOTAL_EXAM_FEES_RECIEVED,TOTAL_EXAM_FEES_BALANCE,PAYMENT_DATE, CREATED_BY, CREATED_ON, CREATED_ON_IP)";
					$insertSql2 = "INSERT INTO $tableName2 $tabFields2 
									SELECT generate_admin_reciept_num(), INSTITUTE_ID, CERTIFICATE_REQUEST_MASTER_ID, TOTAL_EXAM_FEES,0,TOTAL_EXAM_FEES, NOW(), '$created_by', NOW(), '$ip_address' FROM certificate_requests_master WHERE  CERTIFICATE_REQUEST_MASTER_ID='$last_id'";
					$exSql2		= parent::execQuery($insertSql2);

					foreach ($checkstud as $examresultid) {
						$tableName = 'certificate_requests';
						$tabFields = "(CERTIFICATE_REQUEST_MASTER_ID,EXAM_RESULT_ID, STUDENT_ID, INSTITUTE_ID,COURSE_ID,EXAM_TITLE, GRADE,MARKS_PER, RESULT_STATUS, EXAM_TYPE,EXAM_FEES, REQUEST_STATUS, REQUEST_RESPONSE_MSG, CREATED_BY, CREATED_ON, CREATED_ON_IP)";

						$insertSql = "INSERT INTO $tableName $tabFields 
										SELECT '$last_id',A.EXAM_RESULT_ID, A.STUDENT_ID, A.INSTITUTE_ID,B.COURSE_ID,A.EXAM_TITLE,A.GRADE,A.MARKS_PER, A.RESULT_STATUS, A.EXAM_TYPE,(SELECT C.COURSE_FEES FROM courses C WHERE C.COURSE_ID=B.COURSE_ID LIMIT 0,1) AS EXAM_FEES,1,'','$created_by',NOW(),'$ip_address'  FROM exam_result A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID WHERE A.EXAM_RESULT_ID = '$examresultid'; ";

						$updSql = " UPDATE exam_result SET APPLY_FOR_CERTIFICATE=1 WHERE EXAM_RESULT_ID='$examresultid';";
						$exSql		= parent::execQuery($insertSql);
						$exSql2		= parent::execQuery($updSql);
						if (!$exSql && parent::rows_affected() <= 0)
							array_push($failedArr, $examresultid);
					}
				}
				if (empty($failedArr)) {
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Students has been aplied for certificates successfully!';
				} else {
					parent::rollback();
					$data['success'] = false;
					$data['message'] = 'Sorry! Application for certificates failed!';
				}
			}
		}
		return json_encode($data);
	}

	//apply for certificate by institute
	public function apply_for_certificate_wihtout_pay()
	{
		$errors 	= array();  // array to hold validation errors
		$data 		= array();
		$data['success'] = false;		// array to pass back data		
		$institute_id 	= isset($_POST['institute_id']) ? $_POST['institute_id'] : '';
		$checkstud 	= isset($_POST['checkstud']) ? $_POST['checkstud'] : '';
		$created_by = $_SESSION['user_fullname'];
		$ip_address = $_SESSION['ip_address'];

		if ($checkstud == '' || empty($checkstud)) $errors['checkstud'] = 'Please select atleast one student!';
		if (! empty($errors)) {
			$data['errors']  	= $errors;
			$message  		= isset($errors['checkstud']) ? $errors['checkstud'] : '';
			$data['message']  = $message;
		} else {
			if (is_array($checkstud) && count($checkstud) > 0) {
				$resultidstr = rtrim(implode(",", $checkstud), ",");
				$failedArr = array();

				$tableName1 = 'certificate_requests_master';
				$tabFields1 = "(INSTITUTE_ID,TOTAL_EXAM_FEES, CREATED_BY, CREATED_ON, CREATED_ON_IP)";

				$insertSql1 = "INSERT INTO $tableName1 $tabFields1 
										SELECT DISTINCT A.INSTITUTE_ID, SUM(B.PLAN_FEES)+100,'$created_by',NOW(),'$ip_address' FROM exam_result A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID WHERE A.EXAM_RESULT_ID IN ($resultidstr) ";
				$exSql1		= parent::execQuery($insertSql1);
				$last_id 	= parent::last_id();

				foreach ($checkstud as $examresultid) {
					$tableName = 'certificate_requests';
					$tabFields = "(CERTIFICATE_REQUEST_MASTER_ID,EXAM_RESULT_ID, STUDENT_ID, INSTITUTE_ID,COURSE_ID,EXAM_TITLE,OBJECTIVE_MARKS,PRACTICAL_MARKS,SUBJECT,GRADE,MARKS_PER, RESULT_STATUS, EXAM_TYPE,EXAM_FEES, REQUEST_STATUS, REQUEST_RESPONSE_MSG, CREATED_BY, CREATED_ON, CREATED_ON_IP)";

					$insertSql = "INSERT INTO $tableName $tabFields 
										SELECT '$last_id',A.EXAM_RESULT_ID, A.STUDENT_ID, A.INSTITUTE_ID,B.COURSE_ID,A.EXAM_TITLE,A.MARKS_OBTAINED,A.PRACTICAL_MARKS,A.SUBJECT,A.GRADE,A.MARKS_PER, A.RESULT_STATUS, A.EXAM_TYPE,B.PLAN_FEES AS EXAM_FEES,1,'','$created_by',NOW(),'$ip_address'  FROM exam_result A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID WHERE A.EXAM_RESULT_ID = '$examresultid'; ";


					$updSql = " UPDATE exam_result SET APPLY_FOR_CERTIFICATE=1 WHERE EXAM_RESULT_ID='$examresultid';";
					$exSql		= parent::execQuery($insertSql);
					$exSql2		= parent::execQuery($updSql);
					if (!$exSql && parent::rows_affected() <= 0)
						array_push($failedArr, $examresultid);
				}

				if (empty($failedArr)) {
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Students has been aplied for certificates successfully!';
				} else {
					parent::rollback();
					$data['success'] = false;
					$data['message'] = 'Sorry! Application for certificates failed!';
				}
			}
		}
		return json_encode($data);
	}
	public function list_marksheet_requests($id = '', $stud_id = '', $inst_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT 
certificate_requests.EXAM_RESULT_ID, 
certificate_requests.STUDENT_ID,certificate_requests.MARKS_PER,certificate_requests.GRADE,get_student_name (certificate_requests.STUDENT_ID)as STUDENT_NAME,
certificate_requests.INSTITUTE_ID, 
exam_result.MARKS_OBTAINED,
marksheet_requests.MARKSHEET_MARKS,marksheet_requests.MARKSHEET_NO,
marksheet_requests.MARKSHEET_SUBJECT,marksheet_requests.MARKSHEET_REQUEST_ID,
student_details.STUDENT_FNAME, student_details.STUDENT_MNAME, student_details.STUDENT_LNAME, DATE_FORMAT(student_details.STUDENT_DOB,'%d-%m-%Y') as dob, student_details.STUDENT_CODE,
institute_details.INSTITUTE_CODE, institute_details.INSTITUTE_NAME,
get_course_title_modify(certificate_requests.COURSE_ID) AS COURSE_NAME,
courses.COURSE_DURATION,student_enquiry.STUDENT_MOTHERNAME
FROM certificate_requests
INNER JOIN exam_result ON certificate_requests.EXAM_RESULT_ID=exam_result.EXAM_RESULT_ID  
INNER JOIN student_details ON certificate_requests.STUDENT_ID=student_details.STUDENT_ID
INNER JOIN institute_details ON certificate_requests.INSTITUTE_ID=institute_details.INSTITUTE_ID
INNER JOIN marksheet_requests ON certificate_requests.CERTIFICATE_REQUEST_ID=marksheet_requests.CERTIFICATE_REQUEST_ID

INNER JOIN courses ON certificate_requests.COURSE_ID= courses.COURSE_ID
INNER JOIN student_enquiry ON certificate_requests.INSTITUTE_ID=student_enquiry.INSTITUTE_ID
			WHERE certificate_requests.DELETE_FLAG=0";
		if ($id != '') {
			$sql .= " AND certificate_requests.CERTIFICATE_REQUEST_ID='$id' ";
		}
		if ($inst_id != '') {
			$sql .= " AND certificate_requests.INSTITUTE_ID='$inst_id' ";
		}
		if ($stud_id != '') {
			$sql .= " AND certificate_requests.STUDENT_ID='$stud_id' ";
		}

		if ($cond != '') {
			$sql .= $cond;
		}
		$sql .= ' ORDER BY certificate_requests.CREATED_ON DESC';

		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//list student exam results

	public function list_certificates_requests($id = '', $stud_id = '', $inst_id = '', $cond = '')
	{
		$data = '';
		/*$sql= "SELECT A.*,get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_student_code(A.STUDENT_ID) AS STUDENT_CODE,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, get_student_name(A.STUDENT_ID) AS STUDENT_NAME,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE,(SELECT B.EXAM_TYPE FROM exam_types_master B WHERE B.EXAM_TYPE_ID=A.EXAM_TYPE) AS EXAM_TYPE_NAME,(SELECT C.REQUEST_STATUS FROM certificate_requests_status_master C WHERE C.REQUEST_STATUS_ID=A.REQUEST_STATUS) AS REQUEST_STATUS_NAME, (SELECT D.COURSE_FEES FROM courses D WHERE D.COURSE_ID=A.COURSE_ID) AS EXAM_FEES,(SELECT UPPER(D.COURSE_DURATION) FROM courses D WHERE D.COURSE_ID=A.COURSE_ID) AS COURSE_DURATION, get_institute_city(A.INSTITUTE_ID) AS INSTITUTE_CITY,get_course_title_modify(A.COURSE_ID) AS AICPE_COURSE_AWARD FROM certificate_requests A WHERE A.DELETE_FLAG=0 ";
*/
		$sql = "SELECT 
                A.*,
                ID.INSTITUTE_ID AS INSTITUTE_CODE,
                SD.STUDENT_CODE,
                SD.STUDENT_DOB,
                get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, 
                  get_student_certificate_name(SD.STUDENT_FNAME,SD.STUDENT_MNAME,SD.STUDENT_LNAME,SD.CERT_MNAME,SD.CERT_LNAME) AS STUDENT_NAME,
                SD.STUDENT_FNAME,
                SD.STUDENT_MNAME,
                SD.STUDENT_LNAME,
                SD.STUDENT_MOTHERNAME,
                ID.INSTITUTE_NAME,
                get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE,
                ETM.EXAM_TYPE AS EXAM_TYPE_NAME,
                CRSM.REQUEST_STATUS AS REQUEST_STATUS_NAME, 
                A.EXAM_FEES AS EXAM_FEES, UPPER(C.COURSE_DURATION) AS COURSE_DURATION,(SELECT F.CITY_NAME as city_name FROM city_master F WHERE A.INSTITUTE_ID=F.CITY_ID) AS INSTITUTE_CITY,
                get_course_title_modify(A.COURSE_ID) AS AICPE_COURSE_AWARD
                FROM certificate_requests A
                INNER JOIN student_details SD ON A.STUDENT_ID = SD.STUDENT_ID
                INNER JOIN institute_details ID ON A.INSTITUTE_ID = ID.INSTITUTE_ID
                INNER JOIN exam_types_master ETM ON A.EXAM_TYPE = ETM.EXAM_TYPE_ID
                INNER JOIN certificate_requests_status_master CRSM ON A.REQUEST_STATUS=CRSM.REQUEST_STATUS_ID
                INNER JOIN courses C ON A.COURSE_ID=C.COURSE_ID
                WHERE A.DELETE_FLAG=0";

		if ($id != '') {
			$sql .= " AND A.CERTIFICATE_REQUEST_ID='$id' ";
		}
		if ($inst_id != '') {
			$sql .= " AND A.INSTITUTE_ID='$inst_id' ";
		}
		if ($stud_id != '') {
			$sql .= " AND A.STUDENT_ID='$stud_id' ";
		}

		if ($cond != '') {
			$sql .= $cond;
		}
		$sql .= ' ORDER BY A.CREATED_ON DESC';
		// echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	public function list_marksheet_requests1($id = '', $stud_id = '', $inst_id = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT A.*,get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_student_code(A.STUDENT_ID) AS STUDENT_CODE,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, get_student_name(A.STUDENT_ID) AS STUDENT_NAME,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE,(SELECT B.EXAM_TYPE FROM exam_types_master B WHERE B.EXAM_TYPE_ID=A.EXAM_TYPE) AS EXAM_TYPE_NAME,(SELECT C.REQUEST_STATUS FROM certificate_requests_status_master C WHERE C.REQUEST_STATUS_ID=A.REQUEST_STATUS) AS REQUEST_STATUS_NAME, (SELECT D.COURSE_FEES FROM courses D WHERE D.COURSE_ID=A.COURSE_ID) AS EXAM_FEES,(SELECT UPPER(D.COURSE_DURATION) FROM courses D WHERE D.COURSE_ID=A.COURSE_ID) AS COURSE_DURATION, (SELECT F.CITY_NAME as city_name FROM city_master F WHERE A.INSTITUTE_ID=F.CITY_ID) AS INSTITUTE_CITY,get_course_title_modify(A.COURSE_ID) AS AICPE_COURSE_AWARD FROM certificate_requests A WHERE A.DELETE_FLAG=0 ";

		/*	$sql= "SELECT A.*,get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_student_code(A.STUDENT_ID) AS STUDENT_CODE,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, get_student_name(A.STUDENT_ID) AS STUDENT_NAME,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE,(SELECT B.EXAM_TYPE FROM exam_types_master B WHERE B.EXAM_TYPE_ID=A.EXAM_TYPE) AS EXAM_TYPE_NAME,(SELECT C.REQUEST_STATUS FROM certificate_requests_status_master C WHERE C.REQUEST_STATUS_ID=A.REQUEST_STATUS) AS REQUEST_STATUS_NAME,  get_institute_city(A.INSTITUTE_ID) AS INSTITUTE_CITY,get_course_title_modify(A.COURSE_ID) AS AICPE_COURSE_AWARD FROM certificate_requests A WHERE A.DELETE_FLAG=0 ";
*/
		if ($id != '') {
			$sql .= " AND A.CERTIFICATE_REQUEST_ID='$id' ";
		}
		if ($inst_id != '') {
			$sql .= " AND A.INSTITUTE_ID='$inst_id' ";
		}
		if ($stud_id != '') {
			$sql .= " AND A.STUDENT_ID='$stud_id' ";
		}

		if ($cond != '') {
			$sql .= $cond;
		}
		$sql .= ' ORDER BY A.CREATED_ON DESC';

		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	/* list question bank by course */
	public function list_quetion_bank_courses($course_id = '', $condition = '')
	{
		$data = '';
		$sql = "SELECT DISTINCT A.COURSE_ID, A.QUEBANK_ID, (SELECT COUNT(*) FROM exam_question_bank B WHERE B.COURSE_ID=A.COURSE_ID AND B.DELETE_FLAG=0) AS TOTAL_QUE , get_course_title_modify(A.COURSE_ID) AS EXAM_NAME,  DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE, (SELECT C.COURSE_NAME FROM courses C WHERE C.COURSE_ID=A.COURSE_ID) AS COURSE_NAME FROM  exam_question_bank A WHERE A.DELETE_FLAG=0 GROUP BY A.COURSE_ID";

		if ($course_id != '') {
			$sql .= " AND A.COURSE_ID='$course_id' ";
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
	public function count_total_question_multi_sub($course_id, $subject_id)
	{
		$total = 0;
		$sql = "SELECT COUNT(*) AS TOTAL_QUE FROM multi_sub_exam_question_bank B WHERE B.MULTI_SUB_COURSE_ID='$course_id' AND B.COURSE_SUBJECT_ID='$subject_id' AND B.DELETE_FLAG=0 ";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$total = $data['TOTAL_QUE'];
		}
		return $total;
	}

	/* list question bank by course */
	public function list_quetion_bank_master_19_05_2018($course_id = '', $condition = '')
	{
		$data = '';
		/*
		$sql= "SELECT A.QUEBANK_ID, A.COURSE_ID AS COURSE_ID,get_course_title_modify(A.COURSE_ID) AS EXAM_NAME, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%s %p') AS CREATED_DATE, (SELECT C.COURSE_NAME FROM courses C WHERE C.COURSE_ID=A.COURSE_ID) AS COURSE_NAME FROM  exam_question_bank_master A LEFT JOIN exam_question_bank C ON A.QUEBANK_ID=C.QUEBANK_ID WHERE A.DELETE_FLAG=0 ";	*/

		$sql = "SELECT A.QUEBANK_ID, A.COURSE_ID AS COURSE_ID,get_course_title_modify(A.COURSE_ID) AS EXAM_NAME, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%s %p') AS CREATED_DATE,D.COURSE_NAME  FROM  exam_question_bank_master A  LEFT JOIN courses D ON D.COURSE_ID=A.COURSE_ID  WHERE A.DELETE_FLAG=0 ";

		if ($course_id != '') {
			$sql .= " AND A.COURSE_ID='$course_id' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= ' GROUP BY A.COURSE_ID ORDER BY A.CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	public function list_quetion_bank_master_multi_sub($course_id = '', $condition = '')
	{
		$data = '';


		$sql = "SELECT DISTINCT A.MULTI_SUB_COURSE_ID,A.QUEBANK_ID, A.COURSE_SUBJECT_ID,get_subject_title_multi_sub(A.COURSE_SUBJECT_ID) AS SUBJECT_NAME_MODIFY, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%s %p') AS CREATED_DATE,D.MULTI_SUB_COURSE_NAME,D.MULTI_SUB_COURSE_CODE  FROM  multi_sub_exam_question_bank A  INNER JOIN multi_sub_courses D ON D.MULTI_SUB_COURSE_ID=A.MULTI_SUB_COURSE_ID  WHERE A.DELETE_FLAG=0 ";

		if ($course_id != '') {
			$sql .= " AND A.MULTI_SUB_COURSE_ID='$course_id' ";
		}
		if ($condition != '') {
			$sql .= " $condition ";
		}
		$sql .= ' GROUP BY A.COURSE_SUBJECT_ID ORDER BY A.CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	//filter exams
	public function list_practical_exams($studid = '', $instid, $courseid = '', $examtype = '', $examstatus = '', $cond = '')
	{
		$data = '';
		$sql = "SELECT A.*,B.STUDENT_MOBILE,B.STUDENT_EMAIL,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, get_student_name (A.STUDENT_ID) AS  STUDENT_NAME, (SELECT C.EXAM_STATUS FROM exam_status_master C WHERE C.EXAM_STATUS_ID=A.EXAM_STATUS) AS EXAM_STATUS_NAME, (SELECT D.EXAM_TYPE FROM exam_types_master D WHERE D.EXAM_TYPE_ID=A.EXAM_TYPE) AS EXAM_TYPE_NAME, student_calculate_balance_fees2 (A.STUD_COURSE_DETAIL_ID,0) AS BALANCE_FEES, C.COURSE_SUBJECTS FROM student_course_details A LEFT JOIN student_details B ON A.STUDENT_ID=B.STUDENT_ID LEFT JOIN institute_courses C ON A.INSTITUTE_COURSE_ID=C.INSTITUTE_COURSE_ID WHERE A.DELETE_FLAG=0 AND C.COURSE_TYPE=1 AND B.DELETE_FLAG=0  ";
		if ($studid != '') {
			$sql .= " AND A.STUDENT_ID='$studid' ";
		}
		if ($instid != '') {
			$sql .= " AND A.INSTITUTE_ID='$instid' ";
		}
		if ($courseid != '') {
			$sql .= " AND A.INSTITUTE_COURSE_ID='$courseid' ";
		}
		if ($examtype != '') {
			$sql .= " AND A.EXAM_TYPE='$examtype' ";
		}
		if ($examstatus != '') {
			$sql .= " AND A.EXAM_STATUS='$examstatus' ";
		}
		if ($cond != '') {
			$sql .= $cond;
		}

		$sql .= 'ORDER BY A.CREATED_ON DESC';
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}
	public function add_practical_exam_marks_multi_sub()
	{
		$errors 			= array();  // array to hold validation errors
		$data 				= array();
		$data['success'] 	= false;		// array to pass back data


		$totalSubjectCount = parent::test(isset($_POST['totalSubjectCount']) ? $_POST['totalSubjectCount'] : '');

		if (! empty($errors)) {
			$data['errors']  	= $errors;
			$data['message']  = "Please correct all the errors!";
		} else {
			parent::start_transaction();
			if ($totalSubjectCount != '') {
				for ($i = 1; $i <= $totalSubjectCount; $i++) {

					$offline_paper_id 	= parent::test(isset($_POST['offline_paper_id' . $i]) ? $_POST['offline_paper_id' . $i] : '');

					$multisub_studcoursedetailid = parent::test(isset($_POST['multisub_studcoursedetailid' . $i]) ? $_POST['multisub_studcoursedetailid' . $i] : '');

					$multisub_studentid 		= parent::test(isset($_POST['multisub_studentid' . $i]) ? $_POST['multisub_studentid' . $i] : '');

					$multisub_instituteid 		= parent::test(isset($_POST['multisub_instituteid' . $i]) ? $_POST['multisub_instituteid' . $i] : '');

					$multisub_examid 			= parent::test(isset($_POST['multisub_examid' . $i]) ? $_POST['multisub_examid' . $i] : '');

					$multisub_coursesubid 			= parent::test(isset($_POST['multisub_coursesubid' . $i]) ? $_POST['multisub_coursesubid' . $i] : '');

					$multisub_instcourseid 	= parent::test(isset($_POST['multisub_instcourseid' . $i]) ? $_POST['multisub_instcourseid' . $i] : '');

					$multisub_examsecretecode 	= parent::test(isset($_POST['multisub_examsecretecode' . $i]) ? $_POST['multisub_examsecretecode' . $i] : '');

					$multisub_examtitle 		= parent::test(isset($_POST['multisub_examtitle' . $i]) ? $_POST['multisub_examtitle' . $i] : '');

					$multisub_examattempt 		= parent::test(isset($_POST['multisub_examattempt' . $i]) ? $_POST['multisub_examattempt' . $i] : '');

					$multisub_totalque 	= parent::test(isset($_POST['multisub_totalque' . $i]) ? $_POST['multisub_totalque' . $i] : '');
					$multisub_totalmarks 	= parent::test(isset($_POST['multisub_totalmarks' . $i]) ? $_POST['multisub_totalmarks' . $i] : '');

					$multisub_passingmark = parent::test(isset($_POST['multisub_passingmark' . $i]) ? $_POST['multisub_passingmark' . $i] : '');

					$multisub_markperque = parent::test(isset($_POST['multisub_markperque' . $i]) ? $_POST['multisub_markperque' . $i] : '');

					$multisub_exametime 			= parent::test(isset($_POST['multisub_exametime' . $i]) ? $_POST['multisub_exametime' . $i] : '');
					$multisub_examtype 			= parent::test(isset($_POST['multisub_examtype' . $i]) ? $_POST['multisub_examtype' . $i] : '');
					$multisub_examstatus		= parent::test(isset($_POST['multisub_examstatus' . $i]) ? $_POST['multisub_examstatus' . $i] : '');

					$totaltheory    = parent::test(isset($_POST['totaltheory' . $i]) ? $_POST['totaltheory' . $i] : '');
					$thobt 			= parent::test(isset($_POST['thobt' . $i]) ? $_POST['thobt' . $i] : '');
					$totalpract 	= parent::test(isset($_POST['totalpract' . $i]) ? $_POST['totalpract' . $i] : '');
					$probt 			= parent::test(isset($_POST['probt' . $i]) ? $_POST['probt' . $i] : '');
					$tot_obt 		= parent::test(isset($_POST['tot_obt' . $i]) ? $_POST['tot_obt' . $i] : '');
					$tot_marks 		= parent::test(isset($_POST['tot_marks' . $i]) ? $_POST['tot_marks' . $i] : '');
					$created_by = $_SESSION['user_fullname'];
					$created_on_ip = $_SESSION['ip_address'];

					$tableName 	= "multi_sub_exam_result";

					$tabFields 	= "(EXAM_RESULT_ID, STUD_COURSE_ID,STUDENT_ID,STUDENT_SUBJECT_ID,INSTITUTE_ID,EXAM_ID, 
						INSTITUTE_COURSE_ID, EXAM_TITLE,EXAM_ATTEMPT, EXAM_SECRETE_CODE, EXAM_TOTAL_QUE, EXAM_TOTAL_MARKS,EXAM_MARKS_PER_QUE, EXAM_PASSING_MARKS, MARKS_OBTAINED,PRACTICAL_MARKS, EXAM_TYPE, CREATED_BY, CREATED_ON, CREATED_ON_IP)";

					$insertVals	= "(NULL,'$multisub_studcoursedetailid','$multisub_studentid','$multisub_coursesubid','$multisub_instituteid','$multisub_examid','$multisub_instcourseid','$multisub_examtitle','$multisub_examattempt','$multisub_examsecretecode', '$multisub_totalque', '$multisub_totalmarks','$multisub_markperque','$multisub_passingmark','$thobt','$probt','$multisub_examtype','$created_by',NOW(),'$created_on_ip')";

					$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
					$exSql		= parent::execQuery($insertSql);
				}
				if ($exSql) {
					//$last_id = parent::last_id();					
					$sqlUpd 	= "UPDATE student_course_details SET EXAM_STATUS='3' WHERE STUD_COURSE_DETAIL_ID='$multisub_studcoursedetailid'";
					parent::execQuery($sqlUpd);

					$total_obt_marks 		= parent::test(isset($_POST['total_obt_marks']) ? $_POST['total_obt_marks'] : '');
					$total_marks 		= parent::test(isset($_POST['total_marks']) ? $_POST['total_marks'] : '');
					$percentage 		= parent::test(isset($_POST['percentage']) ? $_POST['percentage'] : '');
					$grade_multi 		= parent::test(isset($_POST['grade_multi']) ? $_POST['grade_multi'] : '');
					$result_status_multi 		= parent::test(isset($_POST['result_status_multi']) ? $_POST['result_status_multi'] : '');

					$tableName1 	= "multi_sub_exam_result_final";

					$tabFields1 	= "(EXAM_RESULT_FINAL_ID, STUD_COURSE_ID,STUDENT_ID,INSTITUTE_ID,INSTITUTE_COURSE_ID,EXAM_TITLE,EXAM_TOTAL_MARKS,MARKS_OBTAINED,MARKS_PER,GRADE, 
						RESULT_STATUS,CREATED_BY, CREATED_ON,CREATED_ON_IP)";

					$insertVals1	= "(NULL,'$multisub_studcoursedetailid','$multisub_studentid','$multisub_instituteid','$multisub_instcourseid','$multisub_examtitle','$total_marks','$total_obt_marks ','$percentage','$grade_multi','$result_status_multi','$created_by',NOW(),'$created_on_ip')";

					$insertSql1	= parent::insertData($tableName1, $tabFields1, $insertVals1);
					parent::execQuery($insertSql1);

					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Practical exam result has been added successfully!';
				} else {
					parent::rollback();
					$errors['message'] = 'Sorry! Something went wrong! Could not add the practical exam result.';
					$data['success'] = false;
					$data['errors']  = $errors;
				}
			}
		}
		return json_encode($data);
	}

	public function update_practical_exam_marks_multi_sub()
	{
		$errors 			= array();  // array to hold validation errors
		$data 				= array();

		$totalSubjectCount = parent::test(isset($_POST['totalSubjectCount']) ? $_POST['totalSubjectCount'] : '');

		if (! empty($errors)) {
			$data['errors']  	= $errors;
			$data['message']  = "Please correct all the errors!";
		} else {
			parent::start_transaction();
			if ($totalSubjectCount != '') {
				for ($i = 1; $i <= $totalSubjectCount; $i++) {
					$multisub_examid 	= parent::test(isset($_POST['multisub_examid' . $i]) ? $_POST['multisub_examid' . $i] : '');

					$totaltheory    = parent::test(isset($_POST['totaltheory' . $i]) ? $_POST['totaltheory' . $i] : '');
					$thobt 			= parent::test(isset($_POST['thobt' . $i]) ? $_POST['thobt' . $i] : '');
					$totalpract 	= parent::test(isset($_POST['totalpract' . $i]) ? $_POST['totalpract' . $i] : '');
					$probt 			= parent::test(isset($_POST['probt' . $i]) ? $_POST['probt' . $i] : '');
					$tot_obt 		= parent::test(isset($_POST['tot_obt' . $i]) ? $_POST['tot_obt' . $i] : '');
					$tot_marks 		= parent::test(isset($_POST['tot_marks' . $i]) ? $_POST['tot_marks' . $i] : '');
					$created_by = $_SESSION['user_fullname'];
					$created_on_ip = $_SESSION['ip_address'];

					$tableName		= "multi_sub_exam_result";
					$setValues 		= "EXAM_TOTAL_MARKS='$totaltheory', MARKS_OBTAINED='$thobt',
					PRACTICAL_MARKS='$probt',UPDATED_BY='$created_by', UPDATED_ON=NOW(),UPDATED_ON_IP='$created_on_ip'";

					$whereClause 	= " WHERE EXAM_RESULT_ID='$multisub_examid' ";
					$exc 			= parent::updateData($tableName, $setValues, $whereClause);
					$res			= parent::execQuery($exc);
				}
				if ($res) {
					//$last_id = parent::last_id();					
					$sqlUpd 	= "UPDATE student_course_details SET EXAM_STATUS='3' WHERE STUD_COURSE_DETAIL_ID='$multisub_studcoursedetailid'";
					parent::execQuery($sqlUpd);

					$multisub_examresultfinalid 		= parent::test(isset($_POST['multisub_examresultfinalid']) ? $_POST['multisub_examresultfinalid'] : '');
					$total_obt_marks 		= parent::test(isset($_POST['total_obt_marks']) ? $_POST['total_obt_marks'] : '');
					$total_marks 		= parent::test(isset($_POST['total_marks']) ? $_POST['total_marks'] : '');
					$percentage 		= parent::test(isset($_POST['percentage']) ? $_POST['percentage'] : '');
					$grade_multi 		= parent::test(isset($_POST['grade_multi']) ? $_POST['grade_multi'] : '');
					$result_status_multi 		= parent::test(isset($_POST['result_status_multi']) ? $_POST['result_status_multi'] : '');

					$tableName1		= "multi_sub_exam_result_final";
					$setValues1 		= "EXAM_TOTAL_MARKS	='$total_marks', MARKS_OBTAINED='$total_obt_marks',MARKS_PER='$percentage',GRADE='$grade_multi',RESULT_STATUS='$result_status_multi',UPDATED_BY='$created_by', UPDATED_ON=NOW(),UPDATED_ON_IP='$created_on_ip'";

					$whereClause1 	= " WHERE EXAM_RESULT_FINAL_ID='$multisub_examresultfinalid' ";
					$exc1 			= parent::updateData($tableName1, $setValues1, $whereClause1);
					parent::execQuery($exc1);

					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Practical exam result has been added successfully!';
				} else {
					parent::rollback();
					$data['success'] = false;
					$data['message'] = 'Sorry! Something went wrong! Could not update the exam result.';
				}
			}
		}
		return json_encode($data);
	}
	public function check_practical_result_added($stud_course_id)
	{
		$output = '';
		$sql = "SELECT EXAM_RESULT_ID FROM exam_result WHERE STUD_COURSE_ID='$stud_course_id' AND EXAM_TYPE=3 LIMIT 0,1";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$output = $data['EXAM_RESULT_ID'];
		}
		return $output;
	}
	public function student_reports($studid = '', $instid = '', $courseid = '', $cond = '')
	{
		$data = '';
		//$sql= "SELECT A.*,C.COURSE_TYPE,get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME, B.STUDENT_MOBILE,B.STUDENT_EMAIL,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, get_student_name (A.STUDENT_ID) AS  STUDENT_NAME, (SELECT E.EXAM_STATUS FROM exam_status_master E WHERE E.EXAM_STATUS_ID=A.EXAM_STATUS) AS EXAM_STATUS_NAME, (SELECT D.EXAM_TYPE FROM exam_types_master D WHERE D.EXAM_TYPE_ID=A.EXAM_TYPE) AS EXAM_TYPE_NAME, student_calculate_balance_fees2 (A.STUD_COURSE_DETAIL_ID,0) AS BALANCE_FEES FROM student_course_details A LEFT JOIN student_details B ON A.STUDENT_ID=B.STUDENT_ID LEFT JOIN institute_courses C ON A.INSTITUTE_COURSE_ID=C.INSTITUTE_COURSE_ID WHERE A.DELETE_FLAG=0 AND B.DELETE_FLAG=0  ";
		$sql = "SELECT A.*,C.COURSE_TYPE,get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME, B.STUDENT_MOBILE,B.STUDENT_EMAIL,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, get_student_name (A.STUDENT_ID) AS STUDENT_NAME, (SELECT E.EXAM_STATUS FROM exam_status_master E WHERE E.EXAM_STATUS_ID=A.EXAM_STATUS) AS EXAM_STATUS_NAME, (SELECT D.EXAM_TYPE FROM exam_types_master D WHERE D.EXAM_TYPE_ID=A.EXAM_TYPE) AS EXAM_TYPE_NAME, student_calculate_balance_fees2 (A.STUD_COURSE_DETAIL_ID,0) AS BALANCE_FEES, D.USER_NAME, D.USER_LOGIN_ID FROM student_course_details A LEFT JOIN student_details B ON A.STUDENT_ID=B.STUDENT_ID LEFT JOIN institute_courses C ON A.INSTITUTE_COURSE_ID=C.INSTITUTE_COURSE_ID LEFT JOIN user_login_master D ON A.INSTITUTE_ID=D.USER_ID AND D.USER_ROLE=2 WHERE A.DELETE_FLAG=0 AND B.DELETE_FLAG=0";

		if ($studid != '') {
			$sql .= " AND A.STUDENT_ID=$studid ";
		}
		if ($instid != '') {
			$sql .= " AND A.INSTITUTE_ID=$instid ";
		}
		if ($courseid != '') {
			$sql .= " AND A.INSTITUTE_COURSE_ID=$courseid ";
		}

		if ($cond != '') {
			$sql .= $cond;
		}

		//	$sql .= " ORDER BY A.CREATED_ON DESC";
		//echo $sql;
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
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
		//echo $sql;
		$res = parent::execQuery($sql);

		$result = $res->fetch_assoc();
		$data = $result['TOTAL'];

		return $data;
	}
	public function reset_student_exam($stud_course_id)
	{
		parent::start_transaction();
		$tableName 	= "student_course_details";
		$setValues 	= "EXAM_STATUS='1',EXAM_TYPE=NULL,EXAM_ATTEMPT=EXAM_ATTEMPT+1,EXAM_SECRETE_CODE=NULL,EXAM_SECRETE_CODE_DATE=NULL,DEMO_COUNT=10, UPDATED_BY='" . $_SESSION['user_name'] . "', UPDATED_ON=NOW(),UPDATED_ON_IP='" . $_SESSION['ip_address'] . "'";
		$whereClause	= " WHERE STUD_COURSE_DETAIL_ID='$stud_course_id'";
		$updateSql		= parent::updateData($tableName, $setValues, $whereClause);
		$exSql			= parent::execQuery($updateSql);
		if ($exSql && parent::rows_affected() > 0) {
			$tableName2 	= "exam_offline_papers";
			$setValues2 	= "DELETE_FLAG='1', UPDATED_BY='" . $_SESSION['user_name'] . "', UPDATED_ON=NOW(),UPDATED_ON_IP='" . $_SESSION['ip_address'] . "'";
			$whereClause2	= " WHERE STUD_COURSE_ID='$stud_course_id'";
			$updateSql2		= parent::updateData($tableName2, $setValues2, $whereClause2);
			$exSql2			= parent::execQuery($updateSql2);

			$tableName2 	= "exam_result";
			$setValues2 	= "DELETE_FLAG='1',APPLY_FOR_CERTIFICATE=0, UPDATED_BY='" . $_SESSION['user_name'] . "', UPDATED_ON=NOW(),UPDATED_ON_IP='" . $_SESSION['ip_address'] . "'";
			$whereClause2	= " WHERE STUD_COURSE_ID='$stud_course_id'";
			$updateSql2		= parent::updateData($tableName2, $setValues2, $whereClause2);
			$exSql2			= parent::execQuery($updateSql2);
			if ($exSql2) {
				parent::commit();
				return true;
			} else parent::rollback();
		} else parent::rollback();
		return false;
	}
	public function check_certificate_applied($stud_course_id)
	{
		$sql = "SELECT COUNT(*) as TOTAL FROM exam_result WHERE STUD_COURSE_ID='$stud_course_id' AND DELETE_FLAG=0 AND APPLY_FOR_CERTIFICATE=1";
		$res = parent::execQuery($sql);
		if ($res) {
			$data = $res->fetch_assoc();
			$count = isset($data['TOTAL']) ? $data['TOTAL'] : 0;
			if ($count > 0)
				return true;
		}
		return false;
	}
	public function getInstTotalCourseFee($instCourseId = '')
	{
		$value = 0;
		$sql = "SELECT SUM(COURSE_FEES) AS TOTAL_COURSE_FEE FROM student_course_details WHERE DELETE_FLAG=0";
		if ($instCourseId != '')
			$sql .= " AND INSTITUTE_COURSE_ID='$instCourseId'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$value = $data['TOTAL_COURSE_FEE'];
		}
		return $value;
	}
	public function getInstTotalCourseBalanceFee($instCourseId = '')
	{
		$value = 0;
		$sql = "SELECT SUM(student_calculate_balance_fees2 (A.STUD_COURSE_DETAIL_ID,0)) AS BALANCE_FEES  FROM student_course_details WHERE DELETE_FLAG=0";
		if ($instCourseId != '')
			$sql .= " AND INSTITUTE_COURSE_ID='$instCourseId'";
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res->fetch_assoc();
			$value = $data['BALANCE_FEES'];
		}
		return $value;
	}
	public function getInstTotalFees($courseType = '')
	{
		$value = array();
		$sql = "SELECT ";
		if ($courseType == 1) {
			$sql .= "(SELECT SUM(C.COURSE_FEES) FROM courses C LEFT JOIN institute_courses D ON C.COURSE_ID=D.COURSE_ID AND D.COURSE_TYPE=1 ) AS AICPE_FEES,";
		}
		$sql .= "SUM(A.COURSE_FEES) AS INST_COURSE_FEES, SUM( student_calculate_balance_fees2(A.STUD_COURSE_DETAIL_ID,0)) AS BALANCE_FEES FROM student_course_details A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID WHERE A.DELETE_FLAG=0 ";
		if ($courseType != '')
			$sql .= " AND B.COURSE_TYPE='$courseType'";

		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$value['AICPE_FEES'] = isset($data['AICPE_FEES']) ? $data['AICPE_FEES'] : '';
				$value['INST_COURSE_FEES'] = $data['INST_COURSE_FEES'];
				$value['BALANCE_FEES'] = $data['BALANCE_FEES'];
			}
		}
		return $value;
	}
	//apply for certificate by institute
	public function add_student_admission_paid()
	{
		$errors 	= array();  // array to hold validation errors
		$data 		= array();
		$data['success'] = false;		// array to pass back data		
		$institute_id = isset($_POST['institute_id']) ? $_POST['institute_id'] : '';
		$checkstud 	= isset($_POST['checkstud']) ? $_POST['checkstud'] : '';
		$created_by = $_SESSION['user_fullname'];
		$ip_address = $_SESSION['ip_address'];

		if ($checkstud == '' || empty($checkstud)) $errors['checkstud'] = 'Please select atleast one student!';

		//check if wallet has sufficient balance
		$walletBal = 0;
		$totalToPay = 0;
		$wallet_id = '';
		$res = parent::get_wallet('', $institute_id, 2);
		if ($res != '') {
			$data1 = $res->fetch_assoc();
			$walletBal = $data1['TOTAL_BALANCE'];
			$wallet_id = $data1['WALLET_ID'];
		} else {
			$errors['checkstud'] = "Sorry! Your wallet is empty!  Please rechrage your wallet and Tray again! <a href='pay-online' class='btn btn-sm bg-teal'>Click to Recharge Now!</a>";
		}
		if (is_array($checkstud) && count($checkstud) > 0) {
			$resultidstr = rtrim(implode(",", $checkstud), ",");
			/* get total fees */
			/*	$sql2 = "SELECT DISTINCT A.INSTITUTE_ID,SUM((SELECT C.COURSE_FEES FROM courses C WHERE C.COURSE_ID=B.COURSE_ID))+100 AS TOTAL_FEES FROM student_course_details A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID WHERE A.STUD_COURSE_DETAIL_ID IN ($resultidstr)";*/

			$sql2 = "SELECT DISTINCT A.INSTITUTE_ID,SUM(B.PLAN_FEES)+100 AS TOTAL_FEES FROM student_course_details A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID WHERE A.STUD_COURSE_DETAIL_ID IN ($resultidstr)";

			$res2 = parent::execQuery($sql2);
			if ($res2 && $res2->num_rows > 0) {
				$data2 = $res2->fetch_assoc();
				$totalToPay = $data2['TOTAL_FEES'];
				if ($totalToPay > $walletBal)
					$errors['checkstud'] = "Sorry! Your total bill is <strong>Rs. $totalToPay</strong>.  You have only <strong>Rs. $walletBal</strong> availabel in your wallet! You need more <strong> Rs. " . ($totalToPay - $walletBal) . "</strong> to order the certificates.<br> Please rechrage your wallet. <a href='pay-online'>Recharge Now!</a>";
			}
		}
		if (! empty($errors)) {
			$data['errors']  	= $errors;
			$message  		= isset($errors['checkstud']) ? $errors['checkstud'] : '';
			$data['message']  = $message;
		} else {
			if (is_array($checkstud) && count($checkstud) > 0) {
				$failedArr = array();

				$sql = "UPDATE student_course_details SET ADMISSION_CONFIRMED='1' WHERE STUD_COURSE_DETAIL_ID IN ($resultidstr)";
				$exSql1		= parent::execQuery($sql);


				/*	Deduct money from wallet */
				if ($wallet_id != '') {
					$user_info 	= $this->get_user_info($institute_id, 2);
					$NAME 		= $user_info['NAME'];
					$MOBILE 	= $user_info['MOBILE'];
					$EMAIL 		= $user_info['EMAIL'];

					$tableName3 	= "offline_payments";
					$tabFields3 	= "(PAYMENT_ID, TRANSACTION_NO,TRANSACTION_TYPE,USER_ID,USER_ROLE,USER_FULLNAME,USER_EMAIL,USER_MOBILE,PAYMENT_AMOUNT,PAYMENT_MODE,PAYMENT_DATE,PAYMENT_STATUS,PAYMENT_REMARK,WALLET_ID,ACTIVE,CREATED_BY, CREATED_ON,CREATED_BY_IP)";
					$insertVals3	= "(NULL, get_payment_transaction_id_admin(), 'DEBIT','$institute_id','2', '$NAME','$EMAIL','$MOBILE','$totalToPay','OFFLINE',NOW(), 'success', 'admission_confirmed','$wallet_id', '1','$created_by',NOW(),'$ip_address')";
					$insertSql3	= parent::insertData($tableName3, $tabFields3, $insertVals3);
					$exSql3		= parent::execQuery($insertSql3);

					$sqlwallet = "UPDATE wallet SET TOTAL_BALANCE= TOTAL_BALANCE - $totalToPay, UPDATED_BY='$created_by', UPDATED_ON=NOW(),UPDATED_ON_IP='$ip_address' WHERE WALLET_ID='$wallet_id'";
					$reswallet = parent::execQuery($sqlwallet);

					//insert payment table
					$tableName2 = 'institute_payments';
					$tabFields2 = "(RECIEPT_NO, INSTITUTE_ID, TOTAL_EXAM_FEES,TOTAL_EXAM_FEES_RECIEVED,TOTAL_EXAM_FEES_BALANCE,PAYMENT_DATE,PAYMENT_CATEGORY, CREATED_BY, CREATED_ON, CREATED_ON_IP)";
					$insertVals2 = "(generate_admin_reciept_num(),'$institute_id','$totalToPay','$totalToPay',0,NOW(),'admission_confirmed','$created_by',NOW(),'$ip_address')";
					$insertSql2 = parent::insertData($tableName2, $tabFields2, $insertVals2);
					$exSql2		= parent::execQuery($insertSql2);
				}
				if (empty($failedArr)) {
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Students admission has been confirmed successfully!';
				} else {
					parent::rollback();
					$data['success'] = false;
					$data['message'] = 'Sorry! Application for stduent admission failed!';
				}
			}
		}
		return json_encode($data);
	}
	//list of hall tickets for only applied student
	public function list_hallticket($studid = '', $instid, $courseid = '')
	{
		$data = '';
		$sql = "SELECT A.*,B.ABBREVIATION,B.STUDENT_FNAME,B.STUDENT_MNAME,B.STUDENT_LNAME,B.STUDENT_MOTHERNAME,B.STUDENT_MOBILE,B.STUDENT_EMAIL,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO,C.INSTITUTE_CODE,C.INSTITUTE_NAME,C.MOBILE,C.ADDRESS_LINE1,(SELECT F.CITY_NAME as city_name FROM city_master F WHERE C.INSTITUTE_ID=F.CITY_ID) as CITY,get_institute_state(C.INSTITUTE_ID) as STATE,C.COUNTRY,C.TALUKA,C.POSTCODE, D.COURSE_ID FROM student_course_details A LEFT JOIN student_details B ON A.STUDENT_ID=B.STUDENT_ID LEFT JOIN institute_details C ON A.INSTITUTE_ID=C.INSTITUTE_ID LEFT JOIN institute_courses D ON A.INSTITUTE_COURSE_ID = D.INSTITUTE_COURSE_ID WHERE 1";
		if ($studid != '') {
			$sql .= " AND A.STUDENT_ID='$studid' ";
		}
		if ($instid != '') {
			$sql .= " AND A.INSTITUTE_ID='$instid' ";
		}
		if ($courseid != '') {
			$sql .= " AND A.INSTITUTE_COURSE_ID='$courseid' ";
		}
		echo $sql .= 'ORDER BY A.CREATED_ON DESC';
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0)
			$data = $res;
		return $data;
	}

	//Add Hall Ticket 
	public function add_hallticket()
	{
		$errors 	= array();  // array to hold validation errors
		$data 		= array();
		$data['success'] = false;		// array to pass back data
		$data['invalid'] = array();		// array to pass back data

		$checkstud 	= isset($_POST['checkstud']) ? $_POST['checkstud'] : '';

		$examdate 		= isset($_POST['examdate']) ? $_POST['examdate'] : '';
		$examstarttime 	= isset($_POST['examstarttime']) ? $_POST['examstarttime'] : '';
		$examendtime 	= isset($_POST['examendtime']) ? $_POST['examendtime'] : '';

		if ($checkstud == '' || empty($checkstud)) $errors['checkstud'] = 'Please select atleast one student!';
		if (! empty($errors)) {
			$data['errors']  	= $errors;
			if ($message != '') $message .= '<br>';
			$message  		.= isset($errors['checkstud']) ? $errors['checkstud'] : '';
			$data['message']  = $message;
		} else {

			if (is_array($checkstud) && count($checkstud) > 0) {
				foreach ($checkstud as $coursedetailid) {
					$checksql = "SELECT INSTITUTE_COURSE_ID,STUDENT_ID,INSTITUTE_ID,get_student_name(STUDENT_ID) AS STUDENT_NAME FROM student_course_details WHERE STUD_COURSE_DETAIL_ID='$coursedetailid'";
					$checkres = parent::execQuery($checksql);


					if ($checkres && $checkres->num_rows > 0) {
						$checkdata 				= $checkres->fetch_assoc();
						$INSTITUTE_COURSE_ID 	= $checkdata['INSTITUTE_COURSE_ID'];
						$INSTITUTE_ID 			= $checkdata['INSTITUTE_ID'];
						$STUDENT_ID 			= $checkdata['STUDENT_ID'];
						$STUDENT_NAME 			= $checkdata['STUDENT_NAME'];

						$tableName 	= "student_hallticket_details";
						$tabFields 	= "(HALLTICKET_ID, STUDENT_ID, COURSE_ID, EXAMDATE,EXAMSTARTTIME,EXAMENDTIME,ACTIVE,CREATED_ON)";
						$insertVals	= "(NULL,'$STUDENT_ID','$INSTITUTE_COURSE_ID','$examdate','$examstarttime','$examendtime','1',NOW())";
						$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);
						$exSql		= parent::execQuery($insertSql);

						if ($exSql && parent::rows_affected() > 0) {
							parent::commit();
							$data['success'] = true;
							$data['message'] = 'Success! Students Hall Ticket has been generated successfully!';
						}
					}
				}
			}
		}

		return json_encode($data);
	}

	/*.....................Add marksheet...........................................*/
	public function update_marksheet()
	{
		$errors 	= array();  // array to hold validation errors
		$data 		= array();
		$data['success'] = false;		// array to pass back data
		$data['invalid'] = array();		// array to pass back data
		//print_r($data);
		//$checkstud 	= isset($_POST['checkstud'])?$_POST['checkstud']:'';
		$id 			= parent::test(isset($_POST['marksheet_request_id']) ? $_POST['marksheet_request_id'] : '');
		$subject 		= isset($_POST['subject']) ? $_POST['subject'] : '';
		$marks 			= isset($_POST['marks']) ? $_POST['marks'] : '';
		$certificate_requests_id 	= isset($_POST['certificate_requests_id']) ? $_POST['certificate_requests_id'] : '';

		//if($checkstud=='' || empty($checkstud)) $errors['checkstud'] = 'Please select atleast one student!';

		//	 echo hiiiiii;
		if (! empty($errors)) {
			$data['errors']  	= $errors;
			if ($message != '') $message .= '<br>';
			$message  		.= isset($errors['checkstud']) ? $errors['checkstud'] : '';
			$data['message']  = $message;
		} else {



			$tableName 	= "marksheet_requests";
			$setValues 	= "CERTIFICATE_REQUEST_ID='$certificate_requests_id',MARKSHEET_SUBJECT='$subject',MARKSHEET_MARKS='$marks',ACTIVE='1',CREATED_ON='NOW()'";

			$whereClause = " WHERE MARKSHEET_REQUEST_ID='$id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);


			if ($exSql)
				parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! Students Marksheet has been updated successfully!';
		}



		return json_encode($data);
	}
	/*.....................update-marksheet...........................................*/
	public function add_marksheet()
	{
		$errors 	= array();  // array to hold validation errors
		$data 		= array();
		$data['success'] = false;		// array to pass back data
		$data['invalid'] = array();		// array to pass back data
		//print_r($data);
		$checkstud 	= isset($_POST['checkstud']) ? $_POST['checkstud'] : '';

		$institute_id 	= isset($_POST['institute_id']) ? $_POST['institute_id'] : '';
		$subject 		= isset($_POST['subject']) ? $_POST['subject'] : '';
		$marks 			= isset($_POST['marks']) ? $_POST['marks'] : '';
		$marksobj 			= isset($_POST['marksobj']) ? $_POST['marksobj'] : '';
		$exam_id 	= isset($_POST['exam_id']) ? $_POST['exam_id'] : '';

		$MARKS_PER = $marks + $marksobj;


		if ($MARKS_PER >= 85) {
			$GRADE = "A+";
			$RESULT_STATUS = "Passed";
		} else if ($MARKS_PER	>= 70 && $MARKS_PER < 85) {
			$GRADE = "A";
			$RESULT_STATUS = "Passed";
		} else if ($MARKS_PER >= 55 && $MARKS_PER < 70) {
			$GRADE = "B";
			$RESULT_STATUS = "Passed";
		} else if ($MARKS_PER >= 40 && $MARKS_PER < 55) {
			$GRADE = "C";
			$RESULT_STATUS = "Passed";
		} else {
			$GRADE = "";
			$RESULT_STATUS = "Failed";
		}


		if ($exam_id != '') {
			$tableName 	= "exam_result";
			$setValues 	= "SUBJECT='$subject',PRACTICAL_MARKS='$marks',MARKS_OBTAINED='$marksobj',MARKS_PER='$MARKS_PER',GRADE='$GRADE',RESULT_STATUS='$RESULT_STATUS', UPDATED_ON=NOW()";
			$whereClause = " WHERE EXAM_RESULT_ID='$exam_id'";
			$updateSql	= parent::updateData($tableName, $setValues, $whereClause);
			$exSql		= parent::execQuery($updateSql);
		}
		if ($exSql && parent::rows_affected() > 0) {
			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! Students Marksheet has been saved successfully!';
		}
		return json_encode($data);
	}
	public function apply_for_marksheet()
	{
		$errors 	= array();  // array to hold validation errors
		$data 		= array();
		$data['success'] = false;

		//	print_r($_POST);exit();	// array to pass back data		
		$institute_id 	= isset($_POST['institute_id']) ? $_POST['institute_id'] : '';
		$checkstud 	= isset($_POST['checkstud']) ? $_POST['checkstud'] : '';
		$created_by = $_SESSION['user_fullname'];
		$ip_address = $_SESSION['ip_address'];
		//	$certificate_requests_id 	= isset($_POST['certificate_requests_id'])?$_POST['certificate_requests_id']:'';

		if ($checkstud == '' || empty($checkstud)) $errors['checkstud'] = 'Please select atleast one student!';
		if (! empty($errors)) {
			$data['errors']  	= $errors;
			if ($message != '') $message .= '<br>';
			$message  		.= isset($errors['checkstud']) ? $errors['checkstud'] : '';
			$data['message']  = $message;
		} else {
			//check if wordwrap(str)allet has sufficient balance

			//echo $certificate_requests_id;


			foreach ($checkstud as $value) {
				$sql = "SELECT COUNT(*) AS total FROM marksheet_requests WHERE CERTIFICATE_REQUEST_ID='$value'";
				$res = parent::execQuery($sql);
				$data1 = $res->fetch_assoc();
				$count = $data1['total'];
				if ($count > 0) {

					$updSql = "UPDATE marksheet_requests SET REQUEST_STATUS=1 WHERE CERTIFICATE_REQUEST_ID='$value'";
					//echo $updSql;
					//$exSql		= parent::execQuery($insertSql);
					$exSql2		= parent::execQuery($updSql);

					$updSql1 = "UPDATE certificate_requests SET MARKSHEET_REQUEST_STATUS=1, MARKSHEET_APPLIED_DATE=NOW() WHERE CERTIFICATE_REQUEST_ID='$value'";

					$exSql2		= parent::execQuery($updSql1);
				}
			}
		}

		if ($exSql2) {
			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! Students has been aplied for Marksheet successfully!';
		} else {
			parent::rollback();
			$data['success'] = false;
			$data['message'] = 'Sorry! Application for Marksheet failed!';
		}
		return json_encode($data);
	}
	public function parcelReceivedStatus($dispatch_id)
	{
		$sql = "UPDATE postal_dispatch SET DISPATCH_STATUS=2, UPDATED_BY='" . $_SESSION['user_fullname'] . "', UPDATED_ON=NOW() WHERE DISPATCH_ID='$dispatch_id'";
		$res = parent::execQuery($sql);
		if ($res && parent::rows_affected() > 0) {
			return false;
		}
		return true;
	}

	//get exam final id for multi subject list in print marksheet
	public function get_exam_final_id($cert_req_id = '')
	{
		$data = '';
		$sql = "SELECT EXAM_RESULT_FINAL_ID FROM certificate_requests WHERE DELETE_FLAG=0 ";
		if ($cert_req_id != '') {
			$sql .= " AND CERTIFICATE_REQUEST_ID='$cert_req_id'";
		}
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if ($res && $res->num_rows > 0) {
			$data = $res;
		}
		return $data;
	}
}
