<?php session_start();
ob_start();

include('database_results.class.php');
include('access.class.php');

$db 	= new  database_results();
$access = new  access();
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($action != '') {
	switch ($action) {
		case ('get_institute_list'):
			$state_id = isset($_POST['state_id']) ? $_POST['state_id'] : '';
			echo '<option>--Please Select---</option>';
			echo $db->MenuItemsDropdown('institute_details', 'INSTITUTE_ID', 'INSTITUTE_NAME', "INSTITUTE_ID,CONCAT(INSTITUTE_NAME,' - ',CITY) as INSTITUTE_NAME", '', " WHERE DELETE_FLAG = 0 AND STATE = '$state_id' ORDER BY INSTITUTE_ID ASC");
			exit();
			break;

			/* get city list on state change */
		case ('get_city_list'):
			$state_id = isset($_POST['state_id']) ? $_POST['state_id'] : '';
			echo '<option>--Select City---</option>';
			echo $db->MenuItemsDropdown('city_master', 'CITY_ID', 'CITY_NAME', 'CITY_ID,CITY_NAME', '', ' WHERE STATE_ID="' . $state_id . '"');
			break;
			/* get city list on state change */
		case ('search_course'):
			$search_str 	= isset($_POST['search_str']) ? $_POST['search_str'] : '';
			$search_view 	= isset($_POST['search_view']) ? $_POST['search_view'] : '';
			$output = '';

			$condition = ' AND A.COURSE_NAME LIKE "%' . $search_str . '%" OR B.AWARD LIKE "%' . $search_str . '%"';
			$res = $db->list_courses('', $condition, '');
			if ($res != '') {
				$srno = 1;
				while ($data = $res->fetch_assoc()) {
					$COURSE_ID 		= $data['COURSE_ID'];
					$COURSE_CODE 	= $data['COURSE_CODE'];
					$COURSE_DURATION = $data['COURSE_DURATION'];
					$COURSE_NAME 	= $data['COURSE_NAME'];
					$COURSE_FEES 	= $data['COURSE_FEES'];
					$COURSE_AWARD_NAME 	= $data['COURSE_AWARD_NAME'];
					$ACTIVE			= $data['ACTIVE'];
					$CREATED_BY 	= $data['CREATED_BY'];
					$CREATED_ON 	= $data['CREATED_ON'];
					$COURSE_NAME_MODIFY 	= $data['COURSE_NAME_MODIFY'];
					$COURSE_IMAGE 	= $data['COURSE_IMAGE'];


					$course_img_path = HTTP_HOST . '/resources/img/poetry.jpg';
					if ($COURSE_IMAGE != '')
						$course_img_path = HTTP_HOST . '/' . COURSE_MATERIAL_PATH . '/' . $COURSE_ID . '/thumb/' . $COURSE_IMAGE;

					$course_title = $COURSE_NAME;
					if ($COURSE_AWARD_NAME != '')
						$course_title = $COURSE_AWARD_NAME . ' IN ' . $COURSE_NAME;

					if ($COURSE_AWARD == 9)
						$course_title = $COURSE_NAME;
					$url_course_name = $db->to_prety_url($course_title);
					$course_link = HTTP_HOST . "/courses-grid/$COURSE_ID/$url_course_name";
					$course_title_trunc = $access->readmore($course_title, 40);


					if ($search_view == 'grid') {
						$output .= '<div class="col-lg-2 col-md-2">
							<div class="col-item">
								<div class="photo">
									<a href="' . $course_link . '"><img src="' . $course_img_path . '" alt="' . $course_title . '" style="height: 122px;" /></a>								
								</div>
								<div class="info">
									<div class="row">
										<div class="course_info col-md-12 col-sm-12" style="height: 50px;">
											<a href="' . $course_link . '"><h4>' . $course_title_trunc . '</h4></a>								
										</div>
									</div>
									<div class="separator clearfix">                                       
										<p class="btn btn-xs btn-default"><a href="javascript:void(0)" data-toggle="modal" data-target="#courseEnquiry" class="course-enquiry" data-coursename="' . $course_title . '" data-courseid="' . $COURSE_ID . '"><i class="icon-info"></i> Enquiry</a></p>
									</div>
								</div>
							</div>
							</div>';
					} else if ($search_view == 'list') {
						$output .= '<tr>
											<td>' . $srno . '</td>
											<td>' . $course_title . '</td>
											<td>' . $COURSE_DURATION . '</td>
											<td><a href="' . $course_link . '" class="button_top hidden-xs">Details</a></td>
											<td><p class="btn btn-xs btn-default"><a href="javascript:void(0)" data-toggle="modal" data-target="#courseEnquiry" class="course-enquiry" data-coursename="' . $course_title . '" data-courseid="' . $COURSE_ID . '"><i class="icon-info"></i> Enquiry</a></p></td>
										</tr>';
					}
					$srno++;
				}
			}

			echo ($output != '') ? $output : '<h5> No course found with your search !</h5>';
			break;

		case ('course_enquiry'):
			$result1	= $access->course_enquiry();
			$result = json_decode($result1, true);
			$success = isset($result['success']) ? $result['success'] : '';
			$message = isset($result['message']) ? $result['message'] : '';
			$errors = isset($result['errors']) ? $result['errors'] : '';
			if ($success == true) {
				$_SESSION['msg'] = $message;
				$_SESSION['msg_flag'] = $success;
				header('location:list-jobs');
			}
			echo $result1;
			break;
		case ('get_inst_course_fees'):
			$coursefee = 0;
			$inst_course_id = isset($_POST['inst_course_id']) ? $_POST['inst_course_id'] : '';
			$res = $db->get_inst_course_fees($inst_course_id);
			if ($res != 0) {
				$coursefee = $res;
			}
			echo $coursefee;
			break;
		case ('apply_coupon_code'):
			$couponcode = $db->test(isset($_POST['coupon_code']) ? $_POST['coupon_code'] : '');
			$course 	= isset($_POST['course']) ? $_POST['course'] : '';

			$result = $db->apply_coupon_code($couponcode, $course);
			echo $result;

			break;

		case ("get_inst_course_fees_enquiry"):
			$instcourseid = isset($_POST['instcourseid']) ? $_POST['instcourseid'] : '';
			$coursefees = '';
			$minimumamount = '';
			//$sql = "SELECT COURSE_FEES FROM institute_courses WHERE INSTITUTE_COURSE_ID= $instcourseid";
			$res = $db->get_inst_course_info($instcourseid);

			$output = array();

			if ($res != '') {
				//print_r($res);
				$COURSE_ID 			 = $res['COURSE_ID'];
				$MULTI_SUB_COURSE_ID = $res['MULTI_SUB_COURSE_ID'];

				if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
					$coursefees 	= isset($res['COURSE_MRP']) ? $res['COURSE_MRP'] : '0';
					$minimumamount 	= isset($res['MINIMUM_AMOUNT']) ? $res['MINIMUM_AMOUNT'] : '0';
				}
				if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
					$coursefees 	= isset($res['MULTI_SUB_COURSE_MRP']) ? $res['MULTI_SUB_COURSE_MRP'] : '0';
					$minimumamount 	= isset($res['MULTI_SUB_MINIMUM_AMOUNT']) ? $res['MULTI_SUB_MINIMUM_AMOUNT'] : '0';
				}
				$output = array('coursefees' => $coursefees, 'minimumamount' => $minimumamount);
			}
			echo json_encode($output);
			break;
	}
}
