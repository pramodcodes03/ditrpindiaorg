<?php
$course_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_course']) ? $_POST['update_course'] : '';
include_once('include/classes/course.class.php');
$course = new course();
if ($action != '') {
	$result = $course->update_course($course_id);
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:listCourse');
	}
}
/* get course details */
$res = $course->list_courses($course_id, '');
if ($res != '') {
	$srno = 1;
	while ($data = $res->fetch_assoc()) {
		$COURSE_ID 		= $data['COURSE_ID'];
		$COURSE_CODE 	= $data['COURSE_CODE'];
		$COURSE_DURATION = $data['COURSE_DURATION'];
		$COURSE_NAME 	= $data['COURSE_NAME'];
		$COURSE_SUBJECTS 	= $data['COURSE_SUBJECTS'];
		$COURSE_AWARD 	= $data['COURSE_AWARD'];
		$COURSE_DETAILS = $data['COURSE_DETAILS'];
		$COURSE_ELIGIBILITY 	= $data['COURSE_ELIGIBILITY'];
		$COURSE_FEES 	= $data['COURSE_FEES'];
		$COURSE_MRP 	= $data['COURSE_MRP'];
		$MINIMUM_AMOUNT 	= $data['MINIMUM_AMOUNT'];
		$ACTIVE			= $data['ACTIVE'];
		$CREATED_BY 	= $data['CREATED_BY'];
		$CREATED_ON 	= $data['CREATED_ON'];
		$UPDATED_BY 	= $data['UPDATED_BY'];
		$UPDATED_ON 	= $data['UPDATED_ON'];
		$COURSE_IMAGE 	= $data['COURSE_IMAGE'];
		$IS_MULTIPLE 	= $data['IS_MULTIPLE'];
		$DISPLAY_FEES 	= $data['DISPLAY_FEES'];
		$VIDEO1 	= $data['VIDEO1'];
		$VIDEO2 	= $data['VIDEO2'];
	}
}
