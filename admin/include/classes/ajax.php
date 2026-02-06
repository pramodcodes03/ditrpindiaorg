<?php session_start();
ob_start();
if (!isset($_SESSION['user_login_id'])) {
	header('location:login.php');
}
include('database_results.class.php');
include('access.class.php');

$db 	= new  database_results();
$access = new  access();

$user_id	= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role 	= isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

if ($user_role == 3) {
	$institute_id = $db->get_parent_id($user_role, $user_id);
	$staff_id = $user_id;
} else {
	$institute_id = $user_id;
	$staff_id = 0;
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($action != '') {
	switch ($action) {

		case ('delete_news'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_news($id)) {
				echo  "News deleted successfully.";
			} else {
				echo "Sorry! Deleting News failed.";
			}
			break;

		case ('delete_teacher'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_teacher($id)) {
				echo  "Teacher deleted successfully.";
			} else {
				echo "Sorry! Deleting Teacher failed.";
			}
			break;

		case ('delete_rechargerequest'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_rechargerequest($id)) {
				echo  "Recharge Request deleted successfully.";
			} else {
				echo "Sorry! Deleting Recharge Request failed.";
			}
			break;

		case ('delete_recharge_offer'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_rechargeoffers($id)) {
				echo  "Recharge Offer deleted successfully.";
			} else {
				echo "Sorry! Deleting Recharge Offer failed.";
			}
			break;

		case ('get_course_condition'):
			$state_id = isset($_POST['state_id']) ? $_POST['state_id'] : '';

			echo '<option name="" value="">Select a Course</option>';
			$sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.COURSE_TYPE, A.TYPING_COURSE_ID FROM institute_courses A WHERE  A.INSTITUTE_ID='$institute_id' AND A.DELETE_FLAG=0 AND A.ACTIVE=1 $state_id";
			//echo $sql;
			$ex = $db->execQuery($sql);
			if ($ex && $ex->num_rows > 0) {
				while ($data = $ex->fetch_assoc()) {
					$INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
					$COURSE_ID 			 = $data['COURSE_ID'];
					$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
					$TYPING_COURSE_ID 	 = $data['TYPING_COURSE_ID'];

					if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
						$course 			 = $db->get_course_detail($COURSE_ID);
						$course_name 		 = $course['COURSE_NAME_MODIFY'];
					}

					if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
						$course 			 = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
						$course_name 		 = $course['COURSE_NAME_MODIFY'];
					}

					if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
						$course = $db->get_course_detail_typing($TYPING_COURSE_ID);
						$course_name 	= $course['COURSE_NAME_MODIFY'];
					}

					$selected = (is_array($interested_course) && in_array($INSTITUTE_COURSE_ID, $interested_course)) ? 'selected="selected"' : '';

					echo '<option value="' . $INSTITUTE_COURSE_ID . '" ' . $selected . '>' . $course_name . '</option>';
				}
			}

			break;


		case ('delete_franchise_enquiry'):
			///echo "Hi"; exit();		
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('institute.class.php');
			$institute 	= new  institute();
			if ($institute->delete_franchise_enquiry($id)) {
				echo  "Franchise Enquiry deleted successfully.";
			} else {
				echo "Sorry! Deleting Franchise Enquiry failed.";
			}
			break;

		case ('delete_product'):
			///echo "Hi"; exit();		
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('tools.class.php');
			$tools 	= new  tools();
			if ($tools->delete_product($id)) {
				echo  "Product deleted successfully.";
			} else {
				echo "Sorry! Deleting Product failed.";
			}
			break;

		case ('delete_seminar'):
			///echo "Hi"; exit();		
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('seminar.class.php');
			$seminar 	= new  seminar();
			if ($seminar->delete_seminar($id)) {
				echo  "Seminar deleted successfully.";
			} else {
				echo "Sorry! Deleting Seminar failed.";
			}
			break;

		case ('delete_seminar_student'):
			///echo "Hi"; exit();		
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('seminar.class.php');
			$seminar 	= new  seminar();
			if ($seminar->delete_seminar_student($id)) {
				echo  "Seminar Student deleted successfully.";
			} else {
				echo "Sorry! Deleting Seminar Student failed.";
			}
			break;

		case ('delete_batch'):
			///echo "Hi"; exit();		
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('institute.class.php');
			$institute 	= new  institute();
			if ($institute->delete_batch($id)) {
				echo  "Batch deleted successfully.";
			} else {
				echo "Sorry! Deleting Batch failed.";
			}
			break;

		case ('delete_award'):
			///echo "Hi"; exit();		
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('course.class.php');
			$course 	= new  course();
			if ($course->delete_award($id)) {
				echo  "Award deleted successfully.";
			} else {
				echo "Sorry! Deleting Award failed.";
			}
			break;

			/* change slider status  */
		case ('delete_slider'):
			///echo "Hi"; exit();		
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_slider($id)) {
				echo  "Slider deleted successfully.";
			} else {
				echo "Sorry! Deleting Slider failed.";
			}
			break;

		case ('delete_testimonial'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_testimonial($id)) {
				echo  "Testimonial deleted successfully.";
			} else {
				echo "Sorry! Deleting Testimonial failed.";
			}
			break;

		case ('delete_socialinks'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_socialinks($id)) {
				echo  "Social Link deleted successfully.";
			} else {
				echo "Sorry! Deleting Social Link failed.";
			}
			break;

		case ('delete_advertise'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_advertise($id)) {
				echo  "Advertise deleted successfully.";
			} else {
				echo "Sorry! Deleting Advertise failed.";
			}
			break;

		case ('delete_advertise_updated'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('tools.class.php');
			$tools 	= new  tools();
			if ($tools->delete_advertise($id)) {
				echo  "Advertise deleted successfully.";
			} else {
				echo "Sorry! Deleting Advertise failed.";
			}
			break;

		case ('delete_courses'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_courses($id)) {
				echo  "Course deleted successfully.";
			} else {
				echo "Sorry! Deleting Course failed.";
			}
			break;

		case ('delete_courses'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_courses($id)) {
				echo  "Course deleted successfully.";
			} else {
				echo "Sorry! Deleting Course failed.";
			}
			break;

		case ('delete_services'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_services($id)) {
				echo  "Services deleted successfully.";
			} else {
				echo "Sorry! Deleting Services failed.";
			}
			break;

		case ('delete_affiliations'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_affiliations($id)) {
				echo  "Affiliations deleted successfully.";
			} else {
				echo "Sorry! Deleting Affiliations failed.";
			}
			break;

		case ('delete_achievers'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_achievers($id)) {
				echo  "Achievers deleted successfully.";
			} else {
				echo "Sorry! Deleting Achievers failed.";
			}
			break;

		case ('delete_team'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_team($id)) {
				echo  "Team deleted successfully.";
			} else {
				echo "Sorry! Deleting Team failed.";
			}
			break;

		case ('delete_galleryImages'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_galleryImages($id)) {
				echo  "Gallery Images deleted successfully.";
			} else {
				echo "Sorry! Deleting Gallery Images failed.";
			}
			break;

		case ('delete_galleryVideos'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_galleryVideos($id)) {
				echo  "Gallery Videos deleted successfully.";
			} else {
				echo "Sorry! Deleting Gallery Videos failed.";
			}
			break;

		case ('delete_jobupdate'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_jobupdate($id)) {
				echo  "Job deleted successfully.";
			} else {
				echo "Sorry! Deleting Job failed.";
			}
			break;

		case ('delete_verification'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_verification($id)) {
				echo  "Verification deleted successfully.";
			} else {
				echo "Sorry! Deleting verification failed.";
			}
			break;

		case ('delete_blog'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_blog($id)) {
				echo  "Blog deleted successfully.";
			} else {
				echo "Sorry! Deleting Blog failed.";
			}
			break;

		case ('delete_partner'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_partner($id)) {
				echo  "Partner deleted successfully.";
			} else {
				echo "Sorry! Deleting Partner failed.";
			}
			break;

		case ('delete_sample_certificate'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_sample_certificate($id)) {
				echo  "Sample Certficate deleted successfully.";
			} else {
				echo "Sorry! Deleting Sample Certficate failed.";
			}
			break;

		case ('delete_onlineclasses_details'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('tools.class.php');
			$tools = new tools();
			if ($tools->delete_onlineclasses_details($id)) {
				echo  "Online class link deleted successfully.";
			} else {
				echo "Sorry! Deleting Online class link failed.";
			}
			break;


		case ('delete_old_certificate'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('tools.class.php');
			$tools = new tools();
			if ($tools->delete_old_certificate($id)) {
				echo  "Certificate deleted successfully.";
			} else {
				echo "Sorry! Deleting Certificate failed.";
			}
			break;

			//delete multiple subject
		case ('delete_multi_subject'):
			$subjectid = isset($_POST['subjectid']) ? $_POST['subjectid'] : '';
			$courseid = isset($_POST['courseid']) ? $_POST['courseid'] : '';

			include_once('coursemultisub.class.php');
			$coursemultisub = new coursemultisub();
			if ($coursemultisub->delete_multi_subject($subjectid, $courseid)) {
				echo  "Subject deleted successfully.";
			} else {
				echo "Sorry! Deleting Subject failed.";
			}
			break;

		case ('delete_payment'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_payment($id)) {
				echo  "Payment deleted successfully.";
			} else {
				echo "Sorry! Deleting Payment failed.";
			}
			break;

		case ('delete_download_material'):
			$id = isset($_POST['id']) ? $_POST['id'] : '';
			include_once('websiteManage.class.php');
			$websiteManage 	= new  websiteManage();
			if ($websiteManage->delete_download_material($id)) {
				echo  "Download material deleted successfully.";
			} else {
				echo "Sorry! Deleting download material failed.";
			}
			break;


			/* set account expiry date*/
		case ('set_acc_expiry_date'):
			$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
			echo $access->acc_expiry_date($start_date);
			break;
			/* get city list on state change */
		case ('get_city_list'):
			$state_id = isset($_POST['state_id']) ? $_POST['state_id'] : '';
			echo $db->MenuItemsDropdown('city_master', 'CITY_ID', 'CITY_NAME', 'CITY_ID,CITY_NAME', '', ' WHERE STATE_ID="' . $state_id . '"');
			break;
			/* set categrybysupportype*/
		case ('categrybyType_act'):
			$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : '';
			echo $db->MenuItemsDropdown('help_support_category', 'SUPPORT_CAT_ID', 'CATEGORY_NAME', 'SUPPORT_CAT_ID,CATEGORY_NAME', '', ' WHERE SUPPORT_TYPE_ID="' . $type_id . '"');
			break;
			/* set account expiry date*/
		case ('generate_pass'):
			echo $res =  $access->generate_password();
			break;
			/* get exam list on course change */
		case ('get_exam_list'):
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			echo $db->MenuItemsDropdown('exam_structure', 'EXAM_ID', 'EXAM_TITLE', 'EXAM_ID,EXAM_TITLE', '', ' WHERE AICPE_COURSE_ID="' . $course_id . '" ORDER BY EXAM_TITLE ASC');
			break;
			/* getstudent list by institute id */
		case ('get_student_list_by_inst'):
			$inst_id = isset($_POST['inst_id']) ? $_POST['inst_id'] : '';

			echo $db->MenuItemsDropdown('student_details', 'STUDENT_ID', 'STUDENT_FULLNAME', 'STUDENT_ID,STUDENT_FULLNAME', '', ' WHERE INSTITUTE_ID="' . $inst_id . '" ORDER BY STUDENT_FULLNAME ASC');
			break;
			/* get user list bu user role */
		case ('get_user_list_by_role'):
			$user_role = isset($_POST['user_role']) ? $_POST['user_role'] : '';
			if ($user_role == 2)
				echo $db->MenuItemsDropdown('institute_details', 'INSTITUTE_ID', 'INSTITUTE_NAME', 'INSTITUTE_ID,INSTITUTE_NAME', '', " WHERE DELETE_FLAG=0 AND ACTIVE=1");
			else if ($user_role == 3)
				echo $db->MenuItemsDropdown('employer_details', 'EMPLOYER_ID', 'EMPLOYER_COMPANY_NAME', 'EMPLOYER_ID,EMPLOYER_COMPANY_NAME', '', " WHERE DELETE_FLAG=0 AND ACTIVE=1");
			if ($user_role == 8)
				echo $db->MenuItemsDropdown('institute_details', 'INSTITUTE_ID', 'INSTITUTE_NAME', 'INSTITUTE_ID,CONCAT(INSTITUTE_NAME," - ",INSTITUTE_CODE) AS INSTITUTE_NAME', $user_id, " WHERE DELETE_FLAG=0 AND ACTIVE=1 AND INSTITUTE_ID !=1");
			break;

			/* get institute course by course type change */
		case ('get_institute_courses'):
			$data = '';
			$course_type = isset($_POST['course_type']) ? $_POST['course_type'] : '';
			if ($course_type == 1)
				$data =  $db->MenuItemsDropdown('institute_courses A LEFT JOIN courses B ON A.COURSE_ID=B.COURSE_ID', "COURSE_ID", "COURSE_NAME", "A.COURSE_ID, B.COURSE_NAME", $course, " WHERE A.INSTITUTE_ID ='" . $_SESSION['user_id'] . "' AND A.DELETE_FLAG=0 AND A.ACTIVE=1 ORDER BY B.COURSE_NAME ASC");
			elseif ($course_type == 2)
				$data =  $db->MenuItemsDropdown('non_courses', "COURSE_ID", "COURSE_NAME", "COURSE_ID, COURSE_NAME", $course, " WHERE INSTITUTE_ID ='" . $_SESSION['user_id'] . "' AND DELETE_FLAG=0 AND ACTIVE=1 ORDER BY COURSE_NAME ASC");
			echo $data;
			break;
			/* delete institue files */
		case ('delete_institute_file'):
			$file_id = isset($_POST['file_id']) ? $_POST['file_id'] : '';
			$inst_id = isset($_POST['inst_id']) ? $_POST['inst_id'] : '';
			include_once('institute.class.php');
			$institute 	= new  institute();
			if ($institute->delete_institue_file($file_id, $inst_id)) {
				echo  "File deleted successfully.";
			} else {
				echo "Sorry! Deleting file failed.";
			}
			break;
			/* change institute status  */
		case ('change_emp_status'):
			$emp_id = $db->test(isset($_POST['emp_id']) ? $_POST['emp_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('employer.class.php');
			$employer 	= new  employer();
			if ($employer->changeStatusFlag($emp_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;

			/* change institute verfiy  */
		case ('change_emp_verify'):
			$emp_id = $db->test(isset($_POST['emp_id']) ? $_POST['emp_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('employer.class.php');
			$employer 	= new  employer();
			if ($employer->changeVerifyFlag($emp_id, $flag)) {
				echo  "Verify status changed.";
			} else {
				echo "Sorry! Verify status change failed.";
			}
			break;
			/* delete employer */
		case ('delete_employer'):
			//$file_id = isset($_POST['file_id'])?$_POST['file_id']:'';
			$emp_id = isset($_POST['emp_id']) ? $_POST['emp_id'] : '';
			include_once('employer.class.php');
			$employer 	= new  employer();
			if (!$employer->delete_employer($emp_id)) {
				echo  "Employer deleted successfully.";
			} else {
				echo "Sorry! Deleting Employer failed.";
			}
			break;
			/* delete employer files */
		case ('delete_employer_file'):
			$file_id = isset($_POST['file_id']) ? $_POST['file_id'] : '';
			$emp_id = isset($_POST['emp_id']) ? $_POST['emp_id'] : '';
			include_once('employer.class.php');
			$employer 	= new  employer();
			if ($employer->delete_employer_file($file_id, $emp_id)) {
				echo  "Employer file deleted successfully.";
			} else {
				echo "Sorry! Deleting Employer file failed.";
			}
			break;
			/* change job post status  */
		case ('change_jobpost_status'):
			$job_id = $db->test(isset($_POST['job_id']) ? $_POST['job_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('employer.class.php');
			$employer 	= new  employer();
			if ($employer->changeJobpostStatus($job_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* delete job post */
		case ('delete_jobpost'):
			$job_id = isset($_POST['job_id']) ? $_POST['job_id'] : '';
			include_once('employer.class.php');
			$employer 	= new  employer();
			if ($employer->deletejobpost($job_id)) {
				echo  "Job post deleted successfully.";
			} else {
				echo "Sorry! Deleting Job failed.";
			}
			break;
			/* delete institue files */
		case ('delete_course_file'):
			$file_id = isset($_POST['file_id']) ? $_POST['file_id'] : '';
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			include_once('course.class.php');
			$course 	= new  course();
			if ($course->delete_course_file($file_id, $course_id)) {
				echo  "File deleted successfully.";
			} else {
				echo "Sorry! Deleting file failed.";
			}
			break;

		case ('delete_course_video'):
			$file_id = isset($_POST['file_id']) ? $_POST['file_id'] : '';
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			include_once('course.class.php');
			$course 	= new  course();
			if ($course->delete_course_video($file_id, $course_id)) {
				echo  "Video deleted successfully.";
			} else {
				echo "Sorry! Deleting Video failed.";
			}
			break;
			/* delete course  */
		case ('delete_course'):
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			include_once('course.class.php');
			$course 	= new  course();
			if ($course->delete_course($course_id)) {
				echo  "Course deleted successfully.";
			} else {
				echo "Sorry! Deleting Course failed.";
			}
			break;

			/* delete festival files */
		case ('delete_festival_file'):
			$file_id = isset($_POST['file_id']) ? $_POST['file_id'] : '';
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			include_once('festival.class.php');
			$festival 	= new  festival();
			if ($festival->delete_festival_file($file_id, $course_id)) {
				echo  "File deleted successfully.";
			} else {
				echo "Sorry! Deleting file failed.";
			}
			break;
			/* delete festival */
		case ('delete_festival'):
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			include_once('festival.class.php');
			$festival 	= new  festival();
			if ($festival->delete_festival($course_id)) {
				echo  "Festival deleted successfully.";
			} else {
				echo "Sorry! Deleting Festival failed.";
			}
			break;


			/* delete exam  */
		case ('delete_exam'):
			$exam_id = isset($_POST['exam_id']) ? $_POST['exam_id'] : '';
			include_once('exam.class.php');
			$exam 	= new  exam();
			if ($exam->delete_exam($exam_id)) {
				echo  "Exam deleted successfully.";
			} else {
				echo "Sorry! Deleting Exam failed.";
			}
			break;
			/* delete exam  */
		case ('delete_quebank'):
			$quebank_id = isset($_POST['quebank_id']) ? $_POST['quebank_id'] : '';
			include_once('exam.class.php');
			$exam 	= new  exam();
			if ($exam->delete_que_bank($quebank_id)) {
				echo  "Question bank deleted successfully.";
			} else {
				echo "Sorry! Deleting Question bank failed.";
			}
			break;
			/* empty que bank  */
		case ('empty_quebank'):
			$quebank_id = isset($_POST['quebank_id']) ? $_POST['quebank_id'] : '';
			include_once('exam.class.php');
			$exam 	= new  exam();
			if ($exam->empty_que_bank($quebank_id)) {
				echo  "Question bank is empty now!";
			} else {
				echo "Sorry! Deleting All Questions failed.";
			}
			break;
			/* delete admin staff  */
		case ('delete_admin_staff'):
			$staff_id = isset($_POST['staff_id']) ? $_POST['staff_id'] : '';
			$login_id = isset($_POST['login_id']) ? $_POST['login_id'] : '';
			include_once('account.class.php');
			$account 	= new  account();
			if (!$account->delete_admin_staff($staff_id, $login_id)) {
				echo  "Staff deleted successfully.";
			} else {
				echo "Sorry! Deleting staff failed.";
			}
			break;
			/* delete gallery file  */
		case ('delete_gallery_file'):
			$gallery_file_id = isset($_POST['gallery_file_id']) ? $_POST['gallery_file_id'] : '';
			if (!$db->delete_gallery_file($gallery_file_id)) {
				echo  "File deleted successfully.";
			} else {
				echo "Sorry! Deleting file failed.";
			}
			break;
			/* delete gallery   */
		case ('delete_gallery'):
			$gallery_id = isset($_POST['gallery_id']) ? $_POST['gallery_id'] : '';
			if (!$db->delete_gallery($gallery_id)) {
				echo  "Gallery deleted successfully.";
			} else {
				echo "Sorry! Deleting gallery failed.";
			}
			break;
			/* change institute name visibility on website  */
		case ('change_inst_website_visiblity'):
			$inst_id = $db->test(isset($_POST['inst_id']) ? $_POST['inst_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('institute.class.php');
			$institute 	= new  institute();
			if ($institute->changeVisiblityFlag($inst_id, $flag)) {
				echo  "Visibility status changed.";
			} else {
				echo "Sorry! Visibility status change failed.";
			}
			break;
			/* delete institute */
		case ('delete_institute'):
			$inst_id = $db->test(isset($_POST['inst_id']) ? $_POST['inst_id'] : '');
			include_once('institute.class.php');
			$institute 	= new  institute();
			if ($institute->deleteInstitue($inst_id)) {
				echo  "Institue delete status changed.";
			} else {
				echo "Sorry! Institue delete status change failed.";
			}
			break;
			/* change institute status  */
		case ('change_inst_status'):
			$inst_id = $db->test(isset($_POST['inst_id']) ? $_POST['inst_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('institute.class.php');
			$institute 	= new  institute();
			if ($institute->changeStatusFlag($inst_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;

			/* change institute status  website*/
		case ('change_inst_status_website'):
			$inst_id = $db->test(isset($_POST['inst_id']) ? $_POST['inst_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('institute.class.php');
			$institute 	= new  institute();
			if ($institute->changeStatusFlagWebsite($inst_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;


			/* change institute verfiy  */
		case ('change_inst_verify'):
			$inst_id = $db->test(isset($_POST['inst_id']) ? $_POST['inst_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('institute.class.php');
			$institute 	= new  institute();
			if ($institute->changeVerifyFlag($inst_id, $flag)) {
				echo  "Verify status changed.";
			} else {
				echo "Sorry! Verify status change failed.";
			}
			break;
			/* change course status */
		case ('change_course_status'):
			$course_id = $db->test(isset($_POST['course_id']) ? $_POST['course_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('course.class.php');
			$course 	= new  course();
			if ($course->changeStatusFlag($course_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* change password  */
		case ('change_pass'):
			$login_id = $db->test(isset($_POST['login_id']) ? $_POST['login_id'] : '');
			$email = $db->test(isset($_POST['email']) ? $_POST['email'] : '');
			if ($access->change_pass($login_id, $email)) {
				echo  "Password change successfully..";
			} else {
				echo "Sorry! Password not changed.";
			}
			break;
			/* bulk edit courses */
		case ('bulk_edit_course'):
			$courseIdArr = json_decode(stripslashes(isset($_POST['courseIdArr']) ? $_POST['courseIdArr'] : ''));
			//echo json_encode($courseIdArr);			
			include_once('course.class.php');
			$course 	= new  course();
			echo $html = $course->getBulkInfo($courseIdArr);
			break;
		case ('bulk_update_submit_course'):
			$exam_fees = $db->test(isset($_POST['exam_fees']) ? $_POST['exam_fees'] : '');
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			$result = array();
			$result['success'] = false;
			if ($course_id == '') {
				$result['error'] = 'Please select at least one course.';
			}
			if ($exam_fees == '') {
				$result['error'] = 'Please enter valid exam fees.';
			}
			if ($exam_fees != '' && !is_numeric($exam_fees)) {
				$result['error'] = 'Please enter valid exam fees.';
			}
			if (!isset($result['error'])) {
				include_once('course.class.php');
				$course 	= new  course();
				$res = $course->updateBulkInfo($course_id, $exam_fees);
				if ($res)
					$result['success'] = true;
			}
			echo json_encode($result);
			break;
			//bulk delete courses
		case ('bulk_delete_courses'):
			$courseIdArr = json_decode(stripslashes(isset($_POST['course_id']) ? $_POST['course_id'] : ''));

			include_once('course.class.php');
			$course 	= new  course();
			if ($res = $course->bulkDeleteCourses($courseIdArr))
				echo "Delete courses successfully.";
			else
				echo "Sorry! Courses was not deleted.";

			break;
			/* change exam status */
		case ('change_exam_status'):
			$exam_id = $db->test(isset($_POST['exam_id']) ? $_POST['exam_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			//print_r($_POST);

			include_once('exam.class.php');
			$exam 	= new  exam();
			if ($exam->changeExamStatus($exam_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* change exam status */
		case ('change_exam_result_display'):
			$exam_id = $db->test(isset($_POST['exam_id']) ? $_POST['exam_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('exam.class.php');
			$exam 	= new  exam();
			if ($exam->changeExamResultDisp($exam_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* change exam status */
		case ('change_exam_demo_status'):
			$exam_id = $db->test(isset($_POST['exam_id']) ? $_POST['exam_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('exam.class.php');
			$exam 	= new  exam();
			if ($exam->changeExamDemoStatus($exam_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* get city list on state change */
		case ('set_course_name_by_course_id'):
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			include_once('exam.class.php');
			$exam 	= new  exam();
			echo $exam->get_course_name($course_id);
			break;
			/* delete question */
		case ('delete_question'):
			$question_id = isset($_POST['question_id']) ? $_POST['question_id'] : '';
			include_once('exam.class.php');
			$exam 	= new  exam();
			if ($exam->deleteQuestion($question_id)) {
				echo  "Question deleted successfully.";
			} else {
				echo "Sorry! Deleting Question failed.";
			}
			break;
			/* change question bank status */
		case ('change_quebank_status'):
			$quebank_id = $db->test(isset($_POST['quebank_id']) ? $_POST['quebank_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			//print_r($_POST);

			include_once('exam.class.php');
			$exam 	= new  exam();
			if ($exam->changeQuebankStatus($quebank_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* send email */
		case ('send_email'):
			$errors = array();  // array to hold validation errors
			$data = array();
			$inst_id	= $db->test(isset($_POST['inst_id']) ? $_POST['inst_id'] : '');
			$inst_name	= $db->test(isset($_POST['inst_name']) ? $_POST['inst_name'] : '');
			$email 		= $db->test(isset($_POST['inst_email']) ? $_POST['inst_email'] : '');
			$subject 	= $db->test(isset($_POST['subject']) ? $_POST['subject'] : '');
			$message 	= $db->test(isset($_POST['message']) ? $_POST['message'] : '');

			if ($email == '')
				$errors['email'] = 'Please enter email.';
			if ($email != '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors['email'] = "Invalid email format.";
			}
			if ($message == '')
				$errors['message'] = 'Please enter message.';

			if (! empty($errors)) {
				$data['success'] = false;
				$data['errors']  = $errors;
				$data['message']  = 'Please correct all the errors.';
			} else {
				if ($access->send_email($email, $inst_name, $subject, $message)) {
					$data['success'] = true;
					$data['message']  = 'Success! Email has been sent successfully.';
					$_SESSION['msg'] = $data['message'];
					$_SESSION['msg_flag'] = true;
				} else {
					$data['success'] = true;
					$data['message']  = 'Sorry! Message not sent.';
					$_SESSION['msg'] = $data['message'];
					$_SESSION['msg_flag'] = false;
				}
			}
			echo json_encode($data);
			break;
			/* ----------------------------------------- INSTITUTE Actions ---------------------------------- */
			/* delete question */
		case ('delete_inst_course'):
			$inst_course_id = $db->test(isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '');
			include_once('course.class.php');
			$course 	= new  course();
			if ($course->delete_institute_course($inst_course_id)) {
				echo  "Course deleted successfully.";
			} else {
				echo "Sorry! Deleting Course failed.";
			}
			break;
			//bulk delete courses
		case ('bulk_delete_inst_courses'):
			$courseIdArr = json_decode(stripslashes(isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : ''));
			$inst_id = $_SESSION['user_id'];
			include_once('course.class.php');
			$course 	= new  course();
			$res = $course->bulk_delete_inst_course($courseIdArr, $inst_id);
			if ($res)
				echo "Delete courses successfully.";
			else
				echo "Sorry! Courses was not deleted.";

			break;
			/* change course status */
		case ('change_inst_course_status'):
			$inst_course_id = $db->test(isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('course.class.php');
			$course 	= new  course();
			if ($course->change_inst_course_status($inst_course_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* change course fees */
		case ('chage_inst_course_fees'):
			$inst_course_id = $db->test(isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '');
			$course_fees 	= $db->test(isset($_POST['course_fees']) ? $_POST['course_fees'] : '');
			include_once('course.class.php');
			$course 	= new  course();
			if ($course->change_inst_course_fees($inst_course_id, $course_fees)) {
				echo  "Course fee changed.";
			} else {
				echo "Sorry! Course fee change failed.";
			}
			break;
			/* change non DITRP course status */
		case ('change_nonaicpe_course_status'):
			$course_id = $db->test(isset($_POST['course_id']) ? $_POST['course_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('course.class.php');
			$course 	= new  course();
			if ($course->change_nonaicpe_course_status($course_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* delete non DITRP course  */
		case ('delete_nonaicpe_course'):
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			include_once('course.class.php');
			$course 	= new  course();
			if (!$course->delete_nonaicpe_course($course_id)) {
				echo  "Course deleted successfully.";
			} else {
				echo "Sorry! Deleting Course failed.";
			}
			break;
			//bulk delete courses
		case ('bulk_delete_non_courses'):
			$courseIdArr = json_decode(stripslashes(isset($_POST['course_id']) ? $_POST['course_id'] : ''));
			$inst_id = $_SESSION['user_id'];
			include_once('course.class.php');
			$course 	= new  course();
			$res = $course->bulk_delete_non_courses($courseIdArr);
			if ($res)
				echo "Delete courses successfully.";
			else
				echo "Sorry! Courses was not deleted.";
			break;
			/* change course status */
		case ('change_inst_staff_status'):
			$staff_id = $db->test(isset($_POST['staff_id']) ? $_POST['staff_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('institute.class.php');
			$institute 	= new  institute();
			if ($institute->change_inst_staff_status($staff_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* delete non DITRP course  */
		case ('delete_inst_staff'):
			$inst_staff_id = $db->test(isset($_POST['inst_staff_id']) ? $_POST['inst_staff_id'] : '');
			include_once('institute.class.php');
			$institute 	= new  institute();
			if (!$institute->delete_inst_staff($inst_staff_id)) {
				echo  "Institute staff deleted successfully.";
			} else {
				echo "Sorry! Deleting Institute staff  failed.";
			}
			break;
			/* delete student */
		case ('delete_student_enquiry'):
			$enq_id = $db->test(isset($_POST['enq_id']) ? $_POST['enq_id'] : '');
			include_once('student.class.php');
			$student 	= new  student();
			if ($student->delete_student_enquiry($enq_id)) {
				echo  "Student enquiry deleted.";
			} else {
				echo "Sorry! Deleting student enquiry failed.";
			}
			break;
			/* delete student file  */
		case ('delete_stud_file'):
			$stud_file_id = $db->test(isset($_POST['stud_file_id']) ? $_POST['stud_file_id'] : '');
			include_once('student.class.php');
			$student 	= new  student();
			if (!$student->delete_student_file($stud_file_id)) {
				echo  "Student file deleted successfully.";
			} else {
				echo "Sorry! Deleting student file failed.";
			}
			break;
			/* change student status */
		case ('change_student_status'):
			$student_id = $db->test(isset($_POST['student_id']) ? $_POST['student_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('student.class.php');
			$student 	= new  student();
			if ($student->changeStudentStatusFlag($student_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* get student course by student id */
		case ('get_student_courses'):
			$data = '';
			$stud_id = isset($_POST['stud_id']) ? $_POST['stud_id'] : '';

			$data =  $db->MenuItemsDropdown('student_course_details A', "COURSE_ID", "COURSE_NAME", "A.COURSE_ID, (SELECT B.COURSE_NAME FROM courses B WHERE B.COURSE_ID=A.COURSE_ID) AS COURSE_NAME", $stud_id, " WHERE A.STUDENT_ID ='$stud_id' AND A.DELETE_FLAG=0 AND A.ACTIVE=1");

			echo $data;
			break;
		case ('get_student_allcourses'):
			$data = '';
			$stud_id = isset($_POST['stud_id']) ? $_POST['stud_id'] : '';
			include_once('student.class.php');
			$student 	= new  student();
			$res = $student->get_student_allcourses($stud_id);
			if ($res != '') {
				echo '<option value="">--select--</option>';
				while ($data = $res->fetch_assoc()) {
					$INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
					$output = $db->get_inst_course_info($INSTITUTE_COURSE_ID);
					//print_r($output);
					if (!empty($output)) {
						$COURSE_NAME = $output['COURSE_NAME_MODIFY'];
						//$COURSE_ID = $output['COURSE_ID'];
						//$COURSE_TYPE = $output['COURSE_TYPE'];						
						echo '<option value="' . $INSTITUTE_COURSE_ID . '">' . $COURSE_NAME . '</option>';
					}
				}
			}
			break;
			/* delete student */
			// case('delete_student'):			
			// 	$stud_id = $db->test(isset($_POST['stud_id'])?$_POST['stud_id']:'');			
			// 	include_once('student.class.php');
			// 	$student 	= new  student();
			// 	if($student->delete_student($stud_id))
			// 	{
			// 		echo  "Student deleted.";
			// 	}else{
			// 		echo "Sorry! Deleting student failed.";
			// 	}
			// 	break;
			/* delete student admission */
		case ('delete_student'):
			$id = $db->test(isset($_POST['id']) ? $_POST['id'] : '');
			include_once('student.class.php');
			$student 	= new  student();
			if ($student->delete_student($id)) {
				echo  "Student admission deleted.";
			} else {
				echo "Sorry! Deleting student admission failed.";
			}
			break;
		case ('get_stud_course_details'):
			$stud_id = $db->test(isset($_POST['stud_id']) ? $_POST['stud_id'] : '');
			include_once('student.class.php');
			$student 	= new  student();
			echo $html = $student->show_stud_course_info($stud_id);
			break;
		case ('get_course_details'):
			$stud_id = $db->test(isset($_POST['stud_id']) ? $_POST['stud_id'] : '');
			$course_id = $db->test(isset($_POST['course_id']) ? $_POST['course_id'] : '');
			include_once('student.class.php');
			include_once('course.class.php');
			$course 	= new  course();
			$html = $course->display_course_info($course_id, '');
			echo json_encode($html);
			//echo $html = $student->display_payment_info($stud_id,$course_id);
			break;
		case ('get_inst_course_info'):
			$inst_id 	= $db->test(isset($_POST['inst_id']) ? $_POST['inst_id'] : '');
			$inst_course_id 	= $db->test(isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '');
			include_once('course.class.php');
			$course 		= new  course();
			$output 		= $course->get_inst_course_detail($inst_id, $inst_course_id, '', false);
			echo json_encode($output);
			break;
			/* add course to student */
		case ('add_stud_course'):
			print_r($_POST);
			$errors = array();  // array to hold validation errors
			$data = array();
			$stud_id	= $db->test(isset($_POST['stud_id']) ? $_POST['stud_id'] : '');
			$course		= $db->test(isset($_POST['course']) ? $_POST['course'] : '');
			$course_type = $db->test(isset($_POST['course_type']) ? $_POST['course_type'] : '');
			include_once('student.class.php');
			$student 	= new  student();
			if ($course == '')
				$errors['course'] = 'Please select course.';

			if ($course_type == '')
				$errors['course_type'] = 'Please select course type.';
			$data['success'] = false;
			if (! empty($errors)) {
				$data['success'] = false;
				$data['errors']  = $errors;
				$data['message']  = 'Please correct all the errors.';
			} else {
				if ($student->add_student_course($stud_id, $course, $course_type)) {
					$data['success'] = true;
					$data['message']  = 'Success! Course has been added successfully.';
					$_SESSION['msg'] = $data['message'];
					$_SESSION['msg_flag'] = true;
				} else {
					$data['success'] = true;
					$data['message']  = 'Sorry! Message not sent.';
					$_SESSION['msg'] = $data['message'];
					$_SESSION['msg_flag'] = false;
				}
			}
			echo json_encode($data);
			break;
			//change student exam type
		case ('change_student_exam_type'):
			$course_detail_id = $db->test(isset($_POST['course_detail_id']) ? $_POST['course_detail_id'] : '');
			$flag 			= $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('exam.class.php');
			$exam = new exam();
			$res = $exam->change_exam_type($course_detail_id, $flag);
			if ($res)
				echo $db->MenuItemsDropdown('exam_types_master', "EXAM_TYPE_ID", "EXAM_TYPE", "EXAM_TYPE_ID,EXAM_TYPE", $flag, "");
			else echo "";
			break;
			//change student exam status
		case ('change_student_exam_status'):
			$course_detail_id = $db->test(isset($_POST['course_detail_id']) ? $_POST['course_detail_id'] : '');
			$flag 			= $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('exam.class.php');
			$exam = new exam();
			$res = $exam->change_exam_status($course_detail_id, $flag);
			if ($res)
				echo $db->MenuItemsDropdown('exam_status_master', "EXAM_STATUS_ID", "EXAM_STATUS", "EXAM_STATUS_ID,EXAM_STATUS", $flag, "");
			else echo "";
			break;
		case ('delete_student_exam_detail'):
			$course_detail_id = $db->test(isset($_POST['course_detail_id']) ? $_POST['course_detail_id'] : '');
			$flag 			= $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('exam.class.php');
			$exam = new exam();
			$res = $exam->delete_stud_exam_details($course_detail_id);
			if ($res) echo "success!";
			else echo "failed!";
			break;
		case ('delete_student_payment'):
			$payment_id = $db->test(isset($_POST['payment_id']) ? $_POST['payment_id'] : '');
			include_once('institute.class.php');
			$institute = new institute();
			$res = $institute->delete_student_payment($payment_id);
			if ($res) echo "success!";
			else echo "failed!";
			break;
		case ('add_new_course_row'):
			$lastrowindex = isset($_POST['lastrowindex']) ? $_POST['lastrowindex'] : '';
			$sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID,A.MULTI_SUB_COURSE_ID, A.COURSE_TYPE FROM institute_courses A WHERE A.INSTITUTE_ID='$institute_id' AND A.DELETE_FLAG=0";
			$ex = $db->execQuery($sql);
			$options = '<option>--select--</option>';
			if ($ex && $ex->num_rows > 0) {
				while ($data = $ex->fetch_assoc()) {
					$INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
					$COURSE_ID 			 = $data['COURSE_ID'];
					$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
					$COURSE_TYPE		 = $data['COURSE_TYPE'];

					$course 			 = $db->get_course_detail($COURSE_ID, $COURSE_TYPE);
					$course_name 		 = $course['COURSE_NAME'];
					$course_fees 		 = $course['COURSE_FEES'];

					if ($COURSE_ID != '') {
						$course 			 = $db->get_course_detail($COURSE_ID, $COURSE_TYPE);
						$course_name 		 = $course['COURSE_NAME'];
						$course_fees 		 = $course['COURSE_FEES'];
						$arr = array("NC", $COURSE_ID);
						$id = implode("-", $arr);
					}

					if ($MULTI_SUB_COURSE_ID != '') {
						$course 			 = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID, $COURSE_TYPE);
						$course_name 		 = $course['COURSE_NAME'];
						$course_fees 		 = $course['COURSE_FEES'];
						$arr = array("MSC", $MULTI_SUB_COURSE_ID);
						$id = implode("-", $arr);
					}


					$selected = '';

					$options .= '<option value="' . $INSTITUTE_COURSE_ID . '" ' . $selected . '>' . $course_name . '</option>';
				}
			}
			$html = '<tr id="courserow-' . $lastrowindex . '">
						<td>
							<select class="form-control" name="course[]" id="course' . $lastrowindex . '" onchange="getInstCourseFees(this.value, this.id);">
								' . $options . '
							</select>
						</td>											
						<td>											
						<input type="text" class="form-control" name="coursefees[]" id="coursefees' . $lastrowindex . '" value="" readonly /></td>
						<td>
							<select class="form-control" name="discrate[]" id="discrate' . $lastrowindex . '" onchange="calDiscountedAmt(' . $lastrowindex . ')">
								<option value="amtminus">Amount - </option>
								<option value="amtplus">Amount + </option>
								<option value="perminus">Percent - </option>
								<option value="perplus">Percent + </option>
							</select>
						</td>
						<td><input type="text" class="form-control" name="discamt[]" id="discamt' . $lastrowindex . '" onchange="calDiscountedAmt(' . $lastrowindex . ')" onkeyup="calDiscountedAmt(' . $lastrowindex . ')" /></td>
						<td><input type="text" class="form-control" name="totalcoursefee[]" id="totalcoursefee' . $lastrowindex . '" readonly /></td>
						<td><input type="text" class="form-control" name="amtrecieved[]" id="amtrecieved' . $lastrowindex . '" onchange="calTotalPerCourse(' . $lastrowindex . ')" onkeyup="calTotalPerCourse(' . $lastrowindex . ')" /></td>
						<td><input type="text" class="form-control" name="amtbalance[]" id="amtbalance' . $lastrowindex . '" readonly /></td>
						<td><textarea class="form-control" name="payremarks[]" id="payremarks' . $lastrowindex . '"></textarea></td>
						<td><a href="javascript:void(0)"  onclick="deleteCourseRow(' . $lastrowindex . ')" class="btn btn-xs btn-danger"><i class="fa fa-minus-circle"></i></a></td>
					</tr>';
			echo $html;
			break;
			/*
		case('get_inst_course_fees'):
			$coursefee=0;
			$inst_course_id = isset($_POST['inst_course_id'])?$_POST['inst_course_id']:'';
			$res = $db->get_inst_course_info($inst_course_id);
			if($res!='')
				$coursefee = $res['COURSE_FEES'];
			echo $coursefee;
			break;*/
		case ('get_inst_course_fees'):
			$coursefee = 0;
			$inst_course_id = isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '';
			$res = $db->get_inst_course_fees($inst_course_id);
			if ($res != 0) {
				$coursefee = $res;
			}
			echo $coursefee;
			break;
			//display payment details of course of student
		case ('get_stud_payment_info'):
			$stud_id = $db->test(isset($_POST['stud_id']) ? $_POST['stud_id'] : '');
			$course_id = $db->test(isset($_POST['course_id']) ? $_POST['course_id'] : '');
			$cond = '';
			if ($cond != '') {
				$cond = " AND A.INSTITUTE_COURSE_ID='$course_id' ";
			}

			$output = '';
			include_once('institute.class.php');
			$institute = new institute();
			$payments = $institute->get_stud_course_payment_total($stud_id, $course_id, 0);
			//$payments = $institute->list_student_payments('',$stud_id,'', '', $cond);
			if ($payments != '') {
				$output = '<div class="table-responsive pt-3"> <table id="order-listing" class="table">
							<thead>
								<tr>									
									<th>Date</th>					
									<th>Course Name</th>					
									<th>Total Course Fees</th>
									<th>Fees Paid</th>
									<th>Fees Balance</th>
									<th>Action</th>									
								</tr>
							</thead><tbody>';
				while ($data = $payments->fetch_assoc()) {
					extract($data);
					$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
					//$FEES_BALANCE = floatval($TOTAL_COURSE_FEES) - floatval($FEES_PAID);
					$action = '';
					if ($db->permission('update_student_fees'))
						$action = "<a href='page.php?page=studentUpdateFees&payid=$PAYMENT_ID' class='btn btn-primary table-btn' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";


					$action .= "<a href='page.php?page=viewStudentReceipt&payid=$PAYMENT_ID' target='_blank' class='btn btn-primary table-btn' title='Print Reciept'><i class='mdi mdi-file-pdf'></i></a>";

					$output .= "<tr>
								  <td>$FEES_PAID_ON</td>	
								  <td>$COURSE_NAME</td>	
								  <td>$TOTAL_COURSE_FEES</td>	
								  <td>$FEES_PAID</td>											  	
								  <td>$FEES_BALANCE</td>	
								  <td>$action</td>	 									 
								 </tr>";
				}
				$output .= '</tbody></table></div>';
			}
			echo $output;
			break;
			//get student course fees balance
		case ('get_stud_course_fee_bal'):
			$stud_id = $db->test(isset($_POST['stud_id']) ? $_POST['stud_id'] : '');
			$course_id = $db->test(isset($_POST['course_id']) ? $_POST['course_id'] : '');
			include_once('institute.class.php');
			$institute = new institute();
			$res = $institute->get_stud_course_fee_detail($stud_id, $course_id);
			echo json_encode($res);
			break;
			//get student course fees balance
		case ('generate_esc'):
			$elem = isset($_POST['elem']) ? $_POST['elem'] : '';
			$stud_course_id = substr($elem, 3);
			include_once('exam.class.php');
			$exam = new exam();
			$res = $exam->generate_esc($stud_course_id);
			if ($res)
				echo "Success!";
			else echo "Failed!";
			break;
			//delete student exam results
		case ('delete_student_exam_result'):
			$exam_result_id = $db->test(isset($_POST['exam_result_id']) ? $_POST['exam_result_id'] : '');
			if ($exam_result_id != '')
				$exam_result_id = substr($exam_result_id, 6);
			include_once('exam.class.php');
			$exam = new exam();
			$res = $exam->delete_stud_exam_result($exam_result_id);
			if ($res) echo "success!";
			else echo "failed!";
			break;
			/* change gallery status  */
		case ('change_gallery_status'):
			$gallery_id = $db->test(isset($_POST['gallery_id']) ? $_POST['gallery_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');

			if ($db->change_gallery_status($gallery_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* delete certificate request */
		case ('delete_certificate_request'):
			$cert_req_id = isset($_POST['cert_req_id']) ? $_POST['cert_req_id'] : '';
			if ($db->delete_certificate_request($cert_req_id)) {
				echo  "Certificate request deleted successfully.";
			} else {
				echo "Sorry! Deleting certificate request failed.";
			}
			break;
			/* delete certificate request */
		case ('delete_certificate_request_all'):
			$cert_req_id = isset($_POST['cert_req_id']) ? $_POST['cert_req_id'] : '';
			if ($db->delete_certificate_request_all($cert_req_id)) {
				echo  "Certificate request deleted successfully.";
			} else {
				echo "Sorry! Deleting certificate request failed.";
			}
			break;
			/* delete order certificate request all */
		case ('delete_order_certificate_request_all'):
			$cert_req_id = isset($_POST['cert_req_id']) ? $_POST['cert_req_id'] : '';
			if ($db->delete_order_certificate_request_all($cert_req_id)) {
				echo  "Order Certificate request deleted successfully.";
			} else {
				echo "Sorry! Deleting order certificate request failed.";
			}
			break;
			/* delete order certificate request */
		case ('delete_order_certificate_request'):
			$cert_req_id = isset($_POST['cert_req_id']) ? $_POST['cert_req_id'] : '';
			if ($db->delete_order_certificate_request($cert_req_id)) {
				echo  "Order Certificate request deleted successfully.";
			} else {
				echo "Sorry! Deleting Order certificate request failed.";
			}
			break;
			/* reset_student_exam*/
		case ('reset_student_exam'):
			$stud_course_id = isset($_POST['stud_course_id']) ? $_POST['stud_course_id'] : '';
			require_once('exam.class.php');
			$exam = new exam();
			if ($exam->reset_student_exam($stud_course_id)) {
				echo  "Exam has been RESET successfully! Please Refresh the page.";
			} else {
				echo "Sorry! Reseting exam failed.";
			}
			break;
			//send password recovery sms
		case ('forget_pass_sms'):

			break;
		case ('upload_doc_sms'):
			$userId = $db->test($_REQUEST['userId']) ? $_REQUEST['userId'] : '';
			$userType = $db->test($_REQUEST['userType']) ? $_REQUEST['userType'] : '';
			$mobile = $db->get_user_mobile($userId, $userType);
			$name = $db->get_owner_fullname($userId, $userType);
			$message = "Dear $name\r\nKindly upload following documents in your DITRP Login for verification.\r\nLOGO\r\nPASSPORT PHOTO\r\nPHOTO ID PROOF\r\nREGISTRATION CERTIFICATE\r\nQUALIFICATION\r\nPROFESSIONAL COURSE\r\nINST. PHOTOS\r\nDITRP\r\nFor assistance call " . SUPPORT_NO;
			$access->trigger_sms($message, $mobile);
			break;
			/* send certificate dispatch sms */
			/*case('send_cert_dispatch_sms'):
			$errors = array();  // array to hold validation errors
			$data = array();
			$cert_req_mast_id	= $db->test(isset($_POST['cert_req_mast_id'])?$_POST['cert_req_mast_id']:'');
			$reciept_no			= $db->test(isset($_POST['reciept_no'])?$_POST['reciept_no']:'');
			$date_dispatch		= $db->test(isset($_POST['date_dispatch'])?$_POST['date_dispatch']:'');
			$total_cert 		= $db->test(isset($_POST['total_cert'])?$_POST['total_cert']:'');
			$dispatch_mode 		= $db->test(isset($_POST['dispatch_mode'])?$_POST['dispatch_mode']:'');
			$inst_name 			= $db->test(isset($_POST['inst_name'])?$_POST['inst_name']:'');
			$inst_mobile 		= $db->test(isset($_POST['inst_mobile'])?$_POST['inst_mobile']:'');
			$message 			= isset($_POST['message'])?$_POST['message']:'';
			
			if($reciept_no=='')
				$errors['reciept_no'] = 'Please enter reciept number!';
			if($date_dispatch=='')
				$errors['date_dispatch'] = 'Please enter date of dsipatch!';
			if($total_cert=='')
				$errors['date_dispatch'] = 'Please enter total number of certifcates!';
			
			if($message=='')
				$errors['message'] = 'Message can not be empty!';
			
			if ( ! empty($errors)) {
				  $data['success'] = false;
				  $data['errors']  = $errors;
				  $data['message']  = 'Please correct all the errors.';
			} else {				
				$access->trigger_sms($message,$inst_mobile);
				$data['success'] = true;
				$data['message']  = 'Success! Dispatch details SMS has been sent successfully.';
				$_SESSION['msg'] = $data['message'];
				$_SESSION['msg_flag'] = true;				
			}
			echo json_encode($data);
			break;*/
			// Parcel Dispatch New Code

		case ('send_cert_dispatch_sms'):
			$errors = array();  // array to hold validation errors
			$data = array();
			$institute_id		= $db->test(isset($_POST['institute_id']) ? $_POST['institute_id'] : '');
			$cert_req_mast_id	= $db->test(isset($_POST['cert_req_mast_id']) ? $_POST['cert_req_mast_id'] : '');
			$reciept_no			= $db->test(isset($_POST['reciept_no']) ? $_POST['reciept_no'] : '');
			$date_dispatch		= $db->test(isset($_POST['date_dispatch']) ? $_POST['date_dispatch'] : '');
			$total_cert 		= $db->test(isset($_POST['total_cert']) ? $_POST['total_cert'] : '');
			$dispatch_mode 		= $db->test(isset($_POST['dispatch_mode']) ? $_POST['dispatch_mode'] : '');
			$inst_name 			= $db->test(isset($_POST['inst_name']) ? $_POST['inst_name'] : '');
			$inst_mobile 		= $db->test(isset($_POST['inst_mobile']) ? $_POST['inst_mobile'] : '');
			$message 			= isset($_POST['message']) ? $_POST['message'] : '';
			$created_by  		= $_SESSION['user_name'];

			if ($reciept_no == '')
				$errors['reciept_no'] = 'Please enter reciept number!';
			if ($date_dispatch == '')
				$errors['date_dispatch'] = 'Please enter date of dsipatch!';
			if ($total_cert == '')
				$errors['date_dispatch'] = 'Please enter total number of certifcates!';

			if ($message == '')
				$errors['message'] = 'Message can not be empty!';

			if (! empty($errors)) {
				$data['success'] = false;
				$data['errors']  = $errors;
				$data['message']  = 'Please correct all the errors.';
			} else {
				$date_dispatch1 = date('Y-m-d', strtotime($date_dispatch));
				$tableName 	= "postal_dispatch";
				$tabFields 	= "(DISPATCH_ID,CERTIFICATE_REQUEST_MASTER_ID, INSTITUTE_ID, RECEIPTNO, DISPATCH_DATE,NO_OF_CERTIFICATE,MODE_OF_DISPATCH,PREVIEW_SMS,DISPATCH_STATUS,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON)";
				$insertVals	= "(NULL,'$cert_req_mast_id','$institute_id', '$reciept_no', '$date_dispatch1','$total_cert','$dispatch_mode','$message',1,1,0,'$created_by',NOW())";
				$insertSql	= $db->insertData($tableName, $tabFields, $insertVals);
				$exSql		= $db->execQuery($insertSql);

				//$access->trigger_sms($message,$inst_mobile);
				$data['success'] = true;
				$data['message']  = 'Success! Dispatch details SMS has been sent successfully.';
				$_SESSION['msg'] = $data['message'];
				$_SESSION['msg_flag'] = true;
			}
			echo json_encode($data);
			break;
			/*------------- Parcel Received Status ----------------*/
		case ('parcel_received_status'):
			$dispatch_id = $db->test(isset($_POST['dispatch_id']) ? $_POST['dispatch_id'] : '');
			include_once('exam.class.php');
			$exam 	= new  exam();
			if ($exam->parcelReceivedStatus($dispatch_id)) {
				echo  "Parcel Status Is Received.";
			} else {
				echo "Sorry! Parcel Status Can Not Be Changed.";
			}
			break;

		case ('receive_parcel_status_form'):
			$errors = array();  // array to hold validation errors
			$data = array();
			$dispatchid		= $db->test(isset($_POST['dispatchid']) ? $_POST['dispatchid'] : '');
			$receiveddate	= $db->test(isset($_POST['receiveddate']) ? $_POST['receiveddate'] : '');
			$status			= $db->test(isset($_POST['status']) ? $_POST['status'] : '');

			$updated_by  		= $_SESSION['user_name'];

			if ($receiveddate == '')
				$errors['receiveddate'] = 'Please select received date!';
			if ($status == '')
				$errors['status'] = 'Please select parcel status!';

			if (! empty($errors)) {
				$data['success'] = false;
				$data['errors']  = $errors;
				$data['message']  = 'Please correct all the errors.';
			} else {
				$receiveddate = date('Y-m-d', strtotime($receiveddate));
				$tableName 	= "postal_dispatch";
				$setValues 	= "RECEIVED_DATE='$receiveddate',DISPATCH_STATUS='$status',UPDATED_BY='$updated_by',UPDATED_ON=NOW()";
				$whereClause = " WHERE DISPATCH_ID='$dispatchid'";
				$updateSql	= $db->updateData($tableName, $setValues, $whereClause);
				$exSql		= $db->execQuery($updateSql);

				$data['success'] = true;
				$data['message']  = 'Success! Parcel is received successfully.';
				$_SESSION['msg'] = $data['message'];
				$_SESSION['msg_flag'] = true;
			}
			echo json_encode($data);
			break;

			/*-------------------Help Support ----------------*/
		case ('delete_support_type'):
			$supporttype_id = $db->test(isset($_POST['supporttype_id']) ? $_POST['supporttype_id'] : '');
			include_once('helpsupport.class.php');
			$helpsupport 	= new  helpsupport();
			if ($helpsupport->deleteSupportType($supporttype_id)) {
				echo  "Support Type delete status changed.";
			} else {
				echo "Sorry! Support Type delete status change failed.";
			}
			break;

		case ('delete_support_cat'):
			$supportcat_id = $db->test(isset($_POST['supportcat_id']) ? $_POST['supportcat_id'] : '');
			include_once('helpsupport.class.php');
			$helpsupport 	= new  helpsupport();
			if ($helpsupport->deleteSupportCat($supportcat_id)) {
				echo  "Support Category delete status changed.";
			} else {
				echo "Sorry! Support Category delete status change failed.";
			}
			break;

			/*-------------- DIRECT LOGIN----------- */
		case ('direct_login'):
			/* logout */
			$logout = isset($_POST['logout']) ? $_POST['logout'] : '';
			$uname 	= isset($_POST['uname']) ? $_POST['uname'] : '';
			$pword 	= isset($_POST['pword']) ? $_POST['pword'] : '';
			$old_session = $_SESSION;
			if ($access->user_logout()) {
				@session_start();
				$_SESSION['old_session'] = $old_session;
				$result	= $access->user_login($uname, $pword, 'direct_login');
				/*$result = json_decode($result, true);
				$success= isset($result['success'])?$result['success']:'';
				$message= isset($result['message'])?$result['message']:'';
				$errors = isset($result['errors'])?$result['errors']:'';*/
			} else {
				$result['success'] = false;
				$result['message'] = "Failed!";
				$result = json_encode($result);
			}
			echo $result;
			break;

			/*---------------Order Before SMS---------------------*/
		case ('order_before_sms'):

			$message = "Dear ATC Your Certificates Is Approved. Please Order Your Certificate AS Soon As Possible.\r\nDITRP\r\n" . WEBSITE_MOBILE1;

			$inst_mobile = $db->test(isset($_POST['inst_mobile']) ? $_POST['inst_mobile'] : '');
			include_once('access.class.php');
			$access 	= new  access();
			if ($access->trigger_sms($message, $inst_mobile)) {
				echo  "SMS Send Successfully.";
			} else {
				echo "Sorry! SMS Failed.";
			}
			break;

			/*---------------TYping Software---------------------*/
		case ('delete_plan'):
			$plan_id = $db->test(isset($_POST['plan_id']) ? $_POST['plan_id'] : '');
			include_once('typing.class.php');
			$typing 	= new  typing();
			if ($typing->deletePlan($plan_id)) {
				echo  "Plan delete status changed.";
			} else {
				echo "Sorry! Plan delete status change failed.";
			}
			break;

		case ('delete_typing_institute'):
			$inst_id = $db->test(isset($_POST['inst_id']) ? $_POST['inst_id'] : '');
			include_once('typing.class.php');
			$typing 	= new  typing();
			if ($typing->deleteInstitute($inst_id)) {
				echo  "Institute delete status changed.";
			} else {
				echo "Sorry! Institute delete status change failed.";
			}
			break;

			//SEnd Activation Key Via SMS
		case ('send_activationkey_sms'):
			$userId = $db->test($_REQUEST['userId']) ? $_REQUEST['userId'] : '';
			$userType = $db->test($_REQUEST['userType']) ? $_REQUEST['userType'] : '';
			$mobile = $db->get_user_mobile($userId, $userType);
			$key = $db->get_typing_key($userId);

			$inst_code = $db->get_typing_inst_code($userId);

			$message = "Your Excellent Typing Master Activation Key For Institute Code $inst_code  Is -> $key  \r\nDITRP\r\nFor assistance, call " . SUPPORT_NO;
			$access->trigger_sms($message, $mobile);
			break;
			//SEnd Activation Key Via EMAIL
		case ('send_activationkey_email'):
			$inst_id = $db->test(isset($_POST['inst_id']) ? $_POST['inst_id'] : '');
			$userType = $db->test(isset($_POST['userType']) ? $_POST['userType'] : '');
			include_once('typing.class.php');
			$typing 	= new  typing();
			$typing->sendActivationEmail($inst_id, $userType);

			break;
		case ('get_marksheet_detail'):
			$out = array();
			$exam_id 		= isset($_POST['exam_id']) ? $_POST['exam_id'] : '';
			$inst_course_id = isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '';
			if ($exam_id != '') {
				$sql = "SELECT EXAM_RESULT_ID,SUBJECT,PRACTICAL_MARKS,MARKS_OBTAINED FROM exam_result  WHERE EXAM_RESULT_ID=$exam_id";
				$exsql = $db->execQuery($sql);
				$res = $exsql->fetch_assoc();

				if (isset($res['EXAM_RESULT_ID']) && $res['EXAM_RESULT_ID'] > 0) {
					$SUBJECT = isset($res['SUBJECT']) ? $res['SUBJECT'] : '';
					if ($SUBJECT == '') {
						$sql1 = "SELECT COURSE_SUBJECTS FROM institute_courses WHERE INSTITUTE_COURSE_ID=$inst_course_id";
						$res1 = $db->execQuery($sql1);
						if ($res1 && $res1->num_rows > 0) {
							$data1 = $res1->fetch_assoc();
							$SUBJECT = $data1['COURSE_SUBJECTS'];
						}
					}
					$out['markshitreq'] = $res['EXAM_RESULT_ID'];
					$out['markshitsub'] = $SUBJECT;
					$out['markshitmark'] = $res['PRACTICAL_MARKS'];
					$out['markshitmarkobj'] = $res['MARKS_OBTAINED'];
				}
			}

			echo json_encode($out);
			break;
		case ('add_inst_course_subjects'):
			$inst_course_id = isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '';
			$subject = isset($_POST['subject']) ? $_POST['subject'] : ''; {
				$sql = "update  institute_courses SET COURSE_SUBJECTS='$subject'  WHERE INSTITUTE_COURSE_ID=$inst_course_id";
				$exsql = $db->execQuery($sql);
				if ($exsql)
					echo 'success';
				exit();
			}
			echo "failed";
			exit();
			break;
		case ("get_inst_course_subjects"):
			$inst_course_id = isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '';
			$subjects = array();
			$sql = "SELECT COURSE_SUBJECTS FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
			$res = $db->execQuery($sql);
			$sub = '';
			if ($res && $res->num_rows > 0) {
				$data 	= $res->fetch_assoc();
				$sub 	= $data['COURSE_SUBJECTS'];
			}
			$subjects["data"] = array("id" => $inst_course_id, "subjects" => $sub);
			echo json_encode($subjects);
			break;
			// AMC 
		case ('delete_amc'):
			$amc_id = $db->test(isset($_POST['amc_id']) ? $_POST['amc_id'] : '');
			include_once('amc.class.php');
			$amc 	= new  amc();
			if ($amc->delete_Amc($amc_id)) {
				echo  "Amc Deleted successfully";
			} else {
				echo "Sorry! Amc  delete Failed.";
			}
			break;

		case ('deassign_amc'):
			$assign_id = $db->test(isset($_POST['assign_id']) ? $_POST['assign_id'] : '');
			$INSTITUTE_ID = $db->test(isset($_POST['INSTITUTE_ID']) ? $_POST['INSTITUTE_ID'] : '');
			include_once('amc.class.php');
			$amc 	= new  amc();
			if ($amc->deassign_Amc($assign_id, $INSTITUTE_ID)) {
				echo  "Amc De-Assign successfully";
			} else {
				echo "Sorry! Amc  De-Assign  Failed.";
			}
			break;

		case ('save_payment'):
			$amc_id = $db->test(isset($_POST['amc_id']) ? $_POST['amc_id'] : '');
			include_once('amc.class.php');
			$amc 	= new  amc();
			if ($amc->save_payment($amc_id)) {
				echo  "Payment Added Successfully";
			} else {
				echo "Sorry! Payment  Failed.";
			}

			break;

			/* change AMC verfiy  */
		case ('change_amc_verify'):
			$amc_id = $db->test(isset($_POST['amc_id']) ? $_POST['amc_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('amc.class.php');
			$amc 	= new  amc();
			if ($amc->changeAmcVerifyFlag($amc_id, $flag)) {
				echo  "AMC Verify status changed.";
			} else {
				echo "Sorry! AMC Verify status change failed.";
			}
			break;

			//get all courses
		case ("getCourses"):
			$id 	= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
			$code 	= isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
			$instid = isset($_REQUEST['instid']) ? $_REQUEST['instid'] : '';
			$output = array();
			include_once('course.class.php');
			$courses = new course();
			$res = $courses->list_added_courses($instid);
			if ($res != '') {
				while ($data = $res->fetch_assoc()) {
					$courseData = array();
					$courseData['COURSE_ID'] = $data['COURSE_ID'];
					$courseData['INSTITUTE_COURSE_ID'] = $data['INSTITUTE_COURSE_ID'];
					$courseData['COURSE_CODE'] = $db->test($data['COURSE_CODE']);
					$courseData['COURSE_NAME'] = $db->test($data['COURSE_NAME']);
					$courseData['COURSE_NAME_MODIFY'] = $db->test($data['COURSE_NAME_MODIFY']);
					array_push($output, $courseData);
				}
			}
			echo json_encode($output);
			exit();
			break;
		case ('save_bulk_student'):
			//var_dump($_REQUEST); 
			//exit();
			//var_dump($_FILES); exit();
			//print_r($_REQUEST); exit();	
			include_once('student.class.php');
			$student 	= new  student();
			$student->save_bulk_student();
			break;
		case ('save_ticket_rating'):
			include_once('helpsupport.class.php');
			$helpsupport 	= new  helpsupport();
			echo $helpsupport->save_ticket_rating();
			break;

			//Institute Plans
		case ('delete_institute_plan'):
			$plan_id = $db->test(isset($_POST['plan_id']) ? $_POST['plan_id'] : '');
			include_once('instituteplans.class.php');
			$instituteplans 	= new  instituteplans();
			if ($instituteplans->deleteInstitutePlan($plan_id)) {
				echo  "Institute Plan delete status changed.";
			} else {
				echo "Sorry! Institue Plan delete status change failed.";
			}
			break;
			/* delete new course with subject course material */
		case ('delete_course_multi_sub_file'):
			$file_id = isset($_POST['file_id']) ? $_POST['file_id'] : '';
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			include_once('coursemultisub.class.php');
			$coursemultisub 	= new  coursemultisub();
			if ($coursemultisub->delete_course_multi_sub_file($file_id, $course_id)) {
				echo  "File deleted successfully.";
			} else {
				echo "Sorry! Deleting file failed.";
			}
			break;

		case ('delete_course_multi_sub_video'):
			$file_id = isset($_POST['file_id']) ? $_POST['file_id'] : '';
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			include_once('coursemultisub.class.php');
			$coursemultisub 	= new  coursemultisub();
			if ($coursemultisub->delete_course_multi_sub_video($file_id, $course_id)) {
				echo  "Video deleted successfully.";
			} else {
				echo "Sorry! Deleting Video failed.";
			}
			break;

		case ('get_course_not_purchase'):
			$output = array();
			$student_id = isset($_POST['student_id']) ? $_POST['student_id'] : '';
			$interested_course  = isset($_POST['interested_course']) ? $_POST['interested_course'] : '';
			$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

			$sql1 = "SELECT A.INSTITUTE_COURSE_ID FROM student_course_details A WHERE  A.INSTITUTE_ID='$user_id' AND A.STUDENT_ID = $student_id AND A.DELETE_FLAG=0 AND A.ACTIVE=1";
			$ex1 = $db->execQuery($sql1);

			if ($ex1 && $ex1->num_rows > 0) {
				while ($data1 = $ex1->fetch_assoc()) {
					$courseData = $data1['INSTITUTE_COURSE_ID'];
					array_push($output, $courseData);
				}
			}
			$output1 = implode(",", $output);
			$sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.COURSE_TYPE, A.TYPING_COURSE_ID FROM institute_courses A WHERE  A.INSTITUTE_ID='$user_id' AND A.DELETE_FLAG=0 AND A.ACTIVE=1 AND A.INSTITUTE_COURSE_ID NOT IN ($output1)";

			//echo $sql;
			echo '<option value=""> Select a Course </option>';
			$ex = $db->execQuery($sql);
			if ($ex && $ex->num_rows > 0) {
				while ($data = $ex->fetch_assoc()) {
					$INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
					$COURSE_ID 			 = $data['COURSE_ID'];
					$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
					$TYPING_COURSE_ID 	 = $data['TYPING_COURSE_ID'];

					if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
						$course 			 = $db->get_course_detail($COURSE_ID);
						$course_name 		 = $course['COURSE_NAME_MODIFY'];
					}

					if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
						$course 			 = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
						$course_name 		 = $course['COURSE_NAME_MODIFY'];
					}

					if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
						$course = $db->get_course_detail_typing($TYPING_COURSE_ID);
						$course_name 	= $course['COURSE_NAME_MODIFY'];
					}

					$selected = (is_array($interested_course) && in_array($INSTITUTE_COURSE_ID, $interested_course)) ? 'selected="selected"' : '';

					echo '<option value="' . $INSTITUTE_COURSE_ID . '" ' . $selected . '>' . $course_name . '</option>';
				}
			}
			break;
			/* get subject id*/
		case ('get_subject_id'):
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			//echo $db->MenuItemsDropdown ('multi_sub_courses_subjects','COURSE_SUBJECT_ID','COURSE_SUBJECT_NAME','COURSE_SUBJECT_ID,COURSE_SUBJECT_NAME','',' WHERE MULTI_SUB_COURSE_ID="'.$course_id.'"');

			echo $db->MenuItemsDropdown('multi_sub_courses_subjects A LEFT JOIN multi_sub_course_exam_structure B ON A.COURSE_SUBJECT_ID=B.COURSE_SUBJECT_ID', 'COURSE_SUBJECT_ID', 'COURSE_SUBJECT_NAME', 'A.COURSE_SUBJECT_ID, A.COURSE_SUBJECT_NAME', '', " WHERE A.MULTI_SUB_COURSE_ID = $course_id AND A.ACTIVE=1 AND A.DELETE_FLAG=0 ORDER BY A.COURSE_SUBJECT_ID ASC");

			break;

		case ('get_subject_id_typing'):
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
			//echo $db->MenuItemsDropdown ('multi_sub_courses_subjects','COURSE_SUBJECT_ID','COURSE_SUBJECT_NAME','COURSE_SUBJECT_ID,COURSE_SUBJECT_NAME','',' WHERE MULTI_SUB_COURSE_ID="'.$course_id.'"');

			echo $db->MenuItemsDropdown('courses_typing_subjects A LEFT JOIN course_typing_exam_structure B ON A.TYPING_COURSE_SUBJECT_ID=B.COURSE_SUBJECT_ID', 'TYPING_COURSE_SUBJECT_ID', 'TYPING_COURSE_SUBJECT_NAME', 'A.TYPING_COURSE_SUBJECT_ID, CONCAT(A.TYPING_COURSE_SUBJECT_NAME," ",A.TYPING_COURSE_SPEED) AS TYPING_COURSE_SUBJECT_NAME', '', " WHERE A.TYPING_COURSE_ID = $course_id AND B.COURSE_SUBJECT_ID  IS NULL AND A.ACTIVE=1 AND A.DELETE_FLAG=0 ORDER BY A.TYPING_COURSE_SUBJECT_ID ASC");

			break;

			//delete exam multi sub
		case ('delete_exam_multi_sub'):
			$exam_id = isset($_POST['exam_id']) ? $_POST['exam_id'] : '';
			include_once('exammultisub.class.php');
			$exammultisub 	= new  exammultisub();
			if ($exammultisub->delete_exam_multi_sub($exam_id)) {
				echo  "Exam deleted successfully.";
			} else {
				echo "Sorry! Deleting Exam failed.";
			}
			break;
			/* delete question bank for multi sub */
		case ('delete_quebank_multi_sub'):
			$quebank_id = isset($_POST['quebank_id']) ? $_POST['quebank_id'] : '';
			include_once('exammultisub.class.php');
			$exammultisub 	= new  exammultisub();
			if ($exammultisub->delete_que_bank_multi_sub($quebank_id)) {
				echo  "Question bank deleted successfully.";
			} else {
				echo "Sorry! Deleting Question bank failed.";
			}
			break;
			/* empty que bank  for multi sub*/
		case ('empty_quebank_multi_sub'):
			$quebank_id = isset($_POST['quebank_id']) ? $_POST['quebank_id'] : '';
			include_once('exammultisub.class.php');
			$exammultisub 	= new  exammultisub();
			if ($exammultisub->empty_que_bank_multi_sub($quebank_id)) {
				echo  "Question bank is empty now!";
			} else {
				echo "Sorry! Deleting All Questions failed.";
			}
			break;
			/* delete question for multi sub*/
		case ('delete_question_multi_sub'):
			$question_id = isset($_POST['question_id']) ? $_POST['question_id'] : '';
			include_once('exammultisub.class.php');
			$exammultisub 	= new  exammultisub();
			if ($exammultisub->deleteQuestion_multi_sub($question_id)) {
				echo  "Question deleted successfully.";
			} else {
				echo "Sorry! Deleting Question failed.";
			}
			break;
			/* change exam status for multi sub*/
		case ('change_exam_status_multi_sub'):
			$exam_id = $db->test(isset($_POST['exam_id']) ? $_POST['exam_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			//print_r($_POST);

			include_once('exammultisub.class.php');
			$exammultisub 	= new  exammultisub();
			if ($exammultisub->changeExamStatusMultiSub($exam_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* change exam status for multi sub*/
		case ('change_exam_result_display_multi_sub'):
			$exam_id = $db->test(isset($_POST['exam_id']) ? $_POST['exam_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('exammultisub.class.php');
			$exammultisub 	= new  exammultisub();
			if ($exammultisub->changeExamResultDispMultiSub($exam_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* change exam status for multi sub */
		case ('change_exam_demo_status_multi_sub'):
			$exam_id = $db->test(isset($_POST['exam_id']) ? $_POST['exam_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('exammultisub.class.php');
			$exammultisub 	= new  exammultisub();
			if ($exammultisub->changeExamDemoStatusMultiSub($exam_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;
			/* institute -> remove course subject in multi sub course */
		case ('delete_inst_course_sub'):
			$inst_course_sub_id = $db->test(isset($_POST['inst_course_sub_id']) ? $_POST['inst_course_sub_id'] : '');
			include_once('coursemultisub.class.php');
			$coursemultisub 	= new  coursemultisub();
			if ($coursemultisub->delete_institute_course_sub($inst_course_sub_id)) {
				echo  "Course Subject remove successfully.";
			} else {
				echo "Sorry! Removing Course Subject failed.";
			}
			break;

			//institute -> Remove course multi sub
		case ('delete_inst_coursemultisub'):
			$inst_course_id = $db->test(isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '');
			include_once('coursemultisub.class.php');
			$coursemultisub 	= new  coursemultisub();
			if ($coursemultisub->delete_institute_courseMultisub($inst_course_id)) {
				echo  "Course deleted successfully.";
			} else {
				echo "Sorry! Deleting Course failed.";
			}
			break;
			//institute -> bulk delete courses multi sib
		case ('bulk_delete_inst_courses_multi_sub'):
			$courseIdArr = json_decode(stripslashes(isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : ''));
			$inst_id = $_SESSION['user_id'];
			include_once('coursemultisub.class.php');
			$coursemultisub 	= new  coursemultisub();
			$res = $coursemultisub->bulk_delete_inst_course_multi_sub($courseIdArr, $inst_id);
			if ($res)
				echo "Delete courses successfully.";
			else
				echo "Sorry! Courses was not deleted.";

			//delete student exam results multi sub
		case ('delete_student_exam_result_multi_sub'):
			$exam_result_id = $db->test(isset($_POST['exam_result_id']) ? $_POST['exam_result_id'] : '');
			if ($exam_result_id != '')
				$exam_result_id = substr($exam_result_id, 6);
			include_once('exammultisub.class.php');
			$exammultisub = new exammultisub();
			$res = $exammultisub->delete_student_exam_result_multi_sub($exam_result_id);
			if ($res) echo "success!";
			else echo "failed!";
			break;

			/* change course status multi sub */
		case ('change_course_status_multi_sub'):
			$course_id = $db->test(isset($_POST['course_id']) ? $_POST['course_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('coursemultisub.class.php');
			$coursemultisub 	= new  coursemultisub();
			if ($coursemultisub->changeStatusFlagMultiSub($course_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;

			/* change institute status  */
		case ('change_print_status'):
			$inst_id = $db->test(isset($_POST['inst_id']) ? $_POST['inst_id'] : '');
			$flag = $db->test(isset($_POST['flag']) ? $_POST['flag'] : '');
			include_once('admin.class.php');
			$admin 	= new  admin();
			if ($admin->changePrintFlag($inst_id, $flag)) {
				echo  "Active status changed.";
			} else {
				echo "Sorry! Active status change failed.";
			}
			break;

			//multi course subject list
		case ('multi_sub_list'):
			$data1 = array();
			$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';

			include('coursemultisub.class.php');
			$coursemultisub = new coursemultisub();

			$res = $coursemultisub->get_course_subject_added_by_institute($course_id, $institute_id, false);
			if ($res != '') {
				$srno = 1;

				while ($data1 = $res->fetch_assoc()) {
					extract($data1);

					return $data1;
				}
				$srno++;
			}
			break;

		case ("get_inst_course_fees_enquiry"):
			$instcourseid = isset($_POST['instcourseid']) ? $_POST['instcourseid'] : '';
			$coursefees = '';
			$sql = "SELECT COURSE_ID,MULTI_SUB_COURSE_ID,COURSE_FEES,EXAM_FEES,TYPING_COURSE_ID,MINIMUM_FEES FROM institute_courses WHERE INSTITUTE_COURSE_ID= $instcourseid";
			$res = $db->execQuery($sql);

			$output = array();

			if ($res != '') {
				while ($data = $res->fetch_assoc()) {
					$COURSE_ID 	= $data['COURSE_ID'];
					$MULTI_SUB_COURSE_ID 	= $data['MULTI_SUB_COURSE_ID'];
					$TYPING_COURSE_ID 	= $data['TYPING_COURSE_ID'];
					$minamount = '';

					if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != NULL && $COURSE_ID != '0') {
						$course 		 = $db->get_course_detail($COURSE_ID);
						$minamount 		 = $course['MINIMUM_AMOUNT'];
					}

					if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != NULL && $MULTI_SUB_COURSE_ID != '0') {
						$course 			 = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
						$minamount 		 = $course['MULTI_SUB_MINIMUM_AMOUNT'];
					}

					if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != NULL && $TYPING_COURSE_ID != '0') {
						$course 			 = $db->get_course_detail_typing($TYPING_COURSE_ID);
						$minamount 		 = $course['TYPING_MINIMUM_AMOUNT'];
					}

					$coursefees 	= $data['COURSE_FEES'];
					$examfees 		= $data['EXAM_FEES'];
					$minamount 		=  $data['MINIMUM_FEES'];
					$balance 		= $coursefees - $minamount;
					$output = array('coursefees' => $coursefees, 'examfees' => $examfees, 'minamount' => $minamount, 'balance' => $balance);
				}
			}

			echo json_encode($output);
			break;

			//student attendance
		case ('get_student_attendance'):
			$userId 	= isset($_POST['userId']) ? $_POST['userId'] : '';
			$batchId 	= isset($_POST['batchId']) ? $_POST['batchId'] : '';
			$date 		= isset($_POST['date']) ? $_POST['date'] : '';
			$output = array();
			include_once('student.class.php');
			$student 	= new  student();
			$res = $student->list_attendance_student($userId, $batchId, $date);
			if (!empty($res)) {
				while ($data = $res->fetch_assoc()) {
					$result = array();
					$result['id'] = $data['id'];
					$result['batch_id'] = $data['batch_id'];
					$result['student_id'] = $data['student_id'];
					$result['date'] = $data['date'];
					$result['is_present'] = $data['is_present'];
					$output[] = $result;
				}
			}
			echo json_encode($output);
			exit();
			break;

		case ("get_fees_details"):
			$output = '';
			$balancefees = '';
			$totalfees = '';
			$feespaid = '';
			$user_id = $_SESSION['user_id'];
			$sql = "SELECT SUM(TOTAL_COURSE_FEES) as ALL_COURSE_FEES FROM student_course_details WHERE DELETE_FLAG=0 AND INSTITUTE_ID = $user_id";

			//$sql = "SELECT SUM(TOTAL_COURSE_FEES) AS ALL_COURSE_FEES, SUM(FEES_PAID) AS TOTAL_FEES_PAID, SUM(FEES_BALANCE) AS TOTAL_FEES_BALANCE FROM student_payments WHERE DELETE_FLAG=0 AND INSTITUTE_ID = $user_id";
			$res = $db->execQuery($sql);
			$output = array();
			if ($res != '') {
				while ($data = $res->fetch_assoc()) {
					$totalfees 	= isset($data['ALL_COURSE_FEES']) ? $data['ALL_COURSE_FEES'] : 0;
					//$output = array($totalfees);
				}
			}

			$sql1 = "SELECT SUM(FEES_PAID) as FEES_PAID FROM  student_payments WHERE DELETE_FLAG=0 AND INSTITUTE_ID = $user_id";

			$res1 = $db->execQuery($sql1);
			if ($res1 && $res1->num_rows > 0) {
				$data1  = $res1->fetch_assoc();
				$feespaid = $data1['FEES_PAID'];
			}
			$balancefees = $totalfees - $feespaid;
			$output = array($balancefees, $totalfees);
			echo json_encode($output);
			break;

		case ('get_batch_remaining_count'):
			$batch_id = isset($_POST['batch_id']) ? $_POST['batch_id'] : '';
			$inst_id = isset($_POST['inst_id']) ? $_POST['inst_id'] : '';
			$output = array();
			include_once('student.class.php');
			$student 	= new  student();
			$res = $student->batchRemainingCounts($batch_id, $inst_id);
			echo $res;
			break;

		case ('get_subcategory_list'):
			$cat_id = isset($_POST['cat_id']) ? $_POST['cat_id'] : '';
			echo $db->MenuItemsDropdown('expense_subcategory', 'SUBCATEGORY_ID', 'SUBCATEGORY', 'SUBCATEGORY_ID,SUBCATEGORY', '', ' WHERE CATEGORY_ID="' . $cat_id . '"');
			break;

		case ('delete_expense'):
			$id = isset($_POST['enq_id']) ? $_POST['enq_id'] : '';
			include_once('expense.class.php');
			$expense 	= new  expense();
			if ($expense->delete_expense($id)) {
				echo  "Expense deleted successfully.";
			} else {
				echo "Sorry! Deleting Expense failed.";
			}
			break;

		case ('delete_expense_category'):
			$id = isset($_POST['enq_id']) ? $_POST['enq_id'] : '';
			include_once('expense.class.php');
			$expense 	= new  expense();
			if ($expense->delete_expense_category($id)) {
				echo  "Expense category deleted successfully.";
			} else {
				echo "Sorry! Deleting Expense category failed.";
			}
			break;

		case ('delete_expense_subcategory'):
			$id = isset($_POST['enq_id']) ? $_POST['enq_id'] : '';
			include_once('expense.class.php');
			$expense 	= new  expense();
			if ($expense->delete_expense_subcategory($id)) {
				echo  "Expense subcategory deleted successfully.";
			} else {
				echo "Sorry! Deleting Expense subcategory failed.";
			}
			break;

			/* delete gallery file  */
		case ('delete_gallery_file'):
			$gallery_file_id = isset($_POST['gallery_file_id']) ? $_POST['gallery_file_id'] : '';
			if (!$db->delete_gallery_file($gallery_file_id)) {
				echo  "File deleted successfully.";
			} else {
				echo "Sorry! Deleting file failed.";
			}
			break;
			/* delete gallery   */
		case ('delete_gallery'):
			$gallery_id = isset($_POST['gallery_id']) ? $_POST['gallery_id'] : '';
			if (!$db->delete_gallery($gallery_id)) {
				echo  "Gallery deleted successfully.";
			} else {
				echo "Sorry! Deleting gallery failed.";
			}
			break;


		default:
			echo "Invalid request!";
			break;
	}
}
