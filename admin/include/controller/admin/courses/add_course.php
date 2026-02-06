<?php

$action = isset($_POST['add_course']) ? $_POST['add_course'] : '';

include_once('include/classes/course.class.php');

$course = new course();

if ($action != '') {

	$result = $course->add_course();

	$result = json_decode($result, true);

	$success = isset($result['success']) ? $result['success'] : '';

	$message = $result['message'];

	$errors = isset($result['errors']) ? $result['errors'] : '';

	if ($success == true) {

		$_SESSION['msg'] = $message;

		$_SESSION['msg_flag'] = $success;

		header('location:page.php?page=listCourse');
	}
}
